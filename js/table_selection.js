document.getElementById("tableSelect").addEventListener("change", function () {
  // Hide all tables
  document.getElementById("ojt_program").style.display = "none";
  document.getElementById("companies").style.display = "none";
  document.getElementById("requirements").style.display = "none";

  // Show selected table
  document.getElementById(this.value).style.display = "block";
});

document
  .getElementById("academicYearSelect")
  .addEventListener("change", function () {
    // Reload the page with the selected academic year as a URL parameter
    window.location.href =
      window.location.pathname + "?academic_year=" + this.value;
  });
