<?php

namespace App\Http\Controllers\Setting;

use Illuminate\Http\Request;

use App\Http\Controllers\Controller;
use App\UserSetting;

class DisplayController extends Controller
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
     * 表示設定画面のビューを返す。
     *
     * @param Request $request
     * @return View
     */
    public function index(Request $request): object
    {
        $setting = UserSetting::find(\Auth::id());

        if ($request->ajax()) {
            return response($setting);
        } else {
            return view('settings.display.index', compact('setting'));
        }
    }

    public function update(Request $request): object
    {
        $setting = UserSetting::find(\Auth::id());
        $setting->fill($request->all());
        $setting->save();

        return redirect('/');
    }
}
