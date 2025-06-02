<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddNewFieldsToClientsTable extends Migration
{
    public function up()
    {
        Schema::table('clients', function (Blueprint $table) {
            $table->boolean('is_have_money')->default(null)->nullable()->after('source');
            $table->boolean('is_have_time')->default(null)->nullable()->after('source');
            $table->boolean('is_have_invest')->default(null)->nullable()->after('source');
            $table->boolean('is_25')->default(null)->nullable()->after('source');
        });
    }

    public function down()
    {
        Schema::table('clients', function (Blueprint $table) {
            $table->dropColumn('is_have_money');
            $table->dropColumn('is_have_time');
            $table->dropColumn('is_have_invest');
            $table->dropColumn('is_25');
            
        });
    }
}
