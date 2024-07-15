<!-- Login -->
<div class="login-container">
	<div class="login-content">
		<p class="text-center"><i class="fa-solid fa-user-circle fa-5x"></i></p>
		<p class="text-center titulos">inicia sesión con tu cuenta</p>
		<form action="" method="POST" autocomplete="off">
			<div class="container">
				<div class="mb-4">
					<input type="text" name="login_usuario" class="form-control form-control-sm text-center">
				</div>
				<div class="mb-4">
					<input type="password" name="login_clave" class="form-control form-control-sm text-center">
				</div>
				<div class="mb-2">
					<button type="submit" class="btn-login titulos">entrar al sistema</button>
				</div>
			</div>
		</form>
	</div>
</div>
<?php
if (isset($_POST['login_usuario']) && isset($_POST['login_clave'])) {
	require_once "./controlador/loginControlador.php";
	$login_controlador = new loginControlador();
	echo $login_controlador->iniciar_sesion_contolador();
}
?>