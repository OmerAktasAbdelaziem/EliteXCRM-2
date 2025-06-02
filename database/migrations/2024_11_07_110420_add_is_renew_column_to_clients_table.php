<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddIsRenewColumnToClientsTable extends Migration
{
    public function up()
    {
        Schema::table('clients', function (Blueprint $table) {
            $table->boolean('is_renew')->default(0)->after('deleted_at');
            $table->timestamp('renewed_at')->after('is_renew')->nullable();
        });
    }

    public function down()
    {
        Schema::table('clients', function (Blueprint $table) {
            $table->dropColumn('is_renew');
            $table->dropColumn('renewed_at');
        });
    }
}
