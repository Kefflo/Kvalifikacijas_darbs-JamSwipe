<?php
    class User {

        private $con;
        private $username; // Lietotāja vārds.

        // Konstruktors ievāc datubāzes savienojumu un lietotāja vārdu.
        public function __construct($con, $username) {
            $this->con = $con; // Saglabā datu bāzes savienojumu.
            $this->username = $username; // Saglabā lietotāja vārdu.
        }
        // Funkcija atgriež lietotāja vārdu.
        public function getUsername() {
            return $this->username;
        }
        // Funkcija atgriež lietotāja epastu.
        public function getEmail() {
            $query = mysqli_query($this->con, "SELECT email FROM users WHERE username='$this->username'"); // Veic pieprasījumu, ievācot lietotāja e-pastu.
            $row = mysqli_fetch_array($query); // Pievieno datus masīvam.
            return $row['email']; //  Atgriež atrastos datus.
        }  
    }
?>