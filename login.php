<?php
// login.php

$mensaje = "";
$tipo = ""; // "ok" | "error"

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    // 1) Recibir datos con $_POST
    $usuario = trim($_POST["usuario"] ?? "");
    $password = $_POST["password"] ?? "";

    // 2) Validar campos vacíos
    if ($usuario === "" || $password === "") {
        $mensaje = "Complete usuario y contraseña.";
        $tipo = "error";
    } else {

        // 3) Datos almacenados en archivo de texto
        // Formato por línea: usuario|email|hash
        $archivo = __DIR__ . "/data/usuarios.txt";

        if (!file_exists($archivo)) {
            $mensaje = "No hay usuarios registrados. Cree una cuenta primero.";
            $tipo = "error";
        } else {

            $lineas = file($archivo, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
            $acceso = false;

            // 4) Comparar usuario y contraseña con los datos almacenados
            foreach ($lineas as $linea) {
                $partes = explode("|", $linea);

                if (count($partes) === 3) {
                    $u = trim($partes[0]);
                    $hash = trim($partes[2]);

                    // Comparación:
                    // - Usuario igual
                    // - Contraseña verifica con hash
                    if ($u === $usuario && password_verify($password, $hash)) {
                        $acceso = true;
                        break;
                    }
                }
            }

            // 5) Resultado según coincidencia
            if ($acceso) {
                $mensaje = "Acceso permitido ✅ Bienvenido, $usuario.";
                $tipo = "ok";
            } else {
                $mensaje = "Usuario o contraseña incorrectos.";
                $tipo = "error";
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <link href="public/css/style.css" rel="stylesheet">
</head>
<body>

<div class="login-container">
    <h2>ACCESO</h2>

    <!-- FORMULARIO: ya envía por POST a login.php -->
    <form method="post" action="login.php">

        <!-- INPUTS con NAME para que existan en $_POST -->
        <input type="text" name="usuario" placeholder="USUARIO" required>
        <input type="password" name="password" placeholder="CONTRASEÑA" required>

        <a href="#" class="forgot">Olvidó su contraseña o nombre de usuario? Click aquí</a>

        <button type="submit">INGRESAR</button>

        <a href="register.php" class="register-btn">REGISTRARSE</a>

        <!-- MENSAJE PHP -->
        <?php if ($mensaje): ?>
            <div class="msg <?= $tipo ?>"><?= htmlspecialchars($mensaje) ?></div>
        <?php endif; ?>

    </form>
</div>

</body>
</html>
