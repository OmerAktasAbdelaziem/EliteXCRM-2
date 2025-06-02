<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddGetClientsFromApiToPipelinesTable extends Migration
{
    public function up()
    {
        Schema::table('pipelines', function (Blueprint $table) {
            $table->integer('get_clients_from_api')->default(0)->after('name');
        });
    }

    public function down()
    {
        Schema::table('pipelines', function (Blueprint $table) {
            $table->dropColumn('get_clients_from_api');
        });
    }
}
