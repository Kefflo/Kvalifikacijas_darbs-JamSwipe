<?php
// Pārbauda, vai pieprasījums nāk no AJAX (asynchronous JavaScript and XML)
if(isset($_SERVER['HTTP_X_REQUESTED_WITH'])) { 
    
    // Iekļauj nepieciešamos failus
    include("includes/config.php");  // Datubāzes konfigurācija
    include("includes/classes/User.php");  // Lietotāja klase
    include("includes/classes/Artist.php"); // Mākslinieka klase
    include("includes/classes/Album.php"); // Albuma klase
    include("includes/classes/Song.php"); // Dziesmas klase
    include("includes/classes/Playlist.php"); // Atskaņošanas saraksta klase
    include("includes/classes/Genre.php"); // Žanra klase
    
    // Pārbauda, vai sesija jau ir sākta, ja nav – sāk jaunu sesiju
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }

    // Pārbauda, vai ir ielogots lietotājs, un izveido User klases objektu
    if(isset($_SESSION['userLoggedIn'])) {  
        $userLoggedIn = new User($con, $_SESSION['userLoggedIn']); // Ja lietotājs ir ielogots caur sesiju

    } elseif(isset($_GET['userLoggedIn'])) {  
        $userLoggedIn = new User($con, $_GET['userLoggedIn']); // Ja lietotājs tiek nodots caur GET parametru

    } elseif(isset($_POST['userLoggedIn'])) {  
        $userLoggedIn = new User($con, $_POST['userLoggedIn']); // Ja lietotājs tiek nodots caur POST parametru

    } else {  
        // Ja nav ielogota lietotāja, izvada kļūdas ziņojumu un pārtrauc skriptu
        echo "userLoggedIn not set";
        exit();
    }

} else {  
    // Ja pieprasījums NAV no AJAX, tad ielādē standarta lapas struktūru
    include("includes/header.php");
    include("includes/footer.php");

    // Iegūst pašreizējo URL un atver lapu, izmantojot JavaScript funkciju openPage()
    $url = $_SERVER['REQUEST_URI'];
    echo "<script>openPage('$url')</script>";
    exit();
}

?>
