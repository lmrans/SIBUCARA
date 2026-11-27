<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Detail Laporan Pekerjaan') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 flex flex-col gap-6">
                    <dl class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                        <div>
                            <dt class="text-sm font-medium text-gray-500">{{ __('Judul Tugas') }}</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $laporan->tugas->judul_tugas }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">{{ __('Pekerja') }}</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $laporan->pekerja->name }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">{{ __('Pertanian') }}</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $laporan->tugas->budidaya->tanaman->pertanian->nama_pertanian }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">{{ __('Tanggal Laporan') }}</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $laporan->tanggal_laporan?->format('d/m/Y') }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">{{ __('Status Validasi') }}</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ App\Models\LaporanPekerjaan::STATUS_VALIDASI[$laporan->status_validasi] ?? $laporan->status_validasi }}</dd>
                        </div>
                        <div class="sm:col-span-2">
                            <dt class="text-sm font-medium text-gray-500">{{ __('Deskripsi') }}</dt>
                            <dd class="mt-1 text-sm text-gray-900 whitespace-pre-line">{{ $laporan->deskripsi }}</dd>
                        </div>
                    </dl>

                    <div>
                        <h3 class="text-sm font-semibold text-gray-900">{{ __('Bukti Pekerjaan') }}</h3>
                        <ul class="mt-2 flex flex-col gap-2">
                            @forelse ($laporan->buktiPekerjaans as $bukti)
                                <li>
                                    <a href="{{ Illuminate\Support\Facades\Storage::url($bukti->path_file) }}" target="_blank" rel="noopener noreferrer" class="text-indigo-600 hover:text-indigo-900">{{ $bukti->nama_file }}</a>
                                </li>
                            @empty
                                <li class="text-sm text-gray-500">{{ __('Tidak ada bukti pekerjaan.') }}</li>
                            @endforelse
                        </ul>
                    </div>

                    <div>
                        <a href="{{ route('pemilik.laporan.index') }}" class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                            {{ __('Kembali') }}
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
