<?php

namespace App\Http\Controllers;

use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

use App\Book;
use App\Facades\NDL;
use App\User;
use Auth;

class BookBaseController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    protected function response(int $status = 200, array $data = NULL) {
        return response([ 'status' => $status, 'data' => $data ], $status);
    }

    public function index(Request $request)
    {
        return [ 'data' => Book::currentUser() ];
    }

    public function create(Request $request)
    {
        $validation = Validator::make($request->all(), [
            'code' => [ 'required', 'regex:/^(.{8}|.{10}|.{13})$/' ],
        ]);

        if ($validation->passes()) {
            $book = NDL::query($request->code);
            if (isset($book)) {
                try {
                    Book::create(Book::formatter($book));

                    $user = Auth::user();
                    $user->next_id++;
                    $user->save();

                    return $this->response(200, $book);
                } catch (QueryException $e) {
                    return $this->response(409, $book);
                }
            } else {
                return $this->response(404);
            }
        } else {
            return $this->response(422, $validation->errors()->all());
        }
    }


    public function delete(Request $request)
    {
        $validation = Validator::make($request->all(), [ 'id' => 'required|integer' ]);
        if ($validation->passes()) {
            $book = Book::search($request->id);
            if ($book->count()) {
                $book->delete();

                return $this->response();
            } else {
                return $this->response(404);
            }
        } else {
            return $this->response(422, $validation->errors()->all());
        }
    }
}
