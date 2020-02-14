<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\QueryException;

class CreateMemberView extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        DB::unprepared("
                DROP VIEW IF EXISTS member_view;
                CREATE VIEW member_view AS
                    SELECT DISTINCT member_id, first_name, last_name, email, password, role.name AS role, member.created_at, member.updated_at,
                                    facebook_link, cip, phone
                    FROM member INNER JOIN role             USING(role_id)
                                LEFT JOIN member_facebook   USING(member_id)
                                LEFT JOIN member_university USING(member_id)
                                LEFT JOIN member_phone      USING(member_id)
                ;
            ");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        DB::unprepared("DROP VIEW member_view;");
    }
}
