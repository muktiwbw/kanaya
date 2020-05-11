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
            $table->bigIncrements('id');
            $table->string('trans_no');
            $table->text('notes')->nullable();
            $table->string('receipt')->nullable();
            $table->integer('status')->nullable()->default(0);
            /**
             * Status:
             * 0 = Cart
             * 1 = Checkout
             * 2 = Checkout complete
             * 3 = Borrowed (barang diambil)
             * 4 = Returned
             * 5 = Overdue
             */
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->timestamp('cart_expiration')->nullable();
            $table->unsignedBigInteger('customer_id')->nullable();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->foreign('customer_id')->references('id')->on('customers');
            $table->foreign('user_id')->references('id')->on('users');
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
