<?php

use Illuminate\Database\Seeder;
use Propaganistas\LaravelPhone\PhoneNumber;

class MemberTableSeeder extends Seeder {
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run() {
        $superadminUid = DB::table('role')->where('name', 'Superadmin')->first();

        $members = [
            ['Jean-Charles', 'Verdier', 'jean-charles.verdier@uherbrooke.ca', Hash::make('verj2009'), 'verj2009',
                NULL, $superadminUid->role_id, PhoneNumber::make('+14185759264')->ofCountry('CA')->formatE164()],
            ['Nicolas', 'Joubert', 'nicolas.joubert@usherbrooke.ca', Hash::make('nicolas.joubert@usherbrooke.ca'),
                NULL, NULL, $superadminUid->role_id, PhoneNumber::make('+18199195673')->ofCountry('CA')->formatE164()]
        ];

        foreach ($members as $member) {
            DB::select(
                "CALL create_member(
                    @member_first_name   := ?,
                    @member_last_name    := ?,
                    @member_email        := ?,
                    @member_password     := ?,
                    @member_cip		     := ?,
                    @member_facebook     := ?,
                    @member_role         := ?,
                    @member_phone        := ?
                )", $member
            );
        }
    }
}
