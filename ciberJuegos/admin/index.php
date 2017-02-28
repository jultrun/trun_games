<?php
require_once 'C:\xampp\htdocs\ciberJuegos\php\funtions.php';

if (isset ( $_SESSION ["id"] )) {
	$user = getUsuario ( $_SESSION ["id"] );
	if (intval ( $user ["ROL"] ) == 1) {
		?>

<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">

<title>Administacion</title>
<link rel="stylesheet" type="text/css"
	href="http://localhost/ciberJuegos/styles/style.css" />
<link rel="stylesheet"
	href="http://localhost/ciberJuegos/js/jquerry/ui/jquery-ui.css">
<script src="http://localhost/ciberJuegos/js//jquerry/jquery-1.12.3.js"></script>
<script src="http://localhost/ciberJuegos/js//jquerry/ui/jquery-ui.js"></script>
<script
	src="http://localhost/ciberJuegos/js//jquerry/ui/i18n/datepicker-es.js"></script>
<script src="http://localhost/ciberJuegos/js//function.js"></script>
 <script src="http://localhost/ciberJuegos/js/ckeditor/ckeditor/ckeditor.js"></script>
</head>
<body>
<article>
<section>
<ul id="admin-menu">
	<li><a href="#">juegos</a>
		<ul>
			<li><a href="?cat=games&action=crear">crear</a></li>
			<li><a href="?cat=games&action=update">actualizar</a></li>
			<li><a href="?cat=games&action=eliminar">eliminar</a></li>
		</ul></li>
			<li><a href="#">generos</a>
		<ul>
			<li><a href="?cat=generos&action=crear">crear</a></li>
			<li><a href="?cat=generos&action=update">actualizar</a></li>
			<li><a href="?cat=generos&action=eliminar">eliminar</a></li>
		</ul></li>
			<li><a href="#">desarrolladoras</a>
		<ul>
			<li><a href="?cat=desarrolladoras&action=crear">crear</a></li>
			<li><a href="?cat=desarrolladoras&action=update">actualizar</a></li>
			<li><a href="?cat=desarrolladoras&action=eliminar">eliminar</a></li>
		</ul>
		<li><a href="#">etiquetas</a>
		<ul>
			<li><a href="?cat=etiquetas&action=crear">crear</a></li>
			<li><a href="?cat=etiquetas&action=update">actualizar</a></li>
			<li><a href="?cat=etiquetas&action=eliminar">eliminar</a></li>
		</ul>
		
		</li>
</ul>
<br>
<br>
<br>
		
		
		<?php 
		if (isset ( $_GET ["cat"] ) and ! empty ( $_GET ["cat"] )) {
			$cat = trim ( strip_tags ( $_GET ['cat'] ) );
			switch ($cat) {
				case 'games' :
					include 'pages/editgames.php';
					break;
				case 'generos':
					include 'pages/editgeneros.php';
					break;
				case 'desarrolladoras':
					include 'pages/editdesarrolladoras.php';
					break;
				case 'etiquetas':
						include 'pages/editetiquetas.php';
						break;
			}
		}?>
</section>

</article>
</body>
</html>

<?php
	
} else {
		header ( "Location: ../index.php" );
	}
} else {
	header ( "Location: ../index.php" );
}
?>