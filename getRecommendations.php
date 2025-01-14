<?php
include("includes/includedFiles.php");

// Debugging statement to check if userLoggedIn is set


// Debugging statement to check if genre is set
if(isset($_POST['genre']) && !empty($_POST['genre'])) {
    $genreId = $_POST['genre'];

    $genre = new Genre($con, $genreId);
    $songs = $genre->getSongs(); // Assuming getSongs() returns an array of song IDs

    // Debugging statement to check if songs are found
    if(empty($songs)) {
        echo "<p>No songs found for this genre.</p>";
    } else {
        foreach($songs as $i => $songId) {
            $song = new Song($con, $songId);
            echo "<li class='trackListRow'>
                    <div class='trackCount'>
                        <img class='play' src='assets/images/icons/play-white.png' onclick='setTrack(\"". $song->getId() . "\", tempPlaylist, setTimeout(playSong, 100))'>    
                        <span class='trackNumber'>".($i + 1)."</span>
                    </div>
                    <div class='trackInfo'>
                        <span class='trackName'>".$song->getTitle()."</span>
                        <span class='artistName'>".$song->getArtist()->getName()."</span>
                    </div>
                    <div class='trackOptions'>
                        <input type='hidden' class='songId' value='" . $song->getId() . "'>
                        <img class='optionsButton' src='assets/images/icons/more.png' onclick='showOptionsMenu(this)'>
                    </div>
                    <div class='trackDuration'>
                        <span class='duration'>" . $song->getDuration() . "</span>
                    </div>
                </li>";
        }
    }
} else {
    echo "<p>No genre selected.</p>";
}
?>
<nav class="optionsMenu">
    <input type="hidden" class="songId">
    <?php echo Playlist::getPlaylistsDropdown($con, $userLoggedIn->getUsername());?>
    <div class="item" onclick="removeFromPlaylist(this, '<?php echo $playlistId; ?>')">Remove from playlist</div>
</nav>