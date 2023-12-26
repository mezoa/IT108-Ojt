<?php
include "db_config.php";
session_start();

// Check if the user is logged in and has a specific role
if (!isset($_SESSION['instructor_name']) && !isset($_SESSION['assistant_name']) && !isset($_SESSION['student_name'])) {
    // Redirect to the appropriate page or show an error message
    echo "Unauthorized access";
    exit();
}

// Determine the user's role
$userRole = '';
if (isset($_SESSION['instructor_name'])) {
    $userRole = 'instructor';
} elseif (isset($_SESSION['assistant_name'])) {
    $userRole = 'assistant';
} elseif (isset($_SESSION['student_name'])) {
    $userRole = 'student';
}

// Check the user's role and execute the delete operation based on privileges
if ($userRole === 'assistant' || $userRole === 'student') {
    // Assistant can't perform delete operations
    echo "You do not have permission to delete records.";
    exit();
}

if (isset($_POST['id'], $_POST['table'])) {
    $recordId = $_POST['id'];
    $table = $_POST['table'];

    switch ($table) {
        case "ojt_program":
            $primaryKey = "id_no";
            if ($userRole === 'instructor') {
                // Delete the referencing records in the 'requirements' table
                $deleteRequirementsQuery = "DELETE FROM requirements WHERE id_no = '$recordId'";
                pg_query($conn, $deleteRequirementsQuery);
            } else {
                echo "You do not have permission to delete records in this table.";
                exit();
            }
            break;
        case "companies":
            if ($userRole !== 'instructor') {
                echo "You do not have permission to delete records in this table.";
                exit();
            }
            $primaryKey = "company_entry_id";
            $updateRequirementsQuery = "UPDATE requirements SET company_entry_id = NULL WHERE company_entry_id = '$recordId'";
            pg_query($conn, $updateRequirementsQuery);
            break;
        case "requirements":
            if ($userRole !== 'instructor') {
                echo "You do not have permission to delete records in this table.";
                exit();
            }
            $primaryKey = "rq_id";
            break;
        default:
            echo "Invalid table";
            exit();
    }

    // Check if the record exists before deletion
    $checkRecordQuery = "SELECT COUNT(*) FROM $table WHERE $primaryKey = '$recordId'";
    $checkResult = pg_query($conn, $checkRecordQuery);
    $rowCount = pg_fetch_result($checkResult, 0, 0);

    if ($rowCount === '0') {
        echo "Record with ID $recordId not found in $table";
        exit();
    }
     // After handling related tables, delete from the main table
    $sql = "DELETE FROM $table WHERE $primaryKey = '$recordId'";
    $result = pg_query($conn, $sql);

    if ($result) {
        echo "success";
    } else {
        echo "Error deleting record: " . pg_last_error($conn);
    }
} else {
    echo "Incomplete data received for deletion";
}

?>
