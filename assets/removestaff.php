
<?php
include("config.php");

if (isset($_POST['teacherid'])) {
    $teacherid = $_POST['teacherid'];

    // Vérifier si l'élément existe
    $checkSql = "SELECT * FROM `staff` WHERE `id`=?";
    $checkStmt = mysqli_prepare($conn, $checkSql);
    mysqli_stmt_bind_param($checkStmt, "s", $teacherid);
    mysqli_stmt_execute($checkStmt);
    $checkResult = mysqli_stmt_get_result($checkStmt);

    if (mysqli_num_rows($checkResult) > 0) {
        // L'élément existe, on peut le supprimer
        $deleteSql = "DELETE FROM `staff` WHERE `id`=?";
        $deleteStmt = mysqli_prepare($conn, $deleteSql);
        mysqli_stmt_bind_param($deleteStmt, "s", $teacherid);
        if (mysqli_stmt_execute($deleteStmt)) {
            echo "Agent Supprimé Avec Success.";
        } else {
            echo "Erreur lors de la suppression.";
        }
    } else {
        echo "Aucun élément trouvé avec cet ID.";
    }
}

?>
