<?php

require_once "principalModelo.php"; 

class clienteModelo extends principalModelo {

	# Función para seleccionar los datos de un usuario
	protected static function seleccionar_cliente_modelo($tipo, $id) {
		if ($tipo == "unico") {
			$sql = principalModelo::conectar()->prepare("SELECT * FROM clientes WHERE cliente_id = :id");
			$sql->bindParam(":id", $id);
		} elseif ($tipo == "contar") {
			$sql = principalModelo::conectar()->prepare("SELECT cliente_id FROM clientes WHERE cliente_id != '0'");
		}
		$sql->execute();
		return $sql;
	}

	# Función para registrar los datos de un usuario
	protected static function nuevo_cliente_modelo($datos) {
		$sql = principalModelo::conectar()->prepare("INSERT INTO clientes(cliente_nombre, cliente_apellido, cliente_telefono, cliente_direccion)  VALUE(:nombre, :apellido, :telefono, :direccion)");
		$sql->bindParam(":nombre", $datos['nombre']);
		$sql->bindParam(":apellido", $datos['apellido']);
		$sql->bindParam(":telefono", $datos['telefono']);
		$sql->bindParam(":direccion", $datos['direccion']);
		$sql->execute();
		return $sql;
	}
		# Función para actualizar los datos de un usuario
		protected static function actualizar_cliente_modelo($datos) {
			$sql = principalModelo::conectar()->prepare("UPDATE clientes SET cliente_nombre = :nombre, cliente_apellido = :apellido, cliente_telefono = :telefono, cliente_direccion = :direccion WHERE cliente_id = :id ");
			$sql->bindParam(":nombre", $datos['nombre']);
			$sql->bindParam(":apellido", $datos['apellido']);
			$sql->bindParam(":telefono", $datos['telefono']);
			$sql->bindParam(":direccion", $datos['direccion']);
			$sql->bindParam(":id", $datos['id']);
			$sql->execute();
			return $sql;
		}


	# Función para eliminar los datos de un usuario
	protected static function eliminar_cliente_modelo($id) {
		$sql = principalModelo::conectar()->prepare("DELETE FROM clientes WHERE cliente_id = :id AND cliente_id != 0");
		$sql->bindParam(":id", $id);
		$sql->execute();
		return $sql;
	}

}