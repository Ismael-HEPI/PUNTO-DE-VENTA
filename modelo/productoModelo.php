<?php

require_once "principalModelo.php";

class productoModelo extends principalModelo {

# Función para seleccionar los datos de un producto
protected static function seleccionar_producto_modelo($tipo, $id) {
	if ($tipo == "unico") {
		$sql = principalModelo::conectar()->prepare("SELECT * FROM productos WHERE producto_id = :id");
		$sql->bindParam(":id", $id);
	} elseif ($tipo == "contar") {
		$sql = principalModelo::conectar()->prepare("SELECT producto_id FROM productos WHERE producto_id != '0'");
	}
	$sql->execute();
	return $sql;
}

protected static function nuevo_producto_modelo($datos) {
	$sql = principalModelo::conectar()->prepare("INSERT INTO productos(producto_categoria, producto_codigo, producto_nombre, producto_descripcion, producto_stock, producto_tipo_unidad, producto_precio_compra, producto_precio_venta, producto_proveedor, producto_estado, producto_imagen) VALUES(:categoria, :codigo, :nombre, :descripcion, :stock, :tipo_unidad, :precio_compra, :precio_venta, :proveedor, :estado, :imagen)");
	$sql->bindParam(":categoria", $datos['categoria']);
	$sql->bindParam(":codigo", $datos['codigo']);
	$sql->bindParam(":nombre", $datos['nombre']);
	$sql->bindParam(":descripcion", $datos['descripcion']);
	$sql->bindParam(":stock", $datos['stock']);
	$sql->bindParam(":tipo_unidad", $datos['tipo_unidad']);
	$sql->bindParam(":precio_compra", $datos['precio_compra']);
	$sql->bindParam(":precio_venta", $datos['precio_venta']);
	$sql->bindParam(":proveedor", $datos['proveedor']);
	$sql->bindParam(":estado", $datos['estado']);
	$sql->bindParam(":imagen", $datos['imagen']);
	$sql->execute();
	return $sql;
}

protected static function actualizar_producto_modelo($datos) {
    $sql = principalModelo::conectar()->prepare("UPDATE productos SET producto_categoria = :categoria, producto_codigo = :codigo, producto_nombre = :nombre, producto_descripcion = :descripcion, producto_stock = :stock, producto_tipo_unidad = :tipo_unidad, producto_precio_compra = :precio_compra, producto_precio_venta = :precio_venta, producto_proveedor = :proveedor, producto_estado = :estado WHERE producto_id = :id");
    $sql->bindParam(":categoria", $datos['categoria']);
    $sql->bindParam(":codigo", $datos['codigo']);
    $sql->bindParam(":nombre", $datos['nombre']);
    $sql->bindParam(":descripcion", $datos['descripcion']);
    $sql->bindParam(":stock", $datos['stock']);
    $sql->bindParam(":tipo_unidad", $datos['tipo_unidad']);
    $sql->bindParam(":precio_compra", $datos['precio_compra']);
    $sql->bindParam(":precio_venta", $datos['precio_venta']);
    $sql->bindParam(":proveedor", $datos['proveedor']);
    $sql->bindParam(":estado", $datos['estado']);
    $sql->bindParam(":id", $datos['id']);
    $sql->execute();
    return $sql;
}


protected static function eliminar_producto_modelo($id) {
	$sql = principalModelo::conectar()->prepare("DELETE FROM productos WHERE producto_id = :id AND producto_id != 0");
	$sql->bindParam(":id", $id);
	$sql->execute();
	return $sql;
}
}
