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
                    member_first_name   VARCHAR(255),
                    member_last_name    VARCHAR(255),
                    member_email        VARCHAR(255),
                    member_password     VARCHAR(555),
                    member_cip          CHAR(8),
                    member_facebook     VARCHAR(255),
                    is_permanent      BOOLEAN,
                    is_admin          BOOLEAN
                )
                BEGIN
                    INSERT INTO member (member_id, first_name, last_name, password, created_at, updated_at)
                    VALUES (NULL, member_first_name, member_last_name, member_password, NOW(), NOW())
                    ;

                    SET @member_uid = (
                        SELECT member_id FROM member WHERE member_id = LAST_INSERT_ID()
                    )
                    ;

                    CALL update_member_contact(@member_uid, member_cip, member_email, member_facebook);
                    CALL update_member_privileges(@member_uid, is_permanent, is_admin);

                    SELECT * FROM member_view WHERE member_id = @member_uid;
                END;
            ');
    }

    private function createMemberContactProcedure()
    {
        DB::unprepared("
            DROP PROCEDURE IF EXISTS update_member_contact;
            CREATE PROCEDURE update_member_contact(
                member_uid      BIGINT,
                member_cip       CHAR(8),
                member_email     VARCHAR(255),
                member_facebook  VARCHAR(255)
            )
            BEGIN
                IF member_cip IS NOT NULL AND member_cip <> ''
                THEN
                    IF NOT EXISTS (SELECT member_id FROM member_university WHERE member_id = member_uid) THEN
                        INSERT INTO member_university (member_id, cip) VALUES (member_uid, member_cip);
                    ELSE
                        UPDATE member_university SET cip = member_cip WHERE member_id = member_uid;
                    END IF;
                END IF;

                IF member_email IS NOT NULL AND member_email <> ''
                THEN
                    IF NOT EXISTS(SELECT member_id FROM member_email WHERE member_id = member_uid)
                    THEN
                        INSERT INTO member_email (member_id, email) VALUES (member_uid, member_email);
                    ELSE
                        UPDATE member_email SET email = member_email WHERE member_id = member_uid;
                    END IF;
                END IF;

                IF member_facebook IS NOT NULL AND member_facebook <> ''
                THEN
                    IF NOT EXISTS(SELECT member_id FROM member_facebook WHERE member_id = member_uid)
                    THEN
                        INSERT INTO member_facebook (member_id, facebook_link) VALUES (member_uid, member_facebook);
                    ELSE
                        UPDATE member_facebook SET facebook_link = member_facebook WHERE member_id = member_uid;
                    END IF;
                END IF;
            END;
        ");
    }

    private function createMemberPrivilegesProcedure() {
        DB::unprepared('
            DROP PROCEDURE IF EXISTS update_member_privileges;
            CREATE PROCEDURE update_member_privileges(
                member_uid     BIGINT,
                is_permanent  BOOLEAN,
                is_admin      BOOLEAN
            )
            BEGIN
                IF is_permanent IS TRUE THEN
                    INSERT INTO permanent (member_id) VALUES(member_uid);
                ELSE
                    DELETE FROM permanent WHERE member_id = member_uid;
                END IF;
                IF is_admin IS TRUE THEN
                    INSERT INTO admin (member_id) VALUES(member_uid);
                ELSE
                    DELETE FROM admin WHERE member_id = member_uid;
                END IF;
            END;
        ');
    }

    private function createUpdateMemberProcedure() {
        DB::unprepared('
            DROP PROCEDURE IF EXISTS update_member;
            CREATE PROCEDURE update_member(
                member_uid          BIGINT,
                member_first_name   VARCHAR(255),
                member_last_name    VARCHAR(255),
                member_email        VARCHAR(255),
                member_password     VARCHAR(555),
                member_cip          CHAR(8),
                member_facebook     VARCHAR(255),
                is_permanent        BOOLEAN,
                is_admin            BOOLEAN
            )
            BEGIN
                UPDATE member
                SET first_name = member_first_name, last_name = member_last_name, password = member_password, updated_at = NOW()
                WHERE member_id = member_uid;

                CALL update_member_contact(@member_id, member_cip, member_email, member_facebook);
                CALL update_member_privileges(@member_id, is_permanent, is_admin);

                SELECT * FROM member_view WHERE member_id = member_uid;
            END;
        ');
    }
}
