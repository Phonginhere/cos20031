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
        header("location: Archer_form.php");
    }
    
// TEST ANY ARCHER IN SELECT from Archer    
    $archer_query = "SELECT * FROM Archer WHERE `ArcherID` = {$archer_id}";
    $archer_result = mysqli_query($conn, $archer_query);
    if (mysqli_num_rows($archer_result) == 0){
        echo "NO DATA";
        header("location: Archer_form.php");
    }

            $join_select = "
            SELECT
            Archer.ArcherID,
            Archer.ArcherName,
            Archer.ArcherGender,
            Archer.ArcherAge,
            Competition.CompetitionName,
            Category.CategoryName,
            Division.DivisionName,
            Class.ClassName,
            Round.RoundID,
            DefinedRound.RoundName,
            DefinedRound.PossibleScore,
            Round.Date 
            FROM
            Archer
            JOIN
            ArcherCategory ON Archer.ArcherID = ArcherCategory.ArcherID
            JOIN
            Category ON ArcherCategory.CategoryID = Category.CategoryID
            JOIN
            Division ON Category.DivisionID = Division.DivisionID
            JOIN
            Class ON Category.ClassID = Class.ClassID
            JOIN
            Competition ON ArcherCategory.CompetitionID = Competition.CompetitionID
            JOIN
            Round ON ArcherCategory.ArcherCategoryID = Round.ArcherCategoryID
            JOIN
            DefinedRound ON Round.DefinedRoundID = DefinedRound.DefinedRoundID
            WHERE
            Archer.ArcherID = {$archer_id} AND
            ArcherCategory.CompetitionID = {$competition_id};";
            // echo $join_select;
            
            $join_select_result = mysqli_query($conn, $join_select);
            if (mysqli_num_rows($join_select_result) == 0){
                echo "NO DATA";
                header("location: Archer_form.php");
            }
            if ($join_select_result){
                while($row = mysqli_fetch_array($join_select_result, MYSQLI_ASSOC)) {
                    $archer_id = $row['ArcherID'];
                    $archer_name = $row['ArcherName'];
                    $dob = $row['ArcherAge'];
                    $gender = $row['ArcherGender'];
                    $competition_name = $row['CompetitionName'];
                    $round_id = $row['RoundID'];
                    $category_name = $row['CategoryName'];
                    $class_name = $row['ClassName'];
                    $division_name = $row['DivisionName'];
                    $round_name = $row['RoundName'];
                    $possible_score = $row['PossibleScore']; 
                    $round_date = $row['Date'];   
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
                <td>{$round_name}</td>
                <td>{$possible_score}</td>
                <td>{$round_date}</td>
            </tr>
            </table>
            <br>
            ";

            $score_query = "SELECT
            End.EndID,
            End.EndOrder,
            TargetFace.TargetFace,
            RangeDistance.RangeDistance,
            `Range`.RangeOrder,
            Arrow.ArrowID,
            Arrow.ArrowPoint,
            End.Date
            FROM
                Archer
            JOIN
                ArcherCategory ON Archer.ArcherID = ArcherCategory.ArcherID
            JOIN
                Round ON ArcherCategory.ArcherCategoryID = Round.ArcherCategoryID
            JOIN
                End ON Round.RoundID = End.RoundID
            JOIN
                `Range` ON End.RangeID = `Range`.RangeID
            JOIN
                TargetFace ON `Range`.TargetFaceID = TargetFace.TargetFaceID
            JOIN
                RangeDistance ON `Range`.RangeDistanceID = RangeDistance.RangeDistanceID
            JOIN
                Arrow ON End.EndID = Arrow.EndID
            WHERE
                Archer.ArcherID = {$archer_id} AND
                ArcherCategory.CompetitionID = {$competition_id}
            ORDER BY
                End.EndID,
                Arrow.ArrowID,
                End.Date;";        

        // echo $score_query;
        $count = 0;
        $end_list = array();
        $arrow_list = array();
        $total_round = array();
        $score_result = mysqli_query($conn, $score_query);
            if ($score_result){
                echo "<div class= end_table_list>";
                while($row = mysqli_fetch_array($score_result, MYSQLI_ASSOC)) {
                    $idx = $count % 6;
                    $arr_num = ($idx +1);
                    $end_num = $count/6+1;
                    if ($count == 0){
                        array_push($total_round, $row['ArrowPoint']);
                        echo "<table>
                        <tr>
                            <th> End Number</th>
                            <th> End Definition</th>
                            <th> End Date</th>
                            <th> Arrow Number</th>
                            <th class= tbl-point> Arrow Point</th>
                        </tr>";
                        echo "<tr>   
                            <td rowspan='6'>{$end_num}</td>
                            <td rowspan='6'>{$row['TargetFace']} - {$row['RangeDistance']}</td>
                            <td rowspan='6'>{$row['Date']}</td>
                            <td>{$arr_num}</td>
                            <td>{$row['ArrowPoint']}</td> 
                            </tr>";
                    }else if ($idx == 0 && $count != 0){
                        array_push($total_round, $row['ArrowPoint']);
                        $sum = get_total_point($total_round,$count,"end");
                        echo "<tr>        
                            <td colspan = '4'>TOTAL</td>
                            <td>$sum</td>
                            </tr>";
                        echo "</table> <br>";
                        echo "<table>
                        <tr>
                            <th> End Number</th>
                            <th> End Definition</th>
                            <th> End Date</th>
                            <th> Arrow Number</th>
                            <th> Arrow Point</th>
                        </tr>";
                        echo "<tr>
                            <td rowspan='6'>{$end_num}</td>
                            <td rowspan='6'>{$row['TargetFace']} - {$row['RangeDistance']}</td>
                            <td rowspan='6'>{$row['Date']}</td>
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
                            <td colspan = '4'>TOTAL</td>
                            <td>$sum</td>
                            </tr>";
                        }
                    }
                    $count ++;
                }
                echo "</table>";
                echo "</div>";

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