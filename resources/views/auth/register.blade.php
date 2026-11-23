
<x-guest-layout>
    <div class="auth-page">

        {{-- PANEL KIRI --}}
        <div class="auth-side"
            style="
                background:
                    linear-gradient(rgba(5,46,22,.86), rgba(5,46,22,.82)),
                    url('https://images.unsplash.com/photo-1592841200221-a6898f307baa?q=80&w=1600&auto=format&fit=crop');
                background-position: center;
                background-size: cover;
                color: white;
            ">

            <div class="auth-side-content">

                <div class="auth-brand">
                    <div class="auth-brand-icon">
                        <i class="fa-solid fa-seedling"></i>
                    </div>

                    <div>
                        <div class="auth-brand-name" style="color:white">
                            SIBUCARA PRO
                        </div>
                        <div style="font-size:12px; color:#bbf7d0; letter-spacing:1px">
                            SISTEM INFORMASI BUDIDAYA CABAI RAWIT
                        </div>
                    </div>
                </div>

                <div style="margin-bottom:25px">
                    <span style="
                        display:inline-block;
                        padding:9px 15px;
                        border-radius:30px;
                        background:rgba(255,255,255,.12);
                        border:1px solid rgba(255,255,255,.2);
                        color:#dcfce7;
                        font-size:12px;
                    ">
                        <i class="fa-solid fa-circle mr-2" style="font-size:8px"></i>
                        Pertanian Modern dan Terintegrasi
                    </span>
                </div>

                <h1 style="color:white">
                    Kelola Budidaya Cabai Rawit dengan Lebih
                    <span style="color:#86efac">Cerdas.</span>
                </h1>

                <p style="color:#dcfce7">
                    Bergabunglah dengan SIBUCARA PRO untuk mengelola
                    aktivitas pertanian, memantau pekerjaan, mencatat
                    hasil panen, dan mengembangkan usaha pertanian
                    secara lebih terstruktur.
                </p>

                <div class="auth-benefits">

                    <div class="auth-benefit" style="color:#f0fdf4">
                        <i class="fa-solid fa-chart-line"></i>
                        <div>
                            <strong>Monitoring Pertanian</strong>
                            <div style="color:#bbf7d0;font-size:12px">
                                Pantau perkembangan budidaya cabai rawit.
                            </div>
                        </div>
                    </div>

                    <div class="auth-benefit" style="color:#f0fdf4">
                        <i class="fa-solid fa-users"></i>
                        <div>
                            <strong>Manajemen Pekerja</strong>
                            <div style="color:#bbf7d0;font-size:12px">
                                Atur tugas dan aktivitas pekerja pertanian.
                            </div>
                        </div>
                    </div>

                    <div class="auth-benefit" style="color:#f0fdf4">
                        <i class="fa-solid fa-boxes-stacked"></i>
                        <div>
                            <strong>Pencatatan Hasil Panen</strong>
                            <div style="color:#bbf7d0;font-size:12px">
                                Kelola stok, panen, penjualan, dan keuangan.
                            </div>
                        </div>
                    </div>

                </div>

                <div style="margin-top:55px;font-size:12px;color:#bbf7d0">
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
                        <i class="fa-solid fa-seedling"></i>
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

                {{-- HEADER --}}
                <div style="margin-bottom:25px">

                    <div class="auth-badge">
                        <i class="fa-solid fa-user-plus"></i>
                        PENDAFTARAN PENGGUNA
                    </div>

                    <h2 class="auth-title">
                        Buat Akun Baru
                    </h2>

                    <p class="auth-description">
                        Lengkapi informasi berikut untuk membuat akun SIBUCARA PRO.
                    </p>

                </div>


                {{-- VALIDATION ERRORS --}}
                @if ($errors->any())
                    <div style="
                        margin-bottom:20px;
                        padding:15px;
                        border:1px solid #fecaca;
                        background:#fef2f2;
                        border-radius:10px;
                        color:#b91c1c;
                        font-size:12px;
                    ">
                        <strong>
                            <i class="fa-solid fa-circle-exclamation"></i>
                            Terdapat kesalahan pada formulir.
                        </strong>

                        <ul style="padding-left:20px;margin-top:8px;list-style:disc">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif


                {{-- FORM REGISTER --}}
                <form action="{{ route('register') }}" method="POST" class="auth-form">
                    @csrf

                    {{-- NAMA --}}
                    <div class="auth-field">
                        <label for="name">Nama Lengkap</label>

                        <div class="auth-input-wrap">
                            <i class="fa-regular fa-user auth-input-icon"></i>

                            <input
                                type="text"
                                name="name"
                                id="name"
                                value="{{ old('name') }}"
                                class="auth-input"
                                placeholder="Masukkan nama lengkap"
                                autocomplete="name"
                                required>
                        </div>

                        @error('name')
                            <div class="auth-error">{{ $message }}</div>
                        @enderror
                    </div>


                    {{-- EMAIL --}}
                    <div class="auth-field">
                        <label for="email">Alamat Email</label>

                        <div class="auth-input-wrap">
                            <i class="fa-regular fa-envelope auth-input-icon"></i>

                            <input
                                type="email"
                                name="email"
                                id="email"
                                value="{{ old('email') }}"
                                class="auth-input"
                                placeholder="contoh@email.com"
                                autocomplete="email"
                                required>
                        </div>

                        @error('email')
                            <div class="auth-error">{{ $message }}</div>
                        @enderror
                    </div>


                    {{-- NOMOR WHATSAPP --}}
                    <div class="auth-field">
                        <label for="phone">Nomor WhatsApp</label>

                        <div class="auth-input-wrap">
                            <i class="fa-brands fa-whatsapp auth-input-icon"></i>

                            <input
                                type="tel"
                                name="phone"
                                id="phone"
                                value="{{ old('phone') }}"
                                class="auth-input"
                                placeholder="Contoh: 081234567890"
                                autocomplete="tel"
                                required>
                        </div>

                        @error('phone')
                            <div class="auth-error">{{ $message }}</div>
                        @enderror
                    </div>


                    {{-- ROLE --}}
                    <div class="auth-field">

                        <label>Daftar Sebagai</label>

                        <div class="auth-role-list">

                            <label class="auth-role">
                                <input
                                    type="radio"
                                    name="role"
                                    value="pemilik"
                                    {{ old('role', 'pemilik') == 'pemilik' ? 'checked' : '' }}
                                    required>

                                <span class="auth-role-content">
                                    <i class="fa-solid fa-house-user"></i>
                                    <strong>Pemilik</strong>
                                    <span>Pemilik pertanian</span>
                                </span>
                            </label>


                            <label class="auth-role">
                                <input
                                    type="radio"
                                    name="role"
                                    value="mandor"
                                    {{ old('role') == 'mandor' ? 'checked' : '' }}>

                                <span class="auth-role-content">
                                    <i class="fa-solid fa-clipboard-list"></i>
                                    <strong>Mandor</strong>
                                    <span>Pengawas kebun</span>
                                </span>
                            </label>


                            <label class="auth-role">
                                <input
                                    type="radio"
                                    name="role"
                                    value="pekerja"
                                    {{ old('role') == 'pekerja' ? 'checked' : '' }}>

                                <span class="auth-role-content">
                                    <i class="fa-solid fa-person-digging"></i>
                                    <strong>Pekerja</strong>
                                    <span>Pekerja kebun</span>
                                </span>
                            </label>

                        </div>

                        @error('role')
                            <div class="auth-error">{{ $message }}</div>
                        @enderror

                    </div>


                    {{-- PASSWORD --}}
                    <div class="auth-field">
                        <label for="password">Password</label>

                        <div class="auth-input-wrap">
                            <i class="fa-solid fa-lock auth-input-icon"></i>

                            <input
                                type="password"
                                name="password"
                                id="password"
                                class="auth-input"
                                style="padding-right:45px"
                                placeholder="Minimal 8 karakter"
                                autocomplete="new-password"
                                required>

                            <button
                                type="button"
                                class="auth-eye"
                                onclick="togglePassword('password', 'icon-password')"
                                aria-label="Tampilkan password">
                                <i class="fa-regular fa-eye" id="icon-password"></i>
                            </button>
                        </div>

                        @error('password')
                            <div class="auth-error">{{ $message }}</div>
                        @enderror
                    </div>


                    {{-- KONFIRMASI PASSWORD --}}
                    <div class="auth-field">
                        <label for="password_confirmation">
                            Konfirmasi Password
                        </label>

                        <div class="auth-input-wrap">
                            <i class="fa-solid fa-lock auth-input-icon"></i>

                            <input
                                type="password"
                                name="password_confirmation"
                                id="password_confirmation"
                                class="auth-input"
                                style="padding-right:45px"
                                placeholder="Masukkan ulang password"
                                autocomplete="new-password"
                                required>

                            <button
                                type="button"
                                class="auth-eye"
                                onclick="togglePassword('password_confirmation', 'icon-confirm-password')"
                                aria-label="Tampilkan konfirmasi password">
                                <i class="fa-regular fa-eye" id="icon-confirm-password"></i>
                            </button>
                        </div>
                    </div>


                    {{-- PERSETUJUAN --}}
                    <div>
                        <label class="auth-terms">
                            <input
                                type="checkbox"
                                name="terms"
                                value="1"
                                {{ old('terms') ? 'checked' : '' }}
                                required>

                            <span>
                                Saya menyetujui
                                <a href="#" class="auth-small-link">Syarat dan Ketentuan</a>
                                serta
                                <a href="#" class="auth-small-link">Kebijakan Privasi</a>
                                SIBUCARA PRO.
                            </span>
                        </label>

                        @error('terms')
                            <div class="auth-error">{{ $message }}</div>
                        @enderror
                    </div>


                    {{-- TOMBOL DAFTAR --}}
                    <button type="submit" class="auth-button">
                        <i class="fa-solid fa-user-plus"></i>
                        &nbsp; Buat Akun Sekarang
                    </button>


                    {{-- LINK LOGIN --}}
                    <div class="auth-bottom-link">
                        Sudah memiliki akun?
                        <a href="{{ route('login') }}">
                            <strong>Masuk di sini</strong>
                        </a>
                    </div>

                </form>


                {{-- FOOTER --}}
                <div class="auth-note">
                    <i class="fa-solid fa-shield-halved"></i>
                    Data akun Anda dilindungi oleh sistem keamanan SIBUCARA PRO.
                </div>

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


    {{-- SCRIPT TAMPILKAN PASSWORD --}}
    <script>
        function togglePassword(inputId, iconId) {
            const input = document.getElementById(inputId);
            const icon = document.getElementById(iconId);

            if (!input || !icon) return;

            if (input.type === 'password') {
                input.type = 'text';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                input.type = 'password';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        }
    </script>

</x-guest-layout>
