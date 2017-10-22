<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateThingspeaksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('thingspeaks', function (Blueprint $table) {
            $table->increments('id');
            $table->string('channel')->unique();
            $table->string('party')->nullable();
            $table->string('maker');
            $table->text('fields_map')->nullable();
            $table->boolean('active')->default(true);
            $table->integer('group_id');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('thingspeaks');
    }
}
