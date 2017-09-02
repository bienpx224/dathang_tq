<?php 
//入库保存－开始------------------------------------------------------------------------------------------
$alert_color='info';
$lx=par($_POST['lx']);
if($lx=="add")
{ 
	$bgydh=par($_POST['bgydh']);
	$warehouse=par($_POST['warehouse']);
	//<!--2017.06.17:删除dgid-->
	$sm=spr($_POST['sm']);
	$_SESSION['in_shelves']=spr($_POST['shelves']);
	$_SESSION['shelves_whPlace']=par($_POST['whPlace']);
	$_SESSION['getTyp']=spr($_POST['getTyp']);

	//指定值
	if($_POST['reply']){$_POST['replytime']=time();}
	
	
	if($_POST['shelves']){
		//已上架(入库)
		if($baoguo_qr){$_POST['status']=3;}else{$_POST['status']=2;}
		$_POST['rukutime']=time();
	}else{
		//未上架(不入库)
		$_POST['status']=1.5;
		$_POST['rukutime']=0;
	}
	
	$ts='';$ok=1;$result='';
	//验证
	if (!$bgydh){$ok=0;$ts.='请输入单号！';}	
	if (!$warehouse){$ok=0;$ts.='请选择仓库！';}	
	
		
	//查询会员是否存在
	if ($_POST['useric']||$_POST['userid']||$_POST['username'])
	{
		if (!MemberOK('',$_POST['useric'],$_POST['userid'],$_POST['username'],1,0))
		{
			$ok=0;$ts.='所填写会员账号错误';
		}
	}
	

	//有预报-更新---------------
	if($ok)
	{
		$rs=FeData('baoguo','bgid,status',"bgydh='{$bgydh}' and username='".$_POST['username']."' and userid='".spr($_POST['userid'])."' and useric='".$_POST['useric']."'");
		
		$bgid=$rs['bgid'];
		if($bgid)
		{
			if(spr($rs['status'])>1){$ok=0;$ts.='该包裹之前已经入库过 ('.baoguo_Status(spr($rs['status'])).')!';}
			if($ok)
			{

				//更新
				$savelx='edit';//调用类型(add,edit,cache)
				$getlx='POST';//获取类型(POST,GET,REQUEST,SQL)
				$alone='bgid,sm,print_tem,print_tem_plug,getTyp,shelves';//不处理的字段
				$digital='warehouse,weight,addid';//数字字段
				$radio='';//单选、复选、空文本、数组字段
				$textarea='reply';//过滤不安全的HTML代码
				$date='';//日期格式转数字
				$save=XingAoSave($_POST,$getlx,$savelx,$alone,$digital,$radio,$textarea,$html,$date);
				
				$xingao->query("update baoguo set {$save} where bgid='{$bgid}' ");SQLError('有预报-更新');
				
				$rc=mysqli_affected_rows($xingao);
				if($rc>0)
				{
					baoguoInStorage($bgid);//通用更新
					
					$result='yes';$ts='该包裹有预报，已更新状态(未更新物品资料)';
				}else{
					$result='no';$ts='该包裹有预报但更新状态失败!';
				}

			}
		}
	}
	
	//无预报-添加---------------
	if($ok&&!$result)
	{
		//无主包裹
		if (!$_POST['useric']&&!$_POST['userid']&&!$_POST['username'])
		{
			$_POST['unclaimed']=1;
		}
		
		//验证运单号
		$num=mysqli_num_rows($xingao->query("select bgydh from baoguo where bgydh='{$bgydh}' "));
		if($num){$ok=0;$ts.=$bgydh.' 该单号已存在,请修改！';}

		if($ok)
		{
			$_POST['addSource']=2; //<!--2017.06.17:删除dgid-->

			$savelx='add';//调用类型(add,edit,cache)
			$getlx='POST';//获取类型(POST,GET,REQUEST,SQL)
			$alone='bgid,sm,print_tem,print_tem_plug,getTyp,shelves';//不处理的字段
			$digital='warehouse,weight,addid,userid';//数字字段
			$radio='';//单选、复选、空文本、数组字段
			$textarea='reply,unclaimedContent';//过滤不安全的HTML代码
			$date='';//日期格式转数字
			$save=XingAoSave($_POST,$getlx,$savelx,$alone,$digital,$radio,$textarea,$html,$date);

			$xingao->query("insert into baoguo (".$save['field'].") values(".$save['value'].")");SQLError('无预报-添加');
			$rc=mysqli_affected_rows($xingao);
			$bgid=mysqli_insert_id($xingao);
			wupin_save('baoguo',$bgid,0);
			if($rc>0)
			{
				$result='yes';$ts='';
			}else{
				$result='no';$ts='添加失败!';
			}
		
		}
	}

	//返回结果输出---------------
	if($result=='yes')
	{
		$ts='<strong>'.$bgydh.'：</strong>入库成功! '.$ts.'<br><font class=gray_prompt>所属会员:'.($_POST['userid']?cadd($_POST['username']).'('.spr($_POST['userid']).')':'无主包裹').' </font><br><br>';

		//<!--2017.06.17:删除dgid-->
		
		//打印
		//$bgid=$bgid;
		$_SESSION['ruku_warehouse']=$_POST['warehouse'];
		$print_tem=par($_POST['print_tem']);
		$_SESSION['print_tem']=$print_tem;
		require($_SERVER["DOCUMENT_ROOT"]."/xingao/baoguo/call/inStorage_print.php");//自动返回JS变量printOK
		
		//发通知
		//$bgid=$bgid;
		if(!$_POST['unclaimed']){
			//获取发送通知内容
			//$bgid=$rs['bgid'];
			$NoticeTemplate='baoguo_notice_storage';
			require($_SERVER['DOCUMENT_ROOT'].'/public/NoticeTemplate.php');
		}
		
		music('yes');//播放提示声音
		
		unset($_POST);
		$alert_color='success';
	}else{
		music('no');//播放提示声音
		$alert_color='danger';
		echo '<script>printOK=1;</script>';//不用打印时,返回JS变量printOK,以便可关闭页面
	}

	//输出各种处理的提示-----------------------------------------------------------------------------------
	XAalert($ts,$alert_color,'style="font-size: 18px; line-height:23px; padding-left:20px;"');
	if($sm){exit ("<script>setTimeout(\"goBack('c')\",\"1500\");</script>");}//需要延时否则无声音

}

//入库保存－结束
?>
