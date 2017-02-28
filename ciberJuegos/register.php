<?php
require_once 'php/funtions.php';
$titlePage = "Registrarse";

$usuarioInvalido=false;
$usuarioExiste=false;
$paswordInvalido=false;
$paswordRepInvalido=false;
$mailInvalido=false;
$mailExiste=false;

if($_POST){
	if (!preg_match("/^(?!.*\.\.)(?!.*\.$)[^\W][\w.]{0,29}$/", $_POST['usuario'], $match)){
		$usuarioInvalido=true;
	}
	if(!preg_match("/^([\w\.\-_]+)?\w+@[\w-_]+(\.\w+){1,}$/", $_POST['email'], $match)){
		$mailInvalido=true;
	}
	
		if($_POST["pass"]!=$_POST['passrep']){
			$paswordRepInvalido=true;
		}
	
	if(!$usuarioInvalido and !$paswordInvalido and !$paswordRepInvalido and !$mailInvalido){
		//try {
			registrarUsuario($_POST["usuario"], $_POST["pass"], $_POST["email"]);
		//} catch (userExist $e) {
			//$usuarioExiste=true;
	//	} catch (emailExist $e){
			//$mailExiste=true;
	//	}
		
			
		
	}
	
	
	
}
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title><?php echo $titlePage?></title>
<link rel="stylesheet" type="text/css" href="./styles/style.css" />
<link rel="stylesheet" href="js/jquerry/ui/jquery-ui.css">
<script src="js/jquerry/jquery-1.12.3.js"></script>
<script src="js/jquerry/ui/jquery-ui.js"></script>
<script src="js/jquerry/ui/i18n/datepicker-es.js"></script>
<script src="js/function.js"></script>
</head>
<body>
	<header>
		<?php include 'menu.php';?>
	</header>
	<article>
		<section>
			<form action="" method="post">
				Nombre de usuario: <input type="text" name="usuario" value="" pattern="^(?!.*\.\.)(?!.*\.$)[^\W][\w.]{5,20}$" /> <?php if($usuarioInvalido){ echo "el usuario es invalido";}; if($usuarioExiste){ echo "el usuario ya esta registrado";}; ?><br />
				Contraseña: <input type="password" name="pass" value=""  /> <?php if($paswordInvalido) echo "La contraseña debe tener al menos una letra mayúscula, una letra minúscula, un número y longitud de  8 a 20 caracteres"?><br />
				Repita la contraseña: <input type="password" name="passrep" value="" />  <?php if($paswordRepInvalido) echo"las contraseñas no coinciden"?> <br />
				Email: <input type="email" name="email" value="" /> <?php if($mailInvalido) {echo "el formato de email no es valido";}; if($mailExiste){ echo "el email ya esta registrado";}; ?> <br />
				<input type="submit" value="Enviar" />
			</form>

		</section>
	</article>
</body>
</html>