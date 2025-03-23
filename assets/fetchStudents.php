<?php
include("config.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $postData = file_get_contents("php://input");
    $data = json_decode($postData, true);

    // Vérification et nettoyage des entrées
    $name = isset($data['name']) ? trim($data['name']) : "";
    $class = isset($data['as']) ? $data['as'] : "";
    $section = isset($data['a']) ? $data['a'] : "";
    $query = "";
    $resultOutput = [];

    if ($name == "") {
        if ($class =="all" && $section =="all"){
            $query = "SELECT * FROM students ; ";
            $stmt = mysqli_prepare($conn, $query);
         //   mysqli_stmt_bind_param($stmt, "ss", $class, $section);
        }
        else{
        $query = "SELECT * FROM students  WHERE class=? AND section=?  ORDER BY fname, lname ASC;";
        $stmt = mysqli_prepare($conn, $query);
        mysqli_stmt_bind_param($stmt, "ss", $class, $section);
        }

        
    } else {
        // Cas avec recherche par nom
        if (stripos($name, " ") !== false) {
            $array = explode(' ', $name, 2);

            $query = "SELECT * FROM students WHERE ((fname LIKE ? AND lname LIKE ?) OR (fname LIKE ? AND lname LIKE ?))
                      ORDER BY fname, lname ASC;";
            $stmt = mysqli_prepare($conn, $query);

            if ($stmt) {
                $param1 = $array[0] . '%';
                $param2 = '%' . $array[1] . '%';
                $param3 = $array[1] . '%';
                $param4 = '%' . $array[0] . '%';
                mysqli_stmt_bind_param($stmt, "ssss",  $param1, $param2, $param3, $param4);
            }
        } else {
            $query = "SELECT * FROM students WHERE 
                       (fname LIKE ? OR lname LIKE ?)
                      ORDER BY fname, lname ASC;";
            $stmt = mysqli_prepare($conn, $query);

            if ($stmt) {
                $param = '%' . $name . '%';
                mysqli_stmt_bind_param($stmt, "ss", $param, $param);
            }
        }
    }

    if ($stmt) {
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        if (mysqli_num_rows($result) > 0) {
            $count = 1;
            while ($row = mysqli_fetch_assoc($result)) {
                $module = $row['class'];
                $fname = ucfirst(strtolower($row["fname"]));
                $lname = ucfirst(strtolower($row["lname"]));
                $tid = $row['id'];
                $tele = $row['phone'];
                $adress=$row['address'];
                $email=$row['email'];
                $image = '../studentUploads/' . $row['image'];
                $image = file_exists($image) ? $image : "../images/user.png";

                $resultOutput[] = "<tr>
                    <td>&nbsp;&nbsp;{$count}.&nbsp;&nbsp;</td>
                    <td>{$tid}</td>
                    <td class='user'>
                        <img src='{$image}'>
                        <p>{$fname} {$lname}</p>
                    </td>
                    <td>{$tele}</td>
                     <td>{$adress}</td>
                      <td>{$email}</td>
                    <td>{$module}</td>
                   <td class='flex-center'>
                    <div class='edit-delete'>
                        <a onclick='editStudent(`" . $tid . "`)'   class='edit' >
                            <i class='bx bxs-edit'></i>
                            <span>&nbsp;Edit</span>
                        </a>
                        <a onclick='deleteStudentWithId(`" . $tid . "`)'  class='delete'>
                            &nbsp;&nbsp;<i class='bx bxs-trash'></i>
                            <span>&nbsp;Delete</span>
                            &nbsp;&nbsp;
                        </a>
                    </div>
                </td>
                </tr>";

                $count++;
            }
        } else {
            $resultOutput[] = "No_Record";
        }

        mysqli_stmt_close($stmt);
    } else {
        $resultOutput[] = "Error in preparing statement";
    }
} else {
    $resultOutput[] = "Error";
}

echo json_encode($resultOutput);
?>
