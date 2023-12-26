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

if (isset($_POST['id'], $_POST['table'], $_POST['newData'])) {
    $recordId = $_POST['id'];
    $table = $_POST['table'];
    $newData = json_decode($_POST['newData'], true);
    
    switch ($table) {
        case "ojt_program":
            $primaryKey = "id_no";
        
            // Handle empty company_entry_id
            $companyEntryId = isset($newData['company_entry_id']) ? "'" . $newData['company_entry_id'] . "'" : 'NULL';
        
            // Handle empty or invalid dates
            $startDate = !empty($newData['start_date']) ? "'" . $newData['start_date'] . "'" : 'NULL';
            $endDate = !empty($newData['end_date']) ? "'" . $newData['end_date'] . "'" : 'NULL';
        
            // Update ojt_program attributes
            $updateOjtProgramQuery = "UPDATE ojt_program SET 
                last_name = '" . ($newData['last_name'] ?? '') . "', 
                first_name = '" . ($newData['first_name'] ?? '') . "', 
                middle_name = '" . ($newData['middle_name'] ?? '') . "', 
                program = '" . ($newData['program'] ?? '') . "', 
                yr_lvl = " . ($newData['yr_lvl'] ?? 'NULL') . ", 
                email = '" . ($newData['email'] ?? '') . "', 
                contact_no = " . ($newData['contact_no'] ?? 'NULL') . ", 
                company_entry_id = " . $companyEntryId . ", 
                academic_year = '" . ($newData['academic_year'] ?? '') . "', 
                status = '" . ($newData['status'] ?? '') . "', 
                start_date = " . $startDate . ", 
                end_date = " . $endDate . ", 
                rendered_hours = " . ($newData['rendered_hours'] ?? 'NULL') . ", 
                internship_plan = '" . ($newData['internship_plan'] ?? '') . "', 
                requirements = '" . ($newData['requirements'] ?? '') . "', 
                notes = '" . ($newData['notes'] ?? '') . "'
                WHERE id_no = '$recordId'";
            
            // Try executing the query
            if (!pg_query($conn, $updateOjtProgramQuery)) {
                $error = pg_last_error($conn);

                // Check if the error message indicates a foreign key constraint violation
                if (strpos($error, 'violates foreign key constraint') !== false) {
                    echo "Error Updating: Foreign Key is not present in the reference table";
                    exit();
                }

                // If it's not a foreign key constraint violation, echo the original error
                echo "Error Updating: " . $error;
                exit();
            }
            break;
        case "companies":
            $primaryKey = "company_entry_id";

            // Check if the record exists in companies table
            $checkCompanyQuery = "SELECT COUNT(*) FROM companies WHERE company_entry_id = '$recordId'";
            $checkResult = pg_query($conn, $checkCompanyQuery);
            $rowCount = pg_fetch_result($checkResult, 0, 0);

            if ($rowCount === '0') {
                echo "Record with company_entry_id $recordId not found in companies table";
                exit();
            }

            // Update companies attributes
            $updateCompaniesQuery = "UPDATE companies SET 
                company_name = '" . ($newData['company_name'] ?? '') . "', 
                moa = '" . ($newData['moa'] ?? '') . "', 
                date = '" . ($newData['date'] ?? '') . "', 
                notes = '" . ($newData['notes'] ?? '') . "' 
                WHERE company_entry_id = '$recordId'";
            
            pg_query($conn, $updateCompaniesQuery);

             // Check for foreign key constraint violation error
             if (!$result) {
                $error = pg_last_error($conn);
                if (strpos($error, 'violates foreign key constraint') !== false) {
                    echo "Error Updating: Foreign Key is not present in the reference table";
                    exit();
                }
            }

            break;

        case "requirements":
            $primaryKey = "rq_id";
      
            // Handle boolean values
            $pc = isset($newData['pc']) ? ($newData['pc'] === 't' ? 'true' : 'false') : 'false';
            $sp = isset($newData['sp']) ? ($newData['sp'] === 't' ? 'true' : 'false') : 'false';
            $cogh = isset($newData['cogh']) ? ($newData['cogh'] === 't' ? 'true' : 'false') : 'false';
            $hi = isset($newData['hi']) ? ($newData['hi'] === 't' ? 'true' : 'false') : 'false';
            $tff = isset($newData['tff']) ? ($newData['tff'] === 't' ? 'true' : 'false') : 'false';
            $sff = isset($newData['sff']) ? ($newData['sff'] === 't' ? 'true' : 'false') : 'false';
            $coc = isset($newData['coc']) ? ($newData['coc'] === 't' ? 'true' : 'false') : 'false';
            $tr = isset($newData['tr']) ? ($newData['tr'] === 't' ? 'true' : 'false') : 'false';
            $htee = isset($newData['htee']) ? ($newData['htee'] === 't' ? 'true' : 'false') : 'false';
      
            // Update requirements attributes
            $updateRequirementsQuery = "UPDATE requirements SET 
               pc = " . $pc . ", 
               sp = " . $sp . ", 
               cogh = " . $cogh . ", 
               hi = " . $hi . ", 
               tff = " . $tff . ", 
               sff = " . $sff . ", 
               coc = " . $coc . ", 
               tr = " . $tr . ", 
               htee = " . $htee . " 
               WHERE rq_id = '$recordId'";
            
            pg_query($conn, $updateRequirementsQuery);
            break;
   
        default:
            echo "Invalid table";
            exit();
    }

     // Check if the record exists before editing
     $checkRecordQuery = "SELECT COUNT(*) FROM $table WHERE $primaryKey = '$recordId'";
     $checkResult = pg_query($conn, $checkRecordQuery);
     $rowCount = pg_fetch_result($checkResult, 0, 0);
 
     if ($rowCount === '0') {
         echo "Record with ID $recordId not found in $table";
         exit();
     }

    echo "success";
} else {
    echo "Incomplete data received for editing";
    // Log or echo received data for debugging
    error_log("Incomplete data received for editing. Data: " . print_r($_POST, true));
}


?>
