<?php

namespace App\Helpers;

class MessageBrokerHttp
{

    /**
     * Method responsible for communicating with the API with all HTTP verbs
     *
     * @param $method verb http
     * @param $url requisition address
     * @param array Header request header configuration set
     * @return json
     */
    public function execRequest($method, $url, $data, $header = null)
    {
        $baseUrl = "http://192.168.0.7:4000/" . $url;

        $curl = curl_init($baseUrl);

        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);

        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

        if (!empty($header)) {
            curl_setopt($curl, CURLOPT_HTTPHEADER, $header);
        }

        switch ($method) {
            case "GET":
                curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
                curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "GET");
                break;
            case "POST":
                curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
                curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "POST");
                break;
            case "PUT":
                curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
                curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "PUT");
                break;
            case "DELETE":
                curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "DELETE");
                curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
                break;
        }

        $response = curl_exec($curl);

        $httpcode = curl_getinfo($curl, CURLINFO_HTTP_CODE);

        if ($response === FALSE) {
            die('Curl failed: ' . curl_error($curl));
        }

        curl_close($curl);

        $result = [
            "data" => $response,
            "status" => $httpcode
        ];

        return $result;
    }
}
