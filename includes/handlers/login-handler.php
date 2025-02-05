<?php
// Pārbauda, vai ir nospiesta poga ar nosaukumu 'loginButton'.
if(isset($_POST['loginButton'])) {
    // Ja poga nospiesta, iegūst lietotāja vārdu un paroli no POST pieprasījuma.
    $username = $_POST['loginUsername'];
    $password = $_POST['loginPassword'];

    // Izsauc 'login' funkciju, lai mēģinātu autorizēties ar ievadītajiem datiem.
    $result = $account->login($username, $password);

    // Ja lietotājs ir veiksmīgi pieslēdzies (login funkcija atgriež 'true').
    if($result == true) {
        // Saglabā lietotāja vārdu sesijā, lai saglabātu informāciju par pieslēgšanos.
        $_SESSION['userLoggedIn'] = $username;
        // Pārsūta lietotāju uz galveno lapu (index.php).
        header("Location: index.php");
    }
}
?>
