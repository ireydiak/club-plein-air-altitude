<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMemberFacebookTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('member_facebook', function (Blueprint $table) {
            $table->bigInteger('member_id')->unsigned();
            $table->primary('member_id');
            $table->string('facebook_link', 255)->nullable(false);
            $table->foreign('member_id')
                ->references('member_id')->on('member')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('member_facebook', function (Blueprint $table) {
            Schema::dropIfExists('member_facebook');
        });
    }
}
