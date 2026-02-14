<?php
session_start();

// Anti-Brute Force Setup
$max_attempts = 3;
$lock_file = '../data/auth_attempts.json';

// Simple login check
if (isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true) {
    header('Location: dashboard.php');
    exit;
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';

    // CSRF Check (Simplified for example, ideally use tokens)

    // Load attempts
    $attempts_data = file_exists($lock_file) ? json_decode(file_get_contents($lock_file), true) : [];
    $user_ip = $_SERVER['REMOTE_ADDR'];

    if (isset($attempts_data[$user_ip]) && $attempts_data[$user_ip]['count'] >= $max_attempts && (time() - $attempts_data[$user_ip]['last_attempt']) < 900) {
        $error = "Demasiados intentos. Bloqueado por 15 minutos.";
    }
    else {
        // SQL Injection protection via static comparison (in a real app, use prepared statements)
        if ($username === 'leadsicono' && $password === 'f4/r28}7bK9E') {
            $_SESSION['admin_logged_in'] = true;
            // Reset attempts on success
            unset($attempts_data[$user_ip]);
            file_put_contents($lock_file, json_encode($attempts_data));
            header('Location: dashboard.php');
            exit;
        }
        else {
            $error = "Usuario o contraseña incorrectos.";

            // Log failed attempt
            if (!isset($attempts_data[$user_ip])) {
                $attempts_data[$user_ip] = ['count' => 1, 'last_attempt' => time()];
            }
            else {
                $attempts_data[$user_ip]['count']++;
                $attempts_data[$user_ip]['last_attempt'] = time();
            }
            file_put_contents($lock_file, json_encode($attempts_data));
        }
    }
}
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Icono Virtual Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            background-color: #f8f9fa;
            color: #212529;
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Inter', sans-serif;
        }

        .login-card {
            background: #ffffff;
            border: 1px solid #dee2e6;
            border-radius: 12px;
            padding: 2.5rem;
            width: 100%;
            max-width: 400px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        }

        .form-control {
            background-color: #ffffff;
            border-color: #dee2e6;
            color: #212529;
        }

        .form-control:focus {
            background-color: #ffffff;
            border-color: #007bff;
            color: #212529;
            box-shadow: 0 0 0 3px rgba(0, 123, 255, 0.15);
        }

        .btn-primary {
            background-color: #007bff;
            border-color: #007bff;
        }

        .btn-primary:hover {
            background-color: #0069d9;
            border-color: #0062cc;
        }

        .logo-img {
            max-width: 180px;
            margin-bottom: 2rem;
            display: block;
            margin-left: auto;
            margin-right: auto;
            /* Filter to make white logo dark if needed, but let's assume there's a dark logo or the user is fine with current */
            filter: brightness(0);
        }
    </style>
</head>

<body>
    <div class="login-card">
        <img src="https://iconovirtual.com.co/wp-content/uploads/2023/12/logo-icono-virtual-blanco.png" alt="Logo"
            class="logo-img">
        <h4 class="text-center mb-4">Acceso Administrativo</h4>

        <?php if ($error): ?>
        <div class="alert alert-danger py-2 small">
            <?php echo htmlspecialchars($error); ?>
        </div>
        <?php
endif; ?>

        <form method="POST">
            <div class="mb-3">
                <label class="form-label small">Usuario</label>
                <input type="text" name="username" class="form-control" required>
            </div>
            <div class="mb-4">
                <label class="form-label small">Contraseña</label>
                <input type="password" name="password" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-primary w-100 py-2 fw-semibold">
                Entrar <i class="fas fa-sign-in-alt ms-2"></i>
            </button>
        </form>
    </div>
</body>

</html>