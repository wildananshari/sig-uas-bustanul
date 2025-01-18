<?php
include('koneksi.php');

$output = '';
if (isset($_POST['queryString'])) {
	$queryString = $_POST['queryString'];
	$query = "SELECT * FROM kordinat_gis WHERE nama_tempat LIKE '%$queryString%'";
	$result = mysqli_query($db, $query);

	if ($result) {
		while ($row = mysqli_fetch_assoc($result)) {
			$latitude = $row['x'];
			$longitude = $row['y'];
			$locationName = $row['nama_tempat'];

			// Tampilkan nama_tempat sebagai opsi autosuggest
			$output .= "<li class='content list-group-item list-group-item-action d-flex'data-locationname='$locationName' data-latitude='$latitude' data-longitude='$longitude'>$locationName</li>";
		}
		echo $output;
	} else {
		echo 'Query execution error';
	}
}
