# Sistem Penyewaan Kost

Aplikasi web untuk mengelola penyewaan kamar kost dengan fitur admin dan user.

## Fitur

### Admin
- Dashboard dengan statistik
- Kelola kamar (CRUD)
- Kelola penyewaan
- Kelola user

### User
- Registrasi dan login
- Lihat daftar kamar
- Ajukan penyewaan
- Lihat status penyewaan
- Kelola profil

## Instalasi

1. Clone atau download project
2. Import database MySQL
3. Konfigurasi database di \`includes/db.php\`
4. Akses melalui web browser

## Default Login

**Admin:**
- Username: admin
- Password: admin123

## Struktur Database

- \`users\` - Data pengguna
- \`admins\` - Data admin
- \`kamar\` - Data kamar kost
- \`sewa\` - Data penyewaan

## Teknologi

- PHP 7.4+
- MySQL
- Bootstrap 5
- HTML5/CSS3

## Folder Structure

\`\`\`
/penyewaan-kost/
├── index.php
├── login_admin.php
├── login_user.php
├── logout.php
├── register_user.php
├── /admin/
├── /user/
├── /includes/
├── /uploads/
├── /assets/
└── README.md
\`\`\`
