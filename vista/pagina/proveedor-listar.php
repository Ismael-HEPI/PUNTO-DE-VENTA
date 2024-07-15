<div class="container">
	<h3 class="px-3 py-4 titulos"><i class="fa-solid fa-user-gear fa-fw"></i> &nbsp; sección de proveedores</h3>
</div>
<div class="container">
	<ul class="full-box list-unstyled page-tabs">
		<li>
			<a href="<?php echo urlServidor; ?>proveedor-nuevo/" ><i class="fa-solid fa-plus fa-fw"></i> &nbsp; Registrar proveedores</a>
		</li>
		<li>
			<a href="<?php echo urlServidor; ?>proveedor-listar/" class="active"><i class="fa-solid fa-clipboard-list fa-fw"></i> &nbsp; Listar proveedores</a>
		</li>
		<li>
			<a href="<?php echo urlServidor; ?>proveedor-buscar"><i class="fa-solid fa-magnifying-glass fa-fw"></i> &nbsp; Buscar proveedores</a>
		</li>
	</ul>
</div>
<div class="container">
	<div class="px-3">
		<div class="card mb-4">
			<div class="card-body px-4">
			<?php
			require_once "./controlador/proveedorControlador.php";
			$proveedor_controlador = new proveedorControlador();
			echo $proveedor_controlador->listar_proveedores_controlador($pagina[0], 1, $pagina[0], "");
			?>
			</div>
		</div>
	</div>
</div>