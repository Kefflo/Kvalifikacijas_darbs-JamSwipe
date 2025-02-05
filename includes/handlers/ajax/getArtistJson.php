<?php
// Iekļauj datubāzes konfigurācijas failu.
include("../../config.php");

// Pārbauda, vai ir saņemts mākslinieka ID.
if (isset($_POST['artistId'])) {
    $artistId = $_POST['artistId']; // Iegūst mākslinieka ID.

    // Izpilda SQL vaicājumu, lai iegūtu informāciju par mākslinieku.
    $query = mysqli_query($con, "SELECT * FROM artists WHERE id='$artistId'");
    $resultArray = mysqli_fetch_array($query); // Saglabā iegūtos datus masīvā.

    // Pārvērš datus JSON formātā un izvada tos.
    echo json_encode($resultArray);
}
?>
