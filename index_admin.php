<?php
include "db_config.php";

session_start();

if (!isset($_SESSION['instructor_name']) && !isset($_SESSION['assistant_name'])) {
  header('location:index.php');
  exit();
}

// Establish a connection to your PostgreSQL database
$conn = pg_connect($connection_string);
if (!$conn) {
  die("Connection failed: " . pg_last_error());
}

// Construct the SQL query for the default view (view_acadyear_2022_2023)
$defaultViewQuery = "SELECT * FROM view_acadyear_2022_2023 ";

// Fetch data based on the default query
$result = pg_query($conn, $defaultViewQuery);

// Fetch column names
$numFields = pg_num_fields($result);
$columns = array();
for ($i = 0; $i < $numFields; $i++) {
  $colName = pg_field_name($result, $i);
  $columns[] = $colName;
}

// Start building the HTML table within a responsive container
$output = '<div class="table-container">';
$output .= '<table class="table table-dark table-hover text-center table-striped" style="max-width: 100%; width: 100%;" border="1">';
$output .= '<tr style="background-color: #f2f2f2;">';
foreach ($columns as $column) {
  $output .= '<th style="padding: 12px; text-align: center;">' . $column . '</th>';
}
$output .= '</tr>';

// Fetch data and construct table rows
$alternate = false;
while ($row = pg_fetch_assoc($result)) {
  $output .= '<tr' . ($alternate ? ' style="background-color: #f9f9f9;"' : '') . '>';
  foreach ($columns as $column) {
    $value = $row[$column];
    // Handle null values
    if ($value === null) {
      $value = ''; // Display 'N/A' for null values
    }
    $output .= '<td style="padding: 12px; text-align: center;">' . $value . '</td>';
  }
  $output .= '</tr>';
  $alternate = !$alternate; // Alternate row colors
}
$output .= '</table>';
$output .= '</div>'; // Close the table container
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="icon" href="assets/icon.ico" type="image/x-icon">
  <link rel="shortcut icon" href="assets/icon.ico" type="image/x-icon">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
  <link rel="stylesheet" href="css/style.css">
  <title>Schedules</title>
</head>

