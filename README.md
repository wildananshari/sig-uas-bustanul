# Sigap

Dokumentasi pemasangan SIGAP pada 000.webhost

## Live Website
  https://ridwantauhid32210083.000webhostapp.com/index.php

## Cara Setup

1. Git clone https://github.com/tauhidridwan9/sigapp
2. Masuk ke akun https://www.000webhost.com/cpanel-login?from=panel
3. Upload code ke 000.webhost melalui file manager
4. Buat databases di 000.webhost
5. import sig(1).sql ke database teman-teman
6. Setting koneksi.php sesuai dengan username database dan nama database. contoh $db = mysqli_connect('localhost', 'username_anda', 'password_anda') or die('Unable to connect. Check your      connection parameters.'); mysqli_select_db($db, 'nama_database') or die(mysqli_error($db));?>
7. Jika terdapat error pada session tambahkan "php_flag output_buffering on" pada .htaccess
