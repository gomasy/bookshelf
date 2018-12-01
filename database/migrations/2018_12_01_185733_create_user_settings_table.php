<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

use Carbon\Carbon;

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

        foreach (\DB::table('users')->get() as $user) {
            $now = Carbon::now();

            \DB::table('user_settings')->insert([
                'id' => $user->id,
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }
    }
}
