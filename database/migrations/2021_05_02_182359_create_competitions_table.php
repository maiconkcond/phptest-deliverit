<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCompetitionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('competitions', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedBigInteger('runners_id');
            $table->unsignedBigInteger('races_id');
            $table->time('race_start_time');
            $table->time('race_end_time');
            $table->foreign('runners_id')->references('id')->on('runners');
            $table->foreign('races_id')->references('id')->on('races');
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
        Schema::dropIfExists('competitions');
    }
}
