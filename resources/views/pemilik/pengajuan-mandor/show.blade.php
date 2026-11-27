<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Periksa Pengajuan Mandor') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 flex flex-col gap-6">
                    <dl class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                        <div>
                            <dt class="text-sm font-medium text-gray-500">{{ __('Nama Calon Mandor') }}</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $pengajuan->user->name }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">{{ __('Email') }}</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $pengajuan->user->email }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">{{ __('Pertanian') }}</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $pengajuan->pertanian->nama_pertanian }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">{{ __('Tanggal Pengajuan') }}</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $pengajuan->tanggal_pengajuan?->format('d/m/Y') }}</dd>
                        </div>
                    </dl>

                    <form method="POST" action="{{ route('pemilik.pengajuan-mandor.update', $pengajuan) }}" class="flex flex-wrap items-center gap-4">
                        @csrf
                        @method('PUT')

                        <x-input-label for="status" :value="__('Keputusan')" />
                        <select id="status" name="status" class="rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                            <option value="disetujui" @selected(old('status') === 'disetujui')>{{ __('Setujui') }}</option>
                            <option value="ditolak" @selected(old('status') === 'ditolak')>{{ __('Tolak') }}</option>
                        </select>
                        <x-input-error :messages="$errors->get('status')" class="mt-2" />

                        <x-primary-button>{{ __('Simpan Keputusan') }}</x-primary-button>
                        <a href="{{ route('pemilik.pengajuan-mandor.index') }}" class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 disabled:opacity-25 transition ease-in-out duration-150">
                            {{ __('Kembali') }}
                        </a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
