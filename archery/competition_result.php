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
    </style>
</head>

<?php include 'fragment/navbar.php'; ?>
    
<header>
<h1> Competition Result</h1>
</header>

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
    if (isset($_POST["competition"])){
        $competition_id = trim($_POST["competition"]);
        // $competition_id = trim($_POST["action"]);
    }   else {
        // header("location: competition_result.php");
    }


    $competition_result_query = "SELECT
    (SELECT COUNT(*) + 1
     FROM (
         SELECT
             Archer.ArcherID,
             Archer.ArcherName,
             Round.RoundID,
             SUM(Arrow.ArrowPoint) AS TotalPoint
         FROM
             Archer
         JOIN
             ArcherCategory ON Archer.ArcherID = ArcherCategory.ArcherID
         JOIN
             Round ON ArcherCategory.ArcherCategoryID = Round.ArcherCategoryID
         JOIN
             DefinedRound ON Round.DefinedRoundID = DefinedRound.DefinedRoundID
         JOIN
             Competition ON ArcherCategory.CompetitionID = Competition.CompetitionID
         JOIN
             End ON Round.RoundID = End.RoundID
         JOIN
             Arrow ON End.EndID = Arrow.EndID
         WHERE
             ArcherCategory.CompetitionID = {$competition_id}
         GROUP BY
             Archer.ArcherID,
             Round.RoundID
        ) AS subquery
        WHERE subquery.TotalPoint > main.TotalPoint) AS Ranking,
        main.CompetitionID,
        main.CompetitionName,
        main.ArcherID,
        main.ArcherName,
        main.RoundID,
        main.RoundName,
        main.TotalPoint
    FROM (
        SELECT
            Competition.CompetitionID,
            Competition.CompetitionName,
            Archer.ArcherID,
            Archer.ArcherName,
            Round.RoundID,
            DefinedRound.RoundName,
            SUM(Arrow.ArrowPoint) AS TotalPoint
        FROM
            Archer
        JOIN
            ArcherCategory ON Archer.ArcherID = ArcherCategory.ArcherID
        JOIN
            Round ON ArcherCategory.ArcherCategoryID = Round.ArcherCategoryID
        JOIN
            DefinedRound ON Round.DefinedRoundID = DefinedRound.DefinedRoundID
        JOIN
            Competition ON ArcherCategory.CompetitionID = Competition.CompetitionID
        JOIN
            End ON Round.RoundID = End.RoundID
        JOIN
            Arrow ON End.EndID = Arrow.EndID
        WHERE
            ArcherCategory.CompetitionID = {$competition_id}
        GROUP BY
            Competition.CompetitionID,
            Competition.CompetitionName,
            Archer.ArcherID,
            Archer.ArcherName,
            Round.RoundID,
            DefinedRound.RoundName
    ) AS main
    ORDER BY
        Ranking;

    ";

    // echo $competition_result_query;
    
    $competition_ranking_result = mysqli_query($conn, $competition_result_query);
    if ($competition_ranking_result){
        echo "<div class= end_table_list>";
                        echo "<table>
                        <tr>
                            <th> Competition</th>
                            <th> Ranking</th>
                            <th> Archer ID</th>
                            <th> Archer Name</th>
                            <th> Round Name</th>
                            <th> Total Point</th>
                        </tr>";
        while($row = mysqli_fetch_array($competition_ranking_result, MYSQLI_ASSOC)) {
                        echo "
                        <tr>   
                            <td>{$row['CompetitionName']}</td>
                            <td>{$row['Ranking']}</td>
                            <td>{$row['ArcherID']}</td>
                            <td>{$row['ArcherName']}</td>
                            <td>{$row['RoundName']}</td>
                            <td>{$row['TotalPoint']}</td> 
                        </tr>";
        }
        echo "</div>";

    }
?>
<div class="home">
    <div class="homebg"></div>
</div>





<!--Enquiry end-->




<!-- <?php include 'fragment/footer.php'; ?> -->