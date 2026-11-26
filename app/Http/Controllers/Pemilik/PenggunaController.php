<?php

namespace App\Http\Controllers\Pemilik;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class PenggunaController extends Controller
{
    /**
     * Aturan validasi bersama untuk pembaruan data pengguna.
     *
     * @return array<string, array<int, mixed>>
     */
    private function validationRules(User $pengguna): array
    {
        return [
            'name' => ['required', 'string', 'max:50'],
            'username' => ['required', 'string', 'max:100', Rule::unique('users', 'username')->ignore($pengguna->id)],
            'email' => ['required', 'email', 'max:100', Rule::unique('users', 'email')->ignore($pengguna->id)],
            'phone' => ['nullable', 'string', 'max:20'],
            'role' => ['required', Rule::in(array_keys(User::ROLES))],
        ];
    }

    /**
     * Batasi pengguna yang dikelola Pemilik ke akun sendiri dan anggota pertaniannya.
     */
    private function canManage(User $pemilik, User $pengguna): bool
    {
        return $pengguna->id === $pemilik->id
            || $pengguna->keanggotaan()
                ->whereHas('pertanian', fn ($query) => $query->where('pemilik_id', $pemilik->id))
                ->exists();
    }

    public function index(Request $request): View
    {
        $pemilik = $request->user();
        abort_unless($pemilik->isPemilik(), 403);

        $penggunas = User::query()
            ->where(function ($query) use ($pemilik) {
                $query->where('id', $pemilik->id)
                    ->orWhereHas('keanggotaan.pertanian', fn ($q) => $q->where('pemilik_id', $pemilik->id));
            })
            ->orderBy('name')
            ->paginate(10);

        return view('pemilik.pengguna.index', ['penggunas' => $penggunas]);
    }

    public function show(Request $request, User $pengguna): View
    {
        $pemilik = $request->user();
        abort_unless($pemilik->isPemilik(), 403);
        abort_unless($this->canManage($pemilik, $pengguna), 404);

        return view('pemilik.pengguna.show', ['user' => $pengguna]);
    }

    public function edit(Request $request, User $pengguna): View
    {
        $pemilik = $request->user();
        abort_unless($pemilik->isPemilik(), 403);
        abort_unless($this->canManage($pemilik, $pengguna), 404);

        return view('pemilik.pengguna.edit', [
            'user' => $pengguna,
            'roles' => User::ROLES,
        ]);
    }

    public function update(Request $request, User $pengguna): RedirectResponse
    {
        $pemilik = $request->user();
        abort_unless($pemilik->isPemilik(), 403);
        abort_unless($this->canManage($pemilik, $pengguna), 404);

        $pengguna->update($request->validate($this->validationRules($pengguna)));

        return redirect()->route('pemilik.pengguna.index')
            ->with('success', 'Data pengguna berhasil diperbarui.');
    }

    public function destroy(Request $request, User $pengguna): RedirectResponse
    {
        $pemilik = $request->user();
        abort_unless($pemilik->isPemilik(), 403);
        abort_unless($this->canManage($pemilik, $pengguna), 404);

        $pengguna->delete();

        return redirect()->route('pemilik.pengguna.index')
            ->with('success', 'Data pengguna berhasil dihapus.');
    }
}
