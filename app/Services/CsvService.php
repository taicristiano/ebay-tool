<?php

namespace App\Services;

use Excel;
use Lang;
use DB;
use Carbon\Carbon;
use App\Models\User;
use App\Models\Authorization;
use App\Models\Setting;
use App\Models\ShippingFee;
use App\Models\SettingShipping;
use Exception;
use App\Services\CommonService;
use Illuminate\Support\Facades\Hash;

class CsvService extends CommonService
{
    protected $user;
    protected $authorization;
    protected $setting;
    protected $shippingFee;
    protected $settingShipping;

    public function __construct(
        User $user,
        Authorization $authorization,
        Setting $setting,
        ShippingFee $shippingFee,
        SettingShipping $settingShipping
    )
    {
        $this->user            = $user;
        $this->authorization   = $authorization;
        $this->setting         = $setting;
        $this->shippingFee     = $shippingFee;
        $this->settingShipping = $settingShipping;
    }

    /**
     * format data exprot csv
     * @param  array $data
     * @return array
     */
    public function formatDataExprotCsv($data)
    {
        return $data;
    }

    /**
     * generate column export csv
     * @return array
     */
    public static function generateColumnExportCsvFull()
    {
        return array(
            'start_date'       => Lang::get('view.start_date'),
            'user_code'        => Lang::get('view.user_code'),
            'user_name'        => Lang::get('view.user_name'),
            'name_kana'        => Lang::get('view.name_kana'),
            'email'            => Lang::get('view.email'),
            'tel'              => Lang::get('view.tel'),
            'ebay_account'     => Lang::get('view.ebay_account'),
            'type'             => Lang::get('view.type'),
            'introducer_id'    => Lang::get('view.introducer_id'),
            'yahoo_info'       => Lang::get('view.yahoo_info'),
            'amazon_info'      => Lang::get('view.amazon_info'),
            'monitoring'       => Lang::get('view.monitoring_price'),
            'regist_limit'     => Lang::get('view.regist_limit'),
            'post_limit'       => Lang::get('view.post_limit'),
            'memo'             => Lang::get('view.memo'),
        );
    }

    /**
     * generate column export csv
     * @return array
     */
    public static function generateColumnExportCsvSimple()
    {
        return array(
            'tel'           => Lang::get('view.tel'),
            'user_code'     => Lang::get('view.user_code'),
            'email'         => Lang::get('view.email'),
            'address'       => Lang::get('view.address'),
            'memo'          => Lang::get('view.memo'),
        );
    }

    /**
     * generate data exprot csv
     * @param  string $type
     * @param  arrray $data
     * @return array
     */
    public function generateDataExportCsv($type, $data)
    {
        if ($type == 'full') {
            $columnTitle = $this->generateColumnExportCsvFull();
        } else {
            $columnTitle = $this->generateColumnExportCsvSimple();
        }
        $rowOrder = 0;
        if (count($data)) {
            if ($type == 'full') {
                foreach ($data as $key => $item) {
                    $row                                = [];
                    $row[$columnTitle['start_date']]    = self::formatDate('Y/m/d', $item['start_date']);
                    $row[$columnTitle['user_code']]     = $item['user_code'];
                    $row[$columnTitle['user_name']]     = $item['user_name'];
                    $row[$columnTitle['name_kana']]     = $item['name_kana'];
                    $row[$columnTitle['email']]         = $item['email'];
                    $row[$columnTitle['tel']]           = $item['tel'];
                    $row[$columnTitle['ebay_account']]  = $item['ebay_account'];
                    $row[$columnTitle['type']]          = $this->user->getTypeOptions()[$item['type']];
                    $row[$columnTitle['introducer_id']] = $item['introducer_id'];
                    $row[$columnTitle['yahoo_info']]    = self::getStatusFlag($item['yahoo_info']);
                    $row[$columnTitle['amazon_info']]   = self::getStatusFlag($item['amazon_info']);
                    $row[$columnTitle['monitoring']]    = self::getStatusFlag($item['monitoring']);
                    $row[$columnTitle['regist_limit']]  = $item['regist_limit'];
                    $row[$columnTitle['post_limit']]    = $item['post_limit'];
                    $row[$columnTitle['memo']]          = $item['memo'];
                    $results[]                          = $row;
                }
            } else {
                foreach ($data as $key => $item) {
                    $row                            = [];
                    $row[$columnTitle['tel']]       = $item['tel'];
                    $row[$columnTitle['user_code']] = $item['user_code'];
                    $row[$columnTitle['email']]     = $item['email'];
                    $row[$columnTitle['address']]   = '';
                    $row[$columnTitle['memo']]      = $item['memo'];
                    $results[]                      = $row;
                }
            }
        } else {
            foreach ($columnTitle as $key => $value) {
                $row[$columnTitle[$key]] = null;
            }
            $results[] = $row;
        }
        return $results;
    }

