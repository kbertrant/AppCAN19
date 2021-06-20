<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTMatchTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('t_match', function (Blueprint $table) {
            $table->increments('MATCH_ID');
            $table->unsignedInteger('MATCH_T1_TAC');
            $table->unsignedInteger('MATCH_T2_TAC');
            $table->unsignedInteger('MATCH_T1');
            $table->unsignedInteger('MATCH_T2');
            $table->unsignedInteger('MATCH_GRP');
            $table->unsignedInteger('MATCH_CAN_PHASE');
            $table->unsignedInteger('MATCH_GRP_PHASE');
            $table->string('MATCH_CODE');
            $table->Integer('MATCH_T1_VAL');
            $table->Integer('MATCH_T1_BON');
            $table->Integer('MATCH_T1_APT');
            $table->Integer('MATCH_T1_SCO');
            $table->Integer('MATCH_T1_ATT_BON');
            $table->Integer('MATCH_T1_MID_BON');
            $table->Integer('MATCH_T1_DEF_BON');
            $table->Integer('MATCH_T2_VAL');
            $table->Integer('MATCH_T2_BON');
            $table->Integer('MATCH_T2_APT');
            $table->Integer('MATCH_T2_SCO');
            $table->Integer('MATCH_T2_ATT_BON');
            $table->Integer('MATCH_T2_MID_BON');
            $table->Integer('MATCH_T2_DEF_BON');
            $table->string('MATCH_SCORE');
            $table->Integer('MATCH_WINNER');
            $table->timestamps();

            $table->foreign('MATCH_T1_TAC')->on('t_tactic')->references('TAC_ID')->onDelete('cascade');
            $table->foreign('MATCH_T2_TAC')->on('t_tactic')->references('TAC_ID')->onDelete('cascade');
            $table->foreign('MATCH_T1')->on('t_match')->references('MATCH_ID')->onDelete('cascade');
            $table->foreign('MATCH_T2')->on('t_match')->references('MATCH_ID')->onDelete('cascade');
            $table->foreign('MATCH_GRP')->on('t_group')->references('GROUP_ID')->onDelete('cascade');
            
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('t_match');
    }
}
