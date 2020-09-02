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
            $table->bigIncrements('id');
            $table->unsignedBigInteger('subscription_id')->default(1);
            $table->unsignedBigInteger('role_id')->default(1);
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->rememberToken();
            $table->boolean('confirmed')->default(0);
            $table->timestamp('last_login')->nullable();
            $table->boolean('locked')->default(1);
            $table->ipAddress('last_ip');
            $table->string('language');
            $table->string('tel');
            $table->string('country');
            $table->string('communication_media');
            $table->timestamps();

            $table->foreign('subscription_id')->references('id')->on('subscriptions')->onUpdate('cascade');
            $table->foreign('role_id')->references('id')->on('roles')->onUpdate('cascade');
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
