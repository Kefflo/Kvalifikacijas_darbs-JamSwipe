<?php
// Iekļauj konfigurācijas failu, kas satur datubāzes savienojuma iestatījumus.
include("../../config.php");

// Pārbauda, vai POST pieprasījumā ir nosūtīts parametrs 'songId'.
if(isset($_POST['songId'])) {

    // Piešķir mainīgajam 'songId' vērtību no POST pieprasījuma.
    $songId =  $_POST['songId'];

    // Izpilda SQL vaicājumu, lai palielinātu 'plays' laukā vērtību par 1 konkrētajai dziesmai, kuru identificē ID.
    $query = mysqli_query($con, "UPDATE songs SET plays = plays +1 WHERE id='$songId'");
}
?>
