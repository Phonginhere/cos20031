<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Archery</title>
    
    <link rel="stylesheet" href="Styles/style.css"> 
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</head>


<header>
<h1> Archer Page</h1>


</header>
<?php include 'fragment/navbar.php'; ?>
    

<div class="home">
    <div class="homebg"></div>
        <form id="enquiryform" method="post" action="Archery.php">
            <fieldset>
                <legend>Acher Information</legend>
                <p><label for="archer_id">Archer ID:</label>
                    <input type="text" name="archer_id" id="archer_id" maxlength="25" required="required"/>              
                    </p> 

                <p><label for="competition">Competition</label>
                    <select name="competition" id="competition">Competition:
                        <?php
                        include("Competition.php");
                        for ($i = 0; $i < count($competition); $i++) {
                            echo "<option value = {$competition[$i][0]} > {$competition[$i][1]} </option>";
                        }
                        ?>
                    </select>
                </p>
            </fieldset>
        
            <input type= "submit" value="Proceed"/>
            <input type= "reset" value="Reset"/>
        
        </form>
</div>

<!--Enquiry end-->

<script>
    var select = document.getElementById("mySelect");

    // Add event listener for change event
    select.addEventListener("change", function() {
        var selectedOption = select.value;
        var options = select.getElementsByTagName("option");
        
        for (var i = 0; i < options.length; i++) {
            options[i].style.display = "none";
        }

        var selectedOptionElement = select.querySelector("option[value='" + selectedOption + "']");
        if (selectedOptionElement) {
            selectedOptionElement.style.display = "block";
            if (selectedOption === "option1") {
                fetchArcherData();
            }
        }
    });

    function fetchArcherData() {
        var xhr = new XMLHttpRequest();
        xhr.open("GET", "backend_process.php", true);
        xhr.onreadystatechange = function() {
            if (xhr.readyState === 4 && xhr.status === 200) {
                document.getElementById("archerData").innerHTML = xhr.responseText;
            }
        };
        xhr.send();
    }
</script>


<?php include 'fragment/footer.php'; ?>