<?php
require($_SERVER['DOCUMENT_ROOT'].'/public/config_top.php');
require($_SERVER['DOCUMENT_ROOT'].'/public/function.php');
$code=strtolower(par($_POST['code']));

$v=par($_POST['v']);
$vname=xaReturnKeyVarname($v);
if(strlen($code)>=5){
	if($_SESSION[$vname]==$code)
	{
		echo '<img src="/images/ok.png">';
	}else{
		echo '<img src="/images/no.png">';
	}
}

?>