<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash; // Pridajte tento import

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

    public function edit($id)
    {
        $user = User::findOrFail($id);
        return view('admin.edit-user', compact('user'));
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'usertype' => 'required|string',
            'password' => 'nullable|string|min:8|confirmed',
        ]);

        $user->name = $request->name;
        $user->email = $request->email;
        $user->usertype = $request->usertype;

        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        $user->save();

        return redirect()->route('admin.users')->with('success', 'User updated successfully.');
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->questions()->delete(); // Assuming you have a relationship defined in the User model

        $user->delete();

        return redirect()->route('admin.users')->with('success', 'User deleted successfully.');
    }

}
