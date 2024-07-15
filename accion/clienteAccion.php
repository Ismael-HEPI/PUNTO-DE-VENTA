<?php

$accion = true;
require_once "../config/sistema.php";

if (isset($_POST['cliente_nombre_nuevo']) || isset($_POST['cliente_id_actualizar']) || isset($_POST['cliente_id_eliminar'])) {

	# Instancia al controlador usuario
	require_once "../controlador/clienteControlador.php";
	$cliente_controlador = new clienteControlador();

	# Registrar un usuario
	if (isset($_POST['cliente_nombre_nuevo']) && isset($_POST['cliente_apellido_nuevo']) && isset($_POST['cliente_telefono_nuevo']) && isset($_POST['cliente_direccion_nuevo'])) {
    	echo $cliente_controlador->nuevo_cliente_controlador();
	}

	# Actualizar un usuario
	if (isset($_POST['cliente_id_actualizar'])) {
		echo $cliente_controlador->actualizar_cliente_controlador();
	}

	# Eliminar un usuario
	if (isset($_POST['cliente_id_eliminar'])) {
		echo $cliente_controlador->eliminar_cliente_controlador($_POST['cliente_id_eliminar']);
	}

} else {
	session_start(["name" => "sistema"]);
	session_unset();
	session_destroy();
	header("Location: ".urlServidor."login/");
	exit();
}