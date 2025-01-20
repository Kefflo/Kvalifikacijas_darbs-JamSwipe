<?php
class Genre {
    // Privātie mainīgie: datubāzes savienojums, žanra ID un nosaukums
    private $con;
    private $id;
    private $name;

    // Konstruktors inicializē datubāzes savienojumu un žanra ID, kā arī iestata žanra nosaukumu
    public function __construct($con, $id) {
        $this->con = $con; // Saglabā datubāzes savienojumu
        $this->id = $id;   // Saglabā žanra ID

        // Veic pieprasījumu, lai iegūtu žanra informāciju pēc ID
        $query = mysqli_query($this->con, "SELECT * FROM genres WHERE id='$this->id'");
        $genre = mysqli_fetch_array($query); // Iegūst rezultātu masīvā
        $this->name = $genre['name']; // Saglabā žanra nosaukumu
    }

    // Funkcija atgriež masīvu ar žanram atbilstošo dziesmu ID
    public function getSongs() {
        // Veic pieprasījumu, lai iegūtu visas dziesmas, kas saistītas ar žanra ID
        $query = mysqli_query($this->con, "SELECT id FROM songs WHERE genre='$this->id'");
        $songArray = array(); // Izveido tukšu masīvu dziesmu ID glabāšanai

        // Iterē caur katru rezultātu rindu un pievieno ID masīvam
        while($row = mysqli_fetch_array($query)) {
            array_push($songArray, $row['id']);
        }
        return $songArray; // Atgriež dziesmu ID masīvu
    }
}
?>
