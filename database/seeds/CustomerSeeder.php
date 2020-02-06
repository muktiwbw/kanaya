<?php

use Illuminate\Database\Seeder;
use App\Customer;

class CustomerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $customers = [
            [
                'name' => 'Allan Moore',
                'email' => 'amoore@gmail.com',
                'password' => bcrypt('password'),
                'phone' => '089878762514',
                'address' => 'Jalan Kalibata'
            ],
            [
                'name' => 'Bill Gates',
                'email' => 'bgates@gmail.com',
                'password' => bcrypt('password'),
                'phone' => '098987672615',
                'address' => 'Jalan Kemang'
            ],
            [
                'name' => 'Christopher Hassanudin',
                'email' => 'chass@gmail.com',
                'password' => bcrypt('password'),
                'phone' => '098787656715',
                'address' => 'Jalan Palmera'
            ],
            [
                'name' => 'Douglass Brad',
                'email' => 'dbrad@gmail.com',
                'password' => bcrypt('password'),
                'phone' => '087676152456',
                'address' => 'Jalan Mampang'
            ]
        ];

        foreach($customers as $customer){
            Customer::create($customer);
        }
    }
}
