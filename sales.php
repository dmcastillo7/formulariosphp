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

$subtotal = 0;
$iva = 0;
$total = 0;
$cambio = 0;

$mensaje = "";
$tipo = "";

// ================= PROCESAMIENTO =================
if ($_SERVER["REQUEST_METHOD"] === "POST") {

    // Pedidos individuales (ciclo)
    foreach ($cantidades as $producto => $cant) {
        if ($cant < 0) $cant = 0;
        $subtotal += $cant * $precios[$producto];
    }

    // Paquetes (switch)
    switch ($paquete) {
        case "combo1":
            $subtotal += $precios["hamburguesa"] + $precios["papas"] + $precios["refresco"];
            break;

        case "combo2":
            $subtotal += $precios["pizza"] + $precios["nuggets"] + $precios["refresco"];
            break;

        case "combo3":
            $subtotal += $precios["ensalada"] + $precios["yogurt"] + $precios["agua"];
            break;

        case "otras":
        default:
            break;
    }

    // IVA y total
    $iva = $subtotal * $IVA_PORC;
    $total = $subtotal + $iva;

    // Pago y cambio
    if ($pagoCliente > 0) {
        if ($pagoCliente < $total) {
            $mensaje = "Pago insuficiente. Debe completar el total a pagar.";
            $tipo = "error";
            $cambio = 0;
        } else {
            $cambio = $pagoCliente - $total;
            $mensaje = "Pago realizado correctamente.";
            $tipo = "ok";
        }
    } else {
        $mensaje = "Cálculo realizado. Ingrese el pago para obtener el cambio.";
        $tipo = "ok";
    }
}

function money($v) {
    return number_format($v, 2, ".", "");
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Formulario de Ventas</title>

    <!-- ✅ CSS CORRECTO -->
    <link href="public/css/sale.css" rel="stylesheet">
</head>
<body>

<div class="container">
<div class="nav-top">
    <a href="login.php" class="btn-back"><span class="icon">⇽</span> Volver</a>
</div>

<h1>Sistema de Ventas</h1>

    <form class="ventas-form" method="post" action="sales.php">

        <!-- PAQUETES -->
        <section class="card">
            <h2>Paquetes</h2>

            <label>
                <input type="radio" name="paquete" value="combo1"> Hamburguesa, Papas y Refresco
            </label>

            <label>
                 <input type="radio" name="paquete" value="combo2"> Pizza, Nuggets y Refresco
            </label>

            <label>
                <input type="radio" name="paquete" value="combo3"> Ensalada, Yogurt y Agua
            </label>

            <label>
                <input type="radio" name="paquete" value="otras" checked> Otras opciones
            </label>
        </section>

        <!-- PEDIDOS -->
        <section class="card">
            <h2>Pedidos</h2>

            <?php
            $labels = [
                "hamburguesa" => "Hamburguesa",
                "papas" => "Papas",
                "refresco" => "Refresco",
                "pizza" => "Pizza",
                "nuggets" => "Nuggets",
                "ensalada" => "Ensalada",
                "yogurt" => "Yogurt",
                "agua" => "Agua"
            ];

            foreach ($labels as $key => $nombre):
            ?>
            <div class="pedido">
                <input type="number" min="0" name="<?= $key ?>" value="<?= $cantidades[$key] ?>">
                <span><?= $nombre ?></span>
                <strong>$<?= money($precios[$key]) ?></strong>
            </div>
            <?php endforeach; ?>
        </section>

        <!-- PAGO -->
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
                    <input type="number" step="0.01" min="0" name="pago" value="<?= ($pagoCliente>0)?money($pagoCliente):"" ?>">
                </label>

                <label>
                    Cambio
                    <input type="text" readonly value="<?= money($cambio) ?>">
                </label>
            </div>

            <button type="submit">Pagar</button>
            <button type="button" onclick="window.print()" class="btn-print">Imprimir Comprobante</button>

            <?php if ($mensaje): ?>
                <div class="msg <?= $tipo ?>"><?= htmlspecialchars($mensaje) ?></div>
            <?php endif; ?>
        </section>

    </form>
</div>

</body>
</html>
