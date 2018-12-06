<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;

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
        } else if (isset($request->sid) && !Bookshelf::find($request->sid)) {
            abort(401);
        }
    }
}
