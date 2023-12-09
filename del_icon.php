<?php
include "db_config.php";

if (isset($_POST['id'], $_POST['table'])) {
    $recordId = $_POST['id'];
    $table = $_POST['table'];

    switch ($table) {
        case "ojt_program":
            $primaryKey = "id_no";
            // Delete referencing records first
            $deleteRequirementsQuery = "DELETE FROM requirements WHERE id_no = '$recordId'";
            pg_query($conn, $deleteRequirementsQuery);
            break;
        case "companies":
            $primaryKey = "company_entry_id";
            $updateRequirementsQuery = "UPDATE requirements SET company_entry_id = NULL WHERE company_entry_id = '$recordId'";
            $updateRequirementsQuery = "UPDATE requirements SET company_entry_id = NULL WHERE company_entry_id = '$recordId'";
            pg_query($conn, $updateRequirementsQuery);
            break;
        case "requirements":
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
