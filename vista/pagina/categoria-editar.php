<div class="container">
	<h3 class="px-3 py-4 titulos"><i class="fa-solid fa-user-gear fa-fw"></i> &nbsp; sección de categoria</h3>
</div>
<div class="container">
	<ul class="full-box list-unstyled page-tabs">
		<li>
			<a href="<?php echo urlServidor; ?>categoria-nuevo/" ><i class="fa-solid fa-plus fa-fw"></i> &nbsp; Registrar categoria</a>
		</li>
		<li>
			<a href="<?php echo urlServidor; ?>categoria-listar/"><i class="fa-solid fa-clipboard-list fa-fw"></i> &nbsp; Listar categoria</a>
		</li>
		<li>
			<a href="<?php echo urlServidor; ?>categoria-buscar/"><i class="fa-solid fa-magnifying-glass fa-fw"></i> &nbsp; Buscar categoria</a>
		</li>
	</ul>
</div>
<div class="container"> 
	<div class="px-3">
		<div class="card mb-4">
			<div class="card-body px-4">
                <?php
                require_once "./controlador/categoriaControlador.php";
                $categoria_controlador = new categoriaControlador();
                $categoria = $categoria_controlador->seleccionar_categoria_controlador("unico", $pagina[1]);
                if ($categoria->rowCount() == 1) {
                    $campo = $categoria->fetch();
                ?>
				<form action="<?php echo urlServidor; ?>accion/categoriaAccion.php" method="POST" class="Formulario" data-form="guardar" autocomplete="off" enctype="multipart/form-data">
                <input type="hidden" name="categoria_id_actualizar" value="<?php echo $pagina[1]; ?>">
					<div class="container">
						<legend class="titulos mb-3"><i class="fa-regular fa-address-card"></i> &nbsp; información general</legend>
						<div class="row">
							<div class="col-lg-4 col-md-4 col-12">
								<div class="mb-3">
									<input type="text" name="categoria_nombre_actualizar" class="form-control form-control-sm text-center" value="<?php echo $campo['categoria_nombre'];?>">
								</div>
							</div>
							<div class="col-lg-4 col-md-4 col-12">
								<div class="mb-3">
									<input type="text" name="categoria_ubicacion_actualizar" class="form-control form-control-sm text-center" value="<?php echo $campo ['categoria_ubicacion']; ?>">
								</div>
							</div>
						<div class="text-center">
							<button type="submit" class="btn btn-sm btn-primary">Guardar</button>
							<button type="reset" class="btn btn-sm btn-secondary">Limpiar</button>
						</div>
					</div>
				</form>
                <?php } ?>
			</div>
		</div>
	</div>
</div>