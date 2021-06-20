<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTRoundTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('t_round', function (Blueprint $table) {
            $table->increments('RND_ID');
            $table->string('RND_NAME');
            $table->Integer('TEAM_HOME');
            $table->Integer('TEAM_AWAY');
            $table->Boolean('RND_PLAY');
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
        Schema::dropIfExists('t_round');
    }
}
