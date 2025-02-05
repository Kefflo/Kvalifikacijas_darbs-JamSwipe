<?php 
// Iekļauj nepieciešamos failus un skriptus.
include("includes/includedFiles.php"); 

// Pārbauda, vai GET pieprasījumā ir norādīts atskaņošanas saraksta ID.
if(isset($_GET['id'])) { 
    $playlistId = $_GET['id']; // Iegūst atskaņošanas saraksta ID no URL.
}
else { 
    // Ja nav norādīts ID, pāradresē lietotāju uz sākumlapu.
    header("Location: index.php"); 
}

// Izveido Playlist objektu.
$playlist = new Playlist($con, $playlistId);
// Izveido User objektu, lai iegūtu atskaņošanas saraksta īpašnieku.
$owner = new User($con, $playlist->getOwner());
?>

<!-- Atskaņošanas saraksta informācija -->
<div class="entityInfo"> 
    <div class="leftSection">
        <div class="playlistImage">
            <!-- Atskaņošanas saraksta noklusējuma attēls -->
            <img src="assets/images/icons/playlist.png">
        </div>
    </div>
    <div class="rightSection">
        <!-- Parāda atskaņošanas saraksta nosaukumu -->
        <h2><?php echo $playlist->getName(); ?></h2> 
        <!-- Parāda atskaņošanas saraksta īpašnieku -->
        <p><?php echo $playlist->getOwner(); ?></p> 
        <!-- Parāda dziesmu skaitu atskaņošanas sarakstā -->
        <p><?php echo $playlist->getNumberOfSongs(); ?> songs</p> 
        <!-- Poga atskaņošanas saraksta dzēšanai -->
        <button class="button" onclick="deletePlaylist('<?php echo $playlistId; ?>')">Delete playlist</button>
    </div>
</div>

<!-- Dziesmu saraksts atskaņošanas sarakstā -->
<div class="trackListContainer"> 
    <ul class="trackList"> 
        <?php
            // Iegūst visas dziesmas atskaņošanas sarakstā.
            $songIdArray = $playlist->getSongIds();
            $i = 1; // Numurē dziesmas sarakstā.

            // Caur katru dziesmu atskaņošanas sarakstā.
            foreach($songIdArray as $songId) { 
                $playlistSong = new Song($con, $songId); // Izveido Song objektu.
                $songArtist = $playlistSong->getArtist(); // Iegūst izpildītāja objektu.

                // Izvada dziesmu sarakstā.
                echo "<li class='trackListRow'>
                        <div class='trackCount'>
                            <!-- Atskaņo dziesmu, kad tiek nospiesta play ikona -->
                            <img class='play' src='assets/images/icons/play-white.png' 
                                onclick ='setTrack(\"". $playlistSong->getId() . "\", tempPlaylist, setTimeout(playSong, 100))'>
                            <span class='trackNumber'>$i</span>
                        </div>

                        <div class='trackInfo'>
                            <!-- Parāda dziesmas nosaukumu un izpildītāja vārdu -->
                            <span class='trackName'>" . $playlistSong->getTitle() . "</span>
                            <span class='artistName'>" . $songArtist->getName() . "</span>
                        </div>

                        <div class='trackOptions'>
                            <!-- Slēpts lauks dziesmas ID glabāšanai -->
                            <input type='hidden' class='songId' value='" . $playlistSong->getId() . "'>
                            <!-- Ikona ar izvēlni, lai rādītu papildu opcijas -->
                            <img class='optionsButton' src='assets/images/icons/more.png' 
                                onclick='showOptionsMenu(this)'>
                        </div>

                        <div class='trackDuration'>
                            <!-- Parāda dziesmas ilgumu -->
                            <span class='duration'>" . $playlistSong->getDuration() . "</span>
                        </div>
                    </li>";
                $i++; // Palielina dziesmu numurāciju.
            }
        ?>

        <script>
            // Saglabā dziesmu ID sarakstu JavaScript masīvā.
            var tempSongIds = '<?php echo json_encode($songIdArray); ?>';
            tempPlaylist = JSON.parse(tempSongIds);
        </script>
    </ul>
</div>

<!-- Izvēlne ar opcijām dziesmām -->
<nav class="optionsMenu">
    <input type="hidden" class="songId"> <!-- Slēpts lauks dziesmas ID glabāšanai -->

    <!-- Izgūst lietotāja atskaņošanas sarakstus -->
    <?php echo Playlist::getPlaylistsDropdown($con, $userLoggedIn->getUsername());?>

    <!-- Poga dziesmas izņemšanai no atskaņošanas saraksta -->
    <div class="item" onclick="removeFromPlaylist(this, '<?php echo $playlistId; ?>')">
        Remove from playlist
    </div>
</nav>
