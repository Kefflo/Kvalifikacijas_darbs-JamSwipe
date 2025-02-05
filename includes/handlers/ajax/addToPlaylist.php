<?php
// Iekļauj datubāzes konfigurācijas failu.
include("../../config.php");

// Pārbauda, vai ir saņemti nepieciešamie dati (playlistId un songId).
if (isset($_POST['playlistId']) && isset($_POST['songId'])) {
    $playlistId = $_POST['playlistId']; // Iegūst atskaņošanas saraksta ID.
    $songId = $_POST['songId']; // Iegūst dziesmas ID.

    // Atrod nākamo pieejamo atskaņošanas secības numuru šajā atskaņošanas sarakstā.
    $orderIdQuery = mysqli_query($con, "SELECT COALESCE(MAX(playlistOrder) + 1, 1) as playlistOrder FROM playlistSongs WHERE playlistId='$playlistId'");    
    $row = mysqli_fetch_array($orderIdQuery);
    $orderId = $row['playlistOrder']; // Piešķir nākamo secības numuru.

    // Ievieto dziesmu atskaņošanas sarakstā ar noteikto secības numuru.
    $query = mysqli_query($con, "INSERT INTO playlistSongs (songId, playlistId, playlistOrder) VALUES ('$songId', '$playlistId', '$orderId')");
} else {
    // Ja kāds no nepieciešamajiem datiem nav saņemts, izvada kļūdas paziņojumu.
    echo "error";
}
?>
