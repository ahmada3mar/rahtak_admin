<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('transactions', function (Blueprint $table) {
            DB::statement('ALTER TABLE transactions MODIFY COLUMN `amount` DOUBLE(8,3) DEFAULT 0.000');
            DB::statement('ALTER TABLE transactions MODIFY COLUMN `fees` DOUBLE(8,3) DEFAULT 0.000');
            DB::statement('ALTER TABLE transactions MODIFY COLUMN `rahtak_fees` DOUBLE(8,3) DEFAULT 0.000');
            DB::statement('ALTER TABLE transactions MODIFY COLUMN `additional_amount` DOUBLE(8,3) DEFAULT 0.000');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('transactions', function (Blueprint $table) {
            //
        });
    }
};
