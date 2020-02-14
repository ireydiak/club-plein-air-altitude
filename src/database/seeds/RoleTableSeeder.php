<?php

use Illuminate\Database\Seeder;

class RoleTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('role')->insert([
            ['name' => 'Membre', 'created_at' => DB::raw('NOW()'), 'updated_at' => DB::raw('NOW()')],
            ['name' => 'Permanent', 'created_at' => DB::raw('NOW()'), 'updated_at' => DB::raw('NOW()')],
            ['name' => 'Admin', 'created_at' => DB::raw('NOW()'), 'updated_at' => DB::raw('NOW()')],
            ['name' => 'Superadmin', 'created_at' => DB::raw('NOW()'), 'updated_at' => DB::raw('NOW()')],
        ]);
    }
}
