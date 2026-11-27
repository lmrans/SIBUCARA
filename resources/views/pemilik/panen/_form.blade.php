<div class="flex flex-col gap-6">
    <div>
        <x-input-label for="tanaman_id" :value="__('Tanaman')" />
        <select id="tanaman_id" name="tanaman_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
            @foreach ($tanamen as $tanaman)
                <option value="{{ $tanaman->id }}" @selected(old('tanaman_id', $panen?->tanaman_id) == $tanaman->id)>{{ $tanaman->nama_tanaman }}</option>
            @endforeach
        </select>
        <x-input-error :messages="$errors->get('tanaman_id')" class="mt-2" />
    </div>

    <div>
        <x-input-label for="tanggal_panen" :value="__('Tanggal Panen')" />
        <x-text-input id="tanggal_panen" name="tanggal_panen" type="date" class="mt-1 block w-full" :value="old('tanggal_panen', $panen?->tanggal_panen?->format('Y-m-d'))" required autofocus />
        <x-input-error :messages="$errors->get('tanggal_panen')" class="mt-2" />
    </div>

    <div>
        <x-input-label for="jumlah" :value="__('Jumlah')" />
        <x-text-input id="jumlah" name="jumlah" type="number" step="0.01" min="0.01" class="mt-1 block w-full" :value="old('jumlah', $panen?->jumlah)" required />
        <x-input-error :messages="$errors->get('jumlah')" class="mt-2" />
    </div>

    <div>
        <x-input-label for="satuan" :value="__('Satuan')" />
        <x-text-input id="satuan" name="satuan" type="text" class="mt-1 block w-full" :value="old('satuan', $panen?->satuan)" required />
        <x-input-error :messages="$errors->get('satuan')" class="mt-2" />
    </div>

    <div>
        <x-input-label for="kualitas" :value="__('Kualitas')" />
        <x-text-input id="kualitas" name="kualitas" type="text" class="mt-1 block w-full" :value="old('kualitas', $panen?->kualitas)" required />
        <x-input-error :messages="$errors->get('kualitas')" class="mt-2" />
    </div>
</div>
