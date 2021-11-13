<?php

$total = $_POST['amout'];
$user = $_POST['user'];
$course_id = $_POST['course_id'];
$course_name = $_POST['course_name'];


if (isset($_POST['pay'])) {

    $data = [
        'pin' => 'aqayepardakht',
        'amount' => $total,
        'callback' => 'http://localhost:8080/storeWeb/pay/verify.php?amount=' . $total . '&user=' . $user . '&course=' . $course_id . '&name_course=' . $course_name,

    ];

    $data = json_encode($data);
    $ch = curl_init('https://panel.aqayepardakht.ir/api/create');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLINFO_HEADER_OUT, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);

    curl_setopt(
        $ch,
        CURLOPT_HTTPHEADER,
        array(
            'Content-Type: application/json',
            'Content-Length: ' . strlen($data)
        )
    );
    $result = curl_exec($ch);
    curl_close($ch);
    if ($result && !is_numeric($result)) {
        header('Location: https://panel.aqayepardakht.ir/startpay/' . $result);
    } else {
        echo "خطا";
    }
}
