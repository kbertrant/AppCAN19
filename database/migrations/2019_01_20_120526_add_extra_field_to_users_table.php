<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddExtraFieldToUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('phone');
            $table->string('city');
            $table->string('gender');
            $table->string('profession');
            $table->string('hobby');
            $table->string('sport');
            $table->Integer('TEAM_ID');
            $table->string('age');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('phone');
            $table->dropColumn('city');
            $table->dropColumn('gender');
            $table->dropColumn('profession');
            $table->dropColumn('hobby');
            $table->dropColumn('sport');
            $table->dropColumn('team_id');
            $table->dropColumn('age');
        });
    }
}
