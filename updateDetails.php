<?php
include("includes/includedFiles.php");
?>

<div class="userDetails">
    <div class="container borderBottom">
        <h2>Username</h2>
        <input type="text" class="username" name="username" placeholder="username" value="<?php echo $userLoggedIn->getUsername(); ?>" />
        <span class="message"></span>
        <button class="button" onclick="updateUsername('username')">Save</button>
    </div>
        
    <div class="container">
        <h2>PASSWORD</h2>
        <input type="password" class="oldPassword" name="oldPassword" placeholder="Current password"></input>
        <input type="password" class="newPassword1" name="newPassword1" placeholder="New password"></input>
        <input type="password" class="newPassword2" name="newPassword2" placeholder="Confirm password"></input>
        <span class="message"></span>
        <button class="button" onclick="updatePassword('oldPassword', 'newPassword1', 'newPassword2')">Save</button>
    </div>
</div>

<script>
    var userLoggedIn = "<?php echo $userLoggedIn->getUsername(); ?>";
</script>