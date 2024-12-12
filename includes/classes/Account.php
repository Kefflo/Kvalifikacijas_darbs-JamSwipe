<?php
    class Account {

        private $con; // Pārstāv datubāzes savienojumu
        private $errorArray; // Pārstāv kļūdu masīvu, kas satur validācijas kļūdas

        // Klasēs konstruktoru funkcija, kas tiek izsaukta, kad tiek izveidota klase
        public function __construct($con) {
            $this->con = $con; // Saglabā datubāzes savienojumu
            $this->errorArray = array(); // Inicializē kļūdu masīvu
        }

        // Funkcija, lai lietotājs varētu pieteikties
        public function login($un, $pw) {

            $pw = md5($pw); // Parole tiek pārveidota uz MD5 formātu (hashēšana)

            // Izpilda SQL vaicājumu, lai pārbaudītu vai lietotājvārds un parole ir pareizi
            $query = mysqli_query($this->con, "SELECT * FROM users WHERE username='$un' AND password='$pw'");

            // Ja ir atrasts lietotājs, sāk sesiju un pāriet uz sākumlapu
            if(mysqli_num_rows($query) == 1) {
                $_SESSION['userLoggedIn'] = $un; // Saglabā lietotāja informāciju sesijā
                header("Location: index.php"); // Pāradresē uz sākumlapu
                exit(); // Izbeidz izpildi pēc pāradresēšanas
            }
            else {
                // Ja pieteikšanās neizdodas, pievieno kļūdu masīvam
                array_push($this->errorArray, Constants::$loginFailed);
                return false;
            }
        }

        // Funkcija, lai reģistrētu jaunu lietotāju
        public function register($un, $em, $em2, $pw, $pw2) {
            // Validē lietotājvārdu
            $this->validateUsername($un);
            // Validē e-pastus
            $this->validateEmails($em, $em2);
            // Validē paroles
            $this->validatePasswords($pw, $pw2);

            // Ja nav kļūdu, izsauc funkciju, kas ievada lietotāja datus datubāzē
            if(empty($this->errorArray == true)) {
                return $this->insertUserDetails($un, $em, $pw);
            }
            else {
                return false;
            }
        }

        // Funkcija, lai iegūtu kļūdas ziņu
        public function getError($error) {
            // Ja kļūda nav atrasta, tad atgriež tukšu vērtību
            if(!in_array($error, $this->errorArray)) {
                $error = "";
            }
            // Atgriež kļūdas ziņu HTML formātā
            return "<span class='errorMessage'>$error</span>";
        }

        // Funkcija, lai ievadītu lietotāja datus datubāzē
        private function insertUserDetails($un, $em, $pw) {
            $encryptedPw = md5($pw); // Hashē paroli ar MD5
            $profilePic = "assets/images/profile-pics/user.jpg"; // Noklusējuma profila bilde
            $date = date("Y-m-d"); // Pašreizējais datums

            // Izpilda SQL vaicājumu, lai ievadītu jaunu lietotāju datubāzē
            $result = mysqli_query($this->con, "INSERT INTO users VALUES ('', '$un', '$em', '$encryptedPw', '$date', '$profilePic')");
            return $result; // Atgriež vaicājuma izpildes rezultātu
        }

        // Funkcija, lai validētu lietotājvārdu
        private function validateUsername($un) {
            if(strlen($un) > 25 || strlen($un) < 5) { // Ja lietotājvārds ir garāks par 25 vai īsāks par 5 simboliem
                array_push($this->errorArray, Constants::$usernameCharacters); // Pievieno kļūdu masīvam
                return;
            }

            // Izpilda SQL vaicājumu, lai pārbaudītu, vai lietotājvārds jau ir aizņemts
            $checkUsernameQuery = mysqli_query($this->con, "SELECT username FROM users WHERE username='$un'");
            if(mysqli_num_rows($checkUsernameQuery) != 0) { // Ja lietotājvārds jau eksistē datubāzē
                array_push($this->errorArray, Constants::$usernameTaken); // Pievieno kļūdu masīvam
                return;
            }
        }

        // Funkcija, lai validētu e-pastus
        private function validateEmails($em, $em2) {
            if($em != $em2) { // Ja e-pasti nesakrīt
                array_push($this->errorArray, Constants::$emailsDoNotMatch); // Pievieno kļūdu masīvam
                return;
            }
            // Pārbauda, vai e-pasts ir derīgs
            if(!filter_var($em, FILTER_VALIDATE_EMAIL)) {
                array_push($this->errorArray, Constants::$emailInvalid); // Ja e-pasts nav derīgs, pievieno kļūdu
                return;
            }

            // Izpilda SQL vaicājumu, lai pārbaudītu, vai e-pasts jau ir aizņemts
            $checkEmailQuery = mysqli_query($this->con, "SELECT email FROM users WHERE email='$em'");
            if(mysqli_num_rows($checkEmailQuery) != 0) { // Ja e-pasts jau eksistē datubāzē
                array_push($this->errorArray, Constants::$emailTaken); // Pievieno kļūdu masīvam
                return;
            }
        }

        // Funkcija, lai validētu paroles
        private function validatePasswords($pw, $pw2) {
            if($pw != $pw2) { // Ja paroles nesakrīt
                array_push($this->errorArray, Constants::$passwordsDoNotMatch); // Pievieno kļūdu masīvam
                return;
            }
            // Pārbauda, vai parole satur tikai alfabēta un ciparus
            if(preg_match('/[^A-Za-z0-9]/', $pw)) {
                array_push($this->errorArray, Constants::$passwordsNotAlphannumeric); // Ja parole satur citus rakstzīmes, pievieno kļūdu
                return;
            }
            // Ja parole ir pārāk gara vai pārāk īsa
            if(strlen($pw) > 30 || strlen($pw) < 5) {
                array_push($this->errorArray, Constants::$passwordsCharacters); // Pievieno kļūdu masīvam
                return;
            }
        }
    }
?>