<body>

  <header>
    <div class="user-info">
      <h1><?php echo isset($_SESSION['instructor_name']) ? $_SESSION['instructor_name'] : (isset($_SESSION['assistant_name']) ? $_SESSION['assistant_name'] : 'Guest'); ?></h1>
      <p><?php echo isset($_SESSION['instructor_name']) ? 'Instructor' : (isset($_SESSION['assistant_name']) ? 'Assistant' : 'Guest'); ?></p>
    </div>
    <div class="spacer"></div>
    <a href="index_view.php" class="btn btn-dark" role="button">Edit Record</a>
    <button type="button" class="btn btn-dark" data-bs-toggle="modal" data-bs-target="#filterContainer">Filter</button>
    <button type="button" class="btn btn-dark" data-bs-toggle="modal" data-bs-target="#viewQuery">View</button>
    <a href="logout.php" class="btn btn-danger" role="button">Logout</a>
  </header>

  <div class="container">
    <?php
    if (isset($_GET["msg"])) {
      $msg = $_GET["msg"];
      echo '<div class="alert alert-warning alert-dismissible fade show" role="alert">
      ' . $msg . '
      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>';
    }
    ?>


    <!-- Customized Filter Column Selection Modal -->
    <!-- Filter Container Modal -->
    <div class="modal fade" id="filterContainer" tabindex="-1" aria-labelledby="filterContainerLabel" aria-hidden="true">
      <div class="modal-dialog modal-lg custom-modal-width">
        <div class="modal-content">
          <!-- Filter Section -->
          <div class="modal-filter">
            <!-- Filter Header -->
            <div class="modal-header">
              <h5 class="modal-title" id="filterColumnModalLabel">Filter Columns</h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <!-- Filter Body -->
            <div class="modal-body">
              <!-- Filter Content Here -->
              <div class="table-container">
                <h5 class="table-header">OJT Program Table</h5>
                <div class="checkbox-row">
                  <div class="checkbox-group">
                    <input type="checkbox" id="id_no" name="columns[]" value="ojt_program.id_no" data-table="ojt_program">
                    <label for="id_no">Student ID</label>
                  </div>
                  <div class="checkbox-group">
                    <input type="checkbox" id="full_name" name="columns[]" value="CONCAT(ojt_program.first_name, ' ', ojt_program.middle_name, ' ', ojt_program.last_name) AS full_name" data-table="ojt_program">
                    <label for="full_name">Full Name</label>
                  </div>
                  <div class="checkbox-group">
                    <input type="checkbox" id="program" name="columns[]" value="ojt_program.program" data-table="ojt_program">
                    <label for="program">Program</label>
                  </div>
                </div>
                <div class="checkbox-row">
                  <div class="checkbox-group">
                    <input type="checkbox" id="yr_lvl" name="columns[]" value="ojt_program.yr_lvl" data-table="ojt_program">
                    <label for="yr_lvl">Year Level</label>
                  </div>
                  <div class="checkbox-group">
                    <input type="checkbox" id="email" name="columns[]" value="ojt_program.email" data-table="ojt_program">
                    <label for="email">Email</label>
                  </div>
                  <div class="checkbox-group">
                    <input type="checkbox" id="contact_no" name="columns[]" value="ojt_program.contact_no" data-table="ojt_program">
                    <label for="contact_no">Contact No.</label>
                  </div>
                </div>
                <div class="checkbox-row">
                  <div class="checkbox-group">
                    <input type="checkbox" id="academic_year" name="columns[]" value="ojt_program.academic_year" data-table="ojt_program">
                    <label for="academic_year">A.Y.</label>
                  </div>
                  <div class="checkbox-group">
                    <input type="checkbox" id="rendered_hours" name="columns[]" value="ojt_program.rendered_hours" data-table="ojt_program">
                    <label for="rendered_hours">Rendered Hrs</label>
                  </div>
                  <div class="checkbox-group">
                    <input type="checkbox" id="requirements" name="columns[]" value="ojt_program.requirements" data-table="ojt_program">
                    <label for="requirements">Require</label>
                  </div>
                </div>
              </div>
              <!-- Display checkboxes for Companies table columns -->
              <div class="table-container">
                <h5 class="table-header">Companies Table</h5>
                <div class="checkbox-row">
                  <div class="checkbox-group">
                    <input type="checkbox" id="company_entry_id" name="columns[]" value="companies.company_entry_id" data-table="companies">
                    <label for="company_entry_id">Company ID</label>
                  </div>
                  <div class="checkbox-group">
                    <input type="checkbox" id="company_name" name="columns[]" value="companies.company_name" data-table="companies">
                    <label for="company_name">Company Name</label>
                  </div>
                  
                </div>
                <div class="checkbox-row">
                  <div class="checkbox-group">
                    <input type="checkbox" id="moa" name="columns[]" value="companies.moa" data-table="companies">
                    <label for="moa">MOA</label>
                  </div>
                  <div class="checkbox-group">
                    <input type="checkbox" id="date" name="columns[]" value="companies.date" data-table="companies">
                    <label for="date">Date</label>
                  </div>
                </div>
              </div>
              <!-- Requirements Table Selection -->
              <div class="table-container">
                <h5 class="table-header">Requirements Table</h5>
                <div class="checkbox-row">
                  <div class="checkbox-group">
                    <input type="checkbox" id="rq_id" name="columns[]" value="requirements.rq_id" data-table="requirements">
                    <label for="rq_id">Requirement ID</label>
                  </div>
                  <div class="checkbox-group">
                    <input type="checkbox" id="company_entry_id_req" name="columns[]" value="requirements.company_entry_id" data-table="requirements">
                    <label for="company_entry_id_req">Company ID</label>
                  </div>
                  
                </div>
                <div class="checkbox-row">
                  <div class="checkbox-group">
                      <input type="checkbox" id="id_no_req" name="columns[]" value="requirements.id_no" data-table="requirements">
                      <label for="id_no_req">Student ID</label>
                  </div>
                  <div class="checkbox-group">
                    <input type="checkbox" id="select_all" name="columns[]" value="SELECT * FROM requirements WHERE column_name NOT IN ('company_entry_id', 'id_no')" data-table="requirements">
                    <label for="select_all">Select All</label>
                  </div>
                </div>
              </div>
            </div>
            <!-- Filter Footer -->
            <div class="modal-footer">
              <button type="button" class="btn btn-danger" id="applyFilter">Apply Filter</button>
              <button type="button" class="btn btn-secondary" id="cancelFilter" data-bs-dismiss="modal">Cancel</button>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- View Button -->
    <div class="modal fade" id="viewQuery" tabindex="-1" aria-labelledby="viewQueryLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="viewQueryLabel">Select View</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <div class="mb-3">
              <label for="viewSelection" class="form-label">Select View:</label>
              <select class="form-select" id="viewSelection" required>
                <option value="">-- Select View --</option>
                <option value="view_studrec">View Student Records</option>
                <option value="view_company">View Company Partners</option>
                <option value="view_comstud">View Deployed Students</option>
              </select>
            </div>

            <!-- View 1: Student Records Dropdowns -->
            <div id="viewStudrecDropdown" style="display: none;">
              <div class="mb-3">
                <label for="academicYear" class="form-label">Select Academic Year:</label>
                <select class="form-select" id="academicYear" required>
                  <option value="">-- Select Academic Year --</option>
                  <option value="2020_2021">View Academic Year 2020-2021</option>
                  <option value="2021_2022">View Academic Year 2021-2022</option>
                  <option value="2022_2023">View Academic Year 2022-2023</option>
                </select>
              </div>
            </div>

            <!-- View 2: Company Partners Input Fields -->
            <div id="viewCompanyDropdown" style="display: none;">
              <div class="mb-3">
                <label for="companyYear" class="form-label">Select Academic Year:</label>
                <select class="form-select" id="companyYear" required>
                  <option value="">-- Select Academic Year --</option>
                  <option value="2020-2021">View by Company 2020-2021</option>
                  <option value="2021-2022">View by Company 2021-2022</option>
                  <option value="2022-2023">View by Company 2022-2023</option>
                  <option value="N/A">N/A</option>
                </select>
              </div>
            </div>

            <!-- View 3: Student Deployed in the Company -->
            <div id="viewComStudDropdown" style="display: none;">
              <div class="mb-3">
                <label for="companyName" class="form-label">Enter Company Name:</label>
                <input type="text" class="form-control" id="companyNameInput" placeholder="Enter Company Name">
              </div>
            </div>

          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            <!-- Add logic to trigger the data fetching based on the selected view -->
            <button type="button" class="btn btn-danger" id="fetchDataButton">Fetch Data</button>
          </div>
        </div>
      </div>
    </div>


    <div id="filterResultContainer" class="mt-4"></div>
    <div id="resultSection">
    <?php echo $output; ?>
    </div>

   

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="js/filter.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script src="js/view_admin.js"></script>
    <script src="js/toggleResults.js"></script>



</body>

</html>