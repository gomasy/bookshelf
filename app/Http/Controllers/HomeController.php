<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if (\Auth::check()) {
            if (\Auth::user()->hasVerifiedEmail()) {
                return view('dashboard');
            } else {
                return redirect('email/verify');
            }
        } else {
            return view('home');
        }
    }
}
