<?php

namespace App\Helpers;

use App\Config\Configuration as cfg;

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
        $baseUrl = "http://" . cfg::HOST_DEV . $url;

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
                curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
                curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "DELETE");
                break;
        }

        $response = curl_exec($curl);

        if ($response === FALSE) {
            die('Curl failed: ' . curl_error($curl));
        }

        curl_close($curl);

        return $response;
    }
}
