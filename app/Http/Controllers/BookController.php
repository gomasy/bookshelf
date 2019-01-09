<?php
declare(strict_types=1);

namespace App\Http\Controllers;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;

use Google\Cloud\Vision\V1\ImageAnnotatorClient;

use App\Http\Requests\BookCreateRequest as CreateRequest;
use App\Http\Requests\BookDeleteRequest as DeleteRequest;
use App\Http\Requests\BookEditRequest as EditRequest;
use App\Http\Requests\BookFetchRequest as FetchRequest;

use App\Book;
use App\Bookshelf;
use App\User;

use App\Exceptions\TimeoutException;

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
     * 本の登録に必要な書籍番号とユーザー情報を含んだデータを生成する。
     *
     * @return array
     */
    protected function appendHeader(array $book, int $sid): object
    {
        $book = new Book($book);
        $book->bookshelf_id = $sid;
        $book->status_id = 1;

        return $book;
    }

    /**
     * 指定した本が既に登録されているか
     *
     * @param Request $request
     * @param Book $book
     * @return bool
     */
    protected function checkConflict(Request $request, array $books): array
    {
        $items = [];
        foreach ($books as $book) {
            $count = Book::shelves($request->sid)->where(function ($query) use ($book) {
                $query->where('isbn', $book['isbn'])->orWhere('jpno', $book['jpno']);
            })->count();

            if (!$count) {
                array_push($items, $book);
            }
        }

        return $items;
    }

    /**
     * 本の検索
     *
     * @param Request $request
     * @param Book $books
     * @return array
     */
    protected function search(Request $request, Book $books): array
    {
        $books = Book::shelves($request->sid);
        foreach ([ 'title', 'authors' ] as $column) {
            if ($request->query($column) !== null) {
                $books = $books->where($column, 'like', '%'.$request->query($column).'%');
                $count = $books->count();
            }
        }

        return [ $books, $count ?? $books->count() ];
    }

    /**
     * ページネーション処理
     *
     * @param Request $request
     * @param Builder $books
     * @return Builder
     */
    protected function paginate(Request $request, Builder $books): object
    {
        if (isset($request->offset) && isset($request->limit)) {
            $books = $books->offset($request->offset)->limit($request->limit);
        }

        return $books;
    }

    /**
     * ソート処理
     *
     * @param Request $request
     * @param Builder $books
     * @return Builder
     */
    protected function sort(Request $request, Builder $books): object
    {
        if (isset($request->sort) && isset($request->order)) {
            $books = $books->orderBy($request->sort, $request->order);
        }

        return $books;
    }

    /**
     * 登録済みの本のリストを返す。
     *
     * @param Request $request
     * @return array
     */
    public function list(Request $request): array
    {
        $this->checkAuthorize($request);

        list($books, $count) = $this->search($request, new Book);
        $books = $this->sort($request, $this->paginate($request, $books));

        return [
            'data' => $books->get(),
            'total' => $count,
        ];
    }

    /**
     * 本を登録する。
     *
     * @param Request $request
     * @param return Book|Response
     */
    public function create(CreateRequest $request): array
    {
        $this->checkAuthorize($request);

        return \DB::transaction(function () use ($request) {
            $books = $request->p;
            for ($i = 0; $i < count($books); $i++) {
                $books[$i] = $this->appendHeader($books[$i], $request->sid);
                $books[$i]->save();
            }

            return $books;
        });
    }

    /**
     * 登録済みの本を編集する。
     *
     * @param EditRequest $request
     * @return Book
     */
    public function edit(EditRequest $request): object
    {
        $this->checkAuthorize($request);

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
        $this->checkAuthorize($request);
        try {
            $books = \NDL::query($request->p, $request->type);
        } catch (TimeoutException $e) {
            abort(408, 'Timeout exceeded');
        }

        if (count($books)) {
            $items = $this->checkConflict($request, $books);
            if (!count($items)) {
                return response($books, 409);
            }

            return response($items);
        }

        abort(404, 'Book(s) not found');
    }

    /**
     * 表紙の画像を取得する（プロキシ）
     *
     * @param Request $request
     * @return Response
     */
    public function fetchImage(Request $request): object
    {
        $key = md5($request->fullUrl());
        $image = \Cache::store('file')->get($key);
        if ($image === null) {
            $image = \AmazonImages::fetch($request->path(), $request->text);
            \Cache::store('file')->put($key, $image, 10080);
        }

        return response($image)->withHeaders([
            'Cache-Control' => 'max-age=604800, public',
            'Content-Type' => 'image/jpeg',
        ]);
    }

    public function detectImage(Request $request)
    {
        $response = (new ImageAnnotatorClient())
            ->webDetection($request->getContent());
        $web = $response->getWebDetection();

        $labels = [];
        foreach ($web->getBestGuessLabels() as $label) {
            array_push($labels, $label->getLabel());
        }

        return [ 'result' => $labels ];
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
        $this->checkAuthorize($request);

        \DB::transaction(function () use ($request) {
            if (!Book::destroy($request->ids)) {
                abort(400, 'Invalid request');
            }
        });

        return response(null, 204);
    }
}
