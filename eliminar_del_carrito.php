<?php
 ?>
<?php
var_dump($productos);

include_once "funciones.php";
if (!isset($_POST["id_producto"])) {
    exit("No hay id_producto");
}
quitarProductoDelCarrito($_POST["id_producto"]);

if (isset($_POST["redireccionar_carrito"])) {
    header("Location: ver_carrito.php");
} else {
    header("Location: tienda.php");
}