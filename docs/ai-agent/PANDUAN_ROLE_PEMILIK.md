# Panduan AI Agent — Role Pemilik SIBUCARA PRO

## Tujuan
Kembangkan hanya fungsi yang menurut tabel **Daftar Functional Requirement Bab II SRS** memiliki aktor Pemilik. Jangan menganggap seluruh fitur yang tampak relevan otomatis menjadi kewenangan Pemilik.

## Daftar FR Pemilik menurut kolom Stakeholder SRS

| FR | Nama/kebutuhan resmi dalam SRS | Aktor pada tabel FR |
|---|---|---|
| FR-01 | Registrasi akun pengguna | Pemilik, Mandor, Pekerja |
| FR-02 | Login pengguna | Pemilik, Mandor, Pekerja |
| FR-03 | Pembatasan akses berdasarkan peran | Pemilik, Mandor, Pekerja |
| FR-04 | Dashboard sesuai peran pengguna | Pemilik, Mandor, Pekerja |
| FR-05 | Mengelola data pengguna | Pemilik |
| FR-06 | Mengelola data pertanian | Pemilik |
| FR-08 | Memvalidasi permohonan bergabung Mandor | Pemilik |
| FR-11 | Mengelola data tanaman cabai rawit | Pemilik, Mandor |
| FR-12 | Mengelola kegiatan budidaya tanaman cabai rawit | Pemilik, Mandor |
| FR-14 | Mengatur jadwal kegiatan dan pekerjaan | Pemilik, Mandor |
| FR-20 | Menampilkan laporan pekerjaan | Pemilik, Mandor |
| FR-22 | Menampilkan informasi cuaca | Pemilik, Mandor, Pekerja |
| FR-24 | Mengelola data hasil panen | Pemilik, Mandor |
| FR-25 | Mengelola data penjualan hasil panen | Pemilik, Mandor |
| FR-26 | Mengelola data pemasukan dan pengeluaran | Pemilik, Mandor |
| FR-27 | Menampilkan informasi keuangan | Pemilik, Mandor |
| FR-28 | Menampilkan laporan pengelolaan pertanian | Pemilik, Mandor |
| FR-29 | Menghasilkan laporan pengelolaan pertanian secara terstruktur | Pemilik, Mandor |
| FR-30 | Menampilkan informasi budidaya, tugas, stok, panen, penjualan, dan keuangan secara terintegrasi | Pemilik, Mandor, Pekerja |
| FR-31 | Mengirimkan notifikasi kepada pengguna terkait tugas dan perubahan status pekerjaan | Pemilik, Mandor, Pekerja |
| FR-32 | Menampilkan perkembangan pekerjaan | Pemilik, Mandor |

**Jangan masukkan sebagai fungsi Pemilik berdasarkan tabel aktor FR:** FR-07 (pengajuan Mandor), FR-09 (pendaftaran Pekerja), FR-10 (verifikasi Pekerja), FR-13 (Mandor membuat dan memberikan tugas), FR-15–19 (fungsi Pekerja), FR-21 (Mandor memvalidasi hasil pekerjaan), dan FR-23 (pada tabel FR, hanya Mandor mengelola data stok).

## Arahan implementasi berdasarkan isi SRS

- **FR-01–04:** registrasi, login, pembatasan akses berdasarkan peran, dan dashboard sesuai peran. Ikuti alur serta validasi yang ditentukan SRS dan implementasi autentikasi project.
- **FR-05–06:** pengelolaan data pengguna dan data pertanian oleh Pemilik. Periksa detail atribut dan operasi pada SRS/SDD/ERD; jangan otomatis mengasumsikan operasi atau field yang tidak ditentukan.
- **FR-08:** Pemilik memeriksa dan menentukan penerimaan atau penolakan pengajuan Mandor sebelum Mandor memperoleh akses ke pertanian terkait.
- **FR-11–12:** Pemilik dan Mandor mengelola data tanaman cabai rawit serta kegiatan budidaya. Terapkan pembatasan sesuai pertanian dan kewenangan.
- **FR-14:** Pemilik dan Mandor mengatur jadwal kegiatan dan pekerjaan.
- **FR-20:** Pemilik dan Mandor melihat laporan pekerjaan yang dikirim Pekerja. Hak melihat laporan tidak sama dengan hak memvalidasi hasil pekerjaan.
- **FR-22:** informasi cuaca ditampilkan kepada Pemilik, Mandor, dan Pekerja menurut tabel FR. Gunakan integrasi dan sumber yang sudah ditetapkan project/dokumen; jangan mengarang provider atau data.
- **FR-24–27:** menurut tabel FR, Pemilik dan Mandor mengelola data hasil panen, penjualan, pemasukan/pengeluaran, serta melihat informasi keuangan. Perhatikan bahwa uraian FR-25 dan FR-26 pada bagian penjelasan menyebut Pemilik, sementara tabel stakeholder FR mencantumkan Pemilik dan Mandor. **Laporkan ketidaksesuaian ini dan jangan memperluas hak Mandor untuk transaksi tersebut tanpa keputusan pemilik project.**
- **FR-28–29:** Pemilik dan Mandor melihat laporan pengelolaan pertanian serta menghasilkan laporan terstruktur.
- **FR-30:** informasi budidaya, tugas, stok, panen, penjualan, dan keuangan ditampilkan secara terintegrasi. Tampilan dan rincian harus mengikuti hak akses.
- **FR-31:** notifikasi terkait aktivitas/tugas/perubahan status ditujukan kepada pengguna yang relevan.
- **FR-32:** Pemilik dan Mandor melihat perkembangan pekerjaan yang dilakukan Pekerja.

