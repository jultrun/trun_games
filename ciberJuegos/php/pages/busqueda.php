<?php
require_once 'php/funtions.php';
$title = "busqueda";
echo str_replace ( "##titulo##", $title, ob_get_clean () );
// si hay termino de busqueda
?>
<form action="index.php" id="">
	<input type="hidden" name="cat" value="search"> <input name="term">
	<input type="submit" value="buscar">
	<br>
		
		<input id="currentEtiqueta" type="hidden">
			<input id="etiquetas-buscador" ><input type="button" value="agregar" id="agregarcat" >
			<div id="etiquetas">

		</div>
</form>
<?php
$etiquetasAFiltrar = array ();
if (isset ( $_GET ['etiquetas'] ) and ! empty ( $_GET ['etiquetas'] )) {
	foreach ( $_GET ['etiquetas'] as $etiqueta ) {
		array_push ( $etiquetasAFiltrar, $etiqueta );
	}
}
if (isset ( $_GET ['term'] ) and ! empty ( $_GET ['term'] )) {
	$term = trim ( strip_tags ( $_GET ['term'] ) );
	if (is_numeric ( $term )) {
		$search = strval ( $term );
	}
	
	//
	$filasPorPagina = 10;
	if (isset ( $_GET ['numfilas'] ) and ! empty ( $_GET ['numfilas'] )) {
		if (is_numeric ( $_GET ['numfilas'] )) {
			$filasPorPagina = $_GET ['numfilas'];
		}
	}
	
	$numeroPagina = 1;
	if (isset ( $_GET ['npage'] ) and ! empty ( $_GET ['npage'] )) {
		if (is_numeric ( $_GET ['npage'] )) {
			$numeroPagina = $_GET ['npage'];
		}
	}
	// numero total de paginas
	$curs = oci_new_cursor ( $conn );
	$stid = oci_parse ( $conn, "begin PAQ_SELECT.P_SELECT_JUEGO_LIKE(:cursbv,:term); end;" );
	oci_bind_by_name ( $stid, ":cursbv", $curs, - 1, OCI_B_CURSOR );
	oci_bind_by_name ( $stid, ":term", $term );
	oci_execute ( $stid );
	oci_execute ( $curs );
	$totalPaginas = oci_fetch_all ( $curs, $ress );
	
	$cursJuegos = oci_new_cursor ( $conn );
	$stid = oci_parse ( $conn, "begin PAQ_SELECT.P_SELECT_ALL_JUEGOS_PAGINACION(:cursbv,:cond,:fila,:maxfilas); end;" );
	oci_bind_by_name ( $stid, ":cursbv", $cursJuegos, - 1, OCI_B_CURSOR );
	oci_bind_by_name ( $stid, ":cond", $term );
	oci_bind_by_name ( $stid, ":fila", $numeroPagina );
	oci_bind_by_name ( $stid, ":maxfilas", $filasPorPagina );
	oci_execute ( $stid );
	oci_execute ( $cursJuegos );
	
	// oci_fetch_all ( $curs, $listajuegos, null, null, OCI_FETCHSTATEMENT_BY_ROW + OCI_ASSOC );
	$tieneEtiquetas = true;
	$tieneGenero = true;
	$listaJuegos = array ();
	while ( ($row = oci_fetch_array ( $cursJuegos, OCI_ASSOC + OCI_RETURN_NULLS )) != false ) {
		$tieneEtiquetas = true;
		
		$cursEtiqueta = oci_new_cursor ( $conn );
		$stid = oci_parse ( $conn, "begin PAQ_SELECT. P_SELECT_ETIQUETAS_JUEGO(:cursbv,:vid); end;" );
		oci_bind_by_name ( $stid, ":cursbv", $cursEtiqueta, - 1, OCI_B_CURSOR );
		oci_bind_by_name ( $stid, "vid", $row ["IDJUEGO"] );
		oci_execute ( $stid );
		oci_execute ( $cursEtiqueta );
		//oci_fetch_all ( $cursEtiqueta, $listaEtiquetas, null, null, OCI_FETCHSTATEMENT_BY_ROW + OCI_ASSOC );
		$listaEtiquetas=array();
		while ( ($rowEtiquetas = oci_fetch_array ( $cursEtiqueta, OCI_ASSOC + OCI_RETURN_NULLS )) != false){
			array_push($listaEtiquetas, $rowEtiquetas["IDETIQUETA"]);
		}
		
		if($etiquetasAFiltrar==array_intersect($etiquetasAFiltrar,$listaEtiquetas)){
			array_push($listaJuegos, $row);
		}
		
	}
	foreach ($listaJuegos as $juegos){?>
		<div class="item-game">
		<span><a href="index.php?cat=games&id=<?php echo $juegos["IDJUEGO"] ?>">
		<?php
		echo trim ( $juegos ["NOMBREJUEGO"]  );
		?>
			</a></span>
			<p><?php
				echo $juegos ["DESCJUEGO"];
				?>
			</p>
			<?php echo '$' . number_format ( $juegos['PRECIOJUEGO'], 0, ',', '.' );?>
			<?php echo '$' . number_format ( $juegos['PRECIODESC'], 0, ',', '.' );?>
			</div>
		<?php
		
	}
	echo pagination ( $totalPaginas, $filasPorPagina, $numeroPagina );
}

?>