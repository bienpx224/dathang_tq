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
require_once($_SERVER['DOCUMENT_ROOT'].'/public/function.php');


//------------------------------------------------获取数据----------------------------------------------------
if(!$phpcall)//是否php页内调用
{
	//获取会员组ID
	if(!CheckEmpty($userid)){$userid=(int)$_POST['userid'];}
	if(!CheckEmpty($groupid)){$groupid=(int)$_POST['groupid'];}
	
	//主要参数
	$calc_typ=spr($_POST['calc_typ']);//calc_transport的计费类型:1不自动算fee_cc
	$yf=par($_POST['yf']) ;//是否计算所有费用(0=只显示总费用;1=数组(JS用);2=保存到数组(PHP用))
	$warehouse=spr($_POST['warehouse']);//仓库
	$country=spr($_POST['country']);//寄往国家
	$channel=spr($_POST['channel']) ;//渠道,必须用spr
	$fee_transport=par($_POST['fee_transport']);//固定运费(不能加spr,下面有CheckEmpty)
	$weight=spr($_POST['weight']);//重量
	$bg_number=(int)$_POST['bg_number'];//包裹数量
	$insurevalue=spr($_POST['insurevalue'],3);//保价费
	$tax_number=spr($_POST['tax_number'],2,0);//扣税次数
	$fee_tax=spr($_POST['fee_tax'],2,0);//税费
	$wp_number=spr($_POST['wp_number'],0);//物品数量
	$fee_ware=spr($_POST['fee_ware'],0);//指定仓储费

	$gdid=DelStr(par($_POST['gdid']));//商品资料ID
	$gd_number=DelStr(par($_POST['gd_number']));//商品资料ID对应的数量
	
	$goid=DelStr(par($_POST['goid']));//代购ID
	$go_number=DelStr(par($_POST['go_number']));//代购对应的数量

	//尺寸体积
	$fee_cc=par($_POST['fee_cc'],3);
	$cc_chang=spr($_POST['cc_chang']);//下面有*,必须spr
	$cc_kuan=spr($_POST['cc_kuan']);//下面有*,必须spr
	$cc_gao=spr($_POST['cc_gao']);//下面有*,必须spr
	
	//增值服务
	$op_bgfee1=par($_POST['op_bgfee1']);
	$op_bgfee2=par($_POST['op_bgfee2']);
	$op_wpfee1=par($_POST['op_wpfee1']);
	$op_wpfee2=par($_POST['op_wpfee2']);
	$op_ydfee1=par($_POST['op_ydfee1']);
	$op_ydfee2=par($_POST['op_ydfee2']);
	
	//其他费用
	$fee_service=par($_POST['fee_service']) ;//服务费,不能用spr,下面有用
	$fee_other=spr($_POST['fee_other'],3) ;//其他
	$discount=par($_POST['discount']) ;//运费折扣,不能用spr,下面有用
	$baoguo_hx_fee=spr($_POST['baoguo_hx_fee'],3);//合箱发货收费
}

//验证
if(!$groupid&&!$userid){exit($LG['yundan.11']);}//无法获取会员ID
elseif(!$groupid&&$userid){$groupid=FeData('member','groupid',"userid='{$userid}'");}

if (!$groupid){exit($LG['yundan.12']);}//会员组为空，可能该会员已被删除
if (!$warehouse){exit($LG['yundan.13']);}//请选仓库
if (!$country){exit($LG['yundan.14']);}//请选寄往国家
if (!$channel){exit($LG['yundan.15']);}//请选渠道
if ($weight<=0){exit($LG['js.12']);}//请填写重量












//---------------------------------------计算运费---------------------------------------------------
require($_SERVER['DOCUMENT_ROOT'].'/xingao/yundan/call/get_price.php');//返回会员组数据和返回$area,不加_once
if(!$phpcall&&!$channel_checked){exit($LG['yundan.16']);}//该会员无权使用该仓库的运输渠道
if($phpcall==1||!CheckEmpty($discount)){$discount=$channel_discount;}



