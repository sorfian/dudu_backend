<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFieldVideoFileTalentToUserTransactions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('user_transactions', function (Blueprint $table) {
            $table->text('video_file_talent')->after('video_file')->nullable();
            $table->text('video_thumbnail_talent')->after('video_file')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('user_transactions', function (Blueprint $table) {
            //
        });
    }
}
