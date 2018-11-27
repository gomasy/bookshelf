<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeBooksTablePrimaryKey extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('books', function (Blueprint $table) {
            $table->dropForeign('books_bookshelf_id_foreign');
            $table->dropForeign('books_user_id_foreign');
            $table->dropUnique('books_user_id_isbn_unique');
            $table->dropUnique('books_user_id_jpno_unique');
            $table->dropPrimary([ 'id', 'user_id' ]);
            $table->dropColumn('user_id');
            $table->primary([ 'id', 'bookshelf_id' ]);
            $table->unique([ 'bookshelf_id', 'isbn' ]);
            $table->unique([ 'bookshelf_id', 'jpno' ]);
            $table->foreign('bookshelf_id')
                  ->references('id')->on('bookshelves');
        });

        Schema::table('bookshelves', function (Blueprint $table) {
            $table->integer('next_id')->unsigned()->after('user_id')->default(0);
        });

        foreach (\DB::table('users')->get() as $user) {
            \DB::table('bookshelves')->where('user_id', $user->id)
                ->update([ 'next_id' => $user->next_id ]);
        }

        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('next_id');
        });
    }
}
