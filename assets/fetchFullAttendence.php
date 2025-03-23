<?php
include("config.php");

$response = array();
if ($_SERVER['REQUEST_METHOD'] == "POST") {

    $jsonData = file_get_contents('php://input');
    $decodedData = json_decode($jsonData, true);
    $class = $decodedData['class'];
    $section = $decodedData['section'];

    if ($class == "all" && $section == "all") {
        // Select all attendance records    
        $query = "SELECT * FROM `attendence`";
        $stmt = mysqli_prepare($conn, $query);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt); // Retrieve the result
    } 
    else {
        // Ensure $limit and $begin are integers, for pagination
        $query = "SELECT * FROM `attendence` WHERE `class`=? AND `section`=?";
        $stmt = mysqli_prepare($conn, $query);
        mysqli_stmt_bind_param($stmt, "ss", $class, $section); // Bind class and section
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt); // Retrieve the result
    }

    if ($result) {
        $response[0] = "DATA";
        $count = 1;
        $response[1] = "";

        while ($row = mysqli_fetch_assoc($result)) {

            $pathToFile = ".." . DIRECTORY_SEPARATOR . "studentUploads" . DIRECTORY_SEPARATOR . $row['image'];

            if (!file_exists($pathToFile)) {
                $pathToFile = "../images/user.png";
            }
            $currentYear = date("Y");
            $currentMonth = date('m');

            $startDate = "";
            $endDate = "";
            if($currentMonth <= 3){
                $startDate = ($currentYear - 1) . "-04-01";
                $endDate = $currentYear . "-03-31";
            } else {
                $startDate = $currentYear . "-04-01";
                $endDate = ($currentYear + 1) . "-03-31";
            }

            // Query to count present days
            $presentquery = "SELECT COUNT(*) 
                             FROM `attendence` 
                             WHERE `student_id` = ? 
                             AND `attendence` = ? 
                             AND `date` BETWEEN ? AND ?";
            $stmt2 = mysqli_prepare($conn, $presentquery);
            $temp = "1";
            mysqli_stmt_bind_param($stmt2, "ssss", $row['id'], $temp, $startDate, $endDate);
            mysqli_stmt_execute($stmt2);
            mysqli_stmt_bind_result($stmt2, $presentCount);
            mysqli_stmt_fetch($stmt2);
            mysqli_stmt_close($stmt2);

            // Query to count working days
            $workingDaysQuery = "SELECT COUNT(DISTINCT DATE_FORMAT(`date`, '%Y-%m-%d')) 
                                 FROM `attendence`
                                 WHERE `date` BETWEEN ? AND ?";
            $stmt3 = mysqli_prepare($conn, $workingDaysQuery);
            mysqli_stmt_bind_param($stmt3, "ss", $startDate, $endDate);
            mysqli_stmt_execute($stmt3);
            mysqli_stmt_bind_result($stmt3, $workingDays);
            mysqli_stmt_fetch($stmt3);
            mysqli_stmt_close($stmt3);

            $present = (int) $presentCount;
            $percent = 0;
            if ($workingDays != 0) {
                $percent = (int) (($present / $workingDays) * 100);
            }

            $response[1] .= '<tr>
                                <td>' . $count . '.&nbsp;&nbsp;</td>
                                <td>' . $row['id'] . '</td>
                                <td class="user">
                                    <img src="' . $pathToFile . '">
                                    <p>' . ucfirst(strtolower($row['fname'])) . " " . strtolower($row['lname']) . '</p>
                                </td>
                                <td class="text-center"> ' . $workingDays . '</td>
                                <td class="text-center"> ' . $present . '</td>
                                <td class="text-center">' . $percent . '%</td>
                            </tr>';

            $count++;
        }
    } else {
        $response[0] = "No Data";
    }

    mysqli_stmt_close($stmt); // Close the statement
}

echo json_encode($response);

?>
