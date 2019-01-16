<?php

namespace App\Http\Controllers\Setting;

use Illuminate\Database\QueryException;
use Illuminate\Http\Request;

use App\Http\Controllers\Controller;
use App\Book;
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

    /**
     * 本棚を作成
     *
     * @param Request $request
     * @return Response
     */
    public function create(Request $request): object
    {
        try {
            $shelf = new Bookshelf($request->all());
            $shelf->user_id = \Auth::id();
            if ($shelf->save()) {
                return response(null, 204);
            }
        } catch (QueryException $e) {
            abort(403, 'Duplicate or invalid name.');
        }

        abort(400, 'Invalid request.');
    }

    /**
     * 本棚を削除
     *
     * @param Request $request
     * @return Response
     */
    public function delete(Request $request): object
    {
        $this->checkAuthorize($request);
        $id = $request->id;
        $default = Bookshelf::default();

        if ($id !== $default->id) {
            if (!$request->recursive) {
                $books = Book::where('bookshelf_id', $id);

                foreach ($this->checkConflict($books->get(), $id) as $book) {
                    $book->where('id', $book->id)
                        ->update([ 'bookshelf_id' => $default->id ]);
                }
            }

            if (Bookshelf::destroy($request->id)) {
                return response(null, 204);
            }
        }

        abort(400, 'Invalid request.');
    }
}
