<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RemoveNextIdAndRenumber extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('bookshelves', function (Blueprint $table) {
            $table->dropColumn('next_id');
        });

        Schema::table('books', function (Blueprint $table) {
            $table->integer('new_id')->unsigned()->after('id');
        });

        $books = \DB::table('books')->get();
        for ($i = 0; $i < count($books); $i++) {
            \DB::table('books')
                ->where('bookshelf_id', $books[$i]->bookshelf_id)
                ->where('id', $books[$i]->id)
                ->update([ 'new_id' => $i + 1 ]);
        }

        Schema::table('books', function (Blueprint $table) {
            $table->dropPrimary([ 'id', 'bookshelf_id' ]);
            $table->dropColumn('id');
            $table->renameColumn('new_id', 'id');
            $table->primary('id');
        });

        \DB::statement('ALTER TABLE `books` CHANGE `id` `id` INT UNSIGNED AUTO_INCREMENT NOT NULL;');
    }
}
