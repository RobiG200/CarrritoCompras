<?php
 ?>
<?php
include_once "funciones.php"; 


if (!isset($_POST["id_producto"]) || !isset($_POST["cantidad"])) {
    exit("No se recibió el id del producto o la cantidad.");
}


$id_producto = $_POST["id_producto"];
$cantidadSolicitada = (int)$_POST["cantidad"];


if (verificarStock($id_producto, $cantidadSolicitada)) {
   
    agregarProductoAlCarrito($id_producto, $cantidadSolicitada);
    header("Location: tienda.php"); 
} else {
    
    echo "<script>alert('No hay suficiente stock para el producto solicitado'); window.location.href='tienda.php';</script>";
}
?>