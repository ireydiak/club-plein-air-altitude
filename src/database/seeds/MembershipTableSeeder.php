<?php

use Illuminate\Database\Seeder;

class MembershipTableSeeder extends Seeder {
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run() {
        DB::table('membership')->insert([
            'name'          => 'Abonnement annuel',
            'price'         => 10.00,
            'created_at'    => DB::raw('NOW()'),
            'updated_at'    => DB::raw('NOW()')
        ]);
    }
}
