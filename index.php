 <?php session_start(); ?>
 <!DOCTYPE html>
 <html xmlns="http://www.w3.org/1999/xhtml">

 <head>
     <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
     <title>Contoh Aplikasi Peta GIS Sederhana Dengan Google Map API</title>
     <script type="text/javascript" src="https://maps.google.com/maps/api/js?key=AIzaSyDTXGEsxXrF_C3YwcY-5N6vEoW1sEI31Sg&libraries=places"></script>

     <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

     <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
     <!-- jQuery -->
     <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

     <!-- Popper.js -->
     <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/2.11.6/umd/popper.min.js"></script>

     <!-- Bootstrap JS -->
     <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js" integrity="sha384-BBtl+eGJRgqQAUMxJ7pMwbEyER4l1g+O15P+16Ep7Q9Q+zqX6gSbd85u4mG4QzX+" crossorigin="anonymous"></script>

     <script type="text/javascript" src="https://maps.google.com/maps/api/js?key=AIzaSyDTXGEsxXrF_C3YwcY-5N6vEoW1sEI31Sg&libraries=places"></script>

     <link href="./main.css" rel="stylesheet" />
     <script type="text/javascript">
         var peta;
         var koorAwal = new google.maps.LatLng(-1.965844, 114.712050);

         function peta_awal() {
             carikordinat();
             var settingpeta = {
                 zoom: 15,
                 center: koorAwal,
                 mapTypeId: google.maps.MapTypeId.HYBRID
             };
             peta = new google.maps.Map(document.getElementById("kanvaspeta"), settingpeta);
             google.maps.event.addListener(peta, 'click', function(event) {
                 tandai(event.latLng);
             });
         }

         function geocodeCountry(countryName) {
             var geocoder = new google.maps.Geocoder();

             geocoder.geocode({
                 address: countryName
             }, function(results, status) {
                 if (status == google.maps.GeocoderStatus.OK && results.length > 0) {
                     var location = results[0].geometry.location;

                     // Set koorAwal to the new coordinates
                     koorAwal = new google.maps.LatLng(location.lat(), location.lng());

                     // Setel peta ke lokasi geokode
                     peta.panTo(koorAwal);

                     // Tandai lokasi pada peta
                     tandai(koorAwal);

                     // Isi input koordinat X dan Y
                     $("#koorX").val(location.lat());
                     $("#koorY").val(location.lng());
                 } else {
                     console.error('Geokoding gagal: ' + status);
                 }
             });
         }
         $(document).ready(function() {
             $("#simpanpeta").click(function() {
                 var koordinat_x = $("#koorX").val();
                 var koordinat_y = $("#koorY").val();
                 var nama_tempat = $("#namaTempat").val();
                 $.ajax({
                     url: "simpan_lokasi_baru.php",
                     method: "POST",
                     data: {
                         koordinat_x: koordinat_x,
                         koordinat_y: koordinat_y,
                         nama_tempat: nama_tempat
                     },
                     dataType: "json",
                     success: function(response) {
                         // Menangani respons dari server
                         if (response.status === "success") {
                             // Jika respons sukses, tampilkan pesan sukses menggunakan SweetAlert
                             showSweetAlert("success", "Sukses", response.message);
                             // Bersihkan nilai input setelah penyimpanan berhasil
                             $("#namaTempat").val(null);
                         } else {
                             // Jika respons error, tampilkan pesan error menggunakan SweetAlert
                             showSweetAlert("error", "Gagal", response.message);
                         }
                     },
                     error: function() {
                         // Menangani kesalahan jika terjadi
                         showSweetAlert("error", "Gagal", "Terjadi kesalahan. Silakan coba lagi.");
                     }
                 });
             });

             $('#suggestionsList').on('click', 'li', function() {
                 var description = $(this).text();
                 var location = $(this).data('location');
                 fill(description, location);
             });
         });

         function showSweetAlert(type, judul, message) {
             // Menampilkan SweetAlert
             Swal.fire({
                 icon: type,
                 title: judul,
                 text: message,
                 showConfirmButton: false,
                 timer: 2000
             });
         }


         function initMap() {
             loadDataLokasiTersimpan();
             var settingpeta = {
                 zoom: 15,
                 center: koorAwal,
                 mapTypeId: google.maps.MapTypeId.HYBRID
             };
             peta = new google.maps.Map(document.getElementById("kanvaspeta"), settingpeta);
             google.maps.event.addListener(peta, 'click', function(event) {
                 tandai(event.latLng);
             });

             var input = document.getElementById('country');
             var autocomplete = new google.maps.places.Autocomplete(input);

             autocomplete.addListener('place_changed', function() {
                 var place = autocomplete.getPlace();
                 if (place.geometry) {
                     // Setel peta ke lokasi yang dipilih
                     peta.setCenter(place.geometry.location);
                     peta.setZoom(15);

                     // Tandai lokasi pada peta
                     tandai(place.geometry.location);

                     // Isi input koordinat X dan Y
                     $("#koorX").val(place.geometry.location.lat());
                     $("#koorY").val(place.geometry.location.lng());
                 }
             });
         }


         function tandai(lokasi) {
             $("#koorX").val(lokasi.lat());
             $("#koorY").val(lokasi.lng());

             // Hapus marker yang ada jika sudah ada
             if (tanda) {
                 tanda.setMap(null);
             }

             // Tambahkan marker baru
             tanda = new google.maps.Marker({
                 position: lokasi,
                 map: peta
             });

             // Pan peta ke lokasi yang ditandai
             peta.panTo(lokasi);
         }

         function loadDataLokasiTersimpan() {
             $('#kordinattersimpan').load('tampilkan_lokasi_tersimpan.php');
         }
         setInterval(loadDataLokasiTersimpan, 3000);

         function carikordinat(lokasi) {
             var settingpeta = {
                 zoom: 15,
                 center: lokasi,
                 mapTypeId: google.maps.MapTypeId.HYBRID
             };
             peta = new google.maps.Map(document.getElementById("kanvaspeta"), settingpeta);
             tanda = new google.maps.Marker({
                 position: lokasi,
                 map: peta
             });
             google.maps.event.addListener(tanda, 'click', function() {
                 infowindow.open(peta, tanda);
             });
             google.maps.event.addListener(peta, 'click', function(event) {
                 tandai(event.latLng);
             });
         }

         function gantipeta() {
             loadDataLokasiTersimpan();
             var isi = document.getElementById('cmb').value;
             if (isi == '1') {
                 var settingpeta = {
                     zoom: 15,
                     center: koorAwal,
                     mapTypeId: google.maps.MapTypeId.HYBRID
                 };
             } else if (isi == '2') {
                 var settingpeta = {
                     zoom: 15,
                     center: koorAwal,
                     mapTypeId: google.maps.MapTypeId.TERRAIN
                 };
             } else if (isi == '3') {
                 var settingpeta = {
                     zoom: 15,
                     center: koorAwal,
                     mapTypeId: google.maps.MapTypeId.SATELLITE
                 };
             } else if (isi == '4') {
                 var settingpeta = {
                     zoom: 15,
                     center: koorAwal,
                     mapTypeId: google.maps.MapTypeId.HYBRID
                 };
             }
             peta = new google.maps.Map(document.getElementById("kanvaspeta"), settingpeta);
             google.maps.event.addListener(peta, 'click', function(event) {
                 tandai(event.latLng);
             });
         }

         function suggest(inputString) {
             if (inputString.length == 0) {
                 $('#suggestions').fadeOut();
             } else {
                 $('#country').addClass('load');
                 $.post("autosuggest.php", {
                     queryString: "" + inputString + ""
                 }, function(data) {
                     if (data.length > 0) {
                         $('#suggestions').fadeIn();
                         $('#suggestionsList').html(data);
                         $('#country').removeClass('load');

                         // Tambahkan informasi lokasi ke setiap elemen li
                         $('#suggestionsList li').each(function() {
                             var locationName = $(this).data('locationname');
                             var latitude = $(this).data('latitude');
                             var longitude = $(this).data('longitude');
                             $(this).data('location', {
                                 lat: latitude,
                                 lng: longitude
                             });
                             $(this).html(locationName); // Ganti isi elemen dengan nama_tempat
                         });
                     }
                 });
             }
         }


         function fill(thisValue, coordinates) {
             $('#country').val(thisValue);

             // Isi input koordinat X dan Y jika koordinat disertakan
             if (coordinates) {
                 $("#koorX").val(coordinates.lat);
                 $("#koorY").val(coordinates.lng);

                 // Tandai lokasi pada peta
                 tandai(new google.maps.LatLng(coordinates.lat, coordinates.lng));
             } else {
                 // Jika koordinat tidak disertakan, set koorAwal ke koordinat negara yang dipilih
                 geocodeCountry(thisValue);
             }

             setTimeout(function() {
                 $('#suggestions').fadeOut();
             }, 600);
         }


         $(document).ready(function() {

             $("#editLocationForm").submit(function(event) {
                 event.preventDefault(); // Mencegah pengiriman formulir standar

                 // Ambil nilai dari formulir
                 var locationId = $("#editLocationId").val();
                 var locationName = $("#editLocationName").val();
                 var locationStatus = $("#editLocationStatus").val();

                 // Kirim data ke server menggunakan AJAX
                 $.ajax({
                     type: "POST",
                     url: "update_location.php",
                     data: {
                         locationId: locationId,
                         locationName: locationName,
                         locationStatus: locationStatus
                     },
                     success: function(response) {
                         // Handle response jika diperlukan
                         console.log('Update successful:', response);

                         // Tutup modal setelah berhasil
                         $('#editModal').modal('hide');

                         // Refresh data lokasi
                         loadDataLokasiTersimpan();
                     },
                     error: function(error) {
                         console.error('Error updating location:', error);
                     }
                 });
             });

         })
     </script>

 </head>

 <body onLoad="peta_awal()">
     <div id="alerts" style="position: fixed; top: 10px; right: 10px; z-index: 1000;"></div>

     <!-- Modal for Editing Location -->
     <div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="editModalLabel" aria-hidden="true">
         <div class="modal-dialog" role="document">
             <div class="modal-content">
                 <div class="modal-header">
                     <h5 class="modal-title" id="editModalLabel">Edit Tempat</h5>
                     <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                         <span aria-hidden="true">&times;</span>
                     </button>
                 </div>
                 <div class="modal-body">
                     <!-- Form for Editing Location -->
                     <form id="editLocationForm">
                         <div class="form-group">
                             <label class='mb-2' for="editLocationName">Nama Tempat:</label>
                             <input type="text" class="form-control" id="editLocationName" name="editLocationName" required>
                         </div>
                         <div class="form-group mt-4">
                             <label class='mb-2' for="editLocationStatus">Status:</label>
                             <select class="form-control" id="editLocationStatus" name="editLocationStatus" required>
                                 <option value="0">Tidak Aktif</option>
                                 <option value="1">Aktif</option>
                             </select>
                         </div>
                         <input type="hidden" id="editLocationId" name="editLocationId">
                         <button type="submit" class="btn btn-primary mt-5">Simpan</button>
                     </form>
                 </div>
             </div>
         </div>
     </div>

     <?php
        // Tangkap parameter sukses
        $success = isset($_GET['success']) ? $_GET['success'] : null;
        // Periksa apakah parameter sukses ada dan bernilai 1
        if ($success === '1') {
            echo "<script>
        Swal.fire({
            icon: 'success',
            title: 'Sukses!',
            text: 'Logout Berhasil',
            showConfirmButton: false,
            timer: 2000
        });
    </script>";
        }
        ?>
     <?php
        if (isset($_SESSION['login']) && $_SESSION['login'] === true) {
            echo "<script>
                Swal.fire({
                    icon: 'success',
                    title: 'Sukses!',
                    text: 'Login Berhasil',
                    showConfirmButton: false,
                    timer: 2000
                });
            </script>";
        }
        ?>

     <nav class="navbar navbar-expand-lg bg-body-tertiary">
         <div class="container-fluid">
             <a class="navbar-brand" href="#">SIGAP</a>
             <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                 <span class="navbar-toggler-icon"></span>
             </button>
             <div class="collapse navbar-collapse" id="navbarSupportedContent">
                 <ul id="form_lokasi" class="navbar-nav me-auto mb-4 mb-lg-0">
                     <li class="nav-item">
                         <select class="form-select" id="cmb" onchange="gantipeta()">
                             <option value="1">Peta Roadmap</option>
                             <option value="2">Peta Terrain</option>
                             <option value="3">Peta Satelite</option>
                             <option value="4">Peta Hybrid</option>
                         </select>
                     </li>
                     <li class="nav-item">
                         <input class="form-control me-2" type="text" name="koordinatx" id="koorX" readonly="readonly" placeholder="Kordinat X" required>
                     </li>

                     <li class="nav-item">
                         <input class="form-control me-2" type="text" name="koordinaty" id="koorY" readonly="readonly" placeholder="Kordinat Y" required>
                     </li>
                     <li class="nav-item">
                         <input class="form-control me-2" type="text" name="namatempat" id="namaTempat" placeholder="Nama Tempat" required>
                     </li>
                     <li class="nav-item ms-5">
                         <button id="simpanpeta" class="btn btn-primary ">Simpan</button>
                         <button class="btn btn-warning" onclick="javascript:carikordinat(koorAwal);" class="bg-yellow rounded-md mt-2">Koordinat Awal</button>
                     </li>
                 </ul>
                 <form id="form" method="POST" role="search" name="country">
                     <div id="suggest" class="d-flex flex-row">
                         <input class="form-control me-2" placeholder="Cari Lokasi Tersimpan" aria-label="Search" type="text" value="" id="country" onblur="fill();" onkeyup="suggest(this.value);">
                     </div>
                     <div class="suggestionsBox" id="suggestions" style="display: none; text-decoration: none; position:absolute; z-index:2;"> <img src="arrow.png" style="position: relative; top: -12px; left: 30px;" alt="upArrow" />
                         <div class="suggestionList list-group" id="suggestionsList"> &nbsp; </div>
                     </div>
                 </form>
             </div>
         </div>
     </nav>
     </div>

     <div id="kanvaspeta" style="width:100%; height:630px; margin-bottom: 2em;">
     </div>

     <div class="justify-center flex">
         <div id="login" class="row">
             <div class="col-12">
                 <?php
                    if (isset($_SESSION['login']) && $_SESSION['login'] === true) {
                        // Jika sudah login, tampilkan pesan selamat datang
                        echo " <div class='col-12 md:col-12'>";
                        echo "<h2 style='margin: 10px;'> Data Lokasi Tersimpan : </h2>";
                        echo "<div id=kordinattersimpan class='list-group m-auto'>";
                        echo "</div>";
                        echo "<div class='container p-3 mb-2 bg-primary text-center' style='width: 6rem; border-radius: 5px;'>";
                        echo "<a class='text-white' style='text-decoration: none;' href='logout.php'>Logout</a>";
                        echo "</div>";
                        echo " </div>";
                    ?>

                 <?php
                    } else {
                        // Jika belum login, tampilkan formulir login
                        echo "<div class='container'>";
                        echo "<h2>Lokasi Tersimpan : </h2>";
                        echo "</div>";
                        echo "<div id=kordinattersimpan class='list-group container'>";
                        echo "</div>";
                        echo "<div class='container'>";
                        echo "<h2>Login Form</h2>";
                        echo "<form action='process_login.php' method='post'>";
                        echo "<div class='mb-3'>";
                        echo "<label class='form-label' for='username'>Username:</label>";
                        echo "<input class='form-control' type='text' name='username' required><br>";
                        echo "</div";
                        echo "<div class='mb-3'>";
                        echo "<label  class='form-label' for='password'>Password:</label>";
                        echo "<input class='form-control' type='password' name='password' required><br>";
                        echo "</div>";
                        echo "<input type='submit' class='btn btn-primary' value='Login'>";
                        echo "</form>";
                        echo "</div>";
                    }
                    ?>
             </div>
         </div>

     </div>


     <!-- <p class="text-green text-4xl">Silahkan klik kordinat awal terlebih dahulu sebelum memilih autosuggest</p> -->


     <script type="module">
         // Import the functions you need from the SDKs you need
         import {
             initializeApp
         } from "https://www.gstatic.com/firebasejs/10.7.1/firebase-app.js";
         import {
             getAnalytics
         } from "https://www.gstatic.com/firebasejs/10.7.1/firebase-analytics.js";
         // TODO: Add SDKs for Firebase products that you want to use
         // https://firebase.google.com/docs/web/setup#available-libraries

         // Your web app's Firebase configuration
         // For Firebase JS SDK v7.20.0 and later, measurementId is optional
         const firebaseConfig = {
             apiKey: "AIzaSyDOtlLPbfzI1_cMO_4zqo2dkXH0drZEXlU",
             authDomain: "sigapp-408617.firebaseapp.com",
             projectId: "sigapp-408617",
             storageBucket: "sigapp-408617.appspot.com",
             messagingSenderId: "240415901196",
             appId: "1:240415901196:web:c73feeb178b9e806505029",
             measurementId: "G-EVER8ZHS9P"
         };

         // Initialize Firebase
         const app = initializeApp(firebaseConfig);
         const analytics = getAnalytics(app);
     </script>

     <footer>
         <div class="container d-flex justify-content-center">
             <p>&copy; Bustanul Wildan Anshari (32220020) STMIK DCI</p>
         </div>
     </footer>

 </body>

 </html>