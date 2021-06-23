<?php

require_once __DIR__ . '/vendor/autoload.php';

$itemId = $_GET['id'] ?? oauth_obtener_root_id();
$referencias = oauth_obtener_referencias($itemId);
$archivos = oauth_obtener_archivos($itemId);
$notificaciones = obtener_notificaciones();

?>
<!doctype html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-+0n0xVW2eSR5OomGNYDnhzAbDsOXxcvSN1TPprVMTNDbiYZCxYbOOl7+AMvyTG2x" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css">

    <title>Administrar OneDrive</title>
</head>

<body>
    <nav style="--bs-breadcrumb-divider: url(&#34;data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='8' height='8'%3E%3Cpath d='M2.5 0L1 1.5 3.5 4 1 6.5 2.5 8l4-4-4-4z' fill='white'/%3E%3C/svg%3E&#34;);" aria-label="breadcrumb">
        <ol class="breadcrumb m-0 bg-dark p-3">
            <?php foreach ($referencias as $referencia) : ?>
                <li class="breadcrumb-item">
                    <a href="./index.php?id=<?= $referencia['id'] ?>" class="text-decoration-none text-white"><?= $referencia['nombre'] ?></a>
                </li>
            <?php endforeach ?>
        </ol>
    </nav>

    <div class="container p-3">
        <?php foreach ($notificaciones as $notificacion) : ?>
            <div class="alert alert-<?= $notificacion['tipo'] ?> alert-dismissible fade show fs-5" role="alert">
                <span class="fw-bold"><?= $notificacion['titulo'] ?></span>
                <?php if(! empty($notificacion['mensaje'])): ?>
                    <p><?= $notificacion['mensaje'] ?>
                <?php endif ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endforeach ?>

        <h1 class="d-inline">Administrar OneDrive</h1>
        <hr>

        <div class="mb-3">
            <a href="#" class="text-decoration-none text-dark fs-2" title="subir un archivo" data-bs-toggle="modal" data-bs-target="#modal_subir_archivo">
                <i class="bi bi-file-earmark-plus"></i>
            </a>
            <a href="#" class="text-decoration-none text-dark fs-2" title="crear una carpeta" data-bs-toggle="modal" data-bs-target="#modal_crear_carpeta">
                <i class="bi bi-folder-plus"></i>
            </a>
        </div>

        <table class="table table-hover fs-5">
            <thead class="table-dark">
                <tr>
                    <th scope="col">Nombre</th>
                    <th scope="col">Última modificación</th>
                    <th scope="col">Opciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($archivos as $archivo) : ?>
                    <tr>
                        <td><?= $archivo['nombre'] ?></td>
                        <td><?= $archivo['creado_en'] ?></td>
                        <td>
                            <?php if ($archivo['tipo'] === 'carpeta') : ?>
                                <a href="./index.php?id=<?= $archivo['id'] ?>" class="text-decoration-none text-dark fs-2" title="abrir carpeta">
                                    <i class="bi bi-folder"></i>
                                </a>
                                <a href="#" class="text-decoration-none text-dark fs-2" title="borrar carpeta" onclick="borrar('<?= $archivo['id'] ?>', '<?= $archivo['nombre'] ?>')">
                                    <i class="bi bi-folder-x"></i>
                                </a>
                            <?php else : ?>
                                <a href="./servicios/descargar_archivo.php?id=<?= $archivo['id'] ?>" target="_blank" class="text-decoration-none text-dark fs-2" title="descargar archivo">
                                    <i class="bi bi-file-earmark-arrow-down"></i>
                                </a>
                                <a href="#" class="text-decoration-none text-dark fs-2" title="borrar archivo" onclick="borrar('<?= $archivo['id'] ?>', '<?= $archivo['nombre'] ?>')">
                                    <i class="bi bi-file-earmark-x"></i>
                                </a>
                            <?php endif ?>
                        </td>
                    </tr>
                <?php endforeach ?>
            </tbody>
        </table>
    </div>

    <div class="modal fade" id="modal_subir_archivo" tabindex="-1" aria-labelledby="label_modal_subir_archivo" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="label_modal_subir_archivo">Subir un archivo</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="./servicios/subir_archivo.php?id=<?= $itemId ?>" method="POST" enctype="multipart/form-data">
                        <div class="mb-3">
                            <input class="form-control form-control-lg" id="f01_subir_archivo" name="f01" type="file" required>
                        </div>
                        <div class="d-grid gap-2">
                            <input type="submit" class="btn btn-dark btn-lg" value="Subir">
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modal_crear_carpeta" tabindex="-1" aria-labelledby="label_modal_crear_carpeta" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="label_modal_crear_carpeta">Crear una carpeta</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="./servicios/crear_carpeta.php?id=<?= $itemId ?>" method="POST" enctype="multipart/form-data">
                        <div class="mb-3">
                            <label for="f01_nombre" class="fs-5">Nombre de la carpeta</label>
                            <input class="form-control form-control-lg" id="f01_crear_carpeta" name="f01" type="text" max="255" required>
                        </div>
                        <div class="d-grid gap-2">
                            <input type="submit" class="btn btn-dark btn-lg" value="Crear">
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modal_borrar" tabindex="-1" aria-labelledby="label_modal_borrar" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="label_modal_borrar">Borrar archivo</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="./servicios/borrar_archivo.php?id=<?= $itemId ?>" method="POST" enctype="multipart/form-data">
                        <div class="mb-3">
                            <label for="f01_nombre" class="fs-5">Se eliminara el siguiente archivo</label>
                            <input class="form-control form-control-lg" id="f01_nombre" type="text" required readonly>
                            <input class="form-control form-control-lg" id="f01_borrar" name="f01" type="text" required hidden>
                        </div>
                        <div class="d-grid gap-2">
                            <input type="submit" class="btn btn-dark btn-lg" value="Borrar">
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        const borrar = (id, nombre) => {
            document.getElementById("f01_borrar").value = id;
            document.getElementById("f01_nombre").value = nombre;

            const modal = new bootstrap.Modal(document.getElementById('modal_borrar'))
            modal.show()
        }
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-gtEjrD/SeCtmISkJkNUaaKMoLD0//ElJ19smozuHV6z3Iehds+3Ulb9Bn9Plx0x4" crossorigin="anonymous"></script>
</body>

</html>
