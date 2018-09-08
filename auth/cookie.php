<?php

require($_SERVER['DOCUMENT_ROOT'] . "/init.php");

if (!isset($_COOKIE['REMEMBERME'])) {
    redirect('/?logout');
}

list($user, $token, $mac) = explode(':', $_COOKIE['REMEMBERME']);

$user = clean_data($user);

$query =
"SELECT
    token,
    created,
    days_valid
FROM
    tokens
WHERE
    user='{$user}' AND type = 'remember_me'";

$token_sql = sql_query($query, true);

$config = config_load();

if (!hash_equals(hash_hmac('sha512', $user . ':' . $token, $config['hmac_key']), $mac)) {
    echo 'cookie hash key doesnt match';
    // redirect('/?logout');
}
if (!hash_equals($token_sql['token'], $token)) {
    echo 'token db doesnt match token cookie';
    // redirect('/?logout');
}

echo 'success';
