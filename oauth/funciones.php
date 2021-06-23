<?php declare(strict_types=1);

use Krizalys\Onedrive\Client;
use Krizalys\Onedrive\Onedrive;
use Krizalys\Onedrive\ClientState;
use Krizalys\Onedrive\Constant\AccessTokenStatus;

require_once __DIR__ . '/../vendor/autoload.php';

function oauth_obtener_configuracion(): array
{
    $configuracion = require __DIR__ . '/../configuracion.php';
    return $configuracion['oauth'];
}

function oauth_guardar_estado(ClientState $estado): void
{
    $contenido = base64_encode(serialize($estado));

    $resultado = @file_put_contents(__DIR__ . '/../var/oauth/state', $contenido, LOCK_EX);

    if ($resultado === false) {
        throw new \Exception('No ha sido posible guardar el ClientState');
    }
}

function oauth_obtener_estado(): ClientState
{
    $contenido = @file_get_contents(__DIR__ . '/../var/oauth/state');

    if ($contenido === false) {
        throw new \Exception('No ha sido posible recuperar el ClientState');
    }

    $state = unserialize(base64_decode($contenido));

    if (! $state instanceof ClientState) {
        throw new \Exception('Se esperaba una instancia de ClientState');
    }

    return $state;
}

function oauth_obtener_cliente(): Client
{
    $configuracion = oauth_obtener_configuracion();

    $cliente = Onedrive::client(
        $configuracion['ONEDRIVE_CLIENT_ID'],
        [
            'state' => oauth_obtener_estado(),
        ]
    );

    if ($cliente->getAccessTokenStatus() === AccessTokenStatus::EXPIRED) {
        $cliente->renewAccessToken($configuracion['ONEDRIVE_CLIENT_SECRET']);

        oauth_guardar_estado($cliente->getState());
    }

    return $cliente;
}

function oauth_obtener_root_id(): string
{
    return oauth_obtener_cliente()->getRoot()->id;
}

function oauth_obtener_referencias(string $itemId): array
{
    $cliente = oauth_obtener_cliente();
    $driveItem = $cliente->getDriveItemById($itemId);

    $referencias = [];

    while (true) {
        array_unshift($referencias, [
            'id' => $driveItem->id,
            'nombre' => $driveItem->name,
        ]);

        $referencia = $driveItem->parentReference;

        if (null === $referencia->id) {
            break;
        }

        $driveItem = $cliente->getDriveItemById($referencia->id);
    }

    return $referencias;
}


function oauth_obtener_archivos(string $itemId): array
{
    $cliente = oauth_obtener_cliente();
    $driveItem = $cliente->getDriveItemById($itemId);

    $archivos = [];
    $listaDeItems = $driveItem->children;

    foreach ($listaDeItems as $item) {

        $archivo = [
            'id' => $item->id,
            'nombre' => $item->name,
            'tipo' => 'archivo',
            'creado_en' => $item->createdDateTime->format('Y-m-d H:i:s'),
            'ruta' => '',
        ];

        if (null !== $item->folder) {
            $archivo['tipo'] = 'carpeta';
        }

        if (null !== $referencia = $item->parentReference) {
            $archivo['ruta'] = $referencia->path . '/' . $item->name;
        }

        $archivos[] = $archivo;
    }

    return $archivos;
}
