<?php

require_once __DIR__ . '/../vendor/autoload.php';

try {

    if (
        $_SERVER['REQUEST_METHOD'] !== 'POST'
        || empty($_FILES['f01'])
        || empty($_GET['id'])
    ) {
        throw new \Exception('Pero que ha pasao!?');
    }

    if ($_FILES['f01']['error'] !== UPLOAD_ERR_OK) {
        throw new \Exception('Error al subir el archivo');
    }

    $contenido = @file_get_contents($_FILES['f01']['tmp_name']);

    if ($contenido === false) {
        throw new \Exception('Error leyendo el contenido del archivo');
    }

    oauth_obtener_cliente()
        ->getDriveItemById($_GET['id'])
        ->upload($_FILES['f01']['name'], $contenido);

} catch (\Exception $e) {

    notificar_error('No se subió el archivo :(', $e->getMessage());
    ir_a('./index.php', ['id' => $_GET['id']]);

}

notificar_ok('Se subió el archivo :D');
ir_a('/index.php', ['id' => $_GET['id']]);
