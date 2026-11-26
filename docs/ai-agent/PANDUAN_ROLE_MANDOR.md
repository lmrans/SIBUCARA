# Panduan AI Agent — Role Mandor SIBUCARA PRO

## Tujuan
Kembangkan hanya fungsi yang menurut tabel **Daftar Functional Requirement Bab II SRS** memiliki aktor Mandor. Mandor berfokus pada pengawasan dan operasional kegiatan pertanian, pekerjaan Pekerja, serta fungsi lain yang secara eksplisit diberikan kepadanya dalam SRS.

## Daftar FR Mandor menurut kolom Stakeholder SRS

| FR | Nama/kebutuhan resmi dalam SRS | Aktor pada tabel FR |
|---|---|---|
| FR-01 | Registrasi akun pengguna | Pemilik, Mandor, Pekerja |
| FR-02 | Login pengguna | Pemilik, Mandor, Pekerja |
| FR-03 | Pembatasan akses berdasarkan peran | Pemilik, Mandor, Pekerja |
| FR-04 | Dashboard sesuai peran pengguna | Pemilik, Mandor, Pekerja |
| FR-07 | Pengajuan permohonan bergabung ke pertanian | Mandor |
| FR-10 | Memverifikasi pendaftaran Pekerja | Mandor |
| FR-11 | Mengelola data tanaman cabai rawit | Pemilik, Mandor |
| FR-12 | Mengelola kegiatan budidaya tanaman cabai rawit | Pemilik, Mandor |
| FR-13 | Membuat dan memberikan tugas kepada Pekerja | Mandor |
| FR-14 | Mengatur jadwal kegiatan dan pekerjaan | Pemilik, Mandor |
| FR-20 | Menampilkan laporan pekerjaan | Pemilik, Mandor |
| FR-21 | Memvalidasi hasil pekerjaan | Mandor |
| FR-22 | Menampilkan informasi cuaca | Pemilik, Mandor, Pekerja |
| FR-23 | Mengelola data stok pertanian | Mandor |
| FR-24 | Mengelola data hasil panen | Pemilik, Mandor |
| FR-25 | Mengelola data penjualan hasil panen | Pemilik, Mandor |
| FR-26 | Mengelola data pemasukan dan pengeluaran | Pemilik, Mandor |
| FR-27 | Menampilkan informasi keuangan | Pemilik, Mandor |
| FR-28 | Menampilkan laporan pengelolaan pertanian | Pemilik, Mandor |
| FR-29 | Menghasilkan laporan pengelolaan pertanian secara terstruktur | Pemilik, Mandor |
| FR-30 | Menampilkan informasi budidaya, tugas, stok, panen, penjualan, dan keuangan secara terintegrasi | Pemilik, Mandor, Pekerja |
| FR-31 | Mengirimkan notifikasi kepada pengguna terkait tugas dan perubahan status pekerjaan | Pemilik, Mandor, Pekerja |
| FR-32 | Menampilkan perkembangan pekerjaan | Pemilik, Mandor |

**Jangan masukkan sebagai fungsi Mandor berdasarkan tabel aktor FR:** FR-05 (pengelolaan data pengguna oleh Pemilik), FR-06 (data pertanian oleh Pemilik), FR-08 (validasi pengajuan Mandor oleh Pemilik), FR-09 (pengajuan Pekerja), FR-15–19 (fungsi Pekerja).

## Arahan implementasi berdasarkan isi SRS

- **FR-01–04:** registrasi, login, pembatasan akses berdasarkan peran, dan dashboard sesuai peran.
- **FR-07:** Mandor mengajukan permohonan bergabung ke pertanian. Pengajuan kemudian diproses oleh Pemilik melalui FR-08.
- **FR-10:** Mandor memeriksa pengajuan Pekerja dan menentukan apakah Pekerja dapat bergabung serta menjalankan pekerjaan di pertanian.
- **FR-11–12:** Mandor bersama Pemilik mengelola data tanaman cabai rawit dan kegiatan budidaya.
- **FR-13:** Mandor membuat tugas berdasarkan kegiatan pertanian dan memberikannya kepada Pekerja yang bertanggung jawab.
- **FR-14:** Mandor bersama Pemilik mengatur jadwal kegiatan dan pekerjaan.
- **FR-20:** Mandor melihat laporan pekerjaan yang dikirim Pekerja.
- **FR-21:** Mandor memeriksa kesesuaian hasil pekerjaan dengan tugas yang diberikan dan melakukan validasi. Jangan memindahkan kewenangan validasi ini kepada Pemilik atau Pekerja.
- **FR-22:** tabel FR mencantumkan Pemilik, Mandor, dan Pekerja sebagai aktor penerima informasi cuaca. Pastikan tampilan Mandor sesuai kebutuhan tersebut.
- **FR-23:** Mandor mengelola data stok pertanian menurut tabel stakeholder FR.
- **FR-24:** Mandor bersama Pemilik mengelola data hasil panen.
- **FR-25–26:** tabel stakeholder FR mencantumkan Pemilik dan Mandor untuk penjualan serta pemasukan/pengeluaran, tetapi uraian penjelasan FR-25 dan FR-26 menyebut Pemilik. **Jangan langsung memberikan akses kelola transaksi kepada Mandor sebelum ketidaksesuaian ini dikonfirmasi.**
- **FR-27:** informasi keuangan ditampilkan kepada Pemilik dan Mandor menurut tabel FR. Terapkan batasan data yang telah ditentukan dokumen; jangan menambah akses atau rumus.
- **FR-28–29:** Mandor dapat melihat laporan pengelolaan pertanian dan menghasilkan laporan terstruktur menurut tabel FR.
- **FR-30:** informasi budidaya, tugas, stok, panen, penjualan, dan keuangan ditampilkan terintegrasi sesuai hak akses Mandor.
- **FR-31:** notifikasi terkait tugas dan perubahan status pekerjaan dikirim kepada pengguna yang relevan.
- **FR-32:** Mandor memantau perkembangan pekerjaan yang dilakukan Pekerja.

## Batasan khusus
- Mandor mengajukan permohonan bergabung; Pemilik memvalidasi pengajuan Mandor.
- Mandor memverifikasi pengajuan Pekerja.
- Mandor membuat dan memberikan tugas kepada Pekerja.
- Mandor memvalidasi hasil pekerjaan.
- FR-23 menyebut Mandor sebagai aktor pada tabel FR, walaupun bagian kebutuhan pengguna dan Use Case menyebut Pemilik dan Mandor. Catat perbedaan ini dan jangan menyelaraskan diam-diam.
- FR-25 dan FR-26: tabel aktor dan uraian FR tidak sepenuhnya konsisten mengenai kewenangan Mandor. Minta keputusan sebelum mengaktifkan operasi transaksi.
- FR-22: tabel FR mencantumkan Pekerja sebagai aktor, tetapi daftar Use Case cuaca hanya mencantumkan Pemilik dan Mandor. Catat perbedaan ini dalam audit.

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
Mulai dari Tahap 1 — Audit. Tampilkan matriks FR Mandor berdasarkan SRS dan daftar ketidaksesuaian di atas. Jangan mengubah kode sebelum hasil audit disetujui.
