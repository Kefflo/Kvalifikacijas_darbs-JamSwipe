<?php include("includes/includedFiles.php"); // Iekļauj nepieciešamos failus, kas satur konfigurācijas un funkcijas
if(isset($_GET['id'])) { // Pārbauda, vai URL satur 'id' parametru
    $albumId = $_GET['id']; // Saglabā albumu ID, ja tas ir pieejams
}
else { // Ja 'id' parametrs nav norādīts
    header("Location: index.php"); // Pārsūta lietotāju uz galveno lapu
}

// Izveido albumu objektu, izmantojot albumu ID
$album = new Album($con, $albumId);
// Iegūst mākslinieku, kas saistīts ar šo albumu
$artist = $album->getArtist();
?>

<div class="entityInfo"> <!-- Sāk albumu informācijas sadaļu -->
    <div class="leftSection">
        <img src="<?php echo $album->getArtworkPath(); ?>"> <!-- Attēlo albuma mākslas darbu -->
    </div>
    <div class="rightSection">
        <h2><?php echo $album->getTitle(); ?></h2> <!-- Izvērš albuma nosaukumu -->
        <p><?php echo $artist->getName(); ?></p> <!-- Izvērš mākslinieka nosaukumu -->
        <p><?php echo $album->getNumberOfSongs(); ?> songs</p> <!-- Izvērš dziesmu skaitu albumā -->
    </div>
</div>

<div class="trackListContainer"> <!-- Sāk dziesmu saraksta konteineru -->
    <ul class="trackList"> <!-- Saraksts, kurā tiks izvietotas dziesmas -->
        <?php
            // Iegūst visu dziesmu ID no albuma
            $songIdArray = $album->getSongIds();
            $i = 1; // Dziesmu secības numurs
            foreach($songIdArray as $songId) { // Cikls, kas pārskata katru dziesmas ID
                // Izveido dziesmas objektu, izmantojot dziesmas ID
                $albumSong = new Song($con, $songId);
                // Iegūst mākslinieku, kas saistīts ar dziesmu
                $albumArtist = $albumSong->getArtist();

                // Izvada katru dziesmu sarakstā
                echo "<li class='trackListRow'>
                        <div class='trackCount'>
                            <img class='play' src='assets/images/icons/play-white.png' onclick ='setTrack(\"". $albumSong->getId() . "\", tempPlaylist, setTimeout(playSong, 100))'>
                            <span class='trackNumber'>$i</span>
                        </div>

                        <div class='trackInfo'>
                            <span class='trackName'>" . $albumSong->getTitle() . "</span>
                            <span class='artistName'>" . $albumArtist->getName() . "</span>
                        </div>

                        <div class='trackOptions'>
                            <input type='hidden' class='songId' value='" . $albumSong->getId() . "'>
                            <img class='optionsButton' src='assets/images/icons/more.png' onclick='showOptionsMenu(this)'>
                        </div>

                        <div class='trackDuration'>
                            <span class='duration'>" . $albumSong->getDuration() . "</span>
                        </div>
                    </li>";
                $i++;
            }
        ?>

        <script>
            // Izveido pagaidu dziesmu ID masīvu, kuru izmantos JavaScript
            var tempSongIds = '<?php echo json_encode($songIdArray); ?>';
            tempPlaylist = JSON.parse(tempSongIds); // Parsē JSON formāta masīvu
        </script>
    </ul>
</div>




<nav class="optionsMenu">
    <input type="hidden" class="songId">
    <?php echo Playlist::getPlaylistsDropdown($con, $userLoggedIn->getUsername());?>
</nav>