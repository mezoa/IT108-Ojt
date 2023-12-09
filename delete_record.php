<?php
include "db_config.php";

session_start();

if (!isset($_SESSION['instructor_name'])) {
   header('location:index.php');
   exit(); // Always exit after a redirect
}

if (isset($_POST["submit"])) {
   if (isset($_POST['table_selection'])) {
      $table = $_POST['table_selection'];
      
      // Fetch the primary key column name for the selected table
      $pk_query = "SELECT column_name FROM information_schema.key_column_usage WHERE table_name = '$table'";
      $pk_result = pg_query($conn, $pk_query);
      
      if ($pk_result) {
         $pk_row = pg_fetch_assoc($pk_result);
         $primary_key = $pk_row['column_name'];

         // Delete a specific record based on the primary key
         $event_data = $_POST['event_data'];

         // Construct the DELETE query
         $sql = "DELETE FROM $table WHERE $primary_key = '$event_data'";
         
         $result = pg_query($conn, $sql);

         if ($result) {
            header("Location: index_view.php?msg=Record(s) deleted successfully");
            exit();
         } else {
            echo "Failed: " . pg_last_error($conn);
         }
      } else {
         echo "Failed to fetch primary key information.";
         exit();
      }
   } else {
      echo "Table selection not set.";
      exit();
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
   <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
   <link rel="stylesheet" href="css/style.css">
   <title>Delete Event</title>

   <script>
   function populateAttributes() {
      var selectedTable = document.getElementById("table_selection").value;

      var primaryKeySelection = document.getElementById("primary_key_selection");
      primaryKeySelection.innerHTML = ""; // Clear previous options

      // Fetch primary key data based on the selected table
      fetch(`fetch_primary_key_data.php?table=${selectedTable}`)
         .then(response => response.json())
         .then(data => {
            data.forEach(key => {
               var option = document.createElement("option");
               option.value = key;
               option.text = key;
               primaryKeySelection.appendChild(option);
            });
         })
         .catch(error => {
            console.error('Error fetching primary key data:', error);
         });
   }
</script>
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
      <a href="add_record.php" class="ghost">Add Event</a>
      <a href="edit_record.php" class="ghost">Edit Event</a>
      <a href="index_view.php" class="ghost">Return</a>
   </header>

   <div class="container isWhite">
      <div class="text-center mb-4">
         <h4>Delete Event to Database</h4>
      </div>

      <div class="container d-flex justify-content-center">
   <form action="" method="post" style="width:50vw; min-width:300px;">
      <div class="mb-3">
         <label class="form-label">Select Table:</label>
         <select class="form-select" name="table_selection" id="table_selection" onchange="populateAttributes()">
            <option value="students">Students</option>
            <option value="ojt_program">OJT Program</option>
            <option value="companies">Companies</option>
            <option value="requirements">Requirements</option>
            <!-- Add more options as per your tables -->
         </select>
      </div>

      <!-- New dropdown for primary key data -->
      <div class="mb-3">
        <label class="form-label">Primary Key Data:</label>
            <input type="text" class="form-control" name="event_data" placeholder="Enter Primary Key Data">
      </div>

      <div class="float-end">
         <button type="submit" class="btn btn-success" name="submit">Save</button>
         <a href="index_view.php" class="btn btn-danger">Cancel</a>
      </div>
   </form>
</div>

   <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous"></script>
   <script src="js/particles.js"></script>
   <script src="js/app.js"></script>
   <script src="js/bg.js"></script>
</body>

</html>
