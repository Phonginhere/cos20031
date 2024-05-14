
<!DOCTYPE html>
<html lang="en">
<head>
  <title>Recorder</title>
  <meta charset="utf-8">
  <meta name="description" content="Recorder">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel='stylesheet' type='text/css' href='records_style.css'>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

  <script src="script.js"></script>

</head>
<body>
<div class="wrapper">
<?php include 'fragment/navbar.php'; ?>

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
                // Retrieve previous values from $_POST if available
                $archerNameValue = isset($_POST['archerName']) ? $_POST['archerName'] : '';
                $archerAgeValue = isset($_POST['archerAge']) ? $_POST['archerAge'] : '';
                $genderValue = isset($_POST['gender']) ? $_POST['gender'] : '';

                  echo "<p>
                  <form id=\"archerInfo\" method=\"post\" action=\"records.php\"> 

                  <input hidden name=\"action\" value=\"1\"/>

                  <label>Information about Archer you want to register: </label>
                  <br>
                  
                  <label for=\"archerName\">Name
                  <input type=\"text\" name=\"archerName\" id=\"archerName\" 
                  required value=\"$archerNameValue\"
                  /></label>

                  <label for=\"archerAge\">Archer's Age
                  <input type=\"number\" name=\"archerAge\" id=\"archerAge\" required 
                  value=\"$archerAgeValue\"
                  /></label>

                  <label for=\"gender\">Gender
                    <select name=\"gender\" id=\"gender\" required\">
                      <option value=\"\" " . ($genderValue == '' ? 'selected' : '') . ">Please select</option>
                      <option value=\"Male\" " . ($genderValue == 'Male' ? 'selected' : '') . ">Male</option>
                      <option value=\"Female\" " . ($genderValue == 'Female' ? 'selected' : '') . ">Female</option>
                    </select>
                  </label>
                  </p>";

                  
                  //Code Below Is Used to Determine the Class
                  if (!(empty($_POST["archerName"]) AND empty($_POST["archerAge"]) AND empty($_POST["gender"])))
                  {
                    // Get user input for age and gender
                  $archerAge = $_POST['archerAge'];
                  $gender = $_POST['gender'];

                  // Determine age group based on user input
                  if ($archerAge >= 70) {
                    $ageGroup = '70+';
                  } elseif ($archerAge >= 60) {
                    $ageGroup = '60+';
                  } elseif ($archerAge >= 50) {
                    $ageGroup = '50+';
                  } elseif ($archerAge >= 21) {
                    $ageGroup = 'Open';
                  } elseif ($archerAge < 14) {
                    $ageGroup = 'Under 14';
                  } elseif ($archerAge < 16) {
                    $ageGroup = 'Under 16';
                  } elseif ($archerAge < 18) {
                    $ageGroup = 'Under 18';
                  } elseif ($archerAge < 21) {
                    $ageGroup = 'Under 21';
                  }
                  
                  if ($ageGroup == 'Open') {
                    $class = $gender . ' Open';
                  } else {
                    $class = $ageGroup . ' ' . $gender;
                  }
                  
                  echo "<p>
                  Now please enter the Category for the Archer:
                  <br>
                  <label for=\"category\">Category
                    <select name=\"category\" id=\"category\" required>
                        <option value=\"\">Please select</option>";

                        $query = "SELECT * 
                                  FROM Category 
                                  WHERE ClassID = (
                                      SELECT ClassID 
                                      FROM Class 
                                      WHERE ClassName = '$class'
                                  );";
                        $result = $conn->query($query);

                        while ($row = mysqli_fetch_assoc($result)) {
                          echo "<option value=\"" . $row["CategoryID"] . "\">" .  $row["CategoryName"] ."</option>";
                        }
                        #free up the memory, after using the result pointer
                        mysqli_free_result($result);
                  echo "
                    </select>
                  </label>
                  </p>";
                  }


                  echo "
                  <div> <input type=\"submit\" value=\"Apply\"/>
                  <input  type=\"reset\" value=\"Reset\"/> </div>
                  </form>
                  "; 

                  if (!(empty($_POST["archerName"])) AND !(empty($_POST["archerAge"])) AND !(empty($_POST["gender"])) AND !(empty($_POST["category"])))
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

                    //Free up the result after multi_query
                    do {
                      if ($result = $conn->store_result()) {
                          $result->free(); // Free the result set
                      }
                    } while ($conn->more_results() && $conn->next_result());

                    echo "<br>
                    <p>One Archer has been added to Archer and ArcherCategory table.</p>";

                    $query = "SELECT AC.ArcherCategoryID, A.ArcherName, C.CategoryName
                    FROM ArcherCategory AC
                    JOIN Archer A ON AC.ArcherID = A.ArcherID
                    JOIN Category C ON AC.CategoryID = C.CategoryID
                    WHERE A.ArcherName = '$archerName' AND C.CategoryID = '$category';
                    ";

                    $result = $conn->query($query); 

                    // Check if there are any rows returned
                    // Check for errors
                    if (!$result) {
                      echo "Error: " . $conn->error;
                    } else {
                      // Check if there are any rows returned
                      if ($result->num_rows > 0) {
                          // Output data
                          echo "<table>";
                          echo "<tr>
                                <th>ArcherCategoryID</th>
                                <th>ArcherName</th>
                                <th>CategoryName</th>
                                </tr>";

                          // Output data of each row
                          while ($row = $result->fetch_assoc()) {
                              echo "<tr>
                                    <td>" . $row["ArcherCategoryID"] . "</td>
                                    <td>" . $row["ArcherName"] . "</td>
                                    <td>" . $row["CategoryName"] . "</td>
                                    </tr>";
                          }

                          echo "</table>";
                      } else {
                          echo "No results found.";
                      }
                    }

                  }
                break;

                case "2":
                  // Retrieve previous values from $_POST if available
                  $archerNameValue = isset($_POST['archerName']) ? $_POST['archerName'] : '';

                  echo "<p>
                  <form id=\"addRound\" method=\"post\" action=\"records.php\"> 

                  <input hidden name=\"action\" value=\"2\"/>

                  <label>Who will play this round? </label>
                  <br>
                  
                  <label for=\"archerName\">Archer Name:
                  <input type=\"text\" name=\"archerName\" id=\"archerName\" 
                  required value=\"$archerNameValue\"
                  /></label>

                  </p>";

                  if (!(empty($_POST["archerName"]))){
                    $categoryValue = isset($_POST['category']) ? $_POST['category'] : '';
                    $competitionValue = isset($_POST['competition']) ? $_POST['competition'] : '';



                    $archerName = $_POST["archerName"];

                    $query = "SELECT AC.ArcherCategoryID, A.ArcherName, C.CategoryName, C.CategoryID
                    FROM ArcherCategory AC
                    JOIN Archer A ON AC.ArcherID = A.ArcherID
                    JOIN Category C ON AC.CategoryID = C.CategoryID
                    WHERE A.ArcherName LIKE '%$archerName%'";

                    $result = $conn->query($query);

                    if ($result->num_rows > 0) {
                        
                        echo "<table>";
                        echo "<tr>
                                <th>ArcherCategoryID</th>
                                <th>ArcherName</th>
                                <th>CategoryName</th>
                              </tr>";

                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>
                                <td>" . $row["ArcherCategoryID"] . "</td>
                                <td>" . $row["ArcherName"] . "</td>
                                <td>" . $row["CategoryName"] . "</td>
                              </tr>";
                    }
                        echo "</table>";

                        // Reset the internal pointer of the result set back to the beginning
                        mysqli_data_seek($result, 0);

                        echo "
                        <p class=\"needMargin\">
                        <label for=\"category\">According to the table, which category do you want?
                          <select name=\"category\" id=\"category\" required>
                              <option value=\"\">Please select</option>";

                              while ($row = mysqli_fetch_assoc($result)) {
                                $selected = ($row["CategoryID"] == $categoryValue) ? "selected" : ""; // Check if the option should be selected
                                echo "<option value=\"" . $row["CategoryID"] . "\" $selected>" .  $row["CategoryName"] ."</option>";
                              }
                              
                              #free up the memory, after using the result pointer
                              mysqli_free_result($result);
                        echo "
                          </select>
                        </label>
                        
                        <label for=\"competition\"> And from which competition do you prefer:
                          <select name=\"competition\" id=\"competition\" required>
                              <option value=\"\">Please select</option>";

                              $query = "SELECT * 
                                        FROM Competition;";
                              $result = $conn->query($query);

                              while ($row = mysqli_fetch_assoc($result)) {
                                $selected = ($row["CompetitionID"] == $competitionValue) ? "selected" : ""; // Check if the option should be selected
                                echo "<option value=\"" . $row["CompetitionID"] . "\" $selected>" .  $row["CompetitionName"] ."</option>";
                              }
                              #free up the memory, after using the result pointer
                              mysqli_free_result($result);
                        echo "
                          </select>
                        </label>
                        </p>";

                    } else {
                        echo "No records found.";
                    } 
                  }

                  if (!(empty($_POST["category"])) AND !(empty($_POST["competition"]))) {
                    $definedRoundValue = isset($_POST['definedRound']) ? $_POST['definedRound'] : '';
            
                    $category = $_POST["category"];
                    $competition = $_POST["competition"];
                    
                    echo "<p>These are the rounds that the archer can participate in, please make your choice:";

                    $query = "SELECT DR.RoundName, C.CategoryName, CMP.CompetitionName, DR.DefinedRoundID 
                    FROM DefinedRound DR
                    JOIN Category C ON DR.DefinedRoundID = C.DefinedRoundID
                    JOIN Competition CMP ON CMP.CompetitionID = DR.CompetitionID
                    WHERE C.CategoryID = '$category'
                    AND CMP.CompetitionID = '$competition';
                    ";

                    $result = $conn->query($query);

                    if ($result->num_rows > 0) {
                        
                        echo "<table>";
                        echo "<tr>
                                <th>Round Name</th>
                                <th>Category</th>
                                <th>Competition</th>
                              </tr>";

                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>
                                <td>" . $row["RoundName"] . "</td>
                                <td>" . $row["CategoryName"] . "</td>
                                <td>" . $row["CompetitionName"] . "</td>
                              </tr>";
                    }
                        echo "</table>";
                    } else {
                         echo "There is no round available that fits these requirements.";
                    }

                    // Reset the internal pointer of the result set back to the beginning
                    mysqli_data_seek($result, 0);
                    
                    if ($result === false) {
                         echo "Error executing SQL query: " . $conn->error;
                     } else {
                         echo "<p>
                         <label for=\"definedRound\" class=\"needMargin\">Which round does the archer participate in?
                             <select name=\"definedRound\" id=\"definedRound\" required>
                                 <option value=\"\">Please select</option>";
                         
                     while ($row = mysqli_fetch_assoc($result)) {
                         $selected = ($row["DefinedRoundID"] == $definedRoundValue) ? "selected" : ""; // Check if the option should be selected
                         echo "<option value=\"" . $row["DefinedRoundID"] . "\" $selected>" .  $row["RoundName"] ."</option>";
                     }
             
                     #free up the memory, after using the result pointer
                     mysqli_free_result($result);
                     echo "</select>
                         </label>
                         </p>";
                     }

                    }

                    if (!(empty($_POST["definedRound"]))) {
                         $roundDateValue = isset($_POST['roundDate']) ? $_POST['roundDate'] : '';

                         echo "<p>
                         <label for=\"roundDate\">Date of the Round:
                         <input type=\"date\" name=\"roundDate\" id=\"roundDate\" required 
                         value=\"$roundDateValue\"
                         /></label>
                         </p>";


                         if (!(empty($_POST["roundDate"]))) {
                              $roundDate = $_POST["roundDate"];
                              $definedRound = $_POST["definedRound"];

                              echo $roundDate;
                              echo $definedRound;
                              echo $archerName;
                              echo $category;

                              $query = "
                              SET
                              @player_id =(
                              SELECT
                              ArcherID
                              FROM
                              Archer
                              WHERE
                              ArcherName LIKE '%$archerName%'
                              LIMIT 1
                              );

                              SET
                                   @archer_category =(
                                   SELECT
                                   ArcherCategoryID
                                   FROM
                                   ArcherCategory
                                   WHERE
                                   ArcherID = @player_id AND CategoryID = '$category'
                                   LIMIT 1
                              );

                              INSERT INTO Round(DefinedRoundID, ArcherCategoryID, DATE)
                              VALUES('$definedRound', @archer_category, '$roundDate');
                              ";


                              $result = $conn->multi_query($query);
                              if ($result === false) {
                                   echo "Error executing SQL query: " . $conn->error;
                               } else {
                                 //Free up the result after multi_query
                              do {
                                   if ($result = $conn->store_result()) {
                                        $result->free(); // Free the result set
                                   }
                                   } while ($conn->more_results() && $conn->next_result());
     
                                   echo "A new round has been added";
                                   }
                               }
                    }

                  echo "
                  <p>
                  <div> <input type=\"submit\" value=\"Apply\"/>
                  <input  type=\"reset\" value=\"Reset\"/> </div>
                  </form>
                  </p>"; 
                  
                break;

                case "3":

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
