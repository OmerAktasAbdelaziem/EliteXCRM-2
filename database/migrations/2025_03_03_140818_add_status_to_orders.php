<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddStatusToOrders extends Migration
{
    public function up()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->string('status')->default('active')->after('pnl');
            $table->unsignedDouble('open_at_price')->nullable()->after('status');
            $table->unsignedDouble('close_on_profit')->nullable()->after('open_at_price');
            $table->unsignedDouble('close_on_loss')->nullable()->after('close_on_profit');
        });
    }

    public function down()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn('close_on_profit');
            $table->dropColumn('open_at_price');
            $table->dropColumn('close_on_loss');
            $table->dropColumn('status');
        });
    }
}
