<?php 
//普通类型1
if($ok && (par($strs[9])&&par($strs[11])&&par($strs[18])) )
{	
	$number++;$pi++;
	if($pi==4){$pi=1;echo '<div class="PageNext"></div>';}//分页
?>
    <div style="margin-top:30px; padding:20px; font-size:25px; line-height:40px;height:270px; font-weight:bold">
    CHINA (P.R.C)
    <br>收件人：<?=par($strs[9])?>    
    <br>电话：<?=str_ireplace("’",'',par($strs[11]))?>
    <br>地址：<?=par($strs[14])?><?=par($strs[15])?><?=par($strs[16])?><?=par($strs[18])?>
    <br>邮编：<?=par($strs[17])?>
    </div>
<?php }?>
