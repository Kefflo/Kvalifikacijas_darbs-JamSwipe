<?php
include("../../config.php");

if(isset($_POST['playlistId']) && isset($_POST['songId'])) {
    $playlistId = $_POST['playlistId'];
    $songId = $_POST['songId'];

    $orderIdQuery = mysqli_query($con, "SELECT COALESCE(MAX(playlistOrder) + 1, 1) as playlistOrder FROM playlistSongs WHERE playlistId='$playlistId'");    
    $row = mysqli_fetch_array($orderIdQuery);
    $orderId = $row['playlistOrder'];

    $query = mysqli_query($con, "INSERT INTO playlistSongs (songId, playlistId, playlistOrder) VALUES ('$songId', '$playlistId', '$orderId')");}
else {
    echo "error";
}
?>