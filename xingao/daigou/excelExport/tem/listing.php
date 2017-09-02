<?php
/*
注意：
》生成时不能打开已生成的文件，无法报错。
》保存中文时必须转码，用 '中文内容部分')
*/

//不类报表类型配置ToExcel_call.php所在路径
require_once($_SERVER['DOCUMENT_ROOT'].'/public/PHPExcel/tem_normal.php');


$query="select * from daigou where 1=1 {$where} {$Mmy} {$Xwh} {$order}";
$sql=$xingao->query($query);
while($rs=$sql->fetch_array())
{
	$excel_i+=1;
	$first=1;
	
	$imgAdd='';	$img=ToArr(cadd($rs['img']));	if($img[0]){$imgAdd='<XAimg>'.$img[0];}//取第一张图 (只能一张)
	
	$totalFee=daigou_totalFee($rs);//此单全部费用:价格币
	$totalPayTo=daigou_totalPay($rs,1);//已支付:支付币
	
	if($callFrom=='member'){$Address=cadd($rs['address']);}else{$Address=cadd($rs['procurementAddress']);}

	$mr=FeData('member','truename,enname',"userid='{$rs['userid']}'");

	//输出商品
    $go_i=0;
    $query_go="select * from daigou_goods where  dgid='{$rs['dgid']}' and number>0 order by godh asc";
    $sql_go=$xingao->query($query_go);
    while($go=$sql_go->fetch_array())
    {
        $go_i++;
		
		//输出运单
		$yd_i=0;
		$query_yd="select * from yundan where find_in_set('{$go['goid']}',goid) order by ydh desc";
		$sql_yd=$xingao->query($query_yd);
		while($yd=$sql_yd->fetch_array())
		{
			$yd_i++;
			
			$data[] = array(
				'list1'=>$imgAdd,//图片
				'list2'=>cadd($rs['dgdh']),//订单编号
				'list3'=>daigou_source(spr($rs['source'])),//货源
				'list4'=>daigou_brand($rs['brand']),//品牌
				'list5'=>cadd($rs['name']),//货号
				'list6'=>cadd($go['godh']),//商品单号
				'list7'=>$go['color']==0&&$go['colorOther']?cadd($go['colorOther']):classify($go['color'],2),//颜色
				'list8'=>$go['size']==0&&$go['sizeOther']?cadd($go['sizeOther']):classify($go['size'],2),//尺码
				'list9'=>spr($go['number']),//数量
				'list10'=>spr($go['price']).$rs['priceCurrency'],//单价
				'list11'=>$Address,//地址
				'list12'=>$rs['priceCurrency']?$totalFee.$rs['priceCurrency']:'',//总费:价格币
				'list13'=>$rs['toCurrency']?$totalPayTo.$rs['toCurrency']:'',//扣费:支付币
				'list14'=>$rs['username']?cadd($rs['username'])."({$rs['userid']})":'',//会员账号
				'list15'=>$mr['truename']?cadd($mr['truename'])."(".cadd($mr['enname']).")":'',//会员姓名
				'list16'=>striptags($rs['memberContent']),//会员留言
				'list17'=>striptags($rs['memberContentReply']),//回复会员
				
				'list18'=>cadd($yd['ydh']),//运单
				'list19'=>cadd($yd['dsfydh']),//运单
				'list20'=>$yd['gnkdydh']?$expresses[$yd['gnkd']].':'.cadd($yd['gnkdydh']):'',//运单
				'list21'=>$yd['ydh']?status_name(spr($yd['status'])):'',//运单
				'list22'=>DateYmd($yd['chukutime']),//运单
				'list23'=>yundan_add_all($yd),//运单
			);	
			unset($rs);	unset($go);	
		}
		unset($yd);
		
		
		
		
		//没运单数据时:下面的$data[]代码跟上面的$data[]完全一样
		if(!mysqli_num_rows($sql_yd))
		{
			$data[] = array(
				'list1'=>$imgAdd,//图片
				'list2'=>cadd($rs['dgdh']),//订单编号
				'list3'=>daigou_source(spr($rs['source'])),//货源
				'list4'=>daigou_brand($rs['brand']),//品牌
				'list5'=>cadd($rs['name']),//货号
				'list6'=>cadd($go['godh']),//商品单号
				'list7'=>$go['color']==0&&$go['colorOther']?cadd($go['colorOther']):classify($go['color'],2),//颜色
				'list8'=>$go['size']==0&&$go['sizeOther']?cadd($go['sizeOther']):classify($go['size'],2),//尺码
				'list9'=>spr($go['number']),//数量
				'list10'=>spr($go['price']).$rs['priceCurrency'],//单价
				'list11'=>$Address,//地址
				'list12'=>$rs['priceCurrency']?$totalFee.$rs['priceCurrency']:'',//总费:价格币
				'list13'=>$rs['toCurrency']?$totalPayTo.$rs['toCurrency']:'',//扣费:支付币
				'list14'=>$rs['username']?cadd($rs['username'])."({$rs['userid']})":'',//会员账号
				'list15'=>$mr['truename']?cadd($mr['truename'])."(".cadd($mr['enname']).")":'',//会员姓名
				'list16'=>striptags($rs['memberContent']),//会员留言
				'list17'=>striptags($rs['memberContentReply']),//回复会员
				
				'list18'=>cadd($yd['ydh']),//运单
				'list19'=>cadd($yd['dsfydh']),//运单
				'list20'=>$yd['gnkdydh']?$expresses[$yd['gnkd']].':'.cadd($yd['gnkdydh']):'',//运单
				'list21'=>$yd['ydh']?status_name(spr($yd['status'])):'',//运单
				'list22'=>DateYmd($yd['chukutime']),//运单
				'list23'=>yundan_add_all($yd),//运单
			);	
		}
		
		
		
		
		
		
		$imgAdd='';	$Address='';
		unset($rs);	unset($mr);	unset($yd);
	}
	
	
	
}//while($rs=$sql->fetch_array())
if (!$excel_i){exit ("<script>alert('{$LG['NoDataExported']}');goBack('c');</script>");} 











//正式导出---------------------------------------------------------
if($callFrom=='member'){$AddressName='电商网址/专柜地址';}else{$AddressName='采购地址';}

$enTable = array('list1','list2','list3','list4','list5','list6','list7','list8','list9','list10','list11','list12','list13','list14','list15','list16','list17','list18','list19','list20','list21','list22','list23');//保存内容

$cnTable=array(
	'图片',//list1
	'代购单号',//list2
	'货源',//list3
	'品牌',//list4
	'货号',//list5
	'商品单号',//list6
	'颜色',//list7
	'尺码',//list8
	'数量',//list9
	'单价',//list10
	$AddressName,//list11
	'总费',//list12
	'扣费',//list13
	'会员账号',//list14
	'会员姓名',//list15
	'会员留言',//list16
	'回复会员',//list17
	
	'运单-转运单号',//list18
	'运单-第三方转运单号',//list19
	'运单-派送单号',//list20
	'运单-状态',//list21
	'运单-出库时间',//list22
	'运单-收件地址',//list23
);//列表名

$excel-> getExcel($data,$cnTable,$enTable,'other',20);
//20单元格宽度，如果设置为‘auto’ 表示自适应宽度，也可是具体的数字
$success=1;
?>
