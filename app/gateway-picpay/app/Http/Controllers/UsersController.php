<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Helpers\MessageBroker;


class UsersController extends Controller
{

    /**
     * @param Request $request
     * @return Response
     */
    public function findByNameOrUsername(Request $request)
    {
        $query = $request->get('q');
    }

    /**
     * @param $id object identifier
     * @return Response
     */
    public function findById(int $id)
    {
        //$result = Users::find($id);


    }

    /**
     * @param Request $request
     * @return Response
     */
    public function create(Request $request)
    { }

    /**
     * @param Request $request
     * @param $id object identifier
     * @return Response
     */
    public function update(Request $request, int $id)
    { }

    /**     
     * @param $id object identifier
     * @return Response
     */
    public function destroy($id)
    { }

    /**
     * @param Request $request
     * @return Response
     */
    public function login(Request $request)
    {
        $rabbitmq = new MessageBroker();

        $data = (object) $request->all() ?: [];
        $data = [
            "username" => $data->username,
            "password" => $data->password
        ];

        return $rabbitmq->SendAndListen("auth", $data);
    }
}