//获取计费重量:体积重大于重量时,则用体积重作为重量========================
$volume=spr($cc_chang)*spr($cc_kuan)*spr($cc_gao);//体积:有*,必须spr
if($calc_typ!=1&&$channel_cc_exceed>0&&$volume>=$channel_cc_exceed&&$channel_cc_formula)
{
	$volumeWeight=cc_formula_calc($cc_chang,$cc_kuan,$cc_gao,$channel_cc_formula);
	if($volumeWeight>$weight){$weight=$volumeWeight;}
}
//echo "{$weight}-{$volume}-{$channel_cc_exceed}-{$channel_cc_formula}";exit;

//echo "{$weight}";exit;

$Cost=0;$Cost_err=0;
//已指定运费========================
if(CheckEmpty($fee_transport)&&$yf!=2){
	$Cost=$fee_transport;
}
//默认公式========================
elseif(!$channel_formula){
	$trunc=$member_warehouse[$groupid][$warehouse][$area]['channel_'.$channel.'_weight_int'];
	$weight=weight_int(2,$trunc,$weight);//重量取整
	
	if ($weight){$Cost=$channel_sz_price;}//首重价=首重价格
	$xz_weight=$weight-$channel_sz_weight;//续重=总重-首重 
	$xz_fee=0;
	if($xz_weight>0){
		if($channel_xz_weight<=0){$channel_xz_weight=1;}
		$xz_fee=($xz_weight/$channel_xz_weight);
		$xz_fee=weight_int(2,2,$xz_fee);
		$xz_fee=$xz_fee*$channel_xz_price;//续重价格:(续重/续重) * 续重价格
	}
	if($xz_fee>0){$Cost+=$xz_fee;}//总价:运费=首重价格+续重价格
}

//固定价格表========================
elseif(stristr($channel_formula,'price_jp')){
	$pr=price_jp($weight,findNum($channel_formula));
	$Cost=$pr['price'];
	if($pr['service']>0){$fee_service=$pr['service'];}
	elseif($pr['service']==-1){$fee_service=0;}
}

//后台单独设置========================
elseif($channel_formula=='other'){
	if($channel_fee_other)
	{
		$weight_now=$weight;if($channel_fee_other_weight){$weight_now*=$XAwtkg;}
		
		$r_now=GetArrVar($weight_now,$channel_fee_other,2);//获取价格表:返回运费、手续费
		if($r_now[1]>0)
		{
			$Cost=$r_now[1];
			$fee_service=$r_now[2];
			if($channel_fee_other_currency!=$XAMcurrency)
			{
				$exchange=exchange($channel_fee_other_currency,$XAMcurrency);
				$Cost*=$exchange;
				$fee_service*=$exchange;
			}
		}
		
	}else{
		$Cost=$LG['yundan.17'];//请先在后台>会员组设置价格！
	}
}
if($Cost&&!spr($Cost)){$Cost_err=2;}//不支持的重量,无价格提示
$Cost_transport=$Cost;//运费














//------------------------------------------服务费及其他------------------------------------------------
//计算税费========================
if(CheckEmpty($tax_number))
{
	//扣税次数*税费额度
	$fee_tax=$tax_number*$channel_tax;
}elseif(CheckEmpty($gdid)&&!CheckEmpty($fee_tax)){
	
	//备案商品:按商品库税率收
	if(arrcount($gdid)!=arrcount($gd_number)){exit($LG['yundan.18']);}//请填写完整物品数量(gdid数量与gd_number数量不符合)
	$fee_tax=calc_gd_tax($warehouse,$channel,$gdid,$gd_number);
}





//计算体积费========================
/*$lfcc=$cc_chang*$cc_kuan*$cc_gao;
if($calc_typ!=1&&$channel_cc_exceed>0&&$lfcc>=$channel_cc_exceed&&$channel_cc_formula)
{
	$fee_cc=cc_formula_calc($cc_chang,$cc_kuan,$cc_gao,$channel_cc_formula);
}
*/

