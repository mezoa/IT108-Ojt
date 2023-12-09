<?php
require 'vendor/autoload.php'; // Include the Composer autoloader
use PhpOffice\PhpSpreadsheet\IOFactory;

include "db_config.php";
$conn = pg_connect($connection_string); // Establish a PostgreSQL database connection

if (isset($_POST['submit'])) {
  $file = $_FILES['file']['tmp_name'];
  $objPHPExcel = IOFactory::load($file); // Load the spreadsheet using PhpSpreadsheet
  $sheet = $objPHPExcel->getActiveSheet(); // Get the active sheet

  $importType = $_POST['importType'];

  switch ($importType) {
    case 'ojt_program':
      // Import data into the 'ojt_program' table
      foreach ($sheet->getRowIterator(2) as $row) {
        // Fetch data from Excel cells
        $id_no = $sheet->getCell('A'.$row->getRowIndex())->getValue();
        $last_name = $sheet->getCell('B'.$row->getRowIndex())->getValue();
        $first_name = $sheet->getCell('C'.$row->getRowIndex())->getValue();

        // Handle contact_no as a string or NULL if empty
        $contact_no = $sheet->getCell('G'.$row->getRowIndex())->getValue();
        $contact_no = ($contact_no !== null && $contact_no !== '') ? pg_escape_string($contact_no) : 'NULL';

        // Fetch other attributes accordingly based on your Excel columns and handle empty values
        $middle_name = $sheet->getCell('D'.$row->getRowIndex())->getValue() ?: '';
        $program = $sheet->getCell('E'.$row->getRowIndex())->getValue() ?: '';
        $yr_lvl = $sheet->getCell('F'.$row->getRowIndex())->getValue() ?: '';
        $email = $sheet->getCell('H'.$row->getRowIndex())->getValue() ?: '';
        $academic_year = $sheet->getCell('I'.$row->getRowIndex())->getValue() ?: '';

        // Insert data into the ojt_program table after sanitizing variables
        $sql = "INSERT INTO ojt_program (id_no, last_name, first_name, middle_name, program, yr_lvl, contact_no, email, academic_year) 
                VALUES ('$id_no', '$last_name', '$first_name', '$middle_name', '$program', '$yr_lvl', $contact_no, '$email', '$academic_year')";
        $result = pg_query($conn, $sql);

        // Check for errors or handle successful insertion
        if (!$result) {
          echo "Error importing data into ojt_program: " . pg_last_error($conn);
          break;
        }
      }
      break;

    case 'companies':
      // Import data into the 'companies' table
      foreach ($sheet->getRowIterator(2) as $row) {
        // Fetch data from Excel cells for companies table
        $company_name = $sheet->getCell('A'.$row->getRowIndex())->getValue();
        $moa = $sheet->getCell('B'.$row->getRowIndex())->getValue();
        // Fetch other attributes accordingly based on your Excel columns for the companies table
        
        // Insert data into the companies table
        $sql = "INSERT INTO companies (company_name, moa) VALUES ('$company_name', '$moa')";
        $result = pg_query($conn, $sql);

        // Check for errors or handle successful insertion
        if (!$result) {
          echo "Error importing data into companies: " . pg_last_error($conn);
          break;
        }
      }
      break;

    default:
      echo "Invalid import type selected!";
      break;
  }

  echo "Data imported successfully!";
  header("Location: index_view.php");
  exit(); // Ensure no further code execution after the redirection
}

pg_close($conn); // Close the database connection
?>
