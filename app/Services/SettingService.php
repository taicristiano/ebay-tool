<?php

namespace App\Services;

use App\Services\CommonService;


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
}