<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddReceiptToMoneyTrxes extends Migration
{

    public function up()
    {
        Schema::table('money_trxes', function (Blueprint $table) {
            $table->string('receipt')->nullable()->after('usdt');
        });
    }

    public function down()
    {
        Schema::table('money_trxes', function (Blueprint $table) {
            $table->dropColumn('receipt');
        });
    }
}
