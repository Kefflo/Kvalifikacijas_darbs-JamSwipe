<?php
include("includes/includedFiles.php"); // Iekļauj nepieciešamos failus, piemēram, datubāzes savienojuma un funkcionalitātes failus.

// Pārbauda, vai ir ievadīta meklēšanas frāze.
if(isset($_GET['term'])) {
    $term = urldecode($_GET['term']); // Ja ir, dekodē meklēšanas frāzi.
} else {
    $term = ""; // Ja nav, iestata tukšu meklēšanas frāzi.
}

// Pārbauda, vai datubāzē ir mākslinieki, kuru nosaukums sākas ar meklēšanas frāzi.
$artistQuery = mysqli_query($con, "SELECT * FROM artists WHERE name LIKE '$term%' LIMIT 10");

// Ja nav mākslinieku ar šo nosaukumu, pārbauda, vai ir albumi, kuru nosaukums sākas ar meklēšanas frāzi.
if(mysqli_num_rows($artistQuery) == 0) {
    $albumQuery = mysqli_query($con, "SELECT * FROM albums WHERE title LIKE '$term%' LIMIT 10");
    // Ja nav albumu, pārbauda, vai ir dziesmas, kuru nosaukums vai mākslinieks atbilst meklēšanas frāzei.
    if(mysqli_num_rows($albumQuery) == 0) {
        $songQuery = mysqli_query($con, "SELECT * FROM songs WHERE title LIKE '$term%' OR artist LIKE '$term%' LIMIT 10");
    }
}
?>

<div class="searchContainer"> <!-- Meklēšanas formas konteineris -->
    <h4>Search for an artist, album or song</h4> <!-- Virsraksts meklēšanas laukam -->
    <input type="text" class="searchInput" value="<?php echo $term; ?>" onfocus="this.value = this.value;" onblur="if(this.value == '') {this.value = '<?php echo $term; ?>';}" placeholder="artist, album or song...."> <!-- Meklēšanas ievades lauks -->
</div>

<script >
// Jquery kods, lai notīrītu meklēšanas lauku, kad tas iegūst fokusu.
$(".searchInput").focus(function() {
    $(this).val(""); // Iztīra meklēšanas lauku, kad tas tiek aktivizēts.
});

// Funkcija, kas veic meklēšanas pieprasījumu, kad lietotājs ievada tekstu.
$(function() {
    $(".searchInput").keyup(function() {
        clearTimeout(timer); // Notīra iepriekšējo laika taimeri.
        timer = setTimeout(function() {
            var val = $(".searchInput").val(); // Iegūst meklēšanas ievadīto vērtību.
            openPage("search.php?term=" + val); // Atjaunina lapu ar jauniem meklēšanas rezultātiem.
        }, 2000); // Atjauno lapu pēc 2 sekundēm.
    });
});
</script>

<?php  
 if ($term == "") exit(); // Ja meklēšanas frāze ir tukša, apstājas tālāka apstrāde.
?>

<div class="trackListContainer borderBottom"> <!-- Dziesmu saraksta konteineris -->
    <ul class="trackList"> <!-- Dziesmu saraksts -->
    <h2>Songs</h2> <!-- Virsraksts dziesmām -->

        <?php
        // Izpilda SQL pieprasījumu, lai iegūtu dziesmas, kuru nosaukums vai mākslinieks atbilst meklēšanas frāzei.
        $songsQuery = mysqli_query($con, "SELECT id FROM songs WHERE title LIKE '$term%' OR artist LIKE '$term%' ORDER BY plays DESC LIMIT 10");

        // Ja nav atrastas dziesmas, izvada ziņu.
        if(mysqli_num_rows($songsQuery) == 0) {
            echo "<span class='noResults'>No results found for " . $term . "</span>";
        }

        $songIdArray = array(); // Masīvs, kurā tiek glabāti dziesmu ID.
        $i = 1; // Skaitītājs dziesmu rindas numerācijai.
        while($row = mysqli_fetch_array($songsQuery)) { // Pārlasa katru atrasto dziesmu.
            array_push($songIdArray, $row['id']); // Pievieno dziesmas ID masīvam.

            if($i > 10) { // Ja ir vairāk nekā 10 dziesmas, iznāk no cikla.
                break;
            }

            $albumSong = new Song($con, $row['id']); // Izveido dziesmas objektu, izmantojot ID.
            $albumArtist = $albumSong->getArtist(); // Iegūst dziesmas mākslinieku.

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
            $i++; // Pāriet pie nākamās dziesmas.
        }
        ?>

        <script>
            var tempSongIds = '<?php echo json_encode($songIdArray); ?>'; // Iegūst dziesmu ID masīvu.
            tempPlaylist = JSON.parse(tempSongIds); // Parsē dziesmu ID masīvu.
        </script>

    </ul>
</div>

<div class="artistContainer borderBottom"> <!-- Mākslinieku konteineris -->
    <h2>Artists</h2> <!-- Virsraksts māksliniekiem -->

    <?php
    // Izpilda SQL pieprasījumu, lai iegūtu māksliniekus, kuru nosaukums atbilst meklēšanas frāzei.
    $artistQuery = mysqli_query($con, "SELECT id FROM artists WHERE name LIKE '$term%' LIMIT 10");

    // Ja nav atrasti mākslinieki, izvada ziņu.
    if(mysqli_num_rows($artistQuery) == 0) {
        echo "<span class='noResults'>No results found for " . $term . "</span>";
    }

    // Pārlasa atrastos māksliniekus un izvada tos.
    while($row = mysqli_fetch_array($artistQuery)) {
        $artist = new Artist($con, $row['id']); // Izveido mākslinieka objektu.
        echo "<div class='searchResultRow'>
                <div class='artistName'>
                    <span role='link' tabindex='0' onclick='openPage(\"artist.php?id=" . $artist->getId() . "\")'>
                        " . $artist->getName() . "
                    </span>
                </div>
            </div>";
    }
    ?>
</div>

<div class="gridViewContainer borderBottom"> <!-- Albūmu skatījuma konteineris -->
    <h2>Albums</h2> <!-- Virsraksts albūmiem -->

    <?php 
    // Izpilda SQL pieprasījumu, lai iegūtu albumus, kuru nosaukums atbilst meklēšanas frāzei.
    $albumQuery = mysqli_query($con, "SELECT * FROM albums WHERE title LIKE '$term%' LIMIT 10");

    // Ja nav atrasti albumi, izvada ziņu.
    if(mysqli_num_rows($albumQuery) == 0) {
        echo "<span class='noResults'>No results found for " . $term . "</span>";
    }

    // Pārlasa atrastos albumus un izvada tos.
    while($row = mysqli_fetch_array($albumQuery)) {
        echo "<div class='gridViewItem'>
                <span role='link' tabindex='0' onclick='openPage(\"album.php?id=" . $row['id'] . "\")' >
                    <img src='" . $row['artworkPath'] . "'>
                    <div class='gridViewInfo'>"
                        . $row['title'] .
                    "</div>
                </span>
            </div>";
    }
    ?>
</div>

<nav class="optionsMenu">
    <input type="hidden" class="songId">
    <?php echo Playlist::getPlaylistsDropdown($con, $userLoggedIn->getUsername());?>
</nav>