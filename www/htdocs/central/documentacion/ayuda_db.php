<?php
session_start();
include("../common/get_post.php");
include ("../config.php");
$lang=$_SESSION["lang"];
include("../lang/soporte.php");
include("../lang/admin.php");
include("../lang/lang.php");
//foreach ($arrHttp as $var=>$value) echo "$var=$value<br>";

if (!isset($_SESSION["permiso"])) {
	die;
unset($fp);
$archivo=$db_path.$arrHttp["base"]."/ayudas/".$_SESSION["lang"]."/".$arrHttp["campo"];

if (file_exists($archivo)){
}else{

	if (file_exists($archivo)){

}

if (!file_exists($archivo)){
	echo "<h4>".$arrHttp["base"]."/ayudas/".$_SESSION["lang"]."/".$arrHttp["campo"] ."</h4>";
	die;
if (isset($fp)){
		$value=str_replace('/php',$app_path.'/',$value);
		echo "$value\n";
	}
?>
</body>
</html>