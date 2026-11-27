<div class="flex flex-col gap-6">
    <div>
        <x-input-label for="pertanian_id" :value="__('Pertanian')" />
        <select id="pertanian_id" name="pertanian_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
            @foreach ($pertanians as $pertanian)
                <option value="{{ $pertanian->id }}" @selected(old('pertanian_id', $tanaman?->pertanian_id) == $pertanian->id)>{{ $pertanian->nama_pertanian }}</option>
            @endforeach
        </select>
        <x-input-error :messages="$errors->get('pertanian_id')" class="mt-2" />
    </div>

    <div>
        <x-input-label for="nama_tanaman" :value="__('Nama Tanaman')" />
        <x-text-input id="nama_tanaman" name="nama_tanaman" type="text" class="mt-1 block w-full" :value="old('nama_tanaman', $tanaman?->nama_tanaman)" required autofocus />
        <x-input-error :messages="$errors->get('nama_tanaman')" class="mt-2" />
    </div>

    <div>
        <x-input-label for="jenis" :value="__('Jenis')" />
        <x-text-input id="jenis" name="jenis" type="text" class="mt-1 block w-full" :value="old('jenis', $tanaman?->jenis)" required />
        <x-input-error :messages="$errors->get('jenis')" class="mt-2" />
    </div>

    <div>
        <x-input-label for="jumlah" :value="__('Jumlah')" />
        <x-text-input id="jumlah" name="jumlah" type="number" min="1" class="mt-1 block w-full" :value="old('jumlah', $tanaman?->jumlah)" required />
        <x-input-error :messages="$errors->get('jumlah')" class="mt-2" />
    </div>

    <div>
        <x-input-label for="tanggal_tanam" :value="__('Tanggal Tanam')" />
        <x-text-input id="tanggal_tanam" name="tanggal_tanam" type="date" class="mt-1 block w-full" :value="old('tanggal_tanam', $tanaman?->tanggal_tanam?->format('Y-m-d'))" required />
        <x-input-error :messages="$errors->get('tanggal_tanam')" class="mt-2" />
    </div>

    <div>
        <x-input-label for="status" :value="__('Status')" />
        <select id="status" name="status" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
            @foreach (App\Models\Tanaman::STATUSES as $value => $label)
                <option value="{{ $value }}" @selected(old('status', $tanaman?->status) === $value)>{{ $label }}</option>
            @endforeach
        </select>
        <x-input-error :messages="$errors->get('status')" class="mt-2" />
    </div>
</div>
