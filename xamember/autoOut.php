<?php
@session_start();
if(!$_COOKIE["member_cookie"]||!$_SESSION['member']['userid'])
{
	echo '<script language=javascript>';
	echo 'top.location.href="/xamember/login_save.php?lx=logout";';
	echo '</script>';
}
?>
<script>setTimeout("location.href=''",60*1000)</script><!--//毫秒转秒 -->
