<?php
include("includes/includedFiles.php"); // Iekļauj nepieciešamos failus, piemēram, datubāzes savienojuma un funkcionalitātes failus.
?>

<div class="playlistContainer"> 
    <div class="gridViewContainer"> 
        <h2>Playlists</h2> 
        
        <div class="buttonItems"> 
            <button class="button yellow" onclick="createPlaylist()">Create Playlist</button> 
        </div>

        <?php 
        // Iegūst pašreizējā lietotāja vārdu.
        $username = $userLoggedIn->getUsername();
        
        // Izpilda SQL pieprasījumu, lai iegūtu visus atskaņošanas sarakstus, kuru īpašnieks ir pašreizējais lietotājs.
        $playlistQuery = mysqli_query($con, "SELECT * FROM playlists WHERE owner ='$username'");

        // Ja nav atrasti atskaņošanas saraksti, izvada ziņu.
        if(mysqli_num_rows($playlistQuery) == 0) {
            echo "<span class='noResults'>No results found </span>";
        }

        // Pārlasa katru atrasto atskaņošanas sarakstu un izvada to informāciju.
        while($row = mysqli_fetch_array($playlistQuery)) {
            // Izveido Playlist objektu, izmantojot datubāzē atrastos datus.
            $playlist = new Playlist($con, $row);
            
            // Attēlo atskaņošanas saraksta attēlu, nosaukumu un ļauj lietotājam noklikšķināt, lai atvērtu atskaņošanas sarakstu.
            echo "<div class='gridViewItem' role='link' tabindex='0' onclick='openPage(\"playlist.php?id=" . $playlist->getId() . "\")'>
                        <div class='playlistImage'>
                            <img src='assets/images/icons/playlist.png'> <!-- Attēls, kas attēlo atskaņošanas saraksta ikonu -->
                        </div>
                        <div class='gridViewInfo'>" 
                        . $playlist->getName() . 
                        "</div>
                    </div>";
        }
        ?>

    </div>
</div>
