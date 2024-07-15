<div class="container">
    <h3 class="px-3 py-4 titulos"><i class="fa-solid fa-user-gear fa-fw"></i> &nbsp; sección de clientes</h3>
</div>
<div class="container">
    <ul class="full-box list-unstyled page-tabs">
        <li>
            <a href="<?php echo urlServidor; ?>cliente-nuevo/" ><i class="fa-solid fa-plus fa-fw"></i> &nbsp; Registrar cliente</a>
        </li>
        <li>
            <a href="<?php echo urlServidor; ?>cliente-listar/"><i class="fa-solid fa-clipboard-list fa-fw"></i> &nbsp; Lista de clientes</a>
        </li>
        <li>
            <a href="<?php echo urlServidor; ?>cliente-buscar/"><i class="fa-solid fa-magnifying-glass fa-fw"></i> &nbsp; Buscar cliente</a>
        </li>
    </ul>
</div>
<div class="container"> 
    <div class="px-3">
        <div class="card mb-4">
            <div class="card-body px-4">
                <?php
                require_once "./controlador/clienteControlador.php";
                $cliente_controlador = new clienteControlador();
                $cliente = $cliente_controlador->seleccionar_cliente_controlador("unico", $pagina[1]);
                if ($cliente->rowCount() == 1) {
                    $campo = $cliente->fetch();
                ?>
                <form action="<?php echo urlServidor; ?>accion/clienteAccion.php" method="POST" class="Formulario" data-form="guardar" autocomplete="off" enctype="multipart/form-data">
                    <input type="hidden" name="cliente_id_actualizar" value="<?php echo $pagina[1]; ?>">
                    <div class="container">
                        <legend class="titulos mb-3"><i class="fa-regular fa-address-card"></i> &nbsp; Información del cliente</legend>
                        <div class="row">
                            <div class="col-lg-4 col-md-4 col-12">
                                <div class="mb-3">
                                    <input type="text" name="cliente_nombre_actualizar" class="form-control form-control-sm text-center" value="<?php echo $campo['cliente_nombre'];?>">
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-4 col-12">
                                <div class="mb-3">
                                    <input type="text" name="cliente_apellido_actualizar" class="form-control form-control-sm text-center" value="<?php echo $campo ['cliente_apellido']; ?>">
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-4 col-12">
                                <div class="mb-3">
                                    <input type="text" name="cliente_telefono_actualizar" class="form-control form-control-sm text-center" value="<?php echo $campo ['cliente_telefono']; ?>">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-8 col-md-8 col-12">
                                <div class="mb-3">
                                    <input type="text" name="cliente_direccion_actualizar" class="form-control form-control-sm text-center" value="<?php echo $campo ['cliente_direccion']; ?>">
                                </div>
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
