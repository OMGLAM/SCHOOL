<?php

include('config.php');
if (isset($_POST['id'])) {
    $id = $_POST['id'];
    $data = array(
        'id' => $id
    );

    $sql = "SELECT * FROM `staff` WHERE `id`=?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "s", $id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if (mysqli_num_rows($result)) {
        while ($row = mysqli_fetch_assoc($result)) {

            $data["nom"] = $row["nom"];
            $data["email"] = $row["email"];
            $data["departement"] = $row["departement"];            
            $data["numero"] = $row["numero"];
        }
    }

    $jsonData = json_encode($data);
    header('Content-Type: application/json');
    echo $jsonData;

    mysqli_stmt_close($stmt);
}
?>
