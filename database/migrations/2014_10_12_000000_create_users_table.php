<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->rememberToken();
            $table->foreignId('current_team_id')->nullable();
            $table->string('profile_photo_path', 2048)->nullable();

            $table->integer('balance')->default(0);
            $table->string('phone_number');
            $table->string('social_media')->nullable();
            $table->string('socmed_detail')->nullable();
            $table->integer('total_followers')->nullable();
            $table->string('company')->nullable();
            $table->string('web_linkedin')->nullable();
            $table->string('partner_role')->nullable();
            $table->string('industry')->nullable();
            $table->string('npwp')->nullable();
            $table->string('city')->nullable();
            $table->text('description')->nullable();
            $table->integer('is_joined')->nullable();
            $table->integer('is_active')->nullable();
            $table->string('role')->default('USER');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
