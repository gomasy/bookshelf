<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;

use App\Http\Requests\AccountDeleteRequest as DeleteRequest;
use App\Http\Requests\AccountUpdateRequest as UpdateRequest;
use App\User;

class AccountController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        return view('account/update');
    }

    public function update(UpdateRequest $request)
    {
        $configs = $request->has('password') ?
            $request->all() : $request->except('password');

        $user = User::find(\Auth::id());
        $user->fill($configs);
        $user->save();

        return redirect('/');
    }

    public function delete(Request $request)
    {
        return view('account/delete');
    }

    public function confirm_delete(DeleteRequest $request)
    {
        if (\Hash::check($request->password, \Auth::user()['password'])) {
            User::find(\Auth::id())->delete();
            \Auth::logout();

            return redirect('/');
        } else {
            return view('account/delete')
                ->withErrors([ 'password' => __('account.incorrect') ]);
        }

    }
}
