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
$pervar='member_ma';require_once($_SERVER['DOCUMENT_ROOT'].'/xingao/incluce/session.php');

//获取,处理=====================================================
$lx=par($_REQUEST['lx']);
$groupid=$_REQUEST['groupid'];
$groupid_new=par($_POST['groupid_new']);
$tokenkey=par($_POST['tokenkey']);
if (is_array($groupid)){$groupid=implode(',',$groupid);}
$groupid=par($groupid);

$_POST['Price_fh_wg_fee2']=str_ireplace('＝','=',$_POST['Price_fh_wg_fee2']);
$_POST['dg_brandDiscount']=str_ireplace('＝','=',$_POST['dg_brandDiscount']);


//添加,修改=====================================================
if($lx=='add'||$lx=='edit')
{
	//通用验证------------------------------------
	$token=new Form_token_Core();
	$token->is_token("member_group",$tokenkey); //验证令牌密钥
	
	//语言字段处理--
	if(!$LGList){$LGList=languageType('',3);}
	if($LGList)
	{
	  foreach($LGList as $arrkey=>$language)
	  {
		  //生成查询重名条件
		  if($_POST['groupname'.$language]){$numWhere.=" groupname{$language}='".add($_POST['groupname'.$language])."' or";}
	  }
	}
	$numWhere=DelStr($numWhere,'or');


	//处理仓库及渠道-开始
	if($lx=='add'){$wh_groupid=NextId('member_group');}elseif($lx=='edit'){$wh_groupid=$groupid;}
	$warehouse='';
	$alone='';
	$query_wh="select * from warehouse where checked='1' order by myorder desc,whid desc";
	$sql_wh=$xingao->query($query_wh);
	while($wh=$sql_wh->fetch_array())
	{
		$wh_name='warehouse_'.$wh['whid'];
		
		//基本-开始
		$warehouse.='$member_warehouse['.$wh_groupid.']['.$wh['whid'].']=array(';
		$warehouse.="'checked'=>'".spr($_POST[$wh_name.'_checked'])."',";
		$warehouse.="'area'=>'".$wh['area']."',";
		$warehouse.=');
		';
		
		$alone.=$wh_name.'_checked,';
		//基本-结束
		
		//区域-开始
		for($area=1;$area<=$wh['area'];$area++)
		{
			$wh_name_area=$wh_name.'_'.$area;
			$alone.=$wh_name_area.',';

			$warehouse.='$member_warehouse['.$wh_groupid.']['.$wh['whid'].']['.$area.']=array(';
		    $warehouse.="'country'=>'".add(ToStr($_POST[$wh_name_area.'_country']))."',";
			$alone.=$wh_name_area.'_country,';

			$rs_channel=$wh['channel'.$LT];if(!is_array($rs_channel)&&$rs_channel){$rs_channel=explode(':::',$rs_channel);}
			//渠道
			for ($i=1; $i<=20; $i++) 
			{
				$channel='channel_'.$i;
				if($rs_channel[$i])
				{
					$warehouse.="'{$channel}_checked'=>'".spr($_POST[$wh_name_area.'_'.$channel])."',";
					$alone.=$wh_name_area.'_'.$channel.',';
					
					$warehouse.="'{$channel}_formula'=>'".$_POST[$wh_name_area.'_'.$channel.'_formula']."',";
					$alone.=$wh_name_area.'_'.$channel.'_formula,';
					
					$warehouse.="'{$channel}_weight_int'=>'".$_POST[$wh_name_area.'_'.$channel.'_weight_int']."',";
					$alone.=$wh_name_area.'_'.$channel.'_weight_int,';
					
					$warehouse.="'{$channel}_sz_weight'=>'".spr($_POST[$wh_name_area.'_'.$channel.'_sz_weight'])."',";
					$alone.=$wh_name_area.'_'.$channel.'_sz_weight,';
					
					$warehouse.="'{$channel}_sz_price'=>'".spr($_POST[$wh_name_area.'_'.$channel.'_sz_price'])."',";
					$alone.=$wh_name_area.'_'.$channel.'_sz_price,';
					
					$warehouse.="'{$channel}_xz_weight'=>'".spr($_POST[$wh_name_area.'_'.$channel.'_xz_weight'])."',";
					$alone.=$wh_name_area.'_'.$channel.'_xz_weight,';
					
					$warehouse.="'{$channel}_xz_price'=>'".spr($_POST[$wh_name_area.'_'.$channel.'_xz_price'])."',";
					$alone.=$wh_name_area.'_'.$channel.'_xz_price,';
					
					$warehouse.="'{$channel}_discount'=>'".spr($_POST[$wh_name_area.'_'.$channel.'_discount'])."',";
					$alone.=$wh_name_area.'_'.$channel.'_discount,';
					
					$warehouse.="'{$channel}_tax'=>'".spr($_POST[$wh_name_area.'_'.$channel.'_tax'])."',";
					$alone.=$wh_name_area.'_'.$channel.'_tax,';
					
					$warehouse.="'{$channel}_cc_exceed'=>'".spr($_POST[$wh_name_area.'_'.$channel.'_cc_exceed'])."',";
					$alone.=$wh_name_area.'_'.$channel.'_cc_exceed,';
					
					$warehouse.="'{$channel}_cc_formula'=>'".spr($_POST[$wh_name_area.'_'.$channel.'_cc_formula'])."',";
					$alone.=$wh_name_area.'_'.$channel.'_cc_formula,';
					
					$warehouse.="'{$channel}_fee_other'=>'".add($_POST[$wh_name_area.'_'.$channel.'_fee_other'])."',";
					$alone.=$wh_name_area.'_'.$channel.'_fee_other,';
					
					$warehouse.="'{$channel}_fee_other_currency'=>'".add($_POST[$wh_name_area.'_'.$channel.'_fee_other_currency'])."',";
					$alone.=$wh_name_area.'_'.$channel.'_fee_other_currency,';
					
					$warehouse.="'{$channel}_fee_other_weight'=>'".add($_POST[$wh_name_area.'_'.$channel.'_fee_other_weight'])."',";
					$alone.=$wh_name_area.'_'.$channel.'_fee_other_weight,';
				}
			}
			$warehouse.=');
			';
		}
		//区域-结束
		
		
		
	}
	//处理仓库及渠道-结束
	
	
	
	
	for ($i_val=1; $i_val<=10; $i_val++)
	{
		$digital.="op_bgfee1_val_fee{$i_val},op_bgfee2_val_fee{$i_val},op_wpfee1_val_fee{$i_val},op_wpfee2_val_fee{$i_val},op_ydfee1_val_fee{$i_val},op_ydfee2_val_fee{$i_val},";
	}


	//添加验证,保存------------------------------------
	if($lx=='add')
	{
		//验证
		$num=mysqli_num_rows($xingao->query("select groupid from member_group where 1=1 and ({$numWhere})"));
		if($num){exit ("<script>alert('组名称重复,请修改！');goBack();</script>");}
		
		
		//保存
		$savelx='add';//调用类型(add,edit,cache)
		$getlx='POST';//获取类型(POST,GET,REQUEST,SQL)
		$alone=$alone.'groupid,warehouse';//不处理的字段
		$digital=$digital.'myorder,checked,regchecked,off_company,off_qujian,off_print,off_zjxd,off_zjxd_calc,off_lipei,off_tixian,tixian_xiao,tixian_sl,Price_02,Price_06,Price_09,Price_10,Price_11,Price_hxsl,Price_hx1,Price_hx2,Price_fxsl,Price_fx1,Price_fx2,Price_th,Price_04,Price_07,bg_ware_freeDays,bg_ware_weight,bg_ware_price,off_insurance,baoguo_fx,baoguo_fh,baoguo_fh2,Price_fh_wg_fee,Price_fh_wg,Price_fh_wg_type,Price_fh_wg_formula,Price_fh_hxsl,Price_fh_feesl,Price_fh_hx_fee1,Price_fh_hx_fee2,Price_fh_hx_fee1_way,Price_fh_hx_fee2_way,  up_groupid_integral,up_groupid_max_cz_once,up_groupid_max_cz_more,off_settlement,ON_Mbaoguo,daigou,dg_serviceRateWeb,dg_serviceRateShop,dg_serviceFee_1,dg_serviceFee_2,dg_serviceFee_3,dg_serviceFee_4,dg_ware_freeDays,dg_ware_volumePrice,dg_ware_volumeLimit,dg_ware_weightPrice,dg_ware_weightLimit,dg_ware_numberPrice,dg_DeliveryLimitNumber,dg_DeliveryLimitWeight';//数字字段
		$radio='up_groupid';//单选、复选、空文本、数组字段
		$textarea='';//过滤不安全的HTML代码
		$date='';//日期格式转数字
		$save=XingAoSave($_POST,$getlx,$savelx,$alone,$digital,$radio,$textarea,$html,$date);

		$xingao->query("insert into member_group (".$save['field'].",warehouse) values(".$save['value'].",'".add($warehouse)."')");
		SQLError();
		$rc=mysqli_affected_rows($xingao);
		if($rc>0)
		{
			//生成缓存文件
			cache_member_group();
			$token->drop_token("member_group"); //处理完后删除密钥
			exit("<script>alert('{$LG['pptAddSucceed']}');location='list.php';</script>");
		}else{
			exit ("<script>alert('{$LG['pptAddFailure']}');goBack();</script>");
		}
	}
	
	//修改验证,保存------------------------------------
	if($lx=='edit')
	{
		//验证
		if(!$groupid){exit ("<script>alert('groupid{$LG['pptError']}');goBack();</script>");}
		
		$num=mysqli_num_rows($xingao->query("select groupid from member_group where  groupid<>'{$groupid}' and ({$numWhere})"));
		if($num){exit ("<script>alert('组名称重复,请修改！');goBack();</script>");}

		//更新
		$savelx='edit';//调用类型(add,edit,cache)
		$getlx='POST';//获取类型(POST,GET,REQUEST,SQL)
		$alone=$alone.'groupid,warehouse';//不处理的字段
		$digital=$digital.'myorder,checked,regchecked,off_company,off_qujian,off_print,off_zjxd,off_zjxd_calc,off_lipei,off_tixian,tixian_xiao,tixian_sl,Price_02,Price_06,Price_09,Price_10,Price_11,Price_hxsl,Price_hx1,Price_hx2,Price_fxsl,Price_fx1,Price_fx2,Price_th,Price_04,Price_07,bg_ware_freeDays,bg_ware_weight,bg_ware_price,off_insurance,baoguo_fx,baoguo_fh,baoguo_fh2,Price_fh_wg_fee,Price_fh_wg,Price_fh_wg_type,Price_fh_wg_formula,Price_fh_hxsl,Price_fh_feesl,Price_fh_hx_fee1,Price_fh_hx_fee2,Price_fh_hx_fee1_way,Price_fh_hx_fee2_way,  up_groupid_integral,up_groupid_max_cz_once,up_groupid_max_cz_more,off_settlement,ON_Mbaoguo,daigou,dg_serviceRateWeb,dg_serviceRateShop,dg_serviceFee_1,dg_serviceFee_2,dg_serviceFee_3,dg_serviceFee_4,dg_ware_freeDays,dg_ware_volumePrice,dg_ware_volumeLimit,dg_ware_weightPrice,dg_ware_weightLimit,dg_ware_numberPrice,dg_DeliveryLimitNumber,dg_DeliveryLimitWeight';//数字字段
		$radio='up_groupid';//单选、复选、空文本、数组字段
		$textarea='';//过滤不安全的HTML代码
		$date='';//日期格式转数字
		$save=XingAoSave($_POST,$getlx,$savelx,$alone,$digital,$radio,$textarea,$html,$date);
		
		$save.=",warehouse='".add($warehouse)."'";
		$xingao->query("update member_group set ".$save." where groupid='{$groupid}'");
		SQLError('修改');
		$rc=mysqli_affected_rows($xingao);
		
		//生成缓存文件
		cache_member_group();
		
		$token->drop_token("member_group"); //处理完后删除密钥
		//$ts=$rc>0?$LG['pptEditSucceed']:$LG['pptEditNo'];
		exit("<script>location='list.php';</script>");//alert('".$ts."');
	}
	

//移动=====================================================
}elseif($lx=='mobile'){
	if(!$groupid){exit ("<script>alert('请勾选旧分类！');goBack();</script>");}
	if(!$groupid_new){exit ("<script>alert('请选择新分类！');goBack();</script>");}

	$xingao->query("update member set groupid='{$groupid_new}' where groupid in ({$groupid})");
	SQLError();
	$rc=mysqli_affected_rows($xingao);

	exit("<script>alert('转移完成,共转移{$rc}个会员！');location='list.php';</script>");

//删除=====================================================
}elseif($lx=='del'){
	if(!$groupid)
	{
		exit ("<script>alert('请选择分类！');goBack();</script>");
	}
	
	//删除用户相关记录
	$num=mysqli_num_rows($xingao->query("select username from member where groupid in ({$groupid})"));
	if($num)
	{
		exit ("<script>alert('所选分类里有会员，不能删除，请先转移会员！');goBack();</script>");
	}
	
	//删除分类
	$xingao->query("delete from member_group where groupid in ({$groupid})");

	//生成缓存
	cache_member_group();
	
	exit("<script>alert('分类删除完成！');location='list.php';</script>");
}	

?>
