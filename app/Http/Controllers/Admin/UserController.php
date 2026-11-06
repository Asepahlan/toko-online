<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $query = User::query();

        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('email', 'like', '%' . $request->search . '%');
        }

        $users = $query->get();

        return view('admin.users.index', compact('users'));
    }

    public function edit(User $user)
    {
        return view('admin.users.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'role' => 'required|in:admin,user',
            'is_active' => 'boolean'
        ]);

        $user->update($request->all());
        return redirect()->route('admin.users.index')->with('success', 'Pengguna berhasil diperbarui');
    }

    public function destroy(User $user)
    {
        $user->delete();
        return back()->with('success', 'Pengguna berhasil dihapus');
    }
}
