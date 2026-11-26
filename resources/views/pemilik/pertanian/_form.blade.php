<div class="flex flex-col gap-6">
    <div>
        <x-input-label for="nama_pertanian" :value="__('Nama Pertanian')" />
        <x-text-input id="nama_pertanian" name="nama_pertanian" type="text" class="mt-1 block w-full" :value="old('nama_pertanian', $pertanian?->nama_pertanian)" required autofocus />
        <x-input-error :messages="$errors->get('nama_pertanian')" class="mt-2" />
    </div>

    <div>
        <x-input-label for="lokasi" :value="__('Lokasi')" />
        <x-text-input id="lokasi" name="lokasi" type="text" class="mt-1 block w-full" :value="old('lokasi', $pertanian?->lokasi)" required />
        <x-input-error :messages="$errors->get('lokasi')" class="mt-2" />
    </div>

    <div>
        <x-input-label for="luas_lahan" :value="__('Luas Lahan')" />
        <x-text-input id="luas_lahan" name="luas_lahan" type="number" step="0.01" min="0" class="mt-1 block w-full" :value="old('luas_lahan', $pertanian?->luas_lahan)" required />
        <x-input-error :messages="$errors->get('luas_lahan')" class="mt-2" />
    </div>

    <div>
        <x-input-label for="deskripsi" :value="__('Deskripsi')" />
        <textarea id="deskripsi" name="deskripsi" rows="4" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">{{ old('deskripsi', $pertanian?->deskripsi) }}</textarea>
        <x-input-error :messages="$errors->get('deskripsi')" class="mt-2" />
    </div>
</div>
