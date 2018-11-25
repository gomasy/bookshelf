<?php
declare(strict_types=1);

namespace App\Http\Controllers;

use Illuminate\Database\QueryException;
use Illuminate\Http\Request;

use App\Http\Requests\BookDeleteRequest as DeleteRequest;
use App\Http\Requests\BookEditRequest as EditRequest;
use App\Http\Requests\BookFetchRequest as FetchRequest;
use App\Book;
use App\User;

class BookController extends Controller
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
     * リクエストがAjaxによるものなのか検証する
     * 違う場合は404を返す
     *
     * @return void
     */
    protected function checkAjax(Request $request): void
    {
        if (!$request->ajax()) {
            abort(404);
        }
    }

    /**
     * 連番IDをインクリメントする
     *
     * @return void
     */
    protected function incrementNextId(): void
    {
        $user = \Auth::user();
        $user->next_id++;
        $user->save();
    }

    /**
     * 本の登録に必要な書籍番号とユーザー情報を含んだデータを生成する。
     *
     * @return array
     */
    protected function appendHeader(array $book): array
    {
        return array_merge([
            'id' => \Auth::user()['next_id'],
            'user_id' => \Auth::id(),
        ], $book);
    }

    /**
     * 登録済みの本のリストを返す。
     *
     * @param Request $request
     * @return array
     */
    public function list(Request $request): array
    {
        $this->checkAjax($request);

        $books = new Book;
        foreach ([ 'title', 'authors' ] as $column) {
            if ($request->query($column) !== null) {
                $books = $books->where($column, 'like', '%'.$request->query($column).'%');
                $count = $books->count();
            }
        }

        if (isset($request->offset) && isset($request->limit)) {
            $books = $books->offset($request->offset)->limit($request->limit);
        }

        if (isset($request->sort) && isset($request->order)) {
            $books = $books->orderBy($request->sort, $request->order);
        }

        return [
            'data' => $books->get(),
            'total' => $count ?? Book::count(),
        ];
    }

    /**
     * 本を登録する。
     *
     * @param Request $request
     * @param return Book|Response
     */
    public function create(Request $request): object
    {
        $this->checkAjax($request);

        $book = \Cache::get($request->id);
        if ($book) {
            if (Book::create($this->appendHeader($book))) {
                $this->incrementNextId();
            }

            return response($book);
        }

        return response([], 400);
    }

    /**
     * 登録済みの本を編集する。
     *
     * @param EditRequest $request
     * @return Book
     */
    public function edit(EditRequest $request): object
    {
        $this->checkAjax($request);

        $book = Book::find($request->id);
        $book->fill($request->all())->save();

        return $book;
    }

    /**
     * 本の情報を取得する
     * 取得した本が既に登録されていた場合は409、
     * 一次ソース側に存在しない場合は404を返す。
     *
     * @param FetchRequest $request
     * @return Response
     */
    public function fetch(FetchRequest $request): object
    {
        $this->checkAjax($request);

        $book = new Book(\NDL::query($request->code));
        if ($book->title !== null) {
            $count = Book::where('isbn', $book->isbn)
                ->orWhere('jpno', $book->jpno)->count();
            if ($count) {
                return response($book, 409);
            }

            $cid = md5($book->isbn . $book->jpno);
            \Cache::put($cid, $book->toArray(), 5);

            return response($book)->header('X-Request-Id', $cid);
        }

        return response([], 404);
    }

    /**
     * 表紙の画像を取得する（プロキシ）
     *
     * @param Request $request
     * @return Response
     */
    public function fetchImage(Request $request): object
    {
        $key = md5($request->path());
        $image = \Cache::store('file')->get($key);
        if ($image === null) {
            $image = \AmazonImages::fetch($request->path());
            \Cache::store('file')->put($key, $image, 1440);
        }

        return response($image)->withHeaders([
            'Cache-Control' => 'max-age=604800, public',
            'Content-Type' => 'image/jpeg',
        ]);
    }

    /**
     * 登録されている本を削除する。
     * 削除に成功した場合は204、他のセッションで削除済みの場合は404を返す。
     *
     * @param DeleteRequest $request
     * @return Response
     */
    public function delete(DeleteRequest $request): object
    {
        $this->checkAjax($request);

        \DB::beginTransaction();
        try {
            if (!Book::destroy($request->ids)) {
                return response(\DB::rollback(), 400);
            }

            return response(\DB::commit(), 204);
        } catch (\Exception $e) {
            return response(\DB::rollback(), 500);
        }
    }
}
