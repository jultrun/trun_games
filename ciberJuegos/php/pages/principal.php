<?php 
echo str_replace("##titulo##", "ciber juegos", ob_get_clean());
$curs = oci_new_cursor($conn);
$stid = oci_parse($conn, "begin PAQ_SELECT.P_SELECT_JUEGOS(:cursbv); end;");
oci_bind_by_name($stid, ":cursbv", $curs, -1, OCI_B_CURSOR);
oci_execute($stid);
oci_execute($curs);
echo "<h1>juegos mas vendidos</h1>";?>
<div>
<?php
while (($row = oci_fetch_array($curs, OCI_ASSOC+OCI_RETURN_NULLS)) != false) {
	echo "<div>".trim(strip_tags($row['NOMBREJUEGO']))."</div>";
}

echo "<h1>etiquetas populares</h1>";


$curs = oci_new_cursor($conn);
$stid = oci_parse($conn, "begin PAQ_SELECT.P_SELECT_ETIQUETASP(:cursbv); end;");
oci_bind_by_name($stid, ":cursbv", $curs, -1, OCI_B_CURSOR);
oci_execute($stid);
oci_execute($curs);
while (($row = oci_fetch_array($curs, OCI_ASSOC+OCI_RETURN_NULLS)) != false) {
	echo "<div>".trim(strip_tags($row['NOMBREETIQUETA']))."</div>";
}
echo "<h1>generos populares</h1>";


$curs = oci_new_cursor($conn);
$stid = oci_parse($conn, "begin PAQ_SELECT.P_SELECT_GENEROSP(:cursbv); end;");
oci_bind_by_name($stid, ":cursbv", $curs, -1, OCI_B_CURSOR);
oci_execute($stid);
oci_execute($curs);
while (($row = oci_fetch_array($curs, OCI_ASSOC+OCI_RETURN_NULLS)) != false) {
	echo "<div>".trim(strip_tags($row['NOMBREGENERO']))."</div>";
}





?>

</div>