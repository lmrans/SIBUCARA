<div>
    <x-input-label for="nama_kegiatan" :value="__('Nama Kegiatan')" />
    <x-text-input id="nama_kegiatan" name="nama_kegiatan" type="text" class="mt-1 block w-full" :value="old('nama_kegiatan', $kegiatan?->nama_kegiatan)" required autofocus />
    <x-input-error :messages="$errors->get('nama_kegiatan')" class="mt-2" />
</div>

<div>
    <x-input-label for="tanggal" :value="__('Tanggal')" />
    <x-text-input id="tanggal" name="tanggal" type="date" class="mt-1 block w-full" :value="old('tanggal', $kegiatan?->tanggal?->toDateString())" required />
    <x-input-error :messages="$errors->get('tanggal')" class="mt-2" />
</div>

<div>
    <x-input-label for="status" :value="__('Status')" />
    <select id="status" name="status" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required>
        @foreach ($statuses as $value => $label)
            <option value="{{ $value }}" {{ old('status', $kegiatan?->status) === $value ? 'selected' : '' }}>
                {{ $label }}
            </option>
        @endforeach
    </select>
    <x-input-error :messages="$errors->get('status')" class="mt-2" />
</div>

<div>
    <x-input-label for="deskripsi" :value="__('Deskripsi')" />
    <textarea id="deskripsi" name="deskripsi" rows="4" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">{{ old('deskripsi', $kegiatan?->deskripsi) }}</textarea>
    <x-input-error :messages="$errors->get('deskripsi')" class="mt-2" />
</div>
