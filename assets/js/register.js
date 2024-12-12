$(document).ready(function() { //izsauks kad lapa-dokuments bus ieladejusies
        $("#hideLogin").click(function() { //nospiezhot uz tekstu "Don't have an account yet? Signup here." pasleps login form un paradis register
            $("#loginForm").hide();
            $("#registerForm").show();
        });
        $("#hideRegister").click(function() { //nospiezhot uz tekstu "Already have an account? Log in here." pasleps register form un paradis login
            $("#loginForm").show();
            $("#registerForm").hide();
        });
});