<?php 
// Izvēlas nejaušus dziesmu ID no datu bāzes, ierobežojot līdz 10
$songQuery = mysqli_query($con, "SELECT id FROM songs ORDER BY RAND() LIMIT 10");
$resultArray = array();

// Iegūst dziesmu ID un saglabā tos masīvā
while($row = mysqli_fetch_array($songQuery)) {
    array_push($resultArray, $row['id']);
}

// Pārvērš dziesmu ID masīvu par JSON formātu, lai to varētu izmantot JavaScript
$jsonArray = json_encode($resultArray);
?>

<script>
    $(document).ready(function() {
        var newPlaylist = <?php echo $jsonArray ?>;  // Iegūst dziesmu ID masīvu no PHP uz JavaScript
        audioElement = new Audio();  // Izveido jaunu audio elementu
        setTrack(newPlaylist[0], newPlaylist, false);  // Iestata pirmo dziesmu atskaņošanai
        updateVolumeProgressBar(audioElement.audio);  // Atjaunina skaļuma slīdni

        // Novērš dzelteno indikatoru no atskaņošanas joslas, kad tiek vilkts
        $("#nowPlayingBarContainer").on("mousedown touchstart mousemove touchmove", function(e) {
            e.preventDefault();
        });

        // Vadības pogu funkcionalitātes
        $(".playbackBar .progressBar").mousedown(function() {
            mouseDown = true;  // Ja nospiests, sāk atskaņot
        });

        // Pārvieto progresu, kad peles kustība tiek veikta
        $(".playbackBar .progressBar").mousemove(function(e) {
            if(mouseDown) {
                var percent = e.offsetX / $(this).width();
                audioElement.audio.currentTime = audioElement.audio.duration * percent;  // Atjaunina dziesmas laiku
            }
        });

        // Kad peles poga tiek atlaista, atjaunina dziesmas progresu
        $(".playbackBar .progressBar").mouseup(function(e) {
            var percent = e.offsetX / $(this).width();
            audioElement.audio.currentTime = audioElement.audio.duration * percent; 
            mouseDown = false;
        });

        // Vadības funkcionalitāte, kas regulē skaļumu
        $(".volumeBar .progressBar").mousedown(function() {
            mouseDown = true;  // Ja nospiests, sāk mainīt skaļumu
        });

        // Maina skaļumu, kad peles kustība tiek veikta
        $(".volumeBar .progressBar").mousemove(function(e) {
            if(mouseDown) {
                var percent = e.offsetX / $(this).width();
                if(percent > 0.1) {
                    audioElement.audio.volume = percent;  // Atjaunina audio skaļumu
                }
            }
        });

        // Kad peles poga tiek atlaista, atjaunina skaļumu
        $(".volumeBar .progressBar").mouseup(function(e) {
            var percent = e.offsetX / $(this).width();
            if(percent > 0.1) {
                audioElement.audio.volume = percent;  // Atjaunina skaļumu
            }
            mouseDown = false;
        });
    });

    // Funkcija, lai aprēķinātu laiku no progresijas slīdņa
    function timeFromOffset(progressBar, mouse) {
        var percentage = mouse.offsetX / $(".progressBar . playbackBar").width() * 100;
        var seconds = audioElement.audio.duration * (percentage / 100);
        audioElement.setTime(seconds); // Iestata laiku dziesmā
    }
    // Funkcija, lai atskaņotu iepriekšējo dziesmu
    function prevSong() {    
        if(audioElement.audio.currentTime >= 3 || currentIndex == 0) {
            audioElement.setTime(0);  // Ja dziesma ir noskanējusi vismaz 3 sekundes, sāk no jauna
        }
        else {
            currentIndex = currentIndex - 1; // Atgriežas pie iepriekšējās dziesmas
            setTrack(currentPlaylist[currentIndex], currentPlaylist, true)
            setTimeout(playSong, 100);

        }
    }

    // Funkcija, lai atskaņotu nākamo dziesmu
    function nextSong() {
        if(repeat == true) {
            audioElement.setTime(0);  // Ja ir ieslēgta atkārtošana, sāk dziesmu no jauna
            playSong();
            return;
        }

        if(currentIndex == currentPlaylist.length - 1) {
            currentIndex = 0;  // Ja dziesma ir pēdējā, atgriežas pie pirmās
        }
        else {
            currentIndex ++;  // Pāriet uz nākamo dziesmu
        }
        var trackToPlay = shuffle ? shufflePlaylist[currentIndex] : currentPlaylist[currentIndex];
        setTrack(currentPlaylist[currentIndex], currentPlaylist, true);
        setTimeout(playSong, 100);
    }

    // Funkcija, lai aktivizētu vai deaktivizētu atkārtošanu
    function setRepeat() {
        repeat = !repeat;
        if(repeat == true) {
            $(".controlButton.repeat img").attr("src", "assets/images/icons/repeat-yellow.png"); // Maina ikonu uz dzelteno versiju
        }
        else {
            $(".controlButton.repeat img").attr("src", "assets/images/icons/repeat.png"); // Maina ikonu uz parasto versiju
        }
    }

    // Funkcija, lai ieslēgtu vai izslēgtu skaļumu
    function setMute() {
        audioElement.audio.muted = !audioElement.audio.muted;
        if(audioElement.audio.muted) {
            $(".controlButton.volume img").attr("src", "assets/images/icons/volume-mute.png");
        }
        else {
            $(".controlButton.volume img").attr("src", "assets/images/icons/volume.png");
        }
    }

    // Funkcija, lai ieslēgtu vai izslēgtu izlases atskaņošanas kārtību (nejaušības princips)
    function setShuffle() {
        shuffle = !shuffle;
        if(shuffle == true) {
            $(".controlButton.shuffle img").attr("src", "assets/images/icons/shuffle-yellow.png");
            shuffleArray(shufflePlaylist); // Sajauc atskaņošanas kārtību
            currentPlaylist = shufflePlaylist.slice();// Saglabā sajaukto atskaņošanas kārtību
            currentIndex = shufflePlaylist.indexOf(audioElement.currentlyPlaying.id);
        }
        else {
            $(".controlButton.shuffle img").attr("src", "assets/images/icons/shuffle.png"); // Atgriež sākotnējo ikonu
            currentIndex = currentPlaylist.indexOf(audioElement.currentlyPlaying.id);

        }
    }

    // Funkcija, lai sajauktu masīvu (izmanto, kad ieslēgta izlases atskaņošana)
    function shuffleArray(a) {
        var j, x, i;
        for (i = a.length; i; i--) {
            j = Math.floor(Math.random() * i);
            x = a[i - 1];
            a[i - 1] = a[j];
            a[j] = x;
        }
    }

    // Funkcija, lai iestatu dziesmu, kas tiks atskaņota
    function setTrack(trackId, newPlaylist, play) {

        if(newPlaylist != currentPlaylist) {
            currentPlaylist = newPlaylist;
            shufflePlaylist = currentPlaylist.slice();
            shuffleArray(shufflePlaylist); // Sajauc dziesmu kārtību
        }

        if(shuffle == true) {
            currentIndex = shufflePlaylist.indexOf(trackId); // Ja ir izlase, atrod dziesmas pozīciju sajauktajā sarakstā
        }
        else {
            currentIndex = currentPlaylist.indexOf(trackId); // Atrod dziesmas pozīciju sākotnējā sarakstā
        }

        $.post("includes/handlers/ajax/getSongJson.php", {songId: trackId}, function(data) { 

            var track = JSON.parse(data);
            $(".trackName span").text(track.title); // Iestata dziesmas nosaukumu

            $.post("includes/handlers/ajax/getArtistJson.php", {artistId: track.artist}, function(data) {
                var artist = JSON.parse(data);
                $(".artistName span").text(artist.name); // Iestata mākslinieka nosaukumu
                
            });
            $.post("includes/handlers/ajax/getAlbumJson.php", {albumId: track.album}, function(data) {
                var album = JSON.parse(data);
                $(".albumLink img").attr("src", album.artworkPath); // Iestata albuma attēlu
            });
                audioElement.setTrack(track);

                if(play == true) {
                playSong(); // Ja iestatīts atskaņot, sāk atskaņot dziesmu
            }
        });

        
    }
    // Funkcija, lai atskaņotu dziesmu un nomaina atskaņošanas ikonu uz pauzes ikonu
    function playSong() {
        
        // Pārbauda, vai dziesma ir sākusies no sākuma (0 sekundēm)
        if(audioElement.audio.currentTime == 0) {
            // Ja dziesma sākas no sākuma, nosūta POST pieprasījumu uz serveri,
            // lai atjauninātu dziesmas atskaņojumu skaitu datu bāzē
            $.post("includes/handlers/ajax/updatePlays.php", {songId: audioElement.currentlyPlaying.id});
        }

        $(".controlButton.play").hide();
        $(".controlButton.pause").show();
        audioElement.play(); // Atskaņo dziesmu
    }
     // Funkcija, kas aptur dziesmu un nomaina pauzes ikonu uz atskaņošanas ikonu
    function pauseSong() {
        $(".controlButton.play").show();
        $(".controlButton.pause").hide();
        audioElement.pause(); // Aptur dziesmu
    }
