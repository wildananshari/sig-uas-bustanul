<?php
include('koneksi.php');

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // Pemrosesan ketika menerima request GET
    $koordinat_x = $_GET['koordinat_x'];
    $koordinat_y = $_GET['koordinat_y'];
    $nama_tempat = $_GET['nama_tempat'];

    $query = "INSERT INTO kordinat_gis (x, y, nama_tempat) VALUES ($koordinat_x, $koordinat_y, '$nama_tempat')";
    mysqli_query($db, $query) or die(mysqli_error($db));

    // Memberikan respons JSON
    echo json_encode(array("status" => "success", "message" => "Data berhasil disimpan"));
} else {
    // Pemrosesan ketika menerima request POST
    if (isset($_POST['koordinat_x']) && isset($_POST['koordinat_y']) && isset($_POST['nama_tempat'])) {
        // Mengambil nilai koordinat_x, koordinat_y, dan nama_tempat
        $koordinat_x = $_POST['koordinat_x'];
        $koordinat_y = $_POST['koordinat_y'];
        $nama_tempat = $_POST['nama_tempat'];

        // Memastikan bahwa nilai koordinat_x, koordinat_y, dan nama_tempat tidak kosong
        if (!empty($koordinat_x) && !empty($koordinat_y) && !empty($nama_tempat)) {
            // Proses penyimpanan data ke database
            // ...
            $query = "INSERT INTO kordinat_gis (x, y, nama_tempat) VALUES ($koordinat_x, $koordinat_y, '$nama_tempat')";
            mysqli_query($db, $query) or die(mysqli_error($db));
            // Setelah berhasil disimpan, Anda dapat memberikan respons sukses
            echo json_encode(array("status" => "success", "message" => "Data berhasil disimpan"));
        } else {
            // Jika koordinat_x, koordinat_y, atau nama_tempat kosong, memberikan respons error
            echo json_encode(array("status" => "error", "message" => "Koordinat dan nama tempat harus diisi"));
        }
    } else {
        // Jika koordinat_x, koordinat_y, atau nama_tempat tidak disediakan, memberikan respons error
        echo json_encode(array("status" => "error", "message" => "Koordinat dan nama tempat harus diisi"));
    }
}
