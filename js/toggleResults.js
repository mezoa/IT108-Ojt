document.addEventListener("DOMContentLoaded", function () {
  // Get the result containers
  var filterResultsContainer = document.getElementById("filterResultContainer");
  var viewResultsContainer = document.getElementById("resultSection");

  // Get the buttons
  var applyFilterButton = document.getElementById("applyFilter");
  var fetchDataButton = document.getElementById("fetchDataButton");

  // Function to show filter results and hide view results
  function showFilterResults() {
    filterResultsContainer.style.display = "block";
    viewResultsContainer.style.display = "none";
  }

  // Function to show view results and hide filter results
  function showViewResults() {
    viewResultsContainer.style.display = "block";
    filterResultsContainer.style.display = "none";
  }

  // Add event listeners to the buttons
  if (applyFilterButton) {
    applyFilterButton.addEventListener("click", showFilterResults);
  } else {
    console.error("applyFilter not found");
  }

  if (fetchDataButton) {
    fetchDataButton.addEventListener("click", showViewResults);
  } else {
    console.error("fetchDataButton not found");
  }
});
