<?php

namespace App\Services;

use App\Services\CommonService;
use Illuminate\Support\Facades\Session;
use Auth;

class ProductService extends CommonService
{

    /**
     * api get session id
     * @return string
     */
    public function apiGetItemEbayInfo($itemId)
    {
        $url    = config('api_info.api_ebay_get_item') . $itemId;
        $data = $this->callApi(null, null, $url, 'get');
        $response['status'] = false;
        if ($data['Ack'] == 'Failure') {
            return response()->json($response);
        }
        $response['status'] = true;
        $response['data'] = view('admin.product.component.item_ebay_info', compact('data'))->render();
        return response()->json($response);
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