<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTPlayerTacPosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('t_player_tac_pos', function (Blueprint $table) {
            $table->unsignedInteger('TAC_ID_POS');
            $table->unsignedInteger('PLY_ID');
            $table->timestamps();

            $table->foreign('PLY_ID')->on('t_player')->references('PLY_ID')->onDelete('cascade');
            $table->foreign('TAC_ID_POS')->on('t_tactic')->references('TAC_ID')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('t_player_tac_pos');
    }
}
