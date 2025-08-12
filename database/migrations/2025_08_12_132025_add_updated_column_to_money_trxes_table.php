<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddUpdatedColumnToMoneyTrxesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasColumn('money_trxes', 'updated')) {
            Schema::table('money_trxes', function (Blueprint $table) {
                $table->tinyInteger('updated')->default(0)->after('credit_card_details');
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
        if (Schema::hasColumn('money_trxes', 'updated')) {
            Schema::table('money_trxes', function (Blueprint $table) {
                $table->dropColumn('updated');
            });
        }
    }
}
