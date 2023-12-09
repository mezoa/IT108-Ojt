<?php
include "db_config.php";
session_start();

// Check for session or authentication
if (!isset($_SESSION['instructor_name'])) {
    header('location:index.php');
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST["customQuery"])) {
        $customQuery = $_POST["customQuery"];

        // Execute the modified query with sorting
        $result = pg_query($conn, $customQuery);

        if (!$result) {
            // Handle query execution error
            $errorMessage = pg_last_error($conn);
            echo "Error executing query: " . $errorMessage;
            exit;
        }

        // Generate table HTML
        $tableHTML = generateTableHTML($result);

        // Return the table HTML
        echo $tableHTML;
    } else {
        // No custom query received
        echo "No custom query received";
    }
}

function generateTableHTML($result)
{
    $tableHTML = '<div class="table-responsive filter-table">'; // Add a unique class for tables within the filter modal
    $tableHTML .= '<table class="table table-hover text-center">';
    $tableHTML .= '<thead><tr>';

    // Display column headers
    $numFields = pg_num_fields($result);
    for ($i = 0; $i < $numFields; $i++) {
        $fieldName = pg_field_name($result, $i);
        $tableHTML .= "<th>$fieldName</th>";
    }

    $tableHTML .= '</tr></thead><tbody>';

    // Fetch and display query results
    while ($row = pg_fetch_assoc($result)) {
        $tableHTML .= '<tr>';
        for ($i = 0; $i < $numFields; $i++) {
            $fieldName = pg_field_name($result, $i);
            $value = $row[$fieldName];

            // If the column is a date field, format it accordingly
            if (strpos($fieldName, 'date') !== false && strtotime($value)) {
                $value = date("F d, Y", strtotime($value));
            }

            $tableHTML .= "<td>$value</td>";
        }
        $tableHTML .= '</tr>';
    }

    $tableHTML .= '</tbody></table>';
    $tableHTML .= '</div>';

    return $tableHTML;
}
?>
