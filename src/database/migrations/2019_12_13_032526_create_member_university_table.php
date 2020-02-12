<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMemberUniversityTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('member_university', function (Blueprint $table) {
            $table->bigInteger('member_id')->unsigned();
            $table->primary('member_id');
            $table->char('cip', 8)->unique();
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
        Schema::table('member_university', function (Blueprint $table) {
            Schema::dropIfExists('member_university');
        });
    }
}
