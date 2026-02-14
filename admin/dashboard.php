<?php
session_start();

// Security: Check session
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: index.php');
    exit;
}

// Security Headers
header("X-Frame-Options: SAMEORIGIN");
header("X-Content-Type-Options: nosniff");
header("X-XSS-Protection: 1; mode=block");

// Load data
$leads = json_decode(file_get_contents('../data/leads.json'), true) ?: [];
$content = json_decode(file_get_contents('../data/content.json'), true);
$tracking = json_decode(file_get_contents('../data/tracking.json'), true);

// Calculate stats for chart
$service_counts = [];
foreach ($leads as $lead) {
    $svc = $lead['service'] ?? 'Desconocido';
    $service_counts[$svc] = ($service_counts[$svc] ?? 0) + 1;
}
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Icono Virtual Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="css/style.css">
</head>

<body class="bg-light text-dark">

    <nav class="navbar navbar-expand-lg navbar-light bg-white border-bottom mb-4 px-4 shadow-sm">
        <div class="container-fluid">
            <a class="navbar-brand d-flex align-items-center" href="#">
                <img src="https://iconovirtual.com.co/wp-content/uploads/2023/12/logo-icono-virtual-blanco.png"
                    height="30" class="me-2" style="filter: brightness(0);">
                <span class="fw-bold text-dark">Admin Panel</span>
            </a>
            <div class="ms-auto">
                <a href="logout.php" class="btn btn-outline-danger btn-sm">Salir <i
                        class="fas fa-sign-out-alt ms-1"></i></a>
            </div>
        </div>
    </nav>

    <div class="container-fluid px-4">
        <div class="row g-4">

            <!-- Sidebar / Nav -->
            <div class="col-md-3 col-lg-2">
                <div class="list-group list-group-flush border rounded shadow-sm">
                    <a href="#leads" class="list-group-item list-group-item-action active" data-bs-toggle="list">
                        <i class="fas fa-users me-2"></i> Leads
                    </a>
                    <a href="#editor" class="list-group-item list-group-item-action" data-bs-toggle="list">
                        <i class="fas fa-edit me-2"></i> Editar Sitio
                    </a>
                    <a href="#tracking" class="list-group-item list-group-item-action" data-bs-toggle="list">
                        <i class="fas fa-code me-2"></i> Tracking
                    </a>
                </div>
            </div>

            <!-- Content Area -->
            <div class="col-md-9 col-lg-10">
                <div class="tab-content">

                    <!-- Leads Tab -->
                    <div class="tab-pane fade show active" id="leads">
                        <div class="row mb-4">
                            <div class="col-md-12">
                                <div class="card bg-white border">
                                    <div class="card-body">
                                        <h5 class="card-title mb-4">Distribución de Servicios</h5>
                                        <div style="max-height: 250px; display: flex; justify-content: center;">
                                            <canvas id="leadsChart"></canvas>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="card bg-white border">
                            <div
                                class="card-header border-bottom d-flex justify-content-between align-items-center bg-light">
                                <h5 class="mb-0 text-dark font-weight-bold">Lista de Leads</h5>
                                <div class="d-flex gap-2 align-items-center">
                                    <input type="date" id="dateFilter"
                                        class="form-control form-control-sm border-secondary-subtle"
                                        style="width: auto;">
                                    <select id="serviceFilter"
                                        class="form-select form-select-sm border-secondary-subtle" style="width: auto;">
                                        <option value="">Todos los servicios</option>
                                        <?php foreach (array_keys($service_counts) as $svc): ?>
                                        <option value="<?php echo htmlspecialchars($svc); ?>">
                                            <?php echo htmlspecialchars($svc); ?>
                                        </option>
                                        <?php
