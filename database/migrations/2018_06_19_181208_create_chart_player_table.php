<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateChartPlayerTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('chart_player', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('chart_id');
            $table->unsignedInteger('player_id');
            $table->binary('stats')->nullable();
        });

        Schema::table('chart_player', function(Blueprint $table) {
            $table->foreign('chart_id')->references('id')->on('charts');
            $table->foreign('player_id')->references('id')->on('players');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('chart_player');
    }
}
