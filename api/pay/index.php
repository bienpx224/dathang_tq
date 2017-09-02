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
require_once($_SERVER['DOCUMENT_ROOT'].'/xamember/incluce/session.php');

$lx=par($_POST['lx']);
$money=spr($_POST['money']);
$payid=(int)$_POST['payid'];
$content=add($_POST['content']);
$openApi=par($_POST['openApi']);

if ($lx=="tj")
{ 
	
	if($ON_demo){ exit("<script>alert('当前是演示模式，所充值金额无法退回，因此已关闭充值演示！(PA0001)');goBack('c');</script>"); }
	if(!$payid)	{exit("<script>alert('{$LG['api.pay_2']} (PA0002)');goBack('c');</script>");	}




	//获取接口参数----------------------------------------------------
	$payr=FeData('payapi','*'," payid='{$payid}' and checked=1");
	$apiExchange=exchange($Mcurrency,$payr['currency']);//接口币种
	
	
	//验证
	if(!$payr['payid']){exit("<script>alert('{$LG['api.pay_3']} (PA0003)');goBack('c');</script>");}
	
	//验证最小充值金额
	$apiMoney=$money*$apiExchange;
	if($apiMoney<=0||$apiMoney<$payr['payMinMoney'])
	{
		exit("<script>alert('{$LG['api.pay_4']}".spr($payr['payMinMoney'])." (PA0004)');goBack('c');</script>");
	}
	
	//充值时多加/减
	if($payr['payIncMoney']){$money+=spr($payr['payIncMoney'])*exchange($payr['currency'],$Mcurrency);}
	
	
	//人民币和接口币金额
	$money_api=spr($money*$apiExchange);//接口币金额
	if($Mcurrency=='CNY'){$money_rmb=$money;}else{$money_rmb=0;}//人民币
	
	
	//生成支付订单号
	$ddno=make_NoAndPa(16);//有些接口不能超过16位
	//检查重复
	$num=mysqli_num_rows($xingao->query("select ddno from paytemp where ddno='{$ddno}'"));
	while($num>0) 
	{
		$ddno=make_NoAndPa(16);//有些接口不能超过16位
		$num=mysqli_num_rows($xingao->query("select ddno from paytemp where ddno='{$ddno}'"));
	}

	$xingao->query("insert into paytemp (payid,userid,username,ddno,money,currency,content,addtime) values('".$payid."','".$Muserid."','".$Musername."','".$ddno."','".$money."','{$Mcurrency}','".$content."','".time()."') ");
	SQLError();
	
?>


<script>opener.document.getElementById('clickModal').click();</script><!--在上一页弹窗-->

<title><?=$LG['api.pay_1']?></title>
<body>
<div align="center">
	<?php 
	//payid=7:微信扫描支付--------------------------------------------------------------
	if($payid!=7&&$payid!=9)
	{
		echo '<p><img src="/images/loading_b.gif"/></p>';
		echo "<p>{$LG['api.pay_5']}</p>";
	}




	//转向接口支付--------------------------------------------------------------
	//SoftBank-开始
	if($payr['paytype']=='SoftBank')
	{
		if(!$payr['payuser']||!$payr['paykey']){exit("<script>alert('{$LG['api.pay_14']}(PA0005)');goBack('c');</script>");}
		if(!have($payr['openApi'],$openApi,1)){exit("<script>alert('{$LG['api.pay_15']}(PA0007)');goBack('c');</script>");}
		
		if($openApi=='credit')
		{
			require_once('SoftBank/toPay_credit.php');
		}else{
			//SoftBank 通用类型
			$pay_method = $openApi;//类型
			require_once('SoftBank/toPay_general.php');
		}

	}
	
	
	
	//NihaoPay-开始
	elseif($payr['paytype']=='NihaoPay')
	{
		if(!$payr['paykey']){exit("<script>alert('{$LG['api.pay_14']}(PA0008)');goBack('c');</script>");}
		if(!have($payr['openApi'],$openApi)){exit("<script>alert('{$LG['api.pay_15']}(PA0009)');goBack('c');</script>");}
		
		$vendor=$openApi;//类型
		$terminal='ONLINE';		if(isMobile()){$terminal='WAP';}  //应用场景
		require_once('NihaoPay/toPay.php');
	}
	//NihaoPay-结束


	//通用-开始
	else{
		@require_once($payr['paytype'].'/toPay.php');
	}
	//通用-结束
	?>	
</div>
</body>
</html>
<?php }?>