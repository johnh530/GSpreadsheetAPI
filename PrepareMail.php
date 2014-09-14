<?php
// clean up numbers to 1 place past decimal
function pretty($var){
    if (is_numeric($var)){
        $num = $var + 0;
        if (is_int($num)) return $var;
        return number_format($var,1);
    }
    return $var;
}

// for debugging
ini_set("display_errors", 1);
// Authenticate and pick up Calendar object
require_once("AuthorizeSpreadsheet.php");
$spreadsheetFeed = $spreadsheetService->getSpreadsheets();

// pick up posted variables
$spreadsheetTitle = $_POST['spreadsheetTitle'];
$worksheetTitle = $_POST['worksheetTitle'];

echo "<H3>$spreadsheetTitle - $worksheetTitle</H3>";
?>
<!-- Form for sending email - passes Subject, CC, Message
     In session variable is $Mail which is an associative array with key
     of email address and value of table of students grades
-->
<HR>
<FORM method=POST action=SendMail.php>
  <TABLE>
    <TR><TH align=left>Subject</TH>
        <TD><INPUT type=text name=Subject 
            value="Grades from <?php echo $spreadsheetTitle;?>"></TD></TR>
    <TR><TH align=left>From</TH>
        <TD><input type=text name=From value="no-reply@wheaton.edu"> </TD></TR>
    <TR><TH align=left>CC</TH><TD><INPUT type=text name=CC><TD></TR>
    <TR><TH>Message</TH>
    <TD><TEXTAREA name=Message rows=4 cols=60> Here are your grades
          </TEXTAREA></TD></TR>
    <TR><TD colspan=2 align=left><INPUT type=SUBMIT value="Send E-mail"></TD>
    </TR>
  </TABLE>
</FORM>
<H4>Check informatoin below before submitting</H4>
<?php

// Pick up spreadheet and then get worksheets
$s = $spreadsheetFeed->getByTitle($spreadsheetTitle);
$worksheetFeed = $s->getWorksheets();
$w  = $worksheetFeed->getByTitle($worksheetTitle);

// two pass - first pass obtain non student info - second pass student data
$listFeed = $w->getListFeed();

// before and after are values which go to all students
// before is before student rows, after is after student rows
$before = array();
$after = array();
$beforeStudent = true;
foreach($listFeed->getEntries() as $entry) {
    $Values = $entry->getValues();
    if ($Values['email'] !== "") {
        $beforeStudent = false;
        continue;
    }
    if ($beforeStudent) $before[] = $Values;
    else $after[] = $Values;
}

//Mail is array indexed by email and value is table of grades
$Mail = array();
// second pass - get data to mail and report
foreach($listFeed->getEntries() as $entry) {
    $Values = $entry->getValues();
    if ($Values['email'] === "") continue;
    $First =true;
    // now iterate over columns for this row
    foreach($Values as $Key => $Value){
        if ($First){
             $First = false;
             $Str = "<TABLE cellpadding=2>";
             $Str .= "<TR><TH align-left>$Key</TH>";
	     foreach($before as $header)
	          $Str .= "<TH align=right>" . pretty($header[$Key]) . "</TH>";
	     $Str .= "<TH align=right>$Value</TH>";
	     foreach($after as $footer)
	          $Str .= "<TH align=right>" . pretty($footer[$Key]) . "</TH>";
             $Str .= "</TR>";
	 } else { // same as first except no TABLE and TH replaced by TR
             $Str .= "<TR><TD align-left>$Key</TD>";
	     foreach($before as $header)
	          $Str .= "<TD align=right>" . pretty($header[$Key]) . "</TD>";
	     $Str .= "<TD align=right>" . pretty($Value) . "</TD>";
	     foreach($after as $footer)
	          $Str .= "<TD align=right>" . pretty($footer[$Key]) . "</TD>";
             $Str .= "</TR>";
         }
    }
    $Str .= "</TABLE>";
    echo "<HR>$Str<HR>"; // show on web page
    $Mail[$Values['email']] = $Str; // save for session variable
}
$_SESSION['Mail'] = $Mail;
?>
