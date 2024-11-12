<?php
require 'libreria/dompdf/autoload.inc.php';
use Dompdf\Dompdf;
?>
<?php

function obtenerProductosEnCarrito()
{
    $bd = obtenerConexion();
    iniciarSesionSiNoEstaIniciada();
    $sentencia = $bd->prepare("SELECT productos.id, productos.nombre, productos.descripcion ,carrito_usuarios.Cantidad,productos.precio
     FROM productos
     INNER JOIN carrito_usuarios
     ON productos.id = carrito_usuarios.id_producto
     WHERE carrito_usuarios.id_sesion = ?");
    $idSesion = session_id();
    $sentencia->execute([$idSesion]);
    return $sentencia->fetchAll();
}

function quitarProductoDelCarrito($idProducto)
{
    $bd = obtenerConexion();
    iniciarSesionSiNoEstaIniciada();
    $idSesion = session_id();
    $sentencia = $bd->prepare("DELETE FROM carrito_usuarios WHERE id_sesion = ? AND id_producto = ?");
    return $sentencia->execute([$idSesion, $idProducto]);
}

function obtenerProductos()
{
    $bd = obtenerConexion();
    $sentencia = $bd->query("SELECT id, nombre,stock,descripcion, precio FROM productos");
    return $sentencia->fetchAll();
}
function productoYaEstaEnCarrito($idProducto)
{
    $ids = obtenerIdsDeProductosEnCarrito();
    foreach ($ids as $id) {
        if ($id == $idProducto) return true;
    }
    return false;
}

function obtenerIdsDeProductosEnCarrito()
{
    $bd = obtenerConexion();
    iniciarSesionSiNoEstaIniciada();
    $sentencia = $bd->prepare("SELECT id_producto FROM carrito_usuarios WHERE id_sesion = ?");
    $idSesion = session_id();
    $sentencia->execute([$idSesion]);
    return $sentencia->fetchAll(PDO::FETCH_COLUMN);
}



function agregarProductoAlCarrito($idProducto, $cantidadSolicitada)
{
    $bd = obtenerConexion(); 
    iniciarSesionSiNoEstaIniciada();
    $idSesion = session_id();
    $sentencia = $bd->prepare("INSERT INTO carrito_usuarios(id_sesion, id_producto, Cantidad) VALUES (?, ?, ?)");
    return $sentencia->execute([$idSesion, $idProducto, $cantidadSolicitada]);
}


function iniciarSesionSiNoEstaIniciada()
{
    if (session_status() !== PHP_SESSION_ACTIVE) {
        session_start();
    }
}

function eliminarProducto($id)
{
    $bd = obtenerConexion();
    $sentencia = $bd->prepare("DELETE FROM productos WHERE id = ?");
    return $sentencia->execute([$id]);
}

function guardarProducto($nombre, $cantidad ,$precio, $descripcion)
{
    $bd = obtenerConexion();
    $sentencia = $bd->prepare("INSERT INTO productos(nombre,stock, precio, descripcion) VALUES(?,?, ?, ?)");
    return $sentencia->execute([$nombre,$cantidad, $precio, $descripcion]);
}

function obtenerVariableDelEntorno($key)
{
    if (defined("_ENV_CACHE")) {
        $vars = _ENV_CACHE;
    } else {
        $file = "env.php";
        if (!file_exists($file)) {
            throw new Exception("El archivo de las variables de entorno ($file) no existe. Favor de crearlo");
        }
        $vars = parse_ini_file($file);
        define("_ENV_CACHE", $vars);
    }
    if (isset($vars[$key])) {
        return $vars[$key];
    } else {
        throw new Exception("La clave especificada (" . $key . ") no existe en el archivo de las variables de entorno");
    }
}
function obtenerConexion()
{
    $password = obtenerVariableDelEntorno("MYSQL_PASSWORD");
    $user = obtenerVariableDelEntorno("MYSQL_USER");
    $dbName = obtenerVariableDelEntorno("MYSQL_DATABASE_NAME");
    $database = new PDO('mysql:host=localhost;dbname=' . $dbName, $user, $password);
    $database->query("set names utf8;");
    $database->setAttribute(PDO::ATTR_EMULATE_PREPARES, FALSE);
    $database->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $database->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ);
    return $database;
}




