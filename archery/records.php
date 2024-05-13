
<!DOCTYPE html>
<html lang="en">
<head>
  <title>Recorder</title>
  <meta charset="utf-8">
  <meta name="description" content="Recorder">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  
  
</head>
<body>
<?php include 'fragment/navbar.php'; ?>
<div class="wrapper">

  <form id="recorder" method="post" action="records.php"> 
  <h3>Which action do you prefer?</h3>
      <label><input  type="radio" name="action" value="1"/>Add an Archer.</label><br>
      <label><input  type="radio" name="action" value="2"/>Add a new round.</label><br>
      <label><input  type="radio" name="action" value="3"/>Add a new competition.</label><br>

      <br>
      <div> <input  type="submit" value="Apply"/>
      <input  type="reset" value="Reset"/> </div>
  </form>


  <div class="main">
    <?php
    require_once("settings.php");

    // Create connection
    $conn = @mysqli_connect($host, $user, $pwd, $sql_db);

    // Function 
    function sanitise_input($data){ 
      $data = trim($data);				//remove spaces
      $data = stripslashes($data);		//remove backslashes in front of quotes
      $data = htmlspecialchars($data);	//convert HTML special characters to HTML code
      return $data;
    }

    // Check connection
    if (!$conn) {
      echo("Connection failed");
    } else {
        echo ("Connected successfully");

        if (empty($_POST["action"])) {
			  echo "<p>Please select a search option.</p>";
		    } else 
        { //action exists after this
            $action = sanitise_input($_POST["action"]);

            switch ($action) {
                case "1":
                  echo "<p>
                  <form id=\"archerName\" method=\"post\" action=\"records.php\"> 
                  <label>Information about Archer you want to register: </label>
                  <br>
                  <label for=\"archerName\">Name
                  <input type=\"text\" name=\"archerName\" id=\"archerName\" 
                  placeholder=\"Tan Pham\" required
                  /></label>

                  <label for=\"archerAge\">Archer's Age
                  <input type=\"number\" name=\"archerAge\" id=\"archerAge\" required
                  /></label>

                  <label for=\"gender\">Gender
                    <select name=\"gender\" id=\"gender\" required>
                        <option value=\"\">Please select</option>
                        <option value=\"Male\">Male</option>
                        <option value=\"Female\">Female</option>
                    </select>
                  </label>
                  </p>";

                  echo "<p>
                  <label for=\"category\">Category
                    <select name=\"category\" id=\"category\" required>
                        <option value=\"\">Please select</option>";

                        $query = "SELECT * FROM `Category`";
                        $result = $conn->query($query);

                        while ($row = mysqli_fetch_assoc($result)) {
                          echo "<option value=\"" . $row["CategoryID"] . "\">" .  $row["CategoryName"] ."</option>";
                        }
                        #free up the memory, after using the result pointer
                        mysqli_free_result($result);
                  echo "
                    </select>
                  </label>

                  <input hidden name=\"action\" value=\"1\"/>
                  <br>
                  <br>
                  <div> <input type=\"submit\" value=\"Apply\"/>
                  <input  type=\"reset\" value=\"Reset\"/> </div>
                  </form>
                  </p>"; 
                  
                  if (!(empty($_POST["archerName"]) AND empty($_POST["archerAge"]) AND empty($_POST["gender"]) AND empty($_POST["category"])))
                  {
                    $archerName = $_POST["archerName"];
                    $archerAge = $_POST["archerAge"];
                    $gender = $_POST["gender"];
                    $category = $_POST["category"];
                    
                    // Insert new player into Player Table 
                    $query = "INSERT INTO `Archer` (`ArcherID`, `ArcherName`, `ArcherAge`, `ArcherGender`) 
                              VALUES (NULL, '$archerName', '$archerAge', '$gender')";
                    $result = $conn->query($query);

               
                    // Insert new playerCategory into playerCategory Table
                    $query = "SET @player_id = (
                      SELECT ArcherID
                      FROM Archer
                      WHERE ArcherName = '$archerName'
                      LIMIT 1
                      );
                  
                      INSERT INTO ArcherCategory (ArcherID, CategoryID)
                      VALUES (@player_id, '$category');
                      ";
                    $result = $conn->multi_query($query);

                    echo "<p>One Archer has been added to Archer and ArcherCategory table.</p>";
                  }
    
                break;

              }
          }  
          mysqli_close($conn);
    }
    ?>
  </div> <!-- for main -->
</div> <!-- for wrapper -->

</body>
</html>
