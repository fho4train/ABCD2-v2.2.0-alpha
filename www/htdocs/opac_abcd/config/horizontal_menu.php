<?php
include ("tope_config.php");
/*
if (!isset($_REQUEST["db_path"])){	$_REQUEST["db_path"]=$db_path;
	$_REQUEST["db_path_desc"]="$db_path";}
if (isset($_REQUEST["db_path"])) {	$_SESSION["db_path"]=$_REQUEST["db_path"];
	$_SESSION["db_path_desc"]=$_REQUEST["db_path_desc"];
}
*/
if (isset($_REQUEST["lang"])) $_SESSION["lang"]=$_REQUEST["lang"];


//foreach ($_REQUEST AS $var=>$value) echo "$var=$value<br>"; //die;

/////////////////////////////////////////////////////////////////////

if (!isset($_SESSION["permiso"])){
	session_destroy();
	$msg=$msgstr["invalidright"]." ".$msgstr[$_REQUEST["startas"]];
	echo "
	<html>
	<body>
	<form name=err_msg action=error_page.php method=post>
	<input type=hidden name=error value=\"$msg\">
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
?>
<div id="page" style="margin-top:10px;padding:10px;">
<h3><?php echo $msgstr["horizontal_menu"]?> &nbsp; <a href=http://wiki.abcdonline.info/OPAC-ABCD_Apariencia#Agregar_enlaces_al_men.C3.BA_superior_horizontal target=_blank><img src=../images_config/helper_bg.png></a></h3>
<br>
<?php
$lang=$_REQUEST["lang"];
$Permiso=$_SESSION["permiso"];
if (isset($_REQUEST["Opcion"]) and $_REQUEST["Opcion"]=="Guardar"){
	$archivo=$db_path."opac_conf/$lang/".$_REQUEST["file"];
	$fout=fopen($archivo,"w");
	foreach ($_REQUEST as $var=>$value){
		$value=trim($value);
		if ($value!=""){
			$var=trim($var);
			$x=explode('_',$var);
			if ($x[0]=="lk"){				$link[$x[2]][$x[1]]=$value;			}


		}
	}
	ksort($link);
	foreach ($link as $l){		$salida=$l["nombre"]."|".$l["link"]."|";
		if (isset($l["nw"]) and $l["nw"]=="Y")
			$salida.=$l["nw"];
		if ($salida!="") fwrite($fout,$salida."\n");	}
	fclose($fout);
    echo "<p><font color=red>". "opac_conf/$lang/".$_REQUEST["file"]." ".$msgstr["updated"]."</font>";
}
?>

<?php

if (!isset($_REQUEST["Opcion"]) or $_REQUEST["Opcion"]!="Guardar"){
	$file="menu.info";
	echo "<form name=home"."Frm method=post onSubmit=\"return checkform()\">\n";
	echo "<input type=hidden name=db_path value=".$db_path.">";
	echo "<input type=hidden name=Opcion value=Guardar>\n";
   	echo "<input type=hidden name=file value=\"$file\">\n";
   	echo "<input type=hidden name=lang value=\"$lang\">\n";
   	if (isset($_REQUEST["conf_level"])){
		echo "<input type=hidden name=conf_level value=".$_REQUEST["conf_level"].">\n";
	}
	echo "<table cellpadding=5>";
	echo "<tr><th>".$msgstr["nombre"]."</th><th>".$msgstr["link"]."</th><th>".$msgstr["new_w"]."</th></tr>";
	if (file_exists($db_path."opac_conf/".$_REQUEST["lang"]."/$file")){		$fp=file($db_path."opac_conf/".$_REQUEST["lang"]."/$file");
	}else{		$fp=array();
		$fp[]='||';
		$fp[]='||';
		$fp[]='||';
		$fp[]='||';
		$fp[]='||';	}
	$ix=0;
	foreach ($fp as $value){		$value=trim($value);
		if ($value!=""){
			$ix=$ix+1;			$x=explode('|',$value);
			echo "<tr><td><input type=text size=20 name=lk_nombre_$ix value=\"".$x[0]."\"></td>";
			echo "<td><input type=text size=80 name=lk_link_$ix value=\"".$x[1]."\"></td>";
			echo "<td>&nbsp; &nbsp; &nbsp; <input type=checkbox name=lk_nw_$ix value=\"Y\"";
			if (isset($x[2]) and $x[2]=="Y") echo " checked";
			echo "></td>";
			echo"</tr>";		}
	}
	echo "<tr><td colspan=3 align=center>";
   	echo "<p><input type=submit value=\"".$msgstr["save"]."\"></td></tr>";
	echo "</table>";
	echo "</form>";
}
?>
</div>
<br>
<br>
<?php
include ("../php/footer.php");
?>
</div>
</div>
</body
</html>
