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
            abort(403);
        }

        abort(400);
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
        $default = Bookshelf::default();

        if ($request->id !== $default->id) {
            if (!$request->recursive) {
                $books = Book::where('bookshelf_id', $request->id);
                foreach ($books->get() as $book) {
                    $book->where('id', $book->id)
                        ->update([ 'bookshelf_id' => $default->id ]);
                }
            }

            if (Bookshelf::destroy($request->id)) {
                return response(null, 204);
            }
        }

        abort(400);
    }
}
