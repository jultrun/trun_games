<?php
if (campoValido ( $_GET ["action"] )) {
	$action = trim ( strip_tags ( $_GET ['action'] ) );
	switch ($action) {
		case 'crear' :
			if ($_POST) {
				$nombrev = true;
				if (campoValido ( $_POST ["nombre"])) {
					if ( strlen( $_POST ['nombre'])<3 and strlen( $_POST ['nombre'])>50 ) {
						echo "el nombre debe estar entre 3 y 50 caracteres";
						$nombrev = false;
					}
					if ($nombrev) {
												
						$stid = oci_parse ( $conn, "begin PAQ_INSERT.P_CREAR_DESARROLLADORA(:NOMBRE); end;" );
						oci_bind_by_name ( $stid, ":NOMBRE", $_POST ["nombre"] );
						$e =oci_execute ( $stid );
						if (!$e){
								$er = oci_error($stid);  // Para errores de oci_execute, pase el gestor de sentencia
								print htmlentities($er['code']);
							
						}else{
						
							
						header( "Location:".$_SERVER['PHP_SELF']."?".http_build_query($_GET) );
									
						}
						
					
					}
				} else {
					echo "llene todos los campos";
				}
			}
			?>
<form method="post">
	Nombre:<input type="text" name="nombre" >
	<input type="submit" value="crear">
</form>
<?php
			break;
		case "update" :
			if ($_POST) {
				if (campoValido ($_POST ["desarroladorid"]) and campoValido (  $_POST ["nombre"])){ 
						$stid = oci_parse ( $conn, "begin PAC_UPDATE.P_ACUTIALIZAR_DESARROLLADORA(:vid,:NOMBRE); end;" );
						oci_bind_by_name ( $stid, ":vid", $_POST ["desarroladorid"] );
						oci_bind_by_name ( $stid, ":NOMBRE", $_POST ["nombre"]);
						ociexecute($stid);
				}else{
				ECHO "llene los campos";
				}
			}
			?>
<form method="post">
	<input type="hidden" id="desarroladorIDi" name="desarroladorid">
	<div id="desarroladorIDd"></div>
	<input id="buscador-desarroladoru" name="desarroladornom"> <input type="button"
		value="buscar" onclick="updatedesarroradora($('#buscador-desarroladoru').attr('value'))"> Nombre:<input
		type="text" id="nombred" name="nombre" >

			
	<input type="submit" value="actualizar">
</form>
<?php
			
			break;
case "eliminar":
	if ($_POST) {
		if (campoValido ( $_POST ["desarroladorid"] )) {
			$stid = oci_parse($conn, "begin  PAC_ELIMINAR.P_ELIMINAR_DESARROLLADORA(:desarrollador);end;");
				
			oci_bind_by_name ( $stid, ":desarrollador", $_POST ["desarroladorid"] );
			ociexecute($stid);
		}
	}
			
	?>
<form method="post">
	<input type="hidden" id="desarroladorIDi" name="desarroladorid">
	<div id="desarroladorIDd"></div>
	<input id="buscador-desarroladoru" name="desarroladornom"> <input type="button"
		value="buscar" onclick="updatedesarroradora($('#buscador-desarroladoru').attr('value'))"> Nombre:<input
		type="text" id="nombred" name="nombre" disabled="disabled" >

		
		<input type="submit" value="borrar">		
	</form>
	<?php
	
	break;

	}
}
?>
