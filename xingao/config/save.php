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
$pervar='manage_sy';require_once($_SERVER['DOCUMENT_ROOT'].'/xingao/incluce/session.php');

//获取,处理=====================================================
$lx=par($_REQUEST['lx']);
$tokenkey=par($_POST['tokenkey']);

//验证,保存=====================================================
if($lx=='edit')
{
	//通用验证------------------------------------------------------------------------------------------------------------
	$token=new Form_token_Core();
	$token->is_token("config_sys",$tokenkey); //验证令牌密钥
	
	//其他处理------------------------------------------------------------------------------------------------------------
	
	if((int)$_POST['member_ic']<5){$_POST['dg_ic']=5;}
	if((int)$_POST['dg_whcodLength']<5){$_POST['dg_whcodLength']=5;}
	
	//生成QQ登录配置文件
	
	if($_POST['off_connect_qq'])
	{	//不能有空行
		$date_now='<?php die(\'forbidden\'); //此文件格式有特别要求,因此要单独生成,不能直接获取值?>
		{"appid":"'.add($_POST['connect_qqid']).'","appkey":"'.add($_POST['connect_qqkey']).'","callback":"'.$siteurl.'api/login/qq/return.php","scope":"get_user_info,add_share,list_album,add_album,upload_pic,add_topic,add_one_blog,add_weibo,check_page_fans,add_t,add_pic_t,del_t,get_repost_list,get_info,get_other_info,get_fanslist,get_idolist,add_idol,del_idol,get_tenpay_addr"}
		';
	}else{//不能有空行
		$date_now='<?php die(\'forbidden\'); //此文件格式有特别要求,因此要单独生成,不能直接获取值?>
		{"appid":"未开启QQ登录","appkey":"未开启QQ登录"}';
	}
	file_put_contents($_SERVER['DOCUMENT_ROOT'].'/cache/qq_inc.php',$date_now);//生成文件
	


	//处理数据------------------------------------------------------------------------------------------------------------
	
	//处理网址
	$siteurl=add($_POST['siteurl']);
	if(!$siteurl)
	{
		$siteurl='/';
	}else{
		$siteurl=addhttp($siteurl);//自动加http://
		if (!stristr(substr($siteurl,-1),'/')){$siteurl=$siteurl.'/';}
	}

	//处理购物网站
	$wangzhan=yanzheng("包裹购物网站",add($_POST['wangzhan']));

	//处理寄库快递公司
	$zhi=html(str_replace("  "," ",add($_POST['baoguo_kuaidi'])));//获取并清空多空格
	$zhi=str_replace("\n",",",str_replace("\r\n",",",$zhi));//把行替换为“,”
	$x=1;
	while($x==1) {
	 $zhi=str_replace(",,",",",$zhi); //清除空行
	 if (strpos($zhi,',,')===false)
	 {$x=0;}
	 else
 	 {$x=1;}
	} 
	$baoguo_kuaidi=str_replace(",","\n",$zhi);
	
	
	
	//------------------------------------------------------------------------------------------------------------
	//生成数据－开始
	$pagetext='<?php';
	$data.='
/*
软件著作权：=====================================================
软件名称：兴奥国际物流转运网站管理系统(简称：兴奥转运系统)V7.0
著作权人：广西兴奥网络科技有限责任公司
软件登记号：2016SR041223
网址：www.xingaowl.com
本系统已在中华人民共和国国家版权局注册，著作权受法律及国际公约保护！
版权所有，未购买严禁使用，未经书面许可严禁开发衍生品，违反将追究法律责任！
*/
if(!defined(\'InXingAo\')){exit(\'No InXingAo\');}



	//首要配置和载入-------------------------------------------------------------
	//语言配置
	';
	
	$_POST['ONLanguage']=ArrDel($_POST['ONLanguage'],$_POST['LGDefault'],1,',');//删除默认语种
	$_POST['ONLanguage']=$_POST['LGDefault'].','.$_POST['ONLanguage'];//把默认语种放第一位
	$_POST['ONLanguage']=DelStr($_POST['ONLanguage']);
	if(arrcount($_POST['ONLanguage'])>1){$ON_LG=1;}
	
	
	$data.='
	$ON_LG=\''.$ON_LG.'\';//多语种
	$LGDefault=\''.add($_POST['LGDefault']).'\';//默认语种
	$ONLanguage=\''.add(ToStr(array_unique(ToArr($_POST['ONLanguage'])))).'\';//支持的语种
	$openLanguage=\''.add(DelStr(ToStr($_POST['openLanguage']))).'\';//正式开放的语种
	
	//语言载入
	if(!$LGType){$LGType=3;require_once($_SERVER[\'DOCUMENT_ROOT\'].\'/Language/index.php\');}
	if($LT==\'CN\'){$jishu= \'兴奥转运系统\';}else{$jishu=\'XingAo TS\';}//后台系统名称
	
	//组文件放在前面,下面用到
	require_once($_SERVER[\'DOCUMENT_ROOT\'].\'/cache/member_per.php\');
	require_once($_SERVER[\'DOCUMENT_ROOT\'].\'/cache/manage_per.php\');
	
	
	
	
	//基本配置-开始-------------------------------------------------------------
	
	//语言字段处理++
	';
	
	
	
//语言字段处理++
if(!$LGList){$LGList=languageType('',3);}
if($LGList)
{
	foreach($LGList as $arrkey=>$language)
	{
		$data.='
		$sitename'.$language.'=\''.add($_POST['sitename'.$language]).'\';
		$sitetitle'.$language.'=\''.add($_POST['sitetitle'.$language]).'\';
		$sitekey'.$language.'=\''.add($_POST['sitekey'.$language]).'\';
		$sitetext'.$language.'=\''.add($_POST['sitetext'.$language]).'\';
		$site_member_ts'.$language.'=\''.add($_POST['site_member_ts'.$language]).'\';
		$site_manage_ts'.$language.'=\''.add($_POST['site_manage_ts'.$language]).'\';
		$bankAccount'.$language.'=\''.add($_POST['bankAccount'.$language]).'\';
		$sendName'.$language.'=\''.add($_POST['sendName'.$language]).'\';
		$sendMobile'.$language.'=\''.add($_POST['sendMobile'.$language]).'\';
		$sendTel'.$language.'=\''.add($_POST['sendTel'.$language]).'\';
		$sendFax'.$language.'=\''.add($_POST['sendFax'.$language]).'\';
		$sendZip'.$language.'=\''.add($_POST['sendZip'.$language]).'\';
		$sendAdd'.$language.'=\''.add($_POST['sendAdd'.$language]).'\';
		$mallFAQ'.$language.'=\''.add($_POST['mallFAQ'.$language]).'\';
		$member_reg_sendmail'.$language.'=\''.add($_POST['member_reg_sendmail'.$language]).'\';
		$member_birthday_content'.$language.'=\''.add($_POST['member_birthday_content'.$language]).'\';
		$smtp_name'.$language.'=\''.add($_POST['smtp_name'.$language]).'\';
		$cs'.$language.'=\''.add($_POST['cs'.$language]).'\';
		$cs_m'.$language.'=\''.add($_POST['cs_m'.$language]).'\';
		$shaidan_explain'.$language.'=\''.add($_POST['shaidan_explain'.$language]).'\';
		
		//运单服务选项-名称
		$op_bgfee1_name'.$language.'=\''.add($_POST['op_bgfee1_name'.$language]).'\';
		$op_bgfee2_name'.$language.'=\''.add($_POST['op_bgfee2_name'.$language]).'\';
		$op_wpfee1_name'.$language.'=\''.add($_POST['op_wpfee1_name'.$language]).'\';
		$op_wpfee2_name'.$language.'=\''.add($_POST['op_wpfee2_name'.$language]).'\';
		$op_ydfee1_name'.$language.'=\''.add($_POST['op_ydfee1_name'.$language]).'\';
		$op_ydfee2_name'.$language.'=\''.add($_POST['op_ydfee2_name'.$language]).'\';
		$op_free_name'.$language.'=\''.add($_POST['op_free_name'.$language]).'\';
		$op_freearr_name'.$language.'=\''.add($_POST['op_freearr_name'.$language]).'\';
		//运单服务选项-提示说明
		$op_bgfee1_ppt'.$language.'=\''.add($_POST['op_bgfee1_ppt'.$language]).'\';
		$op_bgfee2_ppt'.$language.'=\''.add($_POST['op_bgfee2_ppt'.$language]).'\';
		$op_wpfee1_ppt'.$language.'=\''.add($_POST['op_wpfee1_ppt'.$language]).'\';
		$op_wpfee2_ppt'.$language.'=\''.add($_POST['op_wpfee2_ppt'.$language]).'\';
		$op_ydfee1_ppt'.$language.'=\''.add($_POST['op_ydfee1_ppt'.$language]).'\';
		$op_ydfee2_ppt'.$language.'=\''.add($_POST['op_ydfee2_ppt'.$language]).'\';
		$op_free_ppt'.$language.'=\''.add($_POST['op_free_ppt'.$language]).'\';
		$op_freearr_ppt'.$language.'=\''.add($_POST['op_freearr_ppt'.$language]).'\';
		';
		
		//运单服务选项-名称
		for ($i_val=0; $i_val<=10; $i_val++)
		{
			$data.='
			$op_bgfee1_val'.$i_val.$language.'=\''.add($_POST['op_bgfee1_val'.$i_val.$language]).'\';
			$op_bgfee2_val'.$i_val.$language.'=\''.add($_POST['op_bgfee2_val'.$i_val.$language]).'\';
			$op_wpfee1_val'.$i_val.$language.'=\''.add($_POST['op_wpfee1_val'.$i_val.$language]).'\';
			$op_wpfee2_val'.$i_val.$language.'=\''.add($_POST['op_wpfee2_val'.$i_val.$language]).'\';
			$op_ydfee1_val'.$i_val.$language.'=\''.add($_POST['op_ydfee1_val'.$i_val.$language]).'\';
			$op_ydfee2_val'.$i_val.$language.'=\''.add($_POST['op_ydfee2_val'.$i_val.$language]).'\';
			$op_free_val'.$i_val.$language.'=\''.add($_POST['op_free_val'.$i_val.$language]).'\';
			$op_freearr_val'.$i_val.$language.'=\''.add($_POST['op_freearr_val'.$i_val.$language]).'\';
			';
		}
	}
}

	  $data.='
	  //自动显示当前语种名称
	  
	  $joint=\'sitename\'.$LT; $sitename=$$joint;
	  $joint=\'sitetitle\'.$LT; $sitetitle=$$joint;
	  $joint=\'sitekey\'.$LT; $sitekey=$$joint;
	  $joint=\'sitetext\'.$LT; $sitetext=$$joint;
	  $joint=\'site_member_ts\'.$LT; $site_member_ts=$$joint;
	  $joint=\'site_manage_ts\'.$LT; $site_manage_ts=$$joint;
	  $joint=\'bankAccount\'.$LT; $bankAccount=$$joint;
	  
	  //已改用function调用,支持指定语种
	  /*
	  $joint=\'sendName\'.$LT; $sendName=$$joint;
	  $joint=\'sendMobile\'.$LT; $sendMobile=$$joint;
	  $joint=\'sendTel\'.$LT; $sendTel=$$joint;
	  $joint=\'sendFax\'.$LT; $sendFax=$$joint;
	  $joint=\'sendZip\'.$LT; $sendZip=$$joint;
	  $joint=\'sendAdd\'.$LT; $sendAdd=$$joint;
	  */
	  
	  $joint=\'mallFAQ\'.$LT; $mallFAQ=$$joint;
	  $joint=\'member_reg_sendmail\'.$LT; $member_reg_sendmail=$$joint;
	  $joint=\'member_birthday_content\'.$LT; $member_birthday_content=$$joint;
	  $joint=\'smtp_name\'.$LT; $smtp_name=$$joint;
	  $joint=\'cs\'.$LT; $cs=$$joint;
	  $joint=\'cs_m\'.$LT; $cs_m=$$joint;
	  $joint=\'shaidan_explain\'.$LT; $shaidan_explain=$$joint;
	  
	  $joint=\'op_bgfee1_name\'.$LT; $op_bgfee1_name=$$joint;
	  $joint=\'op_bgfee2_name\'.$LT; $op_bgfee2_name=$$joint;
	  $joint=\'op_wpfee1_name\'.$LT; $op_wpfee1_name=$$joint;
	  $joint=\'op_wpfee2_name\'.$LT; $op_wpfee2_name=$$joint;
	  $joint=\'op_ydfee1_name\'.$LT; $op_ydfee1_name=$$joint;
	  $joint=\'op_ydfee2_name\'.$LT; $op_ydfee2_name=$$joint;
	  $joint=\'op_free_name\'.$LT;	 $op_free_name=$$joint;
	  $joint=\'op_freearr_name\'.$LT; $op_freearr_name=$$joint;
	  
	  $joint=\'op_bgfee1_ppt\'.$LT; $op_bgfee1_ppt=$$joint;
	  $joint=\'op_bgfee2_ppt\'.$LT; $op_bgfee2_ppt=$$joint;
	  $joint=\'op_wpfee1_ppt\'.$LT; $op_wpfee1_ppt=$$joint;
	  $joint=\'op_wpfee2_ppt\'.$LT; $op_wpfee2_ppt=$$joint;
	  $joint=\'op_ydfee1_ppt\'.$LT; $op_ydfee1_ppt=$$joint;
	  $joint=\'op_ydfee2_ppt\'.$LT; $op_ydfee2_ppt=$$joint;
	  $joint=\'op_free_ppt\'.$LT;	 $op_free_ppt=$$joint;
	  $joint=\'op_freearr_ppt\'.$LT; $op_freearr_ppt=$$joint;
	  ';
	  
	  //运单服务选项名称
	  for ($i_val=0; $i_val<=10; $i_val++)
	  {
		  $data.='
		  $joint=\'op_bgfee1_val'.$i_val.'\'.$LT; $op_bgfee1_val'.$i_val.'=$$joint;
		  $joint=\'op_bgfee2_val'.$i_val.'\'.$LT; $op_bgfee2_val'.$i_val.'=$$joint;
		  $joint=\'op_wpfee1_val'.$i_val.'\'.$LT; $op_wpfee1_val'.$i_val.'=$$joint;
		  $joint=\'op_wpfee2_val'.$i_val.'\'.$LT; $op_wpfee2_val'.$i_val.'=$$joint;
		  $joint=\'op_ydfee1_val'.$i_val.'\'.$LT; $op_ydfee1_val'.$i_val.'=$$joint;
		  $joint=\'op_ydfee2_val'.$i_val.'\'.$LT; $op_ydfee2_val'.$i_val.'=$$joint;
		  $joint=\'op_free_val'.$i_val.'\'.$LT;   $op_free_val'.$i_val.'=$$joint;
		  $joint=\'op_freearr_val'.$i_val.'\'.$LT; $op_freearr_val'.$i_val.'=$$joint;
		  ';
	  }
	
	
	allocation($pcmion);
	$data.='
	$siteurl=\''.$siteurl.'\';
	$off_site_member=\''.add($_POST['off_site_member']).'\';
	$off_site_manage=\''.add($_POST['off_site_manage']).'\';
	$off_member_nav=\''.add($_POST['off_member_nav']).'\';
	
	$traffic=\''.add($_POST['traffic']).'\';
	$manage_login_yz=\''.add($_POST['manage_login_yz']).'\';
	$manage_login_limit=\''.add($_POST['manage_login_limit']).'\';
	$manage_limit_time=\''.add($_POST['manage_limit_time']).'\';
	$theme_member=\''.add($_POST['theme_member']).'\';
	$theme_member_ico=\''.add($_POST['theme_member_ico']).'\';
	$theme_manage=\''.add($_POST['theme_manage']).'\';
	$theme_manage_ico=\''.add($_POST['theme_manage_ico']).'\';
	
	//验证码
	$off_code_managelogin=\''.add($_POST['off_code_managelogin']).'\';
	$off_code_reg=\''.add($_POST['off_code_reg']).'\';
	$off_code_login=\''.add($_POST['off_code_login']).'\';
	$off_code_liuyan=\''.add($_POST['off_code_liuyan']).'\';
	$off_code_shangpin_sp=\''.add($_POST['off_code_shangpin_sp']).'\';
	$off_code_shaidan_sp=\''.add($_POST['off_code_shaidan_sp']).'\';

	//系统功能开关(部分不能用spr)
	$ON_daigou=\''.add($_POST['ON_daigou']).'\';
	$off_shaidan=\''.add($_POST['off_shaidan']).'\';
	$off_mall=\''.add($_POST['off_mall']).'\';
	$off_integral=\''.add($_POST['off_integral']).'\';
	$ON_gd_japan=\''.spr($_POST['ON_gd_japan']).'\';
	$shaidan_checked=\''.add($_POST['shaidan_checked']).'\';
	$comments_checked=\''.add($_POST['comments_checked']).'\';
	$off_api=\''.add($_POST['off_api']).'\';
	$off_delbak=\''.add($_POST['off_delbak']).'\';
	
	$ON_MusicYes=\''.spr($_POST['ON_MusicYes']).'\';
	$ON_MusicNo=\''.spr($_POST['ON_MusicNo']).'\';

	//其他配置
	$off_water=\''.add($_POST['off_water']).'\';
	$water_lx=\''.spr($_POST['water_lx']).'\';
	$water_file=\''.add($_POST['water_file']).'\';
	$water_font=\''.add($_POST['water_font']).'\';
	$water_font_size=\''.spr($_POST['water_font_size']).'\';
	$water_font_length=\''.spr($_POST['water_font_length']).'\';
	$water_font_color=\''.add($_POST['water_font_color']).'\';
	$water_location=\''.add($_POST['water_location']).'\';
	$water_tran=\''.add($_POST['water_tran']).'\';
	$off_narrow=\''.add($_POST['off_narrow']).'\';
	$certi_w=\''.add($_POST['certi_w']).'\';
	$certi_h=\''.add($_POST['certi_h']).'\';
	$other_w=\''.add($_POST['other_w']).'\';
	$other_h=\''.add($_POST['other_h']).'\';
	$image_size=\''.add($_POST['image_size']).'\';
	$image_ext=\''.add($_POST['image_ext']).'\';
	$file_size=\''.add($_POST['file_size']).'\';
	$file_ext=\''.add($_POST['file_ext']).'\';
	$media_size=\''.add($_POST['media_size']).'\';
	$media_ext=\''.add($_POST['media_ext']).'\';
	$flash_size=\''.add($_POST['flash_size']).'\';
	$flash_ext=\''.add($_POST['flash_ext']).'\';

	//主要配置
	$XAMcurrency=\''.add($_POST['XAMcurrency']).'\';
	$XAmc=\''.add($_POST['XAmc']).'\';
	$XAScurrency=\''.add($_POST['XAScurrency']).'\';
	$XAsc=\''.add($_POST['XAsc']).'\';

	$XAwt=\''.add($_POST['XAwt']).'\';
	$XAwtkg=\''.(float)add($_POST['XAwtkg']).'\';
	$XAsz=\''.add($_POST['XAsz']).'\';
	$XAszcm=\''.(float)add($_POST['XAszcm']).'\';
	$ON_bankAccount=\''.spr($_POST['ON_bankAccount']).'\';
	$bankAccountLock=\''.spr($_POST['bankAccountLock']).'\';
	
	
	//包裹
	$off_hx=\''.spr($_POST['off_hx']).'\';
	$off_fx=\''.spr($_POST['off_fx']).'\';
	$off_baoguo=\''.spr($_POST['off_baoguo']).'\';
	$off_baoguo_yubao=\''.spr($_POST['off_baoguo_yubao']).'\';
	$off_baoguo_op_02=\''.spr($_POST['off_baoguo_op_02']).'\';
	$off_baoguo_op_04=\''.spr($_POST['off_baoguo_op_04']).'\';
	$off_tra_user=\''.spr($_POST['off_tra_user']).'\';
	$off_baoguo_op_06=\''.spr($_POST['off_baoguo_op_06']).'\';
	$off_baoguo_op_07=\''.spr($_POST['off_baoguo_op_07']).'\';
	$off_baoguo_th=\''.spr($_POST['off_baoguo_th']).'\';
	$off_baoguo_op_09=\''.spr($_POST['off_baoguo_op_09']).'\';
	$off_baoguo_op_10=\''.spr($_POST['off_baoguo_op_10']).'\';
	$off_baoguo_op_11=\''.spr($_POST['off_baoguo_op_11']).'\';
	$off_edit_wp=\''.spr($_POST['off_edit_wp']).'\';
	$off_baoguo_zxyd=\''.spr($_POST['off_baoguo_zxyd']).'\';
	$ON_ware=\''.spr($_POST['ON_ware']).'\';
	$ON_nickname=\''.spr($_POST['ON_nickname']).'\';
	$bg_ware_msg=\''.spr($_POST['bg_ware_msg']).'\';
	$baoguo_ruku_msg=\''.spr($_POST['baoguo_ruku_msg']).'\';
	$baoguo_ruku_mail=\''.spr($_POST['baoguo_ruku_mail']).'\';
	$baoguo_ruku_sms=\''.spr($_POST['baoguo_ruku_sms']).'\';
	$baoguo_ruku_wx=\''.spr($_POST['baoguo_ruku_wx']).'\';
	
	$baoguo_hx_msg=\''.spr($_POST['baoguo_hx_msg']).'\';
	$baoguo_hx_mail=\''.spr($_POST['baoguo_hx_mail']).'\';
	$baoguo_hx_sms=\''.spr($_POST['baoguo_hx_sms']).'\';
	$baoguo_hx_wx=\''.spr($_POST['baoguo_hx_wx']).'\';

	$baoguo_fx_msg=\''.spr($_POST['baoguo_fx_msg']).'\';
	$baoguo_fx_mail=\''.spr($_POST['baoguo_fx_mail']).'\';
	$baoguo_fx_sms=\''.spr($_POST['baoguo_fx_sms']).'\';
	$baoguo_fx_wx=\''.spr($_POST['baoguo_fx_wx']).'\';

	$baoguo_ruku_od=\''.spr($_POST['baoguo_ruku_od']).'\';
	$baoguo_qr=\''.spr($_POST['baoguo_qr']).'\';
	$bg_shelves=\''.spr($_POST['bg_shelves']).'\';
	$baoguo_smws=\''.spr($_POST['baoguo_smws']).'\';
	$baoguo_req_weight=\''.spr($_POST['baoguo_req_weight']).'\';
	$baoguo_req_whPlace=\''.spr($_POST['baoguo_req_whPlace']).'\';
	$shelves_req_whPlace=\''.spr($_POST['shelves_req_whPlace']).'\';
	$baoguo_fg=\''.spr($_POST['baoguo_fg']).'\';
	$baoguo_fgzs=\''.spr($_POST['baoguo_fgzs']).'\';
	$baoguo_fg_type=\''.spr($_POST['baoguo_fg_type']).'\';
	';
	
	$_POST['ON_wupin_type']=(int)$_POST['wupin_req_type']?1:(int)$_POST['ON_wupin_type'];
	$_POST['ON_wupin_name']=(int)$_POST['wupin_req_name']?1:(int)$_POST['ON_wupin_name'];
	$_POST['ON_wupin_brand']=(int)$_POST['wupin_req_brand']?1:(int)$_POST['ON_wupin_brand'];
	$_POST['ON_wupin_spec']=(int)$_POST['wupin_req_spec']?1:(int)$_POST['ON_wupin_spec'];
	$_POST['ON_wupin_price']=(int)$_POST['wupin_req_price']?1:(int)$_POST['ON_wupin_price'];
	$_POST['ON_wupin_unit']=(int)$_POST['wupin_req_unit']?1:(int)$_POST['ON_wupin_unit'];
	$_POST['ON_wupin_number']=(int)$_POST['wupin_req_number']?1:(int)$_POST['ON_wupin_number'];
	$_POST['ON_wupin_price']=(int)$_POST['wupin_req_price']?1:(int)$_POST['ON_wupin_price'];
	$_POST['ON_wupin_total']=(int)$_POST['wupin_req_total']?1:(int)$_POST['ON_wupin_total'];
	$_POST['ON_wupin_weight']=(int)$_POST['wupin_req_weight']?1:(int)$_POST['ON_wupin_weight'];
	
	$data.='
	$ON_wupin_type=\''.(int)$_POST['ON_wupin_type'].'\';
	$ON_wupin_name=\''.(int)$_POST['ON_wupin_name'].'\';
	$ON_wupin_brand=\''.(int)$_POST['ON_wupin_brand'].'\';
	$ON_wupin_spec=\''.(int)$_POST['ON_wupin_spec'].'\';
	$ON_wupin_price=\''.(int)$_POST['ON_wupin_price'].'\';
	$ON_wupin_unit=\''.(int)$_POST['ON_wupin_unit'].'\';
	$ON_wupin_number=\''.(int)$_POST['ON_wupin_number'].'\';
	$ON_wupin_price=\''.(int)$_POST['ON_wupin_price'].'\';
	$ON_wupin_total=\''.(int)$_POST['ON_wupin_total'].'\';
	$ON_wupin_weight=\''.(int)$_POST['ON_wupin_weight'].'\';

	$wupin_req_type=\''.(int)$_POST['wupin_req_type'].'\';
	$wupin_req_name=\''.(int)$_POST['wupin_req_name'].'\';
	$wupin_req_brand=\''.(int)$_POST['wupin_req_brand'].'\';
	$wupin_req_spec=\''.(int)$_POST['wupin_req_spec'].'\';
	$wupin_req_price=\''.(int)$_POST['wupin_req_price'].'\';
	$wupin_req_unit=\''.(int)$_POST['wupin_req_unit'].'\';
	$wupin_req_number=\''.(int)$_POST['wupin_req_number'].'\';
	$wupin_req_price=\''.(int)$_POST['wupin_req_price'].'\';
	$wupin_req_total=\''.(int)$_POST['wupin_req_total'].'\';
	$wupin_req_weight=\''.(int)$_POST['wupin_req_weight'].'\';

	//积分
	$integral_bili=\''.add($_POST['integral_bili']).'\';
	$integral_1=\''.add($_POST['integral_1']).'\';
	$integral_2=\''.add($_POST['integral_2']).'\';
	$integral_3=\''.add($_POST['integral_3']).'\';
	$integral_4=\''.add($_POST['integral_4']).'\';
	$integral_5=\''.add($_POST['integral_5']).'\';
	$integral_MemberBirthday=\''.spr($_POST['integral_MemberBirthday'],0).'\';
	$integral_shaidan=\''.spr($_POST['integral_shaidan'],0).'\';
	$integral_xhysf=\''.spr($_POST['integral_xhysf'],0).'\';
	$integral_daigou=\''.spr($_POST['integral_daigou'],0).'\';
	$integral_yundan=\''.spr($_POST['integral_yundan']).'\';
	$integral_mall=\''.spr($_POST['integral_mall']).'\';
	';
	
	//国家
	$openCountry=add(ToStr($_POST['openCountry']) );
	if(!$openCountry||stristr($openCountry,',')){$ON_country=1;}else{$ON_country=0;}
	
	
	//币种
	$openCurrency=$_POST['openCurrency'];
	if(!$openCurrency){exit ("<script>alert('请选择网站支持的币种！');goBack();</script>");}
	if(!$_POST['me_openCurrency']){exit ("<script>alert('请选择会员支持的币种！');goBack();</script>");}

	array_push($openCurrency,$_POST['XAMcurrency'],$_POST['XAScurrency']);//增加数组
	$openCurrency=array_unique($openCurrency);//删除重复
	$_POST['openCurrency']=$openCurrency;//必须,用于后面验证
	$openCurrency=add(DelStr(ToStr($openCurrency)));
	exchangeUpdate();//更新币种
	
	if(!(int)$_POST['off_fajian'])
	{
		$_POST['f_name_req']=0;$_POST['f_mobile_code_req']=0;$_POST['f_mobile_req']=0;$_POST['f_tel_req']=0;$_POST['f_zip_req']=0;$_POST['f_add_shengfen_req']=0;$_POST['f_add_chengshi_req']=0;$_POST['f_add_quzhen_req']=0;$_POST['f_add_dizhi_req']=0;
	}
	
	//开通此功能时，要关闭以上的【自动删除已完成的运单】功能
	$yundan_del_time=RepPIntvar($_POST['yundan_del_time']);
	if($yundan_del_time>0){$_POST['ON_cardInstead']=0;}
	
	$data.='
	$ON_country=\''.$ON_country.'\';//是否有多国家 0只有一个国家,1有多国家
	$openCountry=\''.$openCountry.'\';//国家
	$openCurrency=\''.$openCurrency.'\';//币种
	$oneCurrency=\''.(arrcount($openCurrency)>1?0:1).'\';//是否只有一个币种
	$ON_exchange=\''.(int)$_POST['ON_exchange'].'\';
	$JSCurrency=\''.add($_POST['JSCurrency']).'\';
	
	$ydh_tpre=\''.add($_POST['ydh_tpre']).'\';
	$ydh_typ=\''.add($_POST['ydh_typ']).'\';
	$ydh_suffix=\''.add($_POST['ydh_suffix']).'\';
	$ON_yundan_prepay=\''.spr($_POST['yundan_prepay']).'\';

	$yundan_fee_msg=\''.(int)$_POST['yundan_fee_msg'].'\';
	$yundan_fee_mail=\''.(int)$_POST['yundan_fee_mail'].'\';
	$yundan_fee_sms=\''.(int)$_POST['yundan_fee_sms'].'\';
	$yundan_fee_wx=\''.(int)$_POST['yundan_fee_wx'].'\';
	$yundan_fee_settlement=\''.(int)$_POST['yundan_fee_settlement'].'\';
	$uretion=\''.$uretion.'\';
	
	//运单附加服务
	
	
	//收件人/发件人
	$off_shenfenzheng=\''.(int)$_POST['off_shenfenzheng'].'\';
	$off_upload_cert=\''.(int)$_POST['off_upload_cert'].'\';
	
	$off_fajian=\''.(int)$_POST['off_fajian'].'\';
	$f_name_req=\''.(int)$_POST['f_name_req'].'\';
	$f_mobile_code_req=\''.(int)$_POST['f_mobile_code_req'].'\';
	$f_mobile_req=\''.(int)$_POST['f_mobile_req'].'\';
	$f_tel_req=\''.(int)$_POST['f_tel_req'].'\';
	$f_zip_req=\''.(int)$_POST['f_zip_req'].'\';
	$f_add_shengfen_req=\''.(int)$_POST['f_add_shengfen_req'].'\';
	$f_add_chengshi_req=\''.(int)$_POST['f_add_chengshi_req'].'\';
	$f_add_quzhen_req=\''.(int)$_POST['f_add_quzhen_req'].'\';
	$f_add_dizhi_req=\''.(int)$_POST['f_add_dizhi_req'].'\';


	$yundan_paysucc_msg=\''.(int)$_POST['yundan_paysucc_msg'].'\';
	$yundan_paysucc_mail=\''.(int)$_POST['yundan_paysucc_mail'].'\';
	$yundan_paysucc_sms=\''.(int)$_POST['yundan_paysucc_sms'].'\';
	$yundan_paysucc_wx=\''.(int)$_POST['yundan_paysucc_wx'].'\';
	
	$yundan_payfail_msg=\''.(int)$_POST['yundan_payfail_msg'].'\';
	$yundan_payfail_mail=\''.(int)$_POST['yundan_payfail_mail'].'\';
	$yundan_payfail_sms=\''.(int)$_POST['yundan_payfail_sms'].'\';
	$yundan_payfail_wx=\''.(int)$_POST['yundan_payfail_wx'].'\';

	$yundan_del_time=\''.$yundan_del_time.'\';
	$ON_cardInstead=\''.spr($_POST['ON_cardInstead']).'\';
	$cardInstead_time=\''.RepPIntvar($_POST['cardInstead_time'],1).'\';
	
	$ON_baiduSearch=\''.spr($_POST['ON_baiduSearch']).'\';
	$ON_SonWebsite_main=\''.spr($_POST['ON_SonWebsite_main']).'\';
	$SonWebsiteList=\''.add($_POST['SonWebsiteList']).'\';
	$ON_SonWebsite=\''.spr($_POST['ON_SonWebsite']).'\';
	
	//代购
	$dg_tpre=\''.add($_POST['dg_tpre']).'\';
	$dg_typ=\''.add($_POST['dg_typ']).'\';
	$dg_suffix=\''.add($_POST['dg_suffix']).'\';
	$dg_checked=\''.spr($_POST['dg_checked']).'\';
	$dg_enquiry=\''.spr($_POST['dg_enquiry']).'\';
	$dg_whcodTpre=\''.add($_POST['dg_whcodTpre']).'\';
	$dg_whcodLength=\''.spr($_POST['dg_whcodLength']).'\';
	$dg_openCurrency=\''.add(ToStr(ArrDel_Repeat($_POST['dg_openCurrency'],$_POST['openCurrency']))).'\';
	$me_openCurrency=\''.add(ToStr(ArrDel_Repeat($_POST['me_openCurrency'],$_POST['openCurrency']))).'\';
	$dg_ware_msg=\''.spr($_POST['dg_ware_msg']).'\';
	
	$daigou_IncCost_msg=\''.spr($_POST['daigou_IncCost_msg']).'\';
	$daigou_IncCost_mail=\''.spr($_POST['daigou_IncCost_mail']).'\';
	$daigou_IncCost_sms=\''.spr($_POST['daigou_IncCost_sms']).'\';
	$daigou_IncCost_wx=\''.spr($_POST['daigou_IncCost_wx']).'\';
	
	$daigou_inStorage_msg=\''.spr($_POST['daigou_inStorage_msg']).'\';
	$daigou_inStorage_mail=\''.spr($_POST['daigou_inStorage_mail']).'\';
	$daigou_inStorage_sms=\''.spr($_POST['daigou_inStorage_sms']).'\';
	$daigou_inStorage_wx=\''.spr($_POST['daigou_inStorage_wx']).'\';
	
	$daigou_managePay_msg=\''.spr($_POST['daigou_managePay_msg']).'\';
	$daigou_managePay_mail=\''.spr($_POST['daigou_managePay_mail']).'\';
	$daigou_managePay_sms=\''.spr($_POST['daigou_managePay_sms']).'\';
	$daigou_managePay_wx=\''.spr($_POST['daigou_managePay_wx']).'\';
	
	$daigou_manageRef_msg=\''.spr($_POST['daigou_manageRef_msg']).'\';
	$daigou_manageRef_mail=\''.spr($_POST['daigou_manageRef_mail']).'\';
	$daigou_manageRef_sms=\''.spr($_POST['daigou_manageRef_sms']).'\';
	$daigou_manageRef_wx=\''.spr($_POST['daigou_manageRef_wx']).'\';
	
	';
	
	
for ($i=0; $i<=10; $i++)
{
	$name=daigou_Status($i);
	if($name)
	{
		$data.='$daigou_status_msg'.$i.'=\''.spr($_POST['daigou_status_msg'.$i]).'\';';
		$data.='$daigou_status_mail'.$i.'=\''.spr($_POST['daigou_status_mail'.$i]).'\';';
		$data.='$daigou_status_sms'.$i.'=\''.spr($_POST['daigou_status_sms'.$i]).'\';';
		$data.='$daigou_status_wx'.$i.'=\''.spr($_POST['daigou_status_wx'.$i]).'\';';
	}
}

	
$data.='
	//商城
	$off_mall_checked=\''.(float)$_POST['off_mall_checked'].'\';
	$mall_order_time=\''.(float)$_POST['mall_order_time'].'\';
	$mall_order_pay_msg=\''.add($_POST['mall_order_pay_msg']).'\';
	$mall_order_pay_mail=\''.add($_POST['mall_order_pay_mail']).'\';
	$mall_order_pay_sms=\''.add($_POST['mall_order_pay_sms']).'\';
	$mall_order_pay_wx=\''.add($_POST['mall_order_pay_wx']).'\';
	$derstan=\''.$derstan.'\';

	//会员
	$off_member_reg=\''.spr($_POST['off_member_reg']).'\';
	$off_reg_mobile=\''.spr($_POST['off_reg_mobile']).'\';
	$off_reg_email=\''.spr($_POST['off_reg_email']).'\';
	$member_reg_lx=\''.add($_POST['member_reg_lx']).'\';
	$member_reg_key=\''.add($_POST['member_reg_key']).'\';
	$memberid_tpre=\''.add($_POST['memberid_tpre']).'\';
	$member_tpreic=\''.add($_POST['member_tpreic']).'\';
	$member_ic=\''.(int)$_POST['member_ic'].'\';
	$member_reg_sh=\''.spr($_POST['member_reg_sh']).'\';
	$member_sh_msg=\''.spr($_POST['member_sh_msg']).'\';
	$member_sh_mail=\''.spr($_POST['member_sh_mail']).'\';
	$member_sh_sms=\''.spr($_POST['member_sh_sms']).'\';
	$member_sh_wx=\''.spr($_POST['member_sh_wx']).'\';
	$off_member_reg_sendmail=\''.add($_POST['off_member_reg_sendmail']).'\';
	
	$ON_MemberAutoLogin=\''.spr($_POST['ON_MemberAutoLogin']).'\';
	$ON_MemberClient=\''.spr($_POST['ON_MemberClient']).'\';
	$member_getpw_number=\''.add($_POST['member_getpw_number']).'\';
	$member_prompt_time=\''.spr($_POST['member_prompt_time']).'\';

	$member_birthday_msg=\''.spr($_POST['member_birthday_msg']).'\';
	$member_birthday_mail=\''.spr($_POST['member_birthday_mail']).'\';
	$member_birthday_sms=\''.spr($_POST['member_birthday_sms']).'\';
	$member_birthday_wx=\''.spr($_POST['member_birthday_wx']).'\';

	
	$off_certification=\''.spr($_POST['off_certification']).'\';
	$certification_baoguo=\''.spr($_POST['certification_baoguo']).'\';
	$certification_yundan=\''.spr($_POST['certification_yundan']).'\';
	$certification_mall_order=\''.spr($_POST['certification_mall_order']).'\';
	$certification_daigou=\''.spr($_POST['certification_daigou']).'\';
	$certification_qujian=\''.spr($_POST['certification_qujian']).'\';

	$certification_ct1=\''.spr($_POST['certification_ct1']).'\';
	$certification_ct2=\''.spr($_POST['certification_ct2']).'\';
	$certification_ct3=\''.spr($_POST['certification_ct3']).'\';
	
	//推广
	$off_tuiguang=\''.add($_POST['off_tuiguang']).'\';
	$tuiguang_tgy=\''.add($_POST['tuiguang_tgy']).'\';
	$tuiguang_xhy=\''.add($_POST['tuiguang_xhy']).'\';
	$tuiguang_tgyip=\''.add($_POST['tuiguang_tgyip']).'\';
	$tuiguang_xhyip=\''.add($_POST['tuiguang_xhyip']).'\';
	$tuiguang_xhysj=\''.add($_POST['tuiguang_xhysj']).'\';
	$tuiguang_xhymc=\''.add($_POST['tuiguang_xhymc']).'\';
	$tuiguang_tgyhdcs=\''.add($_POST['tuiguang_tgyhdcs']).'\';
	$tuiguang_xhyhdcs=\''.add($_POST['tuiguang_xhyhdcs']).'\';
	
	$tuiguang_hqsf=\''.spr($_POST['tuiguang_hqsf']).'\';
	$tuiguang_ydxf_sl=\''.spr($_POST['tuiguang_ydxf_sl']).'\';
	$tuiguang_ydxf_bl=\''.spr($_POST['tuiguang_ydxf_bl']).'\';
	$tuiguang_mallxf_sl=\''.spr($_POST['tuiguang_mallxf_sl']).'\';
	$tuiguang_mallxf_bl=\''.spr($_POST['tuiguang_mallxf_bl']).'\';
	$tuiguang_dgxf_sl=\''.spr($_POST['tuiguang_dgxf_sl']).'\';
	$tuiguang_dgxf_bl=\''.spr($_POST['tuiguang_dgxf_bl']).'\';

	';
	
	
	
	
	
$arr=ToArr('reg,tgy,xhy');
if($arr)
{
	foreach($arr as $key=>$tag)
	{
		for($i=1; $i<=3; $i++)
		{
			$data.='
			//推广:生成优惠券/折扣券参数
			$'.$tag.'cp_types'.$i.'=\''.spr($_POST[$tag.'cp_types'.$i]).'\';
			$'.$tag.'cp_usetypes'.$i.'=\''.spr($_POST[$tag.'cp_usetypes'.$i]).'\';
			$'.$tag.'cp_value'.$i.'=\''.spr($_POST[$tag.'cp_value'.$i]).'\';
			$'.$tag.'cp_limitmoney'.$i.'=\''.spr($_POST[$tag.'cp_limitmoney'.$i]).'\';
			$'.$tag.'cp_code_digits'.$i.'=\''.spr($_POST[$tag.'cp_code_digits'.$i]).'\';
			$'.$tag.'cp_number'.$i.'=\''.spr($_POST[$tag.'cp_number'.$i]).'\';
			$'.$tag.'cp_overdue'.$i.'=\''.spr($_POST[$tag.'cp_overdue'.$i]).'\';
			';
		}
	}
}
	
	
	
	
	
	
	$data.='
	
	$settlement_msg=\''.(int)$_POST['settlement_msg'].'\';
	$settlement_mail=\''.(int)$_POST['settlement_mail'].'\';
	$settlement_sms=\''.(int)$_POST['settlement_sms'].'\';
	$settlement_wx=\''.(int)$_POST['settlement_wx'].'\';
	$CustomerService=\''.add($_POST['CustomerService']).'\';
	$daigouCS=\''.add($_POST['daigouCS']).'\';
	$floatingCS=\''.add($_POST['floatingCS']).'\';
	
	//邮箱接口配置
	$smtp_server=\''.add($_POST['smtp_server']).'\';
	$smtp_secure=\''.add($_POST['smtp_secure']).'\';
	$smtp_port=\''.add($_POST['smtp_port']).'\';
	$smtp_mail=\''.add($_POST['smtp_mail']).'\';
	$smtp_password=\''.add($_POST['smtp_password']).'\';
	
	//对接清关公司
	if($open_gd_mosuda){$ON_gd_mosuda=\''.spr($_POST['ON_gd_mosuda']).'\';}
	$ON_gd_mosuda_apply=\''.spr($_POST['ON_gd_mosuda_apply']).'\';
	$gd_mosuda_plusTaxes=\''.spr($_POST['gd_mosuda_plusTaxes']).'\';
	$gd_mosuda_CountryCode=\''.add($_POST['gd_mosuda_CountryCode']).'\';
	$gd_mosuda_ShopId=\''.add($_POST['gd_mosuda_ShopId']).'\';
	$gd_mosuda_record=\''.add($_POST['gd_mosuda_record']).'\';
	$gd_mosuda_username=\''.add($_POST['gd_mosuda_username']).'\';
	$gd_mosuda_password=\''.add($_POST['gd_mosuda_password']).'\';
	$gd_mosuda_key=\''.add($_POST['gd_mosuda_key']).'\';

	//物流对接
	$APIcustomer=\''.add($_POST['APIcustomer']).'\';
	$APIkey=\''.add($_POST['APIkey']).'\';
	
	$ON_cj=\''.add($_POST['on_cj']).'\';
	$cj_juso_account=\''.add($_POST['cj_juso_account']).'\';
	$cj_juso_password=\''.add($_POST['cj_juso_password']).'\';
	$cj_juso_clntnum=\''.add($_POST['cj_juso_clntnum']).'\';
	$cj_juso_clnmgmcustcd=\''.add($_POST['cj_juso_clnmgmcustcd']).'\';
	$cj_p_prngdivcd=\''.add($_POST['cj_p_prngdivcd']).'\';
	$cj_p_cgsts=\''.add($_POST['cj_p_cgsts']).'\';

	$cj_frt_dv_cd=\''.add($_POST['cj_frt_dv_cd']).'\';
	$cj_box_type_cd=\''.add($_POST['cj_box_type_cd']).'\';
	$cj_sendr_nm=\''.add($_POST['cj_sendr_nm']).'\';
	$cj_sendr_tel=\''.add($_POST['cj_sendr_tel']).'\';
	$cj_sendr_addr=\''.add($_POST['cj_sendr_addr']).'\';

	$cj_opendb_account=\''.add($_POST['cj_opendb_account']).'\';
	$cj_opendb_password=\''.add($_POST['cj_opendb_password']).'\';
	
	$ON_dhl=\''.add($_POST['ON_dhl']).'\';
	$dhl_username=\''.add($_POST['dhl_username']).'\';
	$dhl_password=\''.add($_POST['dhl_password']).'\';
	$dhl_PortalId=\''.add($_POST['dhl_PortalId']).'\';
	$dhl_DeliveryName=\''.add($_POST['dhl_DeliveryName']).'\';
	$dhl_ToAddress=\''.add($_POST['dhl_ToAddress']).'\';
	$mprehen=\''.$mprehen.'\';
	
	
	$fanyi_type=\''.add($_POST['fanyi_type']).'\';
	$youdao_api_id=\''.add($_POST['youdao_api_id']).'\';
	$youdao_api_key=\''.add($_POST['youdao_api_key']).'\';
	$baidu_api_id=\''.add($_POST['baidu_api_id']).'\';
	$baidu_api_key=\''.add($_POST['baidu_api_key']).'\';
	
	//短信
	$off_sms=\''.add($_POST['off_sms']).'\';
	$sms_user=\''.add($_POST['sms_user']).'\';
	$sms_pwd=\''.add($_POST['sms_pwd']).'\';
	$sms_type=\''.add($_POST['sms_type']).'\';
	$sms_key=\''.add($_POST['sms_key']).'\';
	$sms_signature=\''.add($_POST['sms_signature']).'\';
	
	//快捷登录会员
	$off_connect_qq=\''.add($_POST['off_connect_qq']).'\';
	$off_connect_qq_checked=\''.add($_POST['off_connect_qq_checked']).'\';
	$connect_qqid=\''.add($_POST['connect_qqid']).'\';
	$connect_qqkey=\''.add($_POST['connect_qqkey']).'\';
	
	$off_connect_weixin=\''.add($_POST['off_connect_weixin']).'\';
	$connect_weixinid=\''.add($_POST['connect_weixinid']).'\';
	$connect_weixinkey=\''.add($_POST['connect_weixinkey']).'\';
	
	$off_connect_alipay=\''.add($_POST['off_connect_alipay']).'\';
	$connect_alipayid=\''.add($_POST['connect_alipayid']).'\';
	$connect_alipaykey=\''.add($_POST['connect_alipaykey']).'\';
	
	//其他接口
	$ON_mpWeixin=\''.spr($_POST['ON_mpWeixin']).'\';
	$mpWeixin_checked=\''.spr($_POST['mpWeixin_checked']).'\';
	$ON_WX=\''.spr($_POST['ON_WX']).'\';
	$mpWeixin_token=\''.add($_POST['mpWeixin_token']).'\';
	$mpWeixin_appid=\''.add($_POST['mpWeixin_appid']).'\';
	$mpWeixin_appsecret=\''.add($_POST['mpWeixin_appsecret']).'\';
	$ON_api_yundanStatus=\''.spr($_POST['ON_api_yundanStatus']).'\';
	$api_yundanStatus_key=\''.add($_POST['api_yundanStatus_key']).'\';
	$bottion=\''.$bottion.'\';

	//安全配置
	$security_clear=\''.add($_POST['security_clear']).'\';

	//后台配置
	$manage_prompt_time=\''.spr($_POST['manage_prompt_time']).'\';
	
	//其他配置
	$shaidan_Types_0=\''.spr($_POST['shaidan_Types_0']).'\';
	$shaidan_Types_1=\''.spr($_POST['shaidan_Types_1']).'\';
	$ON_shaidan_language=\''.spr($_POST['ON_shaidan_language']).'\';
	';
	
	
	
	$data.='
	//状态更新-开始-------------------------------------------------------------
	$off_statusauto=\''.intval($_POST['off_statusauto']).'\';
	$yd_statusauto=\''.intval($_POST['yd_statusauto']).'\';
	$status_api_ok=\''.intval($_POST['status_api_ok']).'\';
	//状态更新-结束
	';
	
	

	$data.='
	//运单状态-开始-------------------------------------------------------------
	//显示名称
	';
	
	  for ($i=-2; $i<=30; $i++) 
	  {
		  $i_now=str_ireplace('-','0',$i);
		  
		  
		  //语言字段处理++
		  if(!$LGList){$LGList=languageType('',3);}
		  if($LGList)
		  {
			  foreach($LGList as $arrkey=>$language)
			  {
					$data.='$status_'.$i_now.$language.'=\''.add($_POST["status_{$i_now}{$language}"]).'\';
	';
			  }
		  }
		  
		  
		  
	  }
	  
	  
	  
	  
	  
	  
	  for ($i=-2; $i<=30; $i++) 
	  {
		  $i_now=str_ireplace('-','0',$i);
		  $data.='$joint=\'status_'.$i_now.'\'.$LT; $status_'.$i_now.'=$$joint;
	';
	  }


	/*
	输出下拉菜单
	yundan_Status($val);
	*/
	
	$data.='
	/*
	输出下拉菜单
	yundan_Status($val);
	*/
	
	function yundan_Status($val)
	{
	';
	
		for ($i=-2; $i<=30; $i++) {
			$i_now=str_ireplace('-','0',$i);
			$zhi=add(trim($_POST["status_{$i_now}{$LT}"]));
			$zhiON=spr($_POST["status_on_{$i_now}"]);
			
			if ($i<5||$i==20||$i==30){$zhiON=1;}
			if($zhi && $zhiON)
			{
				$data.='
					global $status_'.$i_now.'; if($val==\''.$i.'\'){echo "<option value=\''.$i.'\'  selected>{$status_'.$i_now.'}</option>"; }else{echo "<option value=\''.$i.'\'  >{$status_'.$i_now.'}</option>";}	
				
				';
			}
		}
	
	$data.='
	}
	$status_sms_lx=\''.add($_POST['status_sms_lx']).'\';
	//状态-结束
	
	';
	
	
	
	
	$data.='
	//每种状态是否启用-开始-------------------------------------------------------------
	';
	
		for ($i=5; $i<=30; $i++) {
			$zhi=add($_POST["status_on_{$i}"]);
			$data.='$status_on_'.$i.'=\''.$zhi.'\';
			';
		}
		
	$data.='
	//每种状态是否启用-结束
	';
	
	
	$data.='
	//每种状态自动更新时间-开始-------------------------------------------------------------
	';
	
		for ($i=5; $i<=30; $i++) {
			$zhi=add($_POST["statustime_update{$i}"]);
			$data.='$statustime_update'.$i.'=\''.$zhi.'\';
			';
		}
		
	$data.='
	//状态更新是否发站内信-结束
	';
	
	$data.='
	//周未是否也算时间-开始-------------------------------------------------------------
	';
	
		for ($i=5; $i<=30; $i++) {
			$zhi=add($_POST["whether{$i}"]);
			$data.='$whether'.$i.'=\''.$zhi.'\';
			';
		}
		
	$data.='
	//周未是否也算时间-结束
	';
	
	
	$data.='
	//状态更新是否发站内信-开始-------------------------------------------------------------
	';
	
		for ($i=0; $i<=30; $i++) {
			$zhi=intval($_POST["status_msg{$i}"]);
			$data.='$status_msg'.$i.'=\''.$zhi.'\';
			';
		}
		
	$data.='
	//状态更新是否发站内信-结束
	';
	
	$data.='
	//状态更新是否发邮件-开始-------------------------------------------------------------
	';
	
		for ($i=0; $i<=30; $i++) {
			$zhi=intval($_POST["status_mail{$i}"]);
			$data.='$status_mail'.$i.'=\''.$zhi.'\';
			';
		}
		
	$data.='
	//状态更新是否发邮件-结束
	';
	
	$data.='
	//状态更新是否发短信-开始-------------------------------------------------------------
	';
	
		for ($i=0; $i<=30; $i++) {
			$zhi=intval($_POST["status_sms{$i}"]);
			$data.='$status_sms'.$i.'=\''.$zhi.'\';
			';
		}
		
	$data.='
	//状态更新是否发短信-结束
	';
	
	
	$data.='
	//状态更新是否发微信-开始-------------------------------------------------------------
	';
	
		for ($i=0; $i<=30; $i++) {
			$zhi=intval($_POST["status_wx{$i}"]);
			$data.='$status_wx'.$i.'=\''.$zhi.'\';
			';
		}
		
	$data.='
	//状态更新是否发微信-结束
	';
	
	
	
	$data.='
	//购物网站-开始-------------------------------------------------------------
	$wangzhan=\''.add($_POST['wangzhan']).'\';
	
	/*
	wangzhan($val,$lx=0);$lx=0显示名称;$lx=1显示下拉菜单;
	*/
	
	function wangzhan($val,$lx=0)
	{
		if(!$lx)
		{
			switch($val)
			{
				case \'\':return \'\';
				case \'other\':return \'其他\';
	';

		$zhi=ToArr(add($_POST['wangzhan']),1);

		foreach($zhi as $a=>$b)
		{
			$zhi2=ToArr($b,2);
			$b=html($b);
			if($b){ 
				$data.='case \''.$zhi2[1].'\':return \''.$zhi2[0].'\';
				';
			}
		}
		

	$data.='	
			}
		}
		else
		{
			if ($val==\'\'){echo "<option value=\'\'  selected></option>"; }else{echo "<option value=\'\' ></option>";}	
		';
		
		
		foreach($zhi as $a=>$b)
		{
			$zhi2=ToArr($b,2);
			$b=html($b);
			if($b){
				$data.='
				if ($val==\''.$zhi2[1].'\'){echo "<option value=\''.$zhi2[1].'\'  selected>'.$zhi2[0].'</option>"; }else{echo "<option value=\''.$zhi2[1].'\' >'.$zhi2[0].'</option>";}
				';
			}
		}
		
	$data.='
		if ($val==\'other\'){echo "<option value=\'other\'  selected>其他</option>"; }else{echo "<option value=\'other\' >其他</option>";}	
		}
	}
	//购物网站-结束
	';
	
	
	
	
	
		
	$data.='
	//寄库快递-开始-------------------------------------------------------------
	$baoguo_kuaidi=\''.add($_POST['baoguo_kuaidi']).'\';
	
	function baoguo_kuaidi($val)
	{
		echo "<option value=\'\'  selected></option>";
	';
	
		$zhi=ToArr(add($_POST['baoguo_kuaidi']),1);
		
		foreach($zhi as $a=>$b)
		{
			$b=html($b);
			if($b){
				$data.='
					if ($val==\''.$b.'\'){echo "<option value=\''.$b.'\'  selected>'.$b.'</option>"; }else{echo "<option value=\''.$b.'\' >'.$b.'</option>";}	
				';
			}
		}
	$data.='
	 }
	 //寄库快递-结束           
	';

	$pagetext.=$data.' ?>';
	//生成数据－结束
	//--------------------------------------------------------------------------------------------
	
	
	CopyFile('/cache/config.php','/cache/config.backup.php');
	file_put_contents(AddPath('/cache/config.php'),$pagetext);//生成文件
	$xingao->query("update config set value3='".add($pagetext)."',value2='".md5_file(AddPath('/cache/config.php'))."' where name='config'");

	//左边菜单缓存
	$_SESSION['cache_manage']='';
	$_SESSION['cache_member']='';
	
	$token->drop_token("config_sys"); //处理完后删除密钥
	exit("<script>location='form.php';</script>");//alert('{$LG['pptEditSucceed']}');

}elseif($lx=='restore'){
	CopyFile('/cache/config.backup.php','/cache/config.php');
	$xingao->query("update config set value2='".md5_file(AddPath('/cache/config.backup.php'))."' where name='config'");
	
	exit("<script>alert('恢复成功！');location='form.php';</script>");
}
?>
