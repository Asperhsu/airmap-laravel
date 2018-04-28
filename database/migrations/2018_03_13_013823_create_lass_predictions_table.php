<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLassPredictionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lass_predictions', function (Blueprint $table) {
            $table->increments('id');
            $table->string('uuid')->index;
            $table->string('method');
            $table->float('current', 7, 4)->nullable();
            $table->float('add1h', 7, 4)->nullable();
            $table->float('add2h', 7, 4)->nullable();
            $table->float('add3h', 7, 4)->nullable();
            $table->float('add4h', 7, 4)->nullable();
            $table->float('add5h', 7, 4)->nullable();
            $table->timestamp('published_at');
            $table->timestamps();

            $table->index('uuid');
            $table->index('method');
            $table->index('published_at');
            $table->unique(['uuid', 'method', 'published_at']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('lass_predictions');
    }
}
