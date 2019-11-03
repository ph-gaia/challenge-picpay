<?php

namespace App\Common;

use App\Config\Configuration as cfg;
use App\Common\Json;

class FunctionsModel
{

    /**
     * Validate the inpute of request
     *
     * @since 1.0
     * @param \stdClass|array $data
     * @param string $jsonShemaFile
     * @return bool
     */
    public static function inputValidate($data, $jsonShemaFile)
    {
        $fullPath = base_path() . cfg::JSON_SCHEMA . cfg::DS;

        $jsonSchema = $fullPath . $jsonShemaFile;

        if (Json::validate($data, $jsonSchema)) {
            return true;
        }

        return false;
    }

    /**
     * Return the Response Object configured with common error
     *
     * @since 1.0
     * @param Response $response
     * @param \Exception $ex
     * @return Response
     */
    protected static function commonError(Response $response, \Exception $ex)
    {
        $data = [
            "message" => "Somethings are wrong",
            "status" => "error",
            "dev_error" => $ex->getMessage()
        ];

        return $response->withJson($data, 500);
    }
}
