<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMemberPhoneTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('member_phone', function (Blueprint $table) {
            $table->bigInteger('member_id')->unsigned();
            $table->string('phone', 15);
            $table->primary(['member_id', 'phone']);
            $table->foreign('member_id')
                ->references('member_id')
                ->on('member')
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
        Schema::drop('member_phone');
    }
}
