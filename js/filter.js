document.addEventListener("DOMContentLoaded", function () {
  var applyFilterBtn = document.getElementById("applyFilter");
  var cancelFilterBtn = document.getElementById("cancelbtn");
  var applySortingBtn = document.getElementById("applySorting");

  if (cancelFilterBtn) {
    cancelFilterBtn.addEventListener('click', function () {
      // Uncheck all checkboxes in the modal
      var checkboxes = document.querySelectorAll('input[type="checkbox"]');
      checkboxes.forEach(checkbox => {
        checkbox.checked = false;
      });

      // Clear the generated table
      var filterResultContainer = document.getElementById("filterResultContainer");
      if (filterResultContainer) {
        filterResultContainer.innerHTML = ''; // Clear the content
      }
    });
  }

  applyFilterBtn.addEventListener('click', function () {
      var selectedColumns = getSelectedColumns();

      if (selectedColumns.length > 0) {
          var customQuery = buildCustomQuery(selectedColumns);

          // Fetch data based on custom query
          var formData = new FormData();
          formData.append('customQuery', customQuery);

          fetch('custom_query.php', {
              method: 'POST',
              body: formData
          })
          .then(response => {
              if (!response.ok) {
                  throw new Error('Network response was not ok.');
              }
              return response.text(); // Assuming the response should be text
          })
          .then(data => {
              // Display data in the filterResultContainer
              document.getElementById("filterResultContainer").innerHTML = data;
          })
          .catch(error => console.error('Error:', error));
      } else {
          console.log("No columns selected");
      }
  });

  applySortingBtn.addEventListener('click', function () {
    var selectedSortOptions = getSelectedSortOptions();

    if (selectedSortOptions.length > 0) {
      // You can handle the selected sorting options here
      console.log(selectedSortOptions); // Placeholder logic, replace with sorting implementation
    } else {
      console.log("No sorting options selected");
    }
  });


  function getSelectedColumns() {
    var selectedColumns = [];
    var columnCheckboxes = document.querySelectorAll('input[type="checkbox"]:checked');
    columnCheckboxes.forEach(checkbox => {
      var checkboxValue = checkbox.value;
      var tableName = checkbox.dataset.table; // Access the data-table attribute
      if (checkboxValue && tableName && !selectedColumns.some(col => col.column === checkboxValue && col.table === tableName)) {
        selectedColumns.push({ table: tableName, column: checkboxValue });
      }
    });
    return selectedColumns;
  }

  function buildCustomQuery(columns) {
    var customQuery = "SELECT ";
  
    if (columns.length > 0) {
      var selectParts = [];
      var tableNames = [];
      var hasCompanies = false;
      var hasRequirements = false;
  
      columns.forEach(col => {
        if (col.table === 'ojt_program') {
          selectParts.push(col.column); // Use column name directly
          tableNames.push('ojt_program');
        } else if (col.table === 'companies') {
          hasCompanies = true;
          if (!tableNames.includes('companies')) {
            tableNames.push('companies');
            selectParts.push('companies.company_entry_id', 'companies.company_name');
          }
        } else if (col.table === 'requirements') {
          hasRequirements = true;
          if (!tableNames.includes('requirements')) {
            tableNames.push('requirements');
            selectParts.push(
              'requirements.rq_id',
              'requirements.pc',
              'requirements.sp',
              'requirements.cogh',
              'requirements.hi',
              'requirements.tff',
              'requirements.sff',
              'requirements.coc',
              'requirements.tr',
              'requirements.htee'
            );
          }
        }
      });
  
      customQuery += selectParts.join(', ');
  
      // Constructing FROM part of the query
      customQuery += " FROM ojt_program"; // Always include ojt_program table
  
      // Check if companies table is needed and join it
      if (hasCompanies) {
        customQuery += ' LEFT JOIN companies ON ojt_program.company_entry_id = companies.company_entry_id';
      }
  
      // Check if requirements table is needed and join it
      if (hasRequirements) {
        customQuery += ' LEFT JOIN requirements ON ojt_program.id_no = requirements.id_no';
      }
    } else {
      customQuery += "*"; // Select all columns if none is selected
      customQuery += " FROM ojt_program"; // Default table
    }
  
    return customQuery;
  }

  //Sorting
  applySortingBtn.addEventListener('click', function () {
    var selectedSortOptions = getSelectedSortOptions();
  
    if (selectedSortOptions.length > 0) {
      // Construct sorting query based on selected attributes
      var sortingQuery = buildSortingQuery(selectedSortOptions);
  
      // Fetch data based on the modified query
      var formData = new FormData();
      formData.append('sortingQuery', sortingQuery);
  
      fetch('custom_query.php', {
        method: 'POST',
        body: formData
      })
      .then(response => {
        if (!response.ok) {
          throw new Error('Network response was not ok.');
        }
        return response.text(); // Assuming the response should be text
      })
      .then(data => {
        // Display data in the filterResultContainer or handle as needed
        document.getElementById("filterResultContainer").innerHTML = data;
      })
      .catch(error => console.error('Error:', error));
    } else {
      console.log("No sorting options selected");
    }
  });

  function buildSortingQuery(selectedSortOptions) {
  // Construct an ORDER BY clause based on selected attributes
  var orderByClause = 'ORDER BY ';

  // Join selected attributes for sorting
  var orderByAttributes = selectedSortOptions.join(', ');

  // Customize the order based on ascending or descending selection
  // Here, assuming all selected attributes are sorted in ascending order
  orderByClause += orderByAttributes + ' ASC';

  // You may customize the above logic based on your application's requirements

  return orderByClause;
}

function getSelectedSortOptions() {
  var selectedSortOptions = [];
  // Logic to retrieve selected sorting options (similar to getSelectedColumns)

  // Example logic:
  var sortCheckboxes = document.querySelectorAll('#sortingContainer input[type="checkbox"]:checked');
  sortCheckboxes.forEach(checkbox => {
    var checkboxValue = checkbox.value;
    if (checkboxValue) {
      selectedSortOptions.push(checkboxValue);
    }
  });

  return selectedSortOptions;
}

});
