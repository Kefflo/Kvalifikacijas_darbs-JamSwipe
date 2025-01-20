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

// Dokumenta noklikšķināšanas notikums
$(document).click(function (click) {
  // Iegūst elementu, uz kura tika uzklikšķināts
  var target = $(click.target);

  // Pārbauda, vai klikšķa mērķis nav elements ar klasi "item" vai "optionsButton"
  if (!target.hasClass("item") && !target.hasClass("optionsButton")) {
    hideOptionsMenu(); // Paslēpj opciju izvēlni
  }
});

// Loga ritināšanas notikums
$(window).scroll(function () {
  hideOptionsMenu(); // Paslēpj opciju izvēlni, ja logs tiek ritināts
});

// Notikums, kas izpildās, kad tiek mainīta izvēlne ar klasi "playlist"
$(document).on("change", "select.playlist", function () {
  var select = $(this); // Iegūst pašreizējo izvēlni
  var playlistId = select.val(); // Iegūst izvēlēto atskaņošanas saraksta ID
  var songId = select.prev(".songId").val(); // Iegūst dziesmas ID no iepriekšējā elementa

  // Nosūta POST pieprasījumu serverim, lai pievienotu dziesmu atskaņošanas sarakstam
  $.post("includes/handlers/ajax/addToPlaylist.php", {
    playlistId: playlistId, // Atskaņošanas saraksta ID
    songId: songId, // Dziesmas ID
  }).done(function (error) {
    // Ja serveris atgriež kļūdas ziņojumu
    if (error != "") {
      alert(error); // Parāda kļūdas paziņojumu lietotājam
      return; // Beidz funkcijas izpildi
    }
    hideOptionsMenu(); // Paslēpj opciju izvēlni
    select.val(""); // Atiestata izvēlni
  });
});

