<?php
/*
软件著作权：=====================================================
软件名称：兴奥国际物流转运网站管理系统(简称：兴奥转运系统)V7.0
著作权人：广西兴奥网络科技有限责任公司
软件登记号：2016SR041223
网址：www.xingaowl.com
本系统已在中华人民共和国国家版权局注册，著作权受法律及国际公约保护！
版权所有，未购买严禁使用，未经书面许可严禁开发衍生品，违反将追究法律责任！
*/
require_once($_SERVER['DOCUMENT_ROOT'].'/public/config_top.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/public/html.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/public/function.php');
$pervar='yundan_sc';require_once($_SERVER['DOCUMENT_ROOT'].'/xingao/incluce/session.php');
$headtitle="扫描更新状态";
$alonepage=2;//1单页形式;2框架形式
require_once($_SERVER['DOCUMENT_ROOT'].'/xingao/incluce/head.php');
?>
<div class="page_ny"> 
  <!-- BEGIN PAGE HEADER-->
	<div><!--单页时去掉 class="row",不然会有横滚动条,并且form 加 style="margin:20px;"-->
		<div class="col-md-12"> 
<h3 class="page-title"> <?php if($alonepage!=1){?>
<a href="javascript:history.go(-1)" title="<?=$LG['back']?>"><i class="icon-reply"></i></a> <a href="list.php?status=all" class="gray" target="_parent"><?=$LG['backList']?></a> > <?php }?>

<a href="?" class="gray">重新扫描</a> 
</h3>
    </div>
  </div>
  <!-- END PAGE HEADER--> 

  <!-- BEGIN PAGE CONTENT-->
<?php 
//--------------------------------------------------------------------------------------------------------------------
//提交操作--------------------------------------------------------------------------------
$lx=par($_REQUEST['lx']);
$ydh=par($_REQUEST['ydh']);