function verificarStock($id_producto, $cantidadSolicitada) {
    $pdo = obtenerConexion();

    
    $query = $pdo->prepare('SELECT stock FROM productos WHERE id = ?');
    $query->execute([$id_producto]);
    $producto = $query->fetch(PDO::FETCH_ASSOC);

   
    if ($producto === false) {
        return false;  
    }

  
    if ($producto['stock'] >= $cantidadSolicitada) {
        return true;   
    } else {
        return false;  
    }
}

function reducirStockProducto($producto_id, $cantidad_facturada) {
    $conn = obtenerConexion();
    $sql = "UPDATE productos SET stock = stock - :cantidad_facturada WHERE id = :producto_id";
    
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(":cantidad_facturada", $cantidad_facturada, PDO::PARAM_INT);
    $stmt->bindParam(":producto_id", $producto_id, PDO::PARAM_INT);
    $stmt->execute();
}



function insertarFactura($nombre_cliente, $telefono, $fecha, $total) {
    $conn = obtenerConexion();
    $sql = "INSERT INTO factura (nombre_cliente, telefono, fecha, total) VALUES (:nombre_cliente, :telefono, :fecha, :total)";
    
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(":nombre_cliente", $nombre_cliente);
    $stmt->bindParam(":telefono", $telefono);
    $stmt->bindParam(":fecha", $fecha);
    $stmt->bindParam(":total", $total);
    $stmt->execute();

    return $conn->lastInsertId(); 
}


function insertarDetalleFactura($factura_id, $producto_id, $cantidad, $subtotal) {
    $conn = obtenerConexion();
    $sql = "INSERT INTO detalle_factura (factura_id, producto_id, cantidad, subtotal) VALUES (:factura_id, :producto_id, :cantidad, :subtotal)";
    
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(":factura_id", $factura_id);
    $stmt->bindParam(":producto_id", $producto_id);
    $stmt->bindParam(":cantidad", $cantidad);
    $stmt->bindParam(":subtotal", $subtotal);
    $stmt->execute();
}




function generarPDF($nombre_cliente, $direccion, $telefono, $fecha, $productos, $totalGeneral) {
    $dompdf = new Dompdf();

    
    $html = '
    <html lang="es">
    <head>
        <meta charset="UTF-8">
        <title>Factura</title>
        <style>
            body { font-family: Arial, sans-serif; }
            .header { text-align: center; margin-bottom: 20px; }
            .details { margin: 20px; }
            .table { width: 100%; border-collapse: collapse; margin-top: 20px; }
            th, td { border: 1px solid black; padding: 10px; text-align: left; }
            th { background-color: #f2f2f2; }
        </style>
    </head>
    <body>
        <div class="header">
            <h1>Factura</h1>
            <p><strong>Nombre del Cliente:</strong> ' . htmlspecialchars($nombre_cliente) . '</p>
            <p><strong>Dirección:</strong> ' . htmlspecialchars($direccion) . '</p>
            <p><strong>Teléfono:</strong> ' . htmlspecialchars($telefono) . '</p>
            <p><strong>Fecha:</strong> ' . htmlspecialchars($fecha) . '</p>
        </div>

        <h2>Detalles de Productos</h2>
        <table class="table">
            <thead>
                <tr>
                    <th>Nombre</th>
                    <th>Descripción</th>
                    <th>Cantidad</th>
                    <th>Precio</th>
                    <th>Subtotal</th>
                </tr>
            </thead>
            <tbody>';

    foreach ($productos as $producto) {
        $subtotal = $producto->Cantidad * $producto->precio;
        $html .= '
        <tr>
            <td>' . htmlspecialchars($producto->nombre) . '</td>
            <td>' . htmlspecialchars($producto->descripcion) . '</td>
            <td>' . htmlspecialchars($producto->Cantidad) . '</td>
            <td>Q' . number_format($producto->precio, 2) . '</td>
            <td>Q' . number_format($subtotal, 2) . '</td>
        </tr>';
    }

    $html .= '
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="4" style="text-align: right;"><strong>Total</strong></td>
                    <td>Q' . number_format($totalGeneral, 2) . '</td>
                </tr>
            </tfoot>
        </table>
    </body>
    </html>';

    // Cargar el contenido HTML en DOMPDF
    $dompdf->loadHtml($html);

    // Configurar el tamaño y orientación de la página
    $dompdf->setPaper('A4', 'portrait');

    // Renderizar el HTML como PDF
    $dompdf->render();

    // Descargar el PDF generado en el navegador
    $dompdf->stream("factura.pdf", ["Attachment" => true]);
}