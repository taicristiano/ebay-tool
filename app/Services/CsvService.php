<?php

namespace App\Services;

use Excel;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\Authorization;
use App\Models\Setting;
use App\Models\ShippingFee;
use App\Models\SettingShipping;
use Exception;
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
    ) {
        $this->user            = $user;
        $this->authorization   = $authorization;
        $this->setting         = $setting;
        $this->shippingFee     = $shippingFee;
        $this->settingShipping = $settingShipping;
    }

    /**
     * format data export csv
     * @param  array $data
     * @return array
     */
    public function formatDataExportCsv($data)
    {
        return $data;
    }

    /**
     * generate column export csv
     * @return array
     */
    public static function generateColumnExportCsvFull()
    {
        return [
            Lang::get('view.start_date'),
            Lang::get('view.user_code'),
            Lang::get('view.user_name'),
            Lang::get('view.name_kana'),
            Lang::get('view.email'),
            Lang::get('view.tel'),
            Lang::get('view.ebay_account'),
            Lang::get('view.type'),
            Lang::get('view.introducer_id'),
            Lang::get('view.yahoo_info'),
            Lang::get('view.amazon_info'),
            Lang::get('view.monitoring_price'),
            Lang::get('view.regist_limit'),
            Lang::get('view.post_limit'),
            Lang::get('view.memo'),
        ];
    }

    /**
     * generate column export csv
     * @return array
     */
    public static function generateColumnExportCsvSimple()
    {
        return [
            Lang::get('view.tel'),
            Lang::get('view.user_code'),
            Lang::get('view.email'),
            Lang::get('view.address'),
            Lang::get('view.memo'),
        ];
    }

    /**
     * generate data export csv
     * @param  string $type
     * @param  array $data
     * @return array
     */
    public function generateDataExportCsv($type, $data)
    {
        
        $results = [];
        if (count($data)) {
            if ($type == 'full') {
                foreach ($data as $key => $item) {
                    $row = [
                        self::formatDate('Y/m/d', $item['start_date']),
                        $item['user_code'],
                        $item['user_name'],
                        $item['name_kana'],
                        $item['email'],
                        $item['tel'],
                        $item['ebay_account'],
                        $this->user->getTypeOptions()[$item['type']],
                        $item['introducer_id'],
                        self::getStatusFlag($item['yahoo_info']),
                        self::getStatusFlag($item['amazon_info']),
                        self::getStatusFlag($item['monitoring']),
                        $item['regist_limit'] ? $item['regist_limit'] : '',
                        $item['post_limit'] ? $item['post_limit'] : '',
                        $item['memo'] ? $item['memo'] : '',
                    ];
                    $results[] = $row;
                }
            } else {
                foreach ($data as $key => $item) {
                    $row = [
                        $item['tel'],
                        $item['user_code'],
                        $item['email'],
                        '',
                        $item['memo']
                    ];
                    $results[] = $row;
                }
            }
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
        $data = $this->generateDataExportCsv($type, $data);
        $dateNow = date('Ymd h:i:s');
        if ($type == 'full') {
            $fileName = 'ユーザー一覧（詳細） _' . $dateNow;
            $columnTitle = $this->generateColumnExportCsvFull();
        } else {
            $fileName = 'ユーザー一覧（コネクト用） _' . $dateNow;
            $columnTitle = $this->generateColumnExportCsvSimple();
        }
        return $this->excuteExportCsv($fileName . ".csv", $columnTitle, $data);
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
            Excel::load($file, function ($reader) {
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
