<?php
  session_start();
  require_once("libphp-phpmailer/class.phpmailer.php");
  $Mail = $_SESSION['Mail'];
  echo count($Mail) . " is number of email messages<br>"; 
  $mail = new PHPMailer();
  $mail->SetFrom($_POST['From']);
  $mail->Subject = $_POST['Subject'];
  foreach($Mail as $Key => $GradeCard){
    $message = $_POST['Message'];
    $body = "<p>$message<hr>" . eregi_replace("[\]",'',$GradeCard);
    $mail->MsgHTML($body);
    $mail->ClearAllRecipients();
    if (isset($_POST['CC']) && strlen($_POST['CC']) > 0) { 
        $mail->ClearCCs(); // to be sure
        $mail->AddCC($_POST['CC']);
    }
    $mail->AddAddress($Key); 
    if (!$mail->Send()){
        echo "Mailer Error: " . $mail->ErrorInfo; 
    } else { 
        echo "<p>Message sent to $Key!";
        $count++;
    }
  }
  echo "<p>$count is the number sent";
?>
