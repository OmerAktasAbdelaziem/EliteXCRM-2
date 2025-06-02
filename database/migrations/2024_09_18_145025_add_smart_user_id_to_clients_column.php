<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSmartUserIdToClientsColumn extends Migration
{
    public function up()
    {
        Schema::table('clients', function (Blueprint $table) {
            $table->unsignedBigInteger('smart_user_id')->default(null)->nullable()->after('email');
            $table->unsignedFloat('ftd_bonus')->default(null)->nullable()->after('is_ftd');
        });
    }

    public function down()
    {
        Schema::table('clients', function (Blueprint $table) {
            $table->dropColumn('smart_user_id');
            $table->dropColumn('ftd_bonus');
        });
    }
}
