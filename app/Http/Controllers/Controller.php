<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    /**
     * リクエストがAjaxによるものなのか検証する。
     * 違う場合は404を返す。
     *
     * @return void
     */
    protected function checkAjax(Request $request): void
    {
        if (!$request->ajax()) {
            abort(404);
        }
    }
}
