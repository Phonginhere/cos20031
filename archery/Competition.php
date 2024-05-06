<?php

include_once("settings.php");
$conn = @mysqli_connect(
    $host,
    $user,
    $pwd,
    $sql_db
);
if (!$conn) {
    echo "Error connecting to database";
} else {
    $competition = array();
    $competition_query = "SELECT * FROM `Competition`";
    $competition_query_result = mysqli_query($conn, $competition_query);
    if ($competition_query_result) {
        while ($row = mysqli_fetch_array($competition_query_result, MYSQLI_ASSOC)) {
            $competition_id = $row['CompetitionID'];
            $competition_name = $row['CompetitionName'];
            array_push($competition, array($competition_id, $competition_name));
        }
    } else {
        echo "Error executing query";
    }
}



    

?>