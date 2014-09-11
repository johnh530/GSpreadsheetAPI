<?php
// for debugging
ini_set("display_errors", 1);
// Authenticate and pick up Calendar object
require_once("AuthorizeSpreadsheet.php");
$spreadsheetFeed = $spreadsheetService->getSpreadsheets();
$count = 0;
for ($iterator = $spreadsheetFeed; $iterator->valid(); $iterator->next()){
    $count++;
    $s = $iterator->current();
    $w = $s->getWorksheets();
    echo $s->getID() . " " . $s->getTitle() . " " .  date_format($s->getUpdated(), "m-d-Y") . 
        " " . $w->count() . "<BR>";
}
?>
