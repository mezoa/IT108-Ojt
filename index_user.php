<?php
include "db_config.php";

session_start();

if (!isset($_SESSION['student_name'])) {
  header('location:index.php');
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
  <header>
    <div class="user-info">
      <h1><span><?php echo $_SESSION['student_name'] ?></span></h1>
      <p>Student</p>
    </div>
    <div class="spacer"></div>
    <button class="btn btn-dark" data-bs-toggle="modal" data-bs-target="#viewModal">Views</button>
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

    <table class="table table-dark table-hover text-center table-striped">
      <thead>
        <tr>
          <th scope="col">ID Number</th>
          <th scope="col">Full Name</th>
          <th scope="col">Program</th>
          <th scope="col">Year Level</th>
          <th scope="col">Semester</th>
          <th scope="col">Contact Number</th>
          <th scope="col">Email</th>
          <th scope="col">Status</th>
          <th class="sortable sort-date" scope="col">Start Date <i class="fas fa-sort sort-icon"></i></th>
          <th class="sortable sort-date" scope="col">End Date <i class="fas fa-sort sort-icon"></i></th>
          <th scope="col">Rendered Hours</th>
          <th scope="col">Internship Plan</th>
          <th scope="col">Requirements</th>
          <th scope="col">Company Name</th>
        </tr>
      </thead>
      <tbody>
        <?php
        // Assuming $conn is your PostgreSQL connection
        $sql = "SELECT 
                o.id_no,
                o.last_name || ', ' || o.first_name || ' ' || o.middle_name AS full_name,
                o.program,
                o.yr_lvl,
                o.academic_year,
                o.contact_no,
                o.email,
                o.status,
                o.start_date,
                o.end_date,
                o.rendered_hours,
                o.internship_plan,
                o.requirements,
                c.company_name 
            FROM ojt_program AS o
            JOIN companies AS c ON o.company_entry_id = c.company_entry_id";

        // Check if a specific academic year is requested
        if (isset($_GET['academic_year'])) {
          $academicYear = $_GET['academic_year'];
          $sql .= " WHERE o.academic_year = '$academicYear'";
        }

        $result = pg_query($conn, $sql); // Use pg_query for PostgreSQL

        while ($row = pg_fetch_assoc($result)) {
          $startDate = date("F d, Y", strtotime($row["start_date"]));
          $endDate = date("F d, Y", strtotime($row["end_date"]));
        ?>
          <tr>
            <td><?php echo $row["id_no"] ?></td>
            <td><?php echo $row["full_name"] ?></td>
            <td><?php echo $row["program"] ?></td>
            <td><?php echo $row["yr_lvl"] ?></td>
            <td><?php echo $row["academic_year"] ?></td>
            <td><?php echo $row["contact_no"] ?></td>
            <td><?php echo $row["email"] ?></td>
            <td><?php echo $row["status"] ?></td>
            <td><?php echo $startDate ?></td>
            <td><?php echo $endDate ?></td>
            <td><?php echo $row["rendered_hours"] ?></td>
            <td><?php echo $row["internship_plan"] ?></td>
            <td><?php echo $row["requirements"] ?></td>
            <td><?php echo $row["company_name"] ?></td>
          </tr>
        <?php
        }
        ?>
      </tbody>
    </table>
  </div>

  <!-- View Modal -->
  <!-- View Modal -->
  <div class="modal fade" id="viewModal" tabindex="-1" aria-labelledby="viewModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="viewModalLabel">View Academic Year Record</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <div class="mb-3">
            <label for="academicYearSelect" class="form-label">Select Academic Year:</label>
            <select class="form-select" id="academicYearSelect" required>
              <option value="">-- Select Academic Year --</option>
              <option value="stud2020_2021">View Academic Year 2020-2021</option>
              <option value="stud2021_2022">View Academic Year 2021-2022</option>
              <option value="stud2022_2023">View Academic Year 2022-2023</option>
            </select>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            <button type="button" class="btn btn-primary" id="fetchData">Fetch Data</button>
          </div>
        </div>
      </div>
    </div>

    <script>
      // Get the select element
      var academicYearSelect = document.getElementById('academicYearSelect');
      var fetchDataButton = document.getElementById('fetchData');

      // Add an event listener for the click event to the Fetch Data button
      fetchDataButton.addEventListener('click', function() {
        // Get the selected academic year
        var academicYear = academicYearSelect.value;

        // Make an AJAX request to a PHP script that fetches the data for the selected academic year
        var xhr = new XMLHttpRequest();
        xhr.open('GET', 'fetch_foruser.php?academic_year=' + academicYear, true);
        xhr.onload = function() {
          if (this.status == 200) {
            // Insert the returned HTML into the table
            document.querySelector('.table tbody').innerHTML = this.responseText;
          }
        };
        xhr.send();
      });
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous"></script>
    <script src="js/sort.js"></script>
</body>

</html>