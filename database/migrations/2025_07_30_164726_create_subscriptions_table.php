<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSubscriptionsTable extends Migration
{
    public function up()
    {
        Schema::create('subscriptions', function (Blueprint $table) {
            $table->id(); // PRIMARY KEY + AUTO_INCREMENT
            $table->unsignedInteger('pipeline');
            $table->timestamp('start_date');
            $table->timestamp('end_date');
            $table->tinyInteger('active')->default(1);
            $table->unsignedInteger('parts_count')->default(1);
            $table->unsignedInteger('teams_count')->default(1);
            $table->unsignedInteger('users_count')->default(1);
            $table->unsignedInteger('real_accounts')->default(1);
            $table->unsignedInteger('demo_accounts')->default(1);
            $table->unsignedInteger('supscription_type')->default(1);
            $table->tinyInteger('deleted')->default(0);
            $table->timestamp('deleted_at')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('subscriptions');
    }
}
