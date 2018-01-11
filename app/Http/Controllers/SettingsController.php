<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;

class SettingsController extends Controller
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
     * アカウント更新画面のビューを返す。
     *
     * @param Request $request
     * @return View
     */
    public function index(Request $request)
    {
        return redirect('/settings/account');
    }
}
