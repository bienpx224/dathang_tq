<?php
/*
注意：
》生成时不能打开已生成的文件，无法报错。
》保存中文时必须转码，用 '中文内容部分')
*/

//不类报表类型配置ToExcel_call.php所在路径
require_once($_SERVER['DOCUMENT_ROOT'].'/public/PHPExcel/tem_normal.php');


$query="select * from yundan where 1=1 {$where} {$Xwh} {$order}";
$sql=$xingao->query($query);
while($rs=$sql->fetch_array())
{
	$excel_i+=1;
	$first=1;
	
	$i=$excel_i;
	$rs['userid']=cadd($rs['userid']);
	$rs['ydh']=cadd($rs['ydh']);
	$rs['goodsdescribe']=striptags($rs['goodsdescribe']);

	//基本资料:获取运单全部操作要求
	$callFrom=$callFrom;//member 会员中心($callFrom print.php传值)
	$call_payment=0;//费用及付款情况
	$call_basic=0;//基本资料
	$call_op=1;//操作要求
	$call_baoguo=0;//包裹
	$call_goodsdescribe=0;//货物
	$call_content=1;//备注
	$call_reply=1;//回复
	$call_print=1;//打印调用
	$callFrom_show=1;//显示全部文字内容
	
	ob_start();//开始缓冲
	require($_SERVER['DOCUMENT_ROOT'].'/xingao/yundan/call/basic_show.php');
	$DataCache=ob_get_contents();//得到缓冲区的数据
	ob_end_clean();//结束缓存：清除并关闭缓冲区
	
	//处理数据
	$DataCache=str_ireplace('<div class="gray modal_border">','<span class="xa_sep"> | </span>',$DataCache);//过滤HTML后内容会连续,需要先分开
	$DataCache=striptags($DataCache);
	$DataCache=str_ireplace('||','|',$DataCache);

	
	if($rs['bgid'])
	{
		//获取包裹表-开始
		$query_bg="select bgydh,bgid,useric,addSource,whPlace,weight from baoguo where bgid in ({$rs['bgid']})  order by bgydh asc";
		$sql_bg=$xingao->query($query_bg);
		while($bg=$sql_bg->fetch_array())
		{
			if (!$first)
			{
				$i='';
				$rs['userid']='';
				$rs['ydh']='';
				$rs['goodsdescribe']='';
				$DataCache='';
			}
		
			$data[] = array(
			   'list1'=>$i,
			   'list2'=>$rs['userid'],
			   'list3'=>$rs['ydh'],
			   'list4'=>$rs['goodsdescribe'],
			   'list5'=>cadd($bg['useric']),
			   'list6'=>cadd($bg['whPlace']),
			   'list7'=>spr($bg['weight']).$XAwt,
			   'list8'=>cadd($bg['bgydh']),
			   'list9'=>$DataCache
			);	
				
			$first=0;	
		}
		//获取包裹表-结束
		
	}elseif($rs['goid']){
		
		//获取代购商品-开始
		$query_go="select godh,goid,dgid,weight from daigou_goods where goid in ({$rs['goid']})  order by goid asc";
		$sql_go=$xingao->query($query_go);
		while($go=$sql_go->fetch_array())
		{
			$dg=FeData('daigou','dgdh,whPlace',"dgid='{$go['dgid']}'");
			$wp=FeData('wupin','wpid,wupin_number',"goid='{$go['goid']}'");

			if (!$first)
			{
				$i='';
				$rs['userid']='';
				$rs['ydh']='';
				$rs['goodsdescribe']='';
				$DataCache='';
			}
			$data[] = array(
			   'list1'=>$i,
			   'list2'=>$rs['userid'],
			   'list3'=>$rs['ydh'],
			   'list4'=>$rs['goodsdescribe'],
			   'list5'=>'',
			   'list6'=>cadd($dg['whPlace']),
			   'list7'=>spr($go['weight']*$wp['wupin_number']).$XAwt,
			   'list8'=>cadd($go['godh']),
			   'list9'=>$DataCache
			);	
				
			$first=0;	
		}
		//获取代购商品-结束
		
	}else{
		$data[] = array(
		   'list1'=>$i,
		   'list2'=>$rs['userid'],
		   'list3'=>$rs['ydh'],
		   'list4'=>$rs['goodsdescribe'],
		   'list5'=>'',
		   'list5'=>'',
		   'list6'=>'',
		   'list7'=>'',
		   'list8'=>$DataCache
		);	
	}




	
	//读取身份证文件
	require($_SERVER['DOCUMENT_ROOT'].'/xingao/yundan/excelExport/card_file.php'); 
}//while($rs=$sql->fetch_array())

if (!$excel_i)
{ 
	exit ("<script>alert('{$LG['NoDataExported']}');goBack('c');</script>");
} 

//正式导出---------------------------------------------------------
$enTable = array('list1','list2','list3','list4','list5','list6','list7','list8','list9');//保存内容
$cnTable=array(
	'序号',//list1
	'客户ID',//list2
	'运单号',//list3
	'运单物品',//list4
	'包裹入库码',//list5
	'包裹仓位',//list6
	'包裹重量',//list7
	'包裹单号',//list8
	'打包说明',//list9
);//列表名
$excel-> getExcel($data,$cnTable,$enTable,'other',20);
//20单元格宽度，如果设置为‘auto’ 表示自适应宽度，也可是具体的数字

$success=1;
?>
