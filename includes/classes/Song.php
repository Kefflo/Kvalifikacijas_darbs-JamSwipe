<?php
// Klase Song pārstāv dziesmu un ar to saistīto informāciju.
class Song {

    private $con;
    private $id; // Dziesmas id.
    private $mysqliData; // Dziesmas datubāzes dati, no kurienes tas viss tiek izvilkts ārā.
    private $title; // Dziesmas nosaukums/vārds.
    private $artistId; // Dziesmas izpildītāja id.
    private $albumId; // Dziesmas albuma id.
    private $genre; // Dziesmas žanrs
    private $duration; // Dziesmas ilgums/garums.
    private $path; // Dziesmas lokācija/atrašanās vieta mapē.

    // Konstruktors inicializē datubāzes savienojumu un dziesmas ID, ielādējot dziesmas datus.
    public function __construct($con, $id) {
        $this->con = $con; // Saglabā datubāzes savienojumu.
        $this->id = $id;   // Saglabā dziesmas ID.

        // Veic pieprasījumu, lai iegūtu dziesmas informāciju pēc ID.
        $query = mysqli_query($this->con, "SELECT * FROM songs WHERE id='$this->id'");
        $this->mysqliData = mysqli_fetch_array($query); // Saglabā rezultātus.

        // Inicializē dziesmas laukus no iegūtajiem datiem.
        $this->title = $this->mysqliData['title'];
        $this->artistId = $this->mysqliData['artist'];
        $this->albumId = $this->mysqliData['album'];
        $this->genre = $this->mysqliData['genre'];
        $this->duration = $this->mysqliData['duration'];
        $this->path = $this->mysqliData['path'];
    }

    // Funkcija atgriež dziesmas nosaukumu.
    public function getTitle() {
        return $this->title;
    }

    // Funkcija atgriež Artist klases objektu, kas pārstāv dziesmas izpildītāju.
    public function getArtist() {
        return new Artist($this->con, $this->artistId);
    }

    // Funkcija atgriež Album klases objektu, kas pārstāv dziesmas albumu.
    public function getAlbum() {
        return new Album($this->con, $this->albumId);
    }

    // Funkcija atgriež dziesmas žanru.
    public function getGenre() {
        return $this->genre;
    }

    // Funkcija atgriež dziesmas ilgumu.
    public function getDuration() {
        return $this->duration;
    }

    // Funkcija atgriež dziesmas ceļu failu sistēmā.
    public function getPath() {
        return $this->path;
    }

    // Funkcija atgriež visus dziesmas datus kā masīvu.
    public function getMysqliData() {
        return $this->mysqliData;
    }

    // Funkcija atgriež dziesmas ID.
    public function getId() {
        return $this->id;
    }
}
?>
