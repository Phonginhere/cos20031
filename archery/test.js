// // Get the select element
// var select = document.getElementById("mySelect");

// // Add event listener for change event
// select.addEventListener("change", function() {
//     // Get the selected value
//     var selectedOption = select.value;
    
//     // Hide all options
//     var options = select.getElementsByTagName("option");
//     for (var i = 0; i < options.length; i++) {
//         options[i].style.display = "none";
//     }
    
//     // Show the selected option
//     var selectedOptionElement = select.querySelector("option[value='" + selectedOption + "']");
//     if (selectedOptionElement) {
//         selectedOptionElement.style.display = "block";
//     }
// });
// Add event listener for change event
select.addEventListener("change", function() {
    // Get the selected value
    var selectedOption = select.value;

    // Hide all options
    var options = select.getElementsByTagName("option");
    for (var i = 0; i < options.length; i++) {
        options[i].style.display = "none";
    }

    // Show the selected option
    var selectedOptionElement = select.querySelector("option[value='" + selectedOption + "']");
    if (selectedOptionElement) {
        selectedOptionElement.style.display = "block";

        // If Option 1 is selected, fetch the archer data
        if (selectedOption === "option1") {
            fetchArcherData();
        }
    }
});

function fetchArcherData() {
    // Make an AJAX request to fetch archer data
    var xhr = new XMLHttpRequest();
    xhr.open("GET", "backend_process.php", true);
    xhr.onreadystatechange = function() {
        if (xhr.readyState === 4 && xhr.status === 200) {
            // Display the fetched data in the archerData div
            document.getElementById("archerData").innerHTML = xhr.responseText;
        }
    };
    xhr.send();
}

