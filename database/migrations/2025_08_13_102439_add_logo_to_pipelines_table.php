<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasColumn('pipelines', 'logo')) {
        Schema::table('pipelines', function (Blueprint $table) {
            $table->string('logo', 1024)->nullable()->after('usdt');
        });
    }
    }

    public function down(): void
    {
        Schema::table('pipelines', function (Blueprint $table) {
            $table->dropColumn('logo');
        });
    }
};
