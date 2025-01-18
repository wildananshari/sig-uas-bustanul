<?php
session_start();
include('koneksi.php');

if (isset($_POST['id'])) {
    $locationId = mysqli_real_escape_string($db, $_POST['id']);

    // Fetch location details from the database
    $query = "SELECT * FROM kordinat_gis WHERE nomor = '$locationId'";
    $result = mysqli_query($db, $query);

    if ($result) {
        $locationDetails = mysqli_fetch_assoc($result);

        // Return location details as JSON response
        echo json_encode([
            'id' => $locationDetails['nomor'],
            'name' => $locationDetails['nama_tempat'],
            'status' => $locationDetails['status']
            // Add more fields as needed
        ]);
    } else {
        // Handle query error
        echo json_encode(['error' => 'Error fetching location details']);
    }
} else {
    // Handle invalid or missing location ID
    echo json_encode(['error' => 'Invalid location ID']);
}
