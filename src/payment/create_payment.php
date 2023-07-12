<?php


require __DIR__ . '/../bot/functions.php';

if (isset($_GET['id'], $_GET['amount'])) {





    $order_id = getRandomString(15);
    $amount = $_GET['amount'];

    $curl = curl_init();

    curl_setopt_array($curl, array(
        CURLOPT_URL => 'https://nextpay.org/nx/gateway/token',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS => "api_key=" . NEXTPAY_TOKEN . "&amount={$amount}&order_id={$order_id}&callback_uri=" . PAYMENT_VERIFY_URL,
    ));


    $response = curl_exec($curl);

    curl_close($curl);

    $response = json_decode($response);

    date_default_timezone_set('Asia/Tehran');

    $time = date('Y-m-d/H:i');
    // medoo php for using database

    $db->insert('user_payment', [
        'user_id' => $_GET['id'],
        'order_id' => $order_id,
        'amount' => $amount,
        'trans_id' => $response->trans_id,
        'purchase_time' => $time,
        'finished' => false

    ]);


    header('location: https://nextpay.org/nx/gateway/payment/' . $response->trans_id);
    die();
} else {
    header($_SERVER['SERVER_PROTOCOL'] . ' 404 Not Found');
    include '404.html';
    die();
}
