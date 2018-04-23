<?php

use App\Models\MtbStore;
use Illuminate\Database\Seeder;

class MtbStoreTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $stores = [
            [
                'name'       => 'スタンダード',
                'fixed_fee'  => 30,
                'max_fee'    => 750,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'name'       => 'ベーシック',
                'fixed_fee'  => 20,
                'max_fee'    => 250,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'name'       => 'プレミアム',
                'fixed_fee'  => 10,
                'max_fee'    => 250,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'name'       => 'アンカー',
                'fixed_fee'  => 5,
                'max_fee'    => 250,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
        ];
        $store = new MtbStore;
        $store->insert($stores);
    }
}
