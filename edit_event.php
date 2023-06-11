<?php
include "db_config.php";

session_start();

if (!isset($_SESSION['instructor_name'])) {
  header('location:index.php');
}

$id = $_GET["id"];

if (isset($_POST["submit"])) {
  $building_name = $_POST['building_name'];
  $room_number = $_POST['room_number'];
  $subject_id = $_POST['subject_id'];
  $event_date = $_POST['event_date'];
  $event_time = $_POST['event_time'];

  $sql = "UPDATE `event_tbl` SET `building_name`='$building_name',`room_number`='$room_number',`subject_id`='$subject_id',`event_date`='$event_date',`event_time`='$event_time' WHERE event_id = $id";

  $result = mysqli_query($conn, $sql);

  if ($result) {
    header("Location: index_admin.php?msg=Data updated successfully");
  } else {
    echo "Failed: " . mysqli_error($conn);
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
  <title>Event</title>
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
    <a href="index_subject.php" class="ghost">Return</a>
  </header>

  <div class="container isWhite">
    <div class="text-center mb-4">
      <h4>Edit information</h4>
    </div>

    <?php
    $sql = "SELECT * FROM event_tbl WHERE event_id = $id LIMIT 1";
    $result = mysqli_query($conn, $sql);
    $row = mysqli_fetch_assoc($result);
    ?>

    <div class="container d-flex justify-content-center">
      <form action="" method="post" style="width:50vw; min-width:300px;">
        <div class="row mb-3">
          <div class="col">
            <label class="form-label">Building Name</label>
            <input type="text" class="form-control" name="building_name" placeholder="CCIS" required value="<?php echo $row['building_name'] ?>">
          </div>

          <div class="col">
            <label class="form-label">Room Number</label>
            <input type="text" class="form-control" name="room_number" placeholder="CL1" required value="<?php echo $row['room_number'] ?>">
          </div>

          <div class="col">
            <label class="form-label" for="inputState">Subject ID</label>
            <select id="inputState" class="form-control" name="subject_id" required>
              <?php
              $query = "SELECT * FROM subject_tbl";
              $result = mysqli_query($conn, $query);
              $selectedSubjectId = $row['subject_id'];

              while ($row = mysqli_fetch_assoc($result)) {
                $subjectName = $row['subject_code'];
                $subId = $row['subject_id'];
                $selected = ($subId == $selectedSubjectId) ? 'selected' : '';

                echo "<option value='$subId' $selected>$subjectName</option>";
              }
              ?>
            </select>
          </div>
        </div>


        <?php
        $query2 = "SELECT event_date, event_time FROM event_tbl WHERE event_id = ?";
        $stmt = mysqli_prepare($conn, $query2);
        mysqli_stmt_bind_param($stmt, "i", $id); // Assuming $id contains the identifier value
        mysqli_stmt_execute($stmt);
        mysqli_stmt_bind_result($stmt, $eventDate, $eventTime);
        mysqli_stmt_fetch($stmt);
        ?>
        <div class="row mb-3">
          <div class="col">
            <label class="form-label">Date</label>
            <input type="date" class="form-control" name="event_date" required value="<?php echo $eventDate ?>">
          </div>

          <div class="col">
            <label class="form-label">Time</label>
            <input type="time" class="form-control" name="event_time" required value="<?php echo $eventTime ?>">
          </div>
        </div>
        <?php
        mysqli_stmt_close($stmt);

        ?>

        <div class="float-end">
          <button type="submit" class="btn btn-success" name="submit">Save</button>
          <a href="index_admin.php" class="btn btn-danger">Cancel</a>
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