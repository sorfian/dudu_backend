<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index() {
        $newUser = User::where('is_active', 0)->get();

        return view('dashboard', [
            'newuser' => $newUser,
        ]);
    }
}
