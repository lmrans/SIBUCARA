<div class="flex flex-col gap-6">
    <div>
        <x-input-label for="pertanian_id" :value="__('Pertanian')" />
        <select id="pertanian_id" name="pertanian_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
            @foreach ($pertanians as $pertanian)
                <option value="{{ $pertanian->id }}" @selected(old('pertanian_id', $transaksi?->pertanian_id) == $pertanian->id)>{{ $pertanian->nama_pertanian }}</option>
            @endforeach
        </select>
        <x-input-error :messages="$errors->get('pertanian_id')" class="mt-2" />
    </div>

    <div>
        <x-input-label for="jenis" :value="__('Jenis Transaksi')" />
        <select id="jenis" name="jenis" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
            @foreach ($jenis as $value => $label)
                <option value="{{ $value }}" @selected(old('jenis', $transaksi?->jenis) === $value)>{{ $label }}</option>
            @endforeach
        </select>
        <x-input-error :messages="$errors->get('jenis')" class="mt-2" />
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
        <div>
            <x-input-label for="kategori" :value="__('Kategori')" />
            <x-text-input id="kategori" name="kategori" type="text" class="mt-1 block w-full" :value="old('kategori', $transaksi?->kategori)" required />
            <x-input-error :messages="$errors->get('kategori')" class="mt-2" />
        </div>

        <div>
            <x-input-label for="jumlah" :value="__('Jumlah')" />
            <x-text-input id="jumlah" name="jumlah" type="number" step="0.01" min="0.01" class="mt-1 block w-full" :value="old('jumlah', $transaksi?->jumlah)" required />
            <x-input-error :messages="$errors->get('jumlah')" class="mt-2" />
        </div>
    </div>

    <div>
        <x-input-label for="tanggal_transaksi" :value="__('Tanggal Transaksi')" />
        <x-text-input id="tanggal_transaksi" name="tanggal_transaksi" type="date" class="mt-1 block w-full" :value="old('tanggal_transaksi', $transaksi?->tanggal_transaksi?->format('Y-m-d'))" required />
        <x-input-error :messages="$errors->get('tanggal_transaksi')" class="mt-2" />
    </div>

    <div>
        <x-input-label for="keterangan" :value="__('Keterangan')" />
        <textarea id="keterangan" name="keterangan" rows="4" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required>{{ old('keterangan', $transaksi?->keterangan) }}</textarea>
        <x-input-error :messages="$errors->get('keterangan')" class="mt-2" />
    </div>
</div>
