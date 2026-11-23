
<x-guest-layout>

    <div class="auth-page">

        {{-- PANEL KIRI --}}
        <div class="auth-side">

            <div class="auth-side-content">

                {{-- LOGO --}}
                <div class="auth-brand">
                    <div class="auth-brand-icon">
                        <i class="fas fa-leaf"></i>
                    </div>

                    <div>
                        <div class="auth-brand-name">
                            SIBUCARA <span class="auth-green">PRO</span>
                        </div>

                        <div style="font-size:11px; color:#64748b; letter-spacing:1px">
                            SISTEM INFORMASI BUDIDAYA CABAI RAWIT
                        </div>
                    </div>
                </div>


                {{-- DESKRIPSI --}}
                <h1>
                    Kelola Pertanian Cabai Rawit Lebih Mudah.
                </h1>

                <p>
                    Satu platform untuk membantu pemilik pertanian,
                    mandor, dan pekerja mengelola kegiatan budidaya
                    cabai rawit secara lebih profesional.
                </p>


                {{-- FITUR --}}
                <div class="auth-benefits">

                    <div class="auth-benefit">
                        <i class="fas fa-seedling"></i>

                        <div>
                            <strong>Pengelolaan Budidaya</strong>
                            <div style="font-size:12px;color:#64748b">
                                Pengelolaan budidaya lebih terorganisir.
                            </div>
                        </div>
                    </div>


                    <div class="auth-benefit">
                        <i class="fas fa-chart-line"></i>

                        <div>
                            <strong>Monitoring Pertanian</strong>
                            <div style="font-size:12px;color:#64748b">
                                Pantau perkembangan pertanian Anda.
                            </div>
                        </div>
                    </div>


                    <div class="auth-benefit">
                        <i class="fas fa-users"></i>

                        <div>
                            <strong>Manajemen Pekerja</strong>
                            <div style="font-size:12px;color:#64748b">
                                Koordinasi tim pertanian lebih mudah.
                            </div>
                        </div>
                    </div>

                </div>


                {{-- FOOTER KIRI --}}
                <div style="margin-top:55px;font-size:12px;color:#94a3b8">
                    © {{ date('Y') }} SIBUCARA PRO. All rights reserved.
                </div>

            </div>

        </div>


        {{-- PANEL KANAN --}}
        <div class="auth-main">

            <div class="auth-card">

                {{-- LOGO MOBILE --}}
                <div class="auth-brand" style="margin-bottom:35px">

                    <div class="auth-brand-icon">
                        <i class="fas fa-leaf"></i>
                    </div>

                    <div>
                        <div class="auth-brand-name">
                            SIBUCARA <span class="auth-green">PRO</span>
                        </div>

                        <div style="font-size:10px;color:#64748b">
                            SISTEM INFORMASI BUDIDAYA CABAI RAWIT
                        </div>
                    </div>

                </div>


                {{-- HEADER LOGIN --}}
                <div class="auth-badge">
                    <i class="fas fa-sign-in-alt"></i>
                    LOGIN PENGGUNA
                </div>

                <h2 class="auth-title">
                    Selamat Datang Kembali
                </h2>

                <p class="auth-description">
                    Masuk ke akun SIBUCARA PRO untuk melanjutkan
                    pengelolaan pertanian Anda.
                </p>


                {{-- STATUS SESSION --}}
                <x-auth-session-status
                    class="mb-4"
                    :status="session('status')" />


                {{-- FORM LOGIN --}}
                <form method="POST"
                    action="{{ route('login') }}"
                    class="auth-form">

                    @csrf


                    {{-- EMAIL --}}
                    <div class="auth-field">

                        <label for="email">
                            Email
                        </label>

                        <div class="auth-input-wrap">

                            <i class="far fa-envelope auth-input-icon"></i>

                            <input
                                id="email"
                                type="email"
                                name="email"
                                value="{{ old('email') }}"
                                placeholder="Masukkan email Anda"
                                autocomplete="username"
                                required
                                autofocus
                                class="auth-input">

                        </div>

                        @error('email')
                            <div class="auth-error">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>


                    {{-- PASSWORD --}}
                    <div class="auth-field" x-data="{ showPassword: false }">

                        <div class="auth-between">

                            <label for="password">
                                Password
                            </label>

                            @if (Route::has('password.request'))
                                <a
                                    href="{{ route('password.request') }}"
                                    class="auth-small-link">
                                    Lupa password?
                                </a>
                            @endif

                        </div>


                        <div class="auth-input-wrap">

                            <i class="fas fa-lock auth-input-icon"></i>

                            <input
                                id="password"
                                :type="showPassword ? 'text' : 'password'"
                                name="password"
                                placeholder="Masukkan password Anda"
                                autocomplete="current-password"
                                required
                                class="auth-input"
                                style="padding-right:45px">


                            {{-- TOGGLE PASSWORD --}}
                            <button
                                type="button"
                                class="auth-eye"
                                @click="showPassword = !showPassword"
                                aria-label="Tampilkan atau sembunyikan password">

                                <i
                                    :class="showPassword ? 'far fa-eye-slash' : 'far fa-eye'">
                                </i>

                            </button>

                        </div>

                        @error('password')
                            <div class="auth-error">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>


                    {{-- INGAT SAYA --}}
                    <div>

                        <label class="auth-remember">

                            <input
                                id="remember"
                                type="checkbox"
                                name="remember">

                            <span>
                                Ingat saya di perangkat ini
                            </span>

                        </label>

                    </div>


                    {{-- TOMBOL LOGIN --}}
                    <button type="submit" class="auth-button">

                        Masuk ke SIBUCARA

                        <i class="fas fa-arrow-right" style="margin-left:7px"></i>

                    </button>

                </form>


                {{-- PEMBATAS --}}
                <div class="auth-divider">
                    atau
                </div>


                {{-- LINK REGISTER --}}
                <a
                    href="{{ route('register') }}"
                    class="auth-bottom-link">

                    Belum memiliki akun?

                    <strong>
                        Daftar sekarang
                    </strong>

                </a>


                {{-- CATATAN KEAMANAN --}}
                <div class="auth-note">

                    <i class="fas fa-shield-alt"></i>

                    Data akun Anda dilindungi oleh sistem keamanan SIBUCARA PRO.

                </div>


                {{-- FOOTER --}}
                <div style="
                    text-align:center;
                    margin-top:20px;
                    font-size:10px;
                    color:#94a3b8;
                ">
                    © {{ date('Y') }} SIBUCARA PRO
                </div>

            </div>

        </div>

    </div>

</x-guest-layout>
