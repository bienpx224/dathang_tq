<?php 
$classtype=3;//分类类型

$typ=par($_REQUEST['typ']);
$number=par($_REQUEST['number']);
$lotno=par($_REQUEST['lotno']);
$weightRepeat=par($_REQUEST['weightRepeat']);
$sm_op1=spr($_REQUEST['sm_op1']);
$sm_op2=spr($_REQUEST['sm_op2']);
$sm_op3=spr($_REQUEST['sm_op3']);
$sm_op4=spr($_REQUEST['sm_op4']);
$sm_op5=spr($_REQUEST['sm_op5']);
$sm_op6=spr($_REQUEST['sm_op6']);
$ydid=spr($_REQUEST['ydid']);
   
$classid=GetEndArr($_REQUEST['classid'.$classtag.$classtype]);
if(!CheckEmpty($classid)){$classid=par($_REQUEST['classid']);}//不能用spr

$search="&typ={$typ}&number={$number}&lotno={$lotno}&sm_op1={$sm_op1}&sm_op2={$sm_op2}&sm_op3={$sm_op3}&sm_op4={$sm_op4}&sm_op5={$sm_op5}&sm_op6={$sm_op6}&ydid={$ydid}&classid={$classid}&weightRepeat={$weightRepeat}";
$alert_color='gray_prompt';

//通用搜索条件------------------------------------------------------------------------------------------
if($typ)
{
	$where='1=1';$so=0;
	if(CheckEmpty($lotno)){$where.=" and lotno='{$lotno}'";$so=1;}
	if(CheckEmpty($classid)){$where.=" and classid='{$classid}'";$so=1;}
	if(!$so){$so_ppt='选择批次号/托盘号后再搜索';}
}


//添加运单------------------------------------------------------------------------------------------
if($typ=='add'&&$number)
{
	$err=0;
	if(!$err&&!$lotno&&!$classid){$err=1;$ppt='请选择批次号/托盘号！';}
	if(!$err&&$sm_op5&&!$weightRepeat){$err=1;$ppt='请复称！';}

	if(!$err)
	{
		//搜索运单
		$yd=FeData('yundan','ydid,lotno,classid,warehouse,channel,s_shenfenhaoma,pay,weightRepeat,weight',"ydh='{$number}' or dsfydh='{$number}' or gnkdydh='{$number}' or hscode='{$number}' ");
		$ydid=$yd['ydid'];
		$yd['s_shenfenhaoma']=cadd($yd['s_shenfenhaoma']);
		$warehouseID=$yd['warehouse'];
	}


	
	if(!$err&&!$ydid){$err=1;$ppt='未找到运单！';}
	if(!$err&&warehouse_per('ts',$yd['warehouse'],1)){$err=1;$ppt='无权操作该仓库运单！';}
	if(!$err&&$sm_op6)
	{
		$joint='warehouse_'.$yd['warehouse'].'_weightRepeat';	$weightRepeat_limit=$$joint;
		$differ=spr($weightRepeat-$yd['weight']);
		if(abs($differ)>=$weightRepeat_limit){$err=1;$ppt="复称相差{$differ}{$XAwt} (超过仓库限制{$weightRepeat_limit}{$XAwt})";}
	}
	if(!$err&&$sm_op1&&$yd['lotno']){$err=2;$ppt='该运单已有批次号！';}
	if(!$err&&$sm_op4&&$yd['classid']){$err=2;$ppt='该运单已有托盘号！';}
	if(!$err&&$sm_op3&&!$yd['pay']){$err=1;$ppt='该运单未付款！';}
	if(!$err&&$sm_op2&&$yd['s_shenfenhaoma']&&channelPar($yd['warehouse'],$yd['channel'],'shenfenzheng'))
	{
		$num=mysqli_num_rows($xingao->query("select ydid from yundan where  {$where} and s_shenfenhaoma='{$yd['s_shenfenhaoma']}' and s_shenfenhaoma<>'LATE'"));
		if($num>0){$err=1;$ppt='该批次号/托盘号已有重复证件号的运单！';}
	}
	
	if(!$err)
	{
		$save="ydid=ydid";
		if(CheckEmpty($lotno)){$save.=",lotno='{$lotno}'";$save_ok=1;}
		if(CheckEmpty($classid)){$save.=",classid='{$classid}'";$save_ok=1;}
		if(CheckEmpty($weightRepeat)){$save.=",weightRepeat='".spr($weightRepeat)."'";}
		if($save_ok){$save.=",edittime=".time();}

		$xingao->query("update yundan set {$save} where ydid='{$ydid}'");SQLError('添加运单');$ppt='添加成功！';
		music('yes');
	}else{
		if($err==2)
		{
			$xingao->query("update yundan set edittime=".time()." where ydid='{$ydid}'");SQLError('更新运单');
		}
		
		$alert_color='red';
		music('no');
	}
	
}

//生成批次号------------------------------------------------------------------------------------------
elseif($typ=='generate')
{
	$letter=Cumulative('auto_lotno','d');//获取当前累积数字
	$letter=strtoupper(chr($letter+96));//按数字转成字母并转大写
	$number_str=$letter.substr(date('Y'),-2).date('m').date('d');//生成完成的批次号码
	$save['field']="number_str,`types`, `checked`, `addtime`";
	$save['value']="'{$number_str}','2','1',".time();
	
	$xingao->query("insert into hscode (".$save['field'].") values(".$save['value'].")");
	SQLError('生成批次号');
}

//删除运单------------------------------------------------------------------------------------------
elseif($typ=='del'&&$ydid)
{
	$xingao->query("update yundan set lotno='',classid='0' where ydid='{$ydid}'");SQLError('取出运单');
}
?>


