<?php
// ============================================
// FORM PROCESSING SCRIPT
// ============================================

header('Content-Type: application/json');

// Load tracking configuration
$tracking = json_decode(file_get_contents('data/tracking.json'), true);

// Get POST data
$input = file_get_contents('php://input');
$data = json_decode($input, true);

// Validate data
if (!$data || !isset($data['name']) || !isset($data['email']) || !isset($data['service'])) {
    echo json_encode([
        'success' => false,
        'message' => 'Datos incompletos'
    ]);
    exit;
}

// Sanitize data
$name = htmlspecialchars(trim($data['name']));
$email = filter_var(trim($data['email']), FILTER_SANITIZE_EMAIL);
$service = htmlspecialchars(trim($data['service']));

// Validate email
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo json_encode([
        'success' => false,
        'message' => 'Email inválido'
    ]);
    exit;
}

// Prepare data for storage
$lead = [
    'timestamp' => date('Y-m-d H:i:s'),
    'name' => $name,
    'email' => $email,
    'service' => $service,
    'ip' => $_SERVER['REMOTE_ADDR'] ?? 'unknown',
    'user_agent' => $_SERVER['HTTP_USER_AGENT'] ?? 'unknown'
];

// Save to JSON file (you can replace this with database storage)
$leadsFile = 'data/leads.json';
$leads = [];

if (file_exists($leadsFile)) {
    $leads = json_decode(file_get_contents($leadsFile), true) ?? [];
}

$leads[] = $lead;
file_put_contents($leadsFile, json_encode($leads, JSON_PRETTY_PRINT));

// Optional: Send email notification
// You can configure this section to send emails to your team
$sendEmail = false; // Set to true to enable email notifications

if ($sendEmail) {
    $to = 'info@iconovirtual.com'; // Replace with your email
    $subject = 'Nuevo Lead - Icono Virtual';
    $message = "Nuevo contacto:\n\n";
    $message .= "Nombre: $name\n";
    $message .= "Email: $email\n";
    $message .= "Servicio: $service\n";
    $message .= "Fecha: " . $lead['timestamp'] . "\n";

    $headers = "From: noreply@iconovirtual.com\r\n";
    $headers .= "Reply-To: $email\r\n";

    mail($to, $subject, $message, $headers);
}

// Return success response
echo json_encode([
    'success' => true,
    'message' => 'Datos recibidos correctamente'
]);
?>
