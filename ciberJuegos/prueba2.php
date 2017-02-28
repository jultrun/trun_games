<?php
$term = trim(strip_tags($_POST['term']));
$arr = array('12131','13212','142123','43122','a'.$term);


$conn = oci_connect('TESTPHP', '123456', 'localhost/XE');
if (!$conn) {
	$e = oci_error();
	trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
}



$curs = oci_new_cursor($conn);
$stid = oci_parse($conn, "begin myproc(:cursbv); end;");
oci_bind_by_name($stid, ":cursbv", $curs, -1, OCI_B_CURSOR);
oci_execute($stid);

oci_execute($curs);  // Ejecutar el REF CURSOR como un ide de sentencia normal

while (($row = oci_fetch_array($curs, OCI_ASSOC+OCI_RETURN_NULLS)) != false) {
	$row['ID']=htmlentities(stripslashes($row['ID']));
	$row['NOM']=htmlentities(stripslashes($row['NOM']));
	$row_set[] = $row;
}
echo json_encode($row_set);











?>