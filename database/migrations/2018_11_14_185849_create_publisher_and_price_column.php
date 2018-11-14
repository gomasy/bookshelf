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
                if ($book->isbn !== null) {
                    $query = NDL::query($book->isbn);
                    \DB::table('books')->where('id', $book->id)
                        ->where('user_id', $book->user_id)
                        ->update([
                            'publisher' => $query['publisher'],
                            'price' => $query['price'],
                        ]);
                    echo "Updated: {$book->user_id} -> {$book->title}\n";
                }
            }
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('books', function (Blueprint $table) {
            $table->dropColumn('publisher');
            $table->dropColumn('price');
        });
    }
}
