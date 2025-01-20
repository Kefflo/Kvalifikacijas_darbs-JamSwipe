<?php
if(isset($_SERVER['HTTP_X_REQUESTED_WITH'])) {
    include("includes/config.php");  
    include("includes/classes/User.php");  
    include("includes/classes/Artist.php"); 
    include("includes/classes/Album.php");
    include("includes/classes/Song.php");
    include("includes/classes/Playlist.php");
    include("includes/classes/Genre.php");
    
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }
   

    if(isset($_SESSION['userLoggedIn'])) {
        $userLoggedIn = new User($con, $_SESSION['userLoggedIn']);
    } elseif(isset($_GET['userLoggedIn'])) {
        $userLoggedIn = new User($con, $_GET['userLoggedIn']);
    } elseif(isset($_POST['userLoggedIn'])) {
        $userLoggedIn = new User($con, $_POST['userLoggedIn']);
    } else {
        echo "userLoggedIn not set";
        exit();
    }
} else {
    
    include("includes/header.php");
    include("includes/footer.php");

    
    $url = $_SERVER['REQUEST_URI'];
    echo "<script>openPage('$url')</script>";
    exit();
}

?>