<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTTeamTacTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('t_team_tac', function (Blueprint $table) {
            $table->unsignedInteger('TAC_ID');
            $table->unsignedInteger('TEAM_ID');
            $table->timestamps();

             $table->foreign('TAC_ID')->on('t_tactic')->references('TAC_ID')->onDelete('cascade');
            $table->foreign('TEAM_ID')->on('t_team')->references('TEAM_ID')->onDelete('cascade');
           
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('t_team_tac');
    }
}
