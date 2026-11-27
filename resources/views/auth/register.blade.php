
<x-guest-layout>

    <div class="min-h-screen grid lg:grid-cols-2 bg-white">

        {{-- LEFT PANEL WITH AGRICULTURE IMAGE --}}
        <div class="hidden lg:flex relative flex-col justify-between p-12 xl:p-16 overflow-hidden">

            <img
                src="https://images.unsplash.com/photo-1500382017468-9049fed747ef?auto=format&fit=crop&w=1200&q=85"
                alt="Area pertanian"
                class="absolute inset-0 w-full h-full object-cover">

            <div class="absolute inset-0 bg-gradient-to-br from-green-950/95 via-green-900/85 to-green-800/75"></div>

            {{-- BRAND --}}
            <a href="{{ url('/') }}" class="relative z-10 flex items-center gap-3">
                <div class="w-12 h-12 rounded-xl bg-white/15 backdrop-blur flex items-center justify-center text-2xl">
                    🌿
                </div>

                <span class="text-2xl font-extrabold text-white">
                    SIBUCARA<span class="text-green-300">PRO</span>
                </span>
            </a>

            {{-- DESCRIPTION --}}
            <div class="relative z-10 max-w-lg text-white">

                <span class="inline-flex px-4 py-2 rounded-full bg-white/15 text-green-100 text-sm font-semibold backdrop-blur">
                    Bergabung Bersama SIBUCARA PRO
                </span>

                <h1 class="mt-7 text-4xl xl:text-5xl font-extrabold leading-tight">
                    Kelola Budidaya Cabai Rawit dengan
                    <span class="text-green-300">Lebih Cerdas.</span>
                </h1>

                <p class="mt-6 text-green-50/90 leading-relaxed text-lg">
                    Bergabunglah dan mulai kelola kegiatan budidaya cabai rawit
                    melalui sistem informasi yang terintegrasi.
                </p>

                <div class="mt-10 space-y-6">

                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 shrink-0 rounded-xl bg-white/15 flex items-center justify-center text-2xl">
                            🌱
                        </div>
                        <div>
                            <h3 class="font-bold">Kelola Budidaya</h3>
                            <p class="text-sm text-green-100/80 mt-1">
                                Pengelolaan kegiatan pertanian lebih terstruktur.
                            </p>
                        </div>
                    </div>

                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 shrink-0 rounded-xl bg-white/15 flex items-center justify-center text-2xl">
                            📊
                        </div>
                        <div>
                            <h3 class="font-bold">Pantau Perkembangan</h3>
                            <p class="text-sm text-green-100/80 mt-1">
                                Pantau aktivitas dan perkembangan tanaman.
                            </p>
                        </div>
                    </div>

                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 shrink-0 rounded-xl bg-white/15 flex items-center justify-center text-2xl">
                            👥
                        </div>
                        <div>
                            <h3 class="font-bold">Kolaborasi Tim</h3>
                            <p class="text-sm text-green-100/80 mt-1">
                                Hubungkan pemilik, mandor, dan pekerja.
                            </p>
                        </div>
                    </div>

                </div>
            </div>

            <p class="relative z-10 text-sm text-green-100/70">
                © {{ date('Y') }} SIBUCARA PRO
            </p>
        </div>

        {{-- RIGHT PANEL --}}
        <div class="flex items-center justify-center px-5 py-12 sm:px-10 lg:px-12">

            <div class="w-full max-w-lg">

                {{-- MOBILE LOGO --}}
                <a href="{{ url('/') }}" class="lg:hidden flex items-center justify-center gap-2 mb-9">
                    <span class="text-2xl">🌿</span>
                    <span class="text-xl font-extrabold text-slate-800">
                        SIBUCARA<span class="text-[#288140]">PRO</span>
                    </span>
                </a>

                <div class="mb-8">
                    <h2 class="text-3xl font-extrabold text-slate-900">
                        Buat Akun Baru
                    </h2>

                    <p class="mt-3 text-slate-500">
                        Lengkapi informasi berikut untuk mendaftar ke SIBUCARA PRO.
                    </p>
                </div>

                <form method="POST" action="{{ route('register') }}" class="space-y-5">
                    @csrf

                    {{-- NAME --}}
                    <div>
                        <label for="name" class="block text-sm font-semibold text-slate-700 mb-2">
                            Nama Lengkap
                        </label>

                        <input
                            id="name"
                            type="text"
                            name="name"
                            value="{{ old('name') }}"
                            required
                            autofocus
                            autocomplete="name"
                            placeholder="Masukkan nama lengkap"
                            class="w-full px-4 py-3.5 rounded-xl border border-slate-200 bg-slate-50 focus:bg-white focus:border-[#288140] focus:ring-2 focus:ring-green-100 outline-none transition">

                        @error('name')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- USERNAME --}}
                    <div>
                        <label for="username" class="block text-sm font-semibold text-slate-700 mb-2">
                            Username
                        </label>

                        <input
                            id="username"
                            type="text"
                            name="username"
                            value="{{ old('username') }}"
                            required
                            autocomplete="username"
                            placeholder="Masukkan username unik"
                            class="w-full px-4 py-3.5 rounded-xl border border-slate-200 bg-slate-50 focus:bg-white focus:border-[#288140] focus:ring-2 focus:ring-green-100 outline-none transition">

                        @error('username')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- EMAIL --}}
                    <div>
                        <label for="email" class="block text-sm font-semibold text-slate-700 mb-2">
                            Alamat Email
                        </label>

                        <input
                            id="email"
                            type="email"
                            name="email"
                            value="{{ old('email') }}"
                            required
                            autocomplete="username"
                            placeholder="nama@email.com"
                            class="w-full px-4 py-3.5 rounded-xl border border-slate-200 bg-slate-50 focus:bg-white focus:border-[#288140] focus:ring-2 focus:ring-green-100 outline-none transition">

                        @error('email')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- WHATSAPP --}}
                    <div>
                        <label for="phone" class="block text-sm font-semibold text-slate-700 mb-2">
                            Nomor WhatsApp
                        </label>

                        <input
                            id="phone"
                            type="tel"
                            name="phone"
                            value="{{ old('phone') }}"
                            placeholder="Contoh: 08xxxxxxxxxx"
                            class="w-full px-4 py-3.5 rounded-xl border border-slate-200 bg-slate-50 focus:bg-white focus:border-[#288140] focus:ring-2 focus:ring-green-100 outline-none transition">

                        @error('phone')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- ROLE --}}
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-3">
                            Daftar Sebagai
                        </label>

                        <div class="grid grid-cols-3 gap-3">

                            <label class="cursor-pointer">
                                <input
                                    type="radio"
                                    name="role"
                                    value="pemilik"
                                    class="peer sr-only"
                                    {{ old('role') == 'pemilik' ? 'checked' : '' }}
                                    required>

                                <div class="h-full rounded-xl border-2 border-slate-200 bg-white p-3 text-center transition peer-checked:border-[#288140] peer-checked:bg-green-50 hover:border-green-300">
                                    <div class="text-2xl mb-2">👨‍🌾</div>
                                    <span class="text-xs sm:text-sm font-bold text-slate-700">Pemilik</span>
                                </div>
                            </label>

                            <label class="cursor-pointer">
                                <input
                                    type="radio"
                                    name="role"
                                    value="mandor"
                                    class="peer sr-only"
                                    {{ old('role') == 'mandor' ? 'checked' : '' }}>

                                <div class="h-full rounded-xl border-2 border-slate-200 bg-white p-3 text-center transition peer-checked:border-[#288140] peer-checked:bg-green-50 hover:border-green-300">
                                    <div class="text-2xl mb-2">📋</div>
                                    <span class="text-xs sm:text-sm font-bold text-slate-700">Mandor</span>
                                </div>
                            </label>

                            <label class="cursor-pointer">
                                <input
                                    type="radio"
                                    name="role"
                                    value="pekerja"
                                    class="peer sr-only"
                                    {{ old('role') == 'pekerja' ? 'checked' : '' }}>

                                <div class="h-full rounded-xl border-2 border-slate-200 bg-white p-3 text-center transition peer-checked:border-[#288140] peer-checked:bg-green-50 hover:border-green-300">
                                    <div class="text-2xl mb-2">🧑‍🌾</div>
                                    <span class="text-xs sm:text-sm font-bold text-slate-700">Pekerja</span>
                                </div>
                            </label>

                        </div>

                        @error('role')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- PASSWORD --}}
                    <div x-data="{ showPassword: false }">
                        <label for="password" class="block text-sm font-semibold text-slate-700 mb-2">
                            Password
                        </label>

                        <div class="relative">
                            <input
                                id="password"
                                :type="showPassword ? 'text' : 'password'"
                                name="password"
                                required
                                autocomplete="new-password"
                                placeholder="Minimal 8 karakter"
                                class="w-full px-4 py-3.5 pr-20 rounded-xl border border-slate-200 bg-slate-50 focus:bg-white focus:border-[#288140] focus:ring-2 focus:ring-green-100 outline-none transition">

                            <button type="button"
                                @click="showPassword = !showPassword"
                                class="absolute right-4 top-1/2 -translate-y-1/2 text-xs font-semibold text-slate-400 hover:text-[#288140]">
                                <span x-text="showPassword ? 'Sembunyikan' : 'Lihat'"></span>
                            </button>
                        </div>

                        @error('password')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- CONFIRM PASSWORD --}}
                    <div x-data="{ showPassword: false }">
                        <label for="password_confirmation" class="block text-sm font-semibold text-slate-700 mb-2">
                            Konfirmasi Password
                        </label>

                        <div class="relative">
                            <input
                                id="password_confirmation"
                                :type="showPassword ? 'text' : 'password'"
                                name="password_confirmation"
                                required
                                autocomplete="new-password"
                                placeholder="Ulangi password"
                                class="w-full px-4 py-3.5 pr-20 rounded-xl border border-slate-200 bg-slate-50 focus:bg-white focus:border-[#288140] focus:ring-2 focus:ring-green-100 outline-none transition">

                            <button type="button"
                                @click="showPassword = !showPassword"
                                class="absolute right-4 top-1/2 -translate-y-1/2 text-xs font-semibold text-slate-400 hover:text-[#288140]">
                                <span x-text="showPassword ? 'Sembunyikan' : 'Lihat'"></span>
                            </button>
                        </div>
                    </div>

                    {{-- TERMS --}}
                    <div class="flex items-start gap-3">
                        <input
                            id="terms"
                            type="checkbox"
                            name="terms"
                            value="1"
                            required
                            class="mt-1 w-4 h-4 rounded border-slate-300 text-[#288140] focus:ring-green-500">

                        <label for="terms" class="text-sm text-slate-500 leading-relaxed">
                            Saya menyetujui ketentuan penggunaan dan kebijakan privasi SIBUCARA PRO.
                        </label>
                    </div>

                    {{-- SUBMIT --}}
                    <button type="submit"
                        class="w-full py-4 rounded-xl bg-[#288140] text-white font-bold hover:bg-green-700 shadow-lg shadow-green-900/10 transition">
                        Buat Akun Sekarang
                    </button>

                </form>

                {{-- LOGIN LINK --}}
                <div class="mt-7 text-center">
                    <p class="text-sm text-slate-500">
                        Sudah memiliki akun?
                        <a href="{{ route('login') }}"
                            class="font-bold text-[#288140] hover:text-green-700">
                            Masuk di sini
                        </a>
                    </p>
                </div>

            </div>
        </div>

    </div>

</x-guest-layout>
