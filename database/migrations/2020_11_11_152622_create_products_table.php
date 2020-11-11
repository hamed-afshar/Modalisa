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
            $table->unsignedBigInteger('status_id')->nullable();
//            $table->unsignedBigInteger('order_id');
            $table->string('size');
            $table->string('color');
            $table->string('pic');
            $table->string('link');
            $table->string('price');
            $table->string('quantity');
            $table->string('weight');
            $table->string('country');
            $table->string('currency');
            $table->string('ref');
            $table->timestamps();

            $table->foreign('status_id')->references('id')->on('statuses')->onUpdate('cascade')->onDelete('SET NULL');
//            $table->foreign('order_id')->references('id')->on('products')->onUpdate('cascade')->onDelete('cascade');
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
