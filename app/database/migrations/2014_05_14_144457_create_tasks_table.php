<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

use Nirland\TaskyLand\Models\Task;

class CreateTasksTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create('tasks', function(Blueprint $table) {
            $table->increments('id')->unsigned();
            $table->string('title', 255);
            $table->text('description');
            $table->integer('user_id')->unsigned();
            $table->integer('project_id')->unsigned();
            $table->date('date_end')->nullable();
            $table->enum('kind', array(Task::KIND_NORMAL, Task::KIND_IMPORTANT))->default(Task::KIND_NORMAL);
            $table->enum('status', array(Task::STATUS_OPENED, Task::STATUS_CLOSED, Task::STATUS_DELAYED))->default(Task::STATUS_OPENED);
            $table->timestamps();
            $table->foreign('user_id')
                    ->references('id')->on('users');
            $table->foreign('project_id')
                    ->references('id')->on('projects')
                    ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::drop('tasks');
    }

}
