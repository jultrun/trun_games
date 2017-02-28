<?php
require_once '../configuracion.php';
$id = trim ( strip_tags ( $_POST ['id'] ) );
if (! empty ( $id )) {
	$curs = oci_new_cursor ( $conn );
	$stid = oci_parse ( $conn, "begin PAQ_SELECT.P_SELECT_ETIQUETAS_JUEGO(:vcursor,:vid); end;" );
	oci_bind_by_name ( $stid, ":vcursor", $curs, - 1, OCI_B_CURSOR );
	oci_bind_by_name ( $stid, ":vid", $id );
	oci_execute ( $stid );
	oci_execute ( $curs );
	$row_set = array ();
	while ( ($row = oci_fetch_array ( $curs, OCI_ASSOC + OCI_RETURN_NULLS )) != false) {
		$row_set [] = $row;
	}
	echo json_encode ( $row_set );
}
?>