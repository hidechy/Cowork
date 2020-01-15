<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLineTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('line', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->string('line_cd');
            $table->string('company_cd');
            $table->string('line_name');
            $table->string('line_name_k');
            $table->string('line_name_h');
            $table->string('line_color_c');
            $table->string('line_color_t');
            $table->string('line_type');
            $table->string('lon');
            $table->string('lat');
            $table->string('zoom');
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
        Schema::dropIfExists('line');
    }
}
