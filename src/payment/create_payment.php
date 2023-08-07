<?php


require_once __DIR__ . '/../bot/functions.php';

if (isset($_GET['id'], $_GET['amount'])) {


    $order_id = getRandomString(15);

    $params = [
        'order_id' => $order_id,
        'amount' => $_GET['amount'] . 0,
        'callback' => PAYMENT_VERIFY_URL,
    ];


    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, 'https://api.idpay.ir/v1.1/payment');
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($params));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
        'X-API-KEY: ' . IDPAY_TOKEN,
        'Content-Type: application/json',

    ));

    $response = \json_decode(curl_exec($ch), true);
    curl_close($ch);


    date_default_timezone_set('Asia/Tehran');

    $time = date('Y-m-d/H:i');

    $db->insert('user_payment', [
        'user_id' => $_GET['id'],
        'order_id' => $order_id,
        'amount' =>  $_GET['amount'] . 0,
        'trans_id' => $response['id'],
        'purchase_time' => $time,
        'finished' => false

    ]);

    header("location: {$response['link']}");
    die;
} else {
    header($_SERVER['SERVER_PROTOCOL'] . ' 404 Not Found');
    include_once __DIR__ . "/../../404.shtml";
    die;
}
