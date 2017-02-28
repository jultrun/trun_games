<?php
require_once 'php/funtions.php';

$id = trim ( strip_tags ( $_GET ['id'] ) );
$user = getUsuario ( $id );

if (count ( $user ) > 0) {
	$title = trim ( strip_tags ( $user ['NOMBRUSUARIO'] ) );
	echo str_replace ( "##titulo##", $title, ob_get_clean () );
	?>
<h1>
	<span class="ptitulo"><?php echo $title; ?></span>
</h1>
<?php
	if ($_SESSION ["id"] == $id) {
		?>
		<?php if($_POST){
			if ( is_numeric ( $_POST ['precio'] )) {
				$stid = oci_parse ( $conn, "begin PAQ_INSERT.P_ADD_CUENTA(:usuario,:cantidad); end;" );
				oci_bind_by_name ( $stid, ":usuario",$_SESSION ["id"]  );
				oci_bind_by_name ( $stid, ":cantidad", $_POST ['precio'] );
				oci_execute ( $stid );
				header( "Location:".$_SERVER['PHP_SELF']."?".http_build_query($_GET) );
				
			}else{
			}
			
	}?>

<h2>cuenta</h2>
<?php echo $user["CUENTA"]?>
<form method="post" action="">
<input name="precio" id="precio" >

	<input type="submit" value="añadir">
</form>
<?php
	
}
	?>
<h1>
	<span class="ptitulo">lista de juegos</span>
</h1>
<?php

$curs = oci_new_cursor($conn);
$stid = oci_parse($conn, "begin PAQ_SELECT.P_SELECT_JUEGOS_USER(:cursbv,:id); end;");
oci_bind_by_name($stid, ":cursbv", $curs, -1, OCI_B_CURSOR);
oci_bind_by_name($stid, ":id", $_SESSION["id"]);
oci_execute($stid);
oci_execute($curs);
$numerofilas=oci_fetch_all($curs, $juegos, null, null, OCI_FETCHSTATEMENT_BY_ROW+OCI_ASSOC);
if($numerofilas>0){
	foreach ($juegos as $juego){
		?>
		<div><a href="index.php?cat=games&id=<?php echo $juego["IDJUEGO"] ?>"> <?php echo $juego["NOMBREJUEGO"]?></a></div>";
		<?php
	}	
	
}





} else {
	header ( "Location: index.php" );
}
?>