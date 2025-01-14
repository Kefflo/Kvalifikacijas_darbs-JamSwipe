<?php
    class Genre {
        private $con;
        private $id;
        private $name;

        public function __construct($con, $id) {
            $this->con = $con;
            $this->id = $id;

            $query = mysqli_query($this->con, "SELECT * FROM genres WHERE id='$this->id'");
            $genre = mysqli_fetch_array($query);
            $this->name = $genre['name'];
        }


        public function getSongs() {
            $query = mysqli_query($this->con, "SELECT id FROM songs WHERE genre='$this->id'");
            $songArray = array();
            while($row = mysqli_fetch_array($query)) {
                array_push($songArray, $row['id']);
            }
            return $songArray;
        }
    }
?>