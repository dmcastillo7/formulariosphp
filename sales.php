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

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    if ($paquete === "combo1") {
        $cantidades["hamburguesa"] = 1; $cantidades["papas"] = 1; $cantidades["refresco"] = 1;
    } elseif ($paquete === "combo2") {
        $cantidades["pizza"] = 1; $cantidades["nuggets"] = 1; $cantidades["refresco"] = 1;
    } elseif ($paquete === "combo3") {
        $cantidades["ensalada"] = 1; $cantidades["yogurt"] = 1; $cantidades["agua"] = 1;
    }

    foreach ($cantidades as $producto => $cant) {
        if ($cant < 0) $cant = 0;
        $subtotal += $cant * $precios[$producto];
    }

    $iva = $subtotal * $IVA_PORC;
    $total = $subtotal + $iva;

    if ($pagoCliente > 0) {
        if ($pagoCliente < $total) {
            $mensaje = "Pago insuficiente. Debe completar el total a pagar.";
            $tipo = "error";
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
    <link href="public/css/sale.css" rel="stylesheet">
</head>
<body>

<div class="container">
    <div class="nav-top">
        <a href="login.php" class="btn-back">Volver</a>
    </div>

    <h1>Sistema de Ventas</h1>

    <form class="ventas-form" id="formVentas" method="post" action="sales.php">

        <section class="card">
            <h2>Paquetes</h2>
            <label><input type="radio" name="paquete" value="combo1" <?= $paquete=='combo1'?'checked':'' ?>> Hamburguesa, Papas y Refresco</label>
            <label><input type="radio" name="paquete" value="combo2" <?= $paquete=='combo2'?'checked':'' ?>> Pizza, Nuggets y Refresco</label>
            <label><input type="radio" name="paquete" value="combo3" <?= $paquete=='combo3'?'checked':'' ?>> Ensalada, Yogurt y Agua</label>
            <label><input type="radio" name="paquete" value="otras" <?= $paquete=='otras'?'checked':'' ?>> Otras opciones</label>
        </section>

        <section class="card">
            <h2>Pedidos</h2>
            <?php foreach ($precios as $key => $precio): ?>
            <div class="pedido">
                <input type="number" min="0" name="<?= $key ?>" value="<?= $cantidades[$key] ?>" data-prod="<?= $key ?>">
                <span><?= ucfirst($key) ?></span>
                <strong>$<?= money($precio) ?></strong>
            </div>
            <?php endforeach; ?>
        </section>

        <section class="card pago">
            <h2>Resumen de Venta</h2>
            <div class="pago-grid">
                <label>Subtotal <input type="text" readonly value="<?= money($subtotal) ?>"></label>
                <label>IVA 16% <input type="text" readonly value="<?= money($iva) ?>"></label>
                <label>Total a pagar <input type="text" readonly value="<?= money($total) ?>"></label>
                <label>Pago <input type="number" step="0.01" name="pago" value="<?= $pagoCliente > 0 ? money($pagoCliente) : '' ?>"></label>
                <label>Cambio <input type="text" readonly value="<?= money($cambio) ?>"></label>
            </div>
            
            <?php if ($mensaje): ?>
                <div class="msg <?= $tipo ?>"><?= htmlspecialchars($mensaje) ?></div>
            <?php endif; ?>
        </section>

        <div class="acciones-pago">
            <button type="submit">Pagar</button> 
            <button type="button" class="btn-print" onclick="window.print()">
                Imprimir Ticket
            </button>
        </div>
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const radios = document.querySelectorAll('input[name="paquete"]');
    const inputsNumber = document.querySelectorAll('input[type="number"]:not([name="pago"])');

    const combos = {
        'combo1': ['hamburguesa', 'papas', 'refresco'],
        'combo2': ['pizza', 'nuggets', 'refresco'],
        'combo3': ['ensalada', 'yogurt', 'agua'],
        'otras': []
    };

    radios.forEach(radio => {
        radio.addEventListener('change', function() {
            const seleccion = this.value;
            const itemsIncluidos = combos[seleccion];
            inputsNumber.forEach(input => input.value = 0);
            itemsIncluidos.forEach(prod => {
                const target = document.querySelector(`input[name="${prod}"]`);
                if (target) target.value = 1;
            });
        });
    });
});
</script>

</body>
</html>