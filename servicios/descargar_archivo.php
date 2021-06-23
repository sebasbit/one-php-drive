<?php

require_once __DIR__ . '/../vendor/autoload.php';

try {

    if (
        $_SERVER['REQUEST_METHOD'] !== 'GET'
        || empty($_GET['id'])
    ) {
        throw new \Exception('Pero que ha pasao!?');
    }

    $dirveItem = oauth_obtener_cliente()->getDriveItemById($_GET['id']);
    descargar($dirveItem->name, (string) $dirveItem->content, $dirveItem->size);

} catch (\Exception $e) {
    echo '<h1>Ups... ☹</h1><p>' . $e->getMessage() . '</p>';
}
