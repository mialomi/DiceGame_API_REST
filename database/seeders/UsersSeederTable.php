<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UsersSeederTable extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('users')->insert([

            ['nickname' => 'admin',
            'email' => 'admin@gmail.com',
            'password' => 'admin123456',
            'role_id' => '1',
            ],

            ['nickname' => 'Eli',
            'email' => 'eli@gmail.com',
            'password' => '123456789',
            'role_id' => '2',
            ],

            ['nickname' => 'Mia',
            'email' => 'mia@gmail.com',
            'password' => '123456789',
            'role_id' => '2',
            ]
  
        ]);
    }
}
