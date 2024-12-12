<?php

// Pārbauda, vai pieprasījums ir veikts ar AJAX, pārbaudot HTTP galveni 'HTTP_X_REQUESTED_WITH'
if(isset($_SERVER['HTTP_X_REQUESTED_WITH'])) {
    include("includes/config.php");  // Iekļauj konfigurācijas failu, kas satur savienojuma informāciju ar datubāzi
    include("includes/classes/User.php");  // Iekļauj lietotāja klasi
    include("includes/classes/Artist.php");  // Iekļauj mākslinieka klasi
    include("includes/classes/Album.php");   // Iekļauj albuma klasi
    include("includes/classes/Song.php");    // Iekļauj dziesmas klasi
    include("includes/classes/Playlist.php");  // Iekļauj atskaņošanas saraksta klasi
    
    // Pārbauda, vai URL ir norādīts pieteikušā lietotāja vārds
    if(isset($_GET['userLoggedIn'])) {
        // Ja ir, izveido jaunu lietotāja objektu, izmantojot lietotāja vārdu no URL
        $userLoggedIn = new User($con, $_GET['userLoggedIn']); 
    }
    else {
        echo "userLoggedIn not set";  // Ja nav norādīts, izvada kļūdas ziņu
        exit();  // Beidz izpildi
    }
}
else {
    // Ja pieprasījums nav AJAX, iekļauj lapas galvu un kājeni
    include("includes/header.php");
    include("includes/footer.php");

    // Iegūst pašreizējo URL un izsauc JavaScript funkciju 'openPage' ar šo URL kā argumentu
    $url = $_SERVER['REQUEST_URI'];
    echo "<script>openPage('$url')</script>";
    exit();  // Beidz izpildi
}

?>
