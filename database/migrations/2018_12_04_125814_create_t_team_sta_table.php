<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTTeamStaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('t_team_sta', function (Blueprint $table) {
            $table->increments('TEAM_STA_ID');
            $table->unsignedInteger('TEAM_ID');
            $table->unsignedInteger('RNK_ID');
            $table->unsignedInteger('GRP_ID');
            $table->Integer('TEAM_WIN');
            $table->Integer('TEAM_LOS');
            $table->Integer('TEAM_DRAW');
            $table->Integer('TEAM_PTS');
            $table->Integer('TEAM_SCO');
            $table->Integer('TEAM_CON');
            $table->Integer('TEAM_AVG');
            $table->timestamps();

            $table->foreign('TEAM_ID')->on('t_team')->references('TEAM_ID')->onDelete('cascade');
            $table->foreign('RNK_ID')->on('t_rank')->references('RNK_ID')->onDelete('cascade');
            $table->foreign('GRP_ID')->on('t_group')->references('GRP_ID')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('t_team_sta');
    }
}
