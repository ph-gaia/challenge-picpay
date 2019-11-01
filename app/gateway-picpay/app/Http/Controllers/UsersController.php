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
        $rabbit = new MessageBroker();

        $data = [
            "query" => $request->get('q'),
        ];

        if (!$data['query']) {
            $result = $rabbit->SendAndListen("users.findall", []);
        } else {
            $result = $rabbit->SendAndListen("users.findname", $data);
        }

        return response($result)->header('Content-Type', 'application/json');
    }

    /**
     * @param $id object identifier
     * @return Response
     */
    public function findById(int $id)
    {
        $data = [
            "query" => $id,
        ];

        $rabbit = new MessageBroker();
        $result = $rabbit->SendAndListen("users.find", $data);

        return response($result)->header('Content-Type', 'application/json');
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

        $rabbit = new MessageBroker();
        $result = $rabbit->SendAndListen("users.register", $data);

        return response($result)->header('Content-Type', 'application/json');
    }

    /**
     * @param Request $request
     * @param $id object identifier
     * @return Response
     */
    public function update(Request $request, int $id)
    {
        $data = [
            "id" => $id,
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

        $rabbit = new MessageBroker();
        $result = $rabbit->SendAndListen("users.update", $data);

        return response($result)->header('Content-Type', 'application/json');
    }

    /**     
     * @param $id object identifier
     * @return Response
     */
    public function destroy($id)
    {
        $data = [
            "query" => $id
        ];

        $rabbit = new MessageBroker();
        $result = $rabbit->SendAndListen("users.delete", $data);

        return response($result)->header('Content-Type', 'application/json');
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

        $rabbitmq = new MessageBroker();
        return $rabbitmq->SendAndListen("auth", $data)->header('Content-Type', 'application/json');
    }
}
