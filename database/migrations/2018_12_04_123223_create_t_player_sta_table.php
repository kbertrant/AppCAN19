<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTPlayerStaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('t_player_sta', function (Blueprint $table) {
            $table->increments('PLY_ID_STA');
            $table->unsignedInteger('PLY_ID');
            $table->Integer('PLY_TIT');
            $table->Integer('PLY_SUB');
            $table->Integer('PLY_SHP');
            $table->Integer('PLY_INJ');
            $table->Integer('PLY_STA');
            $table->Integer('PLY_CRD');
            $table->Integer('PLY_DSQ');
            $table->Integer('PLY_SCO');
            $table->Integer('PLY_ASS');
            $table->timestamps();

            $table->foreign('PLY_ID')->on('t_player')->references('PLY_ID')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('t_player_sta');
    }
}
