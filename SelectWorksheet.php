<?php
// for debugging
ini_set("display_errors", 1);
// Authenticate and pick up Calendar object
require_once("AuthorizeSpreadsheet.php");
$spreadsheetFeed = $spreadsheetService->getSpreadsheets();

// pick up posted Title
$spreadsheetTitle = $_POST['spreadsheetTitle'];
echo "<H3>$spreadsheetTitle</H3>";
// Pick up spreadheet and then get worksheets
//$s = $spreadsheetFeed->getByID($spreadhseetID);
$s = $spreadsheetFeed->getByTitle($spreadsheetTitle);
$worksheetFeed = $s->getWorksheets();

$count = 0;
foreach ($worksheetFeed as $w){
    $rows = $w->getListFeed();
    if ($count === 0)
        echo "<TABLE>
               <TR><TH ALIGN=LEFT>Title</TH>
                  <TH ALIGN=LEFT>Last Updated</TH>
                  <TH AlIGN=RIGHT>Row Count</TH>
                  <TH>Action</TD></TR>";
    
    $count++;
    echo "<TR><TD>" . $w->getTitle() . "</TD>
              <TD>" . date_format($w->getUpdated(), "m-d-Y") , "</TD>
              <TD ALIGN=RIGHT>" . count($rows) . "</TD>
              <TD><FORM METHOD=POST ACTION=DumpWorksheet.php>
                  <INPUT TYPE=HIDDEN NAME=worksheetTitle VALUE='" .
                      $w->getTitle() . "'>                      
                  <INPUT TYPE=HIDDEN NAME=spreadsheetTitle VALUE='" .
                      $s->getTitle() . "'>
                  <INPUT TYPE=SUBMIT VALUE=\"Select\">
                  </FORM></TR>";
}
if ($count == 0) echo "No worksheets";
else echo "</TABLE>";
?>
