<?php 
/*
	调用:
	$where=?;//主运单
	$copy_number=1;//复制数量
	$callFrom=='member';//member:会员下单时; manage:后台复制
	require_once($_SERVER['DOCUMENT_ROOT'].'/xingao/yundan/call/copyYundan.php');//复制处理
*/
$rc=0;
$query="select * from yundan where {$where} ";
$sql=$xingao->query($query);
while($rs=$sql->fetch_array())
{
	$main_save='';
	$main_ydid=$rs['ydid'];
	$main_ydh=$rs['ydh'];
	$main_weightEstimate=$rs['weightEstimate'];
	$main_content=$rs['content'];
	$main_s_shenfenimg_z=$rs['s_shenfenimg_z'];
	$main_s_shenfenimg_b=$rs['s_shenfenimg_b'];
	
	//复制全部-开始--------
	for ($ci=1; $ci<=$copy_number; $ci++)
	{
		//新信息的单号
		$rs['ydh']=add(copyOrderNo('yundan',$main_ydh,$copy_typ=2,$copy_digit=2,1));	$copy_ydh=$rs['ydh'];
		$rs['addtime']=time();
		$rs['fx']=1;
		
		if($callFrom=='manage')
		{
			$rs['addSource']='4';
			$rs['money']='0';
			$rs['money_content']='';
			$rs['pay']='0';
			$rs['payment']='0';
			$rs['payment_time']='0';
			$rs['tax_pay']='0';
			$rs['tax_payment']='0';
			$rs['tax_payment_time']='0';
			$rs['weight']='0';
			$rs['weightEstimate']='0';
			$rs['edittime']='0';
			$rs['content']="从{$main_ydh}运单号分包出来；
".$main_content;
	
		}elseif($callFrom=='member'){
			
			//平分预估重量
			if($main_weightEstimate>0)
			{
				$rs['weightEstimate']=$main_weightEstimate/($copy_number+1);
				$main_save="weightEstimate='{$rs['weightEstimate']}',";
			}
			
	    }
		
		
		
		if($main_s_shenfenimg_z)
		{
			$shenfenimg_z='/upxingao/card/'.DateYmd(time(),2).'/'.newFilename($main_s_shenfenimg_z);
		   CopyFile($main_s_shenfenimg_z,$shenfenimg_z);//复制图片
			$rs['s_shenfenimg_z']=add($shenfenimg_z);
		}
		
		if($main_s_shenfenimg_b)
		{
			$shenfenimg_b='/upxingao/card/'.DateYmd(time(),2).'/'.newFilename($main_s_shenfenimg_b);
			CopyFile($main_s_shenfenimg_b,$shenfenimg_b);//复制图片
			$rs['s_shenfenimg_b']=add($shenfenimg_b);
		}
		
		
		$savelx='add';//调用类型(add,edit,cache)
		$getlx='SQL';//获取类型(POST,GET,REQUEST,SQL)
		$alone='ydid,addtime';//不处理的字段
		$digital='';//数字字段
		$radio='';//单选、复选、空文本、数组字段
		$textarea='';//过滤不安全的HTML代码
		$date='';//日期格式转数字
		$save=XingAoSave($rs,$getlx,$savelx,$alone,$digital,$radio,$textarea,$html,$date);
		
		$xingao->query("insert into yundan (".$save['field'].") values(".$save['value'].")");
		SQLError('复制添加信息');
		$ydid_new=mysqli_insert_id($xingao);
		
		if(mysqli_affected_rows($xingao)>0)
		{
			$rc+=1;
			
			//复制物品-开始
			$query_wp="select * from wupin where fromtable='yundan' and fromid='{$rs['ydid']}'";
			$sql_wp=$xingao->query($query_wp);
			while($wp=$sql_wp->fetch_array())
			{
				unset($save); 
				$savelx='add';//调用类型(add,edit,cache)
				$getlx='SQL';//获取类型(POST,GET,REQUEST,SQL)
				$alone='wpid,fromid';//不处理的字段
				$digital='';//数字字段
				$radio='';//单选、复选、空文本、数组字段
				$textarea='';//过滤不安全的HTML代码
				$date='';//日期格式转数字
				$save=XingAoSave($wp,$getlx,$savelx,$alone,$digital,$radio,$textarea,$html,$date,1);
				$xingao->query("insert into wupin (".$save['field'].",fromid) values(".$save['value'].",'{$ydid_new}')");
				SQLError('复制添加物品');
			}
			//复制物品-结束
			
		}
		
	}//for ($ci=1; $ci<=$copy_number; $ci++) 
	//复制全部-结束--------
	
	
	
	
	//更新主运单
	$main_save.="fx='1',";
	
	//修改主单号:主单号后面加字母或数字----开始
	//是加字母还是加数字
	if($copy_typ==2){$add_main_ydh='A';$copy_digit=1;}elseif($copy_typ==3){$add_main_ydh=Digit(1,$copy_digit);}
	
	//识别是否是主单号
	if(
		fnCharCount($main_ydh)<fnCharCount($copy_ydh) &&  
		(
			($ydh_suffix&&substr($main_ydh,-fnCharCount($ydh_suffix))==$ydh_suffix)
			||
			(!$ydh_suffix&&substr($main_ydh,-$copy_digit)!=$add_main_ydh)
		)
	)
	{
		$num=NumData('yundan',"ydh='{$main_ydh}{$add_main_ydh}'");
		if(!$num){$main_save.="ydh='{$main_ydh}{$add_main_ydh}',";}
	}
	//修改主单号:主单号后面加字母或数字----结束
	
	$main_save=DelStr($main_save);
	$xingao->query("update yundan set {$main_save} where ydid='{$main_ydid}'");SQLError('更新主运单');
	
	
	  
}//while($rs=$sql->fetch_array())
?>