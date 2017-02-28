<?php
require_once 'php/funtions.php';
$titlePage = "Ciber Juegos";

?>

<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">

<title><?php ob_start(); ?>
##titulo##</title>
<link rel="stylesheet" type="text/css" href="styles/style.css" />
<link rel="stylesheet" href="js/jquerry/ui/jquery-ui.css">
<script src="js//jquerry/jquery-1.12.3.js"></script>
<script src="js//jquerry/ui/jquery-ui.js"></script>
<script src="js//jquerry/ui/i18n/datepicker-es.js"></script>
<script src="js//function.js"></script>
<script src="js/ckeditor/ckeditor/ckeditor.js"></script>
<script>

</script>
</head>
<body>
	<header>
		<?php include 'menu.php';?>
	</header>
	<article>
		<section>
		<?php
		if (isset ( $_GET ["cat"] ) and ! empty ( $_GET ["cat"] )) {
			$cat = trim ( strip_tags ( $_GET ['cat'] ) );
			switch ($cat) {
				case 'games' :
					include 'php/pages/games.php';
					break;
				case 'search':
					include 'php/pages/busqueda.php';
					break;
				case 'carrito':
					include  'php/pages/carrito.php';
					break;
				case 'user':
					include 'php/pages/usuario.php';
						break;
			}
		}else{
			include 'php/pages/principal.php';
		}
		?>
		</section>
	</article>
</body>

</html>