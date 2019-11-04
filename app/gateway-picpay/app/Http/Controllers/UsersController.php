<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Helpers\MessageBrokerHttp;

class UsersController extends Controller
{

    const base_url = "/user-service-picpay/public/api/";

    /**
     * @param Request $request
     * @return Response
     */
    public function findByNameOrUsername(Request $request)
    {
        $http = new MessageBrokerHttp();

        $data = "";
        if ($request->has('q')) {
            $data = "?query=" . $request->get('q');
        }

        $result = $http->execRequest('GET', self::base_url . "users" . $data, []);

        return response($result['data'], $result['status'])->header('Content-Type', 'application/json');
    }

    /**
     * @param $id object identifier
     * @return Response
     */
    public function findById(int $id)
    {
        $http = new MessageBrokerHttp();

        $result = $http->execRequest('GET', self::base_url . "users/" . $id, []);

        return response($result['data'], $result['status'])->header('Content-Type', 'application/json');
    }

    /**
     * @param Request $request
     * @return Response
     */
    public function create(Request $request)
    {
        $data = [
            "name" => $request->get('name'),
            "cpf" => $request->get('cpf'),
            "email" => $request->get('email'),
            "phone" => $request->get('phone'),
            "typeAccount" => $request->get('typeAccount'),
            "username" => $request->get('username'),
            "password" => $request->get('password'),
            "socialName" => $request->get('socialName'),
            "fantasyName" => $request->get('fantasyName'),
            "cnpj" => $request->get('cnpj')
        ];

        $http = new MessageBrokerHttp();
        $result = $http->execRequest('POST', self::base_url . "users", $data);

        return response($result['data'], $result['status'])->header('Content-Type', 'application/json');
    }

    /**
     * @param Request $request
     * @param $id object identifier
     * @return Response
     */
    public function update(Request $request, int $id)
    {
        $data = [
            "name" => $request->get('name'),
            "cpf" => $request->get('cpf'),
            "email" => $request->get('email'),
            "phone" => $request->get('phone'),
            "typeAccount" => $request->get('typeAccount'),
            "username" => $request->get('username'),
            "password" => $request->get('password'),
            "socialName" => $request->get('socialName'),
            "fantasyName" => $request->get('fantasyName'),
            "cnpj" => $request->get('cnpj')
        ];

        $http = new MessageBrokerHttp();
        $result = $http->execRequest('PUT', self::base_url . "users/" . $id, $data);

        return response($result['data'], $result['status'])->header('Content-Type', 'application/json');
    }

    /**     
     * @param $id object identifier
     * @return Response
     */
    public function delete($id)
    {
        $http = new MessageBrokerHttp();
        $result = $http->execRequest('DELETE', self::base_url . "users/" . $id, []);

        return response($result['data'], $result['status'])->header('Content-Type', 'application/json');
    }

    /**
     * @param Request $request
     * @return Response
     */
    public function login(Request $request)
    {
        $data = (object) $request->all() ?: [];
        $data = [
            "username" => $data->username,
            "password" => $data->password
        ];

        $http = new MessageBrokerHttp();
        $result = $http->execRequest('POST', self::base_url . "login", $data);

        return response($result['data'], $result['status'])->header('Content-Type', 'application/json');
    }
}
