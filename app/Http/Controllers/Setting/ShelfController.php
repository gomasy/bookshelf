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

    /**
     * 本棚の設定画面のビューを返す
     *
     * @return View
     */
    public function index(): object
    {
        $shelves = Bookshelf::get()->toArray();

        return view('settings.shelves.index', compact('shelves'));
    }
}
