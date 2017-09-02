<?php
@session_start();
if($_SESSION['entrance']!='xazy'||!$_COOKIE['manage_cookie']||!$_SESSION['manage']['userid'])
{
	echo '<script language=javascript>';
	echo 'top.location.href="/xingao/login_save.php?lx=logout";';
	echo '</script>';
}
?>
<script>setTimeout("location.href=''",60*1000)</script><!--//毫秒转秒 -->
