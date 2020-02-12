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
        Schema::table('member_view', function (Blueprint $table) {
            DB::unprepared("
                DROP VIEW IF EXISTS member_view;
                CREATE VIEW member_view AS
                SELECT DISTINCT member_id, first_name, last_name, email, password, created_at, updated_at, facebook_link,
                    cip, phone,
                    IF(admin.member_id     IS NULL, 0, 1) AS is_admin,
                    IF(permanent.member_id IS NULL, 0, 1) AS is_permanent
                FROM member LEFT JOIN member_facebook   USING(member_id)
                            LEFT JOIN member_university USING(member_id)
                            LEFT JOIN member_phone      USING(member_id)
                            LEFT JOIN admin             USING(member_id)
                            LEFT JOIN permanent         USING(member_id)
                ;
            ");
        });
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
