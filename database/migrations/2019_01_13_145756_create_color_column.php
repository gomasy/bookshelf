<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateColorColumn extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('statuses', function (Blueprint $table) {
            $table->string('color');
        });

        $color = [
            '黄' => '#fff9c4',
            '水色' => '#bbdefb',
            '緑' => '#c8e6c9',
            '赤' => '#ffccbc',
            '青' => '#cfd8dc',
            '紫' => '#e1bee7',
        ];

        for ($i = 0; $i < 3; $i++) {
            \DB::table('statuses')
                ->where([ 'id' => $i + 1 ])
                ->update([
                    'name' => array_keys($color)[$i],
                    'color' => array_values($color)[$i],
                ]);
        }

        for ($i = 3; $i < 6; $i++) {
            \DB::table('statuses')
                ->insert([
                    'name' => array_keys($color)[$i],
                    'color' => array_values($color)[$i],
                ]);
        }
    }
}
