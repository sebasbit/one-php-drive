<?php

require_once __DIR__ . '/../vendor/autoload.php';

$config = oauth_obtener_configuracion();

if (!array_key_exists('code', $_GET)) {
    throw new \Exception('code undefined in $_GET');
}

$client = oauth_obtener_cliente();
$client->obtainAccessToken($config['ONEDRIVE_CLIENT_SECRET'], $_GET['code']);

oauth_guardar_estado($client->getState());

ir_a('/index.php', [], 302);
