<?php 
include("includes/includedFiles.php"); // Iekļauj nepieciešamos failus, kas satur funkcijas un konfigurācijas.
?>

<h1 class="pageHeadingBig">You might Also Like This</h1> <!-- Virsraksts, kas norāda, ka lietotājam varētu interesēt šie albumi -->

<div class="gridViewContainer"> <!-- Sāk konteineru, kurā tiks attēloti albumi -->
    <?php 
        $albumQuery = mysqli_query($con, "SELECT * FROM albums ORDER BY RAND() LIMIT 10"); // Veic vaicājumu, lai iegūtu 10 nejaušus albumus no datubāzes.

        while($row = mysqli_fetch_array($albumQuery)) { // Cikls, kas pārskata katru albumu no vaicājuma rezultātiem.
            // Izvada katru albumu kā vienību ar attēlu un nosaukumu, kuru var noklikšķināt, lai atvērtu albuma lapu.
            echo "<div class='gridViewItem'>
                    <span role='link' tabindex='0' onclick='openPage(\"album.php?id=" . $row['id'] . "\")' > <!-- Atver albuma lapu, noklikšķinot uz attēla -->
                        <img src='" . $row['artworkPath'] . "'> <!-- Attēlo albuma mākslas darbu -->
                        <div class='gridViewInfo'>"
                            . $row['title'] . // Izvērš albuma nosaukumu.
                        "</div>
                    </span>
                </div>";
        }
    ?>
</div> 
