<div class="flex flex-col gap-6">
    <div>
        <x-input-label for="panen_id" :value="__('Hasil Panen')" />
        <select id="panen_id" name="panen_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
            @foreach ($panens as $panen)
                <option value="{{ $panen->id }}" @selected(old('panen_id', $penjualan?->panen_id) == $panen->id)>
                    {{ $panen->tanaman->nama_tanaman }} — {{ $panen->tanggal_panen?->format('d/m/Y') }}
                </option>
            @endforeach
        </select>
        <x-input-error :messages="$errors->get('panen_id')" class="mt-2" />
    </div>

    <div>
        <x-input-label for="tanggal_penjualan" :value="__('Tanggal Penjualan')" />
        <x-text-input id="tanggal_penjualan" name="tanggal_penjualan" type="date" class="mt-1 block w-full" :value="old('tanggal_penjualan', $penjualan?->tanggal_penjualan?->format('Y-m-d'))" required autofocus />
        <x-input-error :messages="$errors->get('tanggal_penjualan')" class="mt-2" />
    </div>

    <div>
        <x-input-label for="pembeli" :value="__('Pembeli')" />
        <x-text-input id="pembeli" name="pembeli" type="text" class="mt-1 block w-full" :value="old('pembeli', $penjualan?->pembeli)" required />
        <x-input-error :messages="$errors->get('pembeli')" class="mt-2" />
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
        <div>
            <x-input-label for="jumlah" :value="__('Jumlah')" />
            <x-text-input id="jumlah" name="jumlah" type="number" step="0.01" min="0.01" class="mt-1 block w-full" :value="old('jumlah', $penjualan?->jumlah)" required />
            <x-input-error :messages="$errors->get('jumlah')" class="mt-2" />
        </div>

        <div>
            <x-input-label for="harga_satuan" :value="__('Harga Satuan')" />
            <x-text-input id="harga_satuan" name="harga_satuan" type="number" step="0.01" min="0.01" class="mt-1 block w-full" :value="old('harga_satuan', $penjualan?->harga_satuan)" required />
            <x-input-error :messages="$errors->get('harga_satuan')" class="mt-2" />
        </div>
    </div>

    <p class="text-sm text-gray-600">{{ __('Total harga dihitung otomatis dari jumlah dikali harga satuan.') }}</p>
</div>
