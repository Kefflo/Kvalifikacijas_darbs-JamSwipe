<div id="navBarContainer"> <!-- Izveido galvenes konteineru, kurā tiks izvietota navigācijas josla -->
    <nav class="navBar">  <!-- Definē navigācijas joslu ar klasi 'navBar' -->

        <span role="link" tabindex="0" onclick="openPage('index.php')" class="logo"> <!-- Logotips, kas kalpo kā saite uz sākumlapu -->
            <img src="assets/images/icons/logo.png" alt="JamSwipe"> <!-- Attēls ar logotipu -->
        </span>

        <div class="group">  <!-- Grupa, kurā tiek izvietoti saites uz dažādām lapām -->
            <div class="navItem">  <!-- Atsevišķa izvēlnes vienība -->
                <span role='link' tabindex='0' onclick="openPage('search.php')" class="navItemLink">Search</span> <!-- Saite uz meklēšanas lapu -->
                <img src="assets/images/icons/search.png" class="icon" alt="Search"> <!-- Ikona, kas norāda uz meklēšanu -->
            </div>
        </div>

        <div class="group">  <!-- Otra grupa ar vairākām saistītām saites vienībām -->
            <div class="navItem">  <!-- Atsevišķa izvēlnes vienība -->
                <span role="link" tabindex="0" onclick="openPage('browse.php')" class="navItemLink">Browse</span> <!-- Saite uz pārlūkošanas lapu -->
            </div>
            <div class="navItem">  <!-- Atsevišķa izvēlnes vienība -->
                <span role="link" tabindex="0" onclick="openPage('yourMusic.php')" class="navItemLink">Your Music</span> <!-- Saite uz jūsu mūzikas lapu -->
            </div>
            <div class="navItem">  <!-- Atsevišķa izvēlnes vienība -->
                <span role="link" tabindex="0" onclick="openPage('profile.php')" class="navItemLink">Your Profile</span> <!-- Saite uz lietotāja profilu lapu -->
            </div>
        </div>

    </nav>
</div>
