<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id()->unsigned();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('password');
            $table->integer('next_id')->unsigned()->default(1);
            $table->rememberToken();
            $table->timestamps();
        });
    }
}
