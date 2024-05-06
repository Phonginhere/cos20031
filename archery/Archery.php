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
    if (isset($_POST["archer_id"])){
        //Transfer form data to variables
        echo ("Input available\n");
        $archer_id = trim($_POST["archer_id"]);
        $competition_id = trim($_POST["competition"]);
    }   else {
        //header("location: Archer_form.php");
    }
    
//SELECT from Archer    
    $archer_query = "SELECT * FROM Archer WHERE `ArcherID` = {$archer_id}";
    $archer_result = mysqli_query($conn, $archer_query);
    if (mysqli_num_rows($archer_result) == 0){
        echo "NO DATA";
    }else
    if ($archer_result){
        while($row = mysqli_fetch_array($archer_result, MYSQLI_ASSOC)) {    
            $archer_name = $row['ArcherName'];
            $dob = $row['ArcherDOB'];
            $gender = $row['ArcherGender'];


//SELECT from ArcherCategory
            $select_archer_category_query = "SELECT * FROM ArcherCategory WHERE ArcherID = {$archer_id} AND CompetitionID = {$competition_id}";
            $select_archer_category_result = mysqli_query($conn, $select_archer_category_query);
            if ($select_archer_category_result){
                    if (mysqli_num_rows($select_archer_category_result) == 0){
                        header("location: Archer_form.php");
                    }
                while($row = mysqli_fetch_array($select_archer_category_result, MYSQLI_ASSOC)) {
                    
                    $archer_category_id = $row['ArcherCategoryID'];
                    $category_info = $row['CategoryID'];
                }

//SELECT from category                    
            $select_category = "SELECT * FROM Category WHERE CategoryID = {$category_info}";
            $select_category_result = mysqli_query($conn, $select_category);
                if ($select_category_result){
                    while($row = mysqli_fetch_array($select_category_result, MYSQLI_ASSOC)) {
                        $category_name = $row['CategoryName'];
                        $category_defined_round = $row['DefinedRoundID'];
                        $category_class_id = $row['ClassID'];
                        $category_division_id = $row['DivisionID'];
                    }
                }
//SELECT from Class
            $select_class = "SELECT * FROM Class WHERE ClassID = {$category_class_id}";
            $select_class_result = mysqli_query($conn, $select_class);
            if ($select_class_result){
                while($row = mysqli_fetch_array($select_class_result, MYSQLI_ASSOC)) {
                    $class_name = $row['ClassName'];
                }
            }

//SELECT from Division
            $select_division = "SELECT * FROM Division WHERE DivisionID = {$category_division_id}";
            $select_division_result = mysqli_query($conn, $select_division);
            if ($select_division_result){
                while($row = mysqli_fetch_array($select_division_result, MYSQLI_ASSOC)) {
                    $division_name = $row['DivisionName'];
                }
            }

//SELECT from DefinedRound
    //NOTE: NEED TO FIX COMPETITION ID TO Category table
            $select_defined_round = "SELECT * FROM DefinedRound WHERE DefinedRoundID = {$category_defined_round}";
            $select_defined_round_result = mysqli_query($conn, $select_defined_round);
            if ($select_defined_round_result){
                while($row = mysqli_fetch_array($select_defined_round_result, MYSQLI_ASSOC)) {
                    $round_name = $row['RoundName'];
                    $possible_score = $row['PossibleScore'];  
                }
            }
            $select_round = "SELECT * FROM Round WHERE ArcherCategoryID = {$archer_category_id} AND DefinedRoundID = {$category_defined_round}";
            $select_round_result = mysqli_query($conn, $select_round);
            if ($select_round_result){
                while($row = mysqli_fetch_array($select_round_result, MYSQLI_ASSOC)) {
                    $round_id = $row['RoundID'];
                    $round_date = $row['Date'];      
                }
            }

            $select_end = "SELECT * FROM `End` WHERE RoundID = {$category_division_id}";
            $select_division_result = mysqli_query($conn, $select_division);
            if ($select_division_result){
                while($row = mysqli_fetch_array($select_division_result, MYSQLI_ASSOC)) {
                    // $endID = $row['EndID'];

                }
            }    
            echo "<br>--------------------------------<br>".
            "Archer ID: {$archer_id}<br>".
            "Archer Name: {$archer_name}<br>".
            "Archer Gender: {$gender}<br>".
            "Archer DOB: {$dob}<br>".
            "&nbsp&nbsp&nbsp&nbsp&nbsp----------------------     <br>".
            "Archer Category: {$archer_category_id}<br>".
            "Category ID: {$category_info}<br>".
            "Category Name: {$category_name}<br>".
            "Division Name: {$division_name}<br>".
            "Class Name: {$class_name}<br>".
            "&nbsp&nbsp&nbsp&nbsp&nbsp----------------------     <br>".
            "Round ID: {$round_id}<br>".
            "Round Date: {$round_date}<br>".
            "Round Name: {$round_name}<br>".
            "Possible Score: {$possible_score}<br>".
            "&nbsp&nbsp&nbsp&nbsp&nbsp----------------------     <br>".
            
            "--------------------------------<br>";
            }


                // header("location: Archer_Round.php");
            }
         }


?>


