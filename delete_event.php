<?php
include "db_config.php";
$id = $_GET["id"];
$sql = "DELETE FROM `event_tbl` WHERE event_id = $id";
$result = pg_query($conn, $sql);

if ($result) {
  header("Location: index_admin.php?msg=Data deleted successfully");
} else {
  echo "Failed: " . pg_error($conn);
}
