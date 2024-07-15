<?php

if ($accion) {
	require_once "../modelo/clienteModelo.php";
} else {
	require_once "./modelo/clienteModelo.php";
}

class clienteControlador extends clienteModelo {

	# Función para listar los usuarios de la DB
	public function listar_clientes_controlador($pagina, $registros, $url, $busqueda) {
		$pagina = principalModelo::limpiar_cadena($pagina);
		$registros  = principalModelo::limpiar_cadena($registros);
		$url = principalModelo::limpiar_cadena($url);
		$busqueda = principalModelo::limpiar_cadena($busqueda);
		$url = urlServidor.$url."/";
		$tabla = "";

		$pagina = (isset($pagina) && $pagina > 0) ? (int) $pagina : 1 ;
		$inicio = ($pagina > 0) ? (($pagina * $registros) - $registros) : 0 ;

		if (isset($busqueda) && $busqueda != "") {
			$consulta = "SELECT SQL_CALC_FOUND_ROWS * FROM clientes WHERE (cliente_nombre LIKE '%$busqueda%' OR cliente_apellido LIKE '%$busqueda%') ORDER BY cliente_nombre ASC LIMIT $inicio, $registros";
		} else {
			$consulta = "SELECT SQL_CALC_FOUND_ROWS * FROM clientes ORDER BY cliente_nombre ASC LIMIT $inicio, $registros";
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
							<th scope="col">Dirección</th>
                            <th scope="col">Telefono</th>
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
							<td>'.$dato['cliente_nombre'].' '.$dato['cliente_apellido'].'</td>
							<td>'.$dato['cliente_direccion'].'</td>
                            <td>'.$dato['cliente_telefono'].'</td>
							<td class="d-flex justify-content-center">
								<a href="'.urlServidor.'cliente-editar/'.principalModelo::encriptar($dato['cliente_id']).'/" class="btn btn-sm btn-warning mx-1"><i class="fas fa-pen"></i></a>
								<form action="'.urlServidor.'accion/clienteAccion.php" method="POST" class="Formulario" data-form="eliminar" autocomplete="off" >
									<input type="hidden" name="cliente_id_eliminar" value="'.principalModelo::encriptar($dato['cliente_id']).'">
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
								<a href="'.$url.'" class="btn btn-sm btn-primary">Recargar la lista de clientes</a>
							</td>
						</tr>
				';
			} else {
				$tabla.= '
						<tr>
							<td colspan="6">
								<div class="alert alert-danger mb-0">
									No hay registros de clientes en el sistema.
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
	public function seleccionar_cliente_controlador($tipo, $id) {
		$tipo = principalModelo::limpiar_cadena($tipo);
		$id = principalModelo::desencriptar($id);
		$id = principalModelo::limpiar_cadena($id);
		return clienteModelo::seleccionar_cliente_modelo($tipo, $id);
	}

	# función para registrar un usuario
	public function nuevo_cliente_controlador() {
		# Recibiendo datos del formulario
		$nombre = principalModelo::limpiar_cadena($_POST['cliente_nombre_nuevo']);
		$apellido = principalModelo::limpiar_cadena($_POST['cliente_apellido_nuevo']);
        $direccion = principalModelo::limpiar_cadena($_POST['cliente_direccion_nuevo']);
        $telefono = principalModelo::limpiar_cadena($_POST['cliente_telefono_nuevo']);
	
		# Comprobando la integridad de los datos
		if (principalModelo::verificar_datos("[a-zA-ZáéíóúÁÉÍÓÚñÑ ]{5,30}", $nombre)) {
			$alerta = [
				"Alerta"=>"simple",
				"Titulo"=>"ocurrio un error",
				"Texto"=>"El nombre de la categoria no coincide con el formato solicitado.",
				"Icon"=>"error"
			];
			echo json_encode($alerta);
			exit();
		}

		if (principalModelo::verificar_datos("[a-zA-ZáéíóúÁÉÍÓÚñÑ0-9 ]{5,40}", $apellido)) {
			$alerta = [
				"Alerta"=>"simple",
				"Titulo"=>"ocurrio un error",
				"Texto"=>"El apellido no coincide con el formato solicitado.",
				"Icon"=>"error"
			];
			echo json_encode($alerta);
			exit();
		}

        if (principalModelo::verificar_datos("[a-zA-ZáéíóúÁÉÍÓÚñÑ0-9 ]{5,40}", $direccion)) {
			$alerta = [
				"Alerta"=>"simple",
				"Titulo"=>"ocurrio un error",
				"Texto"=>"La dirección no coincide con el formato solicitado.",
				"Icon"=>"error"
			];
			echo json_encode($alerta);
			exit();
		}

        if (principalModelo::verificar_datos("[0-9 ]{5,40}", $telefono)) {
			$alerta = [
				"Alerta"=>"simple",
				"Titulo"=>"ocurrio un error",
				"Texto"=>"El numero de telefono no coincide con el formato solicitado.",
				"Icon"=>"error"
			];
			echo json_encode($alerta);
			exit();
		}

		$datos_cliente = [
			"nombre"=>$nombre,
			"apellido"=>$apellido,
            "direccion"=>$direccion,
            "telefono"=>$telefono,
		];

		$nuevo_cliente = clienteModelo::nuevo_cliente_modelo($datos_cliente);

		if ($nuevo_cliente->rowCount() == 1) {
			$alerta = [
				"Alerta"=>"limpiar",
				"Titulo"=>"cliente registrado",
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
	public function actualizar_cliente_controlador() {
		# Revisando el ID si existe en la base de datos
		$id = principalModelo::desencriptar($_POST['cliente_id_actualizar']);
		$id = principalModelo::limpiar_cadena($id);
		$checar_id =  principalModelo::ejecutar_consulta_simple("SELECT * FROM clientes WHERE cliente_id = '$id'");
		if ($checar_id->rowCount() <= 0) {
			$alerta = [
				"Alerta"=>"simple",
				"Titulo"=>"Ocurrio un error",
				"Texto"=>"No se ha encontrado la cliente en el sistema.",
				"Icon"=>"error"
			];
			echo json_encode($alerta);
			exit();
		}
	
		# Recibiendo datos del formulario
		$nombre = principalModelo::limpiar_cadena($_POST['cliente_nombre_actualizar']);
		$apellido = principalModelo::limpiar_cadena($_POST['cliente_apellido_actualizar']);
		$telefono = principalModelo::limpiar_cadena($_POST['cliente_telefono_actualizar']);
		$direccion = principalModelo::limpiar_cadena($_POST['cliente_direccion_actualizar']);
		
		/** Comprobar campos vacios */
		if ($nombre == "" || $apellido == "" || $telefono == "" || $direccion == "") {
			$alerta = [
				"Alerta"=>"simple",
				"Titulo"=>"Ocurrio un error",
				"Texto"=>"No has llenado todos los campos que son requeridos.",
				"Icon"=>"error"
			];
			echo json_encode($alerta);
			exit();
		}
	
		if (principalModelo::verificar_datos("[a-zA-ZáéíóúÁÉÍÓÚñÑ ]{5,30}", $nombre)) {
			$alerta = [
				"Alerta"=>"simple",
				"Titulo"=>"Ocurrio un error",
				"Texto"=>"El nombre no coincide con el formato solicitado.",
				"Icon"=>"error"
			];
			echo json_encode($alerta);
			exit();
		}
	
		if (principalModelo::verificar_datos("[a-zA-ZáéíóúÁÉÍÓÚñÑ0-9 ]{5,40}", $apellido)) {
			$alerta = [
				"Alerta"=>"simple",
				"Titulo"=>"Ocurrio un error",
				"Texto"=>"La apellido de la cliente no coincide con el formato solicitado.",
				"Icon"=>"error"
			];
			echo json_encode($alerta);
			exit();
		}
	
		// Validar el formato del teléfono y dirección según sea necesario
	
		$datos_cliente = [
			"nombre" => $nombre,
			"apellido" => $apellido,
			"telefono" => $telefono,
			"direccion" => $direccion,
			"id" => $id
		];
		
		if (clienteModelo::actualizar_cliente_modelo($datos_cliente)) {
			$alerta = [
				"Alerta" => "recargar",
				"Titulo" => "Cliente actualizado",
				"Texto" => "Los datos de la cliente han sido actualizados.",
				"Icon" => "success"
			];
		} else {
			$alerta = [
				"Alerta" => "simple",
				"Titulo" => "Ocurrio un error",
				"Texto" => "No se ha podido actualizar los datos, intente de nuevo.",
				"Icon" => "error"
			];
		}
		echo json_encode($alerta);
	}
	

	# Función para eliminar un usuario
	public function eliminar_cliente_controlador($id) {
		$id = principalModelo::desencriptar($_POST['cliente_id_eliminar']);
		$id = principalModelo::limpiar_cadena($id);

		$checar_cliente = principalModelo::ejecutar_consulta_simple("SELECT cliente_id FROM clientes WHERE cliente_id = '$id'");
		if ($checar_cliente->rowCount() <= 0){
			$alerta = [
				"Alerta"=>"simple",
				"Titulo"=>"¡Ocurrio un error!",
				"Texto"=>"La cliente que intenta eliminar, no existe en el sistema.",
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
				"Texto"=>"No tienes permisos para eliminar a las clientes.",
				"Icon"=>"error"
			];
			echo json_encode($alerta);
			exit();
		}

		$eliminar_cliente = clienteModelo::eliminar_cliente_modelo($id);

		if ($eliminar_cliente->rowCount() != 0) {
			$alerta = [
				"Alerta"=>"recargar",
				"Titulo"=>"¡Usuario eliminado!",
				"Texto"=>"La cliente fue eliminado con éxito del sistema.",
				"Icon"=>"success"
			];
		} else {
			$alerta = [
				"Alerta"=>"simple",
				"Titulo"=>"¡Ocurrio un error!",
				"Texto"=>"No se pudo eliminar la cliente, intente de nuevo.",
				"Icon"=>"error"
			];
		}
		echo json_encode($alerta);
	}

}