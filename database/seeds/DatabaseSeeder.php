<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->call(UsersTableSeeder::class);
        $this->call(MtbStoreTableSeeder::class);
        $this->call(UpdateUsersTableSeeder::class);
        $this->call(MtbCategoryFeeTableSeeder::class);
    }
}
