<?php

namespace App\Http\Controllers;

use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

use App\Http\Requests\BookCreateRequest as CreateRequest;
use App\Http\Requests\BookEditRequest as EditRequest;
use App\Http\Requests\BookDeleteRequest as DeleteRequest;

use App\Book;
use App\Facades\NDL;
use App\User;

class BookBaseController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function getHeader()
    {
        return [
            'id' => \Auth::user()['next_id'],
            'user_id' => \Auth::id(),
        ];
    }

    public function index(Request $request)
    {
        return response()->ajax(Book::get());
    }

    public function create(CreateRequest $request)
    {
        $book = NDL::query($request->code);
        if (isset($book)) {
            try {
                $book = array_merge($this->getHeader(), $book);
                Book::create($book);

                $user = \Auth::user();
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

    public function edit(EditRequest $request)
    {
        $book = Book::find($request->id);
        $book->fill($request->all())->save();

        return response()->ajax($book);
    }

    public function delete(DeleteRequest $request)
    {
        $book = Book::search($request->id);
        return $book->count() ? $book->delete() : response(NULL, 404);
    }
}
