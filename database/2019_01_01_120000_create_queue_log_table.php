<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Roost\LaravelTools\Helpers\ExceptionHelper;

class CreateQueueLogTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('queue_log', function (Blueprint $table) {
            $table->increments('id');

            $table->unsignedInteger("job_id")->nullable();
            $table->string("job_name", 500)->nullable();
            $table->string("job_payload", 5000)->nullable();
            $table->string("status")->nullable();
            $table->string("connection_name")->nullable();
            $table->string("error_details", 5000)->nullable();
            $table->unsignedInteger("attempts")->nullable();

            $table->dateTime("start_time")->nullable();
            $table->dateTime("finish_time")->nullable();

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
        Schema::dropIfExists('queue_log');
    }
}