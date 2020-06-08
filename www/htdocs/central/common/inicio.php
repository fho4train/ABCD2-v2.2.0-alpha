<?php
global $Permiso, $arrHttp,$valortag,$nombre;
$arrHttp=Array();
session_start();
include("get_post.php");
//echo "arrHttp_dbpath=". $arrHttp["db_path"]."<BR>";
if (isset($arrHttp["db_path"]))
	$_SESSION["DATABASE_DIR"]=$arrHttp["db_path"];
require_once ("../config.php");
require_once ("ldap.php");
//foreach ($arrHttp as $var=>$value) echo "$var=$value<br>";die;
$valortag = Array();

function CambiarPassword($Mfn,$new_password){
global $xWxis,$Wxis,$db_path,$wxisUrl,$MD5;
	if (isset($MD5) and $MD5==1 ){
		$new_password=md5($new_password);
	}
	$ValorCapturado="d30<30 0>".$new_password."</30>";
	$ValorCapturado=urlencode($ValorCapturado);
	$IsisScript=$xWxis."actualizar.xis";
  	$query = "&base=acces&cipar=$db_path"."par/acces.par&login=".$_SESSION["login"]."&Mfn=" . $Mfn."&Opcion=actualizar&ValorCapturado=".$ValorCapturado;
    echo $query;
    include("wxis_llamar.php");
}
function LeerRegistro() {
// la variable $llave permite retornar alguna marca que est� en el formato de salida
// identificada entre $$LLAVE= .....$$

$llave_pft="";
global $llamada, $valortag,$maxmfn,$arrHttp,$OS,$Bases,$xWxis,$Wxis,$Mfn,$db_path,$wxisUrl,$MD5;
    $ic=-1;
	$tag= "";
	$IsisScript=$xWxis."login.xis";
	$pass=$arrHttp["password"];
	if (isset($MD5) and $MD5==1){
		$pass=md5($pass);
	}
	$query = "&base=acces&cipar=$db_path"."par/acces.par"."&login=".$arrHttp["login"]."&password=".$pass;
//        echo "query=$query<BR>";die;
	include("wxis_llamar.php");
//echo " contenido=" ; var_dump($contenido);
	 foreach ($contenido as $linea){

	 	if ($ic==-1){
	    	$ic=1;
	    	$pos=strpos($linea, '##LLAVE=');
	    	if (is_integer($pos)) {
	     		$llave_pft=substr($linea,$pos+8);
	     		$pos=strpos($llave_pft, '##');
	     		$llave_pft=substr($llave_pft,0,$pos);
			}
		}else{
			$linea=trim($linea);
			$pos=strpos($linea, " ");
			if (is_integer($pos)) {
				$tag=trim(substr($linea,0,$pos));
	//
	//El formato ALL env�a un <br> al final de cada l�nea y hay que quitarselo
	//
				$linea=rtrim(substr($linea, $pos+2,strlen($linea)-($pos+2)-5));
				if (!isset($valortag[$tag])) $valortag[$tag]=$linea;
			}
		}

	}
//echo " llavepft=$llave_pft<BR>" ; die;
	return $llave_pft;

}

function VerificarUsuario(){
	
	
  Global $arrHttp,$valortag,$Path,$xWxis,$session_id,$Permiso,$msgstr,$db_path,$nombre,$Per,$adm_login,$adm_password;
 	$llave=LeerRegistro();
echo " llave=" ; var_dump($llave); //die;

 	if ($llave!=""){
  		$res=explode('|',$llave);
  		$userid=$res[0];
  		$_SESSION["mfn_admin"]=$res[1];
  		$mfn=$res[1];
  		$nombre=$res[2];
		$arrHttp["Mfn"]=$mfn;
  		$Permiso="|";
  		$Per="";
  		$value=$valortag[40];
  		if (isset($valortag[60]))
  			$fecha=$valortag[60];
  		else
  			$fecha="";
  		$today=date("Ymd");
  		if (trim($fecha)!=""){
  			if ($today>$fecha){
  				header("Location: ../../index.php?login=N");
  				die;
  			}
  		}
  		$value=substr($value,2);
  		$ix=strpos($value,'^');
  		$Perfil=substr($value,0,$ix);
    	if (!file_exists($db_path."par/profiles/".$Perfil)){
    		echo "missing ". $db_path."par/profiles/".$Perfil;
    		die;
    	}
    	$profile=file($db_path."par/profiles/".$Perfil);
    	unset($_SESSION["profile"]);
    	unset($_SESSION["permiso"]);
    	unset($_SESSION["login"]);
    	$_SESSION["profile"]=$Perfil;
    	$_SESSION["login"]=$arrHttp["login"];
    	foreach ($profile as $value){
    		$value=trim($value);
    		if ($value!=""){
    			$key=explode("=",$value);
    			$_SESSION["permiso"][$key[0]]=$key[1];
    		}
    	}
        if (isset($valortag[70])){
        	$library=$valortag[70];
        	$_SESSION["library"]=$library;
        }else{
        	unset ($_SESSION["library"]);
        }
 	}else{
 		if ($arrHttp["login"]==$adm_login and $arrHttp["password"]==$adm_password){
 			$Perfil="adm";
 			unset($_SESSION["profile"]);
    		unset($_SESSION["permiso"]);
    		unset($_SESSION["login"]);
 			$profile=file($db_path."par/profiles/".$Perfil);
    		$_SESSION["profile"]=$Perfil;
    		$_SESSION["login"]=$arrHttp["login"];
    		foreach ($profile as $value){
    			$value=trim($value);

    			if ($value!=""){
    				$key=explode("=",$value);
    				$_SESSION["permiso"][$key[0]]=$key[1];
    			}
    		}
    	}else{
 			echo "<script>\n";
 			if (isset($_SESSION["HOME"]))
 				echo "self.location.href=\"".$_SESSION["HOME"]."?login=N\"\n";
 			else
 				echo "self.location.href=\"../../index.php?login=N\";\n";

 			echo "</script>\n";
  			die;
  		}
 	}
}


