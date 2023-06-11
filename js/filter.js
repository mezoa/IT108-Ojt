document.addEventListener("DOMContentLoaded", function () {
  var form = document.getElementById("customQueryForm");
  form.addEventListener("submit", function (event) {
    event.preventDefault(); // Prevent form submission
    var query = document.getElementById("customQuery").value;

    // Execute the custom query using AJAX
    var xhr = new XMLHttpRequest();
    xhr.onreadystatechange = function () {
      if (xhr.readyState === XMLHttpRequest.DONE) {
        if (xhr.status === 200) {
          // Update the table with the response HTML
          var tableBody = document.querySelector("tbody");
          tableBody.innerHTML = xhr.responseText;
        } else {
          // Handle error if the request fails
          console.error("Request failed. Status:", xhr.status);
        }
      }
    };
    xhr.open("POST", "filter.php");
    xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xhr.send("customQuery=" + encodeURIComponent(query));

    // Close the modal
    var modal = document.getElementById("customQueryModal");
    var modalInstance = bootstrap.Modal.getInstance(modal);
    modalInstance.hide();
  });
});

document.getElementById("revertButton").addEventListener("click", function () {
  location.reload();
});

// powered by gpt and stackoverflow