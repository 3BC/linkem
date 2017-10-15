<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRoomsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rooms', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->text('description')->nullable();
            $table->boolean('private')->default(0);
            $table->integer('owner_id');
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('room_moderators', function (Blueprint $table) {
            $table->integer('room_id');
            $table->integer('user_id');
            $table->primary(['room_id', 'user_id']);
        });

        Schema::create('room_followers', function (Blueprint $table) {
            $table->integer('room_id');
            $table->integer('user_id');
            $table->primary(['room_id', 'user_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('rooms');
    }
}
