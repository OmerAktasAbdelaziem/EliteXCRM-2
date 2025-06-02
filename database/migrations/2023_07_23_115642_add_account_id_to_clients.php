<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddAccountIdToClients extends Migration
{
    public function up()
    {
        Schema::table('clients', function (Blueprint $table) {
            $table->unsignedBigInteger('account_id')->nullable()->after('user_id');
            $table->string('nickname')->nullable()->after('last_name');
            $table->string('verificationLevel')->nullable()->after('city');
        });
    }

    public function down()
    {
        Schema::table('clients', function (Blueprint $table) {
            $table->dropColumn('account_id');
            $table->dropColumn('nickname');
            $table->dropColumn('verificationLevel');
        });
    }
}
