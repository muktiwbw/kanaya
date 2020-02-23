<?php

use Illuminate\Database\Seeder;
use App\User;

class InitSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Create Super Admin
        User::create([
            'name' => 'Super Admin',
            'email' => 'superadmin',
            'password' => bcrypt('superadmin'),
            'status' => 1
        ]);
    }
}
