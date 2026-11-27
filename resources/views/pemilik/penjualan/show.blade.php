<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Detail Penjualan') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 flex flex-col gap-6">
                    <dl class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                        <div>
                            <dt class="text-sm font-medium text-gray-500">{{ __('Tanggal Penjualan') }}</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $penjualan->tanggal_penjualan?->format('d/m/Y') }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">{{ __('Pembeli') }}</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $penjualan->pembeli }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">{{ __('Produk') }}</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $penjualan->panen->tanaman->nama_tanaman }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">{{ __('Pertanian') }}</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $penjualan->panen->tanaman->pertanian->nama_pertanian }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">{{ __('Jumlah') }}</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $penjualan->jumlah }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">{{ __('Harga Satuan') }}</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ number_format((float) $penjualan->harga_satuan, 2, ',', '.') }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">{{ __('Total Harga') }}</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ number_format((float) $penjualan->total_harga, 2, ',', '.') }}</dd>
                        </div>
                    </dl>

                    <div class="flex items-center gap-4">
                        <a href="{{ route('pemilik.penjualan.edit', $penjualan) }}" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                            {{ __('Edit') }}
                        </a>
                        <a href="{{ route('pemilik.penjualan.index') }}" class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                            {{ __('Kembali') }}
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
