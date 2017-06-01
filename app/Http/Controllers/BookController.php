<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class BookController extends BookBaseController
{
    public function index(Request $request)
    {
        return $request->ajax() ? parent::index($request) : redirect('/');
    }

    public function create(Request $request)
    {
        $result = parent::create($request);

        return $request->ajax() ? $result :
            redirect('/')->with('result', $result->getOriginalContent());
    }
}
