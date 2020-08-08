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
            ['email' => 'john@vectron.com'],
            [
                'name' => 'John Doe',
                'password' => bcrypt('john@123'),
                'roles' => 'user',
                'created_at' => $ts,
                'updated_at' => $ts,
            ]
        );
    }
}
