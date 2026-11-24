<!doctype html>
<html lang="id">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>SIBUCARA PRO - Sistem Informasi Budidaya Cabai Rawit</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link
      rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"
    />
    <link
      href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap"
      rel="stylesheet"
    />
    <style>
      body {
        font-family: "Plus Jakarta Sans", sans-serif;
      }
      [x-cloak] { display: none !important; }
      html { scroll-behavior: smooth; }
    </style>
  </head>
  <body class="bg-white text-slate-800">
    <!-- Navbar -->
    <nav
      x-data="{ open: false }"
      class="fixed w-full z-50 bg-white/90 backdrop-blur-md border-b border-slate-100"
    >
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center h-20">
          <!-- Logo -->
          <div class="flex items-center gap-2">
            <i class="fas fa-leaf text-[#288140] text-2xl"></i>
            <span class="font-extrabold text-xl tracking-tight text-slate-800"
              >SIBUCARA<span class="text-[#288140]">PRO</span></span
            >
          </div>

          <!-- Desktop Menu -->
          <div class="hidden md:flex space-x-8">
            <a href="#beranda" class="text-[#288140] font-semibold">Beranda</a>
            <a
              href="#fitur"
              class="text-slate-500 hover:text-[#288140] font-medium transition"
              >Fitur</a
            >
            <a
              href="#informasi"
              class="text-slate-500 hover:text-[#288140] font-medium transition"
              >Berita</a
            >
            <a
              href="#bantuan"
              class="text-slate-500 hover:text-[#288140] font-medium transition"
              >Bantuan</a
            >
          </div>

          <!-- Action Button -->
          <div class="hidden md:flex items-center space-x-4">
            <a
              href="{{ route('login') }}"
              class="text-slate-600 hover:text-[#288140] font-semibold transition"
              >Masuk</a
            >
            <a
              href="{{ route('register') }}"
              class="bg-[#288140] hover:bg-[#206833] text-white px-5 py-2.5 rounded-full font-semibold transition shadow-md"
            >
              Daftar
            </a>
          </div>

          <button type="button" @click="open = !open"
            class="md:hidden inline-flex items-center justify-center w-10 h-10 rounded-lg text-slate-700 hover:bg-green-50"
            aria-label="Buka atau tutup menu navigasi" :aria-expanded="open.toString()">
            <i class="fas" :class="open ? 'fa-times' : 'fa-bars'"></i>
          </button>
        </div>

        <div x-show="open" x-cloak x-transition class="md:hidden border-t border-slate-100 py-4">
          <div class="flex flex-col gap-4 px-4">
            <a @click="open = false" href="#beranda" class="font-semibold text-[#288140]">Beranda</a>
            <a @click="open = false" href="#fitur" class="font-medium text-slate-600">Fitur</a>
            <a @click="open = false" href="#informasi" class="font-medium text-slate-600">Informasi</a>
            <a @click="open = false" href="#bantuan" class="font-medium text-slate-600">Bantuan</a>
            <div class="flex flex-col gap-3 border-t border-slate-100 pt-4">
              <a href="{{ route('login') }}" class="font-semibold text-slate-600">Masuk</a>
              <a href="{{ route('register') }}" class="rounded-full bg-[#288140] px-5 py-2.5 text-center font-semibold text-white">Daftar</a>
            </div>
          </div>
        </div>
      </div>
    </nav>

    <!-- Hero Section -->
    <section
      id="beranda"
      class="relative pt-20 pb-32 lg:pt-32 lg:pb-40 bg-slate-900 overflow-hidden min-h-[600px] flex items-center"
    >
      <!-- Background Image -->
      <img
        src="https://images.unsplash.com/photo-1592982537447-6f2334208f34?q=80&w=2000&auto=format&fit=crop"
        alt="Farming Background"
        class="absolute inset-0 w-full h-full object-cover opacity-50"
      />
      <div
        class="absolute inset-0 bg-gradient-to-r from-slate-900/90 to-transparent"
      ></div>

      <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 w-full">
        <div class="max-w-2xl text-white">
          <!-- Weather Widget Mockup -->
          <div
            class="inline-flex items-center gap-4 bg-black/40 backdrop-blur-sm border border-white/10 rounded-xl p-3 mb-6"
          >
            <div class="text-2xl font-bold">30°C</div>
            <div class="flex gap-2 text-sm text-slate-300">
              <div class="flex flex-col items-center">
                <i class="fas fa-sun text-yellow-400"></i><span>Sen</span>
              </div>
              <div class="flex flex-col items-center">
                <i class="fas fa-cloud-sun text-yellow-200"></i><span>Sel</span>
              </div>
              <div class="flex flex-col items-center">
                <i class="fas fa-cloud-rain text-blue-300"></i><span>Rab</span>
              </div>
            </div>
          </div>

          <h1
            class="text-4xl md:text-5xl lg:text-6xl font-extrabold leading-tight mb-6"
          >
            Kelola Budidaya Cabai Rawit <br />
            <span class="text-[#64d07b]">Lebih Cerdas & Terintegrasi</span>
          </h1>
          <p class="text-lg text-slate-300 mb-10 leading-relaxed">
            Kelola kegiatan budidaya cabai rawit secara lebih terstruktur.
            Pantau aktivitas lahan, atur pekerjaan, dan catat perkembangan
            budidaya melalui satu sistem informasi.
          </p>

          <!-- Stats -->
          <div class="flex gap-8 border-t border-white/20 pt-6">
            <div>
              <div class="text-3xl font-bold text-[#64d07b]">3</div>
              <div class="text-sm text-slate-300">Peran Pengguna</div>
            </div>
            <div>
              <div class="text-3xl font-bold text-[#64d07b]">1</div>
              <div class="text-sm text-slate-300">Sistem Terintegrasi</div>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- Smart Dashboard Preview -->
    <section id="fitur" class="py-16 bg-slate-50">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex flex-col lg:flex-row gap-8 items-center">
          <!-- Left: Dashboard Mockup -->
          <div
            class="w-full lg:w-2/3 bg-slate-900 rounded-2xl p-8 flex items-center justify-center min-h-[400px] shadow-2xl relative overflow-hidden"
          >
            <div
              class="absolute inset-0 opacity-10"
              style="
                background-image: radial-gradient(
                  circle at 2px 2px,
                  white 1px,
                  transparent 0
                );
                background-size: 20px 20px;
              "
            ></div>
            <div class="text-center z-10">
              <div
                class="w-16 h-16 rounded-full bg-[#288140]/20 flex items-center justify-center mx-auto mb-4 border border-[#288140]/30"
              >
                <i class="fas fa-chart-pie text-3xl text-[#64d07b]"></i>
              </div>
              <h3 class="text-xl font-bold text-white mb-2">Smart Dashboard</h3>
              <p class="text-slate-400">
                Pantau informasi dan aktivitas budidaya cabai rawit
              </p>
            </div>
          </div>

          <!-- Right: List -->
          <div
            class="w-full lg:w-1/3 bg-white p-6 rounded-2xl shadow-sm border border-slate-100"
          >
            <h4 class="font-bold text-lg mb-4">Aktivitas Terkini</h4>
            <div class="space-y-4">
              <div
                class="flex items-center gap-3 border-b border-slate-50 pb-3"
              >
                <div
                  class="w-10 h-10 rounded-full bg-blue-100 flex items-center justify-center text-blue-600"
                >
                  <i class="fas fa-tint"></i>
                </div>
                <div>
                  <div class="font-semibold text-sm">Aktivitas Penyiraman</div>
                  <div class="text-xs text-slate-500">
                    Contoh aktivitas • Blok A
                  </div>
                </div>
              </div>
              <div
                class="flex items-center gap-3 border-b border-slate-50 pb-3"
              >
                <div
                  class="w-10 h-10 rounded-full bg-green-100 flex items-center justify-center text-green-600"
                >
                  <i class="fas fa-seedling"></i>
                </div>
                <div>
                  <div class="font-semibold text-sm">Pencatatan Pemupukan</div>
                  <div class="text-xs text-slate-500">
                    Contoh aktivitas • Blok B
                  </div>
                </div>
              </div>
              <div class="flex items-center gap-3">
                <div
                  class="w-10 h-10 rounded-full bg-orange-100 flex items-center justify-center text-orange-600"
                >
                  <i class="fas fa-sun"></i>
                </div>
                <div>
                  <div class="font-semibold text-sm">Pemantauan Kondisi Lahan</div>
                  <div class="text-xs text-slate-500">
                    Contoh informasi pemantauan
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- Rekrutmen Mandor / Lahan Section -->
    <section class="py-16">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <h2 class="text-2xl font-bold mb-8 border-b pb-4">
          Contoh Tampilan Pengelolaan Lahan
        </h2>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
          <!-- Card 1 -->
          <div
            class="bg-white rounded-xl border border-slate-200 overflow-hidden hover:shadow-lg transition-shadow"
          >
            <img
              src="https://images.unsplash.com/photo-1599940824399-b87987ceb72a?w=500&auto=format&fit=crop"
              class="w-full h-40 object-cover"
              alt="Lahan"
            />
            <div class="p-5">
              <div
                class="text-xs font-bold text-white bg-green-600 px-2 py-1 rounded inline-block mb-2"
              >
                Tersedia
              </div>
              <h3 class="font-bold text-lg mb-2">Lahan Cabai Merah</h3>
              <div class="space-y-2 text-sm text-slate-600 mb-4">
                <div class="flex items-center gap-2">
                  <i class="fas fa-map-marker-alt w-4"></i> Malang, Jawa Timur
                </div>
                <div class="flex items-center gap-2">
                  <i class="fas fa-expand w-4"></i> 2 Hektar
                </div>
                <div class="flex items-center gap-2">
                  <i class="fas fa-users w-4"></i> Butuh 5 Pekerja
                </div>
              </div>
              <button
                class="w-full bg-[#eaf4eb] text-[#288140] hover:bg-[#288140] hover:text-white font-semibold py-2 rounded-lg transition-colors border border-[#288140]"
              >
                Lihat Detail
              </button>
            </div>
          </div>

          <!-- Card 2 -->
          <div
            class="bg-white rounded-xl border border-slate-200 overflow-hidden hover:shadow-lg transition-shadow"
          >
            <img
              src="https://images.unsplash.com/photo-1592982537447-6f2334208f34?w=500&auto=format&fit=crop"
              class="w-full h-40 object-cover"
              alt="Lahan"
            />
            <div class="p-5">
              <div
                class="text-xs font-bold text-white bg-green-600 px-2 py-1 rounded inline-block mb-2"
              >
                Tersedia
              </div>
              <h3 class="font-bold text-lg mb-2">Lahan Cabai Rawit</h3>
              <div class="space-y-2 text-sm text-slate-600 mb-4">
                <div class="flex items-center gap-2">
                  <i class="fas fa-map-marker-alt w-4"></i> Kediri, Jawa Timur
                </div>
                <div class="flex items-center gap-2">
                  <i class="fas fa-expand w-4"></i> 1.5 Hektar
                </div>
                <div class="flex items-center gap-2">
                  <i class="fas fa-users w-4"></i> Butuh 3 Pekerja
                </div>
              </div>
              <button
                class="w-full bg-[#eaf4eb] text-[#288140] hover:bg-[#288140] hover:text-white font-semibold py-2 rounded-lg transition-colors border border-[#288140]"
              >
                Lihat Detail
              </button>
            </div>
          </div>

          <!-- Card 3 -->
          <div
            class="bg-white rounded-xl border border-slate-200 overflow-hidden hover:shadow-lg transition-shadow"
          >
            <img
              src="https://images.unsplash.com/photo-1627920769841-f8c5b61e2286?w=500&auto=format&fit=crop"
              class="w-full h-40 object-cover"
              alt="Lahan"
            />
            <div class="p-5">
              <div
                class="text-xs font-bold text-white bg-green-600 px-2 py-1 rounded inline-block mb-2"
              >
                Tersedia
              </div>
              <h3 class="font-bold text-lg mb-2">Lahan Tomat</h3>
              <div class="space-y-2 text-sm text-slate-600 mb-4">
                <div class="flex items-center gap-2">
                  <i class="fas fa-map-marker-alt w-4"></i> Lembang, Jawa Barat
                </div>
                <div class="flex items-center gap-2">
                  <i class="fas fa-expand w-4"></i> 3 Hektar
                </div>
                <div class="flex items-center gap-2">
                  <i class="fas fa-users w-4"></i> Butuh 8 Pekerja
                </div>
              </div>
              <button
                class="w-full bg-[#eaf4eb] text-[#288140] hover:bg-[#288140] hover:text-white font-semibold py-2 rounded-lg transition-colors border border-[#288140]"
              >
                Lihat Detail
              </button>
            </div>
          </div>

          <!-- Card 4 -->
          <div
            class="bg-white rounded-xl border border-slate-200 overflow-hidden hover:shadow-lg transition-shadow"
          >
            <img
              src="https://images.unsplash.com/photo-1599940824399-b87987ceb72a?w=500&auto=format&fit=crop"
              class="w-full h-40 object-cover"
              alt="Lahan"
            />
            <div class="p-5">
              <div
                class="text-xs font-bold text-white bg-red-500 px-2 py-1 rounded inline-block mb-2"
              >
                Penuh
              </div>
              <h3 class="font-bold text-lg mb-2">Lahan Bawang Merah</h3>
              <div class="space-y-2 text-sm text-slate-600 mb-4">
                <div class="flex items-center gap-2">
                  <i class="fas fa-map-marker-alt w-4"></i> Brebes, Jawa Tengah
                </div>
                <div class="flex items-center gap-2">
                  <i class="fas fa-expand w-4"></i> 5 Hektar
                </div>
                <div class="flex items-center gap-2">
                  <i class="fas fa-users w-4"></i> Terpenuhi
                </div>
              </div>
              <button
                class="w-full bg-slate-100 text-slate-400 font-semibold py-2 rounded-lg cursor-not-allowed"
                disabled
              >
                Lihat Detail
              </button>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- Informasi Budidaya Cabai Rawit -->
    <section id="informasi" class="py-12 bg-slate-50">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <h2 class="text-2xl font-bold mb-8">Informasi & Panduan Budidaya</h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
          <!-- Art 1 -->
          <div
            class="relative rounded-2xl overflow-hidden h-72 group cursor-pointer"
          >
            <img
              src="https://images.unsplash.com/photo-1592982537447-6f2334208f34?w=800&auto=format&fit=crop"
              class="absolute inset-0 w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"
            />
            <div
              class="absolute inset-0 bg-gradient-to-t from-black/90 via-black/40 to-transparent"
            ></div>
            <div class="absolute bottom-0 left-0 p-6">
              <span
                class="bg-green-500 text-white text-xs font-bold px-2 py-1 rounded mb-2 inline-block"
                >Panduan</span
              >
              <h3 class="text-white font-bold text-xl leading-tight">
                Sukses Budidaya Cabai Rawit di Musim Kemarau
              </h3>
            </div>
          </div>
          <!-- Art 2 -->
          <div
            class="relative rounded-2xl overflow-hidden h-72 group cursor-pointer"
          >
            <img
              src="https://images.unsplash.com/photo-1500382017468-9049fed747ef?w=800&auto=format&fit=crop"
              class="absolute inset-0 w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"
            />
            <div
              class="absolute inset-0 bg-gradient-to-t from-black/90 via-black/40 to-transparent"
            ></div>
            <div class="absolute bottom-0 left-0 p-6">
              <span
                class="bg-orange-500 text-white text-xs font-bold px-2 py-1 rounded mb-2 inline-block"
                >Cuaca</span
              >
              <h3 class="text-white font-bold text-xl leading-tight">
                Antisipasi Cuaca Panas Menyengat di Indonesia
              </h3>
            </div>
          </div>
          <!-- Art 3 -->
          <div
            class="relative rounded-2xl overflow-hidden h-72 group cursor-pointer"
          >
            <img
              src="https://images.unsplash.com/photo-1627920769841-f8c5b61e2286?w=800&auto=format&fit=crop"
              class="absolute inset-0 w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"
            />
            <div
              class="absolute inset-0 bg-gradient-to-t from-black/90 via-black/40 to-transparent"
            ></div>
            <div class="absolute bottom-0 left-0 p-6">
              <span
                class="bg-green-500 text-white text-xs font-bold px-2 py-1 rounded mb-2 inline-block"
                >Panduan</span
              >
              <h3 class="text-white font-bold text-xl leading-tight">
                Teknik Pemupukan Optimal untuk Hasil Maksimal
              </h3>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- PRO Features -->
    <section class="py-20">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div
          class="bg-gradient-to-br from-[#eaf4eb] to-[#d1ebd4] rounded-3xl p-8 lg:p-12 flex flex-col lg:flex-row items-center gap-12"
        >
          <!-- Left Banner -->
          <div class="w-full lg:w-1/2 flex justify-center">
            <div class="text-center">
              <div
                class="w-32 h-32 bg-white rounded-full flex items-center justify-center shadow-xl mx-auto mb-6"
              >
                <i class="fas fa-leaf text-5xl text-[#288140]"></i>
              </div>
              <h2 class="text-4xl font-extrabold text-[#288140] tracking-tight">
                SIBUCARA<span class="text-slate-800">PRO</span>
              </h2>
              <p
                class="text-sm font-semibold tracking-widest text-slate-500 mt-2 uppercase"
              >
                Sistem Budidaya Cabai Rawit
              </p>
            </div>
          </div>
          <!-- Right Content -->
          <div class="w-full lg:w-1/2">
            <h3 class="text-3xl font-bold mb-4 text-slate-800">
              Satu Platform,<br />Pengelolaan Budidaya Cabai Rawit
            </h3>
            <p class="text-slate-600 mb-8 leading-relaxed">
              Kelola pencatatan dan aktivitas budidaya cabai rawit dalam satu
              platform sesuai peran pengguna.
            </p>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-8">
              <div class="flex items-start gap-3">
                <i class="fas fa-check-circle text-[#288140] mt-1"></i>
                <span class="text-slate-700 font-medium"
                  >Manajemen Pekerja</span
                >
              </div>
              <div class="flex items-start gap-3">
                <i class="fas fa-check-circle text-[#288140] mt-1"></i>
                <span class="text-slate-700 font-medium">Pencatatan Biaya</span>
              </div>
              <div class="flex items-start gap-3">
                <i class="fas fa-check-circle text-[#288140] mt-1"></i>
                <span class="text-slate-700 font-medium"
                  >Pemantauan Kondisi Lahan</span
                >
              </div>
              <div class="flex items-start gap-3">
                <i class="fas fa-check-circle text-[#288140] mt-1"></i>
                <span class="text-slate-700 font-medium"
                  >Informasi Aktivitas</span
                >
              </div>
            </div>
            <a
              href="#bantuan"
              class="inline-flex items-center gap-2 bg-[#288140] hover:bg-[#206833] text-white px-6 py-3 rounded-xl font-bold transition-colors shadow-lg"
            >
              Pelajari Lebih Lanjut <i class="fas fa-arrow-right"></i>
            </a>
          </div>
        </div>
      </div>
    </section>

    <!-- Testimonials -->
    <section class="py-16 bg-slate-50">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <h2 class="text-2xl font-bold mb-2">
          Mendukung Kolaborasi dalam Kegiatan Budidaya
        </h2>
        <p class="text-slate-500 mb-10">
          SIBUCARA PRO dirancang untuk mendukung pembagian peran dan pencatatan
          kegiatan budidaya cabai rawit.
        </p>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
          <!-- Testi 1 -->
          <div
            class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100"
          >
            <div class="flex text-yellow-400 text-sm mb-4">
              <i class="fas fa-star"></i><i class="fas fa-star"></i
              ><i class="fas fa-star"></i><i class="fas fa-star"></i
              ><i class="fas fa-star"></i>
            </div>
            <p class="text-slate-600 mb-6 italic">
              "Aplikasi ini sangat membantu saya dalam mengelola pekerja dan
              memantau kondisi lahan cabai saya meskipun saya sedang di luar
              kota."
            </p>
            <div class="flex items-center gap-3">
              <div
                class="w-10 h-10 rounded-full bg-slate-200 flex items-center justify-center text-slate-500 font-bold"
              >
                B
              </div>
              <div>
                <div class="font-bold text-sm">Contoh Pengguna</div>
                <div class="text-xs text-slate-500">Peran Pemilik</div>
              </div>
            </div>
          </div>
          <!-- Testi 2 -->
          <div
            class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100"
          >
            <div class="flex text-yellow-400 text-sm mb-4">
              <i class="fas fa-star"></i><i class="fas fa-star"></i
              ><i class="fas fa-star"></i><i class="fas fa-star"></i
              ><i class="fas fa-star"></i>
            </div>
            <p class="text-slate-600 mb-6 italic">
              "Sangat mudah digunakan. Fitur laporan cuacanya akurat, jadi saya
              bisa menentukan waktu yang tepat untuk penyemprotan pupuk."
            </p>
            <div class="flex items-center gap-3">
              <div
                class="w-10 h-10 rounded-full bg-slate-200 flex items-center justify-center text-slate-500 font-bold"
              >
                A
              </div>
              <div>
                <div class="font-bold text-sm">Contoh Mandor</div>
                <div class="text-xs text-slate-500">Peran Mandor</div>
              </div>
            </div>
          </div>
          <!-- Testi 3 -->
          <div
            class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100"
          >
            <div class="flex text-yellow-400 text-sm mb-4">
              <i class="fas fa-star"></i><i class="fas fa-star"></i
              ><i class="fas fa-star"></i><i class="fas fa-star"></i
              ><i class="fas fa-star-half-alt"></i>
            </div>
            <p class="text-slate-600 mb-6 italic">
              "Mencari pekerja harian lepas sekarang jauh lebih mudah lewat
              fitur rekrutmen pekerja di SIBUCARA PRO."
            </p>
            <div class="flex items-center gap-3">
              <div
                class="w-10 h-10 rounded-full bg-slate-200 flex items-center justify-center text-slate-500 font-bold"
              >
                S
              </div>
              <div>
                <div class="font-bold text-sm">Contoh Pekerja</div>
                <div class="text-xs text-slate-500">Peran Pekerja</div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- Footer -->
    <footer id="bantuan" class="bg-[#eaf4eb] pt-16 pb-8 border-t border-[#d1ebd4]">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-8 mb-12">
          <div class="md:col-span-2">
            <div class="flex items-center gap-2 mb-4">
              <i class="fas fa-leaf text-[#288140] text-2xl"></i>
              <span class="font-extrabold text-xl tracking-tight text-slate-800"
                >SIBUCARA<span class="text-[#288140]">PRO</span></span
              >
            </div>
            <p class="text-slate-600 mb-6 max-w-sm">
              Sistem informasi untuk membantu pengelolaan budidaya cabai rawit secara lebih terstruktur.
            </p>
            <div class="flex gap-4">
              <a
                href="#bantuan"
                class="w-10 h-10 rounded-full bg-white flex items-center justify-center text-[#288140] hover:bg-[#288140] hover:text-white transition-colors shadow-sm"
                ><i class="fab fa-facebook-f"></i
              ></a>
              <a
                href="#bantuan"
                class="w-10 h-10 rounded-full bg-white flex items-center justify-center text-[#288140] hover:bg-[#288140] hover:text-white transition-colors shadow-sm"
                ><i class="fab fa-instagram"></i
              ></a>
              <a
                href="#bantuan"
                class="w-10 h-10 rounded-full bg-white flex items-center justify-center text-[#288140] hover:bg-[#288140] hover:text-white transition-colors shadow-sm"
                ><i class="fab fa-youtube"></i
              ></a>
            </div>
          </div>

          <div>
            <h4 class="font-bold text-slate-800 mb-4">Perusahaan</h4>
            <ul class="space-y-3">
              <li>
                <a href="#bantuan" class="text-slate-600 hover:text-[#288140]"
                  >Tentang Kami</a
                >
              </li>
              <li>
                <a href="#bantuan" class="text-slate-600 hover:text-[#288140]"
                  >Karir</a
                >
              </li>
              <li>
                <a href="#bantuan" class="text-slate-600 hover:text-[#288140]">Blog</a>
              </li>
              <li>
                <a href="#bantuan" class="text-slate-600 hover:text-[#288140]"
                  >Mitra</a
                >
              </li>
            </ul>
          </div>

          <div>
            <h4 class="font-bold text-slate-800 mb-4">Bantuan</h4>
            <ul class="space-y-3">
              <li>
                <a href="#bantuan" class="text-slate-600 hover:text-[#288140]"
                  >Pusat Bantuan</a
                >
              </li>
              <li>
                <a href="#bantuan" class="text-slate-600 hover:text-[#288140]"
                  >Syarat & Ketentuan</a
                >
              </li>
              <li>
                <a href="#bantuan" class="text-slate-600 hover:text-[#288140]"
                  >Kebijakan Privasi</a
                >
              </li>
              <li>
                <a href="#bantuan" class="text-slate-600 hover:text-[#288140]"
                  >Hubungi Kami</a
                >
              </li>
            </ul>
          </div>
        </div>

        <div
          class="border-t border-[#d1ebd4] pt-8 flex flex-col md:flex-row justify-between items-center text-sm text-slate-500"
        >
          <p>&copy; {{ date('Y') }} SIBUCARA PRO. Hak cipta dilindungi.</p>
          <div class="flex gap-4 mt-4 md:mt-0">
            <span
              >Dibuat dengan <i class="fas fa-heart text-red-500"></i> di
              Indonesia</span
            >
          </div>
        </div>
      </div>
    </footer>
  </body>
</html>