<?php

use Krizalys\Onedrive\Onedrive;

require_once __DIR__ . '/../vendor/autoload.php';

$config = oauth_obtener_configuracion();
$client = Onedrive::client($config['ONEDRIVE_CLIENT_ID']);

$url = $client->getLogInUrl([
    'files.read',
    'files.read.all',
    'files.readwrite',
    'files.readwrite.all',
    'offline_access',
], $config['ONEDRIVE_REDIRECT_URI']);

oauth_guardar_estado($client->getState());

header('HTTP/1.1 302 Found', true, 302);
header("Location: $url");
