<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTTacticPosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('t_tactic_pos', function (Blueprint $table) {
            $table->unsignedInteger('TAC_ID');
            $table->unsignedInteger('TAC_POS_ID');
            $table->unsignedInteger('TAC_POS_NAME');
            $table->timestamps();

            $table->foreign('TAC_ID')->on('t_tactic')->references('TAC_ID')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('t_tactic_pos');
    }
}
