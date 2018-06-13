<?php

namespace App\Services;

use Illuminate\Support\Facades\Lang;
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
            return null;
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
     * @param  boolean $isFormParams
     * @return array
     */
    public function callApi($header, $body, $url, $type, $isFormParams = false)
    {
        $bodyRequest = [
            $isFormParams ? 'form_params' : 'body'    => $body,
        ];
        if (!$isFormParams) {
            $bodyRequest['headers'] = $header;
        }
        $client = new \GuzzleHttp\Client();
        $result = $client->$type($url, $bodyRequest);
        $result = $result ->getBody()->getContents();
        $xml    = simplexml_load_string($result, "SimpleXMLElement", LIBXML_NOCDATA);
        $json   = json_encode($xml);
        return json_decode($json, true);
    }

    /**
     * excute export csv
     * @param  string $fileName
     * @param  array $columns
     * @param  array $rowList
     * @return \Symfony\Component\HttpFoundation\StreamedResponse
     */
    public function excuteExportCsv($fileName, $columns, $rowList)
    {
        try {
            $headers = array(
                'Content-type' => 'text/csv',
                'Content-Disposition' => 'attachment; filename=' . $fileName,
                'Pragma' => 'no-cache',
                'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
                'Expires' => '0'
            );

            $callback = function () use ($columns, $rowList) {
                $file = fopen('php://output', 'w');
                fputs($file, "\xEF\xBB\xBF");
                fputcsv($file, $columns);

                foreach ($rowList as $row) {
                    fputcsv($file, $row);
                }

                fclose($file);
            };

            return response()->stream($callback, 200, $headers);
        } catch (Exception $e) {
            logger(__METHOD__ . ': ' . $e->getMessage());
            abort('500');
        }
    }

    /**
     * get policy name by id
     * @param  integer $id
     * @param  array $settingPolicyData
     * @return string
     */
    public function getPolicyNameById($id, $settingPolicyData)
    {
        foreach ($settingPolicyData as $key => $policy) {
            if ($policy->id == $id) {
                return $policy->policy_name;
            }
        }
        return null;
    }
}
