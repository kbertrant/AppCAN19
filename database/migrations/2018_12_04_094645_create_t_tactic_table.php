<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTTacticTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('t_tactic', function (Blueprint $table) {
            $table->increments('TAC_ID');
            $table->string('TAC_NAME');
            $table->string('TAC_CODE');
            $table->float('TAC_BON');
            $table->float('TAC_OPP');
            $table->float('TAC_APT');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('t_tactic');
    }
}
