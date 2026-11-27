<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Notifikasi') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 flex flex-col gap-6">
            @if (session('success'))
                <div class="rounded-md bg-green-50 p-4 text-sm text-green-700">
                    {{ session('success') }}
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 flex flex-col gap-6">
                    <div class="flex flex-wrap items-center justify-between gap-4">
                        <p class="text-sm text-gray-600">
                            {{ __('Belum dibaca') }}: {{ $belumDibaca }}
                        </p>

                        @if ($belumDibaca > 0)
                            <form method="POST" action="{{ route('pemilik.notifikasi.tandai-semua-dibaca') }}">
                                @csrf
                                <x-secondary-button type="submit">{{ __('Tandai Semua Dibaca') }}</x-secondary-button>
                            </form>
                        @endif
                    </div>

                    <div class="flex flex-col gap-4">
                        @forelse ($notifikasis as $notifikasi)
                            <div class="rounded-md border border-gray-200 p-4 flex flex-wrap items-start justify-between gap-4">
                                <div class="flex flex-col gap-1">
                                    <div class="flex items-center gap-3">
                                        <h3 class="text-sm font-semibold text-gray-900">{{ $notifikasi->judul }}</h3>
                                        @if ($notifikasi->status_baca === 'belum_dibaca')
                                            <span class="inline-flex items-center rounded-full bg-indigo-100 px-2 py-0.5 text-xs font-medium text-indigo-800">{{ __('Baru') }}</span>
                                        @endif
                                    </div>
                                    <p class="text-sm text-gray-600">{{ $notifikasi->pesan }}</p>
                                    <p class="text-xs text-gray-400">{{ $notifikasi->created_at?->format('d/m/Y H:i') }} — {{ $notifikasi->jenis }}</p>
                                </div>

                                @if ($notifikasi->status_baca === 'belum_dibaca')
                                    <form method="POST" action="{{ route('pemilik.notifikasi.update', $notifikasi) }}">
                                        @csrf
                                        @method('PUT')
                                        <x-secondary-button type="submit">{{ __('Tandai Dibaca') }}</x-secondary-button>
                                    </form>
                                @endif
                            </div>
                        @empty
                            <p class="text-sm text-gray-500">{{ __('Belum ada notifikasi.') }}</p>
                        @endforelse
                    </div>

                    {{ $notifikasis->links() }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
