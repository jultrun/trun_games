<?php
require_once 'C:\xampp\htdocs\ciberJuegos/php/phpmailer/PHPMailerAutoload.php';
require_once 'C:\xampp\htdocs\ciberJuegos/php/configuracion.php';
session_start ();
function sendMail($destino, $asunto, $mensaje) {
	$mail = new PHPMailer ();
	// indico a la clase que use SMTP
	$mail->isSMTP ();
	$mail->setLanguage ( 'es' );
	$mail->CharSet = 'UTF-8';
	// permite modo debug para ver mensajes de las cosas que van ocurriendo
	$mail->SMTPDebug = 0;
	
	// Debo de hacer autenticación SMTP
	$mail->SMTPAuth = true;
	$mail->SMTPSecure = "tls";
	// indico el servidor de Gmail para SMTP
	$mail->Host = "smtp.gmail.com";
	// indico el puerto que usa Gmail
	$mail->Port = 587;
	// indico un usuario / clave de un usuario de gmail
	$mail->Username = "pruebasphpenvio@gmail.com";
	$mail->Password = "1234567890poiuytrewq";
	
	$mail->setFrom ( 'pruebasphpenvio@gmail.com', 'Nombre completo' );
	$mail->addReplyTo ( "pruebasphpenvio@gmail.com", 'Nombre completo' );
	// cuerpo y asunto del mensaje
	$mail->Subject = $asunto;
	
	$mail->msgHTML ( $mensaje );
	// indico destinatario
	$mail->addAddress ( $destino );
	if (! $mail->Send ()) {
		return false;
	} else {
		return true;
	}
}
function removeTags($text, $tags = "") {
	$text = strip_tags ( $text, '</*html></*head></*body></*header></*section></*article>' . $tags );
	return $text;
}
class userExist extends Exception {
}
class emailExist extends Exception {
}
function registrarUsuario($user, $pass, $email) {
	global $conn;
	
	$compU = oci_parse ( $conn, "begin PAQ_SELECT.P_COMPROBAR_USUARIO(:cuentaU,:nom); end;" );
	oci_bind_by_name ( $compU, ":cuentaU", $cuentaU, 1 );
	oci_bind_by_name ( $compU, ":nom", $user );
	
	oci_execute ( $compU );
	
	$compM = oci_parse ( $conn, "begin PAQ_SELECT.P_COMPROBAR_EMAIL(:cuentaE,:email); end;" );
	oci_bind_by_name ( $compM, ":cuentaE", $cuentaE, 1 );
	oci_bind_by_name ( $compM, ":email", $email );
	
	oci_execute ( $compM );
	

		$stid = oci_parse ( $conn, "begin PAQ_INSERT.P_REGISTRAR_USUARIO(:user,:pass,:email); end;" );
		oci_bind_by_name ( $stid, ":user", $user );
		oci_bind_by_name ( $stid, ":pass", $pass );
		oci_bind_by_name ( $stid, ":email", $email );
		if (oci_execute ( $stid )) {
			sendMail ( $email, 'felizidades ', 'usuario: ' . $user );
			return true;
		} else {
			return false;
		}
	
	if ($cuentaU > 0) {
		//throw new userExist ();
	}
	if ($cuentaE > 0) {
		//throw new emailExist ();
	}
}
function loginUsuario($user, $pass) {
	global $conn;
	$curs = oci_new_cursor ( $conn );
	$stid = oci_parse ( $conn, "begin PAQ_SELECT.P_LOGIN(:cursbv,:user,:pass); end;" );
	oci_bind_by_name ( $stid, ":cursbv", $curs, - 1, OCI_B_CURSOR );
	oci_bind_by_name ( $stid, ":user", $user );
	oci_bind_by_name ( $stid, ":pass", $pass );
	oci_execute ($stid);
	oci_execute ($curs);
	$row_set = array ();
	if(isset($curs)){
		if(($row = oci_fetch_array ( $curs, OCI_ASSOC + OCI_RETURN_NULLS )) != false ) {
			$_SESSION['id']=$row['IDUSUARIO'];
			$_SESSION['carrito']=array();
			
			return  true;
		}
	}else{
		return false;
	}
}
function addParametrosUrl($name,$value){
$url="http://";
$conector="?";

if (empty($_SERVER['QUERY_STRING']))
{
    $CLON_GET = array();
}
else
{
    //hacemos una copia de las variables recividas por get para no interferir otros proceso que usen el get
    $CLON_GET = $_GET;
}
//creamos la variable ord por via get
$CLON_GET[$name] = $value;
$link = $url.$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF'].$conector.http_build_query($CLON_GET); 
return $link;


}

