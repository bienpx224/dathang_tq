<?php 
$typ=par($_REQUEST['typ']);
$yd_number=par($_REQUEST['yd_number']);
$barcode=add($_REQUEST['barcode']);
$number=spr($_REQUEST['number']);
$sm_op1=spr($_REQUEST['sm_op1']);
$sm_op2=spr($_REQUEST['sm_op2']);
$sm_op3=spr($_REQUEST['sm_op3']);
$ydid=spr($_REQUEST['ydid']);
$gdid=spr($_REQUEST['gdid']);

$search="&typ={$typ}&barcode={$barcode}&number={$number}&yd_number={$yd_number}&sm_op1={$sm_op1}&sm_op2={$sm_op2}&sm_op3={$sm_op3}&ydid={$ydid}";
$alert_color='gray_prompt';

$where_yd="ydid='{$yd_number}' or ydh='{$yd_number}' or dsfydh='{$yd_number}' or gnkdydh='{$yd_number}' or hscode='{$yd_number}'";

//添加运单------------------------------------------------------------------------------------------
if($typ=='add'&&$barcode)
{
	$err=0;
	if(!$err&&!$yd_number){$err=1;$ppt='请填写运单！';}
	if(!$err&&!$number){$err=1;$ppt='请填写数量！';}
	if(!$err)
	{
		//搜索运单
		$yd=FeData('yundan','ydid,ydh,goodsdata,warehouse,channel,pay',$where_yd);
		$ydid=$yd['ydid'];
		$yd['s_shenfenhaoma']=cadd($yd['s_shenfenhaoma']);
	}
	
	if(!$err&&!$ydid){$err=1;$ppt='未找到运单！';}
	if(!$err&&warehouse_per('ts',$yd['warehouse'],1)){$err=1;$ppt='无权操作该仓库运单！';}
	if(!$err&&$sm_op3&&!$yd['pay']){$err=1;$ppt='该运单未付款！';}
	if(!$err)
	{
		$gd=FeData('gd_japan','gdid,checked',"barcode='{$barcode}'");
		$yd['goodsdata']=cadd($yd['goodsdata']);
		$yd_gdid=yundan_goodsdata($yd['goodsdata'],'',1);//获取全部ID
		
		if(!$err&&!$gd['gdid']){
			$err=1;$ppt='未找到该条码商品 (请在新窗口<a href="/xingao/gd_japan/form.php" target="_blank">添加资料</a>,再重新扫描)！';
			
			echo '<script language=javascript>';
			echo 'window.open("/xingao/gd_japan/form.php")';
			echo '</script>';
		}
		if(!$err&&$yd_gdid&&have($yd_gdid,$gd['gdid'],1)){$err=1;$ppt='该运单已加过该商品条码！';}
		if(!$err&&$sm_op1&&!$gd['checked']){$err=1;$ppt='【日本清关资料】中的该商品不可用！';}
	}

	if(!$err)
	{
		$goodsdata=$yd['goodsdata'].'|||'.$gd['gdid'].':::'.$number; 
		$goodsdata=add(DelStr($goodsdata,'|||',1));
		$xingao->query("update yundan set goodsdata='{$goodsdata}',edittime=".time()." where ydid='{$ydid}'");SQLError('添加运单');$ppt='添加成功！';
		music('yes');
	}else{
		$alert_color='red';
		music('no');
	}
	
}


//删除运单------------------------------------------------------------------------------------------
elseif($typ=='del'&&$ydid&&$gdid)
{
	$yd=FeData('yundan','ydid,goodsdata',"ydid='{$ydid}'");
	$yd['goodsdata']=cadd($yd['goodsdata']);
	if($yd['goodsdata']&&$yd['ydid'])
	{
		$ydid=$yd['ydid'];
		$gd=yundan_goodsdata($yd['goodsdata'],$gdid,0);//获取资料
		$goodsdata=add(ArrDel($yd['goodsdata'],$gd,1,'|||'));//删除数组
		$xingao->query("update yundan set goodsdata='{$goodsdata}' where ydid='{$ydid}'");SQLError('取出运单');
	}
}
?>


