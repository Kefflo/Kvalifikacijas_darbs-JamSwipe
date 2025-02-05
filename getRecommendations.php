<?php
// Iekļauj nepieciešamos failus un skriptus.
include("includes/includedFiles.php");

// Pārbauda, vai POST pieprasījumā ir norādīts mūzikas žanrs.
if(isset($_POST['genre']) && !empty($_POST['genre'])) {
    $genreId = $_POST['genre']; // Iegūst žanra ID no POST datiem.

    $genre = new Genre($con, $genreId); // Izveido jaunu Genre objektu.
    $songs = $genre->getSongs(); // Iegūst visas dziesmas šim žanram.

    // Ja dziesmu nav, parāda paziņojumu.
    if(empty($songs)) {
        echo "<p>No songs found for this genre.</p>";
    } else {
        // Izvada katru dziesmu atsevišķi.
        foreach($songs as $i => $songId) {
            $song = new Song($con, $songId); // Izveido jaunu Song objektu.

            echo "<li class='trackListRow'>
                    <div class='trackCount'>
                        <!-- Atskaņo dziesmu, kad tiek nospiesta play ikona -->
                        <img class='play' src='assets/images/icons/play-white.png' 
                            onclick='setTrack(\"". $song->getId() . "\", tempPlaylist, setTimeout(playSong, 100))'>    
                        <span class='trackNumber'>".($i + 1)."</span>
                    </div>
                    
                    <div class='trackInfo'>
                        <!-- Parāda dziesmas nosaukumu un izpildītāja vārdu -->
                        <span class='trackName'>".$song->getTitle()."</span>
                        <span class='artistName'>".$song->getArtist()->getName()."</span>
                    </div>

                    <div class='trackOptions'>
                        <!-- Slēpts lauks dziesmas ID glabāšanai -->
                        <input type='hidden' class='songId' value='" . $song->getId() . "'>
                        <!-- Ikona ar izvēlni, lai rādītu papildu opcijas -->
                        <img class='optionsButton' src='assets/images/icons/more.png' 
                            onclick='showOptionsMenu(this)'>
                    </div>

                    <div class='trackDuration'>
                        <!-- Parāda dziesmas ilgumu -->
                        <span class='duration'>" . $song->getDuration() . "</span>
                    </div>
                </li>";
        }
    }
} else {
    // Ja žanrs nav izvēlēts, tiek parādīts paziņojums.
    echo "<p>No genre selected.</p>";
}
?>

<!-- Izvēlne ar opcijām, kas tiek parādīta, kad tiek nospiesta 'more' poga -->
<nav class="optionsMenu">
    <input type="hidden" class="songId"> <!-- Slēpts lauks dziesmas ID glabāšanai -->
    
    <!-- Izgūst lietotāja atskaņošanas sarakstus -->
    <?php echo Playlist::getPlaylistsDropdown($con, $userLoggedIn->getUsername());?>

    <!-- Poga dziesmas izņemšanai no atskaņošanas saraksta -->
    <div class="item" onclick="removeFromPlaylist(this, '<?php echo $playlistId; ?>')">
        Remove from playlist
    </div>
</nav>
