<?php
include "db_config.php";

session_start();

if (!isset($_SESSION['instructor_name']) && !isset($_SESSION['assistant_name'])) {
  header('location:index.php');
  exit();
}

$conn = pg_connect($connection_string); // Establish a PostgreSQL database connection

function viewOjtRecords($conn, $academic_year = "All", $program = "All") {
  if ($academic_year == "All" && $program == "All") {
    $ojt_program_query = "SELECT * FROM ojt_program;";
    $ojt_program_result = pg_query($conn, $ojt_program_query);
  } else if ($academic_year != "All" && $program == "All") {
    $ojt_program_query = "SELECT * FROM ojt_program WHERE academic_year = $1;";
    $ojt_program_result = pg_query_params($conn, $ojt_program_query, array($academic_year));
  } else if ($academic_year == "All" && $program != "All") {
    $ojt_program_query = "SELECT * FROM ojt_program WHERE program = $1;";
    $ojt_program_result = pg_query_params($conn, $ojt_program_query, array($program));
  } else {
    $ojt_program_query = "SELECT * FROM ojt_program WHERE academic_year = $1 AND program = $2;";
    $ojt_program_result = pg_query_params($conn, $ojt_program_query, array($academic_year, $program));
  }


  if ($ojt_program_result) {
    echo "<h2>OJT Program Table</h2>";
    echo "<div class='table-responsive'>";
    echo "<table class='table table-dark table-hover text-center table-striped'>";
    echo "<thead>
                <tr>
                    <th data-column='id_no'>Student ID</th>
                    <th data-column='last_name'>Last Name</th>
                    <th data-column='first_name'>First Name</th>
                    <th data-column='middle_name'>Middle Name</th>
                    <th data-column='program'>Program</th>
                    <th data-column='yr_lvl'>Year Level</th>
                    <th data-column='academic_year'>Academic Year</th>
                    <th data-column='company_entry_id'>Company ID</th>
                    <th data-column='status'>Status</th>
                    <th data-column='start_date'>Start Date</th>
                    <th data-column='end_date'>End Date</th>
                    <th data-column='rendered_hours'>Rendered Hours</th>
                    <th data-column='internship_plan'>Internship Plan</th>
                    <th data-column='requirements'>Requirements</th>
                    <th data-column='notes'>Notes</th>
                    <th class='edit-delete-icons'></th>
                </tr>
            </thead>
            <tbody>";

    while ($row = pg_fetch_assoc($ojt_program_result)) {
      echo "<tr class='record-row'>";
      echo "<td data-column='id_no'>" . $row['id_no'] . "</td>";
      echo "<td data-column='last_name'>" . $row['last_name'] . "</td>";
      echo "<td data-column='first_name'>" . $row['first_name'] . "</td>";
      echo "<td data-column='middle_name'>" . $row['middle_name'] . "</td>";
      echo "<td data-column='program'>" . $row['program'] . "</td>";
      echo "<td data-column='yr_lvl'>" . $row['yr_lvl'] . "</td>";
      echo "<td data-column='academic_year'>" . $row['academic_year'] . "</td>";
      echo "<td data-column='company_entry_id'>" . $row['company_entry_id'] . "</td>";
      echo "<td data-column='status'>" . $row['status'] . "</td>";
      echo "<td data-column='start_date'>" . $row['start_date'] . "</td>";
      echo "<td data-column='end_date'>" . $row['end_date'] . "</td>";
      echo "<td data-column='rendered_hours'>" . $row['rendered_hours'] . "</td>";
      echo "<td data-column='internship_plan'>" . $row['internship_plan'] . "</td>";
      echo "<td data-column='requirements'>" . $row['requirements'] . "</td>";
      echo "<td data-column='notes'>" . $row['notes'] . "</td>";
      echo "<td class='edit-delete-icons'>";
      echo "<span class='edit-icon' data-id='" . $row['id_no'] . "'data-table='ojt_program'><i class='fas fa-edit'></i></span>";
      echo "<span class='delete-icon' data-id='" . $row['id_no'] . "'data-table='ojt_program'><i class='fas fa-trash'></i></span>";
      echo "</td>";

      echo "</tr>";
    }

    echo "</tbody></table></div>";
  } else {
    echo "Error retrieving records: " . pg_last_error($conn);
  }
}


