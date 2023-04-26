<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Movie;
use Auth;
use App\Models\User;

class HomeController extends Controller
{
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        if (Auth::check()) {
            $currentUser = User::where('id', Auth::id())->first(['id', 'name', 'lastName']);
        } else {
            $currentUser = json_encode('');
        }

        return view('home', compact('currentUser'));
    }
}