function pagination($total_pages,$limit,$page){
	// How many adjacent pages should be shown on each side?
	$adjacents = 1;
	                               //how many items to show per page	
	if($page)
		$start = ($page - 1) * $limit;          //first item to display on this page
		else
			$start = 0;                             //if no page var is given, set start to 0
			/* Get data. */
			/* Setup page vars for display. */
			if ($page == 0) $page = 1;                  //if no page var is given, default to 1.
			$prev = $page - 1;                          //previous page is page - 1
			$next = $page + 1;                          //next page is page + 1
			$lastpage = ceil($total_pages/$limit);      //lastpage is = total pages / items per page, rounded up.
			$lpm1 = $lastpage - 1;                      //last page minus 1
	
			/*
			 Now we apply our rules and draw the pagination object.
			 We're actually saving the code to a variable in case we want to draw it more than once.
			 */
			$pagination = "";
			if($lastpage > 1)
			{
				$pagination .= "<div class=\"pagination\">";
				//previous button
				if ($page > 1)
					$pagination.= "<a href=\"".addParametrosUrl("npage",$prev)."\"><< previous</a>";
					else
						$pagination.= "<span class=\"disabled\">previous</span>";
	
						//pages
						if ($lastpage < 7 + ($adjacents * 2))   //not enough pages to bother breaking it up
						{
							for ($counter = 1; $counter <= $lastpage; $counter++)
							{
								if ($counter == $page)
									$pagination.= "<span class=\"current\">$counter</span>";
									else
										$pagination.= "<a  href=\"".addParametrosUrl("npage",$counter)."\">$counter</a>";
							}
	
	
						}
						elseif($lastpage > 5 + ($adjacents * 2))    //enough pages to hide some
						{
							//close to beginning; only hide later pages
							if($page < 1 + ($adjacents * 2))
							{
								for ($counter = 1; $counter < 4 + ($adjacents * 2); $counter++)
								{
									if ($counter == $page)
										$pagination.= "<span class=\"current\">$counter</span>";
										else
											$pagination.= "<a  href=\"".addParametrosUrl("npage",$counter)."\">$counter</a>";
								}
								$pagination.= "...";
								$pagination.= "<a href=\"".addParametrosUrl("npage", $lpm1)."\">$lpm1</a>";
								$pagination.= "<a href=\"".addParametrosUrl("npage", $lastpage)."\">$lastpage</a>";
							}
							//in middle; hide some front and some back
							elseif($lastpage - ($adjacents * 2) > $page && $page > ($adjacents * 2))
							{
								$pagination.= "<a href=\"".addParametrosUrl("npage", $value)."\">1</a>";
								$pagination.= "<a href=\"".addParametrosUrl("npage", 2)."\">1</a>";
								$pagination.= "...";
								for ($counter = $page - $adjacents; $counter <= $page + $adjacents; $counter++)
								{
									if ($counter == $page)
										$pagination.= "<span class=\"current\">$counter</span>";
										else
											$pagination.= "<a href=\"".addParametrosUrl("npage", $counter)."\">$counter</a>";
								}
								$pagination.= "...";
								$pagination.= "<a href=\"".addParametrosUrl("npage", $lpm1)."\">$lpm1</a>";
								$pagination.= "<a href=\"".addParametrosUrl("npage", $lastpage)."\">$lastpage</a>";
							}
							//close to end; only hide early pages
							else
							{
								$pagination.= "<a href=\"".addParametrosUrl("npage", 1)."\">1</a>";
								$pagination.= "<a href=\"".addParametrosUrl("npage", 2)."\">2</a>";
								$pagination.= "...";
								for ($counter = $lastpage - (2 + ($adjacents * 2)); $counter <= $lastpage; $counter++)
								{
									if ($counter == $page)
										$pagination.= "<span class=\"current\">$counter</span>";
										else
											$pagination.= "<a  href=\"".addParametrosUrl("npage",$counter)."\">$counter</a>";
	
								}
							}
						}
	
						//next button
						if ($page < $counter - 1)
							$pagination.= "<a href=\"".addParametrosUrl("npage", $next)."\">next >></a>";
							else
								$pagination.= "<span class=\"disabled\">next>></span>";
								$pagination.= "</div>\n";
			}
			return $pagination;
	
}
function getUsuario($id){
	global $conn;
	$curs = oci_new_cursor ( $conn );
	$stid = oci_parse ( $conn, "begin PAQ_SELECT.P_SELECT_USUARIO(:cursbv,:vid); end;" );
	oci_bind_by_name ( $stid, ":cursbv", $curs, - 1, OCI_B_CURSOR );
	oci_bind_by_name ( $stid, ":vid", $id );
	oci_execute ( $stid );
	oci_execute ( $curs );
	if(($row = oci_fetch_array ( $curs, OCI_ASSOC + OCI_RETURN_NULLS )) != false ) {		
		return  $row;
	}
}
function campoValido($campo){
	return (isset($campo) and !empty($campo));
}

?>