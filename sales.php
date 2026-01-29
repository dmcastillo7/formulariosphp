<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Formulario de Ventas</title>
    <link href="public/css/sale.css" rel="stylesheet">
</head>
<body>

<div class="container">
    <h1>Sistema de Ventas</h1>

    <form class="ventas-form">

        <!-- Paquetes -->
        <section class="card">
            <h2>Paquetes</h2>
            <label><input type="radio" name="paquete"> Hamburguesa, Papas y Refresco</label>
            <label><input type="radio" name="paquete"> Pizza, Nuggets y Refresco</label>
            <label><input type="radio" name="paquete"> Ensalada, Yogurt y Agua</label>
            <label><input type="radio" name="paquete"> Otras opciones</label>
        </section>

        <!-- Pedidos -->
        <section class="card">
            <h2>Pedidos</h2>

            <div class="pedido">
                <input type="number" min="0">
                <span>Hamburguesa</span>
                <strong>$35.00</strong>
            </div>

            <div class="pedido">
                <input type="number" min="0">
                <span>Papas</span>
                <strong>$15.00</strong>
            </div>

            <div class="pedido">
                <input type="number" min="0">
                <span>Refresco</span>
                <strong>$12.00</strong>
            </div>

            <div class="pedido">
                <input type="number" min="0">
                <span>Pizza</span>
                <strong>$70.00</strong>
            </div>

            <div class="pedido">
                <input type="number" min="0">
                <span>Nuggets</span>
                <strong>$25.00</strong>
            </div>

            <div class="pedido">
                <input type="number" min="0">
                <span>Ensalada</span>
                <strong>$30.00</strong>
            </div>

            <div class="pedido">
                <input type="number" min="0">
                <span>Yogurt</span>
                <strong>$15.00</strong>
            </div>

            <div class="pedido">
                <input type="number" min="0">
                <span>Agua</span>
                <strong>$12.00</strong>
            </div>
        </section>

        <!-- Pago -->
        <section class="card pago">
            <h2>Pago</h2>

            <div class="pago-grid">
                <label>Subtotal</label>
                <input type="text" disabled>

                <label>IVA 16%</label>
                <input type="text" disabled>

                <label>Total a pagar</label>
                <input type="text" disabled>

                <label>Pago</label>
                <input type="number">

                <label>Cambio</label>
                <input type="text" disabled>
            </div>

            <button type="submit">Pagar</button>
        </section>
        
        <!-- PHP -->
        <?php

        ?>

    </form>
</div>

</body>
</html>
