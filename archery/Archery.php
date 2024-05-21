<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Archery</title>
    
    <link rel="stylesheet" href="Styles/style.css"> 
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <style>
        table,
        th,
        td {
            border: 1px solid black;
            border-collapse: collapse;
            padding: 6px;
            
        }
        table {
            text-align: center;
        }
        th{
            background-color: grey;
        }
        .end_table_list{
            position: relative; 
            gap: 25px;
            display: flex;
            flex-wrap: wrap;
            justify-content: space-around;
        }
        .end_table_list th{
            background-color: lightgrey;
        }
        .tbl-point{
            background-color: rgb(250, 71, 76);
        }
        
    </style>
</head>

<?php include 'fragment/navbar.php'; ?>

<header>
<h1> Archer Round Page</h1>
</header>

<div class="home">
    <div class="homebg"></div>

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
    header("location: Archer_form.php");

} else 
    if (isset($_POST["archer_id"])){
        //Transfer form data to variables
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
        header("location: Archer_form.php");
    }else
    if ($archer_result){
        while($row = mysqli_fetch_array($archer_result, MYSQLI_ASSOC)) {    
            $archer_name = $row['ArcherName'];
            $dob = $row['ArcherAge'];
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
            $select_round = "SELECT * FROM Round 
            WHERE ArcherCategoryID = {$archer_category_id} 
            AND DefinedRoundID = {$category_defined_round}";
            $select_round_result = mysqli_query($conn, $select_round);
            if ($select_round_result){
                while($row = mysqli_fetch_array($select_round_result, MYSQLI_ASSOC)) {
                    $round_id = $row['RoundID'];
                    $round_date = $row['Date'];      
                }
            }

//Contains information of Range Distance and Target Face of End
            $range_id  = array();
            $target_face_id_of_end = array();
            $range_order = array();
            $range_distance_id = array();
            $select_range = "SELECT * FROM `Range` 
            WHERE DefinedRoundID = {$category_defined_round}
            ORDER BY RangeOrder;";
            $select_range_result = mysqli_query($conn, $select_range);
            if ($select_range_result){
                while($row = mysqli_fetch_array($select_range_result, MYSQLI_ASSOC)) {
                    array_push($range_id  , $row['RangeID']);
                    array_push($target_face_id_of_end , $row['TargetFaceID']);
                    array_push($range_order , $row['RangeOrder']);
                    array_push($range_distance_id , $row['RangeDistanceID']);
                }
            }    

            // $a = array();
            // for ($i = 0; $i < 5; $i++) {
            //     $a[$i] = "i = {$i}";
            // }
            // foreach ($a as $key => $value) {
            //     echo "Key: $key, Value: $value<br>";
            // }
            $target_face = array();
            $select_target_face = "SELECT * FROM TargetFace";
            $select_target_face_result = mysqli_query($conn, $select_target_face);
            if ($select_target_face_result){
                while($row = mysqli_fetch_array($select_target_face_result, MYSQLI_ASSOC)) {
                    $target_face[$row['TargetFaceID']] = $row['TargetFace'];
                }
            }
            // foreach($target_face as $key => $value){
            //     echo "<br>Target_Face_ID: $key, TargetFace: $value<br>";
            // }
            // foreach($target_face as $key => $value){
            //     echo "<br>Target_Face_ID: $key, TargetFace: $value<br>";
            // }
            $target_distance = array();
            $select_distance = "SELECT * FROM `RangeDistance`";
            $select_distance_result = mysqli_query($conn, $select_distance);
            if ($select_distance_result){
                while($row = mysqli_fetch_array($select_distance_result, MYSQLI_ASSOC)) {
                    $target_distance[$row['RangeDistanceID']] = $row['RangeDistance'];
                }
            }
            echo "
            <table>
            <tr>
                <th>Archer ID</th>
                <th>Archer Name</th>
                <th>Archer Gender</th>
                <th>Archer DOB</th>
                <th>Category Name</th>
                <th>Division Name</th>
                <th>Class Name</th>
                <th>Round Name</th>
                <th>Possible Score</th>
                <th>Round Date</th>
            </tr>
            <tr>
                <td>{$archer_id}</td>
                <td>{$archer_name}</td>
                <td>{$gender}</td>
                <td>{$dob}</td>
                <td>{$category_name}</td>
                <td>{$division_name}</td>
                <td>{$class_name}</td>
                <td>{$round_date}</td>
                <td>{$possible_score}</td>
                <td>{$round_date}</td>
            </tr>
            </table>
            <br>
            ";
            $end_list = array();
            $arrow_list = array();
            $count = 0;
            $total_round = array();
            // array_push($end_list, $arrow_list);
            $select_arrow = "SELECT
                End.EndID,
                Arrow.ArrowID,
                Arrow.ArrowPoint
            FROM
                Round
            JOIN
                End ON Round.RoundID = End.RoundID
            JOIN
                Arrow ON End.EndID = Arrow.EndID
            WHERE
                Round.RoundID = {$round_id};";
            // echo $select_arrow;
            $select_arrow_result = mysqli_query($conn, $select_arrow);
            if ($select_arrow_result){
                echo "<div class= end_table_list>";
                while($row = mysqli_fetch_array($select_arrow_result, MYSQLI_ASSOC)) {
                    $idx = $count % 6;
                    $arr_num = ($idx +1)%6;
                    $
                    if ($count == 0){
                        array_push($total_round, $row['ArrowPoint']);
                        echo "<table>
                        <tr>
                            <th> End Number</th>
                            <th> Arrow Number&nbsp&nbsp&nbsp&nbsp&nbsp</th>
                            <th class= tbl-point> Arrow Point</th>
                        </tr>";
                        echo "<tr>   
                            <td rowspan='6'>{$row['EndID']}</td>
                            <td>{$row['ArrowID']}{$idx}</td>
                            <td>{$row['ArrowPoint']}</td> 
                            </tr>";
                    }else if ($idx == 0 && $count != 0){
                        array_push($total_round, $row['ArrowPoint']);
                        $sum = get_total_point($total_round,$count,"end");
                        echo "<tr>        
                            <td colspan = '2'>TOTAL</td>
                            <td>$sum</td>
                            </tr>";
                        echo "</table> <br>";
                        echo "<table>
                        <tr>
                            <th> End Number&nbsp&nbsp&nbsp&nbsp&nbsp</th>
                            <th> Arrow Number&nbsp&nbsp&nbsp&nbsp&nbsp</th>
                            <th> Arrow Point</th>
                        </tr>";
                        echo "<tr>
                            <td rowspan='6'>{$row['EndID']}</td>
                            <td>{$arr_num}</td>
                            <td>{$row['ArrowPoint']}</td>
                            </tr>";
                    }
                    else{
                        array_push($total_round, $row['ArrowPoint']);
                        echo "<tr>        
                            <td>{$arr_num}</td>
                            <td>{$row['ArrowPoint']}</td>
                            </tr>";
                        if ($idx == 5 && $row['EndID'] == 24){       //Update Total score of last table
                            $sum = get_total_point($total_round,$count,"end");
                            echo "<tr>        
                            <td colspan = '2'>TOTAL</td>
                            <td>$sum</td>
                            </tr>";
                        }
                    }
                    $count ++;
                }
                echo "</table>";
                echo "</div>";
            }
        }
    }
}
$round_total_point = get_total_point($total_round,$count,"round");
echo "<br><h1>Total point of all rounds: {$round_total_point}</h1>";

function get_total_point($point, $count, $type) {
    if ($type == "round"){
        return array_sum($point);
    }
    else if ($type == "end"){
        $end_sum = 0;
        for ($i = $count-1; $i >= $count-6; $i--) {
            // echo "{$i} with $point[$i] <br>";
            $end_sum += $point[$i];
        }
        return $end_sum;
    }
    else{
        return 0;
    }
}    


  
?>

</div>

<?php include 'fragment/footer.php'; ?>
</html>