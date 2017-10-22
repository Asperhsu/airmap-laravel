<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGeometryRecordTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('geometry_record', function (Blueprint $table) {
            $table->integer('geometry_id')->unsigned();
            $table->integer('record_id')->unsigned();

            $table->primary(['geometry_id', 'record_id']);

            $table->foreign('record_id')
                  ->references('id')->on('records')
                  ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('geometry_record');
    }
}
