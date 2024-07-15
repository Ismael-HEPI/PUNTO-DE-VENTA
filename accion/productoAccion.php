<?php

$accion = true;
require_once "../config/sistema.php";

if (isset($_POST['producto_nombre_nuevo']) || isset($_POST['producto_id_actualizar']) || isset($_POST['producto_id_eliminar'])) {

	# Instancia al controlador producto
	require_once "../controlador/productoControlador.php";
	$producto_controlador = new productoControlador();

	# Registrar un producto
	if (isset($_POST['producto_categoria_nuevo']) && isset($_POST['producto_codigo_nuevo'])  && isset($_POST['producto_nombre_nuevo']) && isset($_POST['producto_descripcion_nuevo']) && isset($_POST['producto_stock_nuevo']) && isset($_POST['producto_tipo_unidad_nuevo']) && isset($_POST['producto_precio_compra_nuevo']) && isset($_POST['producto_precio_venta_nuevo']) && isset($_POST['producto_proveedor_nuevo']) && isset($_POST['producto_estado_nuevo'])) {
		echo $producto_controlador->nuevo_producto_controlador();
	}

	# Actualizar un usuario
	if (isset($_POST['producto_id_actualizar'])) {
		echo $producto_controlador->actualizar_producto_controlador();
	}

	# Eliminar un usuario
	if (isset($_POST['producto_id_eliminar'])) {
		echo $producto_controlador->eliminar_producto_controlador($_POST['producto_id_eliminar']);
	}

} else {
	session_start(["name" => "sistema"]);
	session_unset();
	session_destroy();
	header("Location: ".urlServidor."login/");
	exit();
}