<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();

            $table->unsignedBiginteger('customer_id');
            $table->foreign('customer_id')->references('id')->on('customers');

            $table->unsignedBiginteger('service_id');
            $table->foreign('service_id')->references('id')->on('services');

            $table->float('amount')->default(0.00);
            $table->float('fees')->default(0.00);
            $table->float('additional_amount')->default(0.00);
            $table->float('rahtak_fees')->default(0.00);

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
};
