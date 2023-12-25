$(document).ready(function () {
  // Logic to handle changes in the view selection
  $("#viewSelection").change(function () {
    var selectedView = $(this).val();

    if (selectedView === "view_studrec") {
      $("#viewStudrecDropdown").show();
      $("#viewCompanyDropdown").hide();
    } else if (selectedView === "view_company") {
      $("#viewStudrecDropdown").hide();
      $("#viewCompanyDropdown").show();
    } else if (selectedView === "view_comstud") {
      $("#viewStudrecDropdown").hide();
      $("#viewCompanyDropdown").hide();
      $("#viewComStudDropdown").show();
    }
  });

  // Logic to handle fetching data based on user selection
  $("#fetchDataButton").click(function () {
    var selectedView = $("#viewSelection").val();

    if (selectedView === "view_studrec") {
      var academicYear = $("#academicYear").val();
      // Fetch data for View 1 (Student Records) based on selected academic year
      fetchDataForStudentRecords(academicYear);
    } else if (selectedView === "view_company") {
      var companyYear = $("#companyYear").val();
      // Fetch data for View 2 (Company Partners) based on entered year
      fetchDataForCompanyPartners(companyYear);
    } else if (selectedView === "view_comstud") {
      var companyName = $("#companyNameInput").val();
      // Fetch data for View 3 (Deployed Students under a Company)
      fetchStudentsForCompany(companyName);
    }
  });

  function fetchDataForStudentRecords(academicYear) {
    $.ajax({
      type: "POST",
      url: "view_admin.php",
      data: {
        action: "fetch_student_records",
        academic_year: academicYear,
      },
      success: function (response) {
        try {
          var result = JSON.parse(response);
          if (result.success) {
            var data = result.data;

            // Display the received data in a specific HTML element
            $("#resultSection").empty().append(data); // Assuming there's an element with id 'resultSection'
          } else {
            console.error(result.error);
          }
        } catch (error) {
          console.error("Error parsing JSON:", error);
        }
      },
      error: function (xhr, status, error) {
        console.error(xhr.responseText);
      },
    });
  }

  function fetchDataForCompanyPartners(companyYear) {
    $.ajax({
      type: "POST",
      url: "view_admin.php",
      data: {
        action: "fetch_company_partners",
        company_year: companyYear,
      },
      success: function (response) {
        try {
          var result = JSON.parse(response);
          if (result.success) {
            var data = result.data;

            // Display the received data in a specific HTML element
            $("#resultSection").empty().append(data); // Assuming there's an element with id 'resultSection'
          } else {
            console.error(result.error);
          }
        } catch (error) {
          console.error("Error parsing JSON:", error);
        }
      },
      error: function (xhr, status, error) {
        console.error(xhr.responseText);
      },
    });
  }

  // Function to fetch deployed students under a specified company
  function fetchStudentsForCompany(companyName) {
    $.ajax({
      type: "POST",
      url: "view_admin.php",
      data: {
        action: "fetch_students_for_company",
        company_name: companyName,
      },
      success: function (response) {
        try {
          var result = JSON.parse(response);
          if (result.success) {
            var data = result.data;

            // Display the received data in a specific HTML element
            $("#resultSection").empty().append(data); // Assuming there's an element with id 'resultSection'
          } else {
            console.error(result.error);
          }
        } catch (error) {
          console.error("Error parsing JSON:", error);
        }
      },
      error: function (xhr, status, error) {
        console.error(xhr.responseText);
      },
    });
  }
});
