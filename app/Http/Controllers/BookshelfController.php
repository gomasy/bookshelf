<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Bookshelf;

class BookshelfController extends Controller
{
    public function list()
    {
        return Bookshelf::get();
    }
}
