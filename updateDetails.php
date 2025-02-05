<?php
// Iekļauj nepieciešamos failus un sesijas informāciju.
include("includes/includedFiles.php");
?>

<!-- Lietotāja iestatījumu sadaļa -->
<div class="userDetails">

    <!-- Lietotājvārda maiņas konteiners -->
    <div class="container borderBottom">
        <h2>Username</h2>
        <!-- Lietotājvārda ievades lauks ar pašreizējo lietotājvārdu -->
        <input type="text" class="username" name="username" placeholder="username" value="<?php echo $userLoggedIn->getUsername(); ?>" />
        <!-- Ziņojumu vieta (piemēram, kļūdām vai apstiprinājumiem) -->
        <span class="message"></span>
        <!-- Poga lietotājvārda saglabāšanai -->
        <button class="button" onclick="updateUsername('username')">Save</button>
    </div>
        
    <!-- Paroles maiņas konteiners -->
    <div class="container">
        <h2>PASSWORD</h2>
        <!-- Ievades lauks vecajai parolei -->
        <input type="password" class="oldPassword" name="oldPassword" placeholder="Current password">
        <!-- Ievades lauks jaunajai parolei -->
        <input type="password" class="newPassword1" name="newPassword1" placeholder="New password">
        <!-- Ievades lauks jaunās paroles apstiprināšanai -->
        <input type="password" class="newPassword2" name="newPassword2" placeholder="Confirm password">
        <!-- Ziņojumu vieta kļūdām vai apstiprinājumiem -->
        <span class="message"></span>
        <!-- Poga paroles saglabāšanai -->
        <button class="button" onclick="updatePassword('oldPassword', 'newPassword1', 'newPassword2')">Save</button>
    </div>

</div>

<!-- JavaScript mainīgais, kas saglabā pašreizējo lietotājvārdu -->
<script>
    var userLoggedIn = "<?php echo $userLoggedIn->getUsername(); ?>";
</script>
