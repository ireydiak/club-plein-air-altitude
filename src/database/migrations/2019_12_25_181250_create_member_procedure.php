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
                    member_role         BIGINT,
                    phone               VARCHAR(15)
                )
                BEGIN
                    INSERT INTO member (member_id, first_name, last_name, email, password, role_id, created_at, updated_at)
                    VALUES (NULL, member_first_name, member_last_name, member_email, member_password, member_role, NOW(), NOW())
                    ;

                    SET @member_uid = (
                        SELECT member_id FROM member WHERE member_id = LAST_INSERT_ID()
                    )
                    ;

                    CALL update_member_contact(@member_uid, member_cip, member_facebook, phone);

                    SELECT * FROM member_view WHERE member_id = @member_uid;
                END;
            ');
    }

    private function createMemberContactProcedure()
    {
        DB::unprepared("
            DROP PROCEDURE IF EXISTS update_member_contact;
            CREATE PROCEDURE update_member_contact(
                member_uid              BIGINT,
                member_cip              CHAR(8),
                member_facebook         VARCHAR(255),
                member_phone_number     VARCHAR(15)
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

                IF member_phone_number IS NOT NULL AND member_phone_number <> ''
                THEN
                    IF NOT EXISTS (SELECT member_id FROM member_phone WHERE member_id = member_uid) THEN
                        INSERT INTO member_phone (member_id, phone) VALUES (member_uid, member_phone_number);
                    ELSE
                        UPDATE member_phone SET phone = member_phone_number WHERE member_id = member_uid;
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
                member_role         BIGINT,
                member_phone        VARCHAR(15)
            )
            BEGIN
                UPDATE member
                    SET first_name = member_first_name,
                        last_name = member_last_name,
                        password = member_password,
                        email = member_email,
                        role_id = member_role,
                        updated_at = NOW()
                WHERE member_id = member_uid;

                CALL update_member_contact(member_uid, member_cip, member_facebook, member_phone);

                SELECT * FROM member_view WHERE member_id = member_uid;
            END;
        ');
    }
}
