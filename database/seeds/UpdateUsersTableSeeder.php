<?php

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Setting;
use App\Models\ShippingFee;
use App\Models\SettingShipping;
use App\Models\Authorization;

class UpdateUsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $users = User::get();
        foreach($users as $user) {
            // if ($user->id == 2) {
            //     dd(User::get());
            // }
            $user->user_code = User::generateUserCode();
            $user->save();
            if (in_array($user->type, [User::TYPE_SUPER_ADMIN, User::TYPE_GUEST_ADMIN])) {
                $setting = Setting::where('user_id', $user->id)->first();
                if ($user->type == User::TYPE_GUEST_ADMIN) {
                    $authorization = Authorization::where('user_id', $user->id)->first();
                    if (!$authorization) {
                        $dataInsert['user_id'] = $user->id;
                        Authorization::insert($dataInsert);
                    }
                }
                if (!$setting) {
                    $dataSetting['user_id'] = $user->id;
                    $dataSetting['created_at'] = date('Y-m-d H:i:s');
                    $dataSetting['updated_at'] = date('Y-m-d H:i:s');
                    Setting::insert($dataSetting);
                    $this->shippingFee     = new ShippingFee;
                    $this->settingShipping = new SettingShipping;
                    // insert setting shipping and setting fee
                    $dataShipping = $this->settingShipping->getDataMaster($user->id);
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
        }
    }
}
