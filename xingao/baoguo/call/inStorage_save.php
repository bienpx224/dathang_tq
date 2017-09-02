<?php
//入库保存－开始--------------------------------------------------------------------------
if ($lx=="sm_save")
{ 
	$ts='';

	//验证
	$_SESSION['in_shelves']=spr($_POST['shelves']);
	$bgid=par($_POST['bgid']);
	//<!--2017.06.17:删除dgid-->
	if (!$bgid){exit ("<script>alert('bgid获取不到！');goBack();</script>");}	
	if (!$_POST['warehouse']){exit ("<script>alert('请选择仓库！');goBack();</script>");}	
	if (!$_POST['useric']||!$_POST['userid']||!$_POST['username']){exit ("<script>alert('请填写完整会员账号！');goBack();</script>");}
		
	$_SESSION['shelves_whPlace']=par($_POST['whPlace']);
	$_SESSION['getTyp']=spr($_POST['getTyp']);

	//获取会员账号资料并赋值到POST,验证账号是否正确
	getMemberUser(1,1);

	//查询会员是否存在
	MemberOK('','',$_POST['userid'],$_POST['username'],1,1);
	
	$rs=FeData('baoguo','*',"bgid='{$bgid}' and status<=1");
	if (!$rs['bgid']){exit ("<script>alert('该包裹已入库过！');goBack();</script>");}	
	
	if($_POST['shelves']){
		//已上架(入库)
		if(spr($rs['status'])==1){$_POST['status']=4;}elseif($baoguo_qr){$_POST['status']=3;}else{$_POST['status']=2;}
		$_POST['rukutime']=time();
	}else{
		//未上架(不入库)
		if(spr($rs['status'])==1){$_POST['delivery']=1;}
		$_POST['status']=1.5;
		$_POST['rukutime']=0;
	}

	$_POST['edittime']=time();//重要,必须修改一项,否则$rc可能为0,就无法执行后面更新
	
	//更新
	$savelx='edit';//调用类型(add,edit,cache)
	$getlx='POST';//获取类型(POST,GET,REQUEST,SQL)
	$alone='bgid,print_tem,print_tem_plug,getTyp,shelves';//不处理的字段
	$digital='warehouse,weight';//数字字段
	$radio='';//单选、复选、空文本、数组字段
	$textarea='reply';//过滤不安全的HTML代码
	$date='';//日期格式转数字
	$save=XingAoSave($_POST,$getlx,$savelx,$alone,$digital,$radio,$textarea,$html,$date);
	
	
	
	//转移包裹
	if(spr($rs['userid'])!=spr($_POST['userid']))
	{
		$transfer=1;
		$save.=",old_userid='{$rs['userid']}',old_username='{$rs['username']}',tra_user_type='0',tra_user='1',tra_user_time='".time()."'";
	}

	$xingao->query("update baoguo set {$save} where bgid='{$bgid}' ");SQLError('入库保存修改');
	$rc=mysqli_affected_rows($xingao);


	if($rc>0)
	{
		//<!--2017.06.17:删除dgid-->
		baoguoInStorage($rs['bgid']);//更新运单状态
		
		//发通知
		//获取发送通知内容
		//$bgid=$bgid;//这里不能用:$bgid=$rs['bgid'];
		$NoticeTemplate='baoguo_notice_storage';
		require($_SERVER['DOCUMENT_ROOT'].'/public/NoticeTemplate.php');
  
		//转移包裹时:复制一条信息作为原主记录
		if($transfer)
		{
			$query="select * from baoguo where bgid='{$bgid}'";//会员已变更不能加 {$Mmy}
			$sql=$xingao->query($query);
			while($rs=$sql->fetch_array())
			{
				//新信息的不同处
				$rs['bgydh']=$rs['bgydh'].' ('.$LG['baoguo.op_save_4'].')';
				$rs['status']='10';
				$rs['tra_user_type']='1';
				$rs['username']=$rs['old_username'];
				$rs['userid']=$rs['old_userid'];
				$rs['useric']=$rs['old_useric'];
				$rs['old_userid']=spr($_POST['userid']);
				$rs['old_username']=add($_POST['username']);
				
	
				$savelx='add';//调用类型(add,edit,cache)
				$getlx='SQL';//获取类型(POST,GET,REQUEST,SQL)
				$alone='bgid';//不处理的字段
				$digital='';//数字字段
				$radio='';//单选、复选、空文本、数组字段
				$textarea='';//过滤不安全的HTML代码
				$date='';//日期格式转数字
				$save=XingAoSave($rs,$getlx,$savelx,$alone,$digital,$radio,$textarea,$html,$date);
	
				$xingao->query("insert into baoguo (".$save['field'].") values(".$save['value'].")");
				
				SQLError('转移包裹时:复制添加信息');
			}
		}
		
		//打印
		//$bgid=$bgid;//这里不能用:$bgid=$rs['bgid'];
		$_SESSION['ruku_warehouse']=$_POST['warehouse'];
		$_SESSION['print_tem']=par($_POST['print_tem']);
		$print_tem=par($_POST['print_tem']);
		require($_SERVER["DOCUMENT_ROOT"]."/xingao/baoguo/call/inStorage_print.php");//自动返回JS变量printOK
		

		music('yes');//播放提示声音
		
		$ts= '<strong>'.cadd($_POST['bgydh']).'：</strong>'.baoguo_Status($_POST['status']).'<br><font class=gray_prompt>所属会员:'.cadd($_POST['username']).'('.spr($_POST['userid']).')</font><br><br>';
		$alert_color='success';
		
		XAalert($ts,$alert_color);
		
		exit ("<script>setTimeout(\"goBack('c')\",\"1500\");</script>");//需要延时否则无声音
		
	}else{
		music('no');//播放提示声音
		exit ("<script>alert('{$LG['pptEditEmpty']}');setTimeout(\"goBack()\",\"1500\");</script>");
	}
	
	
}
//扫描到包裹时:入库保存－结束
?>
