<?php

require_once "PrincipalModelo.php";

class auxiliarModelo extends principalModelo {

    #Funcion para seleccionar los datos de una tabla
    protected static function seleccionar_tabla_modelo($tabla) {
        if ($tabla == "categorias") {
            $sql = principalModelo::conectar()->prepare("SELECT * FROM categorias");
        }
        elseif ($tabla == "proveedores") {
            $sql = principalModelo::conectar()->prepare("SELECT * FROM proveedores");
        }
        $sql->execute();
        return $sql;
    }
}