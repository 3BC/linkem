<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGroupsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('groups', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->unique();
            $table->text('description')->nullable();
            $table->boolean('private')->default(0);
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('group_owner', function (Blueprint $table) {
            $table->integer('group_id');
            $table->integer('user_id');
            $table->primary(['group_id', 'user_id']);
        });


        Schema::create('group_moderator', function (Blueprint $table) {
            $table->integer('group_id');
            $table->integer('user_id');
            $table->primary(['group_id', 'user_id']);
        });

        Schema::create('group_user', function (Blueprint $table) {
            $table->integer('group_id');
            $table->integer('user_id');
            $table->primary(['group_id', 'user_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('groups');
        Schema::dropIfExists('group_owner');
        Schema::dropIfExists('group_moderator');
        Schema::dropIfExists('group_user');
    }
}