function LeerRegistroLDAP() {
// la variable $llave permite retornar alguna marca que est� en el formato de salida
// identificada entre $$LLAVE= .....$$

$llave_pft="";
global $llamada, $valortag,$maxmfn,$arrHttp,$OS,$Bases,$xWxis,$Wxis,$Mfn,$db_path,$wxisUrl,$MD5;
    $ic=-1;
	$tag= "";
	$IsisScript=$xWxis."loginLDAP.xis";
	
	
	$query = "&base=acces&cipar=$db_path"."par/acces.par"."&login=".$arrHttp["login"];
	include("wxis_llamar.php");
	
	 
	 foreach ($contenido as $linea){
        
	 	if ($ic==-1){
	    	$ic=1;

	    	$pos=strpos($linea, '##LLAVE=');
	    	if (is_integer($pos)) {
	     		$llave_pft=substr($linea,$pos+8);
	     		$pos=strpos($llave_pft, '##');
	     		$llave_pft=substr($llave_pft,0,$pos);
				
			}
			 
		}else{
			$linea=trim($linea);
			$pos=strpos($linea, " ");
			
			if (is_integer($pos)) {
				$tag=trim(substr($linea,0,$pos));
	//
	//El formato ALL env�a un <br> al final de cada l�nea y hay que quitarselo
	//linea
				 
				$linea=rtrim(substr($linea, $pos+2,strlen($linea)-($pos+2)-5));
				
				if (!isset($valortag[$tag])) $valortag[$tag]=$linea;
			}
		}
		
	}
	return $llave_pft;

}

function Session($llave)
{
   Global $arrHttp,$valortag,$Path,$xWxis,$session_id,$Permiso,$msgstr,$db_path,$nombre,$Per,$adm_login,$adm_password;
 				
 		$res=explode('|',$llave);
		//si el usuario no tiene pass pq es un usuario de ldap
		if($res[2] == ""){
		   $llave = "clave|".$llave;
		   $res=explode('|',$llave);
		}
  		$userid=$res[0];
  		$_SESSION["mfn_admin"]=$res[1];
  		$mfn=$res[1];
		$nombre=$res[2];
		$arrHttp["Mfn"]=$mfn;
  		$Permiso="|";
  		$Per="";
  		$value=$valortag[40];
  		if (isset($valortag[60]))
  			$fecha=$valortag[60];
  		else
  			$fecha="";
  		$today=date("Ymd");
  		if (trim($fecha)!=""){
  			if ($today>$fecha){
  				header("Location: ../../index.php?login=N");
  				die;
  			}
  		}
  		$value=substr($value,2);
  		$ix=strpos($value,'^');
  		$Perfil=substr($value,0,$ix);
    	if (!file_exists($db_path."par/profiles/".$Perfil)){
    		echo "missing ". $db_path."par/profiles/".$Perfil;
    		die;
    	}
    	$profile=file($db_path."par/profiles/".$Perfil);
    	unset($_SESSION["profile"]);
    	unset($_SESSION["permiso"]);
    	unset($_SESSION["login"]);
    	$_SESSION["profile"]=$Perfil;
    	$_SESSION["login"]=$arrHttp["login"];
    	foreach ($profile as $value){
    		$value=trim($value);
    		if ($value!=""){
    			$key=explode("=",$value);
    			$_SESSION["permiso"][$key[0]]=$key[1];
    		}
    	}
        if (isset($valortag[70])){
        	$library=$valortag[70];
        	$_SESSION["library"]=$library;
        }else{
        	unset ($_SESSION["library"]);
        }
 	
}


