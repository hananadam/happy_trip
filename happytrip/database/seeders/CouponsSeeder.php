<?php
namespace Database\Seeders;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class CouponsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('coupons')->insert([
            [
                'code' => '#1Cairo',
                'discount' => '20%',
                'expire_date' =>'2021-05-30',
                'description' => 'a 20 % discount on all hotels in cairo during the last 10 days of may',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'code' => '#1Dubai',
                'discount' => '40%',
                'expire_date' =>'2021-06-30',
                'description' => 'a 20 % discount on all hotels in dubai',
                'created_at' => now(),
                'updated_at' => now()
            ]
        ]);

    }
}
