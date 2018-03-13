<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class DropSiteGeometriesRecordTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('site_geometries');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::create('site_geometries', function (Blueprint $table) {
            $table->integer('group_id');
            $table->string('uuid');
            $table->integer('geometry_id');

            $table->primary(['group_id', 'uuid', 'geometry_id']);
        });
    }
}
