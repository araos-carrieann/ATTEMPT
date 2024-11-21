<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;


abstract class Controller
{
    public function index()
    {
        // Ensure the user is authenticated
        if (Auth::check()) {
            $role = Auth::user()->role; // Retrieve the user's role
        } else {
            $role = null; // Handle the case when not authenticated
        }

        return view('livewire.welcome.navigation')->with('role', $role);
    }
   
}
