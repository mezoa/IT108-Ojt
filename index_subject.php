<?php
include "db_config.php";

session_start();

if (!isset($_SESSION['instructor_name'])) {
  header('location:form.php');
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
  <title>Subjects</title>
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
    <a href="add_subject.php" class="ghost">Add Subject</a>
    <a href="index_admin.php" class="ghost">Return</a>
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

    <table class="table table-hover text-center">
      <thead class="table-active">
        <tr>
          <th class="sortable sort-id" scope="col">ID <i class="fas fa-sort sort-icon"></i></th>
          <th scope="col">Subject Code</th>
          <th scope="col">Subject Name</th>
          <th scope="col" style="width: 12%">Action</th>
        </tr>
      </thead>
      <tbody>
        <?php
        $sql = "SELECT * FROM subject_tbl";
        $result = mysqli_query($conn, $sql);
        while ($row = mysqli_fetch_assoc($result)) {
        ?>
          <tr>
            <td><?php echo $row["subject_id"] ?></td>
            <td><?php echo $row["subject_code"] ?></td>
            <td><?php echo $row["subject_name"] ?></td>
            <td>
              <a class="edit-btn" href="edit_subject.php?id=<?php echo $row["subject_id"] ?>"><i class="fa-solid fa-pen-to-square hover-blue"></i></a>
              <a class="edit-btn" href="delete_subject.php?id=<?php echo $row["subject_id"] ?>"><i class="fa-solid fa-trash hover-red"></i></a>
            </td>
          </tr>
        <?php
        }
        ?>
      </tbody>
    </table>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous"></script>
  <script src="js/particles.js"></script>
  <script src="js/app.js"></script>
  <script src="js/sort.js"></script>
  <script src="js/bg.js"></script>
</body>

</html>