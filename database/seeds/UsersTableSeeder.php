<?php

use Carbon\Carbon;
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
        $ts = Carbon::now()->format('Y-m-d H:i:s');

    	DB::table('users')->updateOrInsert(
            ['email' => 'admin@vectron.com.au'],
            [
                'name' => 'Admin user',
                'password' => bcrypt('admin@123'),
                'roles' => 'user|admin',
                'created_at' => $ts,
                'updated_at' => $ts,
            ],
            ['email' => 'user@vectron.com.au'],
            [
                'name' => 'Normal user',
                'password' => bcrypt('user@123'),
                'roles' => 'user',
                'created_at' => $ts,
                'updated_at' => $ts,
            ]
        );
    }
}
