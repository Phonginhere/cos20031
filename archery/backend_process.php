<?php
include_once "settings.php";
$conn = @mysqli_connect(
    $host,
    $user,
    $pwd,
    $sql_db
);
//Check the connection
if(!$conn){
    echo "Connection to database failed";

} else 
    echo("Connection established");
    if (isset($_POST["archer_name"])){
        //Transfer form data to variables
        echo ("Data available\n");
        $archer_name = trim($_POST["archer_name"]);
        $dob =trim($_POST["dob"]);
        $gender =trim($_POST["gender"]);
    }   else {
        // header ("location: recorder.html");
    }
    
    $query = "SELECT * FROM Competition";
    $result = mysqli_query($conn, $query);
    if ($result){
        // echo ("Data available");
        while($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
            echo "Competition ID: {$row['CompetitionID']}  <br> ".
               "Competition Name: {$row['CompetitionName']} <br> ".
               "--------------------------------<br>";
         }
    }
    $query1 = "SELECT * FROM Archer";
    $result1 = mysqli_query($conn, $query1);
    if ($result1){
        echo "Data available";
        while($row = mysqli_fetch_array($result1, MYSQLI_ASSOC)) {
            echo "Archer ID: {$row['ArcherID']}<br>".
                "Archer Name: {$row['ArcherName']}<br>".
                "Archer DOB: {$row['ArcherDOB']}<br>".
                "Archer Gender: {$row['ArcherGender']}<br>".
                "--------------------------------<br>";
        }
    }
    

?>