<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class PgsoController extends Controller
{
    public function dashboard()
    {
        $users = User::whereIn('role', ['administrator', 'pgso'])
            ->where('status', 'active')
            ->orderBy('name')
            ->get();

        return view('pgso.dashboard', [
            'users' => $users
        ]);
    }
}
