<?php

include("includes/includedFiles.php"); // Iekļauj nepieciešamos failus, kas satur funkcijas un konfigurācijas

if(isset($_GET['id'])) { // Pārbauda, vai URL satur 'id' parametru
    $artistId = $_GET['id']; // Saglabā mākslinieka ID, ja tas ir pieejams
}
else { // Ja 'id' parametrs nav norādīts
    header("Location: index.php"); // Pārsūta lietotāju uz galveno lapu
}

$artist = new Artist($con, $artistId); // Izveido mākslinieka objektu, izmantojot mākslinieka ID
?>

<div class="entityInfo borderBottom"> <!-- Sāk mākslinieka informācijas sadaļu ar apakšējo malu -->
    <div class="centerSection">
        <div class="artistInfo">
            <h1 class="artistName"><?php echo $artist->getName() ?></h1> <!-- Izvērš mākslinieka nosaukumu -->
            <div class="headerButton">
                <button class="button yellow" onclick="playFirstSong('<?php echo $artistId; ?>')">Play</button> <!-- Poga, lai atskaņotu pirmo dziesmu -->
            </div>
        </div>
    </div>
</div>

<div class="trackListContainer borderBottom"> <!-- Sāk dziesmu saraksta konteineru ar apakšējo malu -->
    <ul class="trackList">

    <h2>Popular</h2> <!-- Populāru dziesmu virsraksts -->

        <?php
            $songIdArray = $artist->getSongIds(); // Iegūst visu dziesmu ID no mākslinieka
            $i = 1; // Dziesmu secības numurs
            foreach($songIdArray as $songId) { // Cikls, kas pārskata katru dziesmas ID

                if($i > 5) { // Aptur ciklu pēc 5 dziesmām
                    break;
                }

                $albumSong = new Song($con, $songId); // Izveido dziesmas objektu, izmantojot dziesmas ID
                $albumArtist = $albumSong->getArtist(); // Iegūst mākslinieku, kas saistīts ar dziesmu

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
                            <img class='optionsButton' src='assets/images/icons/more.png'> 
                        </div>

                        <div class='trackDuration'>
                            <span class='duration'>" . $albumSong->getDuration() . "</span>
                        </div>
                    </li>";
                $i++; // Palielina dziesmas numuru
            }
        ?>

        <script>
            var tempSongIds = '<?php echo json_encode($songIdArray); ?>';
            tempPlaylist = JSON.parse(tempSongIds); // Parsē JSON formāta masīvu
        </script>

    </ul>
</div>

<div class="gridViewContainer"> <!-- Sāk dziesmu diskogrāfijas skata konteineru -->
    <h2>Discography</h2> <!-- Dziesmu diskogrāfijas virsraksts -->

    <?php 
        $albumQuery = mysqli_query($con, "SELECT * FROM albums WHERE artist='$artistId'"); // Veic vaicājumu, lai iegūtu albumus, kas saistīti ar mākslinieku

        while($row = mysqli_fetch_array($albumQuery)) { // Cikls, kas pārskata katru albumu
            echo "<div class='gridViewItem'>
                    <span role='link' tabindex='0' onclick='openPage(\"album.php?id=" . $row['id'] . "\")' >
                        <img src='" . $row['artworkPath'] . "'> 
                        <div class='gridViewInfo'>"
                            . $row['title'] . // Izvērš albuma nosaukumu
                        "</div>
                    </span>
                </div>";
        }
    ?>
</div>
