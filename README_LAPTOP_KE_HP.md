# PERPUSTAKAAN DIGITAL — SMA NEGERI 1 KAMAL

## Tujuan
Paket ini disiapkan agar aplikasi PHP + MySQL dapat dijalankan di laptop melalui XAMPP dan kemudian dibuka dari HP.

## A. Jalankan di laptop
1. Install XAMPP.
2. Salin folder `SMA_NEGERI_1_KAMAL_Perpus_Digital` ke `C:\xampp\htdocs\`.
3. Buka XAMPP Control Panel.
4. Start **Apache** dan **MySQL**.
5. Buka `http://localhost/phpmyadmin`.
6. Import `database/database.sql`. File SQL otomatis membuat database `perpustakaan_digital_kelompok3`.
7. Buka `http://localhost/SMA_NEGERI_1_KAMAL_Perpus_Digital/`.

## B. Jika ingin dibuka dari HP melalui Wi-Fi yang sama
1. Pastikan laptop dan HP terhubung ke Wi-Fi yang sama.
2. Di Windows buka Command Prompt dan jalankan `ipconfig`.
3. Cari **IPv4 Address**, misalnya `192.168.1.10`.
4. Dari HP buka `http://192.168.1.10/SMA_NEGERI_1_KAMAL_Perpus_Digital/`.
5. Jika tidak bisa, izinkan Apache/httpd pada Windows Firewall.

## C. Model seperti screenshot TikTok (Forwarded Address)
Jika VS Code kamu menampilkan panel **PORTS**, kamu dapat meneruskan port server Apache.
- Jalankan Apache pada port 80 (atau port lain yang dipakai Apache).
- Buka **PORTS** → **Add Port / Forward a Port**.
- Masukkan port Apache, biasanya `80`.
- Jika VS Code menyediakan **Forwarded Address**, gunakan alamat HTTPS yang diberikan.
- Buka alamat tersebut dari HP.

> Catatan: menu PORTS/Forwarded Address bergantung pada mode remote/tunnel VS Code. Jika menu itu tidak tersedia untuk server lokal, gunakan metode Wi-Fi pada bagian B atau VS Code Tunnel.

## D. Login demo
Admin: `admin` / `admin123`
Pustakawan: `pustakawan` / `pustakawan123`
Siswa: NISN `141414` / `anitas123`

## E. Jika database gagal tersambung
File `koneksi.php` menggunakan:
- host: `localhost`
- user: `root`
- password: kosong
- database: `perpustakaan_digital_kelompok3`

Jika XAMPP kamu menggunakan password MySQL, ubah bagian tersebut di `koneksi.php`.
