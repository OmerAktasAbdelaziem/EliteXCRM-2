<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ModifyNotificationsTable extends Migration
{

    public function up()
    {
        Schema::table('notifications', function (Blueprint $table) {
            $table->dropColumn('type');
            $table->dropColumn('partner_id');
            $table->unsignedBigInteger('client_id');
            $table->string('text');
        });
    }

    public function down()
    {
        Schema::table('notifications', function (Blueprint $table) {
            $table->unsignedDouble('last_bid');
            $table->unsignedDouble('last_ask');
            $table->dropColumn('client_id');
            $table->dropColumn('text');
        });
    }
}
