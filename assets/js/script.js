var currentPlaylist = []; // Mainīgais, kas satur pašreizējo atskaņošanas sarakstu
var shufflePlaylist = []; // Mainīgais, kas satur sajaukto atskaņošanas sarakstu
var tempPlaylist = []; // Pagaidu atskaņošanas saraksts, kur tiek glabāti dziesmu ID
var audioElement; // Mainīgais, kas satur audio elementu
var mouseDown = false; // Mainīgais, kas norāda, vai peles taustiņš ir nospiests
var currentIndex = 0; // Mainīgais, kas norāda pašreizējo dziesmas indeksu
var repeat = false; // Mainīgais, kas norāda, vai atkārtot dziesmu
var shuffle = false; // Mainīgais, kas norāda, vai dziesmas tiek sajauktas
var userLoggedIn; // Mainīgais, kas satur pašreizējā lietotāja informāciju
var timer; // Mainīgais, kas satur taimeri

// Funkcija, lai atvērtu lapu ar nosūtīto URL
function openPage(url) {
    if(timer != null) { // Ja timeris ir iestatīts, to notīra
        clearTimeout(timer);
    }

    // Pārliecinās, ka URL satur "?", ja nav, pievieno
    if(url.indexOf("?") == -1) {
        url = url + "?";    
    }

    // Šifrē URL, pievienojot pašreizējā lietotāja informāciju
    var encodedUrl = encodeURI(url + "&userLoggedIn=" + userLoggedIn);
    
    // Ielādē galvenā satura daļu ar šo URL
    $("#mainContent").load(encodedUrl);
    
    // Savelk lapas skatu uz augšu
    $("#body").scrollTop(0);
    
    // Pievieno šo URL pārlūkprogrammas vēsturē
    history.pushState(null, null, url);
}

// Funkcija, lai izveidotu jaunu atskaņošanas sarakstu
function createPlaylist() {
    var popup = prompt("Please enter playlist name", ""); // Uzliek uzvedni, lai lietotājs ievadītu saraksta nosaukumu
    if(popup != null) { // Ja lietotājs ievada nosaukumu
        $.post("includes/handlers/ajax/createPlaylist.php", {name: popup, username: userLoggedIn}) // Nosūta datus uz serveri
        .done(function() {
            // Ja nosaukums ir tukšs, izvada kļūdu
            if(popup != "") {
                alert(error);
                return;
            }
            // Atver lietotāja mūzikas lapu pēc atskaņošanas saraksta izveidošanas
            openPage("yourMusic.php");
        });
    }
}

// Funkcija laika formātam (minūtes:sekundes) konvertēšanai
function formatTime(seconds) {
    var time = Math.round(seconds); // Pārveido sekundes uz veselu skaitli
    var minutes = Math.floor(time / 60); // Aprēķina minūtes
    var seconds = time - (minutes * 60); // Aprēķina atlikušās sekundes

    var extraZero; // Mainīgais papildu nulles pievienošanai
    if(seconds < 10) {
        extraZero = "0"; // Ja sekundes ir mazāk nekā 10, pievieno nulli priekšā
    }
    else {
        extraZero = ""; // Pretējā gadījumā nepiemēro nulli
    }

    return minutes + ":" + extraZero + seconds; // Atgriež laiku formātā "minūtes:sekundes"
}

// Funkcija, lai atjauninātu laika progresu audio atskaņošanā
function updateTimeProgressBar(audio) {
    $(".progressTime.current").text(formatTime(audio.currentTime)); // Attēlo pašreizējo atskaņotā laiku
    $(".playbackBar .progress").css("width", (audio.currentTime / audio.duration) * 100 + "%"); // Atjaunina progresu barā
}

// Funkcija, lai atjauninātu skaļuma progresu
function updateVolumeProgressBar(audio) {
    $(".volumeBar .progress").css("width", audio.volume * 100 + "%"); // Atjaunina skaļuma progresu barā
}

// Funkcija, lai atskaņotu pirmo dziesmu no saraksta
function playFirstSong() {
    setTrack(tempPlaylist[0], tempPlaylist, true); // Iestata un atskaņo pirmo dziesmu
}

// Audio funkcija, kas pārstāv audio atskaņošanas procesu
function Audio() {
    this.currentlyPlaying; // Mainīgais pašreizējai atskaņotajai dziesmai
    this.audio = document.createElement('audio'); // Izveido jaunu audio elementu

    // Kad dziesma ir beigusies, atskaņo nākamo
    this.audio.addEventListener("ended", function() {
        nextSong();
    });

    // Kad audio ir gatavs atskaņošanai, parāda dziesmas ilgumu
    this.audio.addEventListener("canplay", function() {
        var duration = formatTime(this.duration);
        $(".progressTime.remaining").text(duration);
    });

    // Atjaunina progresu laika gaitā
    this.audio.addEventListener("timeupdate", function(){
        if(this.duration) {
            updateTimeProgressBar(this);
        }
    });

    // Atjaunina skaļumu, kad tas tiek mainīts
    this.audio.addEventListener("volumechange", function(){
        if(this.duration) {
            updateVolumeProgressBar(this);
        }
    });

    // Funkcija, lai iestatītu dziesmu
    this.setTrack = function(track) {
        this.currentlyPlaying = track;
        this.audio.src = track.path; // Iestata audio avotu uz dziesmas ceļu
    }

    // Funkcija, lai atskaņotu dziesmu
    this.play = function() {
        this.audio.play(); // Atskaņo audio
    }

    // Funkcija, lai pauzētu dziesmu
    this.pause = function() {
        this.audio.pause(); // Pauzē audio
    }

    // Funkcija, lai iestatītu dziesmas laiku (sekundes)
    this.setTime = function(seconds) {
        this.audio.currentTime = seconds; // Iestata audio pašreizējo laiku
    }
}
