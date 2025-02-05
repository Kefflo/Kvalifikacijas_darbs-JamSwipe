$(document).ready(function () {
  //izsauks kad lapa-dokuments bus ieladejusies.
  $("#hideLogin").click(function () {
    //nospiezhot uz tekstu "Don't have an account yet? Signup here." pasleps login form un paradis register.
    $("#loginForm").hide();
    $("#registerForm").show();
  });
  $("#hideRegister").click(function () {
    //nospiezhot uz tekstu "Already have an account? Log in here." pasleps register form un paradis login.
    $("#loginForm").show();
    $("#registerForm").hide();
  });
});
// Kad lapa ir pilnībā ielādēta, tiek izpildīta funkcija.
document.addEventListener("DOMContentLoaded", function () {
  // Iegūst paroles ievades laukus un kritēriju elementus pēc to ID.
  const password = document.getElementById("password");
  const password2 = document.getElementById("password2");
  const lengthCriteria = document.getElementById("lengthCriteria");
  const alphanumericCriteria = document.getElementById("alphanumericCriteria");
  const matchCriteria = document.getElementById("matchCriteria");

  // Funkcija, kas validē paroli pēc noteiktiem kritērijiem.
  function validatePassword() {
    const value = password.value; // Iegūst galvenās paroles vērtību.
    const lengthValid = value.length >= 8; // Pārbauda, vai parole ir vismaz 8 simbolus gara.
    const alphanumericValid = /[a-zA-Z]/.test(value) && /[0-9]/.test(value); // Pārbauda, vai parole satur gan burtus, gan ciparus.
    const matchValid = value === password2.value; // Pārbauda, vai abi paroles lauki sakrīt.

    // Maina kritēriju teksta krāsu uz zaļu, ja nosacījums ir izpildīts, noklusējuma krāsa ir balta.
    lengthCriteria.style.color = lengthValid ? "green" : "";
    alphanumericCriteria.style.color = alphanumericValid ? "green" : "";
    matchCriteria.style.color = matchValid ? "green" : "";
  }

  // Pievieno klausītājus, kas izpilda paroles validāciju, kad lietotājs ievada datus.
  password.addEventListener("input", validatePassword);
  password2.addEventListener("input", validatePassword);
});
