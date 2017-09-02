<?php
require_once($_SERVER['DOCUMENT_ROOT'].'/public/PHPExcel/Export_call.php');

//PHPExcel配置-开始------------------------------------------------------------------------------------
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
		{exit($LG['headerError']);}
	
		// 创建一个处理对象实例   
		$objExcel = new PHPExcel();
		
	

		//------------------------------------多表处理-开始-----------------------------------
		//获取总数数组和通用数组
		global $table_name,$table_data;
		global $table_whCountry,$table_totalNumber,$table_totalWeight;
		
		$i=0;
		if($table_name)
		{
			foreach($table_name as $arrkey=>$value)
			{
						
						
						
						
						
						
						
				
				//内容处理-开始-----------------------------------------------------------------------
				
				$data=$table_data[$i];
				
				if($i)
				{
					//添加一个新的工作表
					$objExcel->createSheet();
				}
					
				//设置当前的sheet索引
				$objExcel->setActiveSheetIndex($i);   
			
				//单元格操作对象
				$objActSheet = $objExcel->getActiveSheet($i);
		
				//设置当前活动工作表的表名称
				$objActSheet->setTitle($value);
				
				
				//合并单元格  
				$objActSheet->mergeCells('A1:AC1'); 
				$objActSheet->mergeCells('A2:AC2'); 
					
		
				//设置行的高度
				$objExcel->getActiveSheet($i)->getRowDimension('1')->setRowHeight(30);
				$objExcel->getActiveSheet($i)->getRowDimension('2')->setRowHeight(30);
				
		
				//格式：主要用来对单元格进行操作，如，设置字体、设置对齐方式、设置边框等
				$objStyleA2 = $objActSheet->getStyle('A2');//获取单元格的样式
			
				//设置单元格的对齐方式  
				$objAlignA2 = $objStyleA2->getAlignment($i);//获得对齐方式
				$objAlignA2->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);//水平居中 
				$objAlignA2->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);//垂直居中
				
				//设置单元格的字体
				$objFontA2 = $objStyleA2->getFont($i); //获得字体
				//$objFontA2->setName('宋体');//设置字体名称 
				$objFontA2->setSize(16);  //设置字体大小
				$objFontA2->setBold(true);//设置字体加粗
				//$objFontA2->getColor($i)->setARGB('FF0000');//设置字体颜色
				
				//设置填充颜色   
				$objFillA2 = $objStyleA2->getFill($i);   
				$objFillA2->setFillType(PHPExcel_Style_Fill::FILL_SOLID);   
				$objFillA2->getStartColor($i)->setARGB('FFC0C0C0');
				
			
				//指定表头内容
				//第2行内容
				$objActSheet->setCellValue('A2', '烟台海关跨境电商 申报清单信息表'); 
		
				//第3行样式
				$objActSheet->mergeCells('X3:Z3'); 
				foreach($txArr as $key =>$value)
				{
					/*设置对齐方式*/
					$objActSheet->getStyle(numToEn($key)."3")->getAlignment($i)->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
					
					/*设置字体加粗*/
					$objActSheet->getStyle(numToEn($key)."3")->getFont($i)->setBold(true);
					$width == "auto"? $objActSheet->getColumnDimension(numToEn($key))->getAutoSize(true): $objActSheet->getColumnDimension(numToEn($key))->setWidth($width);
					
					/*设置设置填充颜色*/
					$objActSheet->getStyle(numToEn($key)."3")->getFill($i)->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor($i)->setARGB('FFDDDDDD');
				}
								
				
		
				$objActSheet->setCellValue('A3', '主运单号');
				$objActSheet->setCellValue('B3', ''); 
				$objActSheet->setCellValue('C3', '分单号'); 
				$objActSheet->setCellValue('D3', ''); 
				$objActSheet->setCellValue('E3', '航班号'); 
				$objActSheet->setCellValue('F3', ''); 
				$objActSheet->setCellValue('G3', '代理'); 
				$objActSheet->setCellValue('H3', '枫华贸易'); 
				$objActSheet->setCellValue('I3', ''); 
				$objActSheet->setCellValue('J3', ''); 
				$objActSheet->setCellValue('K3', '日期'); 
				$objActSheet->setCellValue('L3', DateYmd(time(),2)); 
				$objActSheet->setCellValue('M3', '总件数'); 
				$objActSheet->setCellValue('N3', $table_totalNumber[$i]); //总件数**
				$objActSheet->setCellValue('O3', '总毛重'); 
				$objActSheet->setCellValue('P3', $table_totalWeight[$i]); //总毛重**
				$objActSheet->setCellValue('Q3', '发货人国家'); 
				$objActSheet->setCellValue('R3', $table_whCountry[$i]);//国家代码
				$objActSheet->setCellValue('S3', '币制'); 
				$objActSheet->setCellValue('T3', '142'); //币制:代码跟国家代码一样,如人民币142 ,日币116,美元502
				$objActSheet->setCellValue('U3', '电商代码'); 
				$objActSheet->setCellValue('V3', '692031464'); 
				$objActSheet->setCellValue('W3', '电商名称'); 
				$objActSheet->setCellValue('X3', '烟台惠泽网络科技有限公司'); 
		
			
				/*设置表头宽度，将表头内容添加到excel文件里*/
				foreach($txArr as $key =>$value)
				{
					$objActSheet->setCellValue(numToEn($key)."4",$value);
					/*设置对齐方式*/
					$objActSheet->getStyle(numToEn($key)."4")->getAlignment($i)->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
					/*设置字体加粗*/
					//$objActSheet->getStyle(numToEn($key)."4")->getFont($i)->setBold(true);
					//$width == "auto"? $objActSheet->getColumnDimension(numToEn($key))->getAutoSize(true): $objActSheet->getColumnDimension(numToEn($key))->setWidth($width);
				}
				
				/*将数据添加到excel里*/
				if($data)
				{
					foreach($data as $key=>$value)
					{
						foreach($txArrEn as $k => $val)
						{
							$objActSheet->getStyle(numToEn($k).($key+5))->getNumberFormat($i)->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_TEXT);
							$objActSheet->setCellValue(numToEn($k).($key+5)," ".$value[$val]); //在写入Excels单元格的内容之前加一个空格，防止长数字被转化成科学计数法
							$objActSheet->getStyle(numToEn($k).($key+5))->getAlignment($i)->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
						}
					}
				}
				
				//内容处理-结束-----------------------------------------------------------------------------
				
				
				
				
				
				
				
				$i++;		
			}
		}
		//------------------------------------多表处理-结束-----------------------------------
		
		
		
		/*判断生成excel文件版本*/
		$objWriter ='';
		if($excelVersion == "other"){$objWriter = new PHPExcel_Writer_Excel5($objExcel);}
		elseif($excelVersion == "2007"){$objWriter = new PHPExcel_Writer_Excel2007($objExcel);}
		
		$objWriter->save($this->excelName);
		return $this->excelName;
	}

}
//PHPExcel配置-结束------------------------------------------------------------------------------------
?>


















