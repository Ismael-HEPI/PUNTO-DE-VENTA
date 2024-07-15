<?php

require_once "principalModelo.php"; 

class categoriaModelo extends principalModelo {

	# Función para seleccionar los datos de un usuario
	protected static function seleccionar_categoria_modelo($tipo, $id) {
		if ($tipo == "unico") {
			$sql = principalModelo::conectar()->prepare("SELECT * FROM categorias WHERE categoria_id = :id");
			$sql->bindParam(":id", $id);
		} elseif ($tipo == "contar") {
			$sql = principalModelo::conectar()->prepare("SELECT categoria_id FROM categorias WHERE categoria_id != '0'");
		}
		$sql->execute();
		return $sql;
	}

	# Función para registrar los datos de un usuario
	protected static function nuevo_categoria_modelo($datos) {
		$sql = principalModelo::conectar()->prepare("INSERT INTO categorias(categoria_nombre, categoria_ubicacion)  VALUE(:nombre, :ubicacion)");
		$sql->bindParam(":nombre", $datos['nombre']);
		$sql->bindParam(":ubicacion", $datos['ubicacion']);
		$sql->execute();
		return $sql;
	}
		# Función para actualizar los datos de un usuario
		protected static function actualizar_categoria_modelo($datos) {
			$sql = principalModelo::conectar()->prepare("UPDATE categorias SET categoria_nombre = :nombre, categoria_ubicacion = :ubicacion WHERE categoria_id = :id ");
			$sql->bindParam(":nombre", $datos['nombre']);
			$sql->bindParam(":ubicacion", $datos['ubicacion']);
			$sql->bindParam(":id", $datos['id']);
			$sql->execute();
			return $sql;
		}


	# Función para eliminar los datos de un usuario
	protected static function eliminar_categoria_modelo($id) {
		$sql = principalModelo::conectar()->prepare("DELETE FROM categorias WHERE categoria_id = :id AND categoria_id != 0");
		$sql->bindParam(":id", $id);
		$sql->execute();
		return $sql;
	}

}