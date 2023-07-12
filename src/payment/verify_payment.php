<?php



require __DIR__ . '/../bot/functions.php';

if (isset($_GET['trans_id'], $_GET['order_id'], $_GET['amount'])) {


    $trans_id = $_GET['trans_id'];
    $order_id = $_GET['order_id'];
    $amount = $_GET['amount'];

    $curl = curl_init();

    curl_setopt_array($curl, array(


        CURLOPT_URL => 'https://nextpay.org/nx/gateway/verify',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS => "api_key=" . NEXTPAY_TOKEN . "&trans_id=$trans_id&amount=$amount",
    ));

    $response = json_decode(curl_exec($curl));

    curl_close($curl);

    if ($response->code == 0) {
        // using medoo php for database

        $user_data = $db->select('user_payment', ['user_id'], [
            'trans_id' => $trans_id,
            'order_id' => $order_id
        ]);

        $db->update('user_payment', [
            'finished' => true
        ], [
            'amount' => $amount,
            'order_id' => $order_id,
            'trans_id' => $trans_id
        ]);

        $db->update('user', [
            'balance[+]' => $amount
        ], [
            'id' => $user_data[0]['user_id']
        ]);


        $params = [
            'text' => 'تبریک به مقدار ' . $amount . "تومان" . "\n" . "به حساب شما افزوده شد برای دیدن مقدار شارژ کیف پول \n /start",
            'chat_id' => $user_data[0]['user_id']
        ];

        bot('sendMessage', $params);




        echo '<div style=" width: auto; height: 5vw; background-color:
        green; position: relative; margin-right: auto; margin-left: auto; "></div>
    <div style="position: relative; font-size: 2vw; font-family: monospace;
        "> <h1
            style="text-align:
            center;">پرداخت
            با موفقیت
            انجام شد میتوانید این
            صفحه را ببندید و به ربات
            برگردید</h1></div>

';
        die();
    } else {
        echo '<div style=" width: auto; height: 5vw; background-color:
        red; position: relative; margin-right: auto; margin-left: auto; "></div>
    <div style="position: relative; font-size: 2vw; font-family: monospace;
        "> <h1
            style="text-align:
            center;">خطایی در پرداخت رخ داده است </div>';
    }
    die();
} else {

    header($_SERVER['SERVER_PROTOCOL'] . ' 404 Not Found');
    include '404.html';
    die();
}

?>

<!DOCTYPE html>
<html lang="fa">

<head>
    <link rel="icon" type="image/x-icon" href="favicon.ico">
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0,user-scalable=no">
    <title>پرداخت نکست نامبر</title>





</head>

<body>

</body>

</html>