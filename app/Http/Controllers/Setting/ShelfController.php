<?php

namespace App\Http\Controllers\Setting;

use Illuminate\Http\Request;

use App\Http\Controllers\Controller;
use App\Bookshelf;

class ShelfController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request): object
    {
        $shelves = Bookshelf::get()->toArray();

        return $request->ajax() ?
            response($shelves) : view('settings.shelves.index', compact('shelves'));
    }
}
