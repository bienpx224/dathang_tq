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
$pervar='manage_ma';require_once($_SERVER['DOCUMENT_ROOT'].'/xingao/incluce/session.php');

//获取,处理=====================================================
$lx=par($_REQUEST['lx']);
$groupid=$_REQUEST['groupid'];
$groupid_new=par($_POST['groupid_new']);
$tokenkey=par($_POST['tokenkey']);
$groupname=add($_POST['groupname']);

if (is_array($groupid)){$groupid=implode(',',$groupid);}
$groupid=par($groupid);


//添加,修改=====================================================
if($lx=='add'||$lx=='edit')
{
	//通用验证------------------------------------
	$token=new Form_token_Core();
	$token->is_token("manage_group",$tokenkey); //验证令牌密钥

	if(!$groupname){exit ("<script>alert('请填写名称！');goBack();</script>");}
	

	//添加验证,保存------------------------------------
	if($lx=='add')
	{
		//验证
		$num=mysqli_num_rows($xingao->query("select groupname from manage_group where groupname='{$groupname}'"));
		if($num){exit ("<script>alert('已经有该名称,名称不能相同！');goBack();</script>");}
		
		
		//保存
		$savelx='add';//调用类型(add,edit,cache)
		$getlx='POST';//获取类型(POST,GET,REQUEST,SQL)
		$alone='groupid';//不处理的字段
		$digital='myorder,admin,manage_sy,manage_db,manage_ma,manage_me,manage_ex,manage_ot,member_ed,member_se,member_le,member_re,member_de,member_in,member_cp,member_co,member_ot,baoguo_ed,baoguo_se,baoguo_ad,baoguo_ot,yundan_ed,yundan_se,yundan_ad,yundan_st,yundan_fe,yundan_ta,yundan_im,yundan_ex,yundan_pr,yundan_sc,yundan_ot,mall,mall_order,daigou,qujian,lipei,tixian,notice,shaidan,pinglun,qita,coupons,daigou_ed,daigou_inStorage,daigou_se,daigou_cg,daigou_th,daigou_zg,daigou_hh,daigou_ch,daigou_ck,daigou_ex,daigou_ot,count_dg,count_yd,count_bg,count_hy_sl,count_hy_hx,count_ot,settlement_se,settlement_ed,classify,goodsdata,member_my';//数字字段
		$radio='warehouse';//单选、复选、空文本、数组字段
		$textarea='';//过滤不安全的HTML代码
		$date='';//日期格式转数字
		$save=XingAoSave($_POST,$getlx,$savelx,$alone,$digital,$radio,$textarea,$html,$date);

		$xingao->query("insert into manage_group (".$save['field'].") values(".$save['value'].")");
		SQLError();
		$rc=mysqli_affected_rows($xingao);
		if($rc>0)
		{
			//生成缓存文件
			cache_manage_group();
			$token->drop_token("manage_group"); //处理完后删除密钥
			exit("<script>alert('{$LG['pptAddSucceed']}');location='list.php';</script>");
		}else{
			exit ("<script>alert('{$LG['pptAddFailure']}');goBack();</script>");
		}
	}
	
	//修改验证,保存------------------------------------
	if($lx=='edit')
	{
		//验证
		if(!$groupid)
		{
			exit ("<script>alert('groupid{$LG['pptError']}');goBack();</script>");
		}
		
		$num=mysqli_num_rows($xingao->query("select groupid from manage_group where groupname='{$groupname}' and groupid<>'{$groupid}'"));
		if($num){exit ("<script>alert('组名称重复,请修改！');goBack();</script>");}

		//更新
		$savelx='edit';//调用类型(add,edit,cache)
		$getlx='POST';//获取类型(POST,GET,REQUEST,SQL)
		$alone='groupid';//不处理的字段
		$digital='myorder,admin,manage_sy,manage_db,manage_ma,manage_me,manage_ex,manage_ot,member_ed,member_se,member_le,member_re,member_de,member_in,member_cp,member_co,member_ot,baoguo_ed,baoguo_se,baoguo_ad,baoguo_ot,yundan_ed,yundan_se,yundan_ad,yundan_st,yundan_fe,yundan_ta,yundan_im,yundan_ex,yundan_pr,yundan_sc,yundan_ot,mall,mall_order,daigou,qujian,lipei,tixian,notice,shaidan,pinglun,qita,coupons,daigou_ed,daigou_inStorage,daigou_se,daigou_cg,daigou_th,daigou_zg,daigou_hh,daigou_ch,daigou_ck,daigou_ex,daigou_ot,count_dg,count_yd,count_bg,count_hy_sl,count_hy_hx,count_ot,settlement_se,settlement_ed,classify,goodsdata,member_my';//数字字段
		$radio='warehouse';//单选、复选、空文本、数组字段
		$textarea='';//过滤不安全的HTML代码
		$date='';//日期格式转数字
		$save=XingAoSave($_POST,$getlx,$savelx,$alone,$digital,$radio,$textarea,$html,$date);

		$xingao->query("update manage_group set ".$save." where groupid='{$groupid}'");
		SQLError();
		$rc=mysqli_affected_rows($xingao);
		
		//生成缓存文件
		cache_manage_group();
		
		$token->drop_token("manage_group"); //处理完后删除密钥
		$ts=$rc>0?$LG['pptEditSucceed']:$LG['pptEditNo'];
		exit("<script>alert('".$ts."');location='list.php';</script>");
	}
	
	
	
//删除=====================================================
}elseif($lx=='del'){
	if(!$groupid)
	{
		exit ("<script>alert('请选择分类！');goBack();</script>");
	}

	//不能删除某个组
	if (in_array("1",explode(",",$groupid)))
	{
		exit("<script>alert('ID 1的分类不能删除！');location='list.php';</script>");
	}

	
	//删除用户相关记录
	$query="select username from manage where groupid in ({$groupid})";
	$sql=$xingao->query($query);
	while($rs=$sql->fetch_array())
	{
		$xingao->query("delete from manage_log where username='{$rs[username]}'");//删除用户日志
	}
	
	//删除用户
	$xingao->query("delete from manage where groupid in ({$groupid})");
	$rc=mysqli_affected_rows($xingao);

	//删除分类
	$xingao->query("delete from manage_group where groupid in ({$groupid})");

	//生成缓存
	cache_manage_group();
	
	exit("<script>alert('分类删除完成,其中删除{$rc}个用户！');location='list.php';</script>");
	
//移动=====================================================
}elseif($lx=='mobile'){
	if(!$groupid){exit ("<script>alert('请选择要转移的分类！');goBack();</script>");}
	if(!$groupid_new){exit ("<script>alert('请选择目标的分类！');goBack();</script>");}

	$xingao->query("update manage set groupid='{$groupid_new}' where groupid in ({$groupid})");
	SQLError();
	$rc=mysqli_affected_rows($xingao);

	exit("<script>alert('转移完成,共转移{$rc}个用户！');location='list.php';</script>");
}

?>
