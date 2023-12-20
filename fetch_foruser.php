<?php
include "db_config.php";

// Get the academic year from the query parameters
$academicYear = pg_escape_string($conn, $_GET['academic_year']);

// Fetch the data from the corresponding view
$sql = "SELECT * FROM " . pg_escape_string($conn, $academicYear);
$result = pg_query($conn, $sql);

// Generate the HTML for the table rows
while ($row = pg_fetch_assoc($result)) {
    $startDate = $row["start_date"] ? date("F d, Y", strtotime($row["start_date"])) : null;
    $endDate = $row["end_date"] ? date("F d, Y", strtotime($row["end_date"])) : null;

    echo '<tr>';
    echo '<td>' . $row["id_no"] . '</td>';
    echo '<td>' . $row["full_name"] . '</td>';
    echo '<td>' . $row["program"] . '</td>';
    echo '<td>' . $row["yr_lvl"] . '</td>';
    echo '<td>' . $row["academic_year"] . '</td>';
    echo '<td>' . $row["contact_no"] . '</td>';
    echo '<td>' . $row["email"] . '</td>';
    echo '<td>' . $row["status"] . '</td>';
    echo '<td>' . $startDate . '</td>';
    echo '<td>' . $endDate . '</td>';
    echo '<td>' . $row["rendered_hours"] . '</td>';
    echo '<td>' . $row["internship_plan"] . '</td>';
    echo '<td>' . $row["requirements"] . '</td>';
    echo '<td>' . $row["company_name"] . '</td>';
    echo '</tr>';
}
?>