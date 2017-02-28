<?php 
require_once 'php/funtions.php';?>
<ul id="page-menu">
	<li><a href="index.php">Home</a></li>
</ul>
<form action="index.php" id="form-buscador-juego">
	<input type="hidden" name="cat" value="search"> <input
		id="buscador-juego" name="term"> <a type="submit" id="boton-busqueda"><span
		class="ui-icon ui-icon-search"></span></a>
</form>
<?php if (isset($_SESSION["id"])){
	$user = getUsuario($_SESSION["id"]);
	
	?>
<ul id="account-menu">
	<li><a href=""><?php echo $user["NOMBRUSUARIO"];?></a>
		<ul>
			<li><a href="?cat=user&id=<?php echo $_SESSION["id"]?>">Mis Juegos</a></li>
			<li><a href="?cat=carrito">Carrito</a></li>
			<?php if(intval($user["ROL"])==1){?>
			<li><a href="admin/index.php">Administrar</a></li>
			<?php }?>
			
			<li><a href="logout.php">Salir</a></li>
		</ul></li>
</ul>
<?php }else{?>
<ul id="account-menu">
	<li>
	<a href="register.php">Registrarse</a>
	</li>
	<li>
	<a href="login.php">Iniciar secion </a>
	</li>
</ul>

<?php }?>