//计算服务费========================
if(!CheckEmpty($fee_service))//如果价格表有返回$fee_service或已指定$fee_service值,则不再计算
{
	//按数量收费
	$fee_service=$baoguo_hx_fee;

	//按重量收费
	if($Price_fh_wg_formula==1&&$Price_fh_wg_fee>0&&$Price_fh_wg>0)//固定方式
	{
		$wg_ok=0;
		if($Price_fh_wg_type==1&&$bg_number>0){$wg_ok=1;}
		elseif($Price_fh_wg_type==2&&$bg_number==0){$wg_ok=1;}
		elseif($Price_fh_wg_type==3){$wg_ok=1;}
		if($wg_ok){$fee_service+=($Price_fh_wg_fee*weight_int(2,$ceil_lx,$weight/$Price_fh_wg));}
		
	}elseif($Price_fh_wg_formula==2&&$Price_fh_wg_fee2){//阶梯方式
		$r_now=GetArrVar($weight,$Price_fh_wg_fee2,2);
		if($r_now[1]>0){$fee_service+=$r_now[1];}
	}

	
}
$fee_service=spr($fee_service);

//综合费用========================
if ($Cost>0&&!$Cost_err)
{
	if ($discount>0){$Cost*=($discount/10);}//运费*运费折扣 (打折公式：原价×（折扣÷10）)
	if ($fee_tax>0){$Cost+=$fee_tax;}//运费+税费
	if ($insurevalue>0){$Cost+=$insurevalue;}//运费+保价费
	if ($fee_service>0){$Cost+=$fee_service;}//运费+服务费
	if ($fee_other>0){$Cost+=$fee_other;}//运费+其他
	if ($fee_cc>0){$Cost+=$fee_cc;}//运费+体积费
	if (!$Cost){$Cost_err=1;}//放2个
	
	//代购仓储费
	if ($fee_ware){$Cost+=$fee_ware;}
	elseif ($goid&&$go_number){$fee_ware=dg_wareFee($goid,$go_number);$Cost+=$fee_ware;}
	

	//收费附加服务
	if ($op_bgfee1>0){$calc_joint="op_bgfee1_val_fee{$op_bgfee1}"; $Cost+=($$calc_joint*$bg_number);}
	if ($op_bgfee2>0){$calc_joint="op_bgfee2_val_fee{$op_bgfee2}"; $Cost+=($$calc_joint*$bg_number);}
	if ($op_wpfee1>0){$calc_joint="op_wpfee1_val_fee{$op_wpfee1}"; $Cost+=($$calc_joint*$wp_number);}
	if ($op_wpfee2>0){$calc_joint="op_wpfee2_val_fee{$op_wpfee2}"; $Cost+=($$calc_joint*$wp_number);}
	if ($op_ydfee1>0){$calc_joint="op_ydfee1_val_fee{$op_ydfee1}"; $Cost+=$$calc_joint;}
	if ($op_ydfee2>0){$calc_joint="op_ydfee2_val_fee{$op_ydfee2}"; $Cost+=$$calc_joint;}

}
if (!$Cost&&!$Cost_err){$Cost_err=1;}
	









//输出费用----------------------------------------------------------------------------------------------------
if (!$Cost_err){
	if($yf)
	{
		$fee_0=spr($Cost);
		$fee_1=spr($Cost_transport);
		$fee_2=$discount;
		$fee_3='';if($discount>0){$fee_3= $LG['yundan.19'].$Cost_transport*($discount/10).$XAmc;}//折扣后
		$fee_4=$fee_service;
		$fee_5=$fee_cc;
		$fee_6=spr($fee_tax,2,0);
		$fee_7=$fee_ware;
		
		//0总费用,1单运费,2打多少折,3单运费折扣后,4单服务费,5体积费,6税费,7仓储费
		if($yf==1){
			echo "{$fee_0},{$fee_1},{$fee_2},{$fee_3},{$fee_4},{$fee_5},{$fee_6},{$fee_7}";
		}elseif($yf==2){
			$fee_rs="{$fee_0},{$fee_1},{$fee_2},{$fee_3},{$fee_4},{$fee_5},{$fee_6},{$fee_7}";
			$fee_rs=explode(',',$fee_rs);
		}
	}else{
		echo spr($Cost);
	}
	
}elseif($Cost_err==1){
	echo $LG['yundan.20'];//未填写完整(运费错误)
}elseif ($Cost_err==2){
	//如果是日币,不能有小数,因为小数无法充值
	if($XAMcurrency=='JPY'){$Cost=spr($Cost,0,1,0,1);}
	echo $Cost;
}


//必须,防止$phpcall=1时影响后面数据,并且不能用=0
$Cost='';
$Cost_transport='';
$discount='';
$fee_service='';
$fee_cc='';
$fee_tax='';
$fee_ware='';
?>