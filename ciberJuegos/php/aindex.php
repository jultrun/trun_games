<?php
require_once 'php/funtions.php';
sendMail("jultrun121@hotmail.com", "mensaje", "was");

?>

<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Title of the document</title>
<link rel="stylesheet" type="text/css" href="./styles/style.css" />
</head>
<body>
<?php 
$TexTarea="<p>hola";
$TagsSeguras = array(
		"<p>" => "OpenNegrita",
		"</p>" => "CierraNegrita",
		"<img>" => "OpenImagen",
		"</img>" => "CierraImagen");
echo $TexTarea;



$conn = oci_connect('TESTPHP', '123456', 'localhost/XE');
if (!$conn) {
	$e = oci_error();
	trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
}




$sql = 'BEGIN sayHello(:message); END;';

$stmt = oci_parse($conn,$sql);


// Bind the output parameter
oci_bind_by_name($stmt,':message',$text,10000);



oci_execute($stmt);







$curs = oci_new_cursor($conn);
$stid = oci_parse($conn, "begin myproc(:cursbv); end;");
oci_bind_by_name($stid, ":cursbv", $curs, -1, OCI_B_CURSOR);
oci_execute($stid);

oci_execute($curs);  // Ejecutar el REF CURSOR como un ide de sentencia normal
echo "<table border='1'>\n";
while (($row = oci_fetch_array($curs, OCI_ASSOC+OCI_RETURN_NULLS)) != false) {
	echo "<tr>\n";
	
		echo "<td>" .$row['ID']. "</td><td>" .$row['NOM']. "</td>\n";
	
	echo "</tr>\n";
	
}
echo "</table>\n";




//echo $value1;
?>

	<header>
		<ul id="page-menu">
			<li><a href="default.asp">Home</a></li>
			<li><a href="news.asp">News</a></li>
			<li><a href="about.asp">About</a>
				<ul>
					<li><a href="">Submenu1</a></li>
					<li><a href="">Submenu2</a></li>
					<li><a href="">Submenu3</a></li>
					<li><a href="">Submenu4</a></li>
				</ul>
			</li>
			<li><a href="#">Sobre</a></li>
		</ul>
		<ul id="account-menu">
			<li><a href="about.asp">cuenta</a>
				<ul>
					<li><a href="">Submenu1</a></li>
					<li><a href="">Submenu2</a></li>
					<li><a href="">Submenu3</a></li>
					<li><a href="">Submenu4</a></li>
				</ul>
			</li>
		</ul>
	<input id="project">
	</header>
	<article>
		<section><?php
		$text=strip_tags($text, '</*html></*head></*body></*header></*section></*article>');
		echo $text ?>
		</section>
	</article>
</body>

</html>