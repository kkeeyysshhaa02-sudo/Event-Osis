<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class UserController extends Controller
{
    public function index(Request $request): View
    {
        $search = $request->query('search');
        $role = $request->query('role');

        $users = User::when($search, function ($q) use ($search) {
            $q->where('name', 'like', "%{$search}%")
                ->orWhere('email', 'like', "%{$search}%")
                ->orWhere('kelas', 'like', "%{$search}%");
        })->when($role, function ($q) use ($role) {
            $q->where('role', $role);
        })->latest()->paginate(10)->withQueryString();

        return view('users.index', compact('users', 'search', 'role'));
    }

    public function updateRole(Request $request, User $user): RedirectResponse
    {
        $request->validate([
            'role' => ['required', Rule::in(['admin', 'panitia', 'peserta'])],
        ]);

        $user->update([
            'role' => $request->role,
        ]);

        return back()->with('success', "Role pengguna {$user->name} berhasil diubah menjadi {$user->role}.");
    }

    public function destroy(User $user): RedirectResponse
    {
        if ($user->id === auth()->id()) {
            return back()->with('error', 'Anda tidak dapat menghapus akun Anda sendiri.');
        }

        $user->delete();

        return back()->with('success', 'Pengguna berhasil dihapus.');
    }
}
