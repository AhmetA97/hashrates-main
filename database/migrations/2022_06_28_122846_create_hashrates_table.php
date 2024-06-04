<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('hashrates', function (Blueprint $table) {
            $table->id();
            $table->string('worker_id');
            $table->string('worker_name');
            $table->date('date');
            $table->string('hashrate');
            $table->string('reject');
            $table->timestamps();

            $table->foreign('worker_id')->references('worker_id')->on('workers');
        });
    }

    /**

     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('hashrates');
    }
};
