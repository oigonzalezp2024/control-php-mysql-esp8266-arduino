<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel de Control Industrial - Oscar Gonzalez</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .led-indicator { width: 15px; height: 15px; border-radius: 50%; display: inline-block; }
        .bg-success-led { background-color: #28a745; box-shadow: 0 0 10px #28a745; }
        .bg-danger-led { background-color: #dc3545; box-shadow: 0 0 10px #dc3545; }
    </style>
</head>
<body class="bg-light">

<nav class="navbar navbar-dark bg-dark mb-4">
    <div class="container">
        <span class="navbar-brand mb-0 h1">Sistema de Control Hexagonal</span>
        <span class="text-white-50 small">Tutoría: Oscar Gonzalez</span>
    </div>
</nav>

<div class="container">
    <div class="row g-4">
        <div class="col-md-4">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">Comandos de Hardware</div>
                <div class="card-body">
                    <form method="POST">
                        <div class="mb-3">
                            <label class="form-label">Seleccionar Estado</label>
                            <select name="estado" class="form-select">
                                <option value="en_espera">En Espera (0000)</option>
                                <option value="seleccionado">Seleccionado (0001)</option>
                                <option value="limpio">Limpio (0011)</option>
                                <option value="molido">Molido (0110)</option>
                                <option value="error">Fallo Crítico (1111)</option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary w-100">Ejecutar Acción</button>
                    </form>

                    <?php if (isset($mensaje) && $mensaje): ?>
                        <div class="alert alert-info mt-3 py-2 small"><?= htmlspecialchars($mensaje) ?></div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-header bg-dark text-white d-flex justify-content-between">
                    <span>Logs del Sistema (Base de Datos)</span>
                    <span class="badge bg-secondary">Últimos registros</span>
                </div>
                <div class="card-body p-0">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Fecha</th>
                                <th>Estado</th>
                                <th>Binario</th>
                                <th>Resultado</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (isset($historial) && count($historial) > 0): ?>
                                <?php foreach ($historial as $log): ?>
                                <tr>
                                    <td><small><?= $log['fecha'] ?></small></td>
                                    <td><span class="badge bg-outline-secondary text-dark border"><?= $log['estado'] ?></span></td>
                                    <td><code><?= $log['codigo_binario'] ?></code></td>
                                    <td>
                                        <span class="led-indicator <?= $log['fue_exitoso'] ? 'bg-success-led' : 'bg-danger-led' ?>"></span>
                                        <?= $log['fue_exitoso'] ? 'OK' : 'Error' ?>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr><td colspan="4" class="text-center text-muted py-3">No hay registros en la base de datos</td></tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<footer class="text-center mt-5 text-muted">
    <small>© 2025 - Implementación de Arquitectura Limpia</small>
</footer>

</body>
</html>
