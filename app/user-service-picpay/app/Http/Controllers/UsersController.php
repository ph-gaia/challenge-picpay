<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Users;
use Illuminate\Http\Request;

class UsersController extends Controller
{

    /**
     * @param Request $request
     * @return Response
     */
    public function findByNameOrUsername(Request $request)
    {
        $query = $request->get('q');
        $result = Users::findByNameOrUsername($query);

        if (count($result) == 0) {
            return response([
                "status" => "warning",
                "message" => "No records found",
                "data" => []
            ], 400);
        }

        return response([
            "status" => "success",
            "message" => "",
            "data" => $result
        ], 200);
    }

    /**
     * @param $id object identifier
     * @return Response
     */
    public function findById(int $id)
    {
        $result = Users::find($id);

        if (!$result) {
            return response([
                "status" => "error",
                "message" => "User not found",
                "data" => []
            ], 400);
        }

        return response([
            "status" => "succcess",
            "message" => "",
            "data" => $result
        ], 200);
    }

    /**
     * @param Request $request
     * @return Response
     */
    public function create(Request $request)
    {
        $model = new Users();
        return $model->register($request);
    }

    /**
     * @param Request $request
     * @param $id object identifier
     * @return Response
     */
    public function update(Request $request, int $id)
    {
        $model = new Users();
        return $model->edit($request, $id);
    }

    /**     
     * @param $id object identifier
     * @return Response
     */
    public function destroy($id)
    {
        $user = Users::find($id);

        if (!$user) {
            return response([
                "message" => "User not found",
                "status" => "error",
                "data" => []
            ], 400);
        }

        $user->delete();

        return response([
            "status" => "success",
            "message" => "",
            "data" => []
        ], 204);
    }

    public function login()
    {
        
    }
}
