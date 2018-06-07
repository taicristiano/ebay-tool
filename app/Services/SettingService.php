<?php

namespace App\Services;

use Auth;

class SettingService extends CommonService
{
    /**
     * get option stores
     * @param  array object $stores
     * @return array
     */
    public function getOptionStores($stores)
    {
        $results = [];
        foreach ($stores as $store) {
            $results[$store->id] = $store->name;
        }
        return $results;
    }

    /**
     * format data setting
     * @param  array $data
     * @return array
     */
    public function formatDataSetting($data)
    {
        unset($data['_token']);
        unset($data['id']);
        return $data;
    }

    /**
     * format expiration time
     * @param  string $time
     * @return string
     */
    public function formatExpirationTime($time)
    {
        if (!$time) {
            return null;
        }
        $time = date_create($time);
        return date_format($time, "Y/m/d H:i:s");
    }

    /**
     * api get session id
     * @return string
     */
    public function apiGetSessionId()
    {
        $headers     = config('api_info.header_api_fetch_get_session_id');
        $bodyRequest = config('api_info.body_request_api_fetch_get_session_id');
        $url         = config('api_info.api_common');
        $result = $this->callApi($headers, $bodyRequest, $url, 'post');
        $sessionId = $result['SessionID'];
        if (session()->has('session_id')) {
            session()->forget('session_id');
        }
        session(['session_id' => $sessionId]);
        return $sessionId;
    }

    /**
     * call api fetch token
     * @return array
     */
    public function apiFetchToken()
    {
        $headers     = config('api_info.header_api_fetch_token');
        $bodyRequest = str_replace('session_id', session('session_id'), config('api_info.body_request_api_fetch_token'));
        $url         = config('api_info.api_common');
        $result      = $this->callApi($headers, $bodyRequest, $url, 'post');
        return [
            'ebay_access_token' => $result['eBayAuthToken'],
            'expire_date'       => $this->formatExpirationTime($result['HardExpirationTime'])
        ];
    }

    /**
     * check display button get token
     * @return boolean
     */
    public function checkDisplayButtonGetToken()
    {
        $user = Auth::user();
        if ($user->ebay_access_token &&
            $user->expire_date &&
            time() < strtotime($user->expire_date)) {
            return false;
        }
        return true;
    }

    /**
     * call api get policy
     * @return array
     */
    public function apiGetPolicy()
    {
        $user    = Auth::user();
        $headers = config('api_info.header_api_get_policy');
        $headers['X-EBAY-SOA-SECURITY-TOKEN'] = $user->ebay_access_token;
        $url     = config('api_info.api_get_policy');
        $result  = $this->callApi($headers, null, $url, 'get');
        return $this->formatDataPolicy($result);
    }

    /**
     * format data policy
     * @param array $data
     * @return array
     */
    public function formatDataPolicy($data)
    {
        $result = [];
        if (!empty($data['paymentProfileList'])) {
            $payment                       = $data['paymentProfileList']['PaymentProfile'];
            $dataPayment['policy_name']    = $payment['profileName'];
            $dataPayment['policy_type']    = $payment['profileType'];
            $dataPayment['policy_content'] = json_encode($payment);
            $result[]                      = $dataPayment;
        }

        if (!empty($data['returnPolicyProfileList'])) {
            $return                       = $data['returnPolicyProfileList']['ReturnPolicyProfile'];
            $dataReturn['policy_name']    = $return['profileName'];
            $dataReturn['policy_type']    = $return['profileType'];
            $dataReturn['policy_content'] = json_encode($return);
            $result[]                     = $dataReturn;
        }

        if (!empty($data['shippingPolicyProfile'])) {
            $shipping                       = $data['shippingPolicyProfile']['ShippingPolicyProfile'];
            $dataShipping['policy_name']    = $shipping['profileName'];
            $dataShipping['policy_type']    = $shipping['profileType'];
            $dataShipping['policy_content'] = json_encode($shipping);
            $result[]                       = $dataShipping;
        }

        return $result;
    }
}
