<?php

namespace App\Services;

use Lang;
use Carbon\Carbon;

class CommonService
{
    /**
     * validate email
     * @param  string $email 
     * @return boolean
     */
    public function validateEmail($email)
    {
        if (!$email) {
            return false;
        }
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return false;
        }
        return true;
    }

    /**
     * format date
     * @param  string $format
     * @param  string $date
     * @return string
     */
    public function formatDate($format, $date)
    {
        if (!$date) {
            return;
        }
        return Carbon::parse($date)->format($format);
    }

    /**
     * get status flag
     * @param  integer $value
     * @return string
     */
    public function getStatusFlag($value)
    {
        return $value ? Lang::get('view.on') : Lang::get('view.off');
    }

    /**
     * call api with header and body
     * @param  array $header
     * @param  string $body
     * @param  string $url
     * @param  string $type
     * @return array
     */
    public function callApi($header, $body, $url, $type)
    {
        $client = new \GuzzleHttp\Client();
        $result = $client->$type(
            $url, [
                'headers' => $header,
                'body'    => $body,
            ]
        );
        $result = $result ->getBody()->getContents();
        $xml    = simplexml_load_string($result, "SimpleXMLElement", LIBXML_NOCDATA);
        $json   = json_encode($xml);
        return json_decode($json, TRUE);
    }
}