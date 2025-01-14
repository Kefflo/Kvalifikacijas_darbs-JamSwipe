<?php

// Check if the request is made via AJAX by checking the HTTP header 'HTTP_X_REQUESTED_WITH'
if(isset($_SERVER['HTTP_X_REQUESTED_WITH'])) {
    include("includes/config.php");  // Include the configuration file with database connection information
    include("includes/classes/User.php");  // Include the User class
    include("includes/classes/Artist.php");  // Include the Artist class
    include("includes/classes/Album.php");   // Include the Album class
    include("includes/classes/Song.php");    // Include the Song class
    include("includes/classes/Playlist.php");  // Include the Playlist class
    include("includes/classes/Genre.php");  // Include the Genre class
    
    // Check if the logged-in user's username is provided via GET or POST
    if(isset($_GET['userLoggedIn'])) {
        $userLoggedIn = new User($con, $_GET['userLoggedIn']); 
    } elseif(isset($_POST['userLoggedIn'])) {
        $userLoggedIn = new User($con, $_POST['userLoggedIn']);
    } else {
        echo "userLoggedIn not set";  // If not, output an error message
        exit();  // Stop execution
    }
} else {
    // If the request is not AJAX, include the page header and footer
    include("includes/header.php");
    include("includes/footer.php");

    // Get the current URL and call the JavaScript function 'openPage' with this URL as an argument
    $url = $_SERVER['REQUEST_URI'];
    echo "<script>openPage('$url')</script>";
    exit();  // Stop execution
}

?>