<?php
/*
注意：
》生成时不能打开已生成的文件，无法报错。
》保存中文时必须转码，用 '中文内容部分')
*/

//查询导出--------------------------------------------------------------------------------
$table_name=array();
$table_whCountry=array();
$table_totalNumber=array();
$table_totalWeight=array();
$classid=0;
$table_i=0;//第X个工作表

$order='order by classid asc,ydh asc';//因为按classid做为分表,必须classid asc 为首
$query="select * from yundan where 1=1 {$where} ".whereCS()."   {$Xwh} {$order}";
$sql=$xingao->query($query);
while($rs=$sql->fetch_array())
{
	$excel_i++;

	//工作表基本资料-----------------------------
	if(!$classid){$classid=$rs['classid'];}
	
	//新表
	require($_SERVER['DOCUMENT_ROOT'].'/xingao/yundan/excelExport/tem/yantai_call.php');
	$serial++;//放到yantai_call.php后面
	

	$rs['declarevalue']=spr($rs['declarevalue']*exchange($XAScurrency,$XAMcurrency));//转主币种
	
	$fromtable='yundan'; $fromid=$rs['ydid'];
	$query_wp="select * from wupin where fromtable='{$fromtable}' and fromid='{$fromid}' order by wupin_name desc";
	$sql_wp=$xingao->query($query_wp);
	while($wp=$sql_wp->fetch_array())
	{
		$totalNumber+=$wp['wupin_number'];
		//$totalWeight+=$wp['wupin_weight'];
		$totalWeight+=$rs['weight'];
		$data[] = array(
			'list1'=>$serial,
			'list2'=>'',
			'list3'=>cadd($rs['ydh']),
			'list4'=>'',
			
			'list5'=>cadd($wp['wupin_name']),
			'list6'=>cadd($wp['wupin_brand']),
			'list7'=>cadd($wp['wupin_spec']),
			'list8'=>spr($wp['wupin_number']),
			'list9'=>is_numeric($wp['wupin_unit'])?classify($wp['wupin_unit'],2):cadd($wp['wupin_unit']),
			'list10'=>spr($wp['wupin_price']),
			'list11'=>spr($wp['wupin_price']*$wp['wupin_number']),//总值
			'list12'=>spr($wp['wupin_number']),
			'list13'=>spr($wp['wupin_weight']),
			
			'list14'=>spr($rs['weight']),//毛重
			
			'list15'=>cadd($rs['s_name']),
			'list16'=>cadd($rs['s_shenfenhaoma']),
			'list17'=>yundan_add_all($rs),
			'list18'=>cadd($rs['s_mobile']),
			'list19'=>'',
			'list20'=>'',
			'list21'=>'',
			'list22'=>'',
			'list23'=>'',
			'list24'=>'',
			'list25'=>'',
			'list26'=>'',
			'list27'=>'',
			'list28'=>'',
			'list29'=>'',
		);		
		
		//主单基本数据留空
		$serial='';
		$rs['ydh']='';
		$rs['s_name']='';
		$rs['s_add_quzhen']='';
		$rs['s_add_dizhi']='';
		$rs['s_add_chengshi']='';
		$rs['s_add_shengfen']='';
		$rs['s_mobile']='';
		$rs['declarevalue']='';
		$rs['weight']='';
	}
	//子表查询－结束
	
	
	//没物品数据时:下面的$data[]代码跟上面的$data[]完全一样
	if(!mysqli_num_rows($sql_wp))
	{
		unset($wp);
		$data[] = array(
			'list1'=>$serial,
			'list2'=>'',
			'list3'=>cadd($rs['ydh']),
			'list4'=>'',
			
			'list5'=>cadd($wp['wupin_name']),
			'list6'=>cadd($wp['wupin_brand']),
			'list7'=>cadd($wp['wupin_spec']),
			'list8'=>spr($wp['wupin_number']),
			'list9'=>is_numeric($wp['wupin_unit'])?classify($wp['wupin_unit'],2):cadd($wp['wupin_unit']),
			'list10'=>spr($wp['wupin_price']),
			'list11'=>spr($wp['wupin_price']*$wp['wupin_number']),//总值
			'list12'=>spr($wp['wupin_number']),
			'list13'=>spr($wp['wupin_weight']),
			
			'list14'=>spr($rs['weight']),//毛重
			
			'list15'=>cadd($rs['s_name']),
			'list16'=>cadd($rs['s_shenfenhaoma']),
			'list17'=>yundan_add_all($rs),
			'list18'=>cadd($rs['s_mobile']),
			'list19'=>'',
			'list20'=>'',
			'list21'=>'',
			'list22'=>'',
			'list23'=>'',
			'list24'=>'',
			'list25'=>'',
			'list26'=>'',
			'list27'=>'',
			'list28'=>'',
			'list29'=>'',
		);	
	}


	

	
	
	
	
	
	

	
	//读取身份证文件-----------------------------
	require($_SERVER['DOCUMENT_ROOT'].'/xingao/yundan/excelExport/card_file.php'); 
	
	
}//while($rs=$sql->fetch_array())


