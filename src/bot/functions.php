<?php



function bot($method, $params)
{

    return file_get_contents(API_ENDPOINT . $method . '?' . http_build_query($params));
}
function create_payment($id, $amount)
{

    return PAYMENT_CREATE_URL . '?' . 'id=' . $id . '&' . 'amount=' . $amount;
}
function getRandomString($n)
{
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $randomString = '';

    for ($i = 0; $i < $n; $i++) {
        $index = rand(0, strlen($characters) - 1);
        $randomString .= $characters[$index];
    }

    return $randomString;
}


function send_message(string $text, int $chat_id = null, array $reply_markup = null, string $parse_mode = 'html', array $param = null)
{
    global $update;
    $chat_id ?? $chat_id = $update->message->chat->id;



    if ($param == null) {
        $params = [
            'text' => $text,
            'chat_id' => $chat_id,
            'parse_mode' => $parse_mode,
            'reply_markup' => json_encode($reply_markup)

        ];
        return file_get_contents(API_ENDPOINT . 'sendMessage?' . http_build_query($params));
    } else {

        return file_get_contents(API_ENDPOINT . 'sendMessage?' . http_build_query($param));
    }
}
