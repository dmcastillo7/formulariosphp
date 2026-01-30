<?php
// sales.php

$IVA_PORC = 0.16;

$precios = [
    "hamburguesa" => 35.00,
    "papas"       => 15.00,
    "refresco"    => 12.00,
    "pizza"       => 70.00,
    "nuggets"     => 25.00,
    "ensalada"    => 30.00,
    "yogurt"      => 15.00,
    "agua"        => 12.00
];

$paquete = $_POST["paquete"] ?? "otras";

$cantidades = [
    "hamburguesa" => (int)($_POST["hamburguesa"] ?? 0),
    "papas"       => (int)($_POST["papas"] ?? 0),
    "refresco"    => (int)($_POST["refresco"] ?? 0),
    "pizza"       => (int)($_POST["pizza"] ?? 0),
    "nuggets"     => (int)($_POST["nuggets"] ?? 0),
    "ensalada"    => (int)($_POST["ensalada"] ?? 0),
    "yogurt"      => (int)($_POST["yogurt"] ?? 0),
    "agua"        => (int)($_POST["agua"] ?? 0),
];

$pagoCliente = (float)($_POST["pago"] ?? 0);

$subtotal = 0.0;
$iva = 0.0;
$total = 0.0;
$cambio = 0.0;

$mensaje = "";
$tipo = ""; // ok | error

// ------------------
// Cálculo si envían el formulario
// ------------------
if ($_SERVER["REQUEST_METHOD"] === "POST") {

    // 1) Calcular subtotal por pedidos individuales (ciclo)
    foreach ($cantidades as $producto => $cant) {
        if ($cant < 0) $cant = 0; // por seguridad
        $subtotal += $cant * $precios[$producto];
    }

    // 2) Calcular subtotal por paquete (switch)
    // Nota: aquí el "paquete" suma 1 unidad de cada producto del combo.
    switch ($paquete) {
        case "combo1": // Hamburguesa + Papas + Refresco
            $subtotal += $precios["hamburguesa"] + $precios["papas"] + $precios["refresco"];
            break;

        case "combo2": // Pizza + Nuggets + Refresco
            $subtotal += $precios["pizza"] + $precios["nuggets"] + $precios["refresco"];
            break;

        case "combo3": // Ensalada + Yogurt + Agua
            $subtotal += $precios["ensalada"] + $precios["yogurt"] + $precios["agua"];
            break;

        case "otras":
        default:
            // No suma nada
            break;
    }

    // 3) IVA y Total
    $iva = $subtotal * $IVA_PORC;
    $total = $subtotal + $iva;

    // 4) Validación de pago y cambio
    if ($pagoCliente > 0) {
        if ($pagoCliente < $total) {
            $mensaje = "Pago insuficiente. Debe completar el total a pagar.";
            $tipo = "error";
            $cambio = 0.0;
        } else {
            $cambio = $pagoCliente - $total;
            $mensaje = "Pago realizado correctamente ✅";
            $tipo = "ok";
        }
    } else {
        // Si todavía no ingresan pago, solo mostramos cálculo
        $mensaje = "Cálculo realizado. Ingrese el pago para obtener el cambio.";
        $tipo = "ok";
    }
}

// Función para mostrar valores con 2 decimales
function money($v) {
    return number_format((float)$v, 2, ".", "");
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Formulario de Ventas</title>
    <link href="public/css/sales.css" rel="stylesheet">
</head>
<body>

<div class="container">
    <h1>Sistema de Ventas</h1>

    <!-- IMPORTANTE: method y action -->
    <form class="ventas-form" method="post" action="sales.php">

        <!-- Paquetes -->
        <section class="card">
            <h2>Paquetes</h2>

            <label>
                <input type="radio" name="paquete" value="combo1" <?= ($paquete==="combo1") ? "checked" : "" ?>>
                Hamburguesa, Papas y Refresco
            </label>

            <label>
                <input type="radio" name="paquete" value="combo2" <?= ($paquete==="combo2") ? "checked" : "" ?>>
                Pizza, Nuggets y Refresco
            </label>

            <label>
                <input type="radio" name="paquete" value="combo3" <?= ($paquete==="combo3") ? "checked" : "" ?>>
                Ensalada, Yogurt y Agua
            </label>

            <label>
                <input type="radio" name="paquete" value="otras" <?= ($paquete==="otras") ? "checked" : "" ?>>
                Otras opciones
            </label>
        </section>

        <!-- Pedidos -->
        <section class="card">
            <h2>Pedidos</h2>

            <div class="pedido">
                <input type="number" name="hamburguesa" min="0" value="<?= $cantidades["hamburguesa"] ?>">
                <span>Hamburguesa</span>
                <strong>$35.00</strong>
            </div>

            <div class="pedido">
                <input type="number" name="papas" min="0" value="<?= $cantidades["papas"] ?>">
                <span>Papas</span>
                <strong>$15.00</strong>
            </div>

            <div class="pedido">
                <input type="number" name="refresco" min="0" value="<?= $cantidades["refresco"] ?>">
                <span>Refresco</span>
                <strong>$12.00</strong>
            </div>

            <div class="pedido">
                <input type="number" name="pizza" min="0" value="<?= $cantidades["pizza"] ?>">
                <span>Pizza</span>
                <strong>$70.00</strong>
            </div>

            <div class="pedido">
                <input type="number" name="nuggets" min="0" value="<?= $cantidades["nuggets"] ?>">
                <span>Nuggets</span>
                <strong>$25.00</strong>
            </div>

            <div class="pedido">
                <input type="number" name="ensalada" min="0" value="<?= $cantidades["ensalada"] ?>">
                <span>Ensalada</span>
                <strong>$30.00</strong>
            </div>

            <div class="pedido">
                <input type="number" name="yogurt" min="0" value="<?= $cantidades["yogurt"] ?>">
                <span>Yogurt</span>
                <strong>$15.00</strong>
            </div>

            <div class="pedido">
                <input type="number" name="agua" min="0" value="<?= $cantidades["agua"] ?>">
                <span>Agua</span>
                <strong>$12.00</strong>
            </div>
        </section>

        <!-- Pago -->
        <section class="card pago">
            <h2>Pago</h2>

            <div class="pago-grid">
                <label>
                    Subtotal
                    <input type="text" readonly value="<?= money($subtotal) ?>">
                </label>

                <label>
                    IVA 16%
                    <input type="text" readonly value="<?= money($iva) ?>">
                </label>

                <label>
                    Total a pagar
                    <input type="text" readonly value="<?= money($total) ?>">
                </label>

                <label>
                    Pago
                    <input type="number" step="0.01" min="0" name="pago" value="<?= ($pagoCliente>0) ? money($pagoCliente) : "" ?>">
                </label>

                <label>
                    Cambio
                    <input type="text" readonly value="<?= money($cambio) ?>">
                </label>
            </div>

            <button type="submit">Pagar</button>

            <?php if ($mensaje): ?>
                <div class="msg <?= $tipo ?>"><?= htmlspecialchars($mensaje) ?></div>
            <?php endif; ?>
        </section>
    </form>
</div>

</body>
</html>
