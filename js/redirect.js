// JavaScript for redirection
document.addEventListener("DOMContentLoaded", function() {
    // Find the button with the class "btn-get-started"
    var getStartedBtn = document.querySelector('.btn-get-started');
  
    // Check if the button exists
    if (getStartedBtn) {
      // Add a click event listener to the button
      getStartedBtn.addEventListener('click', function() {
        // Submit the form when the button is clicked
        document.getElementById('redirectForm').submit();
      });
    }
  });
  