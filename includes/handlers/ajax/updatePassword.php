<?php
// Iekļauj datubāzes konfigurācijas failu.
include("../../config.php");

// Pārbauda, vai lietotājvārds ir iestatīts.
if (!isset($_POST['username'])) {
    echo "ERROR: Could not set username";
    exit();
}

// Pārbauda, vai visi paroles lauki ir iestatīti.
if (!isset($_POST['oldPassword']) || !isset($_POST['newPassword1']) || !isset($_POST['newPassword2'])) {
    echo "Could not set password";
    exit();
}

// Pārbauda, vai visi lauki ir aizpildīti.
if ($_POST['oldPassword'] == "" || $_POST['newPassword1'] == "" || $_POST['newPassword2'] == "") {
    echo "Please fill in all fields";
    exit();
}

$username = $_POST['username'];
$oldPassword = $_POST['oldPassword'];
$newPassword1 = $_POST['newPassword1'];
$newPassword2 = $_POST['newPassword2'];

$oldMd5 = md5($oldPassword); // Šifrē veco paroli.

// Pārbauda, vai vecā parole ir pareiza.
$passwordCheck = mysqli_query($con, "SELECT * FROM users WHERE username='$username' AND password='$oldMd5'");
if (mysqli_num_rows($passwordCheck) != 1) {
    echo "Your old password is wrong";
    exit();
}

// Pārbauda, vai jaunās paroles sakrīt.
if ($newPassword1 != $newPassword2) {
    echo "Your new passwords do not match";
    exit();
}

// Pārbauda, vai jaunā parole satur tikai atļautās rakstzīmes.
if (preg_match('/[^A-Za-z0-9]/', $newPassword1)) {
    echo "Your password can only contain numbers and letters";
    exit();
}

// Pārbauda paroles garumu.
if (strlen($newPassword1) > 30 || strlen($newPassword1) < 5) {
    echo "Your password must be between 5 and 30 characters";
    exit();
}

$newMd5 = md5($newPassword1); // Šifrē jauno paroli.

// Atjaunina lietotāja paroli datubāzē.
$updatePasswordQuery = mysqli_query($con, "UPDATE users SET password='$newMd5' WHERE username='$username'");

echo "Update successful";
?>
