<?php

namespace App\Http\Controllers;

use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

use App\Book;
use App\Facades\NDL;
use App\Http\Requests\BookCreateRequest;
use App\Http\Requests\BookEditRequest;
use App\Http\Requests\BookDeleteRequest;
use App\User;
use Auth;

class BookBaseController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function getHeader()
    {
        return [
            'id' => Auth::user()['next_id'],
            'user_id' => Auth::id(),
        ];
    }

    public function index(Request $request)
    {
        return response()->ajax(Book::get());
    }

    public function create(BookCreateRequest $request)
    {
        $book = NDL::query($request->code);
        if (isset($book)) {
            try {
                $book = array_merge($this->getHeader(), $book);
                Book::create($book);

                $user = Auth::user();
                $user->next_id++;
                $user->save();

                return response()->ajax($book);
            } catch (QueryException $e) {
                return response()->ajax($book, 409);
            }
        } else {
            return response(NULL, 404);
        }
    }

    public function edit(BookEditRequest $request)
    {
        $book = Book::find($request->id);
        $book->title = $request->title;
        $book->volume = $request->volume ?? '';
        $book->authors = $request->authors;
        $book->published_date = $request->published_date;
        $book->save();

        return response()->ajax($book);
    }

    public function delete(BookDeleteRequest $request)
    {
        $book = Book::search($request->id);
        if ($book->count()) {
            $book->delete();
        } else {
            return response(NULL, 404);
        }
    }
}