// Funkcija, lai atvērtu lapu ar nosūtīto URL
function openPage(url) {
  if (timer != null) {
    // Ja timeris ir iestatīts, to notīra
    clearTimeout(timer);
  }

  // Pārliecinās, ka URL satur "?", ja nav, pievieno
  if (url.indexOf("?") == -1) {
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

// Funkcija noņem dziesmu no atskaņošanas saraksta
function removeFromPlaylist(button, playlistId) {
  // Iegūst dziesmas ID no pogas iepriekšējās elementa vērtības
  var songId = $(button).prevAll(".songId").val();

  // Nosūta POST pieprasījumu serverim, lai noņemtu dziesmu no saraksta
  $.post("includes/handlers/ajax/removeFromPlaylist.php", {
    playlistId: playlistId, // Atskaņošanas saraksta ID
    songId: songId, // Dziesmas ID
  }).done(function (error) {
    // Ja serveris atgriež kļūdas ziņojumu
    if (error != "") {
      alert(error); // Parāda kļūdas paziņojumu lietotājam
      return; // Beidz funkcijas izpildi
    }

    // Ja nav kļūdu, pāriet uz atskaņošanas saraksta lapu
    openPage("playlist.php?id=" + playlistId);
  });
}

// Funkcija iziet no lietotāja konta
function logout() {
  // Nosūta POST pieprasījumu serverim, lai izietu no konta
  $.post("includes/handlers/ajax/logout.php", function () {
    location.reload(); // Pārlādē lapu pēc iziešanas
  });
}

// Funkcija atjaunina lietotāja e-pasta adresi
function updateUsername(usernameClass) {
  var usernameValue = $("." + usernameClass).val();

  $.post("includes/handlers/ajax/updateUsername.php", {
    username: usernameValue, 
    userLoggedIn: userLoggedIn, 
  }).done(function (response) {

    $("." + usernameClass)
      .nextAll(".message")
      .text(response);


      if (response.trim() === "Update successful") {
      $("." + usernameClass).val(usernameValue);
      $("#navUsername").text(usernameValue);
      userLoggedIn = usernameValue;
    }
  });
}
// Funkcija atjaunina lietotāja paroli
function updatePassword(
  oldPasswordClass,
  newPasswordClass1,
  newPasswordClass2
) {
  // Iegūst veco un jauno paroli no ievades laukiem
  var oldPassword = $("." + oldPasswordClass).val();
  var newPassword1 = $("." + newPasswordClass1).val();
  var newPassword2 = $("." + newPasswordClass2).val();

  // Nosūta POST pieprasījumu serverim, lai atjauninātu paroli
  $.post("includes/handlers/ajax/updatePassword.php", {
    oldPassword: oldPassword, // Vecā parole
    newPassword1: newPassword1, // Jaunā parole (1. ievade)
    newPassword2: newPassword2, // Jaunā parole (2. ievade)
    username: userLoggedIn, // Pašreizējais lietotāja vārds
  }).done(function (response) {
    // Parāda atbildi blakus vecās paroles ievades laukam
    $("." + oldPasswordClass)
      .nextAll(".message")
      .text(response);
  });
}

// Funkcija, lai izveidotu jaunu atskaņošanas sarakstu
function createPlaylist() {
  var popup = prompt("Please enter playlist name", ""); // Uzliek uzvedni, lai lietotājs ievadītu saraksta nosaukumu
  if (popup != null) {
    // Ja lietotājs ievada nosaukumu
    $.post("includes/handlers/ajax/createPlaylist.php", {
      name: popup,
      username: userLoggedIn,
    }) // Nosūta datus uz serveri
      .done(function () {
        // Ja nosaukums ir tukšs, izvada kļūdu
        if (popup != "") {
          alert(error);
          return;
        }
        // Atver lietotāja mūzikas lapu pēc atskaņošanas saraksta izveidošanas
        openPage("yourMusic.php");
      });
  }
}

// Funkcija dzēš atskaņošanas sarakstu pēc norādītā ID
function deletePlaylist(playlistId) {
  // Lietotājam tiek parādīts apstiprinājuma logs
  var prompt = confirm("Are you sure you want to delete this playlist?");
  if (prompt == true) {
    // Ja lietotājs apstiprina, tiek nosūtīts POST pieprasījums uz serveri, lai dzēstu sarakstu
    $.post("includes/handlers/ajax/deletePlaylist.php", {
      playlistId: playlistId, // Saraksta ID tiek nosūtīts kā parametrs
    }).done(function (error) {
      // Ja serveris atgriež kļūdas ziņojumu
      if (error != "") {
        alert(error); // Parādīt kļūdas paziņojumu lietotājam
        return; 
      }
      // Ja nav kļūdu, pāriet uz lapu "yourMusic.php"
      openPage("yourMusic.php");
    });
  }
}

// Funkcija parāda opciju izvēlni
function showOptionsMenu(button) {
  // Iegūst dziesmas ID no pogas iepriekšējās elementa vērtības
  var songId = $(button).prevAll(".songId").val();
  var menu = $(".optionsMenu"); // Atskaņošanas opciju izvēlnes elements
  var menuWidth = menu.width(); // Nosaka izvēlnes platumu
  menu.find(".songId").val(songId); // Uzstāda dziesmas ID izvēlnē

  // Iegūst ritinājuma un elementa atrašanās vietu logā
  var scrollTop = $(window).scrollTop();
  var elementOffset = $(button).offset().top;

  var top = elementOffset - scrollTop; // Aprēķina izvēlnes vertikālo pozīciju
  var left = $(button).position().left; // Iegūst pogas horizontālo pozīciju

  // Uzstāda izvēlnes pozīciju un parāda to
  menu.css({
    top: top + "px",
    left: left - menuWidth + "px", // Izvieto izvēlni pa kreisi no pogas
    display: "inline", // Parāda izvēlni
  });
}

// Funkcija paslēpj opciju izvēlni
function hideOptionsMenu() {
  var menu = $(".optionsMenu"); // Atskaņošanas opciju izvēlnes elements
  if (menu.css("display") != "none") {
    // Ja izvēlne ir redzama
    menu.css("display", "none"); // Paslēpj izvēlni
  }
}

// Funkcija laika formātam (minūtes:sekundes) konvertēšanai
function formatTime(seconds) {
  var time = Math.round(seconds); // Pārveido sekundes uz veselu skaitli
  var minutes = Math.floor(time / 60); // Aprēķina minūtes
  var seconds = time - minutes * 60; // Aprēķina atlikušās sekundes

  var extraZero; // Mainīgais papildu nulles pievienošanai
  if (seconds < 10) {
    extraZero = "0"; // Ja sekundes ir mazāk nekā 10, pievieno nulli priekšā
  } else {
    extraZero = ""; // Pretējā gadījumā nepiemēro nulli
  }

  return minutes + ":" + extraZero + seconds; // Atgriež laiku formātā "minūtes:sekundes"
}

// Funkcija, lai atjauninātu laika progresu audio atskaņošanā
function updateTimeProgressBar(audio) {
  $(".progressTime.current").text(formatTime(audio.currentTime)); // Attēlo pašreizējo atskaņotā laiku
  $(".playbackBar .progress").css(
    "width",
    (audio.currentTime / audio.duration) * 100 + "%"
  ); // Atjaunina progresu barā
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
  this.audio = document.createElement("audio"); // Izveido jaunu audio elementu

  // Kad dziesma ir beigusies, atskaņo nākamo
  this.audio.addEventListener("ended", function () {
    nextSong();
  });

  // Kad audio ir gatavs atskaņošanai, parāda dziesmas ilgumu
  this.audio.addEventListener("canplay", function () {
    var duration = formatTime(this.duration);
    $(".progressTime.remaining").text(duration);
  });

  // Atjaunina progresu laika gaitā
  this.audio.addEventListener("timeupdate", function () {
    if (this.duration) {
      updateTimeProgressBar(this);
    }
  });

  // Atjaunina skaļumu, kad tas tiek mainīts
  this.audio.addEventListener("volumechange", function () {
    if (this.duration) {
      updateVolumeProgressBar(this);
    }
  });

  // Funkcija, lai iestatītu dziesmu
  this.setTrack = function (track) {
    this.currentlyPlaying = track;
    this.audio.src = track.path; // Iestata audio avotu uz dziesmas ceļu
  };

  // Funkcija, lai atskaņotu dziesmu
  this.play = function () {
    this.audio.play(); // Atskaņo audio
  };

  // Funkcija, lai pauzētu dziesmu
  this.pause = function () {
    this.audio.pause(); // Pauzē audio
  };

  // Funkcija, lai iestatītu dziesmas laiku (sekundes)
  this.setTime = function (seconds) {
    this.audio.currentTime = seconds; // Iestata audio pašreizējo laiku
  };
}
