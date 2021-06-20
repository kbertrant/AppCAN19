<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTGoalTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('t_goal', function (Blueprint $table) {
            $table->increments('GOAL_ID');
            $table->unsignedInteger('MATCH_ID');
            $table->unsignedInteger('PLY_ID');
            $table->unsignedInteger('T_P_PLY_ID');
            $table->timestamps();

            $table->foreign('MATCH_ID')->on('t_match')->references('MATCH_ID')->onDelete('cascade');
            $table->foreign('PLY_ID')->on('t_player')->references('PLY_ID')->onDelete('cascade');
            $table->foreign('T_P_PLY_ID')->on('t_player')->references('PLY_ID')->onDelete('cascade');
            
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('t_goal');
    }
}
