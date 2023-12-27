document
  .getElementById("editPageTableSelect")
  .addEventListener("change", function () {
    // Hide all tables
    document.getElementById("editPageOjtProgram").style.display = "none";
    document.getElementById("editPageCompanies").style.display = "none";
    document.getElementById("editPageRequirements").style.display = "none";

    // Show the selected table
    var selectedTable = this.value;
    document.getElementById(
      "editPage" +
        selectedTable.charAt(0).toUpperCase() +
        selectedTable.slice(1)
    ).style.display = "block";
  });

document
  .getElementById("editPageFilterForm")
  .addEventListener("submit", function (event) {
    // Prevent the form from being submitted normally
    event.preventDefault();

    // Get the selected academic year and program
    var academicYear = document.getElementById(
      "editPageAcademicYearSelect"
    ).value;
    var program = document.getElementById("editPageProgramSelect").value;
    var table = document.getElementById("editPageTableSelect").value;

    // Get the current URL
    var url = new URL(window.location.href);

    // Set the academic_year and program parameters in the URL
    url.searchParams.set("academic_year", academicYear);
    if (table === "ojt_program") {
      url.searchParams.set("program", program);
    } else {
      url.searchParams.delete("program");
    }

    // Reload the page with the new URL
    window.location.href = url.href;
  });
