<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMemberProcedure extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('member_procedure', function (Blueprint $table) {
            // create member
            $this->createMemberCreateProcedure();
            // add member contact information
            $this->createMemberContactProcedure();
            // update member privileges
            $this->createMemberPrivilegesProcedure();
            // update member table information
            $this->createUpdateMemberProcedure();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::unprepared('DROP PROCEDURE create_member;');
        DB::unprepared('DROP PROCEDURE IF EXISTS update_member_contact;');
        DB::unprepared('DROP PROCEDURE IF EXISTS update_member_privileges;');
        DB::unprepared('DROP PROCEDURE IF EXISTS update_member');
    }

    private function createMemberCreateProcedure()
    {
        DB::unprepared('
                DROP PROCEDURE IF EXISTS create_member;
                CREATE PROCEDURE create_member(
                    user_first_name   VARCHAR(255),
                    user_last_name    VARCHAR(255),
                    user_email        VARCHAR(255),
                    user_password     VARCHAR(555),
                    user_cip          CHAR(8),
                    user_facebook     VARCHAR(255),
                    is_permanent      BOOLEAN,
                    is_admin          BOOLEAN
                )
                BEGIN
                    INSERT INTO member (member_id, first_name, last_name, password, created_at, updated_at)
                    VALUES (NULL, user_first_name, user_last_name, user_password, NOW(), NOW())
                    ;

                    SET @member_id = (
                        SELECT member_id FROM member WHERE member_id = LAST_INSERT_ID()
                    )
                    ;

                    CALL update_member_contact(@member_id, user_cip, user_email, user_facebook);
                    CALL update_user_privileges(@member_id, is_permanent, is_admin);

                    SELECT * FROM member_view WHERE member_id = @member_id;
                END;
            ');
    }

    private function createMemberContactProcedure()
    {
        DB::unprepared("
            DROP PROCEDURE IF EXISTS update_member_contact;
            CREATE PROCEDURE update_member_contact(
                member_id      BIGINT,
                user_cip       CHAR(8),
                user_email     VARCHAR(255),
                user_facebook  VARCHAR(255)
            )
            BEGIN
                IF user_cip IS NOT NULL AND user_cip <> ''
                THEN
                    IF NOT EXISTS (SELECT member_id FROM member_university WHERE member_id = @member_id) THEN
                        INSERT INTO member_university (member_id, cip) VALUES (@member_id, user_cip);
                    ELSE
                        UPDATE member_university SET cip = user_cip WHERE member_id = @member_id;
                    END IF;
                END IF;

                IF user_email IS NOT NULL AND user_email <> ''
                THEN
                    IF NOT EXISTS(SELECT member_id FROM member_email WHERE member_id = @member_id)
                    THEN
                        INSERT INTO member_email (member_id, email) VALUES (@member_id, user_email);
                    ELSE
                        UPDATE member_email SET email = user_email WHERE member_id = @member_id;
                    END IF;
                END IF;

                IF user_facebook IS NOT NULL AND user_facebook <> ''
                THEN
                    IF NOT EXISTS(SELECT member_id FROM member_facebook WHERE member_id = @member_id)
                    THEN
                        INSERT INTO member_facebook (member_id, facebook_link) VALUES (@member_id, user_facebook);
                    ELSE
                        UPDATE member_facebook SET facebook_link = user_facebook WHERE member_id = @member_id;
                    END IF;
                END IF;
            END;
        ");
    }

    private function createMemberPrivilegesProcedure() {
        DB::unprepared('
            DROP PROCEDURE IF EXISTS update_member_privileges;
            CREATE PROCEDURE update_member_privileges(
                member_id     BIGINT,
                is_permanent  BOOLEAN,
                is_admin      BOOLEAN
            )
            BEGIN
                IF is_permanent IS TRUE THEN
                    INSERT INTO permanent (member_id) VALUES(member_id);
                ELSE
                    DELETE FROM permanent WHERE member_id = member_id;
                END IF;
                IF is_admin IS TRUE THEN
                    INSERT INTO admin (member_id) VALUES(member_id);
                ELSE
                    DELETE FROM admin WHERE member_id = member_id;
                END IF;
            END;
        ');
    }

    private function createUpdateMemberProcedure() {
        DB::unprepared('
            DROP PROCEDURE IF EXISTS update_member;
            CREATE PROCEDURE update_member(
                user_uid          BIGINT,
                user_first_name   VARCHAR(255),
                user_last_name    VARCHAR(255),
                user_email        VARCHAR(255),
                user_password     VARCHAR(555),
                user_cip          CHAR(8),
                user_facebook     VARCHAR(255),
                is_permanent      BOOLEAN,
                is_admin          BOOLEAN
            )
            BEGIN
                UPDATE member
                SET first_name = user_first_name, last_name = user_last_name, password = user_password, updated_at = NOW()
                WHERE member_id = user_uid;

                SELECT * FROM member_view WHERE member_id = user_uid;
            END;
        ');
    }
}
