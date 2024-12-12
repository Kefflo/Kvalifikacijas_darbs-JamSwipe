<?php
include("includes/config.php");  // Iekļauj konfigurācijas failu, kas satur savienojuma informāciju ar datubāzi
include("includes/classes/Artist.php");  // Iekļauj mākslinieku klase
include("includes/classes/Album.php");   // Iekļauj albuma klase
include("includes/classes/Song.php");    // Iekļauj dziesmas klase

// Pārbauda, vai lietotājs ir pieteicies (session mainīgais 'userLoggedIn' ir iestatīts)
if(isset($_SESSION['userLoggedIn'])) {
    $userLoggedIn = $_SESSION['userLoggedIn'];  // Iegūst pieteikušos lietotāju
    echo "<script>userLoggedIn = '$userLoggedIn';</script>";  // Iesniedz pieteikušos lietotāju JavaScript mainīgajam
}
else {
    header("Location: register.php");  // Ja lietotājs nav pieteicies, pārsūta uz reģistrācijas lapu
}
?>

<html>
    <head>
        <title>Welcome to JamSwipe!</title>
        <link rel="stylesheet" type="text/css" href="assets/css/style.css">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
        <script src="assets/js/script.js"></script>
    </head> 

    <body>
        <div id="mainContainer">

                <div id="topContainer">
                    <?php include("includes/navBarContainer.php"); ?>

                    <div id="mainViewContainer"	>
                        <div id="mainContent">