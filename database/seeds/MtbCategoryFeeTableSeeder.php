<?php

use App\Models\User;
use Illuminate\Database\Seeder;

class MtbCategoryFeeTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        try {
            $dataInsert = [];
            DB::beginTransaction();
            \Excel::load(public_path('data/mtb_category_fee_data.csv'), function($reader) {
                $results = $reader->toArray();
                foreach ($results as $key => $item) {
                    $data['category_path']     = $item['category_path'];
                    $data['category_id']       = $item['category_id'];
                    $data['standard_fee_rate'] = str_replace('%', '', $item['standard_fee_rate']);
                    $data['basic_fee_rate']    = str_replace('%', '', $item['basic_fee_rate']);
                    $data['premium_fee_rate']  = str_replace('%', '', $item['premium_fee_rate']);
                    $data['anchor_fee_rate']   = str_replace('%', '', $item['anchor_fee_rate']);
                    $data['created_at']        = date('Y-m-d H:i:s');
                    $data['updated_at']        = date('Y-m-d H:i:s');
                    DB::table('mtb_category_fee')->insert($data);
                }
            });
            DB::commit();
        } catch(Exception $ex) {
            \Log::info($ex);
            DB::rollback();
        }
        
    }
}
