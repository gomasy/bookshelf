<?php

namespace App\Http\Controllers;

use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

use App\Book;
use App\Facades\NDL;
use App\Http\Requests\BookCreateRequest;
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
        return response()->json([
            'data' => Book::currentUser(),
        ]);
    }

    public function create(BookCreateRequest $request)
    {
        $book = NDL::query($request->code);
        if (isset($book)) {
            try {
                Book::create(array_merge($this->getHeader(), $book));

                $user = Auth::user();
                $user->next_id++;
                $user->save();

                return response()->ajax(200, $book);
            } catch (QueryException $e) {
                return response()->ajax(409, $book);
            }
        } else {
            return response()->ajax(404);
        }
    }


    public function delete(BookDeleteRequest $request)
    {
        $book = Book::search($request->id);
        if ($book->count()) {
            $book->delete();

            return response()->ajax();
        } else {
            return response()->ajax(404);
        }
    }
}
