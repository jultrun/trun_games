<?php
require_once 'php/funtions.php';


$id=trim(strip_tags($_GET['id']));
$curs = oci_new_cursor($conn);
$stid = oci_parse($conn, "begin PAQ_SELECT.P_SELECT_ONE_JUEGO(:cursbv,:id); end;");
oci_bind_by_name($stid, ":cursbv", $curs, -1, OCI_B_CURSOR);
oci_bind_by_name($stid, ":id", $id);
oci_execute($stid);
oci_execute($curs);
if (($row = oci_fetch_array($curs, OCI_ASSOC+OCI_RETURN_NULLS)) != false) {
	$title = trim(strip_tags($row['NOMBREJUEGO']));
	echo str_replace("##titulo##", $title, ob_get_clean());
}else {
	header ( "Location: index.php" );
}

if($_POST){
	if(isset($_POST["gameid"]) and !empty($_POST["gameid"])){
	if(!in_array($_POST["gameid"], $_SESSION["carrito"])){
		array_push($_SESSION["carrito"], $_POST["gameid"]);
		$_SESSION["carrito"]=array_unique($_SESSION["carrito"]);
		header( "Location:".$_SERVER['PHP_SELF']."?".http_build_query($_GET) );
		
		
	}
	}
}

?>
<div>
<table id="games-tabla">
	<tr>
		<td>Desarrollador</td>
		<td><?php echo $row["NOMBREDESAROLLADOR"];?></td>
	</tr>
	<tr>
		<td>Genero</td>
		<td><?php echo $row["NOMBREGENERO"];?></td>
	</tr>
	<tr>
		<td>Fecha de lanzamiento</td>
		
		<td><?php setlocale(LC_ALL, "Spanish_Colombia");
		echo  strftime('%d de %B de %Y', strtotime($row["LANZAMIENTOJUEGO"]))?></td>
	</tr>
</table>
</div>


<h1><span class="ptitulo"><?php echo trim(strip_tags($row['NOMBREJUEGO']));?></span></h1>
<h4>Acerca del juego</h4>
<?php
$acerca = $row['ACERCAJUEGO'];
echo "<p>".$acerca."</p>";
?>
<h4><span class="ptitulo">Precio</span></h4>
<?php
$decuento = intval($row["DESCUENTOJUEGO"]);
if($decuento==0){
	$precio =  '$'.number_format($row['PRECIOJUEGO'], 0, ',', '.');
	echo $precio;	
}else{
	echo "descuento del ".$row["DESCUENTOJUEGO"]."% <br>";
	$precio =  '$'.number_format($row['PRECIODESC'], 0, ',', '.');
	echo $precio;
}
if( isset( $_SESSION["id"])){
	$stid = oci_parse($conn, "begin PAQ_SELECT. P_COMPROBAR_JUEGO(:cuenta,:vid,:juego) ; end;");
	oci_bind_by_name($stid, ":cuenta", $cuentaJuego, 40);
	oci_bind_by_name($stid, ":vid", $_SESSION["id"]);
	oci_bind_by_name($stid, ":juego", $row["IDJUEGO"]);
	oci_execute($stid);
	
	if($cuentaJuego<1){
		
	
if(!in_array($id, $_SESSION["carrito"])){

	?>
<form action="" method="post">
<input type="hidden" name="gameid" value=<?php echo $id;?>>
	<input type="submit" value="Enviar" />
</form>
<?php }else{
	echo "<div>el juego ya esta añadido al carrito</div>";
}}else{
	echo "<div>ya tienes el juego</div>";
}}
?>


<h4><span class="ptitulo">Requerimientos</span></h4>
<?php 
$curs = oci_new_cursor($conn);
$stid = oci_parse($conn, "begin PAQ_SELECT.P_SELECT_REQUERIMIENTOSJUEGO(:cursbv,:id); end;");
oci_bind_by_name($stid, ":cursbv", $curs, -1, OCI_B_CURSOR);
oci_bind_by_name($stid, ":id", $id);
oci_execute($stid);
oci_execute($curs);

$numerofilas=oci_fetch_all($curs, $requerimientos, null, null, OCI_FETCHSTATEMENT_BY_ROW+OCI_ASSOC);
if($numerofilas>0){
?>
<div id="tabs-games">
	<ul>
	<?php
	foreach ($requerimientos as $so){
		echo "<li><a href='#tab-".$so["NOMBRE"]."'>".$so["NOMBRE"]."</a></li>";
	}
?>
	</ul>
	<?php foreach ($requerimientos as $so){
		echo "
		<div id='tab-".$so["NOMBRE"]."'>
		<p> RAM:".$so["RAM"]."</p>
		<p> DISCO:".$so["DISCO"]."</p>
		<p> GRAFICA:".$so["GRAFICA"]."</p>
		<p> PROCESADOR:".$so["PROCESADOR"]."</p>
		</div>";
	}
	
	?>
</div>
<?php
}else{
	echo "no hay datos";
}

$curs = oci_new_cursor ( $conn );
$stid = oci_parse ( $conn, "begin PAQ_SELECT.P_SELECT_ETIQUETAS_JUEGO(:vcursor,:vid); end;" );
oci_bind_by_name ( $stid, ":vcursor", $curs, - 1, OCI_B_CURSOR );
oci_bind_by_name ( $stid, ":vid", $id );
oci_execute ( $stid );
oci_execute ( $curs );
?>
<div id="etiquetas">
<?php
while ( ($row = oci_fetch_array ( $curs, OCI_ASSOC + OCI_RETURN_NULLS )) != false) {
	?>
	<div> <?php echo $row["NOMBREETIQUETA"]?></div>
	<?php
}
?>
</div>
<?php 
$curs = oci_new_cursor ( $conn );
$stid = oci_parse ( $conn, "begin PAQ_SELECT.P_SELECT_ANALISISJUEGO(:vcursor,:vid); end;" );
oci_bind_by_name ( $stid, ":vcursor", $curs, - 1, OCI_B_CURSOR );
oci_bind_by_name ( $stid, ":vid", $id );
oci_execute ( $stid );
oci_execute ( $curs );
?>
<div id="comentatios">
<?php
while ( ($row = oci_fetch_array ( $curs, OCI_ASSOC + OCI_RETURN_NULLS )) != false) {
	?>
	<div class="comentario">
		<div class="comentariob"> <?php echo $row["ANALISIS"]?></div>
		<div class="comentariof"><?php setlocale(LC_ALL, "Spanish_Colombia");
		echo  strftime('%d de %B de %Y', strtotime($row["FECHA"]))." por ". $row["NOMBRUSUARIO"]?></div>
	</div>
	<?php
}
?>
</div>
<?php 
if (isset ( $_SESSION ["id"] )) {
	$user = getUsuario ( $_SESSION ["id"] );
	if($_POST){
		if(campoValido($_POST["comentario"])){
			
			
			$stid = oci_parse ( $conn, "begin PAQ_INSERT.P_CREAR_COMENTARIO(:usuario,:juego,:vanalisis);end;");
				
			oci_bind_by_name ( $stid, ":usuario", $_SESSION ["id"] );
			oci_bind_by_name ( $stid, ":juego", $id );
			oci_bind_by_name ( $stid, ":vanalisis", $_POST ["comentario"] );
			ociexecute($stid);
			header( "Location:".$_SERVER['PHP_SELF']."?".http_build_query($_GET) );
				
		}
	}
	?>
	<form method="post">
	<textarea name="comentario" id="comentar" rows="10" cols="80">
			</textarea>
			<input type="submit" >
	
	</form>
	<?php
}?>

