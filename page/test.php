<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>
    <?php
    include "../PHPMailer/class.phpmailer.php";
    $mail = new PHPMailer(true);
    $mail->IsSMTP();
    if (isset($_POST['sub'])) {


        try {
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->SMTPSecure = "ssl";
            $mail->Port = 465;
            $mail->Username = "amiradib483@gmail.com";
            $mail->Password = "31013101";
            $mail->AddAddress("amiradib483@gmail.com");
            $mail->SetFrom("amiradib483@gmail.com", "AmirMohammadAdib");
            $mail->Subject = "فعالسازی حساب کاربری";
            $mail->CharSet = "UTF-8";
            $mail->ContentType = "text/htm";
            $mail->MsgHTML("<p>success</p>");
            $mail->Send();
        } catch (phpmailerException $e) {
            echo $e->errorMessage();
        }
    }
    ?>
    <form action="" method="POST">
        <input type="email" name="email" placeholder="email" require><br><br>
        <input type="text" name="subject" placeholder="titre email"><br><br>
        <textarea name="content" placeholder="text content email"></textarea><br><br>
        <input type="submit" value="کلیک" name="sub">
    </form>
</body>

</html>