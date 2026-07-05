<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index()
    {
        $users = User::latest()->paginate(20);

        return view('admin.users', compact('users'));
    }

    public function updateRole(Request $request, User $user)
    {
        $validated = $request->validate([
            'role' => ['required', 'in:student,supervisor,admin'],
        ]);

        $user->update(['role' => $validated['role']]);

        return redirect()->route('admin.users.index')->with('success', 'User role updated.');
    }
}
