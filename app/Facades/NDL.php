<?php
namespace App\Facades;

use Illuminate\Support\Facades\Facade;

class NDL extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'ndl';
    }
}
