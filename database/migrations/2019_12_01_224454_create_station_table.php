<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('station', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->string('station_cd');
            $table->string('station_g_cd');
            $table->string('station_name');
            $table->string('station_name_k');
            $table->string('station_name_r');
            $table->string('line_cd');
            $table->string('pref_cd');
            $table->string('post');
            $table->string('add');
            $table->string('lon');
            $table->string('lat');
            $table->string('open_ymd');
            $table->string('close_ymd');
            $table->string('e_status');
            $table->string('e_sort');

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
        Schema::dropIfExists('station');
    }
}
