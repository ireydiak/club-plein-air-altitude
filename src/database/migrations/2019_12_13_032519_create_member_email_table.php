<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMemberEmailTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('member_email', function (Blueprint $table) {
            $table->bigInteger('member_id')->unsigned();
            $table->primary('member_id');
            $table->string('email', 100)->unique()->nullable(false);
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
        Schema::dropIfExists('member_email');
    }
}
