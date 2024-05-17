<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Retrieve all users from the database.
     *
     * @return \Illuminate\View\View
     */
    public function allUsers()
    {
        // Retrieve all users
        $users = User::all();

        // Return view with users data
        return view('admin.users', compact('users'));
    }
}
