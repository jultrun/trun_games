<?php
require_once '../configuracion.php';
$nom = trim ( strip_tags ( $_POST ['term'] ) );
if (! empty ( $nom )) {
	$curs = oci_new_cursor ( $conn );
	$stid = oci_parse ( $conn, "begin PAQ_SELECT.P_SELECT_GENEROS_LIKE(:cursbv,:nom); end;" );
	oci_bind_by_name ( $stid, ":cursbv", $curs, - 1, OCI_B_CURSOR );
	
	oci_bind_by_name ( $stid, ":nom", $nom );
	oci_execute ( $stid );
	oci_execute ( $curs );
	$row_set = array ();
	$i=0;
	while ( ($row = oci_fetch_array ( $curs, OCI_ASSOC + OCI_RETURN_NULLS )) != false and $i<10) {
		$row_set [] = $row;
		$i++;
	}
	echo json_encode ( $row_set );
}
?>