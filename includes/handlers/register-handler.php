<?php

// Funkcija, lai sanitizētu lietotāja vārdu, noņemot HTML tagus un atbrīvojoties no liekiem balstiem.
function sanitizeFormUsername($inputText) {
    $inputText = strip_tags($inputText); // Noņem HTML tagus no ievadītā teksta.
    $inputText = str_replace(" ","", $inputText); // Noņem visus atstarpes simbolus.
    return $inputText; // Atgriež attīrīto lietotāja vārdu.
}

// Funkcija, lai sanitizētu paroli, noņemot HTML tagus.
function sanitizeFormPassword($inputText) { 
    $inputText = strip_tags($inputText); // Noņem HTML tagus no paroles ievades.
    return $inputText; // Atgriež attīrīto paroli.
}

// Funkcija, lai sanitizētu citus ievadītus tekstus, piemēram, e-pastus vai vārdus.
function sanitizeFormString($inputText) {
    $inputText = strip_tags($inputText); // Noņem HTML tagus no ievades.
    $inputText = str_replace(" ","", $inputText); // Noņem atstarpes, piemēram, ja ievadīts vārds ar atstarpēm.
    $inputText = ucfirst(strtolower($inputText)); // Padara pirmo burtu lielo, bet pārējos mazos.
    return $inputText; // Atgriež attīrīto tekstu.
}

// Pārbauda, vai ir nospiesta reģistrēšanās poga.
if (isset($_POST['registerButton'])) {
    // Ja poga nospiesta, iegūst lietotāja ievadītos datus.
    $username = sanitizeFormUsername($_POST['username']); // Sanitizē lietotāja vārdu.
    $email = sanitizeFormUsername($_POST['email']); // Sanitizē pirmo e-pasta ievadi.
    $email2 = sanitizeFormUsername($_POST['email2']); // Sanitizē otro e-pasta ievadi.
    $password = sanitizeFormPassword($_POST['password']); // Sanitizē paroli.
    $password2 = sanitizeFormPassword($_POST['password2']); // Sanitizē otro paroli.

    // Izsauc reģistrēšanās funkciju, lai reģistrētu lietotāju.
    $wasSuccessful = $account->register($username, $email, $email2, $password, $password2);
    
    // Ja reģistrēšanās ir veiksmīga.
    if($wasSuccessful == true) {
        $_SESSION['userLoggedIn'] = $username; // Saglabā lietotāja vārdu sesijā.
        header("Location: index.php"); // Pārsūta lietotāju uz sākuma lapu.
    }
}

?>
