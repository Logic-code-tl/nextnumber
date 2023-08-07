<?php



require_once __DIR__ . '/../bot/functions.php';

if (isset($_GET['id'], $_GET['order_id'])) {



    $params = [
        'id' => $_GET['id'],
        'order_id' => $_GET['order_id'],
    ];

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, 'https://api.idpay.ir/v1.1/payment/verify');
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($params));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
        'Content-Type: application/json',
        'X-API-KEY: ' . IDPAY_TOKEN,

    ));

    $response = json_decode(curl_exec($ch), true);
    $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);

    curl_close($ch);

    if ($http_code == 200) {

        $user_data = $db->select('user_payment', ['user_id', 'finished', 'amount'], [
            'trans_id' => $_GET['id'],
            'order_id' => $_GET['order_id']
        ]);

        if ($user_data[0]['finished'] == true) {
            header("{$_SERVER['SERVER_PROTOCOL']} 403 Forbidden");
            include_once __DIR__ . "/../../403.shtml";
            die;
        }

        $db->update('user_payment', [
            'finished' => true
        ], [

            'order_id' => $_GET['order_id'],
            'trans_id' => $_GET['id']
        ]);

        $db->update('user', [
            'balance[+]' =>  $user_data[0]['amount'],
        ], [
            'id' => $user_data[0]['user_id']
        ]);


        $params = [
            'text' => 'تبریک به مقدار ' .  $user_data[0]['amount'] . "تومان" . "\n" . "به حساب شما افزوده شد برای دیدن مقدار شارژ کیف پول \n /start",
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
        die;
    } else {
        echo '<div style=" width: auto; height: 5vw; background-color:
        red; position: relative; margin-right: auto; margin-left: auto; "></div>
    <div style="position: relative; font-size: 2vw; font-family: monospace;
        "> <h1
            style="text-align:
            center;">خطایی در پرداخت رخ داده است </div>';
    }
    die;
} else {

    header($_SERVER['SERVER_PROTOCOL'] . ' 404 Not Found');
    include_once __DIR__ . "/../../404.shtml";
    die;
}

?>

<!DOCTYPE html>
<html lang="fa">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0,user-scalable=no">
    <title>پرداخت نکست نامبر</title>





</head>

<body>

</body>

</html>