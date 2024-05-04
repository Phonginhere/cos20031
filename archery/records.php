
<!DOCTYPE html>
<html lang="en">
<head>
  <title>Bootstrap Example</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <script>
    function dropdownChange() {
  var dropdown = document.getElementById("disabledSelect");
  var secondDropdown = document.getElementById("secondDropdown");
  var selectedValue = dropdown.value;

  // Clear existing options
  secondDropdown.innerHTML = '';
  
  if (selectedValue === "choose1") {
    // Do something when "Choose 1" is selected
    console.log("You chose option 1");
    // Add options for 1.1 and 1.2
    addOption(secondDropdown, "1.1", "1.1");
    addOption(secondDropdown, "1.2", "1.2");
  } else if (selectedValue === "choose2") {
    // Do something when "Choose 2" is selected
    console.log("You chose option 2");
    // Add options for 2.1 and 2.2
    addOption(secondDropdown, "2.1", "2.1");
    addOption(secondDropdown, "2.2", "2.2");
  }
}

function addOption(selectElement, text, value) {
  var option = document.createElement("option");
  option.text = text;
  option.value = value;
  selectElement.add(option);
}

// Initially update the second dropdown based on the initial selection
updateSecondDropdown();
    </script>
</head>
<body>
    <?php include 'fragment/navbar.php'; ?>
    <form>
    <div class="d-grid gap-3 col-md-4">
  <div class="p-2 bg-light border">
 <!-- begin form -->
 <div class="row g-3 align-items-center">
  <div class="col-auto">
    <label for="inputPassword6" class="col-form-label">Input 1</label>
  </div>
  <div class="col-auto">
  <select id="disabledSelect" class="form-select" onchange="dropdownChange()">
        <option selected>Selected</option>
        <option value="choose1">Input 1</option>
        <option value="choose2">Input 2</option>
      </select>
  </div>
  <div class="col-auto">
    <span id="passwordHelpInline" class="form-text">
      Must be 8-20 characters long.
    </span>
  </div>
</div>
<!-- end form -->
  </div>
  <div class="p-2 bg-light border">
     <!-- begin form -->
  <div class="row g-3 align-items-center">
  <div class="col-auto">
    <label for="inputPassword6" class="col-form-label">Input 2</label>
  </div>
  <div class="col-auto">
      <select id="secondDropdown" class="form-select">

      </select>
  </div>
  <div class="col-auto">
    <span id="passwordHelpInline" class="form-text">
      Must be 8-20 characters long.
    </span>
  </div>
</div>
<!-- end form -->
  </div>
  <div class="p-2 bg-light border">
     <!-- begin form -->
  <div class="row g-3 align-items-center">
  <div class="col-auto">
    <label for="inputPassword6" class="col-form-label">Password</label>
  </div>
  <div class="col-auto">
    <input type="password" id="inputPassword6" class="form-control" aria-describedby="passwordHelpInline">
  </div>
  <div class="col-auto">
    <span id="passwordHelpInline" class="form-text">
      Must be 8-20 characters long.
    </span>
  </div>
</div>
<!-- end form -->
  </div>
</div>

 
  <div class="mb-3 form-check">
    <input type="checkbox" class="form-check-input" id="exampleCheck1">
    <label class="form-check-label" for="exampleCheck1">Check me out</label>
  </div>
  <button type="submit" class="btn btn-primary">Submit</button>
</form>
    
    <?php include 'fragment/footer.php'; ?>
</body>
</html>
