<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Common\FunctionsModel;
use App\Common\Authenticator;
use App\Common\Json;
use App\Config\Configuration as cfg;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\Authentication;
use App\Models\Seller;
use App\Helpers\MessageBrokerHttp;

class Users extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'users';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'full_name', 'cpf', 'phone_number', 'email', 'password'
    ];

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = true;

    const base_url = "notification-service-picpay/public/";

    public static function findByNameOrUsername($query)
    {
        return self::select('users.*')
            ->leftJoin('authentication', 'authentication.users_id', '=', 'users.id')
            ->where('authentication.username', 'like', '%' . $query . '%')
            ->orWhere('users.full_name', 'like', '%' . $query . '%')
            ->get();
    }

    /**
     * @param Request $request
     * @return Response
     */
    public function register(Request $request)
    {
        $data = (object) $request->all();

        if (
            !FunctionsModel::inputValidate($data, 'users_schema.json') ||
            !FunctionsModel::inputValidate($data, 'auth_schema.json')
        ) {
            return response([
                "message" => "There are wrong fields in submission",
                "status" => "error",
                "error" => Json::getValidateErrors()
            ], 400);
        }

        $duplicate = self::notDuplicate($data);
        if ($duplicate) {
            return $duplicate;
        }

        try {
            $datas = [
                "full_name" => $data->name,
                "cpf" => $data->cpf,
                "phone_number" => $data->phone,
                "email" => $data->email,
                "account_type" => $data->typeAccount
            ];

            $result = self::create($datas);

            self::insertAuthentication($data, $result->id);

            if ($data->typeAccount == 'SELLER') {
                self::insertSeller($data, $result->id);
            }

            self::sendEmail($data);

            return response([
                "message" => "Registry created successfully",
                "status" => "success",
                "data" => $result
            ], 201);
        } catch (\Exception $ex) {
            return response([
                "message" => $ex->getMessage(),
                "status" => "warning"
            ], 400);
        }
    }

    private static function insertAuthentication($data, $userId)
    {
        $dataAuth = [
            "username" =>  $data->username,
            "password" => password_hash($data->password . cfg::SALT_KEY, PASSWORD_DEFAULT),
            "active" => 'ENABLE',
            "users_id" => $userId
        ];
        $result = Authentication::create($dataAuth);
        if (!$result) {
            return false;
        }
        return true;
    }

    private static function insertSeller($data, $userId)
    {
        $dataSeller = [
            "social_name" => $data->socialName,
            "fantasy_name" => $data->fantasyName,
            "cnpj" => $data->cnpj,
            "users_id" => $userId
        ];
        $result = Seller::create($dataSeller);
        if (!$result) {
            return false;
        }
        return true;
    }

    private static function sendEmail($data)
    {
        $http = new MessageBrokerHttp();
        $http->execRequest('POST', self::base_url . "email/welcome", $data);
    }

    /**
     * @param Request $request
     * @return Response
     */
    public function edit(Request $request, $id)
    {
        $data = $request->all();

        return response($data);

        if (!FunctionsModel::inputValidate($data, 'users_schema.json')) {
            return response([
                "message" => "There are wrong fields in submission",
                "status" => "error",
                "error" => Json::getValidateErrors()
            ], 400);
        }

        try {
            $user = self::find($id);

            if (!$user) {
                return response([
                    "message" => "User not found",
                    "status" => "error",
                    "data" => []
                ], 400);
            }

            $user->full_name = $data->name;
            $user->cpf = $data->cpf;
            $user->phone_number = $data->phone;
            $user->email = $data->email;

            $user->save();

            if ($user->type_account == 'SELLER' && $data->socialName || $data->fantasyName) {
                $seller = Seller::where('users_id', $id)->first();
                $seller->social_name = $data->socialName;
                $seller->fantasy_name = $data->fantasyName;
                $seller->save();
            }

            return response([
                "message" => "Registry updated successfully",
                "status" => "success",
                "data" => $user
            ], 200);
        } catch (\Exception $ex) {
            return response([
                "message" => $ex->getMessage(),
                "status" => "error"
            ], 400);
        }
    }

    public function login(Request $request)
    {
        $data = (object) $request->all();
        try {
            $entity = self::join('authentication', 'authentication.users_id', '=', 'users.id')
                ->where('authentication.username', 'like', '%' . $data->username . '%')
                ->first();

            if (
                !$entity ||
                !password_verify($data->password . cfg::SALT_KEY, $entity->password) ||
                !$entity->active == 'ENABLE'
            ) {
                return response([
                    "message" => "Invalid User",
                    "status" => "error",
                    "data" => []
                ], 401);
            }

            $userData = [
                'expiration_sec' => cfg::EXPIRATE_TOKEN,
                'host' => cfg::HOST_DEV,
                'userdata' => [
                    "id" => $entity->id,
                    "name" => $entity->full_name
                ]
            ];

            return response([
                "message" => "User Authorized",
                "status" => "success",
                "data" => [
                    "userId" => $entity->id,
                    "userName" => $entity->full_name,
                    "token" => Authenticator::generateToken($userData)
                ]
            ], 200);
        } catch (\Exception $ex) {
            return response([
                "message" => $ex->getMessage(),
                "status" => "error",
                "data" => ""
            ], 500);
        }
    }

    private static function notDuplicate($data)
    {
        $result = self::where('cpf', $data->cpf)->first();
        if ($result) {
            return response([
                "message" => "The reported CPF has already been registered",
                "status" => "warning"
            ], 400);
        }

        $result = self::where('email', $data->email)->first();
        if ($result) {
            return response([
                "message" => "The reported email has already been registered",
                "status" => "warning"
            ], 400);
        }
    }
}
