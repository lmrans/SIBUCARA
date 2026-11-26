# Panduan AI Agent — Role Pekerja SIBUCARA PRO

## Tujuan
Kembangkan hanya fungsi yang menurut tabel **Daftar Functional Requirement Bab II SRS** memiliki aktor Pekerja. Pekerja berfokus pada pengajuan bergabung ke pertanian, melihat tugas dan jadwal, memperbarui status pekerjaan, mengirim laporan serta bukti pekerjaan, dan mengakses informasi yang secara eksplisit tersedia untuk Pekerja.

## Daftar FR Pekerja menurut kolom Stakeholder SRS

| FR | Nama/kebutuhan resmi dalam SRS | Aktor pada tabel FR |
|---|---|---|
| FR-01 | Registrasi akun pengguna | Pemilik, Mandor, Pekerja |
| FR-02 | Login pengguna | Pemilik, Mandor, Pekerja |
| FR-03 | Pembatasan akses berdasarkan peran | Pemilik, Mandor, Pekerja |
| FR-04 | Dashboard sesuai peran pengguna | Pemilik, Mandor, Pekerja |
| FR-09 | Mendaftar sebagai Pekerja pada pertanian melalui Mandor | Pekerja |
| FR-15 | Menampilkan detail tugas kepada Pekerja | Pekerja |
| FR-16 | Menampilkan jadwal pekerjaan kepada Pekerja | Pekerja |
| FR-17 | Memperbarui status pekerjaan | Pekerja |
| FR-18 | Mengirimkan laporan hasil pekerjaan | Pekerja |
| FR-19 | Mengirimkan bukti pekerjaan | Pekerja |
| FR-22 | Menampilkan informasi cuaca | Pemilik, Mandor, Pekerja |
| FR-30 | Menampilkan informasi budidaya, tugas, stok, panen, penjualan, dan keuangan secara terintegrasi | Pemilik, Mandor, Pekerja |
| FR-31 | Mengirimkan notifikasi kepada pengguna terkait tugas dan perubahan status pekerjaan | Pemilik, Mandor, Pekerja |

**Jangan masukkan sebagai fungsi Pekerja berdasarkan tabel aktor FR:** FR-05–08 (pengelolaan pengguna/pertanian dan pengajuan/validasi Mandor), FR-10 (verifikasi Pekerja oleh Mandor), FR-11–14 (pengelolaan tanaman, budidaya, tugas, dan jadwal oleh Pemilik/Mandor), FR-20–21 (melihat dan memvalidasi laporan oleh Pemilik/Mandor), FR-23–29 (pengelolaan stok, panen, penjualan, keuangan, dan laporan oleh role lain), FR-32 (monitoring perkembangan pekerjaan oleh Pemilik dan Mandor).

## Arahan implementasi berdasarkan isi SRS

- **FR-01–04:** Pekerja dapat melakukan registrasi dan login, lalu memperoleh akses berdasarkan role Pekerja serta dashboard sesuai perannya.
- **FR-09:** Pekerja mengajukan permohonan bergabung ke pertanian. SRS menyatakan proses pendaftaran Pekerja dilakukan melalui Mandor; jangan mengubah alur persetujuan atau menambahkan langkah tanpa dasar.
- **FR-15:** Pekerja dapat melihat detail tugas yang diberikan, termasuk informasi tugas yang dijelaskan SRS seperti kegiatan yang harus dilakukan, waktu pelaksanaan, dan keterangan pekerjaan.
- **FR-16:** Pekerja dapat melihat jadwal pekerjaan yang harus dilakukan.
- **FR-17:** Pekerja memperbarui status pekerjaan sesuai perkembangan atau penyelesaian tugas sehingga Pemilik dan Mandor dapat mengetahui perkembangannya.
- **FR-18:** Pekerja mengirimkan laporan hasil pekerjaan yang telah dilaksanakan.
- **FR-19:** Pekerja mengirimkan bukti pekerjaan sebagai pendukung laporan apabila diperlukan.
- **FR-22:** tabel FR mencantumkan Pekerja sebagai penerima informasi cuaca. Namun daftar Use Case “Melihat Informasi Cuaca” mencantumkan Pemilik dan Mandor saja. **Catat ketidaksesuaian ini dan minta keputusan sebelum membangun halaman cuaca untuk Pekerja.**
- **FR-30:** tabel FR mencantumkan Pekerja sebagai aktor untuk informasi budidaya, tugas, stok, panen, penjualan, dan keuangan secara terintegrasi. Akan tetapi penjelasan FR menyebut Pemilik dan Mandor sebagai pihak yang memperoleh informasi pengelolaan pertanian secara menyeluruh. Jangan menafsirkan ini sebagai hak Pekerja melihat seluruh data keuangan/penjualan. Minta klarifikasi mengenai informasi apa yang boleh ditampilkan kepada Pekerja.
- **FR-31:** notifikasi kepada Pekerja terkait tugas dan perubahan status pekerjaan yang relevan. Jangan membuat notifikasi yang tidak memiliki pemicu pada proses sistem.

## Batasan khusus
- Pekerja bukan pihak yang membuat atau membagikan tugas.
- Pekerja bukan pihak yang mengatur jadwal pekerjaan.
- Pekerja tidak memverifikasi pendaftaran Pekerja lain.
- Pekerja tidak memvalidasi hasil pekerjaannya sendiri.
- Pekerja tidak mengelola data stok, hasil panen, penjualan, atau keuangan berdasarkan tabel aktor FR.
- FR-22 dan FR-30 memiliki ketidaksesuaian antara tabel aktor FR dan uraian/Use Case. Jangan menambahkan akses sebelum keputusan dibuat.
- FR-32 pada tabel FR diberikan kepada Pemilik dan Mandor, bukan Pekerja. Pekerja memperbarui status melalui FR-17; jangan menyamakan tindakan itu dengan fitur monitoring FR-32.

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
Mulai dari Tahap 1 — Audit. Tampilkan matriks FR Pekerja berdasarkan SRS dan daftar ketidaksesuaian di atas. Jangan mengubah kode sebelum hasil audit disetujui.
