<?php 
//入库保存－开始------------------------------------------------------------------------------------------
$alert_color='info';
$lx=par($_POST['lx']);
if($lx=="smt")
{ 
	$bgydh=par($_POST['bgydh']);
	$sm=spr($_POST['sm']);
	$_SESSION['shelves_whPlace']=par($_POST['whPlace']);
	$_SESSION['getTyp']=spr($_POST['getTyp']);
	

	$ts='';$ok=1;$result='';
	//验证
	if (!$bgydh&&$ok){$ok=0;$ts.='请输入单号！';}	

	//搜索包裹
	$id='bgid';
	$table='baoguo';
	$field='bgydh';
	$smlx=0;//1只精确搜索;0全部搜索
	$where_search="";//有其他条件时,以空格 and 开头,如: and userid='{$userid}'
	$rsid=searchNumber($id,$table,$field,$bgydh,$smlx,$where_search);//搜索处理


	if(!$rsid&&$ok){$ok=0;$ts.='找不到该包裹！';}	
	$bgid=$rsid;
	
	$rs=FeData('baoguo','warehouse,whPlace,userid,username,status,delivery',"bgid='{$bgid}'");
	$ts='';
	if(!$rs['warehouse']&&$ok){$ok=0;$ts.='仓库错误！';}	
	if(spr($rs['status'])<1.5&&$ok){$ok=0;$ts.='未到仓库！';}
	if(spr($rs['status'])>1.5&&$ok){$ok=0;$ts.='已上架过！';}

	if($ok)
	{
		if(spr($rs['delivery'])){$_POST['status']=4;}elseif($baoguo_qr){$_POST['status']=3;}else{$_POST['status']=2;}
		$_POST['rukutime']=time();
		
		//更新
		$savelx='edit';//调用类型(add,edit,cache)
		$getlx='POST';//获取类型(POST,GET,REQUEST,SQL)
		$alone='bgid,sm,print_tem,bgydh,getTyp,shelves';//不处理的字段
		$digital='';//数字字段
		$radio='';//单选、复选、空文本、数组字段
		$textarea='reply';//过滤不安全的HTML代码
		$date='';//日期格式转数字
		$save=XingAoSave($_POST,$getlx,$savelx,$alone,$digital,$radio,$textarea,$html,$date);
		
		$xingao->query("update baoguo set {$save} where bgid='{$bgid}' ");SQLError('更新');
		
		baoguoInStorage($bgid);//按包裹入库情况更新运单
		
		$result='yes';
		$ts=baoguo_Status($_POST['status']);
	}





	//返回结果输出---------------
	if($result=='yes')
	{
		$ts='<strong>'.$bgydh.'：</strong>'.$ts.'<br><font class=gray_prompt>所属会员:'.($rs['userid']?cadd($rs['username']).'('.$rs['userid'].')':'无主包裹').' </font><br><br>';
		
		//打印
		//$bgid=$bgid;
		$print_tem=par($_POST['print_tem']);
		$_SESSION['sv_print_tem']=$print_tem;
		require($_SERVER["DOCUMENT_ROOT"]."/xingao/baoguo/call/inStorage_print.php");//自动返回JS变量printOK


		music('yes');//播放提示声音
		$alert_color='success';
		unset($_POST);
	}else{
		music('no');//播放提示声音
		$alert_color='danger';
		echo '<script>printOK=1;</script>';//不用打印时,返回JS变量printOK,以便可关闭页面
	}


	//输出各种处理的提示-----------------------------------------------------------------------------------
	if(!$sm)
	{
		XAalert($ts,$alert_color,'style="font-size: 18px; line-height:23px; padding-left:20px;"');
	}
	
	
	if($sm){exit ("<script>setTimeout(\"goBack('c')\",\"1500\");</script>");}
}
?>
