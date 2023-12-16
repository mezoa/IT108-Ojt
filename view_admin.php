<?php
include "db_config.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if ($_POST['action'] == 'fetch_student_records') {
        $academicYear = $_POST["academic_year"];

        // Establish database connection
        $db = pg_connect($connection_string);
        if (!$db) {
            echo json_encode(["success" => false, "error" => "Database connection error"]);
            exit;
        }

        // Query the database for View 1 (Student Records)
        $query = "SELECT * FROM view_acadyear_" . str_replace('-', '_', $academicYear) . ";";
        $result = pg_query($db, $query);

        if (!$result) {
            echo json_encode(["success" => false, "error" => "Query failed"]);
            exit;
        }
        
        // Fetch column names
        $numFields = pg_num_fields($result);
        $columns = array();
        for ($i = 0; $i < $numFields; $i++) {
            $colName = pg_field_name($result, $i);
            $columns[] = $colName;
        }
        
        // Start building the HTML table within a responsive container
        $output = '<div class="table-container">';
        $output .= '<table class="responsive-table" style="max-width: 100%; width: 100%;" border="1">';
        $output .= '<tr style="background-color: #f2f2f2;">';
        foreach ($columns as $column) {
            $output .= '<th style="padding: 12px; text-align: center;">' . $column . '</th>';
        }
        $output .= '</tr>';
        
        // Fetch data and construct table rows
        $alternate = false;
        while ($row = pg_fetch_assoc($result)) {
            $output .= '<tr' . ($alternate ? ' style="background-color: #f9f9f9;"' : '') . '>';
            foreach ($columns as $column) {
                $value = $row[$column];
                // Handle null values
                if ($value === null) {
                    $value = 'N/A'; // Display 'N/A' for null values
                }
                $output .= '<td style="padding: 12px; text-align: center;">' . $value . '</td>';
            }
            $output .= '</tr>';
            $alternate = !$alternate; // Alternate row colors
        }
        $output .= '</table>';
        $output .= '</div>'; // Close the table container
        
        echo json_encode(["success" => true, "data" => $output]);
    } elseif ($_POST['action'] == 'fetch_company_partners') {
        $company = isset($_POST["company"]) ? $_POST["company"] : null;
        $companyYear = $_POST["company_year"];

        // Establish database connection
        $db = pg_connect($connection_string);
        if (!$db) {
            echo json_encode(["success" => false, "error" => "Database connection error"]);
            exit;
        }

        // Query based on different scenarios (A, B, C, D)

        if ($companyYear === "N/A") {
            if ($company) {
                // Query for a specific company if the N/A option is selected and a company name is provided
                $query = "SELECT c.company_entry_id, c.company_name, c.date, o.id_no, 
                        (o.first_name || ' ' || o.middle_name || ' ' || o.last_name) AS full_name,
                        o.program, o.year
                        FROM companies c
                        INNER JOIN ojt_program o ON c.company_entry_id = o.company_entry_id
                        WHERE c.company_name ILIKE $1";
                $result = pg_query_params($db, $query, array('%' . $company . '%'));
            } else {
                // Handle scenario where no company name is entered when N/A is selected
                echo json_encode(["success" => true, "data" => "<div>No company name entered.</div>"]);
                exit;
            }
        } else {
            // For other cases (when a specific academic year is selected)
            if ($company && !$companyYear) {
                // A. If there is text input without the date
                $query = "SELECT c.company_entry_id, c.company_name, c.date, o.id_no, 
                        (o.first_name || ' ' || o.middle_name || ' ' || o.last_name) AS full_name,
                        o.program, o.year
                        FROM companies c
                        INNER JOIN ojt_program o ON c.company_entry_id = o.company_entry_id
                        WHERE c.company_name ILIKE $1";
                $result = pg_query_params($db, $query, array('%' . $company . '%'));
            } elseif ($company && $companyYear) {
                // B. If there is input and selected academic year or date
                $query = "SELECT c.company_entry_id, c.company_name, c.date, o.id_no, 
                        (o.first_name || ' ' || o.middle_name || ' ' || o.last_name) AS full_name,
                        o.program, o.year
                        FROM companies c
                        INNER JOIN ojt_program o ON c.company_entry_id = o.company_entry_id
                        WHERE c.company_name ILIKE $1 AND c.date = $2";
                $result = pg_query_params($db, $query, array('%' . $company . '%', $companyYear));
            } elseif (!$company && $companyYear) {
                // C. If there is no input in the text field, but there is a selected academic year
                $query = "SELECT * FROM companies WHERE date = $1";
                $result = pg_query_params($db, $query, array($companyYear));
            } else {
                // D. If there is no text input and no selected academic year or date
                $query = "SELECT * FROM companies";
                $result = pg_query($db, $query);
            }
}

        if (!$result) {
            echo json_encode(["success" => false, "error" => "Query failed"]);
            exit;
        }

        // Check if any data is found
        $rowCount = pg_num_rows($result);
        if ($rowCount === 0) {
            echo json_encode(["success" => true, "data" => "<div>No data found for the specified criteria.</div>"]);
            exit;
        }

        // Fetch column names
        $numFields = pg_num_fields($result);
        $columns = array();
        for ($i = 0; $i < $numFields; $i++) {
            $colName = pg_field_name($result, $i);
            $columns[] = $colName;
        }

        // Start building the HTML table within a responsive container
        $output = '<div class="table-container">';
        $output .= '<table class="responsive-table" style="max-width: 100%; width: 100%;" border="1">';
        $output .= '<tr style="background-color: #f2f2f2;">';
        foreach ($columns as $column) {
            $output .= '<th style="padding: 12px; text-align: center;">' . $column . '</th>';
        }
        $output .= '</tr>';

        // Fetch data and construct table rows
        $alternate = false;
        while ($row = pg_fetch_assoc($result)) {
            $output .= '<tr' . ($alternate ? ' style="background-color: #f9f9f9;"' : '') . '>';
            foreach ($columns as $column) {
                $value = $row[$column];
                // Handle null values
                if ($value === null) {
                    $value = 'N/A'; // Display 'N/A' for null values
                }
                $output .= '<td style="padding: 12px; text-align: center;">' . $value . '</td>';
            }
            $output .= '</tr>';
            $alternate = !$alternate; // Alternate row colors
        }
        $output .= '</table>';
        $output .= '</div>'; // Close the table container

        echo json_encode(["success" => true, "data" => $output]);
    } else {
        echo json_encode(["success" => false, "error" => "Invalid action"]);
    }
} else {
    echo json_encode(["success" => false, "error" => "Invalid request"]);
}
?>
