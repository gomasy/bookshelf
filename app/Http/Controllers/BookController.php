<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests\BookCreateRequest;

class BookController extends BookBaseController
{
    public function index(Request $request)
    {
        return $request->ajax() ? parent::index($request) : redirect('/');
    }

    public function create(BookCreateRequest $request)
    {
        $result = parent::create($request);

        return $request->ajax() ? $result :
            redirect('/')->with('result', $result->getOriginalContent());
    }
}
