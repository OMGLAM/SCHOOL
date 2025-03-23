<?php

include("config.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
   
    $json_data = file_get_contents("php://input");
    $dataObject = json_decode($json_data, true);
    
    $id = $dataObject["id"];
    $nom = $dataObject["nom"];
    $email = $dataObject["email"];
    $numero = $dataObject["numero"];
    $departement = $dataObject["departement"];
    

  
 
    $sql = "SELECT * FROM staff WHERE id=?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "s", $id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    
    if(mysqli_num_rows($result) > 0){
        $query = "UPDATE staff SET nom=?, email=?, numero=?, departement=? WHERE id=?";
        $stmt = mysqli_prepare($conn, $query);
        mysqli_stmt_bind_param($stmt, "sssss", $nom, $email, $numero, $departement, $id);
      
   
        

        if (mysqli_stmt_execute($stmt)) {
            echo 'success';
        } else {
            echo "Update is not executed! ";
        }
        
        mysqli_stmt_close($stmt);
        // mysqli_stmt_close($stmt2);
        // mysqli_stmt_close($stmt3);

    } else {
        echo 'No result for that ID!';
    }

    mysqli_close($conn);

} else {
    echo "something went wrong!";
}
?>
