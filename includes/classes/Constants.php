<?php
// Constants klase, kas glabā statiskas kļūdu ziņas
class Constants {

    // Kļūda, ja paroles nesakrīt
    public static $passwordsDoNotMatch = "Your passwords don't match";

    // Kļūda, ja parole satur neatļautus simbolus (tikai burti un cipari ir atļauti)
    public static $passwordsNotAlphannumeric = "Your password can only contain numbers and letters";

    // Kļūda, ja parole nav noteiktā garuma diapazonā (5 līdz 30 simboli)
    public static $passwordsCharacters = "Your password must be between 5 and 30 characters";

    // Kļūda, ja e-pasta formāts ir nepareizs
    public static $emailInvalid = "Email is invalid";

    // Kļūda, ja e-pasti nesakrīt
    public static $emailsDoNotMatch = "Your emails don't match";

    // Kļūda, ja e-pasts jau tiek izmantots
    public static $emailTaken = "This email is already in use";

    // Kļūda, ja lietotājvārds nav noteiktā garuma diapazonā (5 līdz 25 simboli)
    public static $usernameCharacters = "Your username must be between 5 and 25 characters";

    // Kļūda, ja lietotājvārds jau eksistē
    public static $usernameTaken = "This username already exists";

    // Kļūda, ja lietotājvārds vai parole ir nepareiza (pieteikšanās kļūda)
    public static $loginFailed = "Your username or password was incorrect";
}
?>
