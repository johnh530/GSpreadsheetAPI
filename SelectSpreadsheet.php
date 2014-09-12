<?php
// for debugging
ini_set("display_errors", 1);
// Authenticate and pick up Calendar object
require_once("AuthorizeSpreadsheet.php");
$spreadsheetFeed = $spreadsheetService->getSpreadsheets();
$count = 0;
echo "<H3>Recient spreadsheets</H3>";
foreach ($spreadsheetFeed as $s){
    $w = $s->getWorksheets();
    if ($count === 0)
        echo "<TABLE boarder=1>
               <TR><TH ALIGN=LEFT>Title</TH>
                  <TH ALIGN=LEFT>Last Updated</TH>
                  <TH AlIGN=RIGHT>WS Count</TH>
                  <TH>Action</TD></TR>";
    
    $count++;
    echo "<TR><TD>" . $s->getTitle() . "</TD>
              <TD>" . date_format($s->getUpdated(), "m-d-Y") , "</TD>
              <TD ALIGN=RIGHT>" . $w->count() . "</TD>
              <TD><FORM METHOD=POST ACTION=SelectWorksheet.php>
                  <INPUT TYPE=HIDDEN NAME=spreadsheetID VALUE='" .
                      $s->getID() . "'>                      
                  <INPUT TYPE=HIDDEN NAME=spreadsheetTitle VALUE='" .
                      $s->getTitle() . "'>
                  <INPUT TYPE=SUBMIT VALUE=\"Select\">
                  </FORM></TR>";
     if ($count >= 5) break;
}
if ($count == 0) echo "No Spreadsheets";
else echo "</TABLE>";
?>
