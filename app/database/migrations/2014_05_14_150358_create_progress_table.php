<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProgressTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create('progress', function(Blueprint $table) {
            $table->increments('id')->unsigned();
            $table->string('title', 255);
            $table->text('message');
            $table->string('revision', 255)->nullable();
            $table->integer('hours')->default(0);
            $table->integer('minutes')->default(0);
            $table->integer('user_id')->unsigned();
            $table->integer('task_id')->unsigned();
            $table->timestamps();
            $table->foreign('user_id')
                    ->references('id')->on('users');
            $table->foreign('task_id')
                    ->references('id')->on('tasks')
                    ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::drop('progress');
    }

}
