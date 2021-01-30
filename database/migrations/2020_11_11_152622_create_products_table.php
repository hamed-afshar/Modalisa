<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('order_id');
            $table->unsignedBigInteger('kargo_id')->nullable();
            $table->string('size');
            $table->string('color');
            $table->string('link');
            $table->string('price');
            $table->string('quantity');
            $table->string('weight')->nullable();
            $table->string('country');
            $table->string('currency');
            $table->string('ref')->nullable();
            $table->timestamps();

            $table->foreign('order_id')->references('id')->on('orders')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('kargo_id')->references('id')->on('kargos')->onUpdate('cascade')->onDelete('SET NUll');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('products');
    }
}
