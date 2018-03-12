<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class DropGeometriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('geometries');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::create('geometries', function (Blueprint $table) {
            $table->increments('id');
            $table->string('country');
            $table->string('level1')->nullable();
            $table->string('level2')->nullable();
            $table->string('level3')->nullable();
            $table->string('level4')->nullable();
            $table->decimal('westlng', 10, 7);
            $table->decimal('eastlng', 10, 7);
            $table->decimal('northlat', 10, 7);
            $table->decimal('southlat', 10, 7);
            $table->timestamps();

            $table->unique(['westlng', 'eastlng', 'northlat', 'southlat']);
        });
    }
}
