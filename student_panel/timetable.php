
<?php include("../assets/noSessionRedirect.php"); ?>
<?php include("./verifyRoleRedirect.php"); ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Dashboard</title>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Sharp" rel="stylesheet">
    <link rel="shortcut icon" href="../images/aaa.jfif">
    <link rel="stylesheet" href="style.css">
    
</head>
<style>
    
    .logo {
    display: flex;
    align-items: center;
    gap: 10px; /* Espace entre l'image et le texte */
    font-size: 1.5em; /* Augmente la taille du texte */
}

 

.logo h2 {
    font-size: 2em; /* Augmente la taille du titre */
    font-weight: bold;
}

.logo img {
    width: 100px; /* Augmente la taille de l'image */
    height: auto;
     
}
  
</style>
<body>
    <header>
        <div class="logo">
            <img src="../images/aaa.jfif" alt="">
            <h2>I<span class="danger">SG</span>I</h2>
        </div>
     
        
        <?php include "navbar.php" ?>

        <div id="profile-btn" style="display: none;">
            <span class="material-icons-sharp">person</span>
        </div>
        <div class="theme-toggler">
            <span class="material-icons-sharp active">light_mode</span>
            <span class="material-icons-sharp">dark_mode</span>
        </div>
        
    </header>

    <main style="margin: 0;">
        <div class="timetable active" id="timetable">
            <div>
                <span id="prevDay">&lt;</span>
                <h2>Today's Timetable</h2>
                <span id="nextDay">&gt;</span>
            </div>
            <table>
                <thead>
                    <tr>
                        <th>Start Time</th>
                        <th>End Time</th>
                        <th>Subject</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
    </main>

</body>

<script src="timeTable.js"></script>
<script src="app.js"></script>
</html>