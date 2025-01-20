$(document).ready(function () {
  //izsauks kad lapa-dokuments bus ieladejusies
  $("#hideLogin").click(function () {
    //nospiezhot uz tekstu "Don't have an account yet? Signup here." pasleps login form un paradis register
    $("#loginForm").hide();
    $("#registerForm").show();
  });
  $("#hideRegister").click(function () {
    //nospiezhot uz tekstu "Already have an account? Log in here." pasleps register form un paradis login
    $("#loginForm").show();
    $("#registerForm").hide();
  });
});
document.addEventListener("DOMContentLoaded", function () {
  const password = document.getElementById("password");
  const password2 = document.getElementById("password2");
  const lengthCriteria = document.getElementById("lengthCriteria");
  const alphanumericCriteria = document.getElementById("alphanumericCriteria");
  const matchCriteria = document.getElementById("matchCriteria");

  function validatePassword() {
    const value = password.value;
    const lengthValid = value.length >= 8;
    const alphanumericValid = /[a-zA-Z]/.test(value) && /[0-9]/.test(value);
    const matchValid = value === password2.value;

    lengthCriteria.style.color = lengthValid ? "green" : "";
    alphanumericCriteria.style.color = alphanumericValid ? "green" : "";
    matchCriteria.style.color = matchValid ? "green" : "";
  }

  password.addEventListener("input", validatePassword);
  password2.addEventListener("input", validatePassword);
});