endforeach; ?>
                                    </select>
                                    <input type="text" id="leadSearch"
                                        class="form-control form-control-sm border-secondary-subtle"
                                        placeholder="Buscar..." style="width: 150px;">
                                    <button id="applyFilters" class="btn btn-primary btn-sm px-3">Aplicar</button>
                                    <button id="clearFilters"
                                        class="btn btn-outline-secondary btn-sm px-3">Limpiar</button>
                                </div>
                            </div>
                            <div class="card-body p-0 overflow-auto">
                                <table class="table table-hover mb-0" id="leadsTable">
                                    <thead>
                                        <tr>
                                            <th>Fecha</th>
                                            <th>Nombre</th>
                                            <th>Correo</th>
                                            <th>Servicio</th>
                                            <th>Teléfono</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach (array_reverse($leads) as $lead): ?>
                                        <tr class="lead-row"
                                            data-service="<?php echo htmlspecialchars($lead['service'] ?? ''); ?>"
                                            data-date="<?php echo date('Y-m-d', strtotime($lead['timestamp'] ?? '')); ?>">
                                            <td class="small">
                                                <?php echo htmlspecialchars($lead['timestamp'] ?? ''); ?>
                                            </td>
                                            <td>
                                                <?php echo htmlspecialchars($lead['name'] ?? ''); ?>
                                            </td>
                                            <td>
                                                <?php echo htmlspecialchars($lead['email'] ?? ''); ?>
                                            </td>
                                            <td><span class="badge bg-primary">
                                                    <?php echo htmlspecialchars($lead['service'] ?? ''); ?>
                                                </span></td>
                                            <td>
                                                <a href="https://wa.me/57<?php echo preg_replace('/\D/', '', $lead['phone'] ?? ''); ?>"
                                                    target="_blank" class="btn btn-success btn-sm">
                                                    <i class="fab fa-whatsapp"></i>
                                                    <?php echo htmlspecialchars($lead['phone'] ?? ''); ?>
                                                </a>
                                            </td>
                                        </tr>
                                        <?php
endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <!-- Editor Tab -->
                    <div class="tab-pane fade" id="editor">
                        <div class="card bg-secondary-subtle border-secondary">
                            <div class="card-header border-secondary">
                                <h5 class="mb-0">Edición de Contenido Completo</h5>
                            </div>
                            <div class="card-body">
                                <form id="contentForm">
                                    <!-- Hero -->
                                    <div class="section-edit mb-4 p-3 border rounded bg-light">
                                        <h6 class="text-primary mb-3"><i class="fas fa-rocket me-2"></i>Sección Hero
                                        </h6>
                                        <div class="mb-3">
                                            <label class="form-label">Título Principal</label>
                                            <input type="text" name="hero_title"
                                                class="form-control border-secondary-subtle"
                                                value="<?php echo htmlspecialchars($content['hero']['title']); ?>">
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Subtítulo</label>
                                            <textarea name="hero_subtitle" class="form-control border-secondary-subtle"
                                                rows="3"><?php echo htmlspecialchars($content['hero']['subtitle']); ?></textarea>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Texto Botón (CTA)</label>
                                            <input type="text" name="hero_cta"
                                                class="form-control border-secondary-subtle"
                                                value="<?php echo htmlspecialchars($content['hero']['cta']); ?>">
                                        </div>
                                    </div>

                                    <!-- Services -->
                                    <div class="section-edit mb-4 p-3 border rounded bg-light">
                                        <h6 class="text-primary mb-3"><i class="fas fa-brain me-2"></i>Agentes IA
                                            (Servicios)</h6>
                                        <?php foreach ($content['services'] as $idx => $service): ?>
                                        <div class="mb-4 pb-3 border-bottom border-secondary last-no-border">
                                            <p class="fw-bold text-primary">Agente
                                                <?php echo $idx + 1; ?>:
                                                <?php echo htmlspecialchars($service['agent_name']); ?>
                                            </p>
                                            <input type="hidden" name="services[<?php echo $idx; ?>][id]"
                                                value="<?php echo $service['id']; ?>">
                                            <div class="row">
                                                <div class="col-md-6 mb-3">
                                                    <label class="form-label small">Nombre del Agente</label>
                                                    <input type="text" name="services[<?php echo $idx; ?>][agent_name]"
                                                        class="form-control border-secondary-subtle form-control-sm"
                                                        value="<?php echo htmlspecialchars($service['agent_name']); ?>">
                                                </div>
                                                <div class="col-md-6 mb-3">
                                                    <label class="form-label small">Imagen (nombre archivo)</label>
                                                    <input type="text" name="services[<?php echo $idx; ?>][image]"
                                                        class="form-control border-secondary-subtle form-control-sm"
                                                        value="<?php echo htmlspecialchars($service['image']); ?>">
                                                </div>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label small">Título del Servicio</label>
                                                <input type="text" name="services[<?php echo $idx; ?>][title]"
                                                    class="form-control border-secondary-subtle form-control-sm"
                                                    value="<?php echo htmlspecialchars($service['title']); ?>">
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label small">Descripción</label>
                                                <textarea name="services[<?php echo $idx; ?>][description]"
                                                    class="form-control border-secondary-subtle form-control-sm"
                                                    rows="2"><?php echo htmlspecialchars($service['description']); ?></textarea>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label small">Detalles / Viñetas (una por
                                                    línea)</label>
                                                <textarea name="services[<?php echo $idx; ?>][details]"
                                                    class="form-control border-secondary-subtle form-control-sm"
                                                    rows="3"><?php echo htmlspecialchars(implode("\n", $service['details'])); ?></textarea>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label small">Texto Botón</label>
                                                <input type="text" name="services[<?php echo $idx; ?>][cta]"
                                                    class="form-control border-secondary-subtle form-control-sm"
                                                    value="<?php echo htmlspecialchars($service['cta']); ?>">
                                            </div>
                                        </div>
                                        <?php
