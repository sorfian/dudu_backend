<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_transactions', function (Blueprint $table) {
            $table->id();

            $table->integer('user_id');
            $table->integer('talent_id');
            $table->integer('total');
            $table->string('status');
            $table->text('payment_url');
            $table->string('name');
            $table->string('moment');
            $table->timestamp('birthday_date')->nullable();
            $table->integer('age')->nullable();
            $table->text('occasion')->nullable();
            $table->text('instruction')->nullable();
            $table->text('detail')->nullable();

            $table->softDeletes();
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
        Schema::dropIfExists('user_transactions');
    }
}
