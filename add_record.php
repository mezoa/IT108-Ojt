<?php
include "db_config.php";

session_start();

if (!isset($_SESSION['instructor_name'])) {
   header('location:index.php');
}

if (isset($_POST["submit"])) {
   $table = $_POST['table_selection'];
   $attributes = json_decode($_POST['attributes']);
   $attributeValues = [];

   // Retrieve attribute values from the form
   foreach ($attributes as $attribute) {
      $attributeValues[$attribute] = $_POST[$attribute];
   }

   // Construct the SQL query to insert a new record into the selected table
   $sql = "INSERT INTO $table (" . implode(", ", $attributes) . ") VALUES ('" . implode("', '", $attributeValues) . "')";

   $result = pg_query($conn, $sql);

   if ($result) {
      echo "<script>alert('Record added successfully to $table');</script>";
   } else {
      echo "Failed: " . pg_last_error($conn);
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
   <title>Add Event</title>

   <script>
      function populateAttributes() {
         var selectedTable = document.getElementById("table_selection").value;

         var tableAttributes = {
            "ojt_program": ["id_no", "last_name", "first_name", "middle_name", "program", "yr_lvl", "email", "contact_no", "company_entry_id", "academic_year", "status", "start_date", "end_date", "rendered_hours", "internship_plan", "requirements", "notes"],
            "companies": ["company_name", "moa", "date", "notes"],
            "requirements": ["rq_id", "company_entry_id", "id_no", "pc", "sp", "cogh", "hi", "tff", "sff", "coc", "tr", "htee"]
         };

         var attributes = tableAttributes[selectedTable];

         var attributeInputs = document.getElementById("attribute_inputs");
         attributeInputs.innerHTML = "";

         attributes.forEach(function(attribute) {
            var inputField = document.createElement("div");
            inputField.classList.add("mb-3");
            inputField.innerHTML = `
               <label class="form-label">${attribute.replace(/_/g, ' ').toUpperCase()}</label>
               <input type="text" class="form-control" name="${attribute}" placeholder="Enter ${attribute.replace(/_/g, ' ')}">
            `;
            attributeInputs.appendChild(inputField);
         });

         // Add hidden input fields to store attribute names
         var hiddenAttributesField = document.createElement("input");
         hiddenAttributesField.setAttribute("type", "hidden");
         hiddenAttributesField.setAttribute("name", "attributes");
         hiddenAttributesField.setAttribute("value", JSON.stringify(attributes));
         attributeInputs.appendChild(hiddenAttributesField);
      }
   </script>
</head>

<body>
   <header>
      <div class="user-info">
         <h1><?php echo $_SESSION['instructor_name'] ?></h1>
         <p>Instructor</p>
      </div>
      <div class="spacer"></div>
      <a href="index_view.php" class="btn btn-danger" role="button">&#10006;</a>
   </header>

   <div class="container isWhite">
      <div class="text-center mb-4">
         <h4>Add Record</h4>
      </div>

      <div class="container d-flex justify-content-center">
         <form action="" method="post" style="width:50vw; min-width:300px;">
            <div class="mb-3">
               <label class="form-label">Select Table:</label>
               <select class="form-select" name="table_selection" id="table_selection" onchange="populateAttributes()">
                  <option value="ojt_program">OJT Program</option>
                  <option value="companies">Companies</option>
                  <option value="requirements">Requirements</option>
               </select>
            </div>

            <!-- This div will be dynamically populated based on the selected table -->
            <div id="attribute_inputs"></div>

            <div class="float-end">
               <button type="submit" class="btn btn-success" name="submit">Save</button>
               <a href="index_view.php" class="btn btn-danger">Cancel</a>
            </div>
         </form>
      </div>
   </div>

   <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous"></script>
</body>

</html>