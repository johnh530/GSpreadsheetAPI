<?php
// for debugging
ini_set("display_errors", 1);
// Authenticate and pick up Calendar object
require_once("AuthorizeSpreadsheet.php");
$spreadsheetFeed = $spreadsheetService->getSpreadsheets();

// pick up posted variables
$spreadsheetTitle = $_POST['spreadsheetTitle'];
$worksheetTitle = $_POST['worksheetTitle'];
echo "<H3>$spreadsheetTitle - $worksheetTitle</H3>";
// Pick up spreadheet and then get worksheets
$s = $spreadsheetFeed->getByTitle($spreadsheetTitle);
$worksheetFeed = $s->getWorksheets();
$w  = $worksheetFeed->getByTitle($worksheetTitle);
$listFeed = $w->getListFeed();
foreach($listFeed->getEntries() as $entry) {
    echo "<HR>";
    var_dump($entry->getValues());
}
echo "<HR>";
?>
