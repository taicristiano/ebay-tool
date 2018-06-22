<?php

namespace App\Services;

use Illuminate\Support\Facades\Lang;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use App\Models\SettingPolicy;

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

    /**
     * get data setting policies
     * @return array
     */
    public function getDataSettingPolicies()
    {
        $userId            = Auth::user()->id;
        $settingPolicyData = $this->settingPolicy->getSettingPolicyOfUser($userId);
        $shippingType[null] = null;
        $paymentType[null]  = null;
        $returnType[null]   = null;
        foreach ($settingPolicyData as $key => $policy) {
            if ($policy->policy_type == SettingPolicy::TYPE_SHIPPING) {
                $shippingType[$policy->id] = $policy->policy_name;
            } elseif ($policy->policy_type == SettingPolicy::TYPE_PAYMENT) {
                $paymentType[$policy->id] = $policy->policy_name;
            } else {
                $returnType[$policy->id] = $policy->policy_name;
            }
        }
        return [
            'shipping' => $shippingType,
            'payment'  => $paymentType,
            'return'   => $returnType
        ];
    }

    /**
     * get setting shipping of user
     * @param  array $input
     * @return array
     */
    public function getSettingShippingOfUser($input)
    {
        $height                = !empty($input['height']) ? $input['height'] : 0;
        $width                 = !empty($input['width']) ? $input['width'] : 0;
        $length                = !empty($input['length']) ? $input['length'] : 0;
        $sizeOfProduct         = $length + $height + $width;
        $userId                = Auth::user()->id;
        $settingShipping       = $this->settingShipping->getSettingShippingOfUser($userId);
        $settingShippingOption[null] = null;
        foreach ($settingShipping as $key => $item) {
            $sideMaxSize = $item->side_max_size;
            if ($sizeOfProduct <= $item->max_size &&
                $height < $sideMaxSize &&
                $length <= $sideMaxSize &&
                $width <= $sideMaxSize
            ) {
                $settingShippingOption[$item->id] = $item->shipping_name;
            }
        }
        if (count($settingShippingOption) == 1) {
            $settingShipping = $this->settingShipping->findSettingShippingMaxSizeOfUser($userId);
            $settingShippingOption[$settingShipping->id] = $settingShipping->shipping_name;
        }
        return $settingShippingOption;
    }

    /**
     * format store info
     * @param  array $stores
     * @return array
     */
    public function formatStoreInfo($stores)
    {
        $arrayCategoryFee = ['standard_fee_rate', 'basic_fee_rate', 'premium_fee_rate', 'anchor_fee_rate'];
        $result = [];
        foreach ($stores as $key => $store) {
            $result[$store->id] = $arrayCategoryFee[$key];
        }
        return $result;
    }

    /**
     * format data page product
     * @param  array $data
     * @return array
     */
    public function formatDataPageProduct($data)
    {
        $data['duration']['option']      = $this->product->getDurationOption();
        $data['duration']['value']       = $data['dtb_item']['duration'];
        $settingShippingOption           = $this->getSettingShippingOfUser($data['dtb_item']);
        $data['setting_shipping_option'] = $settingShippingOption;
        $data['setting_shipping_selected'] = $data['dtb_item']['temp_shipping_method'];
        $data['dtb_setting_policies']    = $this->getDataSettingPolicies();
        $userId                          = Auth::user()->id;
        $settingTemplate                 = $this->settingTemplate->getByUserId($userId);
        $data['setting_template']        = $this->formatSettingTemplate($settingTemplate);
        return $data;
    }

    /**
     * format setting template
     * @param  array $settingTemplate
     * @return array
     */
    public function formatSettingTemplate($settingTemplate)
    {
        $result[null] = null;
        foreach ($settingTemplate as $item) {
            $result[$item['id']] = $item['title'];
        }
        return $result;
    }

    /**
     * calculator profit detail
     * @param  array $data
     * @return void
     */
    public function calculatorDetail(&$data)
    {
        $exchangeRate                   = $this->exchangeRate->getExchangeRateLatest();
        $userId                         = Auth::user()->id;
        $settingInfo                    = $this->setting->getSettingOfUser($userId);
        $storeIdOfUser                  = $settingInfo->store_id;
        $stores                         = $this->mtbStore->getAllStore();
        $storeInfo                      = $this->formatStoreInfo($stores);
        $typeFee                        = $storeInfo[$storeIdOfUser];
        $sellPriceYen                   = round($data['dtb_item']['price'] * ($exchangeRate->rate - $settingInfo->ex_rate_diff), 2);
        $data['dtb_item']['ebay_fee']   = round($data['dtb_item']['price'] * $this->categoryFee->getCategoryFeeByCategoryId($data['dtb_item']['category_id'])->$typeFee / 100, 2);
        $ebayFeeYen                     = round($data['dtb_item']['ebay_fee'] * ($exchangeRate->rate - $settingInfo->ex_rate_diff), 2);
        $data['dtb_item']['paypal_fee'] = round($settingInfo->paypal_fee_rate  * $sellPriceYen / 100 + $settingInfo->paypal_fixed_fee, 2);
        if ($data['istTypeAmazon']) {
            $data['dtb_item']['profit'] = round((float)$sellPriceYen - $ebayFeeYen - $data['dtb_item']['paypal_fee'] - $data['dtb_item']['ship_fee'] - (float)$data['dtb_item']['buy_price'] * $settingInfo->gift_discount / 100, 2);
        } else {
            $data['dtb_item']['profit'] = round((float)$sellPriceYen - $ebayFeeYen - $data['dtb_item']['paypal_fee'] - $data['dtb_item']['ship_fee'] - (float)$data['dtb_item']['buy_price'], 2);
        }
    }
}
