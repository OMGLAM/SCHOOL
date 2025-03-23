<?php

include("config.php");
$response = "";
if ($_SERVER['REQUEST_METHOD'] == "POST") {
    if (isset($_POST["class"])) {
        $class = $_POST["class"];

        if ($class == "all"){
            $query = "SELECT * FROM `syllabus`";
            $stmt = mysqli_prepare($conn, $query);
            
        }
        else {
            $query = 'SELECT * FROM `syllabus` WHERE `class`= ?';
            $stmt = mysqli_prepare($conn, $query);
            mysqli_stmt_bind_param($stmt, "s", $class);
             

        }
        

        
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
         
        
        
        if (mysqli_num_rows($result) > 0) {
            
            $count = 1;
            while($row = mysqli_fetch_assoc($result)){
              

                $fileName = $row['file'];
                $fileLocation = ".." . DIRECTORY_SEPARATOR . "syllabusUploads" . DIRECTORY_SEPARATOR .$fileName;

                $image =  "../images/p.png" ;
                if ($row['s_no'] == 12) {
                    $image = "../images/aa.jpg" ;

                }
                if ($row['s_no'] == 33) {
                    $image = "../images/ja.png" ;

                }
                if ($row['s_no'] == 88) {
                    $image =  "../images/ko.png" ;

                }
                // else{
                //     $image =  "../images/p.png" ;
                // }
                 

                $response .= '   <tr>

                <td>'.$count.'</td>

                <td >
                    <img src='. $image .'>
                    <p> '.$row['subject'].'</p>
                     
                </td>
                
                <td>'.$row['date_exam'].'</td>
                  <td>'.$row['salle'].'</td>
                    <td>'.$row['prof'].'</td>
                      <td>'.$row['heure_exam'].'</td>
                        <td>'.$row['description'].'</td>
                         <td>'.$row['duree'].'H</td>
                <td>
                    <div class="upload-download">
                        <a href="'.$fileLocation.'" download="'.$fileName.'" class="upload">
                            <i class="bx bxs-download"></i>
                            <span>&nbsp;Download</span>
                        </a>
                        <a class="download"  onclick="openEditSllyabusFile('.$row['s_no'].')">
                            &nbsp;&nbsp;<i class="bx bxs-download"></i>
                            <span>&nbsp;Upload</span>
                            &nbsp;&nbsp;
                        </a>
                    </div>
                </td>
            </tr>';
            $count++;

            }
            
            
        } else {
            $response = "No_data";
        }
        mysqli_stmt_close($stmt);


    }else{
        $response = "No_data";
    }
}
else{
    $response = "No_data";
}
echo $response;

?>