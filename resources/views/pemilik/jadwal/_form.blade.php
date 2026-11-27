<div class="flex flex-col gap-6">
    <div>
        <x-input-label for="tugas_id" :value="__('Tugas')" />
        <select id="tugas_id" name="tugas_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
            @foreach ($tugasOptions as $tugas)
                <option value="{{ $tugas->id }}" @selected(old('tugas_id', $jadwal?->tugas_id) == $tugas->id)>
                    {{ $tugas->judul_tugas }} — {{ $tugas->budidaya->tanaman->pertanian->nama_pertanian }}
                </option>
            @endforeach
        </select>
        <x-input-error :messages="$errors->get('tugas_id')" class="mt-2" />
    </div>

    <div>
        <x-input-label for="tanggal" :value="__('Tanggal')" />
        <x-text-input id="tanggal" name="tanggal" type="date" class="mt-1 block w-full" :value="old('tanggal', $jadwal?->tanggal?->format('Y-m-d'))" required autofocus />
        <x-input-error :messages="$errors->get('tanggal')" class="mt-2" />
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
        <div>
            <x-input-label for="waktu_mulai" :value="__('Waktu Mulai')" />
            <x-text-input id="waktu_mulai" name="waktu_mulai" type="time" class="mt-1 block w-full" :value="old('waktu_mulai', $jadwal?->waktu_mulai ? substr($jadwal->waktu_mulai, 0, 5) : '')" required />
            <x-input-error :messages="$errors->get('waktu_mulai')" class="mt-2" />
        </div>

        <div>
            <x-input-label for="waktu_selesai" :value="__('Waktu Selesai')" />
            <x-text-input id="waktu_selesai" name="waktu_selesai" type="time" class="mt-1 block w-full" :value="old('waktu_selesai', $jadwal?->waktu_selesai ? substr($jadwal->waktu_selesai, 0, 5) : '')" />
            <x-input-error :messages="$errors->get('waktu_selesai')" class="mt-2" />
        </div>
    </div>

    <div>
        <x-input-label for="keterangan" :value="__('Keterangan')" />
        <textarea id="keterangan" name="keterangan" rows="4" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">{{ old('keterangan', $jadwal?->keterangan) }}</textarea>
        <x-input-error :messages="$errors->get('keterangan')" class="mt-2" />
    </div>
</div>
