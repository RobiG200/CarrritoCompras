<?php
 ?>
<?php include_once "encabezado.php" ?>
<?php
include_once "funciones.php";
$productos = obtenerProductosEnCarrito();
if (count($productos) <= 0) {
?>
    <section class="hero is-info">
        <div class="hero-body">
            <div class="container">
                <h1 class="title">
                    Aun no se ingresa ningun dato
                </h1>
                <h2 class="subtitle">
                    Visita la tienda para agregar productos a tu carrito
                </h2>
                <a href="tienda.php" class="button is-warning">Ver tienda</a>
            </div>
        </div>
    </section>
<?php } else { ?>
    <div class="columns">
        <div class="column">
            <h2 class="is-size-2">Mi carrito de compras</h2>
            <table class="table">
                <thead>
                    <tr>
                        <th>Nombre</th>
                        <th>Descripción</th>
                        <th>Cantidad</th>
                        <th>Precio</th>
                        <th>SubTotal</th>
                        <th>Quitar</th>
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
                <form action="eliminar_del_carrito.php" method="post">
                    <input type="hidden" name="id_producto" value="<?php echo $producto->id ?>">
                    <input type="hidden" name="redireccionar_carrito">
                    <button class="button is-danger">
                        <i class="fa fa-trash-o"></i>
                    </button>
                </form>
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
            <a href="terminar_compra.php" class="button is-success is-large"><i class="fa fa-check"></i>&nbsp;Terminar compra</a>
        </div>
    </div>
<?php } ?>
<?php include_once "pie.php" ?>