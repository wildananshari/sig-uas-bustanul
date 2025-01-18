
<?php
session_start();
include('koneksi.php');

$id = $_POST['nomor'];

$hapus = mysqli_query($db, "DELETE FROM kordinat_gis WHERE nomor='$id'");

if ($hapus) {
    echo "Data berhasil dihapus";
} else {
    echo "Gagal menghapus data: " . mysqli_error($db);
}

mysqli_close($db);
