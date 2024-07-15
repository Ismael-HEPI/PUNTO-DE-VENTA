<div class="container">
	<h3 class="px-3 py-4 titulos"><i class="fa-solid fa-user-gear fa-fw"></i> &nbsp; sección de clientes</h3>
</div>
<div class="container">
	<ul class="full-box list-unstyled page-tabs">
		<li>
			<a href="<?php echo urlServidor; ?>cliente-nuevo/" class="active"><i class="fa-solid fa-plus fa-fw"></i> &nbsp; Registrar cliente</a>
		</li>
		<li>
			<a href="<?php echo urlServidor; ?>cliente-listar/"><i class="fa-solid fa-clipboard-list fa-fw"></i> &nbsp; Lista de clientes</a>
		</li>
		<li>
			<a href="<?php echo urlServidor; ?>cliente-buscar"><i class="fa-solid fa-magnifying-glass fa-fw"></i> &nbsp; Buscar clientes</a>
		</li>
	</ul>
</div>
<div class="container"> 
	<div class="px-3">
		<div class="card mb-4">
			<div class="card-body px-4">
				<form action="<?php echo urlServidor; ?>accion/clienteAccion.php" method="POST" class="Formulario" data-form="guardar" autocomplete="off" enctype="multipart/form-data">
					<div class="container">
						<legend class="titulos mb-3"><i class="fa-regular fa-address-card"></i> &nbsp; información del cliente</legend>
						<div class="row">
							<div class="col-lg-4 col-md-4 col-12">
								<div class="mb-3">
									<input type="text" name="cliente_nombre_nuevo" class="form-control form-control-sm text-center" placeholder="Nombre del cliente">
								</div>
							</div>
							<div class="col-lg-4 col-md-4 col-12">
								<div class="mb-3">
									<input type="text" name="cliente_apellido_nuevo" class="form-control form-control-sm text-center" placeholder="Apellido del cliente">
								</div>
							</div>
							<div class="col-lg-4 col-md-4 col-12">
								<div class="mb-3">
									<input type="text" name="cliente_telefono_nuevo" class="form-control form-control-sm text-center" placeholder="Telefono del cliente">
								</div>
							</div>
							<div class="col-lg-8 col-md-8 col-12">
								<div class="mb-3">
									<input type="text" name="cliente_direccion_nuevo" class="form-control form-control-sm text-center" placeholder="Direccion del cliente">
								</div>
							</div>
						<div class="text-center">
							<button type="submit" class="btn btn-sm btn-primary">Guardar</button>
							<button type="reset" class="btn btn-sm btn-secondary">Limpiar</button>
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>