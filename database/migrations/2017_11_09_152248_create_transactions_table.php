<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->increments('id');
            $table->string('transaction_code');
            $table->integer('customer_id');
            $table->integer('stall_id');
            $table->integer('preparation_time')->nullable();
            $table->dateTime('pickup_time');
            $table->decimal('total_price', 10, 2);
            $table->string('order_type');
            $table->string('status');
            $table->string('paymaya_receipt_number')->nullable();
            $table->string('paymaya_transaction_reference_number')->nullable();
            $table->enum('customer_view', ['Y', 'N'])->default('Y');
            $table->enum('stall_view', ['Y', 'N'])->default('Y');
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
        Schema::dropIfExists('transactions');
    }
}