    /**
     * export csv
     * @param  string $type
     * @param  array $data
     * @return file
     */
    public function exportCsv($type, $data)
    {
        $fileName = 'List user' . date('Y-m-d H:i');
        $data = $this->generateDataExportCsv($type, $data);
        return Excel::create($fileName, function($excel) use ($data) {
            $excel->sheet('List user', function($sheet) use ($data) {
                $sheet->fromArray($data);
            });
        })->download('csv');
    }

    /**
     * upload csv
     * @param  file $file
     * @return boolean
     */
    public function uploadCsv($file)
    {
        try {
            DB::beginTransaction();
            Excel::load($file, function($reader) {
                $results = $reader->toArray();
                foreach ($results as $key => $item) {
                    if (self::checkExistEmail($item['email'])) {
                        throw new Exception();
                    }
                    // insert user
                    $item['type'] = array_keys($this->user->getTypeOptions(), $item['type'])[0];
                    $dataInsert = self::formatDataCsvInsert($item);
                    $userId = $this->user->insertGetId($dataInsert['data_user']);

                    // insert authorization
                    if ($item['type'] == $this->user->getTypeGuestAdmin()) {
                        $dataInsert['data_auth']['user_id'] = $userId;
                        $this->authorization->insert($dataInsert['data_auth']);
                    }

                    if ($this->user->isSetting($item['type'])) {
                        // insert setting user
                        $dataSetting['user_id'] = $userId;
                        $dataSetting['created_at'] = date('Y-m-d H:i:s');
                        $dataSetting['updated_at'] = date('Y-m-d H:i:s');
                        $this->setting->insert($dataSetting);

                        // insert setting shipping and setting fee
                        $dataShipping = $this->settingShipping->getDataMaster($userId);
                        foreach ($dataShipping as $key => $shipping) {
                            $settingShippingId = $this->settingShipping->insertGetId($shipping);
                            if ($shipping['shipping_name'] == 'EMS') {
                                $dataShippingFee = $this->shippingFee->getDataMaster($settingShippingId, true);
                            } else {
                                $dataShippingFee = $this->shippingFee->getDataMaster($settingShippingId);
                            }
                            $this->shippingFee->insert($dataShippingFee);
                        }
                    }
                }
            });
            DB::commit();
            return true;
        } catch (Exception $ex) {
            DB::rollback();
            return false;
        }
    }

    /**
     * check exits email
     * @param  string $email
     * @return boolean
     */
    public function checkExistEmail($email)
    {
        return $this->user->findByEmail($email) ? true : false;
    }

    /**
     * format data csv insert
     * @param  array $item
     * @return array
     */
    public function formatDataCsvInsert($item)
    {
        $fieldAuth = ['yahoo_info', 'amazon_info', 'monitoring', 'regist_limit', 'post_limit'];
        $item['start_date'] = str_replace('/', '-', $item['start_date']) . ' 00:00:00';
        $item['password'] = Hash::make($item['password']);
        $item['created_at'] = date('Y-m-d H:i:s');
        $item['updated_at'] = date('Y-m-d H:i:s');
        $item['user_code'] = User::generateUserCode();
        foreach ($fieldAuth as $field) {
            $dataAuth[$field] = $item[$field];
            unset($item[$field]);
        }
        $dataAuth['created_at'] = date('Y-m-d H:i:s');
        $dataAuth['updated_at'] = date('Y-m-d H:i:s');
        return [
            'data_user' => $item,
            'data_auth' => $dataAuth,
        ];
    }
}