<?php

require_once "principalModelo.php";

class proveedorModelo extends principalModelo {

	# Función para seleccionar los datos de un proveedor
	protected static function seleccionar_proveedor_modelo($tipo, $id) {
		if ($tipo == "unico") {
			$sql = principalModelo::conectar()->prepare("SELECT * FROM proveedores WHERE proveedor_id = :id");
			$sql->bindParam(":id", $id);
		} elseif ($tipo == "contar") {
			$sql = principalModelo::conectar()->prepare("SELECT proveedor_id FROM proveedores WHERE proveedor_id >= '1'");
		}
		$sql->execute();
		return $sql;
	}

	protected static function nuevo_proveedor_modelo($datos) {
        $sql = principalModelo::conectar()->prepare("INSERT INTO proveedores(proveedor_nombre, proveedor_descripcion) VALUES (:nombre, :descripcion)");
        $sql->bindParam(":nombre", $datos['nombre']);
        $sql->bindParam(":descripcion", $datos['descripcion']);
        $sql->execute();
        return $sql;
    }
    
    protected static function listar_proveedor_modelo($datos) {
        $sql = principalModelo::conectar()->prepare("UPDATE proveedores SET proveedor_nombre = :nombre, proveedor_descripcion = :descripcion WHERE proveedor_id = :id");
        $sql->bindParam(":nombre", $datos['nombre']);
        $sql->bindParam(":descripcion", $datos['descripcion']);
        $sql->bindParam(":id", $datos['id']);
        $sql->execute();
        return $sql;
    }
    
	# Función para eliminar los datos de un usuario
	protected static function eliminar_proveedor_modelo($id) {
		$sql = principalModelo::conectar()->prepare("DELETE FROM proveedores WHERE proveedor_id = :id AND proveedor_id >= 1");
		$sql->bindParam(":id", $id);
		$sql->execute();
		return $sql;
	}

}