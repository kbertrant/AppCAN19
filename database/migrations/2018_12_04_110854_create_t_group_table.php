<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTGroupTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('t_group', function (Blueprint $table) {
            $table->increments('GRP_ID');
            $table->string('GRP_NAME');
            $table->string('GRP_STADIUM1');
            $table->string('GRP_STADIUM2');
            $table->string('GRP_STADIUM3');
            $table->string('GRP_STADIUM4');
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
        Schema::dropIfExists('t_group');
    }
}
