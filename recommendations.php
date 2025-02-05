<?php
// Iekļauj nepieciešamos failus un sesijas informāciju.
include("includes/includedFiles.php");
?>

<body>

<!-- Formas sākums, lai izvēlētos mūzikas žanru -->
<form id="genreForm">
    <div class="genreSelectContainer borderBottom">
        <select name="genre" id="genre">
            <option value="">Select a genre</option>
            <?php
                // Izgūst žanru sarakstu no datubāzes un pievieno to izvēlnei.
                $query = mysqli_query($con, "SELECT id, name FROM genres");
                while($row = mysqli_fetch_array($query)) {
                    $id = $row['id'];
                    $name = $row['name'];
                    // Izvada katru žanru kā izvēles iespēju dropdown sarakstā.
                    echo "<option value='$id'>$name</option>";
                }
            ?>
        </select>
    </div>

    <!-- Poga žanra izvēles apstiprināšanai -->
    <div class="buttonContainer">
        <button type="submit" class="recButton">Process</button>
    </div>
</form>

<!-- Dziesmu saraksta konteiners -->
<div class="trackListContainer borderBottom">
    <ul class="trackList" id="trackList">
        <h2>Recommendations</h2>
        <!-- Šeit tiks ievietoti ieteiktie skaņdarbi -->
    </ul>
</div>

<script>
// Gaida, kamēr lapa ir ielādēta.
$(document).ready(function() {
    // Kad forma tiek iesniegta.
    $("#genreForm").on("submit", function(event) {
        event.preventDefault(); // Neļauj formai atsvaidzināt lapu.

        var genreId = $("#genre").val(); // Iegūst izvēlēto žanru.

        if (genreId) {
            // Sūta AJAX pieprasījumu, lai iegūtu dziesmu ieteikumus.
            $.ajax({
                url: "getRecommendations.php", // Apstrādes fails.
                type: "POST", // Pieprasījuma metode.
                data: { 
                    genre: genreId, // Nosūta izvēlēto žanru.
                    userLoggedIn: "<?php echo $userLoggedIn->getUsername(); ?>" // Nosūta lietotāja informāciju.
                },
                success: function(data) {
                    // Aizpilda dziesmu sarakstu ar iegūtajiem rezultātiem.
                    $("#trackList").html(data);
                }
            });
        } else {
            // Ja žanrs nav izvēlēts, parāda paziņojumu.
            $("#trackList").html("<p>No genre selected or genre is empty.</p>");
        }
    });
});
</script>

</body>
</html>
