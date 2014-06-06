<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersProjectsTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create('users_projects', function(Blueprint $table) {
            $table->integer('user_id')->unsigned();
            $table->integer('project_id')->unsigned();
            $table->primary(array('user_id', 'project_id'));
            $table->foreign('user_id')
                    ->references('id')->on('users')
                    ->onDelete('cascade');
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
        Schema::drop('users_projects');
    }

}
