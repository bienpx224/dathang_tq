<?php 
$typ=par($_REQUEST['typ']);
$number=par($_POST['number']);
$epid=par($_REQUEST['epid']);
$sm_op1=par($_POST['sm_op1']);

$alert_color='gray_prompt';
//添加运单------------------------------------------------------------------------------------------
if($typ=='smt'&&$number)
{
	//搜索运单
	$yd=FeData('yundan','ydid,pay,warehouse',"ydh='{$number}' or dsfydh='{$number}' or gnkdydh='{$number}' or hscode='{$number}' ");
	$ydid=$yd['ydid'];
	
	//添加运单
	if($ydid)
	{
		if(!warehouse_per('ts',$yd['warehouse'],1))
		{
			if($sm_op1&&!$yd['pay'])
			{
				$alert_color='red';$ppt='该运单未支付！';
			}else{
				
				$num=mysqli_num_rows($xingao->query("select * from yundan_export_temp where ydid='{$ydid}' and userid='{$Xuserid}' "));
				
				if(!$num)
				{
					$xingao->query("insert into yundan_export_temp (`ydid`, `userid`, `addtime`) VALUES ('{$ydid}', '{$Xuserid}', '".time()."') ");SQLError('添加运单');$ppt='添加成功！';
				}else{
					$alert_color='red2';$ppt='该运单已添加过！';
				}
				
			}
		}else{
			$alert_color='red';$ppt='无权操作该仓库运单！';
		}
	}else{
		$alert_color='red';$ppt='未找到运单！';
	}
}

//删除运单------------------------------------------------------------------------------------------
if($typ=='del'&&$epid)
{
	$where="1=1";
	$where.=" and userid='{$Xuserid}'";
	if($epid!='all'){$where.=" and epid='{$epid}'";}
	$xingao->query("delete from yundan_export_temp where {$where} ");SQLError('删除运单');
}
?>


