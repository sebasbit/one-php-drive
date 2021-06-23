<?php

require_once __DIR__ . '/../vendor/autoload.php';

try {

    if (
        $_SERVER['REQUEST_METHOD'] !== 'POST'
        || empty($_POST['f01'])
        || empty($_GET['id'])
    ) {
        throw new \Exception('Pero que ha pasao!?');
    }

    oauth_obtener_cliente()
        ->getDriveItemById($_GET['id'])
        ->delete();

} catch (\Exception $e) {

    notificar_error('No se borró el archivo :(', $e->getMessage());
    ir_a('./index.php', ['id' => $_GET['id']]);

}

notificar_ok('Se borró el archivo :D');
ir_a('/index.php', ['id' => $_GET['id']]);
