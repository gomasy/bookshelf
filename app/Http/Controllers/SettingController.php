<?php
declare(strict_types=1);

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Bookshelf;
use App\UserSetting;
use App\Status;

class SettingController extends Controller
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
     * 全ての設定項目とその値をJSONで返す
     *
     * @param Request $request
     * @return array
     */
    public function all(Request $request): array
    {
        $this->checkAuthorize($request);

        $statuses = Status::get()->toArray();
        $shelves = Bookshelf::get()->toArray();
        $user_setting = UserSetting::find(\Auth::id())->toArray();

        return compact('statuses', 'shelves', 'user_setting');
    }
}