function LoginNLDAP()
{
	
Global $arrHttp,$valortag,$Path,$xWxis,$session_id,$Permiso,$msgstr,$db_path,$nombre,$Per,$adm_login,$adm_password;
 	
   if ($arrHttp["login"]==$adm_login and $arrHttp["password"]==$adm_password){
 			$Perfil="adm";
 			unset($_SESSION["profile"]);
    		unset($_SESSION["permiso"]);
    		unset($_SESSION["login"]);
 			$profile=file($db_path."par/profiles/".$Perfil);
    		$_SESSION["profile"]=$Perfil;
    		$_SESSION["login"]=$arrHttp["login"];
    		foreach ($profile as $value){
    			$value=trim($value);

    			if ($value!=""){
    				$key=explode("=",$value);
    				$_SESSION["permiso"][$key[0]]=$key[1];
    			}
    		}
    	}else{
 			echo "<script>\n";
 			if (isset($_SESSION["HOME"]))
 				echo "self.location.href=\"".$_SESSION["HOME"]."?login=N\"\n";
 			else
 				echo "self.location.href=\"../../index.php?login=N\";\n";

 			echo "</script>\n";
  			die;
  		}
}

function VerificarUsuarioLDAP(){
    Global $arrHttp,$valortag,$Path,$xWxis,$session_id,$Permiso,$msgstr,$db_path,$nombre,$Per,$adm_login,$adm_password;
 	
	
	//echo Auth($arrHttp["login"], $arrHttp["password"],false);
	try {
	
	         /*echo Auth($arrHttp["login"], $arrHttp["password"],false);
			 exit;*/
	
			$login = false;
			$llave=LeerRegistro();

			
			if($llave != ""){
		 		Session($llave);
				$login = true;
		    }  
			else
				{
					
					//Auth($arrHttp["login"], $arrHttp["password"],false);
					if(Auth($arrHttp["login"], $arrHttp["password"],false)){
						  $llave= LeerRegistroLDAP(); 
						  
						  if($llave != ""){
								 Session($llave);
								 $login = true;
							 }
					}
				}
								
			 
			 if(!$login)
				 LoginNLDAP();
	 
	 } catch (Exception $e) {
         echo $e->getMessage();
		 exit;
     }
	  
}

/////
/////   INICIO DEL PROGRAMA
/////


$query="";



//foreach ($arrHttp as $var => $value) echo "$var = $value<br>";
if (isset($arrHttp["base"]))
  $_SESSION["base"]=$arrHttp["base"] ;
else $_SESSION["base"]="acces";
$base=$_SESSION["base"];
//echo 'base='.$base;
//die;
if (isset($arrHttp["lang"])){
//if substr($arrHttp["lang"],1,9)="%EF%BB%BF" {$arrHttp["lang"]=substr($arrHttp["lang"],10,2);}

	$_SESSION["lang"]=$arrHttp["lang"];
	$lang=$arrHttp["lang"];
//echo 'lang='.$lang;
}else{
	if (!isset($_SESSION["lang"])) $_SESSION["lang"]=$lang;
}
include("../lang/dbadmin.php");
include("../lang/admin.php");
include("../lang/prestamo.php");
include("../lang/lang.php");
include("../lang/acquisitions.php");
	if (!isset($_SESSION["Expresion"])) $_SESSION["Expresion"]="";

	if (isset($arrHttp["login"])){
		
		global $use_ldap;	
		if($use_ldap)		
		VerificarUsuarioLDAP();
	else
		VerificarUsuario();
				
		$_SESSION["lang"]=$arrHttp["lang"];
		$_SESSION["login"]=$arrHttp["login"];
		$_SESSION["password"]=$arrHttp["password"];
		$_SESSION["nombre"]=$nombre;
//echo "Session=" ; var_dump($_SESSION); die;

	}
	if (!isset($_SESSION["permiso"])){
		$msg=$msgstr["invalidright"]." ".$msgstr[$arrHttp["startas"]];
		echo "
		<html>
		<body>
		<form name=err_msg action=error_page.php method=post>
		<input type=hidden name=error value=\"$msg\">
		";
		if (isset($arrHttp["newindow"]))
			echo "<input type=hidden name=newindow value=Y>
		";
		echo "
		</form>
		<script>
			document.err_msg.submit()
		</script>
		</body>
		</html>
		";
    	session_destroy();
    	die;
    }
	$Permiso=$_SESSION["permiso"];
	if (isset($arrHttp["Opcion"]) and $arrHttp["Opcion"]=="chgpsw"){
		CambiarPassword($arrHttp["Mfn"],$arrHttp["new_password"]);
		header("Location: ../../index.php?login=P");
	}else{
		include("homepage.php");
	}

