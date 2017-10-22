<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLassAnalysesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lass_analyses', function (Blueprint $table) {
            $table->increments('id');
            $table->string('uuid')->unique();
            $table->boolean('indoor')->default(false);
            $table->boolean('shortterm_pollution')->default(false);
            $table->boolean('longterm_pollution')->default(false);
            $table->tinyInteger('ranking')->nullable();
            $table->timestamp('malfunction_at')->nullable();
            $table->timestamp('pollution_at')->nullable();
            $table->timestamp('ranking_at')->nullable();
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
        Schema::dropIfExists('lass_analyses');
    }
}
