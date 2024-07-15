<?php

if ($accion) {
	require_once "../modelo/categoriaModelo.php";
} else {
	require_once "./modelo/categoriaModelo.php";
}

class categoriaControlador extends categoriaModelo {

	# Función para listar los usuarios de la DB
	public function listar_categorias_controlador($pagina, $registros, $url, $busqueda) {
		$pagina = principalModelo::limpiar_cadena($pagina);
		$registros  = principalModelo::limpiar_cadena($registros);
		$url = principalModelo::limpiar_cadena($url);
		$busqueda = principalModelo::limpiar_cadena($busqueda);
		$url = urlServidor.$url."/";
		$tabla = "";

		$pagina = (isset($pagina) && $pagina > 0) ? (int) $pagina : 1 ;
		$inicio = ($pagina > 0) ? (($pagina * $registros) - $registros) : 0 ;

		if (isset($busqueda) && $busqueda != "") {
			$consulta = "SELECT SQL_CALC_FOUND_ROWS * FROM categorias WHERE (categoria_nombre LIKE '%$busqueda%' OR categoria_ubicacion LIKE '%$busqueda%') ORDER BY categoria_nombre ASC LIMIT $inicio, $registros";
		} else {
			$consulta = "SELECT SQL_CALC_FOUND_ROWS * FROM categorias ORDER BY categoria_nombre ASC LIMIT $inicio, $registros";
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
							<th scope="col">ubicacion</th>
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
							<td>'.$dato['categoria_nombre'].'</td>
							<td>'.$dato['categoria_ubicacion'].'</td>
							<td class="d-flex justify-content-center">
								<a href="'.urlServidor.'categoria-editar/'.principalModelo::encriptar($dato['categoria_id']).'/" class="btn btn-sm btn-warning mx-1"><i class="fas fa-pen"></i></a>
								<form action="'.urlServidor.'accion/categoriaAccion.php" method="POST" class="Formulario" data-form="eliminar" autocomplete="off" >
									<input type="hidden" name="categoria_id_eliminar" value="'.principalModelo::encriptar($dato['categoria_id']).'">
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
								<a href="'.$url.'" class="btn btn-sm btn-primary">Recargar la lista de categorias</a>
							</td>
						</tr>
				';
			} else {
				$tabla.= '
						<tr>
							<td colspan="6">
								<div class="alert alert-danger mb-0">
									No hay registros de categorias en el sistema.
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
	public function seleccionar_categoria_controlador($tipo, $id) {
		$tipo = principalModelo::limpiar_cadena($tipo);
		$id = principalModelo::desencriptar($id);
		$id = principalModelo::limpiar_cadena($id);
		return categoriaModelo::seleccionar_categoria_modelo($tipo, $id);
	}

	# función para registrar un usuario
	public function nuevo_categoria_controlador() {
		# Recibiendo datos del formulario
		$nombre = principalModelo::limpiar_cadena($_POST['categoria_nombre_nuevo']);
		$ubicacion = principalModelo::limpiar_cadena($_POST['categoria_ubicacion_nuevo']);
	
		# Comprobando campos vacios
		if ($nombre== "" || $ubicacion == "" ) {
			$alerta = [
				"Alerta"=>"simple",
				"Titulo"=>"ocurrio un error",
				"Texto"=>"No has llenado todos los campos que son obligatorios.",
				"Icon"=>"error"
			];
			echo json_encode($alerta);
			exit();
		}

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

		$checar_nombre = principalModelo::ejecutar_consulta_simple("SELECT categoria_nombre FROM categorias WHERE categoria_nombre = '$nombre'");
			if ($checar_nombre->rowCount() > 0) {
				$alerta = [
					"Alerta"=>"simple",
					"Titulo"=>"ocurrio un error",
					"Texto"=>"El nombre de la categoria ingresada ya esta registrado en el sistema.",
					"Icon"=>"error"
				];
				echo json_encode($alerta);
				exit();
			}

		if (principalModelo::verificar_datos("[a-zA-ZáéíóúÁÉÍÓÚñÑ0-9 ]{5,40}", $ubicacion)) {
			$alerta = [
				"Alerta"=>"simple",
				"Titulo"=>"ocurrio un error",
				"Texto"=>"La ubicacion no coincide con el formato solicitado.",
				"Icon"=>"error"
			];
			echo json_encode($alerta);
			exit();
		}

		$datos_categoria = [
			"nombre"=>$nombre,
			"ubicacion"=>$ubicacion,
		];

		$nuevo_categoria = categoriaModelo::nuevo_categoria_modelo($datos_categoria);

		if ($nuevo_categoria->rowCount() == 1) {
			$alerta = [
				"Alerta"=>"limpiar",
				"Titulo"=>"categoria registrado",
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
	public function actualizar_categoria_controlador() {
		# Revisando el ID si existe en la base de datos
		$id = principalModelo::desencriptar($_POST['categoria_id_actualizar']);
		$id = principalModelo::limpiar_cadena($id);
		$checar_id =  principalModelo::ejecutar_consulta_simple("SELECT * FROM categorias WHERE categoria_id = '$id'");
		if ($checar_id->rowCount() <= 0) {
			$alerta = [
				"Alerta"=>"simple",
				"Titulo"=>"Ocurrio un error",
				"Texto"=>"No se ha encontrado la categoria en el sistema.",
				"Icon"=>"error"
			];
			echo json_encode($alerta);
			exit();
		} else {
			$campo = $checar_id->fetch();
		}

		# Recibiendo datos del formulario
		$nombre = principalModelo::limpiar_cadena($_POST['categoria_nombre_actualizar']);
		$ubicacion = principalModelo::limpiar_cadena($_POST['categoria_ubicacion_actualizar']);
		
		/** Comprobar campos vacios */
		if ($nombre== "" || $ubicacion== "" ) {
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
				"Titulo"=>"ocurrio un error",
				"Texto"=>"El nombre no coincide con el formato solicitado.",
				"Icon"=>"error"
			];
			echo json_encode($alerta);
			exit();
		}

		if (principalModelo::verificar_datos("[a-zA-ZáéíóúÁÉÍÓÚñÑ0-9 ]{5,40}", $ubicacion)) {
			$alerta = [
				"Alerta"=>"simple",
				"Titulo"=>"ocurrio un error",
				"Texto"=>"La ubicacion de la categoria no coincide con el formato solicitado.",
				"Icon"=>"error"
			];
			echo json_encode($alerta);
			exit();
		}

		$datos_categoria = [
			"nombre"=>$nombre,
			"ubicacion"=>$ubicacion,
			"id"=>$id
		];
		
		if (categoriaModelo::actualizar_categoria_modelo($datos_categoria)) {
			$alerta = [
				"Alerta"=>"recargar",
				"Titulo"=>"Categoria Actualizada",
				"Texto"=>"Los datos de la categoria han sido actualizados.",
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
	public function eliminar_categoria_controlador($id) {
		$id = principalModelo::desencriptar($_POST['categoria_id_eliminar']);
		$id = principalModelo::limpiar_cadena($id);

		$checar_categoria = principalModelo::ejecutar_consulta_simple("SELECT categoria_id FROM categorias WHERE categoria_id = '$id'");
		if ($checar_categoria->rowCount() <= 0){
			$alerta = [
				"Alerta"=>"simple",
				"Titulo"=>"¡Ocurrio un error!",
				"Texto"=>"La categoria que intenta eliminar, no existe en el sistema.",
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
				"Texto"=>"No tienes permisos para eliminar a las categorias.",
				"Icon"=>"error"
			];
			echo json_encode($alerta);
			exit();
		}

		$eliminar_categoria = categoriaModelo::eliminar_categoria_modelo($id);

		if ($eliminar_categoria->rowCount() != 0) {
			$alerta = [
				"Alerta"=>"recargar",
				"Titulo"=>"¡Categoria Eliminada!",
				"Texto"=>"La categoria fue eliminado con éxito del sistema.",
				"Icon"=>"success"
			];
		} else {
			$alerta = [
				"Alerta"=>"simple",
				"Titulo"=>"¡Ocurrio un error!",
				"Texto"=>"No se pudo eliminar la categoria, intente de nuevo.",
				"Icon"=>"error"
			];
		}
		echo json_encode($alerta);
	}

}