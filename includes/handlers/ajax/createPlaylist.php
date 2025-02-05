<?php
// Iekļauj datubāzes konfigurācijas failu.
include("../../config.php");

// Pārbauda, vai ir saņemti nepieciešamie dati (nosaukums un lietotājvārds).
if (isset($_POST['name']) && isset($_POST['username'])) {
    $name = $_POST['name']; // Iegūst atskaņošanas saraksta nosaukumu.
    $username = $_POST['username']; // Iegūst lietotājvārdu, kurš izveido sarakstu.
    $date = date("Y-m-d"); // Iegūst pašreizējo datumu formātā YYYY-MM-DD.

    // Izpilda SQL vaicājumu, lai ievietotu jaunu atskaņošanas sarakstu datubāzē.
    $query = mysqli_query($con, "INSERT INTO playlists VALUES('', '$name', '$username', '$date')");
} else {
    // Ja nepieciešamie dati nav saņemti, izvada kļūdas paziņojumu.
    echo "error";
}
?>
