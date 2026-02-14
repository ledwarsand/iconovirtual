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
if (!$data || !isset($data['name']) || !isset($data['email']) || !isset($data['phone']) || !isset($data['service'])) {
    echo json_encode([
        'success' => false,
        'message' => 'Datos incompletos'
    ]);
    exit;
}

// Sanitize data
$name = htmlspecialchars(trim($data['name']));
$email = filter_var(trim($data['email']), FILTER_SANITIZE_EMAIL);
$phone = htmlspecialchars(trim($data['phone']));
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
    'phone' => $phone,
    'service' => $service,
    'ip' => $_SERVER['REMOTE_ADDR'] ?? 'unknown',
    'user_agent' => $_SERVER['HTTP_USER_AGENT'] ?? 'unknown'
];

// 1. Save to JSON file local
$leadsFile = 'data/leads.json';
$leads = [];
if (file_exists($leadsFile)) {
    $leads = json_decode(file_get_contents($leadsFile), true) ?? [];
}
$leads[] = $lead;
file_put_contents($leadsFile, json_encode($leads, JSON_PRETTY_PRINT));

// 2. Send email notification
$to = 'comercial@iconovirtual.com';
$subject = 'Nuevo Lead - Icono Virtual';
$message = "Has recibido un nuevo contacto desde la web:\n\n";
$message .= "Nombre: $name\n";
$message .= "Email: $email\n";
$message .= "Teléfono: $phone\n";
$message .= "Servicio de Interés: $service\n";
$message .= "Fecha: " . $lead['timestamp'] . "\n";

$headers = "From: noreply@iconovirtual.com\r\n";
$headers .= "Reply-To: $email\r\n";
$headers .= "Content-Type: text/plain; charset=UTF-8\r\n";

mail($to, $subject, $message, $headers);

// 3. Save to Google Sheets (Drive)
// NOTE: You need to create a Google Apps Script and paste its URL here
$googleScriptUrl = 'https://script.google.com/a/macros/iconovirtual.com/s/AKfycbwENgEnyvK1TUtIITSbegf3WluBYVqbMlKmC_3FJpV_MP32hH0DY9GubcnEzbla6oX0/exec'; // Coloca aquí la URL de tu Google Apps Script

if ($googleScriptUrl) {
    $ch = curl_init($googleScriptUrl);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($lead));
    curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
    curl_exec($ch);
    curl_close($ch);
}

// Return success response
echo json_encode([
    'success' => true,
    'message' => 'Datos recibidos correctamente'
]);
?>