function viewCompanies($conn, $academic_year = "All") {
  if ($academic_year == "All") {
    $companies_query = "SELECT * FROM companies";
    $companies_result = pg_query($conn, $companies_query);
  } else {
    $companies_query = "SELECT * FROM companies WHERE date = $1";
    $companies_result = pg_query_params($conn, $companies_query, array($academic_year));
  }

  if ($companies_result) {
    echo "<h2>Companies</h2>";
    echo "<div class='table-responsive'>";
    echo "<table class='table table-dark table-hover text-center table-striped'>";
    echo "<thead>
              <tr>
                  <th data-column='company_entry_id'>Company ID</th>
                  <th data-column='company_name'>Company Name</th>
                  <th data-column='moa'>MOA</th>
                  <th data-column='date'>Date</th>
                  <th data-column='notes'>Notes</th>
                  <th class='edit-delete-icons'></th>
              </tr>
          </thead>
          <tbody>";

    while ($row = pg_fetch_assoc($companies_result)) {
      echo "<tr class='record-row'>";
      echo "<td data-column='company_entry_id'>" . $row['company_entry_id'] . "</td>";
      echo "<td data-column='company_name'>" . $row['company_name'] . "</td>";
      echo "<td data-column='moa'>" . $row['moa'] . "</td>";
      echo "<td data-column='date'>" . $row['date'] . "</td>";
      echo "<td data-column='notes'>" . $row['notes'] . "</td>";
      echo "<td class='edit-delete-icons'>";
      echo "<span class='edit-icon' data-id='" . $row['company_entry_id'] . "'data-table='companies'><i class='fas fa-edit'></i></span>";
      echo "<span class='delete-icon' data-id='" . $row['company_entry_id'] . "'data-table='companies'><i class='fas fa-trash'></i></span>";
      echo "</td>";
      echo "</tr>";
    }

    echo "</tbody></table></div>";
  } else {
    echo "Error retrieving records: " . pg_last_error($conn);
  }
}


function viewReqs($conn)
{
  $requirements_query = "SELECT * FROM requirements";
  $requirements_result = pg_query($conn, $requirements_query);

  if ($requirements_result) {
    echo "<h2>Requirements</h2>";
    echo "<div class='table-responsive'>";
    echo "<table class='table table-dark table-hover text-center table-striped'>";
    echo "<thead>
              <tr>
                  <th data-column='rq_id'>Req ID</th>
                  <th data-column='company_entry_id'>Company ID</th>
                  <th data-column='id_no'>Stud ID</th>
                  <th data-column='pc'>PC</th>
                  <th data-column='sp'>SP</th>
                  <th data-column='cogh'>CoGH</th>
                  <th data-column='hi'>HI</th>
                  <th data-column='tff'>TFF</th>
                  <th data-column='sff'>SFF</th>
                  <th data-column='coc'>COC</th>
                  <th data-column='tr'>TR</th>
                  <th data-column='htee'>HTEE</th>
                  <th class='edit-delete-icons'></th>
              </tr>
          </thead>
          <tbody>";

    while ($row = pg_fetch_assoc($requirements_result)) {
      echo "<tr class='record-row'>";
      echo "<td data-column='rq_id'>" . $row['rq_id'] . "</td>";
      echo "<td data-column='company_entry_id'>" . $row['company_entry_id'] . "</td>";
      echo "<td data-column='id_no'>" . $row['id_no'] . "</td>";
      echo "<td data-column='pc'><input type='checkbox' " . ($row['pc'] === 't' ? 'checked' : '') . " disabled></td>";
      echo "<td data-column='sp'><input type='checkbox' " . ($row['sp'] === 't' ? 'checked' : '') . " disabled></td>";
      echo "<td data-column='cogh'><input type='checkbox' " . ($row['cogh'] === 't' ? 'checked' : '') . " disabled></td>";
      echo "<td data-column='hi'><input type='checkbox' " . ($row['hi'] === 't' ? 'checked' : '') . " disabled></td>";
      echo "<td data-column='tff'><input type='checkbox' " . ($row['tff'] === 't' ? 'checked' : '') . " disabled></td>";
      echo "<td data-column='sff'><input type='checkbox' " . ($row['sff'] === 't' ? 'checked' : '') . " disabled></td>";
      echo "<td data-column='coc'><input type='checkbox' " . ($row['coc'] === 't' ? 'checked' : '') . " disabled></td>";
      echo "<td data-column='tr'><input type='checkbox' " . ($row['tr'] === 't' ? 'checked' : '') . " disabled></td>";
      echo "<td data-column='htee'><input type='checkbox' " . ($row['htee'] === 't' ? 'checked' : '') . " disabled></td>";
      echo "<td class='edit-delete-icons'>";
      echo "<span class='edit-icon' data-id='" . $row['rq_id'] . "'data-table='requirements'><i class='fas fa-edit'></i></span>";
      echo "<span class='delete-icon' data-id='" . $row['rq_id'] . "'data-table='requirements'><i class='fas fa-trash'></i></span>";
      echo "</td>";
      echo "</tr>";
    }

    echo "</tbody></table></div>";
  } else {
    echo "Error retrieving records: " . pg_last_error($conn);
  }

  
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
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet" integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
  <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="css/style.css">
  <title>Subjects</title>
</head>

<body>
  <header>
    <div class="user-info">
      <h1><?php echo isset($_SESSION['instructor_name']) ? $_SESSION['instructor_name'] : (isset($_SESSION['assistant_name']) ? $_SESSION['assistant_name'] : 'Guest'); ?></h1>
      <p><?php echo isset($_SESSION['instructor_name']) ? 'Instructor' : (isset($_SESSION['assistant_name']) ? 'Assistant' : 'Guest'); ?></p>
    </div>
    <div class="spacer"></div>
    <button class="btn btn-dark" data-bs-toggle="modal" data-bs-target="#importModal">Import Data</button>
    <a href="add_record.php" class="btn btn-dark" role="button">Add Record</a>
    <a href="index_admin.php" class="btn btn-danger" role="button">&#10006;</a>
  </header>


  <!-- Import Modal -->
  <div class="modal fade" id="importModal" tabindex="-1" aria-labelledby="importModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content text-center">
        <div class="modal-header">
          <h5 class="modal-title" id="importModalLabel">Import Excel File</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <form action="import_record.php" method="post" enctype="multipart/form-data">
            <div class="mb-3">
              <label for="file" class="form-label">Choose Excel File:</label>
              <input type="file" class="form-control" id="file" name="file" accept=".xlsx, .xls">
            </div>
            <div class="mb-3">
              <label for="importType" class="form-label">Select Import Type:</label>
              <select class="form-select" id="importType" name="importType">
                <option value="ojt_program">OJT Program</option>
                <option value="companies">Companies</option>
              </select>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
              <button type="submit" class="btn btn-danger" name="submit">Import</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>

  <?php 
    $academic_year = isset($_GET['academic_year']) ? $_GET['academic_year'] : 'All';
    $program = isset($_GET['program']) ? $_GET['program'] : "All";
  ?>

  <!-- Academic Year Select and Table Select Dropdowns -->
  <form id="editPageFilterForm" class="editPageFilterForm">
  <label class="editPageLabelFilter">Filter By:</label>
    <div class="editPageFilterFormContent">
      <div class="editPageFilterFormSection">
        <label for="editPageAcademicYearSelect">Academic Year:</label>
        <select id="editPageAcademicYearSelect" name="academic_year" class="editPageFilterFormSelect">
          <option value="All" <?php echo $academic_year == "All" ? "selected" : ""; ?>>All</option>
          <option value="2020-2021" <?php echo $academic_year == "2020-2021" ? "selected" : ""; ?>>2020-2021</option>
          <option value="2021-2022" <?php echo $academic_year == "2021-2022" ? "selected" : ""; ?>>2021-2022</option>
          <option value="2022-2023" <?php echo $academic_year == "2022-2023" ? "selected" : ""; ?>>2022-2023</option>
        </select>
      </div>

      <div class="editPageFilterFormSection">
        <label for="editPageProgramSelect">Program:</label>
        <select id="editPageProgramSelect" name="program" class="editPageFilterFormSelect">
          <option value="All" <?php echo $program == "All" ? "selected" : ""; ?>>All</option>
          <option value="BSIT" <?php echo $program == "BSIT" ? "selected" : ""; ?>>BSIT</option>
          <option value="BSCS" <?php echo $program == "BSCS" ? "selected" : ""; ?>>BSCS</option>
          <option value="BSIS" <?php echo $program == "BSIS" ? "selected" : ""; ?>>BSIS</option>
        </select>
      </div>

      <div class="editPageFilterFormSection">
        <label for="editPageTableSelect">Table:</label>
        <select id="editPageTableSelect" class="editPageFilterFormSelect">
          <option value="ojt_program">OJT Program</option>
          <option value="companies">Companies</option>
          <option value="requirements">Requirements</option>
        </select>
      </div>
    </div>

    <div class="editPageFilterFormButton">
      <button type="submit" class="btn btn-danger">Apply Filter</button>
    </div>
  </form>

  <div class="table-responsive">
    <div id="editPageOjtProgram">
      <?php viewOjtRecords($conn, $academic_year, $program); ?>
    </div>
    <div id="editPageCompanies" style="display: none;">
      <?php viewCompanies($conn, $academic_year); ?>
    </div>
    <div id="editPageRequirements" style="display: none;">
      <?php viewReqs($conn); ?>
    </div>
  </div>

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
  </div>

  </div>

  <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
  <script src="js/edit_del.js"></script>
  <script src="js/table_selection.js"></script>
</body>

</html>

<?php
pg_close($conn); // Close the database connection at the end of the script
?>