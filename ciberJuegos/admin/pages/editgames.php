<?php
if (campoValido ( $_GET ["action"] )) {
	$action = trim ( strip_tags ( $_GET ['action'] ) );
	switch ($action) {
		case 'crear' :
			if ($_POST) {
				$preciov = true;
				$nombrev = true;
				if (campoValido ( $_POST ["nombre"] ) and campoValido ( $_POST ["precio"] ) and campoValido ( $_POST ["fecha"] ) and campoValido ( $_POST ["descripcion"] ) and campoValido ( $_POST ["acerca"] ) and campoValido ( $_POST ["genero"] ) and campoValido ( $_POST ["desarrolladora"] )) {
					if ( strlen( $_POST ['nombre'])<3 and strlen( $_POST ['nombre'])>50 ) {
						echo "el nombre debe estar entre 3 y 50 caracteres";
						$nombrev = false;
					}
					if (! is_numeric ( $_POST ['precio'] )) {
						echo "el precio no es valido";
						$preciov = false;
					}
					if ($preciov and $nombrev) {
												
						$stid = oci_parse ( $conn, "begin PAQ_INSERT.P_CREAR_JUEGO(:NOMBRE,:PRECIO,:FECHA,:DESCUENTO,:ACERCA,:DESARRADOR,:GENERO,:DESCRIPCION); end;" );
						oci_bind_by_name ( $stid, ":NOMBRE", $_POST ["nombre"] );
						oci_bind_by_name ( $stid, ":PRECIO", $_POST ["precio"] );
						oci_bind_by_name ( $stid, ":FECHA", $_POST ["fecha"] );
						oci_bind_by_name ( $stid, ":DESCUENTO", $_POST["descuento"] );
						oci_bind_by_name ( $stid, ":ACERCA", $_POST ["acerca"] );
						oci_bind_by_name ( $stid, ":DESARRADOR", $_POST ["desarrolladora"] );
						oci_bind_by_name ( $stid, ":GENERO", $_POST ["genero"] );
						oci_bind_by_name ( $stid, ":DESCRIPCION", $_POST ["descripcion"] );
					
						$e =oci_execute ( $stid );
						if (!$e){
								$er = oci_error($stid);  // Para errores de oci_execute, pase el gestor de sentencia
								print htmlentities($er['code']);
							
						}else{
						
						
						
						
						$curs = oci_new_cursor($conn);
						$stid = oci_parse($conn, "begin PAQ_SELECT.P_SELECT_ALL_SO(:cursbv); end;");
						oci_bind_by_name($stid, ":cursbv", $curs, -1, OCI_B_CURSOR);
						oci_execute($stid);
						oci_execute($curs);
						
						
						
						oci_fetch_all($curs, $sos, null, null, OCI_FETCHSTATEMENT_BY_ROW+OCI_ASSOC);
						
						foreach ($sos as $so){
							$ram="1GB";
							$disco="1GB!";
							$grafica="";
							$procesador="";
							if(campoValido($_POST["ram-".$so["NOMBRE"]])){
								$ram=$_POST["ram-".$so["NOMBRE"]];
							}
							if(campoValido($_POST["disco-".$so["NOMBRE"]])){
								$disco=$_POST["disco-".$so["NOMBRE"]];
							}
							if(campoValido($_POST["grafica-".$so["NOMBRE"]])){
								$grafica=$_POST["grafica-".$so["NOMBRE"]];
							}
							if(campoValido($_POST["procesador-".$so["NOMBRE"]])){
								$procesador=$_POST["procesador-".$so["NOMBRE"]];
							}
					
							
							if( campoValido($_POST["ram-".$so["NOMBRE"]]) and 
									campoValido($_POST["disco-".$so["NOMBRE"]]) and 
									campoValido($_POST["grafica-".$so["NOMBRE"]]) and 
									campoValido($_POST["procesador-".$so["NOMBRE"]])){
							$stid = oci_parse($conn, "begin PAQ_INSERT.P_CREAR_REQUERIMIENTOS(:nombre,:vram,:vdisco,:vgrafica,:vprocesador,:VSO);end;");
										
							oci_bind_by_name ( $stid, ":nombre", $_POST ["nombre"] );
							oci_bind_by_name ( $stid, ":vram", $ram );
							oci_bind_by_name ( $stid, ":vdisco", $disco );
							oci_bind_by_name ( $stid, ":vprocesador", $procesador );
							oci_bind_by_name ( $stid, ":vgrafica", $grafica );
							oci_bind_by_name ( $stid, ":VSO", $so["IDSISTEMAOPERATIVO"] );
							oci_execute($stid);
							
							}
							
						}
						
						
						
						
						
						
						foreach ($_POST["etiquetas"] as $etiqueta){
							$stid = oci_parse($conn, "begin PAQ_INSERT.P_CREAR_ETIQUETADO(:juego,:nombrec);end;");
							
							oci_bind_by_name ( $stid, ":juego", $_POST ["nombre"] );
							oci_bind_by_name ( $stid, ":nombrec", $etiqueta );
							oci_execute($stid);
						}
						
						
							
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
	Fecha de lanzamiento:<input type="text" id="datepicker" name="fecha"> <br>
	Precio <input name="precio" id="precio" value=1000> descuento <input
		name="descuento" id="descuento" value=0> genero <input
		id="buscador-genero" name="genero"> desarrolladora <input
		id="buscador-dessarrolladoras" name="desarrolladora"> <br> Descripcion
	corta <br>
	<textarea name="descripcion" rows="10" cols="80" maxlength="3000"></textarea>
	<br>
	<textarea name="acerca" id="acerca" rows="10" cols="80">
            </textarea>
            		
			<input id="currentEtiqueta" type="hidden">
			<input id="etiquetas-buscador" ><input type="button" value="agregar" id="agregarcat" >
			<div id="etiquetas">

		</div>
            <?php 
$curs = oci_new_cursor($conn);
$stid = oci_parse($conn, "begin PAQ_SELECT.P_SELECT_ALL_SO(:cursbv); end;");
oci_bind_by_name($stid, ":cursbv", $curs, -1, OCI_B_CURSOR);
oci_execute($stid);
oci_execute($curs);



oci_fetch_all($curs, $sos, null, null, OCI_FETCHSTATEMENT_BY_ROW+OCI_ASSOC);
?>
<div id="tabs-games">
	<ul>
	<?php
	foreach ($sos as $so){
		echo "<li><a href='#tab-".$so["NOMBRE"]."'>".$so["NOMBRE"]."</a></li>";
	}
?>
	</ul>
	<?php foreach ($sos as $so){
		?>
		<div id='tab-<?php echo $so["NOMBRE"] ?>'>
		<p> RAM: <input name="ram-<?php echo $so["NOMBRE"] ?>" pattern="^([0-9]+)(T|G|M|K)B$" ></p>
		<p> DISCO: <input name="disco-<?php echo $so["NOMBRE"] ?>" pattern="^([0-9]+)(T|G|M|K)B$"></p>
		<p> GRAFICA: <input name="grafica-<?php echo $so["NOMBRE"] ?>"></p>
		<p> PROCESADOR: <input name="procesador-<?php echo $so["NOMBRE"] ?>"></p>
		</div>
		<?php
	}
	
	?>
	</div>
	
	
	<input type="submit" value="crear">
</form>
<?php
			break;
		case "update" :
			if ($_POST) {
				if (campoValido ( $_POST ["nombre"] ) and campoValido ( $_POST ["precio"] ) and campoValido ( $_POST ["fecha"] ) and campoValido ( $_POST ["descripcion"] ) and campoValido ( $_POST ["acerca"] ) and campoValido ( $_POST ["genero"] ) and campoValido ( $_POST ["desarrolladora"] )) {
					$stid = oci_parse ( $conn, "begin PAC_UPDATE.P_ACTUALIZAR_JUEGO(:vid,:NOMBRE,:PRECIO,:FECHA,:DESCUENTO,:ACERCA,:DESARRADOR,:GENERO,:DESCRIPCION); end;" );
					oci_bind_by_name ( $stid, "VID",$_POST ["juegoid"]);
					oci_bind_by_name ( $stid, ":NOMBRE", $_POST ["nombre"] );
					oci_bind_by_name ( $stid, ":PRECIO", $_POST ["precio"] );
					oci_bind_by_name ( $stid, ":FECHA", $_POST ["fecha"] );
					$decu = 0;
					oci_bind_by_name ( $stid, ":DESCUENTO", $_POST["descuento"] );
					oci_bind_by_name ( $stid, ":ACERCA", $_POST ["acerca"] );
					oci_bind_by_name ( $stid, ":DESARRADOR", $_POST ["desarrolladora"] );
					oci_bind_by_name ( $stid, ":GENERO", $_POST ["genero"] );
					oci_bind_by_name ( $stid, ":DESCRIPCION", $_POST ["descripcion"] );
					oci_execute ( $stid );
					
					
					
					///
					
					$curs = oci_new_cursor($conn);
					$stid = oci_parse($conn, "begin PAQ_SELECT.P_SELECT_ALL_SO(:cursbv); end;");
					oci_bind_by_name($stid, ":cursbv", $curs, -1, OCI_B_CURSOR);
					oci_execute($stid);
					oci_execute($curs);
					
					
					
					oci_fetch_all($curs, $sos, null, null, OCI_FETCHSTATEMENT_BY_ROW+OCI_ASSOC);
					
					foreach ($sos as $so){
						$ram="1GB";
						$disco="1GB!";
						$grafica="";
						$procesador="";
						if(campoValido($_POST["ram-".$so["NOMBRE"]])){
							$ram=$_POST["ram-".$so["NOMBRE"]];
						}
						if(campoValido($_POST["disco-".$so["NOMBRE"]])){
							$disco=$_POST["disco-".$so["NOMBRE"]];
						}
						if(campoValido($_POST["grafica-".$so["NOMBRE"]])){
							$grafica=$_POST["grafica-".$so["NOMBRE"]];
						}
						if(campoValido($_POST["procesador-".$so["NOMBRE"]])){
							$procesador=$_POST["procesador-".$so["NOMBRE"]];
						}
							
							
						if( campoValido($_POST["ram-".$so["NOMBRE"]]) and
								campoValido($_POST["disco-".$so["NOMBRE"]]) and
								campoValido($_POST["grafica-".$so["NOMBRE"]]) and
								campoValido($_POST["procesador-".$so["NOMBRE"]])){
									$stid = oci_parse($conn, "begin PAC_UPDATE.P_ACUTIALIZAR_REQUERIMIENTOS(:nombre,:vram,:vdisco,:vgrafica,:vprocesador,:VSO);end;");
					
									oci_bind_by_name ( $stid, ":nombre", $_POST ["nombre"] );
									oci_bind_by_name ( $stid, ":vram", $ram );
									oci_bind_by_name ( $stid, ":vdisco", $disco );
									oci_bind_by_name ( $stid, ":vprocesador", $procesador );
									oci_bind_by_name ( $stid, ":vgrafica", $grafica );
									oci_bind_by_name ( $stid, ":VSO", $so["IDSISTEMAOPERATIVO"] );
									oci_execute($stid);
										
						}
							
					}
					
					$curs = oci_new_cursor ( $conn );
					$stid = oci_parse ( $conn, "begin PAQ_SELECT.P_SELECT_ETIQUETAS_JUEGO(:vcursor,:vid); end;" );
					oci_bind_by_name ( $stid, ":vcursor", $curs, - 1, OCI_B_CURSOR );
					oci_bind_by_name ( $stid, ":vid", $_POST ["juegoid"] );
					oci_execute ( $stid );
					oci_execute ( $curs );
					array_unique($_POST["etiquetas"]);
					$etiquetaEnJuego=[];
					while ( ($row = oci_fetch_array ( $curs, OCI_ASSOC + OCI_RETURN_NULLS )) != false) {	
						$etiquetaEnJuego[]=$row["IDETIQUETA"];
					}
					$Aeliminar=array_diff($etiquetaEnJuego,$_POST["etiquetas"]);
					$Acrear=array_diff($_POST["etiquetas"],$etiquetaEnJuego);
				
					
					
					foreach ($Acrear as $etiqueta){
						$stid = oci_parse($conn, "begin PAQ_INSERT.P_CREAR_ETIQUETADO(:juego,:nombrec);end;");
							
						oci_bind_by_name ( $stid, ":juego", $_POST ["nombre"] );
						oci_bind_by_name ( $stid, ":nombrec", $etiqueta );
						oci_execute($stid);
					}
					foreach ($Aeliminar as $etiqueta){
						$stid = oci_parse($conn, "begin  PAC_ELIMINAR.P_ELIMINAR_ETIQUETADO(:juego,:etiqueta);end;");
							
						oci_bind_by_name ( $stid, ":juego", $_POST ["juegoid"] );
						oci_bind_by_name ( $stid, ":etiqueta", $etiqueta );
						oci_execute($stid);
					}
					//P_ELIMINAR_ETIQUETAD
					
					
				
					
					
					
					
				
				}
			}
			?>
<form method="post">
	<input type="hidden" id="juegoIDi" name="juegoid">
	<div id="juegoIDd"></div>
	<input id="buscador-juegou" name="juegonom"> <input type="button"
		value="buscar"
		onclick="updatejuego($('#buscador-juegou').attr('value'))"> Nombre:<input
		type="text" id="nombrej" name="nombre" >
	Fecha de lanzamiento:<input type="text" id="datepicker" name="fecha"> <br>
	Precio <input name="precio" id="precio" value=1000> descuento <input
		name="descuento" id="descuento" value=0> genero <input
		id="buscador-genero" name="genero"> desarrolladora <input
		id="buscador-dessarrolladoras" name="desarrolladora"> <br> Descripcion
	corta <br>
	<textarea name="descripcion" id="descripcion" rows="10" cols="80"
		maxlength="3000"></textarea>
	<br>
	<textarea name="acerca" id="acerca" rows="10" cols="80">
			</textarea>
			<input id="currentEtiqueta" type="hidden">
			<input id="etiquetas-buscador" ><input type="button" value="agregar" id="agregarcat" >
			<div id="etiquetas">

		</div>
			
			            <?php 
$curs = oci_new_cursor($conn);
$stid = oci_parse($conn, "begin PAQ_SELECT.P_SELECT_ALL_SO(:cursbv); end;");
oci_bind_by_name($stid, ":cursbv", $curs, -1, OCI_B_CURSOR);
oci_execute($stid);
oci_execute($curs);

oci_fetch_all($curs, $sos, null, null, OCI_FETCHSTATEMENT_BY_ROW+OCI_ASSOC);
?>
<div id="tabs-games">
	<ul>
	<?php
	foreach ($sos as $so){
		echo "<li><a href='#tab-".$so["NOMBRE"]."'>".$so["NOMBRE"]."</a></li>";
	}
?>
	</ul>
	<?php foreach ($sos as $so){
		?>
		<div id='tab-<?php echo $so["NOMBRE"] ?>'>
		<p> RAM: <input  id="ram-<?php echo $so["NOMBRE"] ?>" name="ram-<?php echo $so["NOMBRE"] ?>" pattern="^([0-9]+)(T|G|M|K)B$" ></p>
		<p> DISCO: <input id="disco-<?php echo $so["NOMBRE"] ?>" name="disco-<?php echo $so["NOMBRE"] ?>" pattern="^([0-9]+)(T|G|M|K)B$"></p>
		<p> GRAFICA: <input id="grafica-<?php echo $so["NOMBRE"] ?>" name="grafica-<?php echo $so["NOMBRE"] ?>"></p>
		<p> PROCESADOR: <input id="procesador-<?php echo $so["NOMBRE"] ?>" name="procesador-<?php echo $so["NOMBRE"] ?>"></p>
		</div>
		<?php
	}
	
	?>
	</div>
			
	<input type="submit" value="actualizar">
</form>
<?php
			
			break;
case "eliminar":
	if ($_POST) {
		if (campoValido ( $_POST ["juegoid"] )) {
			$stid = oci_parse($conn, "begin  PAC_ELIMINAR.P_ELIMINAR_JUEGO(:juego);end;");
				
			oci_bind_by_name ( $stid, ":juego", $_POST ["juegoid"] );
			ociexecute($stid);
		}
	}
			
	?>
	<form method="post">
	<input type="hidden" id="juegoIDi" name="juegoid">
	<div id="juegoIDd"></div>
	<input id="buscador-juegou" name="juegonom"> <input type="button"
		value="buscar"
		onclick="updatejuego($('#buscador-juegou').attr('value'))"> Nombre:<input
		type="text" id="nombrej" disabled="disabled" name="nombre" >
		<input type="submit" value="borrar">		
	</form>
	<?php
	
	break;

	}
}
?>
