<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTPlayerTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('t_player', function (Blueprint $table) {
            $table->increments('PLY_ID');
            $table->unsignedInteger('TEAM_ID');
            $table->unsignedInteger('LINE_ID');
            $table->string('PLY_NAME');
            $table->string('PLY_NBR');
            $table->Integer('PLY_GKP_VAL');
            $table->Integer('PLY_DEF_VAL');
            $table->Integer('PLY_MID_VAL');
            $table->Integer('PLY_ATT_VAL');
            $table->Integer('PLY_VAL');
            $table->timestamps();

            $table->foreign('TEAM_ID')->on('t_team')->references('TEAM_ID')->onDelete('cascade');
            $table->foreign('LINE_ID')->on('t_player_line')->references('LINE_ID')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('t_player');
    }
}
