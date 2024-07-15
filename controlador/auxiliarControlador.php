<?php

if ($accion) {
    require_once "../modelo/auxiliarModelo.php";
} else {
    require_once "./modelo/auxiliarModelo.php";
}

class auxiliarControlador extends auxiliarModelo {

    #Funcion para seleccionar un usuario
    public function seleccionar_tabla_controlador($tabla) {
        $tabla = principalModelo::limpiar_cadena($tabla);
        return auxiliarModelo::seleccionar_tabla_modelo($tabla);
    }
}