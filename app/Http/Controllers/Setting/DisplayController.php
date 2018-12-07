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
     * @return View
     */
    public function index(): object
    {
        $setting = UserSetting::find(\Auth::id());

        return view('settings.display.index', compact('setting'));
    }

    /**
     * 表示設定を更新する
     *
     * @param Request $request
     * @return RedirectResponse
     */
    public function update(Request $request): object
    {
        $setting = UserSetting::find(\Auth::id());
        $setting->fill($request->all());

        if ($setting->animation !== null) {
            $setting->animation = $setting->animation !== 'on' ? 0 : 1;
        }
        $setting->save();

        return redirect('/');
    }
}
