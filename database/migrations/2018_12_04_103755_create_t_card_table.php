<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTCardTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('t_card', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('CRD_ID');
            $table->integer('MATCH_ID')->unsigned();
            $table->integer('PLY_ID')->unsigned();
            $table->integer('T_P_PLY_ID')->unsigned();
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
        Schema::dropIfExists('t_card');
    }
}
