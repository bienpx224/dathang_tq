<?php
//入库保存－开始--------------------------------------------------------------------------
if ($typ=='save'&&$dgid)
{ 
	//通用验证------------------------------------
	$token=new Form_token_Core();
	$token->is_token("daigou{$dgid}",par($_POST['tokenkey'])); //验证令牌密钥

	//代购单字段
	if(!$_POST['warehouse']){echo ("<script>alert('请选择仓库！');goBack();</script>");}	
	$_SESSION['inStorage_warehouse']=$_POST['warehouse'];
	$_SESSION['inStorage_whPlace']=$_POST['whPlace'];
	
	//商品字段:数组
	$goid=$_POST['goid'];
	$inventoryNumber=$_POST['inventoryNumber'];
	$weight=$_POST['weight'];
	$sizeLength=$_POST['sizeLength'];
	$sizeWidth=$_POST['sizeWidth'];
	$sizeHeight=$_POST['sizeHeight'];
	$barcode=$_POST['barcode'];
	
	//获取数量和对应ID
	if($inventoryNumber)
	{
		foreach($inventoryNumber as $key=>$value)
		{
			if(!spr($value)){continue;}
			
			//查询,验证
			$gd=FeData('daigou_goods','*',"goid='{$goid[$key]}'");
			$notStorageNumber=$gd['number']-$gd['inventoryNumber'];//没入库数量
			if($value>$notStorageNumber){$value=$notStorageNumber;}
			if($value<0&&abs($value)>$gd['inventoryNumber']){$value=-$gd['inventoryNumber'];}//减数量时,不能超过已入库数量
			
			//更新保存
			$xingao->query("update daigou_goods set 
				inventoryNumber=inventoryNumber+{$value},
				weight='".spr($weight[$key])."',
				sizeLength='".spr($sizeLength[$key])."',
				sizeWidth='".spr($sizeWidth[$key])."',
				sizeHeight='".spr($sizeHeight[$key])."',
				barcode='".add($barcode[$key])."'
			where goid='{$goid[$key]}' ");
			SQLError('入库保存修改');
			if(mysqli_affected_rows($xingao)>0){$in_spec++;$in_number+=$value;$in_weight+=$weight[$key]*$value;}
		}
	}
	
	//更新代购单
	$xingao->query("update daigou set 
		whPlace='".add($_POST['whPlace'])."',
		warehouse='".spr($_POST['warehouse'])."'
	where dgid='{$dgid}' ");
	SQLError('入库保存修改');
	
	
	
	
	//返回
	if($in_spec)
	{	
		$token->drop_token("daigou{$dgid}"); //处理完后删除密钥
		
		music('yes');//播放提示声音
		
		//更新代购单状态
		if($in_number>0||spr($rs['status'])==9)
		{
			$num=NumData('daigou_goods',"inventoryNumber<number and dgid='{$dgid}'");
			if($num){$status=8;}else{$status=9;}//8部分入库;9全部入库
			daigou_upStatus($dgid,$status,$exceed=0,$send=0,$pop=0,$callFrom='manage',$notify=0);
		}
		
		//发通知
		if($in_number>0){
			//获取发送通知内容
			//要其他参数,$dgid,$in_spec,$in_number,$in_weight
			$NoticeTemplate='daigou_notice_storage';
			require($_SERVER['DOCUMENT_ROOT'].'/public/NoticeTemplate.php');
		}
		
		$ppt=LGtag($LG['daigou.inStorageSend_2'],'<tag1>=='.$in_spec.'||<tag2>=='.$in_number.'||<tag3>=='.$in_weight.$XAwt);//入库商品:<tag1><br>入库总数量:<tag2><br>入库总重量:<tag3>
		
		if($ppt){XAalert($ppt,'success');}
		
		//需要延时否则无声音,typeof(lodopCall)=='undefined'没调用打印(调用时lodop已有自动关闭)
		exit ("<script>if(typeof(lodopCall)=='undefined'){ setTimeout(\"goBack('c')\",\"1000\"); }</script>");
		
	}else{
		music('no');//播放提示声音
		exit ("<script>alert('{$LG['pptEditEmpty']}');setTimeout(\"goBack()\",\"1500\");</script>");//需要延时否则无声音
	}
	
	
	
}
//扫描到包裹时:入库保存－结束
?>
