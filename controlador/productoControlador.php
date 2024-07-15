<?php

if ($accion) {
	require_once "../modelo/productoModelo.php";
} else {
	require_once "./modelo/productoModelo.php";
}

class productoControlador extends productoModelo {

    // Función para listar los productos de la DB
    public function listar_productos_controlador($pagina, $registros, $url, $busqueda) {
        $pagina = principalModelo::limpiar_cadena($pagina);
        $registros = principalModelo::limpiar_cadena($registros);
        $url = principalModelo::limpiar_cadena($url);
        $busqueda = principalModelo::limpiar_cadena($busqueda);
        $url = urlServidor.$url."/";
        $tabla = "";
    
        $pagina = (isset($pagina) && $pagina > 0) ? (int) $pagina : 1;
        $inicio = ($pagina > 0) ? (($pagina * $registros) - $registros) : 0;
    
        $conexion = principalModelo::conectar();
    
        if (isset($busqueda) && $busqueda != "") {
			$consulta = "SELECT * FROM productos
            INNER JOIN categorias ON productos.producto_categoria = categorias.categoria_id
            INNER JOIN proveedores ON productos.producto_proveedor = proveedores.proveedor_id) AND (producto_nombre LIKE '%$busqueda%' OR producto_descripcion LIKE '%$busqueda%')) ORDER BY producto_nombre ASC LIMIT $inicio, $registros";
		} else {
			$consulta = "SELECT * FROM productos
            INNER JOIN categorias ON productos.producto_categoria = categorias.categoria_id
            INNER JOIN proveedores ON productos.producto_proveedor = proveedores.proveedor_id  ORDER BY producto_nombre ASC LIMIT $inicio, $registros";
		}
    
		$conexion = principalModelo::conectar();
		$datos = $conexion->query($consulta);
		$datos = $datos->fetchAll();
		$total = $conexion->query("SELECT FOUND_ROWS()");
		$total = (int) $total->fetchColumn();
		$npaginas = ceil($total / $registros);

    
        if ($total >= 1 && $pagina <= $npaginas) {
            $contador = $inicio + 1;
            foreach ($datos as $dato) {
                $imagen = $dato['producto_imagen'] != "" ? $dato['producto_imagen'] : 'ruta/por/defecto/imagen.png';
                $tabla .= '
                        <div class="col-lg-6 col-md-6 col-12">
                            <table class="table table-bordered fs-8">
                                <tbody class="text-center">
                                    <tr>
                                        <td colspan="3">
                                            <img src="'. $imagen .'"alt="" width="100" height="100">
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="3">'.$contador.'.- '.$dato['producto_nombre'].'</td>
                                    </tr>
                                    <tr>
                                        <td>Código: '.$dato['producto_codigo'].'</td>
                                        <td>Precio de compra: $'.$dato['producto_precio_compra'].'</td>
                                        <td>Precio de ventas: $'.$dato['producto_precio_venta'].'</td>
                                    </tr>
                                    <tr>
                                        <td>En stock: '.$dato['producto_stock'].'</td>
                                        <td>Categoría: '.$dato['categoria_nombre'].'</td>
                                        <td>Proveedor: '.$dato['proveedor_nombre'].'</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                ';
                $contador++;
            }
        } else {
            if ($total >= 1) {
                $tabla .= '
                    <tr>
                        <td colspan="10">
                            <a href="'.$url.'" class="btn btn-sm btn-primary">Recargar la lista de productos</a>
                        </td>
                    </tr>
                ';
            } else {
                $tabla .= '
                    <tr>
                        <td colspan="10">
                            <div class="alert alert-danger mb-0">
                                No hay registros de productos en el sistema.
                            </div>
                        </td>
                    </tr>
                ';
            }
        }
    
        $tabla .= '
                    </tbody>
                </table>
            </div>
        ';
    
        if ($total >= 1 && $pagina <= $npaginas) {
            $tabla .= principalModelo::paginador_tablas($pagina, $npaginas, $url, 7);
        }
    
        return $tabla;
    }
    
    // Función para seleccionar un producto
    public function seleccionar_producto_controlador($tipo, $id) {
        $tipo = principalModelo::limpiar_cadena($tipo);
        $id = principalModelo::desencriptar($id);
        $id = principalModelo::limpiar_cadena($id);
        return productoModelo::seleccionar_producto_modelo($tipo, $id);
    }

    // Función para registrar un producto
    public function nuevo_producto_controlador() {
        # Recibiendo datos del formulario
        $categoria = principalModelo::limpiar_cadena($_POST['producto_categoria_nuevo']);
        $codigo = principalModelo::limpiar_cadena($_POST['producto_codigo_nuevo']);
        $nombre = principalModelo::limpiar_cadena($_POST['producto_nombre_nuevo']);
        $descripcion = principalModelo::limpiar_cadena($_POST['producto_descripcion_nuevo']);
        $stock = principalModelo::limpiar_cadena($_POST['producto_stock_nuevo']);
        $tipo_unidad = principalModelo::limpiar_cadena($_POST['producto_tipo_unidad_nuevo']);
        $precio_compra = principalModelo::limpiar_cadena($_POST['producto_precio_compra_nuevo']);
        $precio_venta = principalModelo::limpiar_cadena($_POST['producto_precio_venta_nuevo']);
        $proveedor = principalModelo::limpiar_cadena($_POST['producto_proveedor_nuevo']);
        $estado = principalModelo::limpiar_cadena($_POST['producto_estado_nuevo']);
    
        # Comprobando campos vacíos
        if ($categoria == "" || $codigo == "" || $nombre == "" ||   $descripcion == "" || $stock == "" || $tipo_unidad == "" || $precio_compra == "" || $precio_venta == "" || $proveedor == "" || $estado == "") {
            $alerta = [
                "Alerta" => "simple",
                "Titulo" => "Ocurrió un error",
                "Texto" => "No has llenado todos los campos que son obligatorios.",
                "Icon" => "error"
            ];
            echo json_encode($alerta);
            exit();
        }
    
        # Comprobando la integridad de los datos
        $checar_categoria = principalModelo::ejecutar_consulta_simple("SELECT categoria_id FROM categorias WHERE categoria_id = '$categoria'");
        if ($checar_categoria->rowCount() <= 0) {
            $alerta = [
                "Alerta" => "simple",
                "Titulo" => "Ocurrió un error",
                "Texto" => "La categoría seleccionada no existe.",
                "Icon" => "error"
            ];
            echo json_encode($alerta);
            exit();
        }
        
        // Verificar el formato de la categoría
        if (principalModelo::verificar_datos("[a-zA-Z0-9]{1,50}", $categoria)) {
            $alerta = [
                "Alerta" => "simple",
                "Titulo" => "Ocurrió un error",
                "Texto" => "La categoría no coincide con el formato solicitado.",
                "Icon" => "error"
            ];
            echo json_encode($alerta);
            exit();
        }

        if (principalModelo::verificar_datos("[a-zA-Z0-9-]{1,50}", $codigo)) {
            $alerta = [
                "Alerta" => "simple",
                "Titulo" => "Ocurrió un error",
                "Texto" => "El código no coincide con el formato solicitado.",
                "Icon" => "error"
            ];
            echo json_encode($alerta);
            exit();
        }
    
        if (principalModelo::verificar_datos("[a-zA-ZáéíóúÁÉÍÓÚñÑ0-9 ]{1,50}", $nombre)) {
            $alerta = [
                "Alerta" => "simple",
                "Titulo" => "Ocurrió un error",
                "Texto" => "El nombre no coincide con el formato solicitado.",
                "Icon" => "error"
            ];
            echo json_encode($alerta);
            exit();
        }

        if ($stock <= 0){
            $alerta = [
                "Alerta" => "simple",
                "Titulo" => "Ocurrió un error",
                "Texto" => "El stock no coincide con el formato solicitado.",
                "Icon" => "error"
            ];
            echo json_encode($alerta);
            exit();
        }
    
        if (principalModelo::verificar_datos("[a-zA-ZáéíóúÁÉÍÓÚñÑ0-9 ]{5,100}", $descripcion)) {
            $alerta = [
                "Alerta" => "simple",
                "Titulo" => "Ocurrió un error",
                "Texto" => "La descripción no coincide con el formato solicitado.",
                "Icon" => "error"
            ];
            echo json_encode($alerta);
            exit();
        }

        if ($stock <= 0){
            $alerta = [
                "Alerta" => "simple",
                "Titulo" => "Ocurrió un error",
                "Texto" => "El stock no coincide con el formato solicitado.",
                "Icon" => "error"
            ];
            echo json_encode($alerta);
            exit();
        }
    
        if (principalModelo::verificar_datos("[a-zA-ZáéíóúÁÉÍÓÚñÑ0-9 ]{1,50}", $tipo_unidad)) {
            $alerta = [
                "Alerta" => "simple",
                "Titulo" => "Ocurrió un error",
                "Texto" => "El tipo de unidad no coincide con el formato solicitado.",
                "Icon" => "error"
            ];
            echo json_encode($alerta);
            exit();
        }
    
        if (principalModelo::verificar_datos("[0-9.]{1,10}", $precio_compra)) {
            $alerta = [
                "Alerta" => "simple",
                "Titulo" => "Ocurrió un error",
                "Texto" => "El precio de compra no coincide con el formato solicitado.",
                "Icon" => "error"
            ];
            echo json_encode($alerta);
            exit();
        }
    
        if (principalModelo::verificar_datos("[0-9.]{1,10}", $precio_venta)) {
            $alerta = [
                "Alerta" => "simple",
                "Titulo" => "Ocurrió un error",
                "Texto" => "El precio de venta no coincide con el formato solicitado.",
                "Icon" => "error"
            ];
            echo json_encode($alerta);
            exit();
        }
    
        if (principalModelo::verificar_datos("[0-9]{1,11}", $proveedor)) {
            $alerta = [
                "Alerta" => "simple",
                "Titulo" => "Ocurrió un error",
                "Texto" => "El proveedor no coincide con el formato solicitado.",
                "Icon" => "error"
            ];
            echo json_encode($alerta);
            exit();
        }

        if ($estado < 1 || $estado > 2) {
			$alerta = [
				"Alerta"=>"simple",
				"Titulo"=>"ocurrio un error",
				"Texto"=>"El estado seleccionado no es valido.",
				"Icon"=>"error"
			];
			echo json_encode($alerta);
			exit();
		}
    
        # Verificar si el código del producto ya está registrado
        $checar_codigo = principalModelo::ejecutar_consulta_simple("SELECT producto_codigo FROM productos WHERE producto_codigo = '$codigo'");
        if ($checar_codigo->rowCount() > 0) {
            $alerta = [
                "Alerta" => "simple",
                "Titulo" => "Ocurrió un error",
                "Texto" => "El código ingresado ya está registrado en el sistema.",
                "Icon" => "error"
            ];
            echo json_encode($alerta);
            exit();
        }

        // Directorio de imágenes
		$img_dir = '../imagen/productos/';

		// Procesar la imagen
		$foto = "";
		if ($_FILES['producto_imagen_nuevo']['name'] != "" && $_FILES['producto_imagen_nuevo']['size'] > 0) {
			// Crear directorio si no existe
			if (!file_exists($img_dir)) {
				if (!mkdir($img_dir, 0777, true)) {
					$alerta = [
						"tipo" => "simple",
						"titulo" => "Ocurrió un error inesperado",
						"texto" => "Error al crear el directorio",
						"icono" => "error"
					];
					echo json_encode($alerta);
					exit();
				}
			}
	
			// Verificar formato de imagen
			$mime_type = mime_content_type($_FILES['producto_imagen_nuevo']['tmp_name']);
			if ($mime_type != "image/jpeg" && $mime_type != "image/png") {
				$alerta = [
					"tipo" => "simple",
					"titulo" => "Ocurrió un error inesperado",
					"texto" => "La imagen que ha seleccionado es de un formato no permitido",
					"icono" => "error"
				];
				echo json_encode($alerta);
				exit();
			}
	
			// Verificar peso de imagen
			if (($_FILES['producto_imagen_nuevo']['size'] / 1024) > 5120) {
				$alerta = [
					"tipo" => "simple",
					"titulo" => "Ocurrió un error inesperado",
					"texto" => "La imagen que ha seleccionado supera el peso permitido",
					"icono" => "error"
				];
				echo json_encode($alerta);
				exit();
			}
	
			// Nombre de la foto
			$foto = $codigo . "_" . rand(0, 100);
	
			// Extensión de la imagen
			switch ($mime_type) {
				case 'image/jpeg':
					$foto = $foto . ".jpg";
					break;
				case 'image/png':
					$foto = $foto . ".png";
					break;
			}
	
			// Cambiar permisos del directorio
			chmod($img_dir, 0777);
	
			// Mover imagen al directorio
			if (!move_uploaded_file($_FILES['producto_imagen_nuevo']['tmp_name'], $img_dir . $foto)) {
				$alerta = [
					"tipo" => "simple",
					"titulo" => "Ocurrió un error inesperado",
					"texto" => "No podemos subir la imagen al sistema en este momento",
					"icono" => "error"
				];
				echo json_encode($alerta);
				exit();
			}
		}
    
        $datos_producto = [
            "categoria" => $categoria,
            "codigo" => $codigo,
            "nombre" => $nombre,
            "stock" => $stock,
            "descripcion" => $descripcion,
            "tipo_unidad" => $tipo_unidad,
            "precio_compra" => $precio_compra,
            "precio_venta" => $precio_venta,
            "proveedor" => $proveedor,
            "estado" => $estado,
            "imagen" => $img_dir . $foto
        ];
    
        $nuevo_producto = productoModelo::nuevo_producto_modelo($datos_producto);
    
        if ($nuevo_producto->rowCount() == 1) {
            $alerta = [
                "Alerta" => "limpiar",
                "Titulo" => "Producto registrado",
                "Texto" => "Los datos se guardaron con éxito en el sistema.",
                "Icon" => "success"
            ];
        } else {
            $alerta = [
                "Alerta" => "simple",
                "Titulo" => "Ocurrió un error",
                "Texto" => "No se guardaron los datos en el sistema.",
                "Icon" => "error"
            ];
        }
    
        echo json_encode($alerta);
    }

    // Función para actualizar un producto
    public function actualizar_producto_controlador() {
        // Revisando el ID si existe en la base de datos
        $id = principalModelo::desencriptar($_POST['producto_id_actualizar']);
        $id = principalModelo::limpiar_cadena($id);
        $checar_id = productoModelo::ejecutar_consulta_simple("SELECT * FROM productos WHERE producto_id = '$id'");
        if ($checar_id->rowCount() <= 0) {
            $alerta = [
                "Alerta" => "simple",
                "Titulo" => "Ocurrió un error",
                "Texto" => "No se ha encontrado el producto en el sistema.",
                "Icon" => "error"
            ];
            echo json_encode($alerta);
            exit();
        } else {
            $campo = $checar_id->fetch();
        }

        // Recibiendo datos del formulario
        $nombre = principalModelo::limpiar_cadena($_POST['producto_nombre_actualizar']);
        $descripcion = principalModelo::limpiar_cadena($_POST['producto_descripcion_actualizar']);
        $categoria = principalModelo::limpiar_cadena($_POST['producto_categoria_actualizar']);
        $proveedor = principalModelo::limpiar_cadena($_POST['producto_proveedor_actualizar']);
        $precio_compra = principalModelo::limpiar_cadena($_POST['producto_precio_compra_actualizar']);
        $precio_venta = principalModelo::limpiar_cadena($_POST['producto_precio_venta_actualizar']);
        $stock = principalModelo::limpiar_cadena($_POST['producto_stock_actualizar']);
        $estado = principalModelo::limpiar_cadena($_POST['producto_estado_actualizar']);

        // Comprobando campos vacíos
        if ($nombre == "" || $descripcion == "" || $categoria == "" || $proveedor == "" || $precio_compra == "" || $precio_venta == "" || $stock == "" || $estado == "") {
            $alerta = [
                "Alerta" => "simple",
                "Titulo" => "Ocurrió un error",
                "Texto" => "No has llenado todos los campos que son obligatorios.",
                "Icon" => "error"
            ];
            echo json_encode($alerta);
            exit();
        }

        // Comprobando la integridad de los datos
        if (principalModelo::verificar_datos("[a-zA-ZáéíóúÁÉÍÓÚñÑ ]{2,50}", $nombre)) {
            $alerta = [
                "Alerta" => "simple",
                "Titulo" => "Ocurrió un error",
                "Texto" => "El nombre no coincide con el formato solicitado.",
                "Icon" => "error"
            ];
            echo json_encode($alerta);
            exit();
        }

        if (principalModelo::verificar_datos("[a-zA-ZáéíóúÁÉÍÓÚñÑ0-9 ]{5,60}", $descripcion)) {
            $alerta = [
                "Alerta" => "simple",
                "Titulo" => "Ocurrió un error",
                "Texto" => "La descripción no coincide con el formato solicitado.",
                "Icon" => "error"
            ];
            echo json_encode($alerta);
            exit();
        }

        if (!is_numeric($precio_compra) || $precio_compra <= 0) {
            $alerta = [
                "Alerta" => "simple",
                "Titulo" => "Ocurrió un error",
                "Texto" => "El precio de compra debe ser un número mayor a cero.",
                "Icon" => "error"
            ];
            echo json_encode($alerta);
            exit();
        }

        if (!is_numeric($precio_venta) || $precio_venta <= 0 || $precio_venta < $precio_compra) {
            $alerta = [
                "Alerta" => "simple",
                "Titulo" => "Ocurrió un error",
                "Texto" => "El precio de venta debe ser un número mayor a cero y mayor que el precio de compra.",
                "Icon" => "error"
            ];
            echo json_encode($alerta);
            exit();
        }

        if (!is_numeric($stock) || $stock <= 0) {
            $alerta = [
                "Alerta" => "simple",
                "Titulo" => "Ocurrió un error",
                "Texto" => "El stock debe ser un número mayor a cero.",
                "Icon" => "error"
            ];
            echo json_encode($alerta);
            exit();
        }

        if ($estado != 1 && $estado != 0) {
            $alerta = [
                "Alerta" => "simple",
                "Titulo" => "Ocurrió un error",
                "Texto" => "El estado del producto debe ser 1 (Activo) o 0 (Inactivo).",
                "Icon" => "error"
            ];
            echo json_encode($alerta);
            exit();
        }

        $datos_producto = [
            "nombre" => $nombre,
            "categoria" => $categoria,
            "codigo" => $codigo,
            "descripcion" => $descripcion,
            "proveedor" => $proveedor,
            "precio_compra" => $precio_compra,
            "precio_venta" => $precio_venta,
            "stock" => $stock,
            "estado" => $estado,
            "id" => $id

        ];

        // Verificar si se actualizó correctamente
        if (productoModelo::actualizar_producto_modelo($datos_producto)) {
            $alerta = [
                "Alerta" => "limpiar",
                "Titulo" => "Producto actualizado",
                "Texto" => "Los datos se actualizaron con éxito en el sistema.",
                "Icon" => "success"
            ];
        } else {
            $alerta = [
                "Alerta" => "simple",
                "Titulo" => "Ocurrió un error",
                "Texto" => "No se guardaron los datos en el sistema.",
                "Icon" => "error"
            ];
        }

        // Retornar la alerta en formato JSON
        echo json_encode($alerta);
    }

    // Función para eliminar un producto
    public function eliminar_producto_controlador() {
        # Revisando el ID si existe en la base de datos
        $id = principalModelo::desencriptar($_POST['producto_id_eliminar']);
        $id = principalModelo::limpiar_cadena($id);
        $checar_id = productoModelo::ejecutar_consulta_simple("SELECT * FROM productos WHERE producto_id = '$id'");
        if ($checar_id->rowCount() <= 0) {
            $alerta = [
                "Alerta" => "simple",
                "Titulo" => "Ocurrió un error",
                "Texto" => "No se ha encontrado el producto en el sistema.",
                "Icon" => "error"
            ];
            echo json_encode($alerta);
            exit();
        }

        $eliminar_producto = productoModelo::eliminar_producto_modelo($id);

        if ($eliminar_producto->rowCount() != 0) {
            $alerta = [
                "Alerta" => "limpiar",
                "Titulo" => "Producto eliminado",
                "Texto" => "El producto se ha eliminado del sistema correctamente.",
                "Icon" => "success"
            ];
        } else {
            $alerta = [
                "Alerta" => "simple",
                "Titulo" => "Ocurrió un error",
                "Texto" => "No se pudo eliminar el producto del sistema.",
                "Icon" => "error"
            ];
        }

        echo json_encode($alerta);
    }
    }
