<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Competition Result</title>
    
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

<form id="enquiryform" method="post" action="competition_result.php">
    <p>
        <label for="competition">Competition Result: </label>
        <select name="competition" id="competition">Competition:
            <?php
            include("Competition.php");
            for ($i = 0; $i < count($competition); $i++) {
                echo "<option name = 'action' value = {$competition[$i][0]} > {$competition[$i][1]} </option>";
            }
            ?>
        </select>
    </p>

    <input type= "submit" value="Proceed"/>
    <input type= "reset" value="Reset"/>
</form>

<div class="home">
    <div class="homebg"></div>
</div>





<!--Enquiry end-->




<!-- <?php include 'fragment/footer.php'; ?> -->