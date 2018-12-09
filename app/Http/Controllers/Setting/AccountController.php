<?php
declare(strict_types=1);

namespace App\Http\Controllers\Setting;

use Illuminate\Http\Request;

use App\Http\Controllers\Controller;
use App\Http\Requests\AccountDeleteRequest as DeleteRequest;
use App\Http\Requests\AccountUpdateRequest as UpdateRequest;
use App\User;

class AccountController extends Controller
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
    public function index(Request $request): object
    {
        return view('settings.account.update');
    }

    /**
     * アカウント情報の更新を行う。
     * UpdateRequestクラスによりバリデーションは行われているので、
     * 無条件で/へリダイレクトする。
     *
     * @param UpdateRequest $request
     * @return RedirectResponse
     */
    public function update(UpdateRequest $request): object
    {
        $inputs = $request->except([ 'password', 'password_confirmation' ]);
        if ($request->filled('password')) {
            $inputs['password'] = \Hash::make($request->password);
        }

        $user = User::find(\Auth::id());
        $old_email = $user->email;
        $user->fill($inputs);
        if ($old_email != $inputs['email']) {
            $user->email_verified_at = null;
        }
        $user->save();

        return redirect('/');
    }

    /**
     * アカウント削除の確認画面のビューを返す。
     *
     * @param Request $request
     * @return View
     */
    public function delete(Request $request): object
    {
        return view('settings.account.delete');
    }

    /**
     * 現在ログイン中のアカウントの削除を行う。
     * 確認画面で入力されたパスワードの検証を行い、
     * ハッシュが一致した場合は処理を実行してログアウトする。
     * 不一致の場合はエラーメッセージを設定した確認画面を返す。
     *
     * @param DeleteRequest $request
     * @return RedirectResponse|View
     */
    public function confirmDelete(DeleteRequest $request): object
    {
        if (\Hash::check($request->password, \Auth::user()['password'])) {
            User::find(\Auth::id())->delete();
            \Auth::logout();

            return redirect('/');
        } else {
            return view('settings.account.delete')
                ->withErrors([ 'password' => 'パスワードが一致しません。' ]);
        }
    }
}
