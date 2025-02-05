<?php
// Iekļauj datubāzes konfigurācijas failu.
include("../../config.php");

// Pārbauda, vai ir saņemts atskaņošanas saraksta ID.
if (isset($_POST['playlistId'])) {
    $playlistId = $_POST['playlistId']; // Iegūst atskaņošanas saraksta ID.

    // Dzēš atskaņošanas sarakstu no datubāzes.
    $playlistQuery = mysqli_query($con, "DELETE FROM playlists WHERE id='$playlistId'");

    // Dzēš visas dziesmas, kas saistītas ar šo atskaņošanas sarakstu.
    $songQuery = mysqli_query($con, "DELETE FROM playlistSongs WHERE playlistId='$playlistId'");
} else {
    // Ja nepieciešamais ID nav saņemts, izvada kļūdas paziņojumu.
    echo "error";
}
?>