## Batasan khusus
- FR-13 secara eksplisit menyebut Mandor sebagai pembuat dan pemberi tugas pada tabel FR. Jangan membuat tombol “buat tugas” untuk Pemilik berdasarkan asumsi.
- FR-21 adalah validasi hasil pekerjaan oleh Mandor, bukan Pemilik.
- FR-23 pada tabel FR menyebut Mandor sebagai aktor, meskipun bagian kebutuhan pengguna dan Use Case dapat berbeda. Jangan memberikan akses pengelolaan stok kepada Pemilik tanpa mencatat dan menyelesaikan ketidaksesuaian dokumen.
- FR-25 dan FR-26 memiliki perbedaan antara tabel stakeholder FR dan uraian penjelasan FR. Minta keputusan sebelum menerapkan hak kelola transaksi keuangan/penjualan untuk Mandor.
- FR-22 mencantumkan Pekerja dalam tabel FR, tetapi daftar Use Case “Melihat Informasi Cuaca” mencantumkan Pemilik dan Mandor. Catat perbedaan tersebut ketika mengaudit seluruh dokumen.

## Aturan wajib untuk semua role

1. Baca SRS, SDD, ERD, dan dokumentasi project yang tersedia sebelum mengubah kode.
2. Ikuti teknologi, framework, bahasa, struktur folder, konfigurasi, pola kode, dan komponen yang benar-benar sudah digunakan project. Jangan mengasumsikan atau mengganti teknologi.
3. SRS adalah acuan kebutuhan fungsional dan aktor. SDD/ERD menjadi acuan rancangan dan struktur data. Jika ada perbedaan, laporkan; jangan menebak atau diam-diam menyelaraskan.
4. Jangan menambah fitur, menu, alur bisnis, field, tabel, indikator, perhitungan, integrasi, atau aturan yang tidak didukung dokumen atau persetujuan pemilik project.
5. Bedakan hak melihat, menambah, mengubah, menghapus, memvalidasi, dan menghasilkan data. Jangan menyimpulkan semua hak CRUD hanya dari kata “mengelola”; periksa uraian FR, Use Case, SDD, dan ERD.
6. Jangan memberikan fungsi role lain kepada role yang sedang dikembangkan.
7. Periksa implementasi yang sudah ada dan gunakan kembali komponen yang sesuai. Jangan membuat ulang project atau menghapus kode yang berfungsi tanpa alasan dan persetujuan.
8. Jangan mengubah database, migration, nama field, atau relasi tanpa mencocokkan ERD dan implementasi.
9. Validasi dan otorisasi harus diterapkan pada sisi server sesuai pola project; menyembunyikan menu saja tidak cukup.
10. Jangan memakai data dummy seolah-olah data produksi. Jangan menyatakan fitur selesai sebelum diuji.
11. Jika aktor, aturan, atau alur pada dokumen ambigu/bertentangan, hentikan implementasi bagian terkait, tunjukkan kutipan/bagian dokumen dan minta keputusan.

## Tahapan kerja wajib

### Tahap 1 — Audit (tanpa mengubah kode)
- Temukan dan baca SRS, SDD, ERD, serta dokumentasi project.
- Periksa struktur dan implementasi yang telah tersedia.
- Buat matriks setiap FR role ini: nomor FR, judul resmi, aktor menurut SRS, Use Case terkait, halaman/proses yang sudah ada, entitas terkait, dan status implementasi.
- Catat ketidaksesuaian SRS dengan SDD, ERD, dan kode.
- Sampaikan hasil audit dan tunggu persetujuan. Jangan mengubah kode pada tahap ini.

### Tahap 2 — Rencana
- Susun urutan implementasi berdasarkan dependensi.
- Petakan FR ke halaman, alur, route, komponen, model, dan tabel yang benar-benar diperlukan berdasarkan project.
- Daftar file yang akan diubah dan alasannya.
- Minta keputusan atas hal yang belum jelas.

### Tahap 3 — Implementasi
- Implementasikan bertahap per kelompok fitur yang disetujui.
- Pertahankan konvensi project.
- Hindari perubahan modul role lain kecuali integrasi memang diperlukan dan disetujui.

### Tahap 4 — Pengujian
- Uji akses yang diizinkan dan penolakan akses role lain.
- Uji validasi input, penyimpanan, pembacaan, relasi, kondisi kosong/tidak ditemukan, dan konsistensi data.
- Uji tampilan sesuai dukungan project.
- Laporkan hasil uji; jangan mengklaim lulus jika belum dijalankan.

### Tahap 5 — Laporan
Laporkan FR yang dipenuhi, Use Case terkait, file yang berubah, perubahan database, alur fitur, hasil pengujian, pekerjaan tersisa, dan keputusan yang masih diperlukan.


## Perintah awal
Mulai dari Tahap 1 — Audit. Tampilkan matriks FR Pemilik berdasarkan SRS dan daftar ketidaksesuaian di atas. Jangan mengubah kode sebelum hasil audit disetujui.
