<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Transactions extends Migration
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
            $table->integer('payee_id')->unsigned();
            $table->foreign('payee_id')
                ->references('id')
                ->on('users');
            $table->integer('payer_id')->unsigned();
            $table->foreign('payer_id')
                ->references('id')
                ->on('users');
            $table->dateTime('transaction_date');
            $table->float('value', 8, 2);
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
