===================================================
PANDUAN DEPLOY APLIKASI KE DOKPLOY (BUTTBET MYSQL)
===================================================

Repositori ini sudah dikonfigurasi untuk siap di-deploy ke Dokploy menggunakan Docker. 
Ikuti langkah-langkah di bawah ini untuk melakukan instalasi dari awal:

---------------------------------------------------
TAHAP 1: MEMBUAT DATABASE MYSQL DI DOKPLOY
---------------------------------------------------
1. Buka dashboard Dokploy, masuk ke menu "Databases" lalu klik "Create Database".
2. Pilih tipe "MySQL" (atau "MariaDB" jika Anda ingin yang lebih ringan).
3. Isi kolom yang diminta (Name, Database Name, User, Password, Root Password).
4. Klik "Deploy" dan tunggu hingga statusnya berubah menjadi "Running".
5. Catat nama "Internal Host" dari database ini (misalnya: buttbet-buttbetdb-bammkh).

---------------------------------------------------
TAHAP 2: IMPORT DATABASE MENGGUNAKAN PHPMYADMIN
---------------------------------------------------
1. Buka menu "Applications" di Dokploy, klik "Create Application".
2. Beri nama aplikasi (misal: phpmyadmin-app).
3. Pada "Provider/Source", pilih "Docker Image" (logo paus biru).
4. Pada kolom "Image Name", ketik: phpmyadmin/phpmyadmin
5. Masuk ke tab "Environment", tambahkan 1 variabel:
   - PMA_HOST = (Isi dengan Internal Host dari Tahap 1, misal: buttbet-buttbetdb-bammkh)
6. Masuk ke tab "Domains" atau "Ports" untuk membuka akses ke phpMyAdmin.
7. Klik "Deploy" dan tunggu hingga "Running".
8. Buka phpMyAdmin di browser, login menggunakan User & Password database dari Tahap 1.
9. Pilih database Anda di menu sebelah kiri.
10. Klik tab "Import", pilih file `database.sql` yang ada di repositori ini, lalu klik "Go".

---------------------------------------------------
TAHAP 3: DEPLOY APLIKASI WEB UTAMA
---------------------------------------------------
1. Buka menu "Applications" di Dokploy, klik "Create Application".
2. Beri nama aplikasi web (misal: buttbet-web).
3. Pada "Provider/Source", pilih "GitHub".
4. Hubungkan dan pilih repositori aplikasi ini.
5. Pada "Build Type", pilih "Docker" (Dokploy akan membaca file Dockerfile secara otomatis).
6. Masuk ke tab "Environment", tambahkan 4 variabel berikut agar web terhubung ke database:
   - DB_HOST = (Internal Host dari Tahap 1)
   - DB_NAME = (Nama Database dari Tahap 1)
   - DB_USER = (User Database dari Tahap 1)
   - DB_PASS = (Password Database dari Tahap 1)
7. Masuk ke tab "Domains" atau "Ports" dan berikan akses domain/port publik.
8. Klik "Deploy" dan tunggu hingga "Running".

===================================================
Aplikasi Anda kini sudah siap dan beroperasi!
===================================================
