<?php
include "db_config.php";

session_start();

if (!isset($_SESSION['instructor_name'])) {
   header('location:index.php');
   
}

if (isset($_POST["submit"])) {
   $table = $_POST['table_selection'];
   $attribute = $_POST['attribute_selection'];
   $event_data = $_POST['event_data'];
   $primary_key = $_POST['primary_key'];

   // Validate the submitted data (perform more robust validation as needed)
   if (!empty($table) && !empty($attribute) && !empty($event_data) && !empty($primary_key)) {
      $sql = "UPDATE $table SET $attribute = '$event_data' WHERE id_no = '$primary_key'";
      $result = pg_query($conn, $sql);

      if ($result) {
         header("Location: index_view.php?msg=Record updated successfully");
         exit();
      } else {
         echo "Failed: " . pg_last_error($conn);
      }
   } else {
      echo "All fields are required!";
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
   <title>Edit Event</title>

   <script>
      function populateAttributes() {
         var selectedTable = document.getElementById("table_selection").value;

         var tableAttributes = {
            "ojt_program": ["ojt_code", "id_no", "company_entry_id","semester", "status", "start_date", "end_date", "rendered_hours", "internship_plan", "requirements", "notes"],
            "companies": ["company_entry_id", "company_name", "moa", "date", "notes"],
            "requirements": ["rq_id", "ojt_code", "company_entry_id", "id_no", "pc", "sp", "cogh", "hi", "tff", "sff", "coc", "tr", "htee"]
         };

         var attributes = tableAttributes[selectedTable];

         var attributeSelection = document.getElementById("attribute_selection");
         attributeSelection.innerHTML = "";

         attributes.forEach(function (attribute) {
            var option = document.createElement("option");
            option.value = attribute;
            option.text = attribute;
            attributeSelection.appendChild(option);
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
      <a href="delete_record.php" class="ghost">Delete Event</a>
      <a href="index_view.php" class="ghost">Return</a>
   </header>

   <div class="container isWhite">
      <div class="text-center mb-4">
         <h4>Edit Event to Database</h4>
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

            <div class="mb-3">
               <label class="form-label">Select Attribute:</label>
               <select class="form-select" name="attribute_selection" id="attribute_selection">
                  <!-- Options will be dynamically populated based on the selected table -->
               </select>
            </div>

            <div class="mb-3">
               <label class="form-label">Event Data:</label>
               <input type="text" class="form-control" name="event_data" placeholder="Enter Event Data">
            </div>
            <div class="mb-3">
               <label class="form-label">Primary Key:</label>
               <input type="text" class="form-control" name="primary_key" placeholder="Enter Primary Key">
            </div>
            <div class="float-end">
               <button type="submit" class="btn btn-success" name="submit">Save</button>
               <a href="index_view.php" class="btn btn-danger">Cancel</a>
            </div>
         </form>
      </div>
   </div>

   <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous"></script>
   <script src="js/particles.js"></script>
   <script src="js/app.js"></script>
   <script src="js/bg.js"></script>
</body>

</html>
