<?php

namespace Database\Seeders;

use App\Utills\Constants\UserRole;
use Illuminate\Database\Seeder;
use App\User;
use Illuminate\Support\Str;

class UsersTableDataSeeder extends Seeder
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
                'id'            => 1,
                'first_name'    => 'Admin',
                'last_name'     => 'Admin',
                'email'         => 'admin@brrring.com',
                'role'          => UserRole::ADMIN
            ],
            [
                'id'            => 2,
                'first_name'    => 'Hamood',
                'last_name'     => 'Ur Rehman',
                'email'         => 'hamood@brrring.com',
                'role'          => UserRole::CUSTOMER
            ],
            [
                'id'            => 3,
                'first_name'    => 'Junaid',
                'last_name'     => 'Tahir',
                'email'         => 'junaid@brrring.com',
                'role'          => UserRole::CUSTOMER
            ],
            [
                'id'            => 4,
                'first_name'    => 'Awais',
                'last_name'     => 'Khan',
                'email'         => 'awais@brrring.com',
                'role'          => UserRole::CUSTOMER
            ]
        ];

        foreach ($users as $user){
            $user['password']           = bcrypt("password");
            $user['phone_no']           = "01-886-0269 x3767";
            $user['remember_token']     = Str::random(10);
            $user['email_verified_at']  = now();
            $user['currency_id']        = 1;

            User::create($user);
        }
    }
}
