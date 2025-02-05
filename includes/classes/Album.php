<?php
    class Album {

        private $con; // Pārstāv datubāzes savienojumu.
        private $id; // Albuma ID.
        private $title; // Albuma nosaukums.
        private $artistId; // Mākslinieka ID, kas ir saistīts ar albumu.
        private $genre; // Albuma žanrs.
        private $artworkPath; // Albuma vāka attēla ceļš.

        // Konstruktors, kas ielādē albuma datus no datubāzes, izmantojot albuma ID.
        public function __construct($con, $id) {
            $this->con = $con; // Saglabā datubāzes savienojumu.
            $this->id = $id; // Saglabā albuma ID.

            // Izpilda SQL vaicājumu, lai iegūtu albuma informāciju no datubāzes, izmantojot albuma ID.
            $query = mysqli_query($this->con, "SELECT * FROM albums WHERE id='$this->id'");
            $album = mysqli_fetch_array($query);

            // Saglabā iegūto informāciju par albumu.
            $this->title = $album['title'];
            $this->artistId = $album['artist'];
            $this->genre = $album['genre'];
            $this->artworkPath = $album['artworkPath'];
        }

        // Funkcija, lai iegūtu albuma nosaukumu.
        public function getTitle() {
            return $this->title;
        }

        // Funkcija, lai iegūtu mākslinieka informāciju, kas saistīts ar albumu.
        public function getArtist() {
            return new Artist($this->con, $this->artistId); // Atgriež Artist objektu, izmantojot mākslinieka ID.
        }

        // Funkcija, lai iegūtu albuma žanru.
        public function getGenre() {
            return $this->genre;
        }

        // Funkcija, lai iegūtu albuma vāka attēla ceļu.
        public function getArtworkPath() {
            return $this->artworkPath;
        }

        // Funkcija, lai iegūtu skaitu dziesmām albumā.
        public function getNumberOfSongs() {
            // Izpilda SQL vaicājumu, lai iegūtu dziesmu ID, kas saistītas ar albumu.
            $query = mysqli_query($this->con, "SELECT id FROM songs WHERE album='$this->id'");
            // Atgriež dziesmu skaitu
            return mysqli_num_rows($query);
        }

        // Funkcija, lai iegūtu dziesmu ID sarakstu albumā.
        public function getSongIds() {
            // Izpilda SQL vaicājumu, lai iegūtu dziesmu ID pēc albumu kārtības.
            $query = mysqli_query($this->con, "SELECT id FROM songs WHERE album='$this->id' ORDER BY albumOrder ASC");
            $array = array(); // Inicializē tukšu masīvu.

            // Pievieno katru dziesmas ID masīvam.
            while($row = mysqli_fetch_array($query)) {
                array_push($array, $row['id']);
            }

            // Atgriež dziesmu ID masīvu.
            return $array;
        }
    }
?>
