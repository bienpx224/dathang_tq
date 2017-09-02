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
$noper='member_re';require_once($_SERVER['DOCUMENT_ROOT'].'/xingao/incluce/session.php');
if(!$ON_bankAccount){exit ("<script>alert('转账充值系统已关闭！');goBack();</script>");}


//获取,处理=====================================================
$lx=par($_REQUEST['lx']);
$tfid=$_REQUEST['tfid'];
$tokenkey=par($_POST['tokenkey']);

if (is_array($tfid)){$tfid=implode(',',$tfid);} $tfid=par($tfid);

//添加,修改=====================================================
if($lx=='add'||$lx=='edit')
{
	//通用验证------------------------------------
	$token=new Form_token_Core();
	$token->is_token('transfer'.$tfid,$tokenkey); //验证令牌密钥
	
	//修改------------------------------------
	if($lx=='edit')
	{
		if(!$tfid){exit ("<script>alert('tfid{$LG['pptError']}');goBack();</script>");}
		
		$rs=FeData('transfer','*'," tfid='{$tfid}'");
		$m_currency=FeData('member','currency',"userid='{$rs['userid']}'");//获取会员币种
		
		if(spr($rs['status'])==5){exit ("<script>alert('该信息已无效，不能再修改！');goBack();</script>");}
		
		
		$save="tfid=tfid";

		//操作金额--开始
		if(spr($_POST['status'])==1)
		{
			if(!trim($_POST['orderNo'])){exit ("<script>alert('请填写转账回单编号！');goBack();</script>");}
			if(spr($_POST['fromMoney'])<=0){exit ("<script>alert('请填写转账金额！');goBack();</script>");}
			if(!trim($_POST['fromCurrency'])){exit ("<script>alert('请选择转账币种！');goBack();</script>");}
			if(spr($_POST['toMoney'])<=0){exit ("<script>alert('充值金额兑换错误！');goBack();</script>");}
			

			$num=mysqli_num_rows($xingao->query("select tfid from transfer where orderNo='".add($_POST['orderNo'])."' and tfid<>'{$tfid}' and status=1"));
			if($num){exit ("<script>alert('该转账回单编号已经充值过，不可重复充值！');goBack();</script>");}
		
			$new_fromMoney=spr($_POST['fromMoney']-$rs['fromMoney']);
			$new_toMoney=spr($_POST['toMoney']-$rs['toMoney']);
			
			$fromtable='transfer';	
			$fromid=$tfid;	
			$title=$_POST['orderNo'];	
			$type=20;
			$tally=0;	
			$operator=$Xuserid;
			
			//扣除差额
			if($new_fromMoney<0)
			{
				$content='由于之前的充值错误而扣除差额';
				
				MoneyKF($rs['userid'],$fromtable,$fromid,$fromMoney=$new_fromMoney,$fromCurrency=$_POST['fromCurrency'],
				$title,$content,$type,$tally,$operator);
				
				$ppt= "扣费成功：已扣 ".$new_toMoney.$m_currency;
			}
			
			//首充或补充差额
			if($new_fromMoney>0)
			{
				if(spr($rs['status'])&&$rs['toMoney']>0){$content='由于之前的充值错误而补充差额';}//补充差额
				
				MoneyCZ($rs['userid'],$fromtable,$fromid,$fromMoney=$new_fromMoney,$fromCurrency=$_POST['fromCurrency'],
				$title,$content,$type,$ddno=0,$operator);
				
			    $ppt= "充值成功：已充 ".$new_toMoney.$m_currency;
				
				
				//自动支付处理-开始--------------------------------------------------
				if(!$rs['autoPayStatus']&&$rs['autoPay']&&$rs['fromtable']&&$rs['fromid'])
				{
					//支付代购
					if($rs['fromtable']=='daigou')
					{
						$ret=daigou_batchPay($rs['fromid'],$refund=0,$per=0,$allPay=1);//支付自动处理
						if($ret){$save.=",autoPayStatus='1'";}else{$save.=",autoPayStatus='2'";}
					}
				}
				//自动支付处理-结束--------------------------------------------------
				
				
				
				
			}
			
			$save.=",orderNo='".add($_POST['orderNo'])."'";
			$save.=",exchange='".spr($_POST['exchange'],5)."'";
			$save.=",fromMoney='".spr($_POST['fromMoney'])."'";
			$save.=",fromCurrency='".add($_POST['fromCurrency'])."'";
			$save.=",toMoney='".spr($_POST['toMoney'])."'";
			$save.=",toCurrency='{$m_currency}'";
		}
		//操作金额--结束





		//修改主表
		$save.=",status='".spr($_POST['status'])."'";
		$save.=",popup='0'";
		$save.=",reply='".html($_POST['reply'])."'";
		if($rs['reply']!=add($_POST['reply'])){$save.=",replytime='".time()."'";}
		if(!$rs['optime']){	$save.=",optime='".time()."'";}else{$save.=",edittime='".time()."'";}

		$xingao->query("update transfer set ".$save." where tfid='{$tfid}'");
		SQLError('更新transfer');

		$token->drop_token('transfer'.$tfid); //处理完后删除密钥
		if(!$ppt){$ppt=$LG['pptEditSucceed'];}
		exit("<script>alert('{$ppt}');location='list.php';</script>");
	}
	
	
	
//删除=====================================================
}elseif($lx=='del'){
	
	if(!$tfid){exit ("<script>alert('tfid{$LG['pptError']}');goBack();</script>");}

	//开启“长期保存记录”时禁止删除的状态
	if($off_delbak)
	{
		$delbak_status="and status='0'";
		$delbak_ts='\\n已开启“长期保存记录”功能，正常记录则不能删除';
	}
	
	$where="tfid in ({$tfid})  and status in (0,1,5) {$delbak_status}";
	//查询文件
	$query="select img from transfer where {$where} and img<>'' ";
	$sql=$xingao->query($query);
	while($rs=$sql->fetch_array())
	{
		//删除文件
		DelFile($rs['img']);
	}
	$xingao->query("delete from transfer where {$where} ");
	$rc=mysqli_affected_rows($xingao);
	
	if($rc>0){
		exit("<script>alert('{$LG['pptDelSucceed']}{$rc}');location='list.php';</script>");
	}else{
		exit("<script>alert('{$LG['pptDelEmpty']}{$delbak_ts}');location='list.php';</script>");
	}
	
}
?>