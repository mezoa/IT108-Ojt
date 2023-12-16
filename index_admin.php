<?php
include "db_config.php";

session_start();

if (!isset($_SESSION['instructor_name'])) {
  header('location:index.php');
  exit();
}

// Construct the SQL query based on $viewQuery or any other criteria
$sql = "SELECT * FROM ojt_program WHERE academic_year = '2020-2021'";

// Fetch data based on the selected query
$result = pg_query($conn, $sql);

if (!$result) {
  // Handle query errors here, if any
  $errorMessage = "Error executing query: " . pg_last_error($conn);
  // Redirect with error message
  header("location:index_admin.php?error=" . urlencode($errorMessage));
  exit();
}

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
  <div id="particles-js"></div>
  <canvas id="canvas" width="32" height="32"></canvas>

  <header>
    <div class="user-info">
      <h1><span><?php echo $_SESSION['instructor_name'] ?></span></h1>
      <p>Instructor</p>
    </div>
    <div class="spacer"></div>
    <a href="index_view.php" class="ghost">Edit Record</a>
    <button type="button" class="btn btn-light" data-bs-toggle="modal" data-bs-target="#filterSortingContainer">Filter</button>
    <button type="button" class="btn btn-light" data-bs-toggle="modal" data-bs-target="#viewQuery">View</button>
    <a href="logout.php" class="ghost">Logout</a>
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
  <!-- Filter and Sorting Container Modal -->
