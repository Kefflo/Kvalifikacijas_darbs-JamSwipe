<?php
    ob_start(); // Sāk buferēšanu, lai novērstu jebkādu izvadīšanu pirms galīgās lapas ielādes
    session_start(); // Sāk PHP sesiju, lai saglabātu un piekļūtu sesijas datiem

    // Iestata laika joslu uz Rīgu, lai nodrošinātu pareizu datumu un laiku
    $timezone = date_default_timezone_set("Europe/Riga");

    // Izveido savienojumu ar MySQL datubāzi "jamswipe" uz vietējā servera ar lietotāju "root" un bez parolēm
    $con = mysqli_connect("localhost", "root", "", "jamswipe");

    // Ja savienojums neizdodas, parāda kļūdas ziņojumu
    if(mysqli_connect_errno()) {
        echo "Failed to connect: " . mysqli_connect_errno(); // Izvada kļūdas numuru, ja savienojums neizdodas
    }
?>
