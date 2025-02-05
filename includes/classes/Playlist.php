<?php
// Klase Playlist pārstāv atskaņošanas sarakstu un ar to saistīto informāciju.
class Playlist {

    // Privātie mainīgie: datubāzes savienojums, atskaņošanas saraksta ID, nosaukums un īpašnieks.
    private $con;
    private $id;
    private $name;
    private $owner;

    // Konstruktors inicializē atskaņošanas saraksta datus, balstoties uz ID vai datu masīvu.
    public function __construct($con, $data) {
        // Ja ievadītie dati nav masīvs, iegūst atskaņošanas saraksta informāciju no datubāzes.
        if (!is_array($data)) {
            $query = $con->query("SELECT * FROM playlists WHERE id='$data'");
            $data = $query->fetch_array();
        }

        $this->con = $con;     // Saglabā datubāzes savienojumu.
        $this->id = $data['id'];   // Saglabā atskaņošanas saraksta ID.
        $this->name = $data['name']; // Saglabā atskaņošanas saraksta nosaukumu.
        $this->owner = $data['owner']; // Saglabā atskaņošanas saraksta īpašnieku.
    }

    // Funkcija, kas atgriež atskaņošanas saraksta ID.
    public function getId() {
        return $this->id;
    }

    // Funkcija, kas atgriež atskaņošanas saraksta nosaukumu.
    public function getName() {
        return $this->name;
    }

    // Funkcija, kas atgriež atskaņošanas saraksta īpašnieka vārdu.
    public function getOwner() {
        return $this->owner;
    }

    // Funkcija, kas atgriež dziesmu skaitu atskaņošanas sarakstā.
    public function getNumberOfSongs() {
        return $this->con->query("SELECT songId FROM playlistSongs WHERE playlistId='$this->id'")->num_rows;
    }

    // Funkcija, kas atgriež visus dziesmu ID no atskaņošanas saraksta sakārtotus pēc kārtības.
    public function getSongIds() {
        // Izpilda SQL vaicājumu, lai iegūtu dziesmu ID pēc atskaņošanas kārtības.
        $query = mysqli_query($this->con, "SELECT songId FROM playlistSongs WHERE playlistId='$this->id' ORDER BY playlistOrder ASC");
        $array = array(); // Inicializē tukšu masīvu.

        // Pievieno katru atrasto dziesmas ID masīvam.
        while ($row = mysqli_fetch_array($query)) {
            array_push($array, $row['songId']);
        }

        return $array; // Atgriež dziesmu ID masīvu.
    }

    // Statiskā funkcija, kas ģenerē atskaņošanas sarakstu izvēlnes HTML, lai lietotājs varētu pievienot dziesmu sarakstam.
    public static function getPlaylistsDropdown($con, $username) {
        $dropdown = '<select class="item playlist">
                        <option value="">Add to playlist</option>';
        // SQL vaicājums, kas iegūst lietotāja izveidotos atskaņošanas sarakstus.
        $query = mysqli_query($con, "SELECT id, name FROM playlists WHERE owner='$username'");

        // Pievieno katru atskaņošanas sarakstu izvēlnei kā opciju.
        while ($row = mysqli_fetch_array($query)) {
            $id = $row['id'];
            $name = $row['name'];
            $dropdown .= "<option value='$id'>$name</option>";
        }

        return $dropdown . "</select>"; // Atgriež izveidoto izvēlni.
    }
}
?>