//新表-最后一个表
require($_SERVER['DOCUMENT_ROOT'].'/xingao/yundan/excelExport/tem/yantai_call.php');


//正式导出---------------------------------------------------------
$enTable = array('list1','list2','list3','list4','list5','list6','list7','list8','list9','list10','list11','list12','list13','list14','list15','list16','list17','list18','list19','list20','list21','list22','list23','list24','list25','list26','list27','list28','list29');//保存内容
$cnTable=array(
	'序号',//list1
	'货号条码',//list2
	'运单编号',//list3
	'行邮税号',//list4
	'商品名称',//list5
	'品牌',//list6
	'规格型号',//list7
	'数量',//list8
	'单位',//list9
	'单价',//list10
	'总值',//list11
	'件数',//list12
	'净重',//list13
	'毛重',//list14
	
	'收件人姓名',//list15
	'收件人证件号码',//list16
	'收件人地址',//list17
	'收件人电话',//list18
	'发货人名称',//list19
	'订单号',//list20
	'备注',//list21
	'发货人地址',//list22
	'商品描述',//list23
	'总运保费',//list24
	'支付编号',//list25
	'支付账号',//list26
	'支付时间',//list27
	'快递公司',//list28
	'快递单号',//list29
);//列表名



$excel->getExcel($data,$cnTable,$enTable,'other',15);
//20单元格宽度，如果设置为‘auto’ 表示自适应宽度，也可是具体的数字


if (!$excel_i)
{ 
	exit ("<script>alert('{$LG['NoDataExported']}');goBack('c');</script>");
} 

$success=1;
?>
