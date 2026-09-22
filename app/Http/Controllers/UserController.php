<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;
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

    public function create(): View
    {
        return view('users.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'confirmed', Password::min(8)],
            'role' => ['required', Rule::in(['admin', 'panitia', 'peserta'])],
            'kelas' => ['nullable', 'string', 'max:50'],
            'phone' => ['nullable', 'string', 'max:20'],
        ]);

        User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => $validated['role'],
            'kelas' => $validated['kelas'] ?? null,
            'phone' => $validated['phone'] ?? null,
        ]);

        return redirect()->route('users.index')
            ->with('success', "Akun pengguna {$validated['name']} berhasil ditambahkan.");
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
