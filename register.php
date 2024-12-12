<?php
    include("includes/config.php"); // Iekļauj konfigurācijas failu, kas satur savienojuma iestatījumus ar datubāzi
    include("includes/classes/Account.php"); // Iekļauj konta pārvaldības klasi
    include("includes/classes/Constants.php"); // Iekļauj konstantu failu, kas satur iepriekš definētas kļūdu ziņas

    $account = new Account($con); // Izveido Account klases objektu, kas saistīts ar datubāzes savienojumu
    
    include("includes/handlers/register-handler.php"); // Iekļauj reģistrācijas apstrādes skriptu
    include("includes/handlers/login-handler.php"); // Iekļauj pieteikšanās apstrādes skriptu

    function getInputValue($name) { // Funkcija, kas iegūst ievadīto vērtību no POST datiem un parāda to ievades laukos
        if(isset($_POST[$name])) { // Pārbauda, vai ievades lauks ir aizpildīts
            echo $_POST[$name]; // Izvada ievadīto vērtību
        }
    }
?>
<html>
    <head>
        <title>Welcome to JamSwipe!</title> <!-- Lapas nosaukums -->
        <link rel="stylesheet" type="text/css" href="assets/css/register.css"> <!-- Stila lapas saite -->
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script> <!-- Iekļauj jQuery bibliotēku -->
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> <!-- Iekļauj jQuery bibliotēku -->
        <script src="assets/js/register.js"></script> <!-- Iekļauj JavaScript failu, kas saistīts ar reģistrācijas un pieteikšanās darbībām -->
    </head>
    <body>
        <?php 
        if(isset($_POST['registerButton'])) { // Pārbauda, vai tiek nospiesta pogas 'registerButton' (reģistrācijas poga)
            echo '<script>
                $(document).ready(function() { // Izpilda JavaScript kodu pēc lapas ielādes
                    $("#loginForm").hide(); // Slēpj pieteikšanās formu
                    $("#registerForm").show(); // Parāda reģistrācijas formu
                });
            </script>';
        }
        else {
            echo '<script>
                $(document).ready(function() { // Izpilda JavaScript kodu pēc lapas ielādes
                    $("#loginForm").show(); // Parāda pieteikšanās formu
                    $("#registerForm").hide(); // Slēpj reģistrācijas formu
                }); 
            </script>';
        }
        ?>
        
        <div id="background"> <!-- Fons visam lapas saturam -->
            <div id="loginContainer"> <!-- Konteineris pieteikšanās un reģistrācijas formām -->
                <div id="inputContainer"> <!-- Konteineris ievades laukiem -->
                    <form id="loginForm" action="register.php" method="POST"> <!-- Pieteikšanās forma -->
                        <h2>Login into your account!</h2> <!-- Virsraksts pieteikšanās formai -->
                        <p>
                            <?php echo $account->getError(Constants::$loginFailed); ?> <!-- Izvada kļūdu ziņu, ja pieteikšanās neizdevās -->
                            <label for="loginUsername">Username</label> <!-- Lietotājvārda ievades lauks -->
                            <input id="loginUsername" name="loginUsername" type="text" placeholder="e.g Armands Reimanis" value="<?php getInputValue('loginUsername')?>" required> <!-- Ievades lauks lietotājvārdam -->
                        </p>
                        <p>
                            <label for="loginPassword">Password</label> <!-- Paroles ievades lauks -->
                            <input id="loginPassword" name="loginPassword" type="password" placeholder="Your password" required> <!-- Ievades lauks parolei -->
                        </p>
                        <button type="submit" name="loginButton">LOG IN</button> <!-- Pieteikšanās poga -->

                        <div class="hasAccountText">
                            <span id="hideLogin">Don't have an account yet? Signup here.</span> <!-- Teksts, kas piedāvā reģistrēties, ja lietotājs vēl nav reģistrējies -->
                        </div>  
                    </form>

                    <form id="registerForm" action="register.php" method="POST"> <!-- Reģistrācijas forma -->
                        <h2>Create your account!</h2> <!-- Virsraksts reģistrācijas formai -->
                        <p>
                            <?php echo $account->getError(Constants::$usernameCharacters); ?> <!-- Lietotājvārda garuma kļūdas ziņa -->
                            <?php echo $account->getError(Constants::$usernameTaken); ?> <!-- Lietotājvārda jau aizņemts kļūdas ziņa -->
                            <label for="username">Username</label> <!-- Lietotājvārda ievades lauks -->
                            <input id="username" name="username" type="text" placeholder="e.g Armands Reimanis" value="<?php getInputValue('username')?>" required> <!-- Ievades lauks lietotājvārdam -->
                        </p>
                        <p>
                            <?php echo $account->getError(Constants::$emailsDoNotMatch); ?> <!-- E-pasta nesaskaņošanās kļūdas ziņa -->
                            <?php echo $account->getError(Constants::$emailInvalid); ?> <!-- Nepareizs e-pasta formāts kļūdas ziņa -->
                            <?php echo $account->getError(Constants::$emailTaken); ?> <!-- E-pasta jau aizņemts kļūdas ziņa -->
                            <label for="email">e-mail</label> <!-- E-pasta ievades lauks -->
                            <input id="email" name="email" type="email" placeholder="e.g armandsreimanis@inbox.lv" value="<?php getInputValue('email')?>" required> <!-- Ievades lauks e-pastam -->
                        </p>
                        <p>
                            <label for="email2">Confirm email</label> <!-- E-pasta apstiprinājuma ievades lauks -->
                            <input id="email2" name="email2" type="email" placeholder="e.g armandsreimanis@inbox.lv" value="<?php getInputValue('email2')?>" required> <!-- E-pasta apstiprinājuma ievades lauks -->
                        </p>
                        <p>
                            <?php echo $account->getError(Constants::$passwordsDoNotMatch); ?> <!-- Paroles nesaskaņošanās kļūdas ziņa -->
                            <?php echo $account->getError(Constants::$passwordsNotAlphannumeric); ?> <!-- Parolei jābūt alfabēta un cipariem kļūdas ziņa -->
                            <?php echo $account->getError(Constants::$passwordsCharacters); ?> <!-- Paroles garuma kļūdas ziņa -->
                            <label for="password">Password</label> <!-- Paroles ievades lauks -->
                            <input id="password" name="password" type="password" placeholder="Your password" required> <!-- Ievades lauks parolei -->
                        </p>
                        <p>
                            <label for="password2">Confirm password</label> <!-- Paroles apstiprinājuma ievades lauks -->
                            <input id="password2" name="password2" type="password" placeholder="Your password" required> <!-- Paroles apstiprinājuma ievades lauks -->
                        </p>
                        <button type="submit" name="registerButton">SIGN UP</button> <!-- Reģistrācijas poga -->
                        <div class="hasAccountText">
                            <span id="hideRegister">Already have an account? Log in here.</span> <!-- Teksts, kas piedāvā pieteikties, ja lietotājs jau ir reģistrējies -->
                        </div> 
                    </form>
                </div>

                <div id="loginText"> <!-- Teksts labajā pusē -->
                    <h1>Get great music, right now</h1> <!-- Virsraksts -->
                    <h2>Listen to loads of songs for free</h2> <!-- Apakšvirsraksts -->
                    <ul>
                        <li>Discover music you'll fall in love with</li> <!-- Saraksts ar funkcijām -->
                        <li>Create your own playlists</li> <!-- Saraksts ar funkcijām -->
                        <li>Follow artists to keep up to date</li> <!-- Saraksts ar funkcijām -->
                    </ul>
                </div> 
            </div>
        </div> 
    </body>
</html>
