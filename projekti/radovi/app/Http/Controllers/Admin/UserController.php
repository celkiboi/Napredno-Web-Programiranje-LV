<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    // List all users
    public function index()
    {
        $users = User::with('roles')->latest()->paginate(10);
        return view('admin.users.index', compact('users'));
    }

    // Show form to create a new user
    public function create()
    {
        $roles = Role::all();
        return view('admin.users.create', compact('roles'));
    }

    // Store new user
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|exists:roles,name',
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
        ]);

        $user->assignRole($validated['role']);

        return redirect()->route('admin.users.index')
            ->with('success', __('User created successfully.'));
    }

    // Show form to edit user
    public function edit(User $user)
    {
        $roles = Role::all();
        return view('admin.users.edit', compact('user', 'roles'));
    }

    // Update user
    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'email', Rule::unique('users')->ignore($user->id)],
            'role' => 'required|exists:roles,name',
        ]);

        $user->update([
            'name' => $validated['name'],
            'email' => $validated['email'],
        ]);

        // Sync roles (replaces old roles with new one)
        $user->syncRoles([$validated['role']]);

        return redirect()->route('admin.users.index')
            ->with('success', __('User updated successfully.'));
    }

    // Delete user
    public function destroy(User $user)
    {
        // Prevent Admin from deleting themselves
        if ($user->id === auth()->id()) {
            return back()->with('error', __('You cannot delete yourself.'));
        }

        $user->delete();

        return back()->with('success', __('User deleted successfully.'));
    }
}