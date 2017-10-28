<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRecordsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('records', function (Blueprint $table) {
            $table->increments('id');
            $table->string('uuid');
            $table->string('name');
            $table->string('maker');
            $table->float('lat', 8, 5)->nullable();
            $table->float('lng', 8, 5)->nullable();
            $table->integer('group_id');
            $table->integer('fetch_id');

            $table->integer('pm25')->nullable();
            $table->float('humidity', 6, 3)->nullable();
            $table->float('temperature', 6, 3)->nullable();

            $table->timestamp('published_at');
            $table->timestamps();

            $table->unique(['uuid', 'group_id', 'published_at']);
            $table->index('uuid');
            $table->index('name');
            $table->index('published_at');
        });
        
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('records');
    }
}