</script>

<div id="nowPlayingBarContainer">
    <div id="nowPlayingBar">
        <div id="nowPlayingLeft">
            <div class="content">
                <span class="albumLink">
                    <img src="" role="link" tabindex="0" onclick="openPage('album.php?id=' + audioElement.currentlyPlaying.album)" class="albumArtwork">
                </span>

                <div class="trackInfo">
                    <span class="trackName">
                        <span role="link" tabindex="0" onclick="openPage('song.php?id=' + audioElement.currentlyPlaying.id)"></span>
                    </span>

                    <span class="artistName">
                        <span role="link" tabindex="0" onclick="openPage('artist.php?id=' + audioElement.currentlyPlaying.artist)"></span></span>
                    </span>
                </div>
            </div>
        </div>  

        <div id="nowPlayingCenter">
            <div class="content playerControls">
                
                <div class="buttons">
                    <button class="controlButton shuffle" title="Shuffle button" onclick="setShuffle()">
                        <img src="assets/images/icons/shuffle.png" alt="Shuffle">
                    </button>
                    <button class="controlButton previous" title="Previous button" onclick="prevSong()">
                        <img src="assets/images/icons/previous.png" alt="Previous">
                    </button>
                    <button class="controlButton play" title="Play button" onclick="playSong()">
                        <img src="assets/images/icons/play.png" alt="Play">
                    </button>
                    <button class="controlButton pause" title="Pause button" style="display: none;" onclick="pauseSong()">
                        <img src="assets/images/icons/pause.png" alt="Pause">
                    </button>
                    <button class="controlButton next" title="Next button" onclick="nextSong()">
                        <img src="assets/images/icons/next.png" alt="Next">
                    </button>
                    <button class="controlButton repeat" title="Repeat button" onclick="setRepeat()">
                        <img src="assets/images/icons/repeat.png" alt="Repeat">
                    </button>
                </div>
                <div class="playbackBar">
                    <span class="progressTime current">0.00</span>

                    <div class="progressBar">
                        <div class="progressBarBg">
                            <div class="progress"></div>
                        </div>
                    </div>

                    <span class="progressTime remaining">0.00</span>
                </div>

            </div>
        </div>
        
        <div id="nowPlayingRight">
            <div class="volumeBar">
                <button class="controlButton volume" title="Volume button">
                    <img src="assets/images/icons/volume.png" alt="Volume" onclick="setMute()">
                </button>

                <div class="progressBar">
                        <div class="progressBarBg">
                            <div class="progress"></div>
                        </div>
                </div>
            </div>
        </div>   
    </div>
</div>