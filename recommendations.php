<?php
include("includes/includedFiles.php");
?>
<body>
<form id="genreForm">
    <div class="genreSelectContainer borderBottom">
        <select name="genre" id="genre">
            <option value="">Select a genre</option>
            <?php
                $query = mysqli_query($con, "SELECT id, name FROM genres");
                while($row = mysqli_fetch_array($query)) {
                    $id = $row['id'];
                    $name = $row['name'];
                    echo "<option value='$id'>$name</option>";
                }
            ?>
        </select>
    </div>
    <div class="buttonContainer">
        <button type="submit" class="recButton">Process</button>
    </div>
</form>

<div class="trackListContainer borderBottom">
    <ul class="trackList" id="trackList">

    <h2>Recommendations</h2>

    </ul>
</div>

<script>
$(document).ready(function() {
    $("#genreForm").on("submit", function(event) {
        event.preventDefault();
        var genreId = $("#genre").val();
        if (genreId) {
            $.ajax({
                url: "getRecommendations.php",
                type: "POST",
                data: { genre: genreId, userLoggedIn: "<?php echo $userLoggedIn->getUsername(); ?>" },
                success: function(data) {
                    $("#trackList").html(data);
                }
            });
        } else {
            $("#trackList").html("<p>No genre selected or genre is empty.</p>");
        }
    });
});
</script>

</body>
</html>