<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create('users', function(Blueprint $table) {
            $table->increments('id')->unsigned();
            $table->string('username', 30)->unique();
            $table->string('password', 255);
            $table->string('email', 150);
            $table->string('firstname', 30);
            $table->string('surname', 30);
            $table->enum('role', array(User::ROLE_USER, User::ROLE_ADMIN));
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::drop('users');
    }

}
