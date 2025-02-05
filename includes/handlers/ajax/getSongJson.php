<?php
// Iekļauj konfigurācijas failu, kas satur datubāzes savienojuma iestatījumus.
include("../../config.php");

// Pārbauda, vai POST pieprasījumā ir nosūtīts parametrs 'songId'.
if(isset($_POST['songId'])) {

    // Piešķir mainīgajam 'songId' vērtību no POST pieprasījuma.
    $songId = $_POST['songId'];

    // Izpilda SQL vaicājumu, lai atlasītu dziesmu no datubāzes pēc tās ID.
    $query = mysqli_query($con, "SELECT * FROM songs WHERE id='$songId'");

    // Pārveido SQL vaicājuma rezultātu par masīvu.
    $resultArray = mysqli_fetch_array($query);

    // Kodē rezultātu masīvu JSON formātā un nosūta to kā atbildi.
    echo json_encode($resultArray);
}
?>
