<div class="container">
	<h3 class="px-3 py-4 titulos"><i class="fa-solid fa-user-gear fa-fw"></i> &nbsp; sección de clientes</h3>
</div>
<div class="container">
	<ul class="full-box list-unstyled page-tabs">
		<li>
			<a href="<?php echo urlServidor; ?>cliente-nuevo/" ><i class="fa-solid fa-plus fa-fw"></i> &nbsp; Registrar cliente</a>
		</li>
		<li>
			<a href="<?php echo urlServidor; ?>cliente-listar" class="active"><i class="fa-solid fa-clipboard-list fa-fw"></i> &nbsp; Lista de clientes</a>
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
				<?php
				require_once "./controlador/clienteControlador.php";
				$categoria_controlador = new clienteControlador();
				echo $categoria_controlador->listar_clientes_controlador($pagina[0], 3, $pagina[0], "");
				?>
			</div>
		</div>
	</div>
</div>