<?php
?>
<?php include_once "encabezado.php" ?>
<div class="columns is-centered mt-5">
    <div class="column is-half">
        <h2 class="title has-text-centered is-size-2">Agregar Nuevo Producto</h2>
        <form action="guardar_producto.php" method="post">
            <!-- Campo de Nombre -->
            <div class="field">
                <label class="label" for="nombre">Nombre</label>
                <div class="control">
                    <input required id="nombre" class="input" type="text" placeholder="Nombre" name="nombre">
                </div>
            </div>
            <div class="field">
                <label class="label" for="cantidad">Cantidad</label>
                <div class="control">
                    <input required id="cantidad" class="input" type="text" placeholder="Cantidad" name="cantidad">
                </div>
            </div>
            <!-- Campo de Descripción -->
            <div class="field">
                <label class="label" for="descripcion">Descripción</label>
                <div class="control">
                    <textarea name="descripcion" class="textarea" id="descripcion" cols="30" rows="5" placeholder="Descripción" required></textarea>
                </div>
            </div>

            <!-- Campo de Precio -->
            <div class="field">
                <label class="label" for="precio">Precio</label>
                <div class="control">
                    <input required id="precio" name="precio" class="input" type="number" placeholder="Precio">
                </div>
            </div>
            <!-- Botones -->
            <div class="field is-grouped is-grouped-centered">
                <div class="control">
                    <button class="button is-success">Guardar</button>
                </div>
                <div class="control">
                    <a href="productos.php" class="button is-warning">Volver</a>
                </div>
            </div>
        </form>
    </div>
</div>
    </div>
</div>
<?php include_once "pie.php" ?>