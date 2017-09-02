<?php
if(!defined('InXingAo'))
{
	exit('No InXingAo');
}

//-----------不能传送有中文------------------------

//****************************************
    $business = $payr['payemail'];              //账号
    $currency = $payr['currency'];   //币种

	$notify_url = $siteurl."api/pay/paypal/notify_url.php";
//	$return_url = $siteurl."api/pay/paypal/return_url.php";
	$item_number=$ddno;//订单号
	$item_name ="[Online recharge] MemberID:".$Muserid."-MemberAccount:".$Musername;//不能传送有中文
	
//****************************************
?>


<!--
    以下<form表单内容在官网平台上生成:更多>网站地图>工具>管理PayPal按钮>自行创建按钮(立即购买)
-->

<!-------------------------------下面用的是 立即购买 形式---------------------------------------->
<form action="https://www.paypal.com/cgi-bin/webscr" method="post" id="dopaypost">
<!--"_xclick" 立即购买--> 
<input type="hidden" name="cmd" value="_xclick"> 

 <!--您PayPal账户上的电子邮件地址-->
<input type="hidden" name="business" value="<?=$business?>">

<!--商品描述-->
<input type="hidden" name="item_name" value="<?=$item_name?>">

<input type="hidden" name="item_number" value="<?=$item_number?>"> 

<!--//定义币种以标示货币变量 如USD，EUR。-->
<input type="hidden" name="currency_code" value="<?=$currency?>">

<!--//物品的价格（购物车中所有物品的总价格,因为是_Xclick模式）-->
<input type="hidden" name="amount" value="<?=$money_api?>">

<!--交易后paypal返回网站地址-->
<input type="hidden" name="notify_url" value="<?=$notify_url?>" />

<!--客户交易返回地址-->
<input type="hidden" name="return" value="<?=$return_url?>" />

<!--物品数量-->
<input type="hidden" name="quantity" value="1" />


</form>

<script>
document.getElementById('dopaypost').submit();
</script> 
