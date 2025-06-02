<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRetentionClientsToUsersTable extends Migration
{
    public function up()
    {
        Schema::table('pipelines', function (Blueprint $table) {
            $table->dropColumn('retention_clients');
        });

        Schema::table('users', function (Blueprint $table) {
            $table->text('retention_clients')->nullable()->after('username');
        });
    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('retention_clients');
        });
        Schema::table('pipelines', function (Blueprint $table) {
            $table->text('retention_clients')->nullable()->after('name');
        });
    }
}
