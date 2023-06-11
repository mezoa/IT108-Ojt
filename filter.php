<?php
// execute_custom_query.php

include "db_config.php";

session_start();

if (!isset($_SESSION['instructor_name'])) {
  header('location:index.php');
  exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $customQuery = $_POST["customQuery"];
  $result = mysqli_query($conn, $customQuery);

  // Generate the updated table HTML
  $tableHTML = '';
  while ($row = mysqli_fetch_assoc($result)) {
    $eventDate = date("F d, Y", strtotime($row["event_date"]));
    $eventTime = date("h:i A", strtotime($row["event_time"]));
    $tableHTML .= '<tr>
            <td>'.$row["event_id"].'</td>
            <td>'.$row["building_name"].'</td>
            <td>'.$row["room_number"].'</td>
            <td>'.$row["subject_code"].'</td>
            <td>'.$eventDate.'</td>
            <td>'.$eventTime.'</td>
            <td>
              <a class="edit-btn" href="edit_event.php?id='.$row["event_id"].'"><i class="fa-solid fa-pen-to-square hover-blue"></i></a>
              <a class="edit-btn" href="delete_event.php?id='.$row["event_id"].'"><i class="fa-solid fa-trash hover-red"></i></a>
            </td>
          </tr>';
  }

  // Return the updated table HTML
  echo $tableHTML;
} else {
  // If the request is not POST, redirect to the homepage or handle the error accordingly
  header('location:index_admin.php');
  exit;
}
?>
