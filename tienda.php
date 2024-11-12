<?php
?>
<?php include_once "encabezado.php" ?>
<?php
include_once "funciones.php";
$productos = obtenerProductos();
?>
<div class="columns is-centered mt-5">
    <div class="column is-three-quarters">
        <h2 class="title has-text-centered is-size-2">Productos disponibles en tienda</h2>
    </div>
</div>

<?php foreach ($productos as $producto) { ?>
    <div class="columns is-centered mb-4">
        <div class="column is-full">
            <div class="card">
                <header class="card-header">
                    <p class="card-header-title is-size-4">
                        <?php echo $producto->nombre ?>
                    </p>
                </header>
                <div class="card-content">
                    <div class="content">
                        <?php echo $producto->descripcion ?>
                    </div>
                    <h1 class="is-size-3">Q<?php echo number_format($producto->precio, 2) ?></h1>
                    <?php if (productoYaEstaEnCarrito($producto->id)) { ?>
                        <form action="eliminar_del_carrito.php" method="post">
                            <input type="hidden" name="id_producto" value="<?php echo $producto->id ?>">
                            <span class="button is-success">
                                <i class="fa fa-check"></i>&nbsp;En el carrito
                            </span>
                            <button class="button is-danger">
                                <i class="fa fa-trash-o"></i>&nbsp;Eliminar del carrito
                            </button>
                        </form>
                    <?php } else { ?>
                        <form action="agregar_al_carrito.php" method="post">
                        <input type="hidden" name="id_producto" value="<?php echo $producto->id ?>">
    
    
                        <div class="field">
                        <label class="label" for="cantidad">Cantidad</label>
                        <div class="control">
                        <input class="input" type="number" name="cantidad" id="cantidad" value="1" min="1" required>
                        </div>
                        </div>

                        <div class="control">
                        <button class="button is-primary">
                        <i class="fa fa-cart-plus"></i>&nbsp;Ingresar al carrito
        </button>
    </div>
</form>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>
<?php } ?>
<?php include_once "pie.php" ?>