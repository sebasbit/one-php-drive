<?php declare(strict_types=1);

function http_obtener_configuracion(): array
{
    $configuracion = require __DIR__ . '/../configuracion.php';
    return $configuracion['http'];
}

function ir_a(string $url, array $parametros, int $estado = 200): void
{
    $configuracion = http_obtener_configuracion();

    if (! empty($parametros)) {
        $qParametros = [];
        foreach ($parametros as $nombre => $valor) {
            $qParametros[] = "$nombre=$valor";
        }
        $url .= '?' . implode('&', $qParametros);
    }

    http_response_code($estado);
    header('Location: ' . $configuracion['BASE_URL'] . $url);
    exit(0);
}

function notificar(string $tipo, string $titulo, string $mensaje = ''): void
{
    if (session_status() !== PHP_SESSION_ACTIVE) {
        session_start();
    }

    if (! isset($_SESSION['notificaciones'])) {
        $_SESSION['notificaciones'] = [];
    }

    switch ($tipo) {
        case 'error':
            $tipo = 'danger';
            break;
        case 'ok':
            $tipo = 'success';
            break;
        default:
            $tipo = 'info';
            break;
    }

    $_SESSION['notificaciones'][] = [
        'tipo' => $tipo,
        'titulo' => $titulo,
        'mensaje' => $mensaje,
    ];
}

function notificar_error(string $titulo, string $mensaje = ''): void
{
    notificar('error', $titulo, $mensaje);
}

function notificar_ok(string $titulo, string $mensaje = ''): void
{
    notificar('ok', $titulo, $mensaje);
}

function obtener_notificaciones(): array
{
    if (session_status() !== PHP_SESSION_ACTIVE) {
        session_start();
    }

    if (! isset($_SESSION['notificaciones'])) {
        $_SESSION['notificaciones'] = [];
    }

    $notificaciones = $_SESSION['notificaciones'];
    $_SESSION['notificaciones'] = [];

    return $notificaciones;
}

function descargar(string $archivo, string $contenido, int $tamano): void
{
    header("Cache-Control: public");
    header("Content-Description: File Transfer");
    header("Content-Disposition: attachment; filename=$archivo");
    header("Content-Type: application/octet-stream");
    header("Content-Length: ".$tamano);
    header("Content-Transfer-Encoding: binary");

    @ob_get_clean();
    echo $contenido;
}
