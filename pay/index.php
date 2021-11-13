<?php

if(isset($_POST['sub'])){
    $amount = $_POST['amount'];
    $data = [
        'pin' => 'aqayepardakht',
      'amount' => $amount,
      'callback' => 'http://localhost:8080/storeWeb/pay/verify.php?amount=' . $amount,
    
    ];
    
    $data = json_encode($data);
    $ch = curl_init('https://panel.aqayepardakht.ir/api/create');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLINFO_HEADER_OUT, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
    'Content-Type: application/json',
    'Content-Length: ' . strlen($data))
    );
    $result = curl_exec($ch);
    curl_close($ch);
    if ($result && !is_numeric($result)) {
        header('Location: https://panel.aqayepardakht.ir/startpay/' . $result);
    } else {
        echo "خطا";
    }
}

?>

<form method="POST">
    <input type="number" name="amount">
    <input type="submit" name="sub">

</form>