<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStatusesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('statuses', function (Blueprint $table) {
            $table->id()->unsigned();
            $table->string('name');
        });

        foreach ([ '未読', '読書中', '既読' ] as $name) {
            \DB::table('statuses')->insert([ 'name' => $name ]);
        }

        Schema::table('books', function (Blueprint $table) {
            $table->integer('status_id')->unsigned()
                  ->after('bookshelf_id')->default(1);
            $table->foreign('status_id')
                ->references('id')->on('statuses');
        });
    }
}
