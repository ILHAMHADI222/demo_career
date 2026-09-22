<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class AdminUserController extends Controller
{
    public function index(Request $request): View
    {
        $users = User::query()
            ->with('roles')
            ->when($request->string('keyword')->toString(), fn ($query, $keyword) => $query->where(fn ($q) => $q->where('name', 'like', "%{$keyword}%")->orWhere('email', 'like', "%{$keyword}%")))
            ->when($request->string('role')->toString(), fn ($query, $role) => $query->whereHas('roles', fn ($q) => $q->where('name', $role)))
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('admin.users.index', [
            'users' => $users,
            'roles' => Role::query()->orderBy('label')->get(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $isSuperAdmin = auth()->user()->hasRole('SUPERADMIN');

        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8'],
            'roles' => ['nullable', 'array'],
            'roles.*' => ['exists:roles,id'],
            'allowed_stages' => ['nullable', 'array'],
            'allowed_stages.*' => ['integer', 'in:1,2,3'],
        ]);

        $allowedStages = !empty($data['allowed_stages'])
            ? array_values(array_unique(array_map('intval', $data['allowed_stages'])))
            : [1, 2, 3];

        $user = User::query()->create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'email_verified_at' => now(),
            'allowed_stages' => $allowedStages,
        ]);

        if ($isSuperAdmin && !empty($data['roles'])) {
            $user->roles()->sync($data['roles']);
        } else {
            // Role HRD hanya membuat akun user internal (Candidate)
            $userRole = Role::where('name', 'USER')->first();
            if ($userRole) {
                $user->roles()->sync([$userRole->id]);
            }
        }

        return back()->with('success', 'Akun user internal berhasil dibuat dengan hak akses tahap psikotes.');
    }

    public function update(Request $request, User $user): RedirectResponse
    {
        $isSuperAdmin = auth()->user()->hasRole('SUPERADMIN');

        // Role HRD tidak dapat mengubah akun administrator lain atau superadmin
        if (!$isSuperAdmin && ($user->hasRole('SUPERADMIN') || ($user->hasRole('ADMIN') && !$user->is(auth()->user())))) {
            abort(403, 'Role HRD tidak diizinkan mengubah akun administrator lain atau Superadmin.');
        }

        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($user->id)],
            'password' => ['nullable', 'string', 'min:8'],
            'roles' => ['nullable', 'array'],
            'roles.*' => ['exists:roles,id'],
            'allowed_stages' => ['nullable', 'array'],
            'allowed_stages.*' => ['integer', 'in:1,2,3'],
        ]);

        $user->fill([
            'name' => $data['name'],
            'email' => $data['email'],
        ]);

        if (! empty($data['password'])) {
            $user->password = Hash::make($data['password']);
        }

        if (isset($data['allowed_stages'])) {
            $user->allowed_stages = array_values(array_unique(array_map('intval', $data['allowed_stages'])));
        }

        $user->save();

        if ($isSuperAdmin && isset($data['roles'])) {
            $user->roles()->sync($data['roles']);
        }

        return back()->with('success', 'Data user & hak akses tahap psikotes berhasil diperbarui.');
    }

    public function destroy(User $user): RedirectResponse
    {
        abort_if($user->is(auth()->user()), 422, 'Anda tidak dapat menghapus akun sendiri.');

        $isSuperAdmin = auth()->user()->hasRole('SUPERADMIN');
        if (!$isSuperAdmin && ($user->hasRole('SUPERADMIN') || $user->hasRole('ADMIN'))) {
            abort(403, 'Role HRD hanya dapat menghapus akun user internal.');
        }

        $user->delete();

        return back()->with('success', "Akun '{$user->name}' berhasil dihapus.");
    }
}
