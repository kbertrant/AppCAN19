<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTTeamTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('t_team', function (Blueprint $table) {
            $table->increments('TEAM_ID');
            $table->string('TEAM_NAME');
            $table->string('TEAM_CODE');
            $table->string('TEAM_FLAG');
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
        Schema::dropIfExists('t_team');
    }
}
