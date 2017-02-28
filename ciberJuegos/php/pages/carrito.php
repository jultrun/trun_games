<?php
require_once 'php/funtions.php';
$title="carrito";
echo str_replace ( "##titulo##", $title, ob_get_clean () );
$precioTotal = 0;
if (isset ( $_SESSION ["carrito"] )) {
	if (count ( $_SESSION ["carrito"] ) > 0) {
		
		$_SESSION ["carrito"] = array_unique ( $_SESSION ["carrito"] );
		
		foreach ( $_SESSION ["carrito"] as $juegoId ) {
			$curs = oci_new_cursor ( $conn );
			$stid = oci_parse ( $conn, "begin PAQ_SELECT.P_SELECT_ONE_JUEGO(:cursbv,:id); end;" );
			oci_bind_by_name ( $stid, ":cursbv", $curs, - 1, OCI_B_CURSOR );
			oci_bind_by_name ( $stid, ":id", $juegoId );
			oci_execute ( $stid );
			oci_execute ( $curs );
			if (($row = oci_fetch_array ( $curs, OCI_ASSOC + OCI_RETURN_NULLS )) != false) {
				$precioTotal = $precioTotal + $row ["PRECIODESC"];
				?>
<div><?php echo $row['NOMBREJUEGO']." ".$row["PRECIODESC"]; ?></div>
<?php
			}
		}
		echo $precioTotal;
		?>
<form action="" method="post">
	<input type="hidden" name="hidden"> <input type="submit"
		value="comprar">
</form>
<?php
		
		if ($_POST) {
			$stid = oci_parse ( $conn, "begin :r := UTILIDADES.calcularCuenta(:usuario,:precio); end;" );
			oci_bind_by_name ( $stid, ":usuario", $_SESSION ["id"] );
			oci_bind_by_name ( $stid, ":r", $cuentaFinal, 40 );
			oci_bind_by_name ( $stid, ":precio", $precioTotal );
			ociexecute ( $stid );
			
			if ($cuentaFinal > 0) {
				
				$stid = oci_parse ( $conn, "begin PAQ_INSERT.P_CREAR_FACTURA(:vid,:usuario); end;" );
				oci_bind_by_name ( $stid, ":vid", $facturaId );
				oci_bind_by_name ( $stid, ":usuario", $_SESSION ["id"] );
				oci_execute ( $stid );
				
				foreach ( $_SESSION ["carrito"] as $juegoId ) {
					$curs = oci_new_cursor ( $conn );
					$stid = oci_parse ( $conn, "begin PAQ_SELECT.P_SELECT_ONE_JUEGO(:cursbv,:id); end;" );
					oci_bind_by_name ( $stid, ":cursbv", $curs, - 1, OCI_B_CURSOR );
					oci_bind_by_name ( $stid, ":id", $juegoId );
					oci_execute ( $stid );
					oci_execute ( $curs );
					if (($row = oci_fetch_array ( $curs, OCI_ASSOC + OCI_RETURN_NULLS )) != false) {
						$stid = oci_parse ( $conn, "begin PAQ_INSERT.P_CREAR_DET_FACTURA(:vid,:juego); end;" );
						oci_bind_by_name ( $stid, ":vid", $facturaId );
						oci_bind_by_name ( $stid, ":juego", $row ["IDJUEGO"] );
						oci_execute ( $stid );
					}
					
					
				}
				$_SESSION ["carrito"] = array ();
				
				$stid = oci_parse ( $conn, "begin PAC_UPDATE.P_REDUCIRCUENTA(:vid,:vcuenta); end;" );
				oci_bind_by_name ( $stid, ":vid", $_SESSION ["id"]);
				oci_bind_by_name ( $stid, ":vcuenta", $precioTotal);
				oci_execute($stid);
				
				header( "Location:".$_SERVER['PHP_SELF']."?".http_build_query($_GET) );
				
			} else {
				echo "no tienes suficiente dinero en tu cuenta";
			}
		}
	} else {
		echo "no tienes juegos agregados";
	}
}

?>
