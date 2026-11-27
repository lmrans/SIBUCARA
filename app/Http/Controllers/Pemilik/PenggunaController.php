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
     * Peran yang dapat diberikan Pemilik kepada anggota pertaniannya.
     *
     * @return array<int, string>
     */
    private function peranYangDapatDiberikan(): array
    {
        return ['mandor', 'pekerja'];
    }

    private function bukanAkunSendiri(User $pemilik, User $pengguna): bool
    {
        return $pengguna->id !== $pemilik->id;
    }

    /**
     * Aturan validasi bersama untuk pembaruan data pengguna.
     *
     * @return array<string, array<int, mixed>>
     */
    private function validationRules(User $pengguna, bool $bolehUbahRole): array
    {
        $aturan = [
            'name' => ['required', 'string', 'max:50'],
            'username' => ['required', 'string', 'max:100', Rule::unique('users', 'username')->ignore($pengguna->id)],
            'email' => ['required', 'email', 'max:100', Rule::unique('users', 'email')->ignore($pengguna->id)],
            'phone' => ['nullable', 'string', 'max:20'],
        ];

        if ($bolehUbahRole) {
            $aturan['role'] = ['required', Rule::in($this->peranYangDapatDiberikan())];
        }

        return $aturan;
    }

    /**
     * Batasi pengguna yang dikelola Pemilik ke akun sendiri dan anggota pertaniannya.
     */
    private function canManage(User $pemilik, User $pengguna): bool
    {
        return $pengguna->id === $pemilik->id
            || $pengguna->keanggotaan()
                ->where('status', 'disetujui')
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
                    ->orWhereHas('keanggotaan', function ($q) use ($pemilik) {
                        $q->where('status', 'disetujui')
                            ->whereHas('pertanian', fn ($p) => $p->where('pemilik_id', $pemilik->id));
                    });
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

        $bolehUbahRole = $this->bukanAkunSendiri($pemilik, $pengguna);

        return view('pemilik.pengguna.edit', [
            'user' => $pengguna,
            'bolehUbahRole' => $bolehUbahRole,
            'roles' => $bolehUbahRole
                ? array_intersect_key(User::ROLES, array_flip($this->peranYangDapatDiberikan()))
                : [],
        ]);
    }

    public function update(Request $request, User $pengguna): RedirectResponse
    {
        $pemilik = $request->user();
        abort_unless($pemilik->isPemilik(), 403);
        abort_unless($this->canManage($pemilik, $pengguna), 404);

        $bolehUbahRole = $this->bukanAkunSendiri($pemilik, $pengguna);
        $pengguna->update($request->validate($this->validationRules($pengguna, $bolehUbahRole)));

        return redirect()->route('pemilik.pengguna.index')
            ->with('success', 'Data pengguna berhasil diperbarui.');
    }

    public function destroy(Request $request, User $pengguna): RedirectResponse
    {
        $pemilik = $request->user();
        abort_unless($pemilik->isPemilik(), 403);
        abort_unless($this->canManage($pemilik, $pengguna), 404);
        abort_unless($this->bukanAkunSendiri($pemilik, $pengguna), 403);

        $pengguna->delete();

        return redirect()->route('pemilik.pengguna.index')
            ->with('success', 'Data pengguna berhasil dihapus.');
    }
}
