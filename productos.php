<?php
 ?>
<?php include_once "encabezado.php" ?>
<?php
include_once "funciones.php";
$productos = obtenerProductos();
?>

<div class="columns is-centered mt-5">
    <div class="column is-three-quarters">
        <h2 class="title has-text-centered is-size-2">Productos Disponibles</h2>
        <div class="has-text-right mb-4">
            <a class="button is-warning" href="agregar_producto.php">Agregar&nbsp;<i class="fa fa-plus"></i></a>
        </div>
        <table class="table is-bordered is-striped is-hoverable is-fullwidth">
            <thead>
                <tr>
                    <th>Nombre Producto</th>
                    <th>Stock</th>
                    <th>Descripción</th>
                    <th>Precio Unitario</th>
                    <th>Eliminar</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($productos as $producto) { ?>
                    <tr>
                        <td><?php echo $producto->nombre ?></td>
                        <td><?php echo $producto->stock ?></td>
                        <td><?php echo $producto->descripcion ?></td>
                        <td>Q<?php echo number_format($producto->precio, 2) ?></td>
                        <td>
                            <form action="eliminar_producto.php" method="post">
                                <input type="hidden" name="id_producto" value="<?php echo $producto->id ?>">
                                <button class="button is-danger">
                                    <i class="fa fa-trash-o"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</div>
<?php include_once "pie.php" ?>