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
        $output .= '<table class="table table-dark table-hover text-center table-striped" style="max-width: 100%; width: 100%;" border="1">';
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
        $companyYear = $_POST["company_year"];

        // Establish database connection
        $db = pg_connect($connection_string);
        if (!$db) {
            echo json_encode(["success" => false, "error" => "Database connection error"]);
            exit;
        }

        if ($companyYear) {
            // Query to fetch company partners based on the company_year
            $query = "SELECT * FROM companies WHERE date = $1"; // Modify this query according to your schema

            $result = pg_query_params($db, $query, array($companyYear));
        } else {
            // Query to fetch all companies if $companyYear is not provided
            $query = "SELECT * FROM companies"; // Modify this query according to your schema

            $result = pg_query($db, $query);
        }

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
        $output .= '<table class="table table-dark table-hover text-center table-striped" style="max-width: 100%; width: 100%;" border="1">';
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
    } elseif ($_POST['action'] == 'fetch_students_for_company') {
        $companyName = isset($_POST["company_name"]) ? $_POST["company_name"] : '';

        // Establish database connection
        $db = pg_connect($connection_string);
        if (!$db) {
            echo json_encode(["success" => false, "error" => "Database connection error"]);
            exit;
        }

        // Construct the query to fetch deployed students under a specified company
        $query = "SELECT c.company_entry_id, c.company_name, o.id_no, CONCAT(o.first_name, ' ', o.middle_name, ' ', o.last_name) AS full_name, o.academic_year, o.start_date, o.end_date, o.rendered_hours 
                  FROM ojt_program o
                  INNER JOIN companies c ON o.company_entry_id = c.company_entry_id
                  WHERE c.company_name = $1
                  ORDER BY full_name ASC, start_date ASC, rendered_hours ASC";

        $result = pg_query_params($db, $query, array($companyName));

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
        $output .= '<table class="table table-dark table-hover text-center table-striped" style="max-width: 100%; width: 100%;" border="1">';
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
    }
}
