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
require_once($_SERVER['DOCUMENT_ROOT'].'/xingao/incluce/session.php');
if(!$ON_daigou){exit("<script>alert('{$LG['daigou.45']}');goBack('uc');</script>");}


//获取,处理=====================================================
$typ=par($_REQUEST['typ']);
$dgid=par(ToStr($_REQUEST['dgid']));
$tmp=par($_POST['tmp']);
$tokenkey=par($_POST['tokenkey']);


//添加,修改=====================================================
if($typ=='add'||$typ=='edit')
{
	//通用验证------------------------------------
	$token=new Form_token_Core();
	$token->is_token("daigou{$dgid}",$tokenkey); //验证令牌密钥

	if( !$_POST['warehouse']||!$_POST['source']||!$_POST['types']||!$_POST['name'] ){exit ("<script>alert('请填写完整红框内容！');goBack();</script>");}
	
	if($dgid){$rs=FeData('daigou','*',"dgid='{$dgid}'");}
	
	//提交验证,处理,返回全局
	daigou_chk();
	
	
	//留言最新标志
	if(add($_POST['memberContentReply'])!=$rs['memberContentReply']){$_POST['memberContentNew']=0;$_POST['memberContentReplyNew']=1;$_POST['memberContentReplyTime']=time();}
	if(add($_POST['sellerContentReply'])!=$rs['sellerContentReply']){$_POST['sellerContentNew']=0;$_POST['sellerContentReplyNew']=1;$_POST['sellerContentReplyTime']=time();}
		
	
	
	
	//添加------------------------------------
	if($typ=='add')
	{
		permissions('daigou_ad',1,'manage','');//验证权限
		
		if(!$tmp){exit ("<script>alert('tmp参数丢失，请刷新浏览器重新填写！');goBack();</script>");}
		
		$num=NumData('daigou_goods',"tmp<>'' and (tmp='{$tmp}' or tmpStaging='{$tmp}')");
		if(!$num){exit("<script>alert('请先添加商品！');goBack();</script>");}

		//更新商品列表：生成代购单-开始------------------------------------------
		$_POST['statusTime']=time();
		$_POST['addtime']=time();
		$_POST['addSource']=2; 
		$_POST['dgdh']=OrderNo('daigou',$_POST['warehouse']);
		$_POST['whcod']=add(createWhcod('daigou'));

		$savelx='add';//调用类型(add,edit,cache)
		$getlx='POST';//获取类型(POST,GET,REQUEST,SQL)
		$alone=daigou_with_field('alone').',tmp,tmpStaging';//不处理的字段
		$digital=daigou_with_field('dg_digital');//数字字段
		$radio='img';//单选、复选、空文本、数组字段
		$textarea='memberContentReply,sellerContentReply';//过滤不安全的HTML代码
		$date='';//日期格式转数字
	    $save=XingAoSave($_POST,$getlx,$savelx,$alone,$digital,$radio,$textarea,$html,$date);
		
		$xingao->query("insert into daigou  (".$save['field'].") values(".$save['value'].")");
		SQLError('添加代购单');
		$rc=mysqli_affected_rows($xingao);
		$dgid=mysqli_insert_id($xingao);
		
		//添加/修改代购单时更新商品列表
		daigou_goods_save($dgid,$tmp,$_POST['dgdh'],'');
		
		if($rc>0)
		{
			$token->drop_token("daigou{$dgid}"); //处理完后删除密钥
			exit("<script>alert('{$LG['pptAddSucceed']}\\n已生成{$rc}个代购单');location='user.php';</script>");
		}else{
			exit ("<script>alert('{$LG['pptAddFailure']}');goBack();</script>");
		}
	}
	
	
	
	
	
	
	
	
	
	
	
	
	//修改------------------------------------
	if($typ=='edit')
	{
		permissions('daigou_ed',1,'manage','');//验证权限
		
		if(!$dgid){exit ("<script>alert('dgid{$LG['pptError']}');goBack();</script>");}
		if(!$_POST['priceCurrency']){exit ("<script>alert('{$LG['daigou.159']}');goBack();</script>");}

		
		/*货源不修改时,手续服务费率也不能修改*/
		if($_POST['source']==$rs['source']){$alone=',serviceRate';$_POST['serviceRate']=$rs['serviceRate'];}//$_POST['serviceRate']在上面开头获取过
		$_POST['edittime']=time();
		
		
		
		//会员不可自填时的旧方式
		//if($_POST['priceCurrency']!=$rs['priceCurrency']){$f=GetArrVar($_POST['priceCurrency'],$member_per[$Mgroupid]['dg_freightFee']); $_POST['freightFee']=spr($f[1]);}//获取运费

		//更新
		$savelx='edit';//调用类型(add,edit,cache)
		$getlx='POST';//获取类型(POST,GET,REQUEST,SQL)
		$alone=daigou_with_field('alone').$alone.',status,tmp,tmpStaging';//不处理的字段
		$digital=daigou_with_field('dg_digital');//数字字段
		$radio='img';//单选、复选、空文本、数组字段
		$textarea='memberContentReply,sellerContentReply';//过滤不安全的HTML代码
		$date='';//日期格式转数字
		$save=XingAoSave($_POST,$getlx,$savelx,$alone,$digital,$radio,$textarea,$html,$date);
		
		$xingao->query("update daigou set ".$save." where dgid='{$dgid}' {$Xwh}");
		SQLError('修改代购单');		
		$rc=mysqli_affected_rows($xingao);
		
		if($rc>0){
			//添加/修改代购单时更新商品列表
			daigou_goods_save($dgid,$tmp,$rs['dgdh']);

			//更新支付情况
			daigou_Update($dgid);

			opLog('daigou',$dgid,1,'修改内容',1);//添加日志
		}
		
		//修改状态
		if($_POST['status']!=spr($rs['status'])){daigou_upStatus($rs,$_POST['status'],$exceed=1,$send=1,$pop=1,$callFrom='manage');}
		
		$token->drop_token("daigou{$dgid}"); //处理完后删除密钥
		exit("<script>alert('{$LG['pptEditSucceed']}');goBack('c');</script>");
		
	}
	
//取消订购/拒绝采购=====================================================
}elseif($typ=='cancel'){
	permissions('daigou_cg',1,'manage','');//验证权限

	if(!$dgid){exit ("<script>alert('dgid{$LG['pptError']}');goBack();</script>");}
	//多ID时:分开执行
	$arr=ToArr($dgid);
	if($arr)
	{
		foreach($arr as $arrkey=>$value)
		{
			$ppt=daigou_cancel($value,'manage');//处理
			$rc++;
		}
	}
	
	if($rc>1){
		exit("<script>alert('共处理:{$rc} \\n请查看列表是否操作成功');location='list.php';</script>");
	}else{
		exit("<script>alert('{$ppt}');location='list.php';</script>");
	}
	
	
//删除:只能删除不用处理费用问题的单=====================================================
}elseif($typ=='del'){
	
	permissions('daigou_ed',1,'manage','');//验证权限
		
	if(!$dgid){exit ("<script>alert('dgid{$LG['pptError']}');goBack();</script>");}
	
	//开启“长期保存记录”时禁止删除的状态
	if($off_delbak){$delbak_status=" and status not in (8,10)";$delbak_ppt=$LG['delbak_ppt'];}
	$where="dgid in ({$dgid}) 
	and 
	(
		(status in (0,1,2)  and pay='0') or (status in (8,10))
	)
	{$delbak_status}";

	//删除
	$query="select dgid,img from daigou where {$where} {$Xwh}";
	$sql=$xingao->query($query);
	while($rs=$sql->fetch_array())
	{
		//退回申请服务时所扣费用-开始
		daigou_memberStatus_refund($rs['dgid'],"dgid='{$rs['dgid']}'");
		$ppt='\\n如之前申请服务时有扣费,已自动退回';
		//退回申请服务时所扣费用-结束
		
		DelFile($rs['img']);
		$xingao->query("delete from daigou where dgid='{$rs['dgid']}'");
		$xingao->query("delete from daigou_goods where dgid='{$rs['dgid']}'");
		opLog('daigou',$rs['dgid'],2);//删除日志
		$rc+=1;
	}

	if($rc>0){
		exit("<script>alert('{$LG['pptDelSucceed']}{$rc}{$ppt}');location='list.php';</script>");
	}else{
		exit("<script>alert('{$LG['pptDelEmpty']}\\n处理中或已支付的代购单不能删除{$delbak_ppt}');location='list.php';</script>");
	}
	
	
}
?>
