<?php
include("config.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
   
    $json_data = file_get_contents("php://input");
    $dataObject = json_decode($json_data, true);

    $uniqueId = "T" . time(); 
    $nom = $dataObject["nom"];
    $departement = $dataObject["departement"];
    $numero = $dataObject["numero"];
    $email = $dataObject["email"];
    

    // Use prepared statement to check if the email already exists
    $sql = "SELECT * FROM staff WHERE email=?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "s", $email);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if (mysqli_num_rows($result) > 0) {
        echo 'Email already exists!';
    } else {

    if ($nom !== "" && $email != "") {

        // Use prepared statements for inserting teacher details
        $addTeacherDetailQuery = "INSERT INTO `staff` (`s_no`, `id`, `nom`, `departement`, `numero`,`email`) VALUES (NULL,?, ?, ?, ?, ?)";
        $stmt = mysqli_prepare($conn, $addTeacherDetailQuery);
        mysqli_stmt_bind_param($stmt, "sssss", $uniqueId, $nom, $departement, $numero,$email );
        mysqli_stmt_execute($stmt);
        echo 'success';
    }

    }

} else {
    echo "Invalid request!";
}
?>
