<?php
require_once 'php/funtions.php';
$titlePage = "Inciar seccion";
$passIncorreta=false;
if($_POST){
	if(loginUsuario($_POST["usuario"], $_POST["pass"])){
		header ( "Location: index.php" );
	}else{

		$passIncorreta=true;
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
		<?php if(!isset($_SESSION["id"])){?>
			<form action="" method="post">
				Nombre de usuario: <input type="text" name="usuario" value=""  /><br />
				Contraseña: <input type="password" name="pass" value=""  /><br />
				<input type="submit" value="Enviar" />
			</form>
			<?php }else{
				echo "ya has iniciado la seccion";
			}?>

		</section>
	</article>
</body>
</html>