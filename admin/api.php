<?php
session_start();

// Security check
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    http_response_code(403);
    echo json_encode(['error' => 'No autorizado']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';

    if ($action === 'update_content') {
        $json_path = '../data/content.json';
        $log_path = '../data/error_log.txt';

        function log_error($msg)
        {
            global $log_path;
            file_put_contents($log_path, "[" . date('Y-m-d H:i:s') . "] " . $msg . "\n", FILE_APPEND);
        }

        if (!file_exists($json_path)) {
            log_error("Archivo no encontrado: " . $json_path);
            echo json_encode(['success' => false, 'error' => 'Archivo no encontrado']);
            exit;
        }

        $raw_content = file_get_contents($json_path);
        $content = json_decode($raw_content, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            log_error("Error al decodificar JSON: " . json_last_error_msg());
            echo json_encode(['success' => false, 'error' => 'Error en formato de datos']);
            exit;
        }

        // Update Hero
        if (isset($_POST['hero_title']))
            $content['hero']['title'] = $_POST['hero_title'];
        if (isset($_POST['hero_subtitle']))
            $content['hero']['subtitle'] = $_POST['hero_subtitle'];
        if (isset($_POST['hero_cta']))
            $content['hero']['cta'] = $_POST['hero_cta'];

        // Update Services (IA Agents)
        if (isset($_POST['services']) && is_array($_POST['services'])) {
            foreach ($_POST['services'] as $idx => $s_data) {
                if (isset($content['services'][$idx])) {
                    $content['services'][$idx]['agent_name'] = $s_data['agent_name'] ?? $content['services'][$idx]['agent_name'];
                    $content['services'][$idx]['title'] = $s_data['title'] ?? $content['services'][$idx]['title'];
                    $content['services'][$idx]['description'] = $s_data['description'] ?? $content['services'][$idx]['description'];
                    $content['services'][$idx]['image'] = $s_data['image'] ?? $content['services'][$idx]['image'];
                    $content['services'][$idx]['cta'] = $s_data['cta'] ?? $content['services'][$idx]['cta'];

                    if (isset($s_data['details'])) {
                        $content['services'][$idx]['details'] = array_filter(explode("\n", str_replace("\r", "", $s_data['details'])));
                    }
                }
            }
        }

        // Update Form
        if (isset($_POST['form_title']))
            $content['form']['title'] = $_POST['form_title'];
        if (isset($_POST['form_fields']) && is_array($_POST['form_fields'])) {
            foreach ($_POST['form_fields'] as $key => $val) {
                if (isset($content['form']['fields'][$key])) {
                    $content['form']['fields'][$key] = $val;
                }
            }
        }
        if (isset($_POST['form_button']))
            $content['form']['button'] = $_POST['form_button'];

        // Update WhatsApp
        if (isset($_POST['whatsapp_number']))
            $content['whatsapp']['number'] = $_POST['whatsapp_number'];
        if (isset($_POST['whatsapp_email']))
            $content['whatsapp']['email'] = $_POST['whatsapp_email'];
        if (isset($_POST['whatsapp_message']))
            $content['whatsapp']['message'] = $_POST['whatsapp_message'];

        $new_json = json_encode($content, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        if ($new_json === false) {
            log_error("Error al codificar JSON: " . json_last_error_msg());
            echo json_encode(['success' => false, 'error' => 'Error al procesar datos']);
            exit;
        }

        if (file_put_contents($json_path, $new_json)) {
            echo json_encode(['success' => true]);
        }
        else {
            log_error("Error al escribir en: " . $json_path . ". Verifique permisos.");
            echo json_encode(['success' => false, 'error' => 'Error al guardar archivo (permisos)']);
        }
        exit;
    }

    if ($action === 'toggle_tracking') {
        $key = $_POST['key'] ?? '';
        $enabled = ($_POST['enabled'] === 'true');

        $tracking = json_decode(file_get_contents('../data/tracking.json'), true);

        if (isset($tracking[$key])) {
            $tracking[$key]['enabled'] = $enabled;
            if (file_put_contents('../data/tracking.json', json_encode($tracking, JSON_PRETTY_PRINT))) {
                echo json_encode(['success' => true]);
            }
            else {
                echo json_encode(['success' => false, 'error' => 'Error al guardar archivo']);
            }
        }
        else {
            echo json_encode(['success' => false, 'error' => 'Pixel no encontrado']);
        }
        exit;
    }
}
?>