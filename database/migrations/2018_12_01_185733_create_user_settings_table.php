<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_settings', function (Blueprint $table) {
            $table->integer('id')->unsigned();
            $table->integer('display_format')->unsigned()->default(0);
            $table->integer('animation')->unsigned()->default(0);
            $table->string('theme')->nullable();
            $table->timestamps();
            $table->foreign('id')
                  ->references('id')->on('users')
                  ->onDelete('cascade');
            $table->primary('id');
        });
    }
}
