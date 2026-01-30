<?php
// register.php
session_start(); // Iniciamos sesión para que pueda entrar directo al menú

$mensaje = "";
$tipo = ""; // "ok" | "error"

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    // 1) Recibir datos con $_POST
    $usuario  = trim($_POST["usuario"] ?? "");
    $email    = trim($_POST["email"] ?? "");
    $pass1    = $_POST["password"] ?? "";
    $pass2    = $_POST["password2"] ?? "";

    // 2) Validar campos vacíos
    if ($usuario === "" || $email === "" || $pass1 === "" || $pass2 === "") {
        $mensaje = "Debe completar todos los campos.";
        $tipo = "error";
    }
    // 3) Validar coincidencia de contraseñas
    elseif ($pass1 !== $pass2) {
        $mensaje = "Las contraseñas no coinciden.";
        $tipo = "error";
    } else {

        // 4) Preparar carpeta y archivo de almacenamiento
        $carpeta = __DIR__ . "/data";
        if (!is_dir($carpeta)) {
            mkdir($carpeta, 0777, true);
        }

        $archivo = $carpeta . "/usuarios.txt";

        // 5) Verificar si el usuario ya existe
        $existe = false;
        if (file_exists($archivo)) {
            $lineas = file($archivo, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
            foreach ($lineas as $linea) {
                $partes = explode("|", $linea);
                if (count($partes) >= 1 && trim($partes[0]) === $usuario) {
                    $existe = true;
                    break;
                }
            }
        }

        if ($existe) {
            $mensaje = "El usuario ya está registrado. Intente con otro.";
            $tipo = "error";
        } else {
            // 6) Guardar contraseña de forma segura (hash)
            $hash = password_hash($pass1, PASSWORD_DEFAULT);

            // 7) Guardar en archivo
            $registro = $usuario . "|" . $email . "|" . $hash . PHP_EOL;
            file_put_contents($archivo, $registro, FILE_APPEND);

            // 8) LOGUEO AUTOMÁTICO Y REDIRECCIÓN DIRECTA AL MENÚ
            $_SESSION['usuario'] = $usuario; 
            header("Location: sales.php");
            exit(); // Detiene la ejecución para que se haga el salto inmediatamente
        }
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Registro</title>
    <link href="public/css/style.css" rel="stylesheet">
</head>
<body>

<div class="login-container">
    <h2>CREAR CUENTA</h2>

    <form method="post" action="register.php">

        <input type="text" name="usuario" placeholder="USUARIO" required>
        <input type="email" name="email" placeholder="EMAIL" required>
        <input type="password" name="password" placeholder="CONTRASEÑA" required>
        <input type="password" name="password2" placeholder="REPITA CONTRASEÑA" required>

        <button type="submit">REGISTRAR Y ENTRAR</button>

        <a href="login.php" class="login-btn">VOLVER</a>

        <?php if ($mensaje): ?>
            <div class="msg <?= $tipo ?>"><?= htmlspecialchars($mensaje) ?></div>
        <?php endif; ?>

    </form>
</div>

</body>
</html>