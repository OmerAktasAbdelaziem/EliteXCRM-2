<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class DeleteSomeColumnsFromClientsTable extends Migration
{
    public function up()
    {
        Schema::table('clients', function (Blueprint $table) {
            $table->dropColumn('account_id');
            $table->dropColumn('nickname');
            $table->dropColumn('city');
            $table->dropColumn('verificationLevel');
            $table->dropColumn('language');
            $table->dropColumn('experience');
            $table->dropColumn('job_title');
            $table->dropColumn('birth');
            $table->dropColumn('notes');
            $table->dropColumn('address');
            $table->unsignedBigInteger('ftd_amount')->after('is_ftd')->nullable();
            $table->timestamp('reg_date')->after('age')->nullable();
            $table->string('ad')->after('campaign')->nullable();
            $table->string('source')->after('ad')->nullable();
            $table->string('password')->after('email')->nullable();
        });
    }

    public function down()
    {
        Schema::table('clients', function (Blueprint $table) {
            //
        });
    }
}
