<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class AdminUserController extends Controller
{
    public function index(Request $request)
    {
        $query = User::query();

        // Filter berdasarkan role
        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }

        // Filter berdasarkan status
        if ($request->filled('status')) {
            $isActive = $request->status === 'active';
            $query->where('is_active', $isActive);
        }

        // Filter berdasarkan pencarian
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        // Ambil data user
        $users = $query->latest()->get();

        // Jika request AJAX, return partial view
        if ($request->ajax()) {
            return response()->json([
                'html' => view('admin.users._table', compact('users'))->render()
            ]);
        }

        return view('admin.users.index', compact('users'));
    }

    public function edit(User $user)
    {
        return view('admin.users.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'role' => 'required|in:admin,user',
        ]);

        try {
            // Cek jika ini admin terakhir
            if ($user->role === 'admin' && $request->role === 'user') {
                $adminCount = User::where('role', 'admin')->count();
                if ($adminCount <= 1) {
                    return redirect()->back()->with('error', 'Tidak dapat mengubah role admin terakhir');
                }
            }

            // Update user
            $user->update([
                'name' => $request->name,
                'email' => $request->email,
                'role' => $request->role,
                'is_active' => $request->boolean('is_active')
            ]);

            return redirect()->route('admin.users.index')->with('success', 'Pengguna berhasil diperbarui');
        } catch (\Exception $e) {
            Log::error('Error updating user: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Terjadi kesalahan saat memperbarui pengguna');
        }
    }

    public function destroy(User $user)
    {
        try {
            // Cek jika user yang akan dihapus bukan admin terakhir
            if ($user->role === 'admin') {
                $adminCount = User::where('role', 'admin')->count();
                if ($adminCount <= 1) {
                    return back()->with('error', 'Tidak dapat menghapus admin terakhir');
                }
            }

            // Cek jika user mencoba menghapus dirinya sendiri
            if (Auth::id() === $user->id) {
                return back()->with('error', 'Anda tidak dapat menghapus akun Anda sendiri');
            }

            // Cek apakah user memiliki pesanan
            if ($user->orders()->exists()) {
                return back()->with('error', 'Tidak dapat menghapus pengguna yang memiliki pesanan');
            }

            // Cek apakah user memiliki item di keranjang
            if ($user->cartItems()->exists()) {
                // Hapus semua item di keranjang
                $user->cartItems()->delete();
            }

            $user->delete();
            return back()->with('success', 'Pengguna berhasil dihapus');

        } catch (\Exception $e) {
            Log::error('Error deleting user: ' . $e->getMessage());
            return back()->with('error', 'Terjadi kesalahan saat menghapus pengguna');
        }
    }
}