endforeach; ?>
                                    </div>

                                    <!-- Form -->
                                    <div class="section-edit mb-4 p-3 border rounded bg-light">
                                        <h6 class="text-primary mb-3"><i
                                                class="fas fa-envelope-open-text me-2"></i>Formulario de Contacto</h6>
                                        <div class="mb-3">
                                            <label class="form-label">Título Formulario</label>
                                            <input type="text" name="form_title"
                                                class="form-control border-secondary-subtle"
                                                value="<?php echo htmlspecialchars($content['form']['title']); ?>">
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Texto Botón</label>
                                            <input type="text" name="form_button"
                                                class="form-control border-secondary-subtle"
                                                value="<?php echo htmlspecialchars($content['form']['button']); ?>">
                                        </div>
                                    </div>

                                    <!-- WhatsApp & Footer -->
                                    <div class="section-edit mb-4 p-3 border rounded bg-light">
                                        <h6 class="text-primary mb-3"><i class="fab fa-whatsapp me-2"></i>WhatsApp y
                                            Contacto</h6>
                                        <div class="row">
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label">Número WhatsApp</label>
                                                <input type="text" name="whatsapp_number"
                                                    class="form-control border-secondary-subtle"
                                                    value="<?php echo htmlspecialchars($content['whatsapp']['number']); ?>">
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label">Correo Comercial</label>
                                                <input type="email" name="whatsapp_email"
                                                    class="form-control border-secondary-subtle"
                                                    value="<?php echo htmlspecialchars($content['whatsapp']['email']); ?>">
                                            </div>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Mensaje Predeterminado WhatsApp</label>
                                            <textarea name="whatsapp_message"
                                                class="form-control border-secondary-subtle"
                                                rows="2"><?php echo htmlspecialchars($content['whatsapp']['message']); ?></textarea>
                                        </div>
                                    </div>

                                    <div class="d-flex justify-content-end gap-2">
                                        <button type="reset" class="btn btn-outline-secondary">Descartar</button>
                                        <button type="submit" class="btn btn-primary px-4">Guardar Todo</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    <!-- Tracking Tab -->
                    <div class="tab-pane fade" id="tracking">
                        <div class="card bg-white border">
                            <div class="card-header border-bottom bg-light">
                                <h5 class="mb-0">Píxeles y Traqueo</h5>
                            </div>
                            <div class="card-body">
                                <div id="trackingList">
                                    <?php foreach ($tracking as $key => $pixel): ?>
                                    <div class="mb-4 p-3 border rounded bg-light">
                                        <div class="d-flex justify-content-between align-items-center mb-2">
                                            <h6 class="mb-0 text-capitalize">
                                                <?php echo str_replace('_', ' ', $key); ?>
                                            </h6>
                                            <div class="form-check form-switch">
                                                <input class="form-check-input tracking-toggle" type="checkbox"
                                                    data-key="<?php echo $key; ?>" <?php echo $pixel['enabled']
                                                    ? 'checked' : '' ; ?>>
                                                <label class="form-check-label">
                                                    <?php echo $pixel['enabled'] ? 'Habilitado' : 'Deshabilitado'; ?>
                                                </label>
                                            </div>
                                        </div>
                                        <textarea class="form-control border-secondary-subtle font-monospace small"
                                            rows="3"
                                            disabled><?php echo htmlspecialchars(json_encode($pixel, JSON_PRETTY_PRINT)); ?></textarea>
                                    </div>
                                    <?php
endforeach; ?>
                                </div>
                                <div class="alert alert-info py-2 small">
                                    <i class="fas fa-info-circle me-1"></i> La edición directa de píxeles está limitada
                                    a la habilitación/deshabilitación en esta versión.
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const chartData = <?php echo json_encode([
    'labels' => array_keys($service_counts),
    'data' => array_values($service_counts)
]); ?>;
    </script>
    <script src="js/script.js"></script>
</body>

</html>