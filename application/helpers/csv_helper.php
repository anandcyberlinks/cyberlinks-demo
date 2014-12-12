<?php
function query_to_csv($result,$heading, $filename, $attachment = false, $headers = true) {
 ob_get_flush();

$output = fopen("php://output",'w') or die("Can't open php://output");
header("Content-Type:application/csv"); 
header("Content-Disposition:attachment;filename=export.csv"); 
fputcsv($output, $heading);
foreach($result as $row) {
    fputcsv($output, $row);
}
fclose($output) or die("Can't close php://output");

    }
?>