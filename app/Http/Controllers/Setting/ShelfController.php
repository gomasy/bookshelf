<?php
declare(strict_types=1);

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
     * 該当する本棚の全ての本をデフォルトに移動する
     * 注: 重複は除く
     *
     * @param int $sid
     */
    protected function migrateDefault(int $sid): void
    {
        \DB::transaction(function () use ($sid) {
            $books = Book::where('bookshelf_id', $sid)->get();
            $default = Bookshelf::default();

            foreach ($this->checkConflict($books, $default->id) as $book) {
                $book->where('id', $book->id)
                     ->update([ 'bookshelf_id' => $default->id ]);
            }
        });
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

        if ($request->id !== Bookshelf::default()->id) {
            if (!$request->recursive) {
                $this->migrateDefault($request->id);
            }

            if (Bookshelf::destroy($request->id)) {
                return response(null, 204);
            }
        }

        abort(400, 'Invalid request.');
    }
}