<div class="modal fade" id="filterSortingContainer" tabindex="-1" aria-labelledby="filterSortingContainerLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
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
                            <input type="checkbox" id="last_name" name="columns[]" value="ojt_program.last_name" data-table="ojt_program">
                            <label for="last_name">Last Name</label>
                        </div>
                        <div class="checkbox-group">
                            <input type="checkbox" id="first_name" name="columns[]" value="ojt_program.first_name" data-table="ojt_program">
                            <label for="first_name">First Name</label>
                        </div>
                    </div>
                    <div class="checkbox-row">
                        <div class="checkbox-group">
                            <input type="checkbox" id="middle_name" name="columns[]" value="ojt_program.middle_name" data-table="ojt_program">
                            <label for="middle_name">Middle Name</label>
                        </div>
                        <div class="checkbox-group">
                            <input type="checkbox" id="program" name="columns[]" value="ojt_program.program" data-table="ojt_program">
                            <label for="program">Program</label>
                        </div>
                        <div class="checkbox-group">
                            <input type="checkbox" id="yr_lvl" name="columns[]" value="ojt_program.yr_lvl" data-table="ojt_program">
                            <label for="yr_lvl">Year Level</label>
                        </div>
                    </div>
                    <div class="checkbox-row">
                        <div class="checkbox-group">
                            <input type="checkbox" id="email" name="columns[]" value="ojt_program.email" data-table="ojt_program">
                            <label for="email">Email</label>
                        </div>
                        <div class="checkbox-group">
                            <input type="checkbox" id="contact_no" name="columns[]" value="ojt_program.contact_no" data-table="ojt_program">
                            <label for="contact_no">Contact No.</label>
                        </div>
                        <div class="checkbox-group">
                            <input type="checkbox" id="company_entry_id" name="columns[]" value="ojt_program.company_entry_id" data-table="ojt_program">
                            <label for="company_entry_id">Company ID</label>
                        </div>
                    </div>
                    <div class="checkbox-row">
                        <div class="checkbox-group">
                            <input type="checkbox" id="academic_year" name="columns[]" value="ojt_program.academic_year" data-table="ojt_program">
                            <label for="academic_year">A.Y.</label>
                        </div>
                        <div class="checkbox-group">
                            <input type="checkbox" id="status" name="columns[]" value="ojt_program.status" data-table="ojt_program">
                            <label for="status">Status</label>
                        </div>
                        <div class="checkbox-group">
                            <input type="checkbox" id="start_date" name="columns[]" value="ojt_program.start_date" data-table="ojt_program">
                            <label for="start_date">Start Date</label>
                        </div>
                    </div>
                    <div class="checkbox-row">
                        <div class="checkbox-group">
                            <input type="checkbox" id="end_date" name="columns[]" value="ojt_program.end_date" data-table="ojt_program">
                            <label for="end_date">End Date</label>
                        </div>
                        <div class="checkbox-group">
                            <input type="checkbox" id="rendered_hours" name="columns[]" value="ojt_program.rendered_hours" data-table="ojt_program">
                            <label for="rendered_hours">Rendered Hours</label>
                        </div>
                        <div class="checkbox-group">
                            <input type="checkbox" id="internship_plan" name="columns[]" value="ojt_program.internship_plan" data-table="ojt_program">
                            <label for="internship_plan">Internship Plan</label>
                        </div>
                    </div>
                    <div class="checkbox-row">
                        <div class="checkbox-group">
                            <input type="checkbox" id="requirements" name="columns[]" value="ojt_program.requirements" data-table="ojt_program">
                            <label for="requirements">Requirements</label>
                        </div>
                        <div class="checkbox-group">
                            <input type="checkbox" id="notes" name="columns[]" value="ojt_program.notes" data-table="ojt_program">
                            <label for="notes">Notes</label>
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
                        <div class="checkbox-group">
                            <input type="checkbox" id="moa" name="columns[]" value="companies.moa" data-table="companies">
                            <label for="moa">MOA</label>
                        </div>
                    </div>
                    <div class="checkbox-row">
                        <div class="checkbox-group">
                            <input type="checkbox" id="date" name="columns[]" value="companies.date" data-table="companies">
                            <label for="date">Date</label>
                        </div>
                        <div class="checkbox-group">
                            <input type="checkbox" id="notes" name="columns[]" value="companies.notes" data-table="companies">
                            <label for="notes">Notes</label>
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
                        <div class="checkbox-group">
                            <input type="checkbox" id="id_no_req" name="columns[]" value="requirements.id_no" data-table="requirements">
                            <label for="id_no_req">Student ID</label>
                        </div>
                    </div>
                    <div class="checkbox-row">
                        <div class="checkbox-group">
                            <input type="checkbox" id="pc" name="columns[]" value="requirements.pc" data-table="requirements">
                            <label for="pc">PC</label>
                        </div>
                        <div class="checkbox-group">
                            <input type="checkbox" id="sp" name="columns[]" value="requirements.sp" data-table="requirements">
                            <label for="sp">SP</label>
                        </div>
                        <div class="checkbox-group">
                            <input type="checkbox" id="cogh" name="columns[]" value="requirements.cogh" data-table="requirements">
                            <label for="cogh">CoGH</label>
                        </div>
                    </div>
                    <div class="checkbox-row">
                        <div class="checkbox-group">
                            <input type="checkbox" id="hi" name="columns[]" value="requirements.hi" data-table="requirements">
                            <label for="hi">HI</label>
                        </div>
                        <div class="checkbox-group">
                            <input type="checkbox" id="tff" name="columns[]" value="requirements.tff" data-table="requirements">
                            <label for="tff">TFF</label>
                        </div>
                        <div class="checkbox-group">
                            <input type="checkbox" id="sff" name="columns[]" value="requirements.sff" data-table="requirements">
                            <label for="sff">SFF</label>
                        </div>
                    </div> 
                    <div class="checkbox-row">
                        <div class="checkbox-group">
                            <input type="checkbox" id="coc" name="columns[]" value="requirements.coc" data-table="requirements">
                            <label for="coc">COC</label>
                        </div>
                        <div class="checkbox-group">
                            <input type="checkbox" id="tr" name="columns[]" value="requirements.tr" data-table="requirements">
                            <label for="tr">TR</label>
                        </div>
                        <div class="checkbox-group">
                            <input type="checkbox" id="htee" name="columns[]" value="requirements.htee" data-table="requirements">
                            <label for="htee">HTEE</label>
                        </div>
                    </div> 
                </div>
          </div>
          <!-- Filter Footer -->
           <div class="modal-footer">
                <button type="button" class="btn btn-success mt-3 float-end" id="applyFilter">Apply Filter</button>
                <button type="button" class="btn btn-secondary" id="cancelFilter">Cancel</button>
                <!-- Button to Open Sorting Section -->
                <button type="button" class="btn btn-light" id="openSorting">Sort</button>
            </div>
        </div>
  
        <!-- Sorting Section -->
        <div class="modal-sorting">
          <!-- Sorting Header -->
          <div class="modal-header">
            <h5 class="modal-title" id="sortingModalLabel">Sort Results</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <!-- Sorting Body -->
          <div class="modal-body">
            <div class="sorting-container">
                <h5 class="sort-header">ID</h5>
                  <div class="sort-row">
                    <div class="sort-check">
                      <input class="sort-check-input" type="checkbox" value="ojt_program.id_no" id="sortById">
                      <label class="sort-check-label" for="sortById">Stud ID</label>
                    </div>
                    <div class="sort-check">
                      <input class="sort-check-input" type="checkbox" value="companies.company_entry_id" id="sortById">
                      <label class="sort-check-label" for="sortById">Company ID</label>
                    </div>
                    <div class="sort-check">
                      <input class="sort-check-input" type="checkbox" value="req_id" id="sortById">
                      <label class="sort-check-label" for="sortById">Requirements ID</label>
                    </div>
                  </div>
              </div>
              <div class="sort-container">
                <h5 class="sort-header">Academic Year</h5>
                <div class="sort-row">
                  <div class="sort-check">
                    <input class="sort-check-input" type="checkbox" value="ojt_program.academic_year" id="sortById">
                    <label class="sort-check-label" for="sortById">2020-2021</label>
                  </div>
                  <div class="sort-check">
                    <input class="sort-check-input" type="checkbox" value="ojt_program.academic_year" id="sortById">
                    <label class="sort-check-label" for="sortById">2021-2022</label>
                  </div>
                  <div class="sort-check">
                    <input class="sort-check-input" type="checkbox" value="ojt_program.academic_year" id="sortById">
                    <label class="sort-check-label" for="sortById">2022-2023</label>
                  </div>
                </div>
              </div>
              <div class="sort-container">
                <h5 class="sort-header">Other Columns</h5>
                <div class="sort-row">
                    <div class="sort-check">
                      <input class="sort-check-input" type="checkbox" value="ojt_program.program" id="sortById">
                      <label class="sort-check-label" for="sortById">Program</label>
                    </div>
                    <div class="sort-check">
                      <input class="sort-check-input" type="checkbox" value="ojt_program.yr_lvl" id="sortById">
                      <label class="sort-check-label" for="sortById">Year Level</label>
                    </div>
                    <div class="sort-check">
                      <input class="sort-check-input" type="checkbox" value="ojt_program.rendered_hours" id="sortById">
                      <label class="sort-check-label" for="sortById">Rendered Hours</label>
                    </div>
                </div>
                <div class="sort-row">
                  <div class="sort-check">
                    <input class="sort-check-input" type="checkbox" value="companies.moa" id="sortById">
                    <label class="sort-check-label" for="sortById">MOA</label>
                  </div>
                  <div class="sort-check">
                    <input class="sort-check-input" type="checkbox" value="ojt_program.requirements" id="sortById">
                    <label class="sort-check-label" for="sortById">Requirements</label>
                  </div>
                  <div class="sort-check">
                    <input class="sort-check-input" type="checkbox" value="ojt_program.start_date" id="sortById">
                    <label class="sort-check-label" for="sortById">Start Date</label>
                  </div>
                </div>
                <div class="sort-row">
                  <div class="sort-check">
                    <input class="sort-check-input" type="checkbox" value="ojt_program.end_date" id="sortById">
                    <label class="sort-check-label" for="sortById">End Date</label>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <!-- Sorting Footer -->
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            <button type="button" class="btn btn-primary" id="applySorting">Apply Sorting</button>
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
          <label for="companyNameInput" class="form-label">Enter Company Name:</label>
          <input type="text" class="form-control" id="companyNameInput" placeholder="Enter Company Name">
        </div>
        <div class="mb-3">
          <label for="companyYear" class="form-label">Select Academic Year:</label>
          <!-- The academic year selection remains unchanged -->
          <select class="form-select" id="companyYear" required>
            <option value="">-- Select Academic Year --</option>
            <option value="2020-2021">View by Company 2020-2021</option>
            <option value="2021-2022">View by Company 2021-2022</option>
            <option value="2022-2023">View by Company 2022-2023</option>
            <option value="N/A">N/A</option>
          </select>
        </div>
      </div>

      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <!-- Add logic to trigger the data fetching based on the selected view -->
        <button type="button" class="btn btn-primary" id="fetchDataButton">Fetch Data</button>
      </div>
    </div>
  </div>
</div>
  

<div id="filterResultContainer" class="mt-4"></div>
<div id="resultSection"></div>


  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous"></script>
  <script src="js/app.js"></script>
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="js/filter.js"></script>
  <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
  <script src="js/view_admin.js"></script>




</body>

</html>