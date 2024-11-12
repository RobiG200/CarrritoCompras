<?php include_once "encabezado.php"; ?>

<?php
include_once "funciones.php";
$productos = obtenerProductosEnCarrito();
?>


<?php
// Verificar si se han enviado los datos del formulario
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Recibir y limpiar los datos del formulario
    $nombre_cliente = htmlspecialchars(trim($_POST["nombre_cliente"]));
    $direccion = htmlspecialchars(trim($_POST["direccion"]));
    $telefono = htmlspecialchars(trim($_POST["telefono"]));
    $fecha = htmlspecialchars(trim($_POST["fecha"]));

    // Mostrar los datos recibidos
    ?>
    <!DOCTYPE html>
    <html lang="es">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulma@0.9.3/css/bulma.min.css">
        <title>Datos de la Factura</title>
    </head>
    <body>
        <section class="section">
            <div class="container">
                <h1 class="title">Factura Generada Exitosamente</h1>
                <div class="box">
                    <h2 class="subtitle">Factura</h2>
                    <p><strong>Nombre del Cliente:</strong> <?php echo $nombre_cliente; ?></p>
                    <p><strong>Dirección:</strong> <?php echo $direccion; ?></p>
                    <p><strong>Teléfono:</strong> <?php echo $telefono; ?></p>
                    <p><strong>Fecha:</strong> <?php echo $fecha; ?></p>
                </div>
                <div class="box">
                    <h2 class="subtitle">Detalles de la Factura</h2>
                    
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
				<?php

				$factura_id = insertarFactura($nombre_cliente, $telefono, $fecha, $totalGeneral);

				foreach ($productos as $producto) {
    			$subtotal = $producto->Cantidad * $producto->precio;
    			insertarDetalleFactura($factura_id, $producto->id, $producto->Cantidad, $subtotal);
                reducirStockProducto($producto->id, $producto->Cantidad);
                
				}
				?>

                </div>
                          </div>

                          <form>
                  <div class="field">
        		<div class="control">
            	<a href="productos.php" class="button is-light">Regresar a inicio</a>
        		</div>
    			</div>
    			 <div class="field">
        		<div class="control">
            	<a href="<?php generarPDF($nombre_cliente, $direccion, $telefono, $fecha, $productos, $totalGeneral);  ?>" class="button is-light">Generar PDF</a>

        		</div>
    			</div>
                          </form>
        </section>
    </body>
    </html>
    <?php
} else {
    // Si no se envían datos, redirigir a la página de formulario
    header("Location: factura.php");
    exit();
}