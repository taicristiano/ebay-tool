<?php

use App\Models\User;
use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $users = [
            [
                'user_name'  => 'admin',
                'password'   => Hash::make('admin@2018'),
                'type'       => User::TYPE_SUPER_ADMIN,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'user_name'  => 'guest_admin',
                'password'   => Hash::make('admin@2018'),
                'type'       => User::TYPE_GUEST_ADMIN,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'user_name'  => 'normal_user',
                'password'   => Hash::make('admin@2018'),
                'type'       => User::TYPE_NORMAL_USER,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'user_name'  => 'cancelation_user',
                'password'   => Hash::make('admin@2018'),
                'type'       => User::TYPE_CANCELATION_USER,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
        ];
        $user = new User;
        $user->insert($users);
    }
}
