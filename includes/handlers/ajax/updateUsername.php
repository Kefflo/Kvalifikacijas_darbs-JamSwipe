<?php
// Iekļauj datubāzes konfigurācijas failu.
include("../../config.php");

// Pārbauda, vai nepieciešamie dati ir saņemti.
if (!isset($_POST['username']) || !isset($_POST['userLoggedIn'])) {
    echo "ERROR: Could not set username";
    exit();
}

$username = $_POST['username'];
$userLoggedIn = $_POST['userLoggedIn'];

// Pārbauda, vai lietotājvārds atbilst garuma prasībām.
if (strlen($username) < 5 || strlen($username) > 25) {
    echo "Username must be between 5 and 25 characters";
    exit();
}

// Pārbauda, vai lietotājvārds jau pastāv, izņemot pašreizējo lietotāju.
$usernameCheck = mysqli_query($con, "SELECT username FROM users WHERE username='$username' AND username != '$userLoggedIn'");
if (mysqli_num_rows($usernameCheck) > 0) {
    echo "Username is already in use";
    exit();
}

// Atjaunina lietotājvārdu datubāzē.
$updateQuery = mysqli_query($con, "UPDATE users SET username='$username' WHERE username='$userLoggedIn'");

if ($updateQuery) {
    $_SESSION['userLoggedIn'] = $username;
    echo "Update successful";
} else {
    echo "Error updating username: " . mysqli_error($con);
}
?>
