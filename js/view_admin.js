$(document).ready(function() {
  // Logic to handle changes in the view selection
  $('#viewSelection').change(function() {
    var selectedView = $(this).val();

    if (selectedView === 'view_studrec') {
      $('#viewStudrecDropdown').show();
      $('#viewCompanyDropdown').hide();
    } else if (selectedView === 'view_company') {
      $('#viewStudrecDropdown').hide();
      $('#viewCompanyDropdown').show(); // Display the company name input field

      $('#companyYear').change(function() {
        if ($(this).val() === 'N/A') {
          $('#companyNameInput').focus();
        }
      });

      $('#fetchDataButton').click(function() {
        var company = $('#companyNameInput').val().trim();
        var companyYear = $('#companyYear').val();
        fetchDataForCompanyPartners(company, companyYear);
      });
    }
  });
  
  // Logic to handle fetching data based on user selection
  $('#fetchDataButton').click(function() {
    var selectedView = $('#viewSelection').val();

    if (selectedView === 'view_studrec') {
      var academicYear = $('#academicYear').val();
      // Fetch data for View 1 (Student Records) based on selected academic year
      fetchDataForStudentRecords(academicYear);
    } else if (selectedView === 'view_company') {
      var company = $('#companyNameInput').val(); // Update this line to capture the input field value
      var companyYear = $('#companyYear').val();
      // Fetch data for View 2 (Company Partners) based on entered company and year
      fetchDataForCompanyPartners(company, companyYear);
      fetchCompanyNames(company);
    }
  });

  function fetchCompanyNames(companyName) {
    // Use the provided companyName or get the input if not provided
    companyName = companyName || $('#companyNameInput').val().trim();
  
    if (companyName !== '') {
      $.ajax({
        type: 'POST',
        url: 'view_admin.php',
        data: {
          action: 'fetch_company_partners',
          company: companyName, // Use the companyName here in the data
          company_year: $('#companyYear').val()
        },
        // Remaining code for success, error handling, etc.
      });
    } else {
      console.log("No company name entered.");
      // Handle scenario where no company name is entered
      // You might want to display a message or take appropriate action to inform the user
    }
  }
  // Function to fetch data for company partners
  function fetchDataForCompanyPartners(company, companyYear) {
    $.ajax({
      type: 'POST',
      url: 'view_admin.php',
      data: {
        action: 'fetch_company_partners',
        company: company,
        company_year: companyYear
      },
      success: function(response) {
        try {
          // Attempt to parse the response as JSON
          var result = JSON.parse(response);
          if (result.success) {
            var data = result.data;

            // Clear the result section before displaying new data
            $('#resultSection').empty();

            // Append the received data directly to the result section
            $('#resultSection').append(data);
          } else {
            // Handle error scenarios if needed
            console.error(result.error);
          }
        } catch (error) {
          console.error("Error parsing JSON:", error);
          // Handle parsing error or invalid JSON response from the server
        }
      },
      error: function(xhr, status, error) {
        console.error(xhr.responseText);
        // Handle other types of errors, such as server-side issues
      }
    });
  }
});
