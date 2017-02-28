<?php
require_once 'C:\xampp\htdocs\ciberJuegos/php/configuracion.php';
$id=trim(strip_tags($_POST['id']));
$curs = oci_new_cursor($conn);
$stid = oci_parse($conn, "begin PAQ_SELECT.P_SELECT_ONE_DESARROLADOR(:cursbv,:id); end;");
oci_bind_by_name($stid, ":cursbv", $curs, -1, OCI_B_CURSOR);
oci_bind_by_name($stid, ":id", $id);
oci_execute($stid);
oci_execute($curs);
$row_set = array ();
if (($row = oci_fetch_array($curs, OCI_ASSOC+OCI_RETURN_NULLS)) != false) {
	$row_set [] = $row;
}
echo json_encode ( $row_set );
?>