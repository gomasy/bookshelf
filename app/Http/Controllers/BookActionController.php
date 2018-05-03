<?php

namespace App\Http\Controllers;

use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

use App\Http\Requests\BookCreateRequest as CreateRequest;
use App\Http\Requests\BookEditRequest as EditRequest;
use App\Http\Requests\BookDeleteRequest as DeleteRequest;

use App\Book;
use App\User;

use Facades\ {
    App\Libs\NDL
};

class BookActionController extends Controller
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
     * 本の登録に必要な書籍番号とユーザー情報を含んだヘッダを生成する。
     *
     * @return array
     */
    public function getHeader()
    {
        return [
            'id' => \Auth::user()['next_id'],
            'user_id' => \Auth::id(),
        ];
    }

    /**
     * 登録済みの本の一覧を返す。
     *
     * @param Request $request
     * @return array
     */
    public function index(Request $request)
    {
        $books = \DB::table('books')
            ->where('user_id', \Auth::id());

        if (isset($request->offset) && isset($request->limit)) {
            $books = $books->offset($request->offset)
                ->limit($request->limit);
        }

        if (isset($request->sort) && isset($request->order)) {
            $books = $books->orderBy($request->sort, $request->order);
        }

        return [
            'data' => $books->get(),
            'total' => Book::count(),
        ];
    }

    /**
     * 本を登録する。
     * DB上にすでに登録されていた場合は409、
     * 国会図書館に存在しない場合は404を返す。
     *
     * @param CreateRequest $request
     * @param return Book|Response
     */
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

                return [ 'response' => $book, 'statusCode' => 200 ];
            } catch (QueryException $e) {
                return [ 'response' => $book, 'statusCode' => 409 ];
            }
        } else {
            return [ 'response' => NULL, 'statusCode' => 404 ];
        }
    }

    /**
     * 登録済みの本を編集する。
     *
     * @param EditRequest $request
     * @return Book
     */
    public function edit(EditRequest $request)
    {
        $book = Book::find($request->id);
        $book->fill($request->all())->save();

        return $book;
    }

    /**
     * 登録されている本を削除する。
     * 削除に成功した場合は204、他のセッションで削除済みの場合は404を返す。
     *
     * @param DeleteRequest $request
     * @return Response
     */
    public function delete(DeleteRequest $request)
    {
        \DB::beginTransaction();
        try {
            if(!Book::destroy($request->ids)) {
                return response(\DB::rollback(), 400);
            }

            return response(\DB::commit(), 204);
        } catch (\Exception $e) {
            return response(\DB::rollback(), 500);
        }
    }
}
