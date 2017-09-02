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


//由于要获取父函数变量太多,函数难调用,在父函数用全局时,会影响外部变量,因此已全改为直接调用文件方式

/*
	调用:
	//获取发送通知内容
	//其他参数,如:$status_name=daigou_Status($status);
	$NoticeTemplate='daigou_upStatus';
	require($_SERVER['DOCUMENT_ROOT'].'/public/NoticeTemplate.php');
*/
	  
require_once($_SERVER['DOCUMENT_ROOT'].'/public/global.php');
global $sitename,$siteurl;

switch($NoticeTemplate)
{
	//通知模板------------------------------------------------------------------------------
	/*
	   注意:微信平台模板,只能选择[物流]行业,不可选择快递
	   
	   微信支持指定颜色
	   "keyword1":{
		   "value":"巧克力",	//如果后面没有参数,不能再加,符号,否则会返回47001错误
			"color":"#173177"
	   },
	*/	   

	//-----------------------------------------
	case 'member_getpwd':
		//global $title,$content,$fr,$yz;
		$title=$sitename.':'.$LG['getpassword.getCheckcode'];
		$content=$fr['truename'].' '.$LG['getpassword.checkcode'].'<strong>'.$yz.'</strong><br>('.$LG['getpassword.reminder'].')<br>'.$sitename;
		//微信
		$send_WxTemId='OPENTM400504773';$send_WxTemName='验证码提醒';
		
		$send_content_wx='
		  "first": {
			   "value":"'.$fr['truename'].' '.$LG['getpassword.checkcode'].'"
		   },
		   "keynote1":{
			   "value":"'.$yz.'",
			   "color":"#CC0000"
		   },
		   "keynote2": {
			   "value":"如有问题请联系我们客服,祝您生活愉快!"
		   },
		   "remark":{
			   "value":"'.$LG['getpassword.reminder'].'"
		   }
		';
	break;
	//-----------------------------------------
	case 'member_reg':
		//global $title,$content,$ts,$member_reg_sendmail;
		$title=$LG['reg.yz_welcome'].cadd($sitename);
		$content=$ts.'<br>'.Label(TextareaToBr($member_reg_sendmail,1));
		//不支持微信
	break;
	//-----------------------------------------
	case 'member_reg_yz':
		//global $title,$content;
		$title=$LG['reg.yz_welcome'].cadd($sitename);
		$content=$LG['reg.yz_EmailCode'].$_SESSION['email_yz'];
		//不支持微信
	break;
	//-----------------------------------------
	case 'member_data_yz':
		//global $title,$content,$fr;
		$title=$sitename.$LG['data.yz_10'];
		$content=$fr['truename']." {$LG['data.yz_11']}<strong>".$_SESSION['email_yz'].'</strong><br>('.$LG['data.yz_7'].')<br>'.$sitename;
		//不支持微信
	break;
	//-----------------------------------------
	case 'member_payment_mall_order':
		//global $ts_pay,$pay_money;
		$send_title=$ts_pay;
		$send_content_msg=$send_title.$LG['payment.mall_order_23'];
		$send_content_sms=$send_title.$LG['payment.mall_order_23'];
		$send_content_mail=$send_content_sms.'<br><br>【<a href="'.$siteurl.'xamember/mall_order/list.php"><strong>'.$LG['loginWebsite'].'</strong></a>】';
		//微信
		$send_WxTemId='OPENTM406675616';$send_WxTemName='支付账单通知';
		$send_content_wx='
		  "first": {
			   "value":"'.$send_content_sms.'"
		   },
		   "keyword1":{
			   "value":"'.$pay_money.$XAmc.'"
		   },
		   "keyword2": {
			   "value":"'.DateYmd(time()).'"
		   },
		   "remark":{
			   "value":"如有问题请联系我们客服,祝您生活愉快!"
		   }
		';
	break;
	//-----------------------------------------
	case 'member_settlement_yundan':
		//global $settlement_money;
		$send_title=$LG['settlement.save_7'];
		$send_content_msg=$send_title.$LG['settlement.save_13'];
		$send_content_sms=$send_title.$LG['settlement.save_13'];
		$send_content_mail=$send_content_sms.'<br><br>【<a href="'.$siteurl.'xamember/settlement/list.php"><strong>'.$LG['loginWebsite'].'</strong></a>】';
		//微信
		$send_WxTemId='OPENTM406675616';$send_WxTemName='支付账单通知';
		$send_content_wx='
		  "first": {
			   "value":"'.$send_content_sms.'"
		   },
		   "keyword1":{
			   "value":"'.$settlement_money.'"
		   },
		   "keyword2": {
			   "value":"'.DateYmd(time()).'"
		   },
		   "remark":{
			   "value":"如有问题请联系我们客服,祝您生活愉快!"
		   }
		';
	break;
	//-----------------------------------------
	case 'member_settlement_other':
		//global $settlement_money;
		$send_title=$LG['settlement.save_10'];
		$send_content_msg=$send_title.$LG['settlement.save_13'];
		$send_content_sms=$send_title.$LG['settlement.save_13'];
		$send_content_mail=$send_content_sms.'<br><br>【<a href="'.$siteurl.'xamember/settlement/list.php"><strong>'.$LG['loginWebsite'].'</strong></a>】';
		//微信
		$send_WxTemId='OPENTM406675616';$send_WxTemName='支付账单通知';
		$send_content_wx='
		  "first": {
			   "value":"'.$send_content_sms.'"
		   },
		   "keyword1":{
			   "value":"'.$settlement_money.'"
		   },
		   "keyword2": {
			   "value":"'.DateYmd(time()).'"
		   },
		   "remark":{
			   "value":"如有问题请联系我们客服,祝您生活愉快!"
		   }
		';
	break;
	//-----------------------------------------
	case 'daigou_upStatus':
		//global $rs;
		$MLG=memberLT($rs['userid']);
		$send_title=LGtag($MLG['daigou.5'],'<tag1>=='.cadd($rs['dgdh']).'||<tag2>=='.$status_name);
		$send_content_msg=$send_title.$MLG['daigou.6'];
		$send_content_sms=$send_title.$MLG['daigou.7'];
		$send_content_mail=$send_content_sms.'<br><br>【<a href="'.$siteurl.'xamember/daigou/list.php?status=all"><strong>'.$MLG['loginWebsite'].'</strong></a>】';
		//微信
		$send_WxTemId='OPENTM401105686';$send_WxTemName='货物状态提醒';
		$send_content_wx='
		  "first": {
			   "value":"您的代购单状态已更新"
		   },
		   "keyword1":{
			   "value":"'.cadd($rs['dgdh']).'"
		   },
		   "keyword2":{
			   "value":"'.$status_name.'"
		   },
		   "remark":{
			   "value":"如有问题请联系我们客服,祝您生活愉快!"
		   }
		';
	break;
	//-----------------------------------------
	case 'daigou_PayRef_1':
		//global $rs,$pay_money,$toMoney,$mr;
		$MLG=memberLT($rs['userid']);
		$send_title=$MLG['daigou.21'].cadd($rs['dgdh']).$MLG['daigou.35'].$pay_money.$rs['priceCurrency'].$send_title;
		$send_content_msg=$send_title.'('.$MLG['daigou.34'].$toMoney.$mr['currency'].')';
		$send_content_sms=$send_content_msg;
		$send_content_mail=$send_content_msg.'<br><br>【<a href="'.$siteurl.'xamember/daigou/list.php?status=all"><strong>'.$MLG['loginWebsite'].'</strong></a>】';
		//微信
		$send_WxTemId='OPENTM406675616';$send_WxTemName='支付账单通知';
		$send_content_wx='
		  "first": {
			   "value":"'.$send_content_sms.'"
		   },
		   "keyword1":{
			   "value":"'.$pay_money.$rs['priceCurrency'].'"
		   },
		   "keyword2": {
			   "value":"'.DateYmd(time()).'"
		   },
		   "remark":{
			   "value":"如有问题请联系我们客服,祝您生活愉快!"
		   }
		';
	break;
	//-----------------------------------------
	case 'daigou_PayRef_2':
		//global $rs,$pay_money,$toMoney,$mr;
		$MLG=memberLT($rs['userid']);
		$send_title=$MLG['daigou.21'].cadd($rs['dgdh']).$MLG['daigou.35'].$pay_money.$rs['priceCurrency'].$MLG['daigou.36'];
		$send_content_msg=$send_title.'('.$MLG['daigou.34'].$toMoney.$mr['currency'].')';
		$send_content_sms=$send_content_msg;
		$send_content_mail=$send_content_msg.'<br><br>【<a href="'.$siteurl.'xamember/daigou/list.php?status=all"><strong>'.$MLG['loginWebsite'].'</strong></a>】';
		//微信
		$send_WxTemId='OPENTM406675616';$send_WxTemName='支付账单通知';
		$send_content_wx='
		  "first": {
			   "value":"'.$send_content_sms.'"
		   },
		   "keyword1":{
			   "value":"'.$pay_money.$rs['priceCurrency'].'"
		   },
		   "keyword2": {
			   "value":"'.DateYmd(time()).'"
		   },
		   "remark":{
			   "value":"如有问题请联系我们客服,祝您生活愉快!"
		   }
		';
	break;
	//-----------------------------------------
	case 'daigou_PayRef_3':
		//global $rs,$pay_money,$toMoney,$mr;
		$MLG=memberLT($rs['userid']);
		$send_title=$MLG['daigou.21'].cadd($rs['dgdh']).$MLG['havePay'].$pay_money.$rs['priceCurrency'];
		$send_content_msg=$send_title.'('.$MLG['deduct'].$toMoney.$mr['currency'].')';
		$send_content_sms=$send_content_msg;
		$send_content_mail=$send_content_msg.'<br><br>【<a href="'.$siteurl.'xamember/daigou/list.php?status=all"><strong>'.$MLG['loginWebsite'].'</strong></a>】';
		//微信
		$send_WxTemId='OPENTM406675616';$send_WxTemName='支付账单通知';
		$send_content_wx='
		  "first": {
			   "value":"'.$send_content_sms.'"
		   },
		   "keyword1":{
			   "value":"'.$pay_money.$rs['priceCurrency'].'"
		   },
		   "keyword2": {
			   "value":"'.DateYmd(time()).'"
		   },
		   "remark":{
			   "value":"如有问题请联系我们客服,祝您生活愉快!"
		   }
		';
	break;
	//-----------------------------------------
	case 'daigou_PayRef_4':
		//global $rs,$pay_money,$cz;
		$MLG=memberLT($rs['userid']);
		$send_title=$MLG['daigou.21'].cadd($rs['dgdh']).$MLG['daigou.24'].$pay_money.$rs['priceCurrency'];
		$send_content_msg=$send_title.'('.$MLG['daigou.37'].$cz['toMoney'].$cz['toCurrency'].')';
		$send_content_sms=$send_content_msg;
		$send_content_mail=$send_content_msg.'<br><br>【<a href="'.$siteurl.'xamember/daigou/list.php?status=all"><strong>'.$MLG['loginWebsite'].'</strong></a>】';
		//微信
		$send_WxTemId='OPENTM406675616';$send_WxTemName='支付账单通知';
		$send_content_wx='
		  "first": {
			   "value":"'.$send_content_sms.'"
		   },
		   "keyword1":{
			   "value":"'.$pay_money.$rs['priceCurrency'].'"
		   },
		   "keyword2": {
			   "value":"'.DateYmd(time()).'"
		   },
		   "remark":{
			   "value":"如有问题请联系我们客服,祝您生活愉快!"
		   }
		';
	break;
	//-----------------------------------------
	case 'daigou_numberRet':
		//global $rs,$refundMoney,$cz;
		$MLG=memberLT($rs['userid']);
		$send_title=$MLG['daigou.21'].cadd($rs['dgdh']).$MLG['daigou.24'].$refundMoney.$rs['priceCurrency'];
		$send_content_msg=$send_title.'('.$MLG['daigou.37'].$cz['toMoney'].$cz['toCurrency'].')';
		$send_content_sms=$send_content_msg;
		$send_content_mail=$send_content_msg.'<br><br>【<a href="'.$siteurl.'xamember/daigou/list.php?status=all"><strong>'.$MLG['loginWebsite'].'</strong></a>】';
		//微信
		$send_WxTemId='OPENTM406675616';$send_WxTemName='支付账单通知';
		$send_content_wx='
		  "first": {
			   "value":"'.$send_content_sms.'"
		   },
		   "keyword1":{
			   "value":"'.$refundMoney.$rs['priceCurrency'].'"
		   },
		   "keyword2": {
			   "value":"'.DateYmd(time()).'"
		   },
		   "remark":{
			   "value":"如有问题请联系我们客服,祝您生活愉快!"
		   }
		';
	break;
	//-----------------------------------------
	case 'daigou_cancel':
		//global $rs,$totalPay,$cz;
		$MLG=memberLT($rs['userid']);
		$send_title=$MLG['daigou.21'].cadd($rs['dgdh']).$MLG['daigou.24'].$totalPay.$rs['priceCurrency'];
		$send_content_msg=$send_title.'('.$MLG['daigou.37'].$cz['toMoney'].$cz['toCurrency'].')';
		$send_content_sms=$send_content_msg;
		$send_content_mail=$send_content_msg.'<br><br>【<a href="'.$siteurl.'xamember/daigou/list.php?status=all"><strong>'.$MLG['loginWebsite'].'</strong></a>】';
		//微信
		$send_WxTemId='OPENTM406675616';$send_WxTemName='支付账单通知';
		$send_content_wx='
		  "first": {
			   "value":"'.$send_content_sms.'"
		   },
		   "keyword1":{
			   "value":"'.$totalPay.$rs['priceCurrency'].'"
		   },
		   "keyword2": {
			   "value":"'.DateYmd(time()).'"
		   },
		   "remark":{
			   "value":"如有问题请联系我们客服,祝您生活愉快!"
		   }
		';
	break;
	//-----------------------------------------
	case 'yundan_updateStatus':
		//global $rs;
		$MLG=memberLT($rs['userid']);
		$send_title=LGtag($MLG['yundan.Xcall_show_10'],'<tag1>=='.cadd($rs['ydh']).'||<tag2>=='.$status_name);
		$send_content_msg=$send_title.$MLG['yundan.Xcall_calc_payment_18'];
		$send_content_sms=$send_title.$MLG['yundan.Xcall_calc_payment_19'];
		$send_content_mail=$send_content_sms.'<br><br>【<a href="'.$siteurl.'xamember/yundan/list.php?status=all"><strong>'.$MLG['loginWebsite'].'</strong></a>】';
		//微信
		$send_WxTemId='OPENTM406713426';$send_WxTemName='运单状态通知';
		$send_content_wx='
		  "first": {
			   "value":"您的运单状态已更新"
		   },
		   "keyword1":{
			   "value":"'.$status_name.'"
		   },
		   "keyword2": {
			   "value":"'.cadd($rs['ydh']).'"
		   },
		   "remark":{
			   "value":"如有问题请联系我们客服,祝您生活愉快!"
		   }
		';
	break;
	//-----------------------------------------
	case 'xingao_hxfx':
		//global $send_title,$MLG,$weight,$_POST,$content,$rs;
		$send_content_msg=$send_title.$MLG['baoguo.send_6'];
		$send_content_sms=$send_title.$MLG['baoguo.send_7'];
		$send_content_mail=$send_content_sms.'<br>'.$MLG['baoguo.send_1'].$weight.$XAwt.'<br>'.$MLG['baoguo.send_2'].$content.'<br>'.$MLG['baoguo.send_3'].cadd($_POST['reply']).'<br>'.$MLG['baoguo.send_8'].date('Y-m-d H:i',time()).'<br><br>【<a href="'.$siteurl.'xamember/baoguo/list.php?status=ruku"><strong>'.$MLG['LinkWebsite'].'</strong></a>】';
		//微信
		$send_WxTemId='OPENTM207433805';$send_WxTemName='合箱包裹状态变更通知';
		$send_content_wx='
		  "first": {
			   "value":"'.$send_content_sms.'"
		   },
		   "keyword1":{
			   "value":"'.cadd($rs['bgydh']).'"
		   },
		   "keyword2": {
			   "value":"'.DateYmd(time()).'"
		   },
		   "remark":{
			   "value":"如有问题请联系我们客服,祝您生活愉快!"
		   }
		';
	break;
	//-----------------------------------------
	case 'xingao_settlement_pay_yundan':
		//global $rs;
		$MLG=memberLT($rs['userid']);
		$send_title=$MLG['settlement.Xcall_pay_11'];
		$send_content_msg=$send_title.$MLG['settlement.save_13'];
		$send_content_sms=$send_title.$MLG['settlement.save_13'];
		$send_content_mail=$send_content_sms.'<br><br>【<a href="'.$siteurl.'xamember/settlement/list.php"><strong>'.$MLG['loginWebsite'].'</strong></a>】';
		//微信
		$send_WxTemId='OPENTM406675616';$send_WxTemName='支付账单通知';
		$send_content_wx='
		  "first": {
			   "value":"'.$send_content_sms.'"
		   },
		   "keyword1":{
			   "value":"'.$settlement_money.'"
		   },
		   "keyword2": {
			   "value":"'.DateYmd(time()).'"
		   },
		   "remark":{
			   "value":"如有问题请联系我们客服,祝您生活愉快!"
		   }
		';
	break;
	//-----------------------------------------
	case 'xingao_settlement_pay_other':
		//global $rs;
		$MLG=memberLT($rs['userid']);
		$send_title=$MLG['settlement.Xcall_pay_7'];
		$send_content_msg=$send_title.$MLG['settlement.save_13'];
		$send_content_sms=$send_title.$MLG['settlement.save_13'];
		$send_content_mail=$send_content_sms.'<br><br>【<a href="'.$siteurl.'xamember/settlement/list.php"><strong>'.$MLG['loginWebsite'].'</strong></a>】';
		//微信
		$send_WxTemId='OPENTM406675616';$send_WxTemName='支付账单通知';
		$send_content_wx='
		  "first": {
			   "value":"'.$send_content_sms.'"
		   },
		   "keyword1":{
			   "value":"'.$settlement_money.'"
		   },
		   "keyword2": {
			   "value":"'.DateYmd(time()).'"
		   },
		   "remark":{
			   "value":"如有问题请联系我们客服,祝您生活愉快!"
		   }
		';
	break;
	//-----------------------------------------
	case 'xingao_settlement_save_yundan':
		//global $MLG;
		$send_title=$MLG['settlement.send_1'];
		$send_content_msg=$send_title.$MLG['settlement.send_2'];
		$send_content_sms=$send_title.$MLG['settlement.send_3'];
		$send_content_mail=$send_content_sms.'<br><br>【<a href="'.$siteurl.'xamember/settlement/list.php"><strong>'.$MLG['LinkWebsite'].'</strong></a>】';
		//微信
		$send_WxTemId='OPENTM400961252';$send_WxTemName='对账单生成通知';
		$send_content_wx='
		  "first": {
			   "value":"'.$send_content_sms.'"
		   },
		   "keyword1":{
			   "value":"'.DateYmd(time()).'"
		   },
		   "keyword2": {
			   "value":"请登录网站查看"
		   },
		   "keyword3": {
			   "value":"'.$settlement_money.$XAmc.'"
		   },
		   "remark":{
			   "value":"如有问题请联系我们客服,祝您生活愉快!"
		   }
		';
	break;
	//-----------------------------------------
	case 'xingao_settlement_save_other':
		//global $MLG;
		$send_title=$MLG['settlement.send_4'];
		$send_content_msg=$send_title.$MLG['settlement.send_2'];
		$send_content_sms=$send_title.$MLG['settlement.send_3'];
		$send_content_mail=$send_content_sms.'<br><br>【<a href="'.$siteurl.'xamember/settlement/list.php"><strong>'.$MLG['LinkWebsite'].'</strong></a>】';
		//微信
		$send_WxTemId='OPENTM400961252';$send_WxTemName='对账单生成通知';
		$send_content_wx='
		  "first": {
			   "value":"'.$send_content_sms.'"
		   },
		   "keyword1":{
			   "value":"'.DateYmd(time()).'"
		   },
		   "keyword2": {
			   "value":"请登录网站查看"
		   },
		   "keyword3": {
			   "value":"'.$settlement_money.$XAmc.'"
		   },
		   "remark":{
			   "value":"如有问题请联系我们客服,祝您生活愉快!"
		   }
		';
	break;
	//-----------------------------------------
	case 'xingao_calc_refund':
		//global $rs,$re_content,$re_money;
		$MLG=memberLT($rs['userid']);
		$send_title=$re_content.'('.$re_money.$XAmc.')';
		$send_content_msg=$send_title.$MLG['yundan.Xcall_calc_payment_18'];
		$send_content_sms=$send_title.$MLG['yundan.Xcall_calc_payment_19'];
		$send_content_mail=$send_content_sms.'<br><br>【<a href="'.$siteurl.'xamember/yundan/list.php?status=all"><strong>'.$MLG['loginWebsite'].'</strong></a>】';
		//微信
		$send_WxTemId='OPENTM409025265';$send_WxTemName='退款通知';
		$send_content_wx='
		  "first": {
			   "value":"'.$send_content_sms.'"
		   },
		   "keyword1":{
			   "value":"'.$re_money.$XAmc.'"
		   },
		   "keyword2": {
			   "value":"账户余额"
		   },
		   "keyword3": {
			   "value":"'.DateYmd(time()).'"
		   },
		   "remark":{
			   "value":"如有问题请联系我们客服,祝您生活愉快!"
		   }
		';
	break;
	//-----------------------------------------
	case 'xingao_calc_payment_1':
		//global $rs,$tz,$pay_money;
		$MLG=memberLT($rs['userid']);
		$send_title=$MLG['yundan.Xcall_calc_payment_4'].cadd($rs['ydh']).$tz.'('.$pay_money.$XAmc.')'.$MLG['yundan.Xcall_calc_payment_17'];
		$send_content_msg=$send_title.$MLG['yundan.Xcall_calc_payment_18'];
		$send_content_sms=$send_title.$MLG['yundan.Xcall_calc_payment_19'];
		$send_content_mail=$send_content_sms.'<br><br>【<a href="'.$siteurl.'xamember/yundan/list.php?status=all"><strong>'.$MLG['loginWebsite'].'</strong></a>】';
		//微信
		$send_WxTemId='OPENTM200996096';$send_WxTemName='订单未支付提醒';
		$send_content_wx='
		  "first": {
			   "value":"'.$send_content_sms.'"
		   },
		   "keyword1":{
			   "value":"'.cadd($rs['ydh']).'"
		   },
		   "keyword2": {
			   "value":"'.spr($rs['weight']).$XAwt.'"
		   },
		   "keyword3": {
			   "value":"'.$pay_money.$XAmc.'"
		   },
		   "keyword4": {
			   "value":"运费/税费"
		   },
		   "remark":{
			   "value":"如有问题请联系我们客服,祝您生活愉快!"
		   }
		';
	break;
	//-----------------------------------------
	case 'xingao_calc_payment_2':
		//global $rs,$tz,$pay_money;
		$MLG=memberLT($rs['userid']);
		$send_title=$MLG['yundan.Xcall_calc_payment_4'].cadd($rs['ydh']).$tz.'('.$pay_money.$XAmc.')';
		$send_content_msg=$send_title.$MLG['yundan.Xcall_calc_payment_18'];
		$send_content_sms=$send_title.$MLG['yundan.Xcall_calc_payment_19'];
		$send_content_mail=$send_content_sms.'<br><br>【<a href="'.$siteurl.'xamember/yundan/list.php?status=all"><strong>'.$MLG['loginWebsite'].'</strong></a>】';
		//微信
		$send_WxTemId='OPENTM200996096';$send_WxTemName='订单未支付提醒';
		$send_content_wx='
		  "first": {
			   "value":"'.$send_content_sms.'"
		   },
		   "keyword1":{
			   "value":"'.cadd($rs['ydh']).'"
		   },
		   "keyword2": {
			   "value":"'.spr($rs['weight']).$XAwt.'"
		   },
		   "keyword3": {
			   "value":"'.$pay_money.$XAmc.'"
		   },
		   "keyword4": {
			   "value":"运费/税费"
		   },
		   "remark":{
			   "value":"如有问题请联系我们客服,祝您生活愉快!"
		   }
		';
	break;
	//-----------------------------------------
	case 'xingao_calc_payment_3':
		//global $rs,$tz,$pay_money;
		$MLG=memberLT($rs['userid']);
		$send_title=$MLG['yundan.Xcall_calc_payment_4'].cadd($rs['ydh']).$tz.'('.$pay_money.$XAmc.')';
		$send_content_msg=$send_title.$MLG['yundan.Xcall_calc_payment_18'];
		$send_content_sms=$send_title.$MLG['yundan.Xcall_calc_payment_19'];
		$send_content_mail=$send_content_sms.'<br><br>【<a href="'.$siteurl.'xamember/yundan/list.php?status=all"><strong>'.$MLG['loginWebsite'].'</strong></a>】';
		//微信
		$send_WxTemId='OPENTM200996096';$send_WxTemName='订单未支付提醒';
		$send_content_wx='
		  "first": {
			   "value":"'.$send_content_sms.'"
		   },
		   "keyword1":{
			   "value":"'.cadd($rs['ydh']).'"
		   },
		   "keyword2": {
			   "value":"'.spr($rs['weight']).$XAwt.'"
		   },
		   "keyword3": {
			   "value":"'.$pay_money.$XAmc.'"
		   },
		   "keyword4": {
			   "value":"运费/税费"
		   },
		   "remark":{
			   "value":"如有问题请联系我们客服,祝您生活愉快!"
		   }
		';
	break;
	//-----------------------------------------
	case 'xingao_member_birthday':
		//global $MLG,$send_content;
		$send_title=$MLG['member.1'];
		$send_content_msg=$send_title.$send_content;
		$send_content_sms=$send_title.$send_content;
		$send_content_mail=$send_title.$send_content;
		//不支持微信
	break;
	//-----------------------------------------
	case 'xingao_member_checked_1':
		//global $rs;
		$MLG=memberLT($rs['userid']);
		$send_title=LGtag($MLG['member.2'],'<tag1>=='.cadd($rs['truename']));
		$send_content_msg=LGtag($MLG['member.3'],'<tag1>=='.$sitename);
		$send_content_sms=$send_content_msg;
		$send_content_mail=$send_content_sms.(LGtag($MLG['member.4'],'<tag1>=='.cadd($rs['username']))).$siteurl.'member/';
		/*需要查询字段:addtime*/
		//微信
		$send_WxTemId='OPENTM201620385';$send_WxTemName='注册成功通知';
		$send_content_wx='
		  "first": {
			   "value":"'.$send_content_sms.'"
		   },
		   "keyword1":{
			   "value":"'.cadd($rs['username']).'"
		   },
		   "keyword2": {
			   "value":"'.DateYmd($rs['addtime']).'"
		   },
		   "remark":{
			   "value":"如有问题请联系我们客服,祝您生活愉快!"
		   }
		';
	break;
	//-----------------------------------------
	case 'xingao_member_checked_0':
		//global $rs;
		$MLG=memberLT($rs['userid']);
		$send_title=LGtag($MLG['member.5'],'<tag1>=='.cadd($rs['truename']));
		$send_content_msg=LGtag($MLG['member.6'],'<tag1>=='.$sitename.'||<tag2>=='.cadd($rs['username']));
		$send_content_sms=$send_content_msg;
		$send_content_mail=$send_content_sms.$MLG['member.7'].$siteurl;
		//微信
		$send_WxTemId='OPENTM202441759';$send_WxTemName='注册失败通知';
		$send_content_wx='
		  "first": {
			   "value":"'.$send_content_sms.'"
		   },
		   "keyword1":{
			   "value":"'.cadd($rs['username']).'"
		   },
		   "keyword2": {
			   "value":"'.DateYmd($rs['addtime']).'"
		   },
		   "remark":{
			   "value":"如有问题请联系我们客服,祝您生活愉快!"
		   }
		';
	break;
	
	
	
	
	
	
	
	
	
	
	
	
	//模板和发送-------------------------------------------------------------------------
	//包裹入库通知-----------------------------------------
	/*需要有$bgid*/
	case 'baoguo_notice_storage':
		$send_msg=$baoguo_ruku_msg;
		$send_mail=$baoguo_ruku_mail;
		$send_sms=$baoguo_ruku_sms;
		$send_wx=$baoguo_ruku_wx;
		if($bgid &&($send_msg||$send_mail||$send_sms||$send_wx))
		{
			$rs=FeData('baoguo','bgydh,weight,content,reply,rukutime,userid,username',"bgid='{$bgid}' and status>1");//查询
			
			$MLG=memberLT($rs['userid']);
			$goodsdescribe=goodsdescribe2('baoguo',$bgid);
			$send_content='<br>'.$MLG['baoguo.send_1'].spr($rs['weight']).$XAwt.'<br>'.$MLG['baoguo.send_1_1'].$goodsdescribe.'<br>'.$MLG['baoguo.send_2'].cadd($rs['content']).'<br>'.$MLG['baoguo.send_3'].cadd($rs['reply']).'<br>'.$MLG['baoguo.send_4'].DateYmd($rs['rukutime']).'<br>';
			
			$send_title=LGtag($MLG['baoguo.send_13'],'<tag1>=='.cadd($rs['bgydh']));
			$send_content_msg=$send_title.$send_content.$MLG['baoguo.send_6'];
			$send_content_sms=$send_title.$send_content.$MLG['baoguo.send_7'];
			$send_content_mail=$send_title.$send_content.'<br><br>【<a href="'.$siteurl.'xamember/baoguo/list.php?status=ruku"><strong>'.$MLG['LinkWebsite'].'</strong></a>】';
			//微信
			$send_WxTemId='OPENTM400181876';$send_WxTemName='货物入库提醒';
			$send_content_wx='
			  "first": {
				   "value":"您的包裹已入库"
		   },
			   "keyword1":{
				   "value":"'.cadd($rs['bgydh']).'"
		   },
			   "keyword2": {
				   "value":"'.$goodsdescribe.'"
		   },
			   "keyword3": {
				   "value":"'.spr($rs['weight']).$XAwt.'"
		   },
			   "keyword4": {
				   "value":"'.DateYmd($rs['rukutime']).'"
		   },
			   "remark":{
				   "value":"如有问题请联系我们客服,祝您生活愉快!"
		   }
			';
	
			//发站内信息
			if($send_msg){SendMsg($rs['userid'],$rs['username'],$send_title,$send_content_msg,$send_file,$from_userid='',$from_username='',$new=1,$status=0,$issys=1,$xs=0);}
			//发邮件
			if($send_mail){SendMail($rsemail='',$send_title,$send_content_mail,$send_file,$issys=1,$xs=0,$rs['userid'],$notify=1);}//$notify=1有批量发送或内容不重要,用异步发送
			//发短信
			if($send_sms){SendSMS($rsmobile='',$send_content_sms,$xs=0,$rs['userid']);}
			//发微信
			if($send_wx){SendWX($send_WxTemId,$send_WxTemName,$send_content_wx,$rs['userid']);}
		}
	break;
	
	
	//代购入库通知-----------------------------------------
	/*需要有$dgid,$in_spec,$in_number,$in_weight*/
	case 'daigou_notice_storage':
		$send_msg=$daigou_inStorage_msg;
		$send_mail=$daigou_inStorage_mail;
		$send_sms=$daigou_inStorage_sms;
		$send_wx=$daigou_inStorage_wx;
		if($dgid && ($send_msg||$send_mail||$send_sms||$send_wx))
		{
			$rs=FeData('daigou','*',"dgid='{$dgid}'");//查询
			$MLG=memberLT($rs['userid']);
			
			$send_title=LGtag($MLG['daigou.inStorageSend_1'],'<tag1>=='.cadd($rs['dgdh']));
			$send_content=LGtag($MLG['daigou.inStorageSend_2'],'<tag1>=='.$in_spec.'||<tag2>=='.$in_number.'||<tag3>=='.$in_weight.$XAwt);
			$send_content_msg=$send_title.$send_content.$MLG['daigou.inStorageSend_3'];
			$send_content_sms=$send_title.$send_content.$MLG['daigou.inStorageSend_4'];
			$send_content_mail=$send_title.$send_content.'<br><br>【<a href="'.$siteurl.'xamember/daigou/list.php?status=inStorage"><strong>'.$MLG['LinkWebsite'].'</strong></a>】';
			//微信
			$send_WxTemId='OPENTM400181876';$send_WxTemName='货物入库提醒';
			$send_content_wx='
			  "first": {
				   "value":"您的代购单有商品已入库"
		   },
			   "keyword1":{
				   "value":"'.cadd($rs['dgdh']).'"
		   },
			   "keyword2": {
				   "value":"'.$send_content.'"
		   },
			   "keyword3": {
				   "value":"'.spr($in_weight).$XAwt.'"
		   },
			   "keyword4": {
				   "value":"'.DateYmd(time()).'"
		   },
			   "remark":{
				   "value":"如有问题请联系我们客服,祝您生活愉快!"
		   }
			';
	
		
			//发站内信息
			if($send_msg){SendMsg($rs['userid'],$rs['username'],$send_title,$send_content_msg,$send_file,$from_userid='',$from_username='',$new=1,$status=0,$issys=1,$xs=0);}
			//发邮件
			if($send_mail){SendMail($rsemail='',$send_title,$send_content_mail,$send_file,$issys=1,$xs=0,$rs['userid'],$notify=1);}//$notify=1有批量发送或内容不重要,用异步发送
			//发短信
			if($send_sms){SendSMS($rsmobile='',$send_content_sms,$xs=0,$rs['userid']);}
			//发微信
			if($send_wx){SendWX($send_WxTemId,$send_WxTemName,$send_content_wx,$rs['userid']);}
		}
	break;
	
	
	
	
}
?>