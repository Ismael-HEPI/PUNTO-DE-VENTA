<?php

if ($accion) {
	require_once "../modelo/proveedorModelo.php";
} else {
	require_once "./modelo/proveedorModelo.php";
}

class proveedorControlador extends proveedorModelo {

	# Función para listar los usuarios de la DB
	public function listar_proveedores_controlador($pagina, $registros, $url, $busqueda) {
		$pagina = principalModelo::limpiar_cadena($pagina);
		$registros  = principalModelo::limpiar_cadena($registros);
		$url = principalModelo::limpiar_cadena($url);
		$busqueda = principalModelo::limpiar_cadena($busqueda);
		$url = urlServidor.$url."/";
		$tabla = "";

		$pagina = (isset($pagina) && $pagina > 0) ? (int) $pagina : 1 ;
		$inicio = ($pagina > 0) ? (($pagina * $registros) - $registros) : 0 ;

		if (isset($busqueda) && $busqueda != "") {
			$consulta = "SELECT SQL_CALC_FOUND_ROWS * FROM proveedores WHERE (proveedor_nombre LIKE '%$busqueda%' OR proveedor_descripcion LIKE '%$busqueda%' ) ORDER BY proveedor_nombre ASC LIMIT $inicio, $registros";
		} else {
			$consulta = "SELECT SQL_CALC_FOUND_ROWS * FROM proveedores ORDER BY proveedor_nombre ASC LIMIT $inicio, $registros";
		}
		
		$conexion = principalModelo::conectar();
		$datos = $conexion->query($consulta);
		$datos = $datos->fetchAll();
		$total = $conexion->query("SELECT FOUND_ROWS()");
		$total = (int) $total->fetchColumn();
		$npaginas = ceil($total / $registros);

		$tabla.= '
			<div class="table-responsive">
				<table class="table table-bordered">
					<thead class="fs-8">
						<tr class="text-center">
							<th scope="col">#</th>
							<th scope="col">Nombre</th>
							<th scope="col">Descripción</th>
							<th scope="col">Opciones</th>
						</tr>
					</thead>
					<tbody class="text-center fs-7">
		';
		if ($total >= 1 && $pagina <= $npaginas) {
			$contador = $inicio + 1;
			foreach ($datos as $dato) {
				$tabla.= '
						<tr>
							<td>'.$contador.'</td>
							<td>'.$dato['proveedor_nombre'].'</td>
							<td>'.$dato['proveedor_descripcion'].'</td>
							<td class="d-flex justify-content-center">
								<a href="'.urlServidor.'proveedor-editar/'.principalModelo::encriptar($dato['proveedor_id']).'/" class="btn btn-sm btn-warning mx-1"><i class="fas fa-pen"></i></a>
								<form action="'.urlServidor.'accion/proveedorAccion.php" method="POST" class="Formulario" data-form="eliminar" autocomplete="off" >
									<input type="hidden" name="proveedor_id_eliminar" value="'.principalModelo::encriptar($dato['proveedor_id']).'">
									<button type="submit" class="btn btn-sm btn-danger"><i class="fas fa-trash"></i></button>
								</form>
							</td>
						</tr>
				';
				$contador++;
			}
		} else {
			if ($total >= 1) {
				$tabla.= '
						<tr>
							<td colspan="6">
								<a href="'.$url.'" class="btn btn-sm btn-primary">Recargar la lista de los proveedores</a>
							</td>
						</tr>
				';
			} else {
				$tabla.= '
						<tr>
							<td colspan="6">
								<div class="alert alert-danger mb-0">
									No hay registros de proveedores en el sistema.
								</div>
							</td>
						</tr>
				';
			}
		}
		$tabla.= '
					</tbody>
				</table>
			</div>
		';
		if ($total >= 1 && $pagina <= $npaginas) {
			$tabla.= principalModelo::paginador_tablas($pagina, $npaginas, $url, 7);
		}

		return $tabla;

	}

	# Función para seleccionar un usuario
	public function seleccionar_proveedor_controlador($tipo, $id) {
		$tipo = principalModelo::limpiar_cadena($tipo);
		$id = principalModelo::desencriptar($id);
		$id = principalModelo::limpiar_cadena($id);
		return proveedorModelo::seleccionar_proveedor_modelo($tipo, $id);
	}

	# función para registrar un usuario
	public function nuevo_proveedor_controlador() {
		# Recibiendo datos del formulario
		$nombre = principalModelo::limpiar_cadena($_POST['proveedor_nombre_nuevo']);
		$descripcion = principalModelo::limpiar_cadena($_POST['proveedor_descripcion_nuevo']);
		
		# Comprobando campos vacios
		if ($nombre == "" || $descripcion == "" ) {
			$alerta = [
				"Alerta"=>"simple",
				"Titulo"=>"ocurrio un error",
				"Texto"=>"No has llenado todos los campos que son obligatorios.",
				"Icon"=>"error"
			];
			echo json_encode($alerta);
			exit();
		}

		if (principalModelo::verificar_datos("[a-zA-ZáéíóúÁÉÍÓÚñÑ ]{3,30}", $nombre)) {
			$alerta = [
				"Alerta"=>"simple",
				"Titulo"=>"ocurrio un error",
				"Texto"=>"El nombre no coincide con el formato solicitado.",
				"Icon"=>"error"
			];
			echo json_encode($alerta);
			exit();
		}

		$checar_nombre = principalModelo::ejecutar_consulta_simple("SELECT proveedor_nombre FROM proveedores WHERE proveedor_nombre = '$nombre'");
			if ($checar_nombre->rowCount() > 0) {
				$alerta = [
					"Alerta"=>"simple",
					"Titulo"=>"ocurrio un error",
					"Texto"=>"El nombre del proveedor ingresado ya esta registrado en el sistema.",
					"Icon"=>"error"
				];
				echo json_encode($alerta);
				exit();
			}

		if (principalModelo::verificar_datos("[a-zA-ZáéíóúÁÉÍÓÚñÑ ]{4,30}", $descripcion)) {
			$alerta = [
				"Alerta"=>"simple",
				"Titulo"=>"ocurrio un error",
				"Texto"=>"La descripcion no coincide con el formato solicitado.",
				"Icon"=>"error"
			];
			echo json_encode($alerta);
			exit();
		}

		$datos_proveedor = [
			"nombre"=>$nombre,
			"descripcion"=>$descripcion,
		];

		$nuevo_proveedor = proveedorModelo::nuevo_proveedor_modelo($datos_proveedor);

		if ($nuevo_proveedor->rowCount() == 1) {
			$alerta = [
				"Alerta"=>"limpiar",
				"Titulo"=>"usuario registrado",
				"Texto"=>"Los datos se guardaron con exito en el sistema.",
				"Icon"=>"success"
			];
		} else {
			$alerta = [
				"Alerta"=>"simple",
				"Titulo"=>"ocurrio un error",
				"Texto"=>"No se guardaron los datos en el sistema.",
				"Icon"=>"error"
			];
		}

		echo json_encode($alerta);

	}

	# Función para actualizar un usuario
	public function actualizar_proveedor_controlador() {
		# Revisando el ID si existe en la base de datos
		$id = principalModelo::desencriptar($_POST['proveedor_id_actualizar']);
		$id = principalModelo::limpiar_cadena($id);
		$checar_id =  principalModelo::ejecutar_consulta_simple("SELECT * FROM proveedores WHERE proveedor_id = '$id'");
		if ($checar_id->rowCount() <= 0) {
			$alerta = [
				"Alerta"=>"simple",
				"Titulo"=>"Ocurrio un error",
				"Texto"=>"No se ha encontrado el usuario en el sistema.",
				"Icon"=>"error"
			];
			echo json_encode($alerta);
			exit();
		} else {
			$campo = $checar_id->fetch();
		}

		# Recibiendo datos del formulario
		$nombre = principalModelo::limpiar_cadena($_POST['proveedor_nombre_actualizar']);
		$descripcion = principalModelo::limpiar_cadena($_POST['proveedor_descripcion_actualizar']);
		
		/** Comprobar campos vacios */
		if ($nombre == "" || $descripcion == "") {
			$alerta = [
				"Alerta"=>"simple",
				"Titulo"=>"Ocurrio un error",
				"Texto"=>"No has llenado todos los campos que son requeridos.",
				"Icon"=>"error"
			];
			echo json_encode($alerta);
			exit();
		}

		# Comprobando la integridad de los datos
		if (principalModelo::verificar_datos("[a-zA-ZáéíóúÁÉÍÓÚñÑ ]{3,30}", $nombre)) {
			$alerta = [
				"Alerta"=>"simple",
				"Titulo"=>"ocurrio un error",
				"Texto"=>"El nombre no coincide con el formato solicitado.",
				"Icon"=>"error"
			];
			echo json_encode($alerta);
			exit();
		}

		if (principalModelo::verificar_datos("[a-zA-ZáéíóúÁÉÍÓÚñÑ ]{4,30}", $descripcion)) {
			$alerta = [
				"Alerta"=>"simple",
				"Titulo"=>"ocurrio un error",
				"Texto"=>"El descripcion no coincide con el formato solicitado.",
				"Icon"=>"error"
			];
			echo json_encode($alerta);
			exit();
		}

		$datos_proveedor= [
			"nombre"=>$nombre,
			"descripcion"=>$descripcion,
			"id"=>$id
		];
		
		if (proveedorModelo::listar_proveedor_modelo($datos_proveedor)) {
			$alerta = [
				"Alerta"=>"recargar",
				"Titulo"=>"Proveedor Actualizado",
				"Texto"=>"Los datos del proveedor han sido actualizados.",
				"Icon"=>"success"
			];
		} else {
			$alerta = [
				"Alerta"=>"simple",
				"Titulo"=>"Ocurrio un error",
				"Texto"=>"No se ha podido actualizar los datos, intente de nuevo.",
				"Icon"=>"error"
			];
		}
		echo json_encode($alerta);

	}

	# Función para eliminar un usuario
	public function eliminar_proveedor_controlador($id) {
		$id = principalModelo::desencriptar($_POST['proveedor_id_eliminar']);
		$id = principalModelo::limpiar_cadena($id);

		$checar_proveedor = principalModelo::ejecutar_consulta_simple("SELECT proveedor_id FROM proveedores WHERE proveedor_id = '$id'");
		if ($checar_proveedor->rowCount() <= 0){
			$alerta = [
				"Alerta"=>"simple",
				"Titulo"=>"¡Ocurrio un error!",
				"Texto"=>"El proveedor que intenta eliminar, no existe en el sistema.",
				"Icon"=>"error"
			];
			echo json_encode($alerta);
			exit();
		}

		session_start(["name" => "sistema"]);
		if ($_SESSION['privilegio_sistema'] != 1) {
			$alerta = [
				"Alerta"=>"simple",
				"Titulo"=>"¡Ocurrio un error!",
				"Texto"=>"No tienes permisos para eliminar a los proveedores.",
				"Icon"=>"error"
			];
			echo json_encode($alerta);
			exit();
		}

		$eliminar_proveedor = proveedorModelo::eliminar_proveedor_modelo($id);

		if ($eliminar_proveedor->rowCount() == 1) {
			$alerta = [
				"Alerta"=>"recargar",
				"Titulo"=>"¡Usuario eliminado!",
				"Texto"=>"El proveedor fue eliminado con éxito del sistema.",
				"Icon"=>"success"
			];
		} else {
			$alerta = [
				"Alerta"=>"simple",
				"Titulo"=>"¡Ocurrio un error!",
				"Texto"=>"No se pudo eliminar el proveedor, intente de nuevo.",
				"Icon"=>"error"
			];
		}
		echo json_encode($alerta);
	}

} 