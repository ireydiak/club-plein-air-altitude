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
            DB::statement("
                CREATE VIEW member_view AS
                SELECT DISTINCT member_id, first_name, last_name, password, created_at, updated_at, email, facebook_link, cip,
                                EXISTS(SELECT member_id FROM admin NATURAL JOIN member) AS is_admin,
                                EXISTS(SELECT member_id FROM permanent NATURAL JOIN member) AS is_permanent
                FROM member LEFT JOIN member_email      USING(member_id)
                            LEFT JOIN member_facebook   USING(member_id)
                            LEFT JOIN member_university USING(member_id)
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
        DB::statement("DROP VIEW member_view;");
    }
}
