<?php
class Artist {

    private $con;
    private $id; // Izpildītāja id.
    private $name; // Izpildītāja nosaukums/vārds.

    // Konstruktors inicializē datubāzes savienojumu un izpildītāja ID.
    public function __construct($con, $id) {
        $this->con = $con; // Saglabā datubāzes savienojumu.
        $this->id = $id;   // Saglabā izpildītāja ID.
    }

    // Funkcija, kas atgriež izpildītāja vārdu no datubāzes.
    public function getName() {
        $artistQuery = mysqli_query($this->con, "SELECT name FROM artists WHERE id='$this->id'");
        $artist = mysqli_fetch_array($artistQuery); // Iegūst rezultātu kā masīvu.
        return $artist['name']; // Atgriež izpildītāja vārdu.
    }

    // Funkcija, kas atgriež izpildītāja dziesmu ID masīvā, sakārtotus pēc atskaņošanas reižu skaita dilstošā secībā.
    public function getSongIds() {
        $query = mysqli_query($this->con, "SELECT id FROM songs WHERE artist='$this->id' ORDER BY plays DESC");
        $array = array(); // Tukšs masīvs dziesmu ID glabāšanai.

        // Cikls, kas pievieno visus atrastos dziesmu ID masīvam.
        while ($row = mysqli_fetch_array($query)) {
            array_push($array, $row['id']);
        }

        return $array; // Atgriež dziesmu ID masīvu.
    }

    // Funkcija, kas atgriež izpildītāja ID.
    public function getId() {
        return $this->id;
    }
}
?>
