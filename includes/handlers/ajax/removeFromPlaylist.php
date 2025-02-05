<?php
// Iekļauj datubāzes konfigurācijas failu.
include("../../config.php");

// Pārbauda, vai ir saņemti nepieciešamie dati (dziesmas ID un atskaņošanas saraksta ID).
if (isset($_POST['songId']) && isset($_POST['playlistId'])) {
    $songId = $_POST['songId']; // Iegūst dziesmas ID.
    $playlistId = $_POST['playlistId']; // Iegūst atskaņošanas saraksta ID.

    // Izpilda SQL vaicājumu, lai dzēstu dziesmu no atskaņošanas saraksta.
    $query = mysqli_query($con, "DELETE FROM playlistSongs WHERE songId='$songId' AND playlistId='$playlistId'");
} else {
    // Ja nepieciešamie dati nav saņemti, izvada kļūdas paziņojumu.
    echo "error";
}
?>
