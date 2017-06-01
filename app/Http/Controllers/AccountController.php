<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;

use App\Http\Requests\AccountUpdateRequest;
use App\User;

class AccountController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        return view('account');
    }

    public function update(AccountUpdateRequest $request)
    {
        $configs = $request->has('password') ?
            $request->all() : $request->except('password');

        $user = User::find(Auth::id());
        $user->fill($configs);
        $user->save();

        return redirect('/');
    }
}
