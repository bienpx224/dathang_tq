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
		if(count($txArr) != count($txArrEn) && count($txArrEn) != count($data['0']) && !empty($data))
		{exit($LG['yundan.XexcelExport_3_1']);}
	
		// 创建一个处理对象实例   
		$objExcel = new PHPExcel();
		
		//设置当前的sheet索引，用于后续的内容操作。一般只有在使用多个sheet的时候才需要显示调用。缺省情况下，PHPExcel会自动创建第一个sheet被设置SheetIndex=0   
		$objExcel->setActiveSheetIndex(0);   
	
		//单元格操作对象
		$objActSheet = $objExcel->getActiveSheet();
		
	
	
	
	
	
		//------------------------------------样式,参数设置-开始-----------------------------------
	
		
		//设置行的高度
		$objExcel->getActiveSheet()->getRowDimension('1')->setRowHeight(40);
	
		//格式：主要用来对单元格进行操作，如，设置字体、设置对齐方式、设置边框等
		$objStyleA1 = $objActSheet->getStyle('1');//获取A5单元格的样式
	
		//设置单元格的对齐方式  
		$objAlignA1 = $objStyleA1->getAlignment();//获得对齐方式
		$objAlignA1->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);//水平居中 
		$objAlignA1->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);//垂直居中
		
	
		//指定表头内容
		$objActSheet->setCellValue('A1', 'Package ID'); //写入内容
		$objActSheet->setCellValue('B1', 'USZCN Code'); //写入内容
		$objActSheet->setCellValue('C1', 'Sender Name'); //写入内容
		$objActSheet->setCellValue('D1', 'Sender Address'); //写入内容
		$objActSheet->setCellValue('E1', 'Sender City'); //写入内容
		$objActSheet->setCellValue('F1', 'Sender Province'); //写入内容
		$objActSheet->setCellValue('G1', 'Sender Postal Code'); //写入内容
		$objActSheet->setCellValue('H1', 'Sender Country'); //写入内容
		$objActSheet->setCellValue('I1', 'Sender Phone Number'); //写入内容
		$objActSheet->setCellValue('J1', 'Recipient Name'); //写入内容
		$objActSheet->setCellValue('K1', 'Recipient Address'); //写入内容
		$objActSheet->setCellValue('L1', 'Recipient City'); //写入内容
		$objActSheet->setCellValue('M1', 'Recipient Province'); //写入内容
		$objActSheet->setCellValue('N1', 'Recipient Postal Code'); //写入内容
		$objActSheet->setCellValue('O1', 'Recipient Country'); //写入内容
		$objActSheet->setCellValue('P1', 'Recipient Phone Number'); //写入内容
		$objActSheet->setCellValue('Q1', 'Recipient Email'); //写入内容
		$objActSheet->setCellValue('R1', 'Reclplent ID'); //写入内容
		$objActSheet->setCellValue('S1', 'Package Weight(KG)'); //写入内容
		$objActSheet->setCellValue('T1', 'Package Total Price'); //写入内容
		$objActSheet->setCellValue('U1', 'Package Total Price Currency'); //写入内容
		$objActSheet->setCellValue('V1', 'Service Type'); //写入内容
		$objActSheet->setCellValue('W1', 'Item UPC'); //写入内容
		$objActSheet->setCellValue('X1', 'Item Name'); //写入内容
		$objActSheet->setCellValue('Y1', 'Item Brand'); //写入内容
		$objActSheet->setCellValue('Z1', 'Item Model'); //写入内容
		$objActSheet->setCellValue('AA1', 'Item Description'); //写入内容
		$objActSheet->setCellValue('AB1', 'Unit Price'); //写入内容
		$objActSheet->setCellValue('AC1', 'Quantity'); //写入内容
		$objActSheet->setCellValue('AD1', 'Package Remark'); //写入内容
	
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
		
		
		//------------------------------------样式,参数设置-结束-----------------------------------
		
		
		
		
		
		
	
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

//查询导出--------------------------------------------------------------------------------
$query="select * from yundan where 1=1 {$where} ".whereCS()."   {$Xwh} {$order}";
$sql=$xingao->query($query);
while($rs=$sql->fetch_array())
{
	$excel_i+=1;
	$rs['declarevalue']=spr($rs['declarevalue']*exchange($XAScurrency,$XAMcurrency));//转主币种
	
	$fromtable='yundan'; $fromid=$rs['ydid'];
	$query_wp="select * from wupin where fromtable='{$fromtable}' and fromid='{$fromid}' order by wupin_name desc";
	$sql_wp=$xingao->query($query_wp);
	while($wp=$sql_wp->fetch_array())
	{
		$data[] = array(
			'list1'=>cadd($rs['ydh']),
			'list2'=>'',
			'list3'=>'',
			'list4'=>'',
			'list5'=>'',
			'list6'=>'',
			'list7'=>'',
			'list8'=>'',
			'list9'=>'',
			
			'list10'=>cadd($rs['s_name']),
			'list11'=>cadd($rs['s_add_quzhen']).cadd($rs['s_add_dizhi']),
			'list12'=>cadd($rs['s_add_chengshi']),
			'list13'=>cadd($rs['s_add_shengfen']),
			
			'list14'=>'',
			'list15'=>'',
			'list16'=>cadd($rs['s_mobile']),
			'list18'=>'',
			'list19'=>'',
			'list20'=>$rs['declarevalue'],
			'list21'=>'',
			'list22'=>'',
			'list23'=>'',
			'list24'=>'',
			'list25'=>cadd($wp['wupin_brand']),
			'list26'=>'',
			'list27'=>cadd($wp['wupin_name']),
			'list28'=>cadd($wp['wupin_price']),
			'list29'=>cadd($wp['wupin_number']),
			'list30'=>'',
		);		
		
		//主单基本数据留空
		$rs['ydh']='';
		$rs['s_name']='';
		$rs['s_add_quzhen']='';
		$rs['s_add_dizhi']='';
		$rs['s_add_chengshi']='';
		$rs['s_add_shengfen']='';
		$rs['s_mobile']='';
		$rs['declarevalue']='';
	}
	//子表查询－结束
	
	//没物品数据时:下面的$data[]代码跟上面的$data[]完全一样
	if(!mysqli_num_rows($sql_wp))
	{
		unset($wp);
		$data[] = array(
			'list1'=>cadd($rs['ydh']),
			'list2'=>'',
			'list3'=>'',
			'list4'=>'',
			'list5'=>'',
			'list6'=>'',
			'list7'=>'',
			'list8'=>'',
			'list9'=>'',
			
			'list10'=>cadd($rs['s_name']),
			'list11'=>cadd($rs['s_add_quzhen']).cadd($rs['s_add_dizhi']),
			'list12'=>cadd($rs['s_add_chengshi']),
			'list13'=>cadd($rs['s_add_shengfen']),
			
			'list14'=>'',
			'list15'=>'',
			'list16'=>cadd($rs['s_mobile']),
			'list18'=>'',
			'list19'=>'',
			'list20'=>$rs['declarevalue'],
			'list21'=>'',
			'list22'=>'',
			'list23'=>'',
			'list24'=>'',
			'list25'=>cadd($wp['wupin_brand']),
			'list26'=>'',
			'list27'=>cadd($wp['wupin_name']),
			'list28'=>cadd($wp['wupin_price']),
			'list29'=>cadd($wp['wupin_number']),
			'list30'=>'',
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
$enTable = array('list1','list2','list3','list4','list5','list6','list7','list8','list9','list10','list11','list12','list13','list14','list15','list16','list17','list18','list19','list20','list21','list22','list23','list24','list25','list26','list27','list28','list29','list30');//保存内容
$cnTable=array(
	$LG['yundan.XexcelExport_3_2'],//客户发货单号 list1
	$LG['yundan.XexcelExport_3_3'],//本地发货单号 list2
	$LG['yundan.XexcelExport_3_4'],//发件人名字 list3
	$LG['yundan.XexcelExport_3_5'],//发件人地址 list4
	$LG['yundan.f_add_chengshi'],//发件人城市 list5
	$LG['yundan.f_add_shengfen'],//发件人省份 list6
	$LG['yundan.f_zip'],//发件人邮编 list7
	$LG['yundan.XexcelExport_3_6'],//发件人国家 list8
	$LG['yundan.XexcelExport_3_7'],//发件人手机 list9
	$LG['yundan.XexcelExport_3_8'],//收件人名字 list10
	$LG['yundan.XexcelExport_3_9'],//收件人地址 list11
	$LG['yundan.s_add_chengshi'],//收件人城市 list12
	$LG['yundan.s_add_shengfen'],//收件人省份 list13
	$LG['yundan.s_zip'],//收件人邮编 list14
	$LG['yundan.XexcelExport_3_10'],//收件人国家 list15
	$LG['yundan.XexcelExport_3_11'],//收件人手机 list16
	$LG['yundan.XexcelExport_3_12'],//收件人邮箱 list17
	
	$LG['yundan.XexcelExport_1_8'],//收件人身份证号码 list18
	$LG['yundan.XexcelExport_3_13'],//包裹重量 list19
	$LG['yundan.XexcelExport_3_14'],//包裹总价 list20
	$LG['yundan.XexcelExport_3_15'],//价钱币种(默认库房当地币种) list21
	$LG['yundan.XexcelExport_3_16'],//服务类型 list22
	$LG['yundan.XexcelExport_3_17'],//商品UPC码 list23
	$LG['yundan.XexcelExport_3_18'],//商品名称 list24
	$LG['yundan.XexcelExport_3_19'],//商品品牌 list25
	$LG['yundan.XexcelExport_3_20'],//商品类型 list26
	$LG['yundan.XexcelExport_3_21'],//商品描述 list27
	$LG['yundan.XexcelExport_3_22'],//商品单价 list28
	$LG['yundan.XexcelExport_3_23'],//商品数量 list29
	$LG['yundan.XexcelExport_3_24'],//包裹备注 list30
);//列表名
$excel-> getExcel($data,$cnTable,$enTable,'other',20);
//20单元格宽度，如果设置为‘auto’ 表示自适应宽度，也可是具体的数字

$success=1;
?>
