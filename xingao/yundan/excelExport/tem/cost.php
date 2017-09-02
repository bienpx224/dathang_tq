<?php
/*
注意：
》生成时不能打开已生成的文件，无法报错。
》保存中文时必须转码，用 '中文内容部分')
*/

//不类报表类型配置ToExcel_call.php所在路径
require_once($_SERVER['DOCUMENT_ROOT'].'/public/PHPExcel/tem_normal.php');


$query="select * from yundan where 1=1 {$where} ".whereCS()."   {$Xwh} and pay='1' order by payment_time asc";
$sql=$xingao->query($query);
while($rs=$sql->fetch_array())
{
	$excel_i+=1;
	
	//增值服务-开始
	$value_show='';
	if($rs['bgid'])
	{
		//会员资料
		$groupid=FeData('member','groupid',"userid='{$rs['userid']}'");
		$channel=$rs['channel'];
		$warehouse=$rs['warehouse'];
		require($_SERVER['DOCUMENT_ROOT'].'/xingao/yundan/call/get_price.php');//获取价格

		ob_start();//开始缓冲
			//获取合箱发货费用($baoguo_hx_fee)
			$bgid=$rs['bgid'];$show_small=1;
			require_once($_SERVER['DOCUMENT_ROOT'].'/xingao/yundan/call/bg_hx_fh.php');//$baoguo_hx_fee
			
			//输出增值服务(用到$baoguo_hx_fee)
			require($_SERVER['DOCUMENT_ROOT'].'/xingao/yundan/call/value_show.php');
			$value_show=ob_get_contents();//得到缓冲区的数据
		ob_end_clean();//结束缓存：清除并关闭缓冲区
	}
	//增值服务-结束

	$data[] = array(
		'list1'=>DateYmd($rs['payment_time']),
		'list2'=>cadd($rs['userid']),
		'list3'=>cadd($rs['s_name']),
		'list4'=>cadd($rs['ydh']),
		'list5'=>status_name(spr($rs['status'])),
		'list6'=>warehouse($rs['warehouse']),
		'list7'=>channel_name(FeData('member','groupid',"userid='{$rs['userid']}'"),$rs['warehouse'],$rs['country'],$rs['channel']),
		'list8'=>yundan_addSource($rs['addSource']),
		'list9'=>spr($rs['weight']),
		'list10'=>spr($rs['fee_transport']),
		'list11'=>$rs['discount']>0?$fee_discount=spr($rs['fee_transport']*$rs['discount']/10).'('.spr($rs['discount']).'折)':$fee_discount=0,
		'list12'=>spr($rs['insurevalue']),
		'list13'=>spr($rs['fee_tax']),
		'list14'=>spr($rs['fee_cc']),
		'list15'=>spr($rs['fee_service']),
		'list23'=>spr($rs['fee_ware']),
		'list16'=>spr($rs['fee_other']),
		'list17'=>striptags($value_show),
		'list18'=>$fee_discount?spr($rs['fee_transport']-$fee_discount):0,
		'list19'=>spr($rs['money']),
		'list20'=>spr($rs['tax_payment']),
		'list21'=>arrcount($rs['bgid']),
		'list22'=>striptags($rs['money_content']),
		);

	//读取身份证文件
	require($_SERVER['DOCUMENT_ROOT'].'/xingao/yundan/excelExport/card_file.php'); 
}//while($rs=$sql->fetch_array())

if (!$excel_i)
{ 
	exit ("<script>alert('没有数据可导出 (必须是已付款的运单)!');goBack('c');</script>");
} 

//正式导出---------------------------------------------------------
$enTable = array('list1','list2','list3','list4','list5','list6','list7','list8','list9','list10','list11','list12','list13','list14','list15','list23','list16','list17','list18','list19','list20','list21','list22');//保存内容
$cnTable=array(
	'支付时间',//list1
	'会员ID',//list2
	'收件人',//list3
	'运单号',//list4
	'运单状态',//list5
	'仓库',//list6
	'渠道',//list7
	'运单来源',//list8
	'货物重量',//list9
	'原运费',//list10
	'折后运费',//list11
	'保价费',//list12
	'固定税',//list13
	'体积费',//list14
	'服务费',//list15
	'仓储费',//list23
	'其他费',//list16
	'增值服务',//list17
	'优惠金额',//list18
	'总计',//list19
	'关税(另收)',//list20
	'合箱数',//list21
	'计费备注',//list22
);//列表名
$excel-> getExcel($data,$cnTable,$enTable,'other',20);
//20单元格宽度，如果设置为‘auto’ 表示自适应宽度，也可是具体的数字

$success=1;
?>
																
							

