<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddAmountColumnToArkAccountsTable extends Migration
{
    public function up()
    {
        Schema::table('ark_accounts', function (Blueprint $table) {
            $table->unsignedFloat('amount')->default(0)->nullable();
            
        });
    }

    public function down()
    {
        Schema::table('ark_accounts', function (Blueprint $table) {
            $table->dropColumn('amount');
        });
    }
}
