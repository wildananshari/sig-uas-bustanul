<?php session_start() ?>
<script type="text/javascript">
	$(document).ready(function() {
		var intervalId = setInterval(loadDataLokasiTersimpan, 30000);



		function showAlert(message, type) {
			var alertContainer = $("#alert-container");
			var alertClass = 'alert-' + type;

			var alertHTML = '<div class="alert ' + alertClass + ' alert-dismissible fade show" role="alert">' +
				message +
				'<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>' +
				'</div>';

			alertContainer.html(alertHTML);
		}


		$(".delbutton").click(function(event) {
			var element = $(this);
			var del_id = element.attr("id");
			var info = 'nomor=' + del_id;

			if (confirm("Anda yakin akan menghapus?")) {
				$.ajax({
					type: "POST",
					url: "hapus_lokasi.php",
					data: info,
					success: function() {
						element.parents(".content").animate({
							opacity: "hide"
						}, "slow");
						// Hentikan interval sebelum memulai yang baru
						clearInterval(intervalId);
						// Mulai interval kembali setelah penghapusan
						intervalId = setInterval(loadDataLokasiTersimpan, 30000);
						showAlert('Berhasil menghapus data', 'success');
					},
					error: function() {
						showAlert('Gagal menghapus data!', 'danger');
					}
				});
			}

			return false;
		});

		$(".editbutton").click(function() {
			var element = $(this);
			var edit_id = element.data("id");

			// Fetch the location details via AJAX and populate the modal fields
			$.ajax({
				type: "POST",
				url: "get_location_details.php", // Adjust the URL
				data: {
					id: edit_id
				},
				dataType: "json",
				success: function(response) {
					// Populate the modal fields
					$("#editLocationId").val(response.id);
					$("#editLocationName").val(response.name);
					$("#editLocationStatus").val(response.status);
					showAlert('Berhasil mengubah data', 'success');
				},
				error: function(error) {
					console.error('Error fetching location details:', error);
					showAlert('Gagal edit data', 'danger');
				}

			});
		});
	});
</script>

<?php
include('koneksi.php');

// Tentukan klausa WHERE berdasarkan status sesi login
$whereClause = (isset($_SESSION['login']) && $_SESSION['login'] === true) ? '' : 'WHERE status = 1';

// Tampilkan semua lokasi tersimpan
$query = "SELECT * FROM kordinat_gis $whereClause ORDER BY nomor DESC LIMIT 10";
$result = mysqli_query($db, $query) or die(mysqli_error($db));

while ($koor = mysqli_fetch_assoc($result)) {
	$statusClass = ($koor['status'] == 1) ? 'bg-danger' : '';
	$statusText = ($koor['status'] == 1) ? 'Aktif' : 'Tidak Aktif';
?>
	<ul>
		<li class="content list-group-item list-group-item-action d-flex <?php echo $statusClass; ?>">
			<div class="container">
				<a class="text-body-secondary" style="text-decoration: none;" href="javascript:carikordinat(new google.maps.LatLng(<?php echo $koor['x']; ?>,<?php echo $koor['y']; ?>))"><?php echo $koor['nama_tempat']; ?></a>
			</div>
			<div class="container d-flex justify-content-end p-8">
				<span style="margin-right: 2em;">Status: <?php echo $statusText; ?></span>
				<?php
				// Cek apakah sesi login aktif
				if (isset($_SESSION['login']) && $_SESSION['login'] === true) {
				?>
					<a href="#" class="delbutton" id="<?php echo $koor['nomor']; ?>"><img width="20px" style="margin-right: 2em;" src="./trash-solid.svg" alt="trash" /></a>
					<a href="#" class="editbutton" data-bs-toggle="modal" data-bs-target="#editModal" data-id="<?php echo $koor['nomor']; ?>"><img width="20px" style="margin-right: 2em;" src="./edit-solid.svg" alt="edit" /></a>
					<!-- Example single danger button -->
				<?php
				}
				?>
			</div>
		</li>
	</ul>
<?php
}
?>
<div id="alert-container" style="position: fixed; top: 10px; right: 10px; z-index: 1000;"></div>