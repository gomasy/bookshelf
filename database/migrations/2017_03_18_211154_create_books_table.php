<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBooksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('books', function (Blueprint $table) {
            $table->integer('id')->unsigned();
            $table->integer('user_id')->unsigned();
            $table->foreign('user_id')
                  ->references('id')->on('users')
                  ->onDelete('cascade');
            $table->string('title');
            $table->string('volume')->nullable();
            $table->string('authors');
            $table->char('isbn', 13)->nullable();
            $table->char('jpno', 10)->nullable();
            $table->date('published_date');
            $table->string('ndl_url');
            $table->primary([ 'id', 'user_id' ]);
            $table->unique([ 'user_id', 'isbn' ]);
            $table->unique([ 'user_id', 'jpno' ]);
        });
    }
}
