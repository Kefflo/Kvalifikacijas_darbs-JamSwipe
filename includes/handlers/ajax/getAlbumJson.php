<?php
// Iekļauj konfigurācijas failu, kas satur datubāzes savienojuma iestatījumus.
include("../../config.php");

// Pārbauda, vai POST pieprasījumā ir nosūtīts parametrs 'albumId'.
if(isset($_POST['albumId'])) {

    // Piešķir mainīgajam 'albumId' vērtību no POST pieprasījuma.
    $albumId = $_POST['albumId'];

    // Izpilda SQL vaicājumu, lai atlasītu albumu no datubāzes pēc tā ID.
    $query = mysqli_query($con, "SELECT * FROM albums WHERE id='$albumId'");

    // Pārveido SQL vaicājuma rezultātu par masīvu.
    $resultArray = mysqli_fetch_array($query);

    // Kodē rezultātu masīvu JSON formātā un nosūta to kā atbildi.
    echo json_encode($resultArray);
}
?>
