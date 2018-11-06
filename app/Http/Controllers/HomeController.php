<?php
declare(strict_types=1);

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request): object
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

    public function privacy_policy() {
        return view('privacy-policy');
    }
}
