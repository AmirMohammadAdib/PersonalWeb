<?php
function mailactive(){
 
$mail=new PHPMailer(true);
$mail->IsSMTP();
    
try
{
	$mail->Host='smtp.gmail.com';
	$mail->SMTPAuth=true;
	$mail->SMTPSecure="ssl";
	$mail->Port=465;
	$mail->Username="moeinbagh09@gmail.com";
	$mail->Password="135489242531841614";
	$mail->AddAddress("user email");
	$mail->SetFrom("moeinbagh09@gmail.com");
	$mail->Subject= "فعالسازی حساب کاربری";
	$mail->CharSet="UTF-8";
	$mail->ContentType="text/htm";
    $mail->MsgHTML("<p>success</p>");
    $mail->Send();
}
catch(phpmailerException $e)
{
	echo $e->errorMessage();
}
}
?>