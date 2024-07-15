<div class="container">
	<h3 class="px-3 py-4 titulos"><i class="fa-solid fa-user-gear fa-fw"></i> &nbsp; sección de usuarios</h3>
</div>
<div class="container">
	<ul class="full-box list-unstyled page-tabs">
		<li>
			<a href="<?php echo urlServidor; ?>producto-actualizar/"><i class="fa-solid fa-plus fa-fw"></i> &nbsp; Registrar producto</a>
		</li>
		<li>
			<a href="<?php echo urlServidor; ?>producto-listar/"><i class="fa-solid fa-clipboard-list fa-fw"></i> &nbsp; Lista de producto</a>
		</li>
		<li>
			<a href="<?php echo urlServidor; ?>producto-buscar"><i class="fa-solid fa-magnifying-glass fa-fw"></i> &nbsp; Buscar producto</a>
		</li>
	</ul>
</div>
<div class="container">
	<div class="px-3">
		<div class="card mb-4">
			<div class="card-body px-4">
				<?php
					require_once "./controlador/productoControlador.php";
					$producto_controlador = new productoControlador();
					$producto = $producto_controlador->seleccionar_producto_controlador("unico", $pagina[1]);
					if ($producto->rowCount() == 1) {
						$campo = $producto->fetch();
					?>
				<form action="<?php echo urlServidor; ?>accion/productoAccion.php" method="POST" class="Formulario" data-form="guardar" autocomplete="off" enctype="multipart/form-data">
				<input type="hidden" name="producto_id_actualizar" value="<?php echo $pagina[1]; ?>">
					<div class="container">
						<legend class="titulos mb-3"><i class="fa-regular fa-address-card"></i> &nbsp; Información general</legend>
							<div class="row">
							<div class="col-lg-4 col-md-4 col-12">
										<div class="mb-3">
											<select name="producto_categoria_actualizar" class="form-select form-select-sm text-center">
												<?php
												require_once "./controlador/auxiliarControlador.php";
												$auxiliar_controlador = new auxiliarControlador();
												$categorias = $auxiliar_controlador->seleccionar_tabla_controlador("categorias");
												if ($categorias->rowCount() > 0) {
													?>
													<option selected>Categorias</option>
													<?php foreach ($categorias as $categoria) {?>
													<option value="<?php echo $categoria['categoria_id']; ?>" <?php if($campo['categoria_id' == "producto_id"]) {
													echo 'selected=""';} ?>><?php echo $categoria['categoria_nombre']; ?></option>
													<?php
													}
												} else {
													?>
													<option>No hay categorias</option>
												<?php } ?>
										</select>
									</div>
								</div>
								<div class="col-lg-4 col-md-4 col-12">
									<div class="mb-3">
										<input type="text" name="producto_codigo_actualizar" class="form-control form-control-sm text-center" value="<?php echo $campo['producto_codigo']; ?>">
									</div>
								</div>
								<div class="col-lg-4 col-md-4 col-12">
									<div class="mb-3">
										<input type="text" name="producto_nombre_actualizar" class="form-control form-control-sm text-center" value="<?php echo $campo['producto_nombre']; ?>">
									</div>
								</div>
							</div>
							<div class="row">
								<div class="col-lg-6 col-md-6 col-12">
									<div class="mb-3">
										<input type="text" name="producto_descripcion_actualizar" class="form-control form-control-sm text-center" value="<?php echo $campo['producto_descripcion']; ?>">
									</div>
								</div>
								<div class="col-lg-3 col-md-3 col-12">
									<div class="mb-3">
										<input type="text" name="producto_stock_actualizar" class="form-control form-control-sm text-center" value="<?php echo $campo['producto_stock']; ?>">
									</div>
								</div>
								<div class="col-lg-3 col-md-3 col-12">
									<div class="mb-3">
										<select name="producto_tipo_unidad_actualizar" class="form-select form-select-sm text-center">
											<option value="">Seleccione una opción</option>
											<?php foreach (unidad_producto as $unidad): ?>
											<option value="<?php echo strtolower($unidad); ?>" <?php if($campo['producto_tipo_unidad'] == strtolower($unidad)) { echo 'selected'; } ?>><?php echo $unidad; ?></option>
											<?php endforeach; ?>
										</select>
									</div>
								</div>
							</div>
							<div class="row">
								<div class="col-lg-3 col-md-3 col-12">
									<div class="mb-3">
										<input type="text" name="producto_precio_compra_actualizar" class="form-control form-control-sm text-center" value="<?php echo $campo['producto_precio_compra']; ?>">
									</div>
								</div>
								<div class="col-lg-3 col-md-3 col-12">
									<div class="mb-3">
										<input type="text" name="producto_precio_venta_actualizar" class="form-control form-control-sm text-center" value="<?php echo $campo['producto_precio_venta']; ?>">
									</div>
								</div>
								<div class="col-lg-3 col-md-3 col-12">
                                    <div class="mb-3">
                                        <select name="producto_proveedor_actualizar" class="form-select form-select-sm text-center">
                                            <?php
                                            require_once "./controlador/auxiliarControlador.php";
                                            $auxiliar_controlador = new auxiliarControlador();
                                            $proveedores = $auxiliar_controlador->seleccionar_tabla_controlador("proveedores");
                                            if ($proveedores->rowCount() > 0) {
                                            ?>
                                                <option selected>Proveedores</option>
                                                <?php foreach ($proveedores as $proveedor) { ?>
													<option value="<?php echo $proveedor['proveedor_id']; ?>" <?php if($campo['proveedor_id' == "producto_id"]) {
													echo 'selected=""';} ?>><?php echo $proveedor['proveedor_nombre']; ?></option>
                                            <?php
                                                }
                                            } else {
                                            ?>
                                                <option>No hay Proveedores</option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                </div>
								<div class="col-lg-3 col-md-3 col-12">
									<div class="mb-3">
										<select name="producto_estado_actualizar" class="form-select form-select-sm text-center">
											<option>Seleccione una opción</option>
											<option value="1" <?php if($campo['producto_estado'] == '1') { echo 'selected'; } ?>>Activo</option>
											<option value="2" <?php if($campo['producto_estado'] == '2') { echo 'selected'; } ?>>Inactivo</option>  
										</select>
									</div>
								</div>
							</div>
							<div class="text-center">
								<button type="submit" class="btn btn-sm btn-primary">Guardar</button>
								<button type="reset" class="btn btn-sm btn-secondary">Limpiar</button>
							</div>
						<?php } ?>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>
