<?php
require_once($_SERVER['DOCUMENT_ROOT'].'/public/PHPExcel/Export_call.php');

//以下可配置
class ChangeArrayToExcel
{
	private $excelName;   //xls文件名，包括生成路径
	public function __construct($name = '/upxingao/export/excel.xls')	{if($name){$this->excelName = $name;}}
	
	/*
	* @param $data 包含excel文件内容的数组
	* @param $txArr 包含excel表头信息（中文)  例如array('编号',"姓名")
	* @param $txArrEn excel表头信息（英文） 例如array('id','username')
	* @param $excelVersion 生成excel文件的版本  可选值为other,2007
	* @param $width 单元格宽度，如果设置为‘auto’ 表示自适应宽度，也可是具体的数字
	* @renturn excel文件的绝对路径
	*/
	public function getExcel($data,$txArr,$txArrEn,$excelVersion = "other",$width="auto")
	{
		require($_SERVER['DOCUMENT_ROOT'].'/public/global.php');
		//验证列数是否正确
		if(count($txArr) != count($txArrEn) && count($txArrEn) != count($data['0']) && !empty($data)){exit($LG['headerError']);}
	
		// 创建一个处理对象实例   
		$objExcel = new PHPExcel();
		
		//设置当前的sheet索引，用于后续的内容操作。一般只有在使用多个sheet的时候才需要显示调用。缺省情况下，PHPExcel会自动创建第一个sheet被设置SheetIndex=0   
		$objExcel->setActiveSheetIndex(0);   
	
		//单元格操作对象
		$objActSheet = $objExcel->getActiveSheet();
	
		$objExcel->getActiveSheet()->getRowDimension('1')->setRowHeight(40);//设置行的高度
		$objActSheet->mergeCells('A1:P1'); //合并
		
		//格式：主要用来对单元格进行操作，如，设置字体、设置对齐方式、设置边框等
		$objStyleA1 = $objActSheet->getStyle('A1');//获取A1单元格的样式,写1则表示第1行
	
		//设置单元格的对齐方式  
		$objAlignA1 = $objStyleA1->getAlignment();//获得对齐方式
		$objAlignA1->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);//水平居中 
		$objAlignA1->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);//垂直居中
		
		$objActSheet->setCellValue('A1', '运管人名称: 深圳海淘物流有限公司'); //写入内容
	
		/*设置表头宽度，将表头内容添加到excel文件里*/
		foreach($txArr as $key =>$value)
		{
		$objActSheet->setCellValue(numToEn($key)."2",$value);
		/*设置对齐方式*/
		$objActSheet->getStyle(numToEn($key)."2")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		/*设置字体加粗*/
		$objActSheet->getStyle(numToEn($key)."2")->getFont()->setBold(true);
		$width == "auto"? $objActSheet->getColumnDimension(numToEn($key))->getAutoSize(true): $objActSheet->getColumnDimension(numToEn($key))->setWidth($width);
		}
	
		//设置单元格的对齐方式  
		$objStyleB2 = $objActSheet->getStyle('B2');//获取A5单元格的样式
		$objAlignB2 = $objStyleB2->getAlignment();//获得对齐方式
		$objAlignB2->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);//水平居中 
		$objAlignB2->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);//垂直居中
		
		$objFontB2 = $objStyleB2->getFont(); //获得字体
		$objFontB2->getColor()->setARGB('FF0000');//设置字体颜色
	
		
		$objStyleD2 = $objActSheet->getStyle('D2');//获取A5单元格的样式
		$objAlignD2 = $objStyleD2->getAlignment();//获得对齐方式
		$objAlignD2->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);//水平居中 
		$objAlignD2->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);//垂直居中
	
		$objStyleN2 = $objActSheet->getStyle('N2');//获取A5单元格的样式
		$objAlignN2 = $objStyleN2->getAlignment();//获得对齐方式
		$objAlignN2->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);//水平居中 
		$objAlignN2->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);//垂直居中
	
		/*将数据添加到excel里*/
		foreach($data as $key=>$value)
		{
			foreach($txArrEn as $k => $val)
			{
				$objActSheet->getStyle(numToEn($k).($key+3))->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_TEXT);
				$objActSheet->setCellValue(numToEn($k).($key+3)," ".$value[$val]); //在写入Excels单元格的内容之前加一个空格，防止长数字被转化成科学计数法
				$objActSheet->getStyle(numToEn($k).($key+3))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			}
		}
		
		/*判断生成excel文件版本*/
		$objWriter ='';
		if($excelVersion == "other")
		{
			$objWriter = new PHPExcel_Writer_Excel5($objExcel);
		}
		if($excelVersion == "2007")
		{
			$objWriter = new PHPExcel_Writer_Excel2007($objExcel);
		}
		
		$objWriter->save($this->excelName);
		return $this->excelName;
	}

}
?>











<?php
/*
注意：
》生成时不能打开已生成的文件，无法报错。
》保存中文时必须转码，用 '中文内容部分')
*/
$query="select * from yundan where 1=1 {$where} ".whereCS()."   {$Xwh} {$order}";
$sql=$xingao->query($query);
while($rs=$sql->fetch_array())
{
	$excel_i+=1;
	//读取多值字段-开始
	$wupin_number=0;
	$category='';
	$wupin_brand='';
	
	$fromtable='yundan'; $fromid=$rs['ydid'];
	$query_wp="select * from wupin where fromtable='{$fromtable}' and fromid='{$fromid}' order by wupin_name desc";
	$sql_wp=$xingao->query($query_wp);
	while($wp=$sql_wp->fetch_array())
	{
		$wupin_number+=$wp['wupin_number'];//总计数量
		
		//英文品名
		if($wupin_brand){$wupin_brand.="/".cadd($wp['wupin_brand']);}else{$wupin_brand=cadd($wp['wupin_brand']);}
		
		//商品名称和数量
		if($category){$category.="/".cadd($wp['wupin_name'])."*".cadd($wp['wupin_number']);}else{$category=cadd($wp['wupin_name'])."*".cadd($wp['wupin_number']);}
	
	}
	//读取多值字段－结束
	$ydh= preg_replace('/[^0-9]/','',cadd($rs['ydh'])); 
	//$ydh= cadd($rs['ydh']); 
	$rs['weight']=spr($rs['weight'])*$XAwtkg;
	$rs['declarevalue']=spr($rs['declarevalue']*exchange($XAScurrency,$XAMcurrency));//转主币种

	$data[] = array(
		'list1'=>$excel_i,
		'list2'=>$rs['hscode'],
		'list3'=>$ydh,
		
		'list4'=>$category,//物品名（ANR棕瓶精华*1 蜂胶软胶囊*4 ）
		'list5'=>$wupin_brand,//品牌（Ddrops/Boon/Cetaphil/Coppertone）
		'list6'=>'',//规格/型号
		'list7'=>1,//件数
		'list8'=>spr($rs['weight']),//标题显示：重量KG (原是LB，转为KG)
		'list9'=>$rs['declarevalue'],//价值(RMB)
		
		'list10'=>CompanySend('sendName'),//发件人公司	发件人地址	发件人电话	
		'list11'=>cadd($rs['ydh']),
		'list12'=>CompanySend('sendTel'),
		'list13'=>'',//收件人公司
		
		'list14'=>yundan_add_all($rs),
		'list15'=>cadd($rs['s_mobile_code']).'-'.cadd($rs['s_mobile']),
		'list16'=>cadd($rs['s_name']),
		'list17'=>'',
	);		
	
	//读取身份证文件
	require($_SERVER['DOCUMENT_ROOT'].'/xingao/yundan/excelExport/card_file.php'); 
	
}//while($rs=$sql->fetch_array())

if (!$excel_i)
{ 
	exit ("<script>alert('{$LG['NoDataExported']}');goBack('c');</script>");
} 
//正式导出---------------------------------------------------------
$enTable = array('list1','list2','list3','list4','list5','list6','list7','list8','list9','list10','list11','list12','list13','list14','list15','list16','list17');//保存内容
$cnTable=array(
	$LG['yundan.XexcelExport_4_1'],//序号 list1
	$LG['yundan.XexcelExport_4_2'],//分运单号码 list2
	$LG['yundan.XexcelExport_4_3'],//客户编号 list3
	$LG['yundan.XexcelExport_3_18'],//商品名称 list4
	$LG['yundan.XexcelExport_4_4'],//品牌 list5
	$LG['yundan.XexcelExport_4_5'],//规格/型号 list6
	$LG['yundan.XexcelExport_1_10'],//件数 list7
	$LG['yundan.XexcelExport_4_6'],//重量(KG) list8
	$LG['yundan.XexcelExport_4_7'],//价值(RMB) list9
	$LG['yundan.XexcelExport_4_8'],//发件人公司 list10
	$LG['yundan.XexcelExport_3_5'],//发件人地址 list11
	$LG['yundan.XexcelExport_4_9'],//发件人电话 list12
	$LG['yundan.XexcelExport_4_10'],//收件人公司 list13
	$LG['yundan.XexcelExport_3_9'],//收件人地址 list14
	$LG['yundan.XexcelExport_4_11'],//收件人电话 list15
	$LG['yundan.s_name'],//收件人姓名 list16
	$LG['yundan.XexcelExport_4_12'],//公司名 list17
);//列表名
$excel-> getExcel($data,$cnTable,$enTable,'other',20);
//20单元格宽度，如果设置为‘auto’ 表示自适应宽度，也可是具体的数字

$success=1;
?>
