<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

use Facades\App\Libs\NDL;

class CreatePublisherAndPriceColumn extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('books', function (Blueprint $table) {
            $table->string('publisher')->nullable()->after('jpno');
            $table->string('price')->nullable()->after('publisher');
        });

        \DB::transaction(function () {
            foreach (\DB::table('books')->get() as $book) {
                try {
                    $query = NDL::query($book->isbn !== null ? $book->isbn : $book->jpno);
                    \DB::table('books')->where('id', $book->id)
                        ->where('user_id', $book->user_id)
                        ->update([
                            'publisher' => $query['publisher'] ?? null,
                            'price' => $query['price'] ?? null,
                        ]);
                    echo "Updated: {$book->user_id} -> {$book->title}\n";
                } catch (\Exception $e) {
                    echo "Errored: {$book->user_id} -> {$book->title}\n";
                }
            }
        });
    }
}
