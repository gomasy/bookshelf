<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBookshelvesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bookshelves', function (Blueprint $table) {
            $table->increments('id')->unsigned();
            $table->integer('user_id')->unsigned();
            $table->foreign('user_id')
                  ->references('id')->on('users')
                  ->onDelete('cascade');
            $table->string('name');
            $table->unique([ 'user_id', 'name' ]);
        });

        Schema::table('books', function (Blueprint $table) {
            $table->integer('bookshelf_id')->unsigned()
                  ->nullable()->after('user_id');
            $table->foreign('bookshelf_id')
                  ->references('id')->on('bookshelves')
                  ->onDelete('set null');
        });

        foreach (\DB::table('users')->get() as $user) {
            \DB::table('bookshelves')->insert([
                'user_id' => $user->id,
                'name' => 'default',
            ]);

            $bookshelf_id = \DB::table('bookshelves')
                ->where([ 'user_id' => $user->id, 'name' => 'default' ])
                ->get()[0]->id;

            \DB::table('books')
                ->where('user_id', $user->id)
                ->update([ 'bookshelf_id' => $bookshelf_id ]);
        }
    }
}
