<x-guest-layout>

    <div class="min-h-screen grid lg:grid-cols-2 bg-white">

        {{-- LEFT PANEL --}}
        <div class="hidden lg:flex flex-col justify-between bg-[#f4f7f6] p-12 xl:p-16">

            <a href="{{ url('/') }}" class="flex items-center gap-3">
                <div class="w-12 h-12 rounded-xl bg-green-100 flex items-center justify-center text-2xl">
                    🌿
                </div>

                <span class="text-2xl font-extrabold text-slate-800">
                    SIBUCARA<span class="text-[#288140]">PRO</span>
                </span>
            </a>

            <div class="max-w-lg">
                <span class="inline-flex px-4 py-2 rounded-full bg-green-100 text-[#288140] text-sm font-bold">
                    Selamat Datang Kembali
                </span>

                <h1 class="mt-7 text-4xl xl:text-5xl font-extrabold text-slate-900 leading-tight">
                    Kelola Pertanian Cabai Rawit
                    <span class="text-[#288140]">Lebih Mudah.</span>
                </h1>

                <p class="mt-6 text-slate-600 leading-relaxed text-lg">
                    Masuk ke akun SIBUCARA PRO untuk mengelola dan
                    memantau kegiatan budidaya cabai rawit.
                </p>

                <div class="mt-10 space-y-6">

                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 shrink-0 rounded-xl bg-white shadow-sm flex items-center justify-center text-2xl">
                            🌱
                        </div>
                        <div>
                            <h3 class="font-bold text-slate-800">Pengelolaan Budidaya</h3>
                            <p class="text-sm text-slate-500 mt-1">Kelola kegiatan pertanian lebih terstruktur.</p>
                        </div>
                    </div>

                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 shrink-0 rounded-xl bg-white shadow-sm flex items-center justify-center text-2xl">
                            📊
                        </div>
                        <div>
                            <h3 class="font-bold text-slate-800">Pemantauan dan Laporan</h3>
                            <p class="text-sm text-slate-500 mt-1">Pantau perkembangan dan hasil budidaya.</p>
                        </div>
                    </div>

                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 shrink-0 rounded-xl bg-white shadow-sm flex items-center justify-center text-2xl">
                            👥
                        </div>
                        <div>
                            <h3 class="font-bold text-slate-800">Kolaborasi Pengguna</h3>
                            <p class="text-sm text-slate-500 mt-1">Menghubungkan pemilik, mandor, dan pekerja.</p>
                        </div>
                    </div>

                </div>
            </div>

            <p class="text-sm text-slate-400">
                © {{ date('Y') }} SIBUCARA PRO
            </p>
        </div>

        {{-- RIGHT PANEL --}}
        <div class="flex items-center justify-center px-5 py-12 sm:px-10 lg:px-12">

            <div class="w-full max-w-md">

                {{-- MOBILE LOGO --}}
                <a href="{{ url('/') }}" class="lg:hidden flex items-center justify-center gap-2 mb-10">
                    <span class="text-2xl">🌿</span>
                    <span class="text-xl font-extrabold text-slate-800">
                        SIBUCARA<span class="text-[#288140]">PRO</span>
                    </span>
                </a>

                <div class="mb-9">
                    <h2 class="text-3xl font-extrabold text-slate-900">
                        Masuk ke Akun
                    </h2>

                    <p class="mt-3 text-slate-500">
                        Silakan masukkan email dan password untuk melanjutkan.
                    </p>
                </div>

                {{-- SESSION STATUS --}}
                <x-auth-session-status class="mb-5" :status="session('status')" />

                {{-- LOGIN FORM --}}
                <form method="POST" action="{{ route('login') }}" class="space-y-6">
                    @csrf

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
                            autofocus
                            autocomplete="username"
                            placeholder="nama@email.com"
                            class="w-full px-4 py-3.5 rounded-xl border border-slate-200 bg-slate-50 focus:bg-white focus:border-[#288140] focus:ring-2 focus:ring-green-100 outline-none transition">

                        @error('email')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- PASSWORD --}}
                    <div x-data="{ showPassword: false }">
                        <div class="flex justify-between items-center mb-2">
                            <label for="password" class="text-sm font-semibold text-slate-700">
                                Password
                            </label>

                            @if (Route::has('password.request'))
                            <a href="{{ route('password.request') }}"
                                class="text-sm font-semibold text-[#288140] hover:text-green-700">
                                Lupa Password?
                            </a>
                            @endif
                        </div>

                        <div class="relative">
                            <input
                                id="password"
                                :type="showPassword ? 'text' : 'password'"
                                name="password"
                                required
                                autocomplete="current-password"
                                placeholder="Masukkan password"
                                class="w-full px-4 py-3.5 pr-14 rounded-xl border border-slate-200 bg-slate-50 focus:bg-white focus:border-[#288140] focus:ring-2 focus:ring-green-100 outline-none transition">

                            <button type="button"
                                @click="showPassword = !showPassword"
                                class="absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 hover:text-[#288140]">

                                <span x-text="showPassword ? 'Sembunyikan' : 'Lihat'"
                                    class="text-xs font-semibold"></span>
                            </button>
                        </div>

                        @error('password')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- REMEMBER ME --}}
                    <div class="flex items-center">
                        <input
                            id="remember_me"
                            type="checkbox"
                            name="remember"
                            class="w-4 h-4 rounded border-slate-300 text-[#288140] focus:ring-green-500">

                        <label for="remember_me" class="ml-3 text-sm text-slate-600">
                            Ingat saya
                        </label>

                    </div>

                    {{-- SUBMIT --}}
                    <button type="submit"
                        class="w-full py-4 rounded-xl bg-[#288140] text-white font-bold hover:bg-green-700 shadow-lg shadow-green-900/10 transition">
                        Masuk ke SIBUCARA

                        
                    </button>

                </form>

                {{-- REGISTER LINK --}}
                <div class="mt-8 text-center">
                    <p class="text-sm text-slate-500">
                        Belum memiliki akun?
                        <a href="{{ route('register') }}"
                            class="font-bold text-[#288140] hover:text-green-700">
                            Daftar Sekarang
                        </a>
                    </p>
                </div>

                {{-- SECURITY INFO --}}
                <div class="mt-10 flex items-center justify-center gap-2 text-xs text-slate-400">
                    <span>🔒</span>
                    <span>Informasi akun Anda dilindungi oleh sistem.</span>
                </div>

            </div>

        </div>

    </div>

</x-guest-layout>
