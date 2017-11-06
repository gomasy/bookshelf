<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests\BookCreateRequest as CreateRequest;

class BookController extends BookActionController
{
    /**
     * 登録済みの本の一覧を返す。
     * XHRからのリクエストは素通しし、それ以外は/にリダイレクトする。
     *
     * @param Request $request
     * @return array|RedirectResponse
     */
    public function index(Request $request)
    {
        return $request->ajax() ? parent::index($request) : redirect('/');
    }

    /**
     * 本を登録する。
     * XHRからのリクエストはスーパークラスのレスポンスをそのまま返し、
     * それ以外は/にリダイレクトする。
     * (ISBN読み取り機能による仕様の差異の吸収）
     *
     * @param CreateRequest $request
     * @return Book|Response|RedirectResponse
     */
    public function create(CreateRequest $request)
    {
        $result = parent::create($request);

        return $request->ajax() ? $result : redirect('/')->with('result', $result);
    }
}
