<!---P age Header-->
<div class="container">
    <h3 class="px-3 py-4 titulos"><i class= "fa-solid fa-user-gear fa-fw"></i> &nbsp; Seccion de usuarios</h3>
</div>
<div class="container p-4">
    <p class="text-justify">!Bienvenido! <?php echo $_SESSION['nombre_sistema'].' '.$_SESSION['apellido_sistema'];?> !Este es el panel principal del sistema, acá podra encontrar atajos para aaceder a los distintos listados de cada modulo del sistema de forma directa.</p>
</div>
<!--Page Content-->
<div class="container">
    <div class="text-center">
    <?php
    if ($_SESSION['privilegio_sistema'] == 1) {
        require_once "./controlador/productoControlador.php";
        $producto_controlador = new productoControlador();
        $productos_total = $producto_controlador->seleccionar_producto_controlador("contar",0);
    ?>
        <a href="<?php echo urlServidor;?>producto-listar/" class="box-info">
            <div class="full-box box-info-icono">
                <i class="fa-solid fa-apple-whole"></i>
            </div>
            <div class="box-info-titulo titulos">productos</div>
            <div class="full-box box-info-numero"><?php echo $productos_total->rowCount();?> </div>
        </a>
    <?php } ?>

    <?php
    if ($_SESSION['privilegio_sistema'] == 1) {
        require_once "./controlador/usuarioControlador.php";
        $usuario_controlador = new usuarioControlador();
        $usuarios_total = $usuario_controlador->seleccionar_usuario_controlador("contar",0);
    ?>
        <a href="<?php echo urlServidor;?>usuario-listar/" class="box-info">
            <div class="full-box box-info-icono">
                <i class="fa-solid fa-users-cog"></i>
            </div>
            <div class="box-info-titulo titulos">usuarios</div>
            <div class="full-box box-info-numero"><?php echo $usuarios_total->rowCount();?> </div>
        </a>
    <?php } ?>

    <?php
    if ($_SESSION['privilegio_sistema'] >= 1) {
        require_once "./controlador/categoriaControlador.php";
        $categoria_controlador = new categoriaControlador();
        $categorias_total = $categoria_controlador->seleccionar_categoria_controlador("contar",0);
    ?>
        <a href="<?php echo urlServidor;?>categoria-listar/" class="box-info">
            <div class="full-box box-info-icono">
            <i class="fa-solid fa-list-ol"></i>
            </div>
            <div class="box-info-titulo titulos">categorias</div>
            <div class="full-box box-info-numero"><?php echo $categorias_total->rowCount();?> </div>
        </a>
    <?php } ?>

    <?php
    if ($_SESSION['privilegio_sistema'] >= 1) {
        require_once "./controlador/proveedorControlador.php";
        $proveedor_controlador = new proveedorControlador();
        $proveedores_total = $proveedor_controlador->seleccionar_proveedor_controlador("contar",0);
    ?>
        <a href="<?php echo urlServidor;?>proveedor-listar/" class="box-info">
            <div class="full-box box-info-icono">
            <i class="fa-solid fa-truck"></i>
            </div>
            <div class="box-info-titulo titulos">proveedores</div>
            <div class="full-box box-info-numero"><?php echo $proveedores_total->rowCount();?> </div>
        </a>
    <?php } ?>

    <?php
    if ($_SESSION['privilegio_sistema'] == 1) {
        require_once "./controlador/clienteControlador.php";
        $cliente_controlador = new clienteControlador();
        $clientes_total = $cliente_controlador->seleccionar_cliente_controlador("contar",0);
    ?>
        <a href="<?php echo urlServidor;?>cliente-listar/" class="box-info">
            <div class="full-box box-info-icono">
                <i class="fa-solid fa-users"></i>
            </div>
            <div class="box-info-titulo titulos">clientes</div>
            <div class="full-box box-info-numero"><?php echo $clientes_total->rowCount();?> </div>
        </a>
    <?php } ?>
    </div>
</div>
