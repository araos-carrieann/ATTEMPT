<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function show()
    {
        Auth::logout();
        return view('user_ui.status');
    }

    
}
