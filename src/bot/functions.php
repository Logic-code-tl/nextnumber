<?php

declare(strict_types=1);

function bot($method, $params)
{


    $url  = API_ENDPOINT . $method . '?' . http_build_query($params);

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4);
    curl_setopt($ch, CURLOPT_FAILONERROR, true);
    $res = curl_exec($ch);
    if (curl_errno($ch)) {

        throw new Exception(curl_error($ch));
    }
    curl_close($ch);

    return $res;
}

function create_payment(int $id,  int $amount)
{
    if ($amount < 5000) {
        throw new Exception();
    }

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


function send_message(string $text = null, int $chat_id = null, array $reply_markup = null, string $parse_mode = 'html', array $param = null)
{
    global $update;

    if ($reply_markup != null) {
        $reply_markup = json_encode($reply_markup);
    }

    if ($param == null) {

        $chat_id ?? $chat_id = $update->message->chat->id ?? $chat_id = $update->callback_query->from->id;

        $param = [
            'chat_id' => $chat_id,
            'text' => $text,
            'parse_mode' => $parse_mode,
            'reply_markup' => $reply_markup,
            'disable_web_page_preview' => true

        ];
        $url = API_ENDPOINT . 'sendMessage?' . http_build_query($param);
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4);
        curl_setopt($ch, CURLOPT_FAILONERROR, true);

        $res = curl_exec($ch);
        if (curl_errno($ch)) {
            throw new Exception(curl_error($ch));
        }
        curl_close($ch);
        return $res;
    } else {

        $url = API_ENDPOINT . 'sendMessage?' . http_build_query($param);
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4);
        curl_setopt($ch, CURLOPT_FAILONERROR, true);

        $res = curl_exec($ch);
        if (curl_errno($ch)) {
            throw new Exception(curl_error($ch));
        }
        curl_close($ch);
        return $res;
    }
}
function validate_data($data, $token)
{
    $hash = $data['hash'];
    $data_check_string = "auth_date=$data[auth_date]\nquery_id=$data[query_id]\nuser=$data[user]";
    $secret_key = hash_hmac('sha256', $token, "WebAppData", true);
    return hash_hmac('sha256', $data_check_string, $secret_key) == $hash; // true if data is from Telegram
}
