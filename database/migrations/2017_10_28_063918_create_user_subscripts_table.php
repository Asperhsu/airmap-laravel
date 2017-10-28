<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserSubscriptsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_subscripts', function (Blueprint $table) {
            $table->increments('id');
            $table->string('fb_m_id');
            $table->integer('group_id')->nullable();
            $table->string('name')->nullable();
            $table->integer('geometry_id')->nullable();
            $table->timestamps();

            $table->unique(['fb_m_id', 'group_id', 'name']);
            $table->unique(['fb_m_id', 'geometry_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_subscripts');
    }
}
