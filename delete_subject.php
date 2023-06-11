<?php
include "db_config.php";
$id = $_GET["id"];
$sql = "DELETE FROM `subject_tbl` WHERE subject_id = $id";
$result = mysqli_query($conn, $sql);

if ($result) {
  header("Location: index_subject.php?msg=Data deleted successfully");
} else {
  echo "Failed: " . mysqli_error($conn);
}