if ($lx=='sm')
{
	//获取,验证 (正面用到$search,所以全部要先获取并且用$_REQUEST)
	$op_ydh=par($_REQUEST['op_ydh']);
	$op_dsfydh=par($_REQUEST['op_dsfydh']);
	$op_gwkdydh=par($_REQUEST['op_gwkdydh']);
	$op_gnkdydh=par($_REQUEST['op_gnkdydh']);
	$op_hscode=par($_REQUEST['op_hscode']);

	$fee=par($_REQUEST['fee']);
	$payment=par($_REQUEST['payment']);
	$s_shenfenhaoma=par($_REQUEST['s_shenfenhaoma']);
	$update_status=par($_REQUEST['update_status']);
	$_SESSION['update_status']=par($_REQUEST['update_status']);
	
	$update_op1=par($_REQUEST['update_op1']);
	$update_op2=par($_REQUEST['update_op2']);
	$update_op3=par($_REQUEST['update_op3']);
	$update_op4=par($_REQUEST['update_op4']);
	
	$lotno=par($_REQUEST['lotno']);
	$lotno_op1=par($_REQUEST['lotno_op1']);
	$lotno_op2=par($_REQUEST['lotno_op2']);
	$lotno_op3=par($_REQUEST['lotno_op3']);	$smt_lotno_op3=par($_REQUEST['smt_lotno_op3']);
	
	$gnkd=par($_REQUEST['gnkd']);
	$gnkdydh=par($_REQUEST['gnkdydh']);
	$gnkd_op1=par($_REQUEST['gnkd_op1']);
	$classid_del=add($_REQUEST['classid_del']);
	
	//$classtag='1';//标记:同个页面,同个$classtype时,要有不同标记
	$classtype=3;//分类类型
	$classid=GetEndArr($_REQUEST['classid'.$classtag.$classtype]);
	if(!CheckEmpty($classid)){$classid=par($_GET['classid']);}
	
	if (!$ydh){echo ("<script>alert('请输入或扫描单号！');goBack();</script>");}	
	if (!CheckEmpty($update_status)){echo ("<script>alert('请选择更新状态！');goBack();</script>");}
	
	$search="&lx={$lx}&ydh={$ydh}&update_status={$update_status}&op_ydh={$op_ydh}&op_dsfydh={$op_dsfydh}&op_gwkdydh={$op_gwkdydh}&op_gnkdydh={$op_gnkdydh}&op_hscode={$op_hscode}&update_op1={$update_op1}&update_op2={$update_op2}&update_op3={$update_op3}&update_op4={$update_op4}&classid={$classid}&classid_del={$classid_del}&lotno={$lotno}&lotno_op1={$lotno_op1}&lotno_op2={$lotno_op2}&lotno_op3={$lotno_op3}&smt_lotno_op3={$smt_lotno_op3}&gnkd={$gnkd}&gnkdydh={$gnkdydh}&gnkd_op1={$gnkd_op1}&fee={$fee}&payment={$payment}&s_shenfenhaoma={$s_shenfenhaoma}";

	//查询条件
	$where='';
	if($op_ydh){if($where){$where.=" or ydh='{$ydh}'";}else{$where=" ydh='{$ydh}'";}}
	if($op_dsfydh){if($where){$where.=" or dsfydh='{$ydh}'";}else{$where=" dsfydh='{$ydh}'";}}
	if($op_gwkdydh){if($where){$where.=" or gwkdydh='{$ydh}'";}else{$where=" gwkdydh='{$ydh}'";}}
	if($op_gnkdydh){if($where){$where.=" or gnkdydh='{$ydh}'";}else{$where=" gnkdydh='{$ydh}'";}}
	if($op_hscode){if($where){$where.=" or hscode='{$ydh}'";}else{$where=" hscode='{$ydh}'";}}
	if($op_lotno){if($where){$where.=" or lotno='{$ydh}'";}else{$where=" lotno='{$ydh}'";}}
	
	if (!$where){echo ("<script>alert('请勾选扫描选项,至少要选一个！');goBack();</script>");}
	

	$query="select * from yundan where {$where} ".whereCS()."  order by ydid desc limit 1";
	$sql=$xingao->query($query);
	while($rs=$sql->fetch_array())
	{
		$ok=1;
		//验证提示====================================================
		//是否算费
		if($ok&&!$fee&&!spr($rs['money'])&&$update_op3)
		{
			$ok=0;
			//music('no');//播放提示声音//无效:confirm未确认之前,无法播放,iframe也无效
			echo ("<script>if(confirm('该运单【未算费用】，确定要更新状态吗?'))location='?{$search}&fee=1';</script>");
		}
		
		//是否付清款
		if($ok&&!$payment&&(spr($rs['money']-$rs['payment'])>0)&&$update_op4)
		{
			$ok=0;
			//music('no');//播放提示声音//无效:confirm未确认之前,无法播放,iframe也无效
			echo ("<script>if(confirm('该运单【未付清费用】，确定要更新状态吗?'))location='?{$search}&payment=1';</script>");
		}
		
		//是否超过要更新的状态
		if($ok&&spr($rs['status'])>=$update_status)
		{
			if(!$update_op1)
			{
				$ok=0;
				$ts='<font class="red">该运单已是或已超过该状态!</font>';
			}
		}
		
		//无身份证件时状态更新为拒绝出库
		if($ok&&$update_op2&&$off_shenfenzheng&&channelPar($rs['warehouse'],$rs['channel'],'shenfenzheng')&&(!$rs['s_shenfenhaoma']||!$rs['s_shenfenimg_z']||!$rs['s_shenfenimg_b']))
		{
			$ok=0;
			$ts='<font class="red">该运单身份证件不完整，状态已更新为'.status_name(1).'!</font>';
			
			$reply='拒绝出库：身份证件未填写完整，可能是证件号码未填写或是证件两面未上传完';
			$set_reply="reply=concat('{$reply}； 
			',reply)";
			$xingao->query("update yundan set {$set_reply} where ydid='{$rs[ydid]}'");SQLError('更新运单-拒绝出库');
					
			yundan_updateStatus($rs,$update_status=1,0,1);
		}

		
		//未填写批次号时提示
		if($ok&&!$smt_lotno_op3&&$lotno_op3&&!$lotno&&!$rs['lotno'])
		{
			$ok=0;
			echo ("<script>if(confirm('该运单【未填写批次号】，确定要更新状态吗?'))location='?{$search}&smt_lotno_op3=1';</script>");
		}

		
		//该批次号有重复证件号时提示 (必须放在所有验证之后)
		if($ok&&!$s_shenfenhaoma&&$lotno_op2&&$off_shenfenzheng&&channelPar($rs['warehouse'],$rs['channel'],'shenfenzheng'))
		{
			$num_where=" 1=1 ";
			$num_where.=" and s_shenfenhaoma='".$rs['s_shenfenhaoma']."' and warehouse='".$rs['warehouse']."' ";//and channel='".$rs['channel']."'
			
			if($lotno){
				if($lotno_op1){$num_where.=" and lotno='".add($lotno)."'";}
				elseif(!$lotno_op1&&$rs['lotno']){ $num_where.=" and lotno='".$rs['lotno']."'";}
				else{$num_where.=" and lotno='".add($lotno)."'";}
			}elseif($rs['lotno']){
				$num_where.=" and lotno='".$rs['lotno']."'";
			}
			$num_where.=" and lotno<>'' and ydid<>'{$rs['ydid']}'";
			
			$num=mysqli_num_rows($xingao->query("select lotno from yundan where  {$num_where}  ".whereCS()." "));
			if($num>0)
			{
				$ok=0;
				//music('no');//播放提示声音//无效:confirm未确认之前,无法播放,iframe也无效
				echo ("<script>if(confirm('该运单的批次号有【重复证件号】，确定要更新状态吗?'))location='?{$search}&s_shenfenhaoma=1';</script>");
			}
		}
		
	
		//开始更新保存====================================================
		if($ok)
		{
			yundan_updateStatus($rs,$update_status,0,1);
			$ts=cadd($rs['ydh']).' 状态更新成功!';
			
			$save='';
			
			//航班/船运/托盘号
			if(strtolower($classid_del)=='del')
			{
				$save.="classid='0',";
				$ts.='，清除托盘号';
			}else{
				if (CheckEmpty($classid)){$save.="classid='{$classid}',";}
				$ts.='，更新托盘号:'.classify($classid);
			}

			//批次号
			if($lotno)
			{
				if(!$rs['lotno']||$lotno_op1)
				{
					$save.="lotno='".add($lotno)."',";
					$ts.='，更新批次号:'.cadd($lotno);
				}
			}

			if($gnkd||$gnkdydh)
			{
				if(!$rs['gnkdydh']||$gnkd_op1)
				{
					$save.=" gnkd='".add($gnkd)."',gnkdydh='".add($gnkdydh)."',";
					$ts.='，更新派送单号:'.cadd($expresses[$gnkd]).' '.cadd($gnkdydh);
				}
			}
			
			$save=DelStr($save);
			if($save)
			{
				$xingao->query("update yundan set {$save} where ydid='{$rs[ydid]}'");
				SQLError('修改其他');
			}
			

			
			
			
			
			
		}
			
	}//while($rs=$sql->fetch_array())
	if(!mysqli_num_rows($sql)){$ok=0;$ts='<font class="red">找不到运单!</font>';}

	//播放提示声音
	if($ok){music('yes');}elseif(!$ok){music('no');}

}//if ($lx=="sm")
?>

<?php 
//--------------------------------------------------------------------------------------------------------------------
//输出各种处理的提示--------------------------------------------------------------------------------------------------
if($ts)
{
	$alert_color='success'; if(!$ok){$alert_color='danger';}
	XAalert($ts,$alert_color);
}
?>

<?php 
//--------------------------------------------------------------------------------------------------------------------
//显示扫描框--------------------------------------------------------------------------------------------------
require_once($_SERVER['DOCUMENT_ROOT'].'/xingao/yundan/call/scan_form.php');
?>

</div>

<?php
require_once($_SERVER['DOCUMENT_ROOT'].'/xingao/incluce/foot.php');
?>
