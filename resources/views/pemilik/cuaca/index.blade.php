<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Informasi Cuaca') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 flex flex-col gap-6">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <form method="GET" action="{{ route('pemilik.cuaca.index') }}" class="p-6 text-gray-900 flex flex-col gap-4">
                    <x-input-label for="pertanian_id" :value="__('Lokasi Pertanian')" />
                    <div class="flex flex-wrap items-center gap-4">
                        <select id="pertanian_id" name="pertanian_id" class="mt-1 block w-full max-w-md rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            @foreach ($pertanians as $pertanian)
                                <option value="{{ $pertanian->id }}" @selected($pertanianDipilih?->id === $pertanian->id)>{{ $pertanian->nama_pertanian }}</option>
                            @endforeach
                        </select>
                        <x-secondary-button type="submit">{{ __('Lihat Cuaca') }}</x-secondary-button>
                    </div>
                </form>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 flex flex-col gap-6">
                    @if ($cuaca['status'] === 'error')
                        <div class="rounded-md bg-yellow-50 p-4 text-sm text-yellow-800">
                            {{ $cuaca['pesan'] }}
                        </div>
                    @else
                        <div>
                            <h3 class="text-lg font-medium">{{ __('Prakiraan untuk').' '.$cuaca['lokasi'] }}</h3>
                            <p class="mt-1 text-sm text-gray-500">{{ __('Suhu (°C), kelembapan (%), dan curah hujan (mm per 3 jam).') }}</p>
                        </div>

                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Waktu') }}</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Suhu') }}</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Kelembapan') }}</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Curah Hujan') }}</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @forelse ($cuaca['prakiraan'] as $item)
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $item['waktu'] }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $item['suhu'] ?? '-' }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $item['kelembapan'] ?? '-' }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $item['curah_hujan'] ?? '-' }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4" class="px-6 py-4 text-center text-sm text-gray-500">
                                                {{ __('Data cuaca belum dapat ditampilkan.') }}
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
