<?php

$accion = true;
require_once "../config/sistema.php";

if (isset($_POST['proveedor_nombre_nuevo']) || isset($_POST['proveedor_id_actualizar']) || isset($_POST['proveedor_id_eliminar'])) {

	# Instancia al controlador usuario
	require_once "../controlador/proveedorControlador.php";
	$proveedor_controlador = new proveedorControlador();

	# Registrar un usuario
	if (isset($_POST['proveedor_nombre_nuevo']) && isset($_POST['proveedor_descripcion_nuevo'])) {
		echo $proveedor_controlador->nuevo_proveedor_controlador();
	}

	# Actualizar un usuario 
	if (isset($_POST['proveedor_id_actualizar'])) {
		echo $proveedor_controlador->actualizar_proveedor_controlador();
	}

	# Eliminar un usuario
	if (isset($_POST['proveedor_id_eliminar'])) {
		echo $proveedor_controlador->eliminar_proveedor_controlador($_POST['proveedor_id_eliminar']);
	}

} else {
	session_start(["name" => "sistema"]);
	session_unset();
	session_destroy();
	header("Location: ".urlServidor."login/");
	exit();
}