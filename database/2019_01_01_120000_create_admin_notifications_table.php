<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Roost\LaravelTools\Helpers\ExceptionHelper;

class CreateAdminNotificationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('admin_notifications', function (Blueprint $table) {
            $table->increments('id');

            $table->string("topic", 100)->nullable();
            $table->string("subject", 1000)->nullable();
            $table->string("body", 5000)->nullable();
            $table->string("sent_to")->nullable();
            $table->string("level")->nullable();

            $table->unsignedInteger("db_update_count")->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('admin_notifications');
    }
}