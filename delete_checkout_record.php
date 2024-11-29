<?php
$filename = 'checkout_records.txt';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Check if "delete_all" is set
    if (isset($_POST['delete_all'])) {
        // Clear the file content
        file_put_contents($filename, '');
    } elseif (isset($_POST['delete_record'])) {
        // Delete specific record
        $record_number = (int)$_POST['delete_record'];

        if (file_exists($filename)) {
            $lines = file($filename, FILE_IGNORE_NEW_LINES);
            unset($lines[$record_number]); // Remove the selected record
            file_put_contents($filename, implode("\n", $lines)); // Rewrite the file without the deleted record
        }
    }
}

header('Location: checkout_records.php'); // Redirect back to the checkout records page
exit();
?>
