#!/bin/sh

# Pindah ke direktori proyek.
cd ~/dashboard-rfid

# Tarik perubahan terbaru dari repositori git
git pull origin development

# Install/memperbarui dependensi composer
composer install

# Jalankan migrasi database
php artisan migrate

# Optimalkan
php artisan optimize:clear

# Cek apakah symlink sudah ada
if [ ! -L ~/public_html/dashboard.senotekno.com/storage ]; then
  # Jika belum ada, buat symlink
  ln -s $(pwd)/storage/app/public ~/public_html/dashboard.senotekno.com/storage
  echo "Symlink untuk storage Laravel telah dibuat."
else
  echo "Symlink untuk storage Laravel sudah ada."
fi

# Dapatkan daftar file yang diupdate atau ditambahkan di direktori public
updated_files=$(git diff --name-only --diff-filter=ACMRTUXB HEAD@{1} public)

# Loop melalui setiap file yang diupdate dan salin ke tujuan
for file in $updated_files; do
  cp "$file" ~/public_html/dashboard.senotekno.com
  echo "File $file telah diperbarui atau ditambahkan."
done
