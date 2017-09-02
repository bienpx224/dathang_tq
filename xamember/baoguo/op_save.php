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


if(!$off_baoguo||!$member_per[$Mgroupid]['ON_Mbaoguo']){exit ("<script>alert('{$LG['baoguo.add_form_2']}');goBack();</script>");}

//获取,处理-----------------------------------------------------------------------------------------------
$lx=par($_POST['lx']);
$field=par($_POST['field']);
$value=par($_POST['value']);
$bgid=par(ToStr($_POST['bgid']));
//验证
if(!$field){exit($LG['baoguo.op_12']);}
if(!$bgid){exit($LG['baoguo.hx_save_1']);}

if($lx=='pay')
{

	$type=op_money_type($field,1);//$type扣费类型
	switch($field)
	{
		case 'status'://确认包裹-----------------------------------
			if($value==3)//安全验证
			{
				//更新主表
				$xingao->query("update baoguo set {$field}='{$value}' where bgid in ({$bgid}) and status=2 and th<>2 {$Mmy}");
				SQLError('更新主表');
				$rc=mysqli_affected_rows($xingao);
			}
		break;
		
		case 'tra_user'://转移会员-----------------------------------
			if(!$off_tra_user){exit($LG['baoguo.add_form_3']);}
			//新会员
			$userid=par($_POST['userid']);
			$username=par($_POST['username']);
			//查询会员是否存在
			MemberOK('','',$userid,$username,1,1);
			//入库码
			$useric=FeData('member','useric',"userid='{$userid}'");
			
			//更新主表
			$xingao->query("update baoguo set 
			old_userid='{$Muserid}',old_username='{$Musername}',tra_user_type='0',tra_user='1',tra_user_time='".time()."'
			,username='{$username}',userid='{$userid}',useric='{$useric}'
			 where bgid in ({$bgid}) {$Mmy}");
			SQLError('更新主表');
			$rc=mysqli_affected_rows($xingao);
			
			//复制一条信息作为记录
			$query="select * from baoguo where bgid in ({$bgid})";//会员已变更不能加 {$Mmy}
			$sql=$xingao->query($query);
			while($rs=$sql->fetch_array())
			{
				//新信息的不同处
				$rs['bgydh']=$rs['bgydh'].' ('.$LG['baoguo.op_save_4'].')';
				$rs['status']=10;
				$rs['tra_user_type']='1';
				$rs['old_userid']=$rs['userid'];
				$rs['old_username']=$rs['username'];
				$rs['username']=$Musername;
				$rs['userid']=$Muserid;
				$rs['useric']=$Museric;
				

				$savelx='add';//调用类型(add,edit,cache)
				$getlx='SQL';//获取类型(POST,GET,REQUEST,SQL)
				$alone='bgid';//不处理的字段
				$digital='';//数字字段
				$radio='';//单选、复选、空文本、数组字段
				$textarea='';//过滤不安全的HTML代码
				$date='';//日期格式转数字
				$save=XingAoSave($rs,$getlx,$savelx,$alone,$digital,$radio,$textarea,$html,$date);
				$xingao->query("insert into baoguo (".$save['field'].") values(".$save['value'].")");
				
				SQLError('复制添加信息');
			}
			
		break;
	
		case 'op_04'://转移仓库-----------------------------------
			if(!$off_baoguo_op_04){exit($LG['baoguo.add_form_3']);}
			$money=$member_per[$Mgroupid]['Price_04'];
			$warehouse=par($_POST['warehouse']);
			if($value==1&&!$warehouse){exit ("<script>alert('{$LG['baoguo.op_save_1']}');goBack();</script>");}
			$rc=baoguo_op_save($money,$type);
		break;
		
		case 'th'://退货-----------------------------------
			if(!$off_baoguo_th){exit($LG['baoguo.add_form_3']);}
			$money=$member_per[$Mgroupid]['Price_th'];
			$th_requ=html($_POST['th_requ']);
			if($value==1&&!$th_requ){exit ("<script>alert('{$LG['baoguo.op_save_2']}');goBack();</script>"); }
			$rc=baoguo_op_save($money,$type);
		break;
		
		case 'ware'://仓储-----------------------------------
			if(!$ON_ware){exit($LG['baoguo.add_form_3']);}
			if($value==1)//仓储
			{
				//更新主表
				$xingao->query("update baoguo set {$field}='{$value}',ware_time=".time()." where bgid in ({$bgid}) and status=3 and th<>2 {$Mmy}");
				SQLError('更新主表');
				$rc=mysqli_affected_rows($xingao);
				
			}else{//取出
			
				//查询
				$query="select * from baoguo where bgid in ({$bgid}) and ware='1' and status=3 and th<>2 {$Mmy}";
				$sql=$xingao->query($query);
				while($rs=$sql->fetch_array())
				{
					$set="ware=0,ware_out_time=".time();
					$money=spr(bg_ware_fee(1));
					if($money>0)
					{
						$content=$LG['baoguo.fx_save_3'].$rs['bgydh'].' (ID:'.$rs['bgid'].')';//发信息可能用到
						
						MoneyKF($rs['userid'],$fromtable='baoguo',$fromid=$rs['bgid'],$fromMoney=$money,$fromCurrency='',
						$title=$rs['bgydh'],'',$type=op_money_type('ware',1));

						$ts= $LG['baoguo.op_save_5'].'<strong>'.$money.$XAmc.'</strong>';
						$set.=",ware_pay='-{$money}'";
					}
						
					echo '&raquo; '.$rs['bgydh'].$LG['baoguo.op_save_6'].$ts.'<br>';
					
					//更新主表
					$xingao->query("update baoguo set {$set} where bgid={$rs[bgid]} {$Mmy}");
					SQLError('更新主表');
					$rc+=1;
				}
				SQLError('查询');
			}
				
		break;
		
		//其他通用-----------------------------------
		
		case 'op_02'://验货
			if(!$off_baoguo_op_02){exit($LG['baoguo.add_form_3']);}
			$money=$member_per[$Mgroupid]['Price_02'];
			$rc=baoguo_op_save($money,$type);
		break;
		
		case 'op_09'://清点
			if(!$off_baoguo_op_09){exit($LG['baoguo.add_form_3']);}
			$money=$member_per[$Mgroupid]['Price_09'];
			$rc=baoguo_op_save($money,$type);
		break;
		
		case 'op_06'://拍照
			if(!$off_baoguo_op_06){exit($LG['baoguo.add_form_3']);}
			$money=$member_per[$Mgroupid]['Price_06'];
			$rc=baoguo_op_save($money,$type);
		break;
		
		case 'op_07'://减重
			if(!$off_baoguo_op_07){exit($LG['baoguo.add_form_3']);}
			$money=$member_per[$Mgroupid]['Price_07'];
			$rc=baoguo_op_save($money,$type);
		break;
		
		case 'op_10'://复称
			if(!$off_baoguo_op_10){exit($LG['baoguo.add_form_3']);}
			$money=$member_per[$Mgroupid]['Price_10'];
			$rc=baoguo_op_save($money,$type);
		break;
		
		case 'op_11'://空隙
			if(!$off_baoguo_op_11){exit($LG['baoguo.add_form_3']);}
			$money=$member_per[$Mgroupid]['Price_11'];
			$rc=baoguo_op_save($money,$type);
		break;
		
	}
	
	//操作完后提示
	if($rc){echo '<br><strong>'.LGtag($LG['baoguo.op_save_7'],'<tag1>=='.$rc).'</strong>';}else{echo '<br><strong>'.$LG['baoguo.fx_save_7'].'</strong>';}
}
?>
<style>body{ height:100px;}</style>
