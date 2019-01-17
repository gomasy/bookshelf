<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

use App\Book;
use App\Bookshelf;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    /**
     * リクエストがあったリソースの取得権限があるか確認する。
     *
     * @return void
     */
    protected function checkAuthorize(Request $request): void
    {
        if (!$request->ajax()) {
            abort(404);
        } elseif (isset($request->sid) && !Bookshelf::find($request->sid)) {
            abort(401);
        }
    }

    /**
     * 指定した本が既に登録されているか
     *
     * @param Request $request
     * @param array $books
     * @return array
     */
    protected function checkConflict(Collection $books, int $sid): object
    {
        return $books->reject(function ($book) use ($sid) {
            return (bool)Book::shelves($sid)
                ->where(function ($query) use ($book) {
                    $query->where('isbn', $book['isbn'])
                          ->orWhere('jpno', $book['jpno']);
                })->count();
        });
    }
}
