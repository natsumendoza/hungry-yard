<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('orders'))
        {
            Schema::create('orders', function (Blueprint $table) {
                $table->increments('id');
                $table->string('transaction_code');
                $table->integer('stall_id');
                $table->integer('product_id');
                $table->integer('customer_id');
                $table->integer('quantity');
                $table->string('comment')->nullable();
                $table->string('status');
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('orders');
    }
}
