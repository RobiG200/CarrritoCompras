<?php include_once "encabezado.php" ?>
<?php
 ?>
<?php

include_once "funciones.php";
$productos = obtenerProductosEnCarrito();

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://unpkg.com/bulma@0.9.1/css/bulma.min.css">
    <title>Datos de Factura</title>
</head>
<body>
    <section class="section">
        <div class="container">
            <h1 class="title">Datos de Factura</h1>
            <form action="procesar_factura.php" method="post">
                <div class="columns">
                    <div class="column is-half">
                        <div class="field">
                            <label class="label">Nombre del Cliente</label>
                            <div class="control">
                                <input class="input" type="text" name="nombre_cliente" placeholder="Nombre del cliente" required>
                            </div>
                        </div>
                        <div class="field">
                            <label class="label">Dirección</label>
                            <div class="control">
                                <input class="input" type="text" name="direccion" placeholder="Dirección" required>
                            </div>
                        </div>
                        <div class="field">
                            <label class="label">Teléfono</label>
                            <div class="control">
                                <input class="input" type="tel" name="telefono" placeholder="Teléfono" required>
                            </div>
                        </div>
                    </div>
                    <div class="column is-half">
                        <div class="field">
                            <label class="label">Fecha</label>
                            <div class="control">
                                <input class="input" type="date" name="fecha" required>
                            </div>
                        </div>
                    </div>
                </div>

                <table class="table">
                <thead>
                    <tr>
                        <th>Nombre</th>
                        <th>Descripción</th>
                        <th>Cantidad</th>
                        <th>Precio</th>
                        <th>SubTotal</th>
                    </tr>
                </thead>
                <tbody>
                <?php
                $totalGeneral = 0; // Total general del carrito
                foreach ($productos as $producto) {
                 // Calcular el total para el producto actual
                $totalProducto = $producto->Cantidad * $producto->precio;
                $totalGeneral += $totalProducto; // Sumar al total general
                ?>
                <tr>
                <td><?php echo $producto->nombre ?></td>
                <td><?php echo $producto->descripcion ?></td>
                <td><?php echo $producto->Cantidad ?></td>
                <td>Q<?php echo number_format($producto->precio, 2) ?></td>
                <td>Q<?php echo number_format($totalProducto, 2) ?></td> <!-- Mostrar total por producto -->
                <td>
                
            </td>
        </tr>
    <?php } ?>
</tbody>

                <tfoot>
                    <tr>
                        <td colspan="2" class="is-size-4 has-text-right"><strong>Total</strong></td>
                        <td colspan="2" class="is-size-4">
                            Q<?php echo number_format($totalGeneral, 2) ?>
                        </td>
                    </tr>
                </tfoot>
            </table>


                



                <div class="field">
                    <div class="control">
                        <button class="button is-primary">Generar Factura</button>
                    </div>
                </div>
                 <div class="field">
        <div class="control">
            <a href="ver_carrito.php" class="button is-light">Regresar al Carrito</a>
        </div>
    </div>
            </form>
        </div>
    </section>
</body>
</html>
