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
		
	

		//------------------------------------托盘表导出-开始-----------------------------------
		//获取总数数组和通用数组
		global $table_name,$table_data;
		global $table_bclassidName,$table_totalNumber,$table_totalWeight,$table_totalPrice;
		
		$i=0;
		if($table_name)
		{
			foreach($table_name as $arrkey=>$value)
			{
						
						
						
						
						
						
						
				
				//------内容处理-开始------
				
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
				
				
				//工作表顶部模板--开始=====================================================
				$si=0;
				
				$si++;//顶部的第1行-----
				$lt='A';//列名
				$objActSheet->mergeCells($lt.$si.':B'.$si); //合并单元格  
				$objActSheet->getStyle($lt.$si)->getAlignment($si)->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);//水平居右
				$objActSheet->getStyle($lt.$si)->getFont($si)->setBold(true);//设置字体加粗
				$objActSheet->setCellValue($lt.$si, 'INVOICE & PACKING LIST');//写入内容
				
				$lt='C';//列名
				$objActSheet->mergeCells($lt.$si.':D'.$si); //合并单元格  
				$objActSheet->getStyle($lt.$si)->getAlignment($si)->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);//水平居右
				$objActSheet->setCellValue($lt.$si, '運送状番号(AWB):');//写入内容
				
				$lt='E';//列名
				$objActSheet->mergeCells($lt.$si.':F'.$si); //合并单元格  
				$objActSheet->getStyle($lt.$si)->getFont($si)->setBold(true);//设置字体加粗
				$objActSheet->setCellValue($lt.$si, $table_bclassidName[$i]);//航空提单号(二级分类)

				$si++;//顶部的第2行-----
				$lt='A';//列名
				$objActSheet->mergeCells($lt.$si.':B'.$si); //合并单元格  
				$objActSheet->getStyle($lt.$si)->getAlignment($si)->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);//水平居左
				$objActSheet->setCellValue($lt.$si, 'From shipper：Japan');//写入内容

				$lt='C';//列名
				$objActSheet->mergeCells($lt.$si.':F'.$si); //合并单元格  
				$objActSheet->getStyle($lt.$si)->getAlignment($si)->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);//水平居左
				$objActSheet->setCellValue($lt.$si, 'MR.GONG');//国内清关公司的地址信息

				$si++;//顶部的第3行-----
				$lt='A';//列名
				$objActSheet->mergeCells($lt.$si.':B'.$si); //合并单元格  
				$objActSheet->getStyle($lt.$si)->getAlignment($si)->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);//水平居左
				$objActSheet->setCellValue($lt.$si, 'Company Name:'.CompanySend('sendName'));//写入内容

				$lt='C';//列名
				$objActSheet->mergeCells($lt.$si.':F'.$si); //合并单元格  
				$objActSheet->getStyle($lt.$si)->getAlignment($si)->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);//水平居左
				$objActSheet->setCellValue($lt.$si, 'Company Name:SHANDONG LANGYUE LOGISTICS CO,. LTD.');//国内清关公司的地址信息

				$si++;//顶部的第4行-----
				
				$lt='A';//列名
				$objActSheet->mergeCells($lt.$si.':B'.$si); //合并单元格  
				$objActSheet->getStyle($lt.$si)->getAlignment($si)->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);//水平居左
				$objActSheet->getStyle($lt.$si)->getAlignment($si)->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);//垂直居中
				$objExcel->getActiveSheet($i)->getRowDimension($si)->setRowHeight(30);//设置行的高度
				$objActSheet->setCellValue($lt.$si, 'Address:'.CompanySend('sendAdd'));//写入内容

				$lt='C';//列名
				$objActSheet->mergeCells($lt.$si.':F'.$si); //合并单元格  
				$objActSheet->getStyle($lt.$si)->getAlignment($si)->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);//水平居左
				$objActSheet->getStyle($lt.$si)->getAlignment($si)->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);//垂直居中
				$objActSheet->setCellValue($lt.$si, 'Address:-1, BINGLUN ROAD, ZHIFU, YANTAI, CHINA');//国内清关公司的地址信息

				$si++;//顶部的第5行-----
				$lt='A';//列名
				$objActSheet->mergeCells($lt.$si.':B'.$si); //合并单元格  
				$objActSheet->getStyle($lt.$si)->getAlignment($si)->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);//水平居左
				$objActSheet->setCellValue($lt.$si, 'Tel NO.:'.CompanySend('sendTel'));//写入内容

				$lt='C';//列名
				$objActSheet->mergeCells($lt.$si.':F'.$si); //合并单元格  
				$objActSheet->getStyle($lt.$si)->getAlignment($si)->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);//水平居左
				$objActSheet->setCellValue($lt.$si, 'Tel: +86-535-6985129  18660092977');//国内清关公司的地址信息

				$si++;//顶部的第6行-----
				$lt='A';//列名
				$objActSheet->mergeCells($lt.$si.':B'.($si+1)); //合并单元格  
				$objActSheet->getStyle($lt.$si)->getAlignment($si)->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);//水平居中
				$objActSheet->getStyle($lt.$si)->getAlignment($si)->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);//垂直居中
				$objActSheet->getStyle($lt.$si)->getFont($si)->setSize(18);//设置字体大小
				$objActSheet->getStyle($lt.$si)->getFont($si)->setBold(true);//设置字体加粗
				$objActSheet->setCellValue($lt.$si, 'BOX NO.: '.$value);//写入内容

				$lt='C';//列名
				$objActSheet->mergeCells($lt.$si.':D'.$si); //合并单元格  
				$objActSheet->getStyle($lt.$si)->getAlignment($si)->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);//水平居中
				$objActSheet->setCellValue($lt.$si, 'Gross Weight（KGS）');//写入内容

				$lt='E';//列名
				$objActSheet->mergeCells($lt.$si.':F'.$si); //合并单元格  
				$objActSheet->getStyle($lt.$si)->getAlignment($si)->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);//水平居中
				$objActSheet->getStyle($lt.$si)->getFont($si)->setBold(true);//设置字体加粗
				$objActSheet->setCellValue($lt.$si, $table_totalWeight[$i]);//托盘总重量

				$si++;//顶部的第7行-----
				$lt='C';//列名
				$objActSheet->mergeCells($lt.$si.':D'.$si); //合并单元格  
				$objActSheet->getStyle($lt.$si)->getAlignment($si)->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);//水平居中
				$objActSheet->setCellValue($lt.$si, 'Dimention（L*W*H CM)');//写入内容

				$lt='E';//列名
				$objActSheet->mergeCells($lt.$si.':F'.$si); //合并单元格  
				$objActSheet->getStyle($lt.$si)->getAlignment($si)->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);//水平居中
				$objActSheet->getStyle($lt.$si)->getFont($si)->setBold(true);//设置字体加粗
				$objActSheet->setCellValue($lt.$si, '量过后请手工填写');//托盘尺寸-量过之后再手工填写

				//工作表顶部模板--结束=====================================================
				
				
				
				
				
				
				
				
				
		
		
		
		
				$si++;//顶部的第8行-----
				/*设置表头宽度，将表头内容添加到excel文件里*/
				$width_old=$width;
				foreach($txArr as $key =>$value)
				{
					$objActSheet->setCellValue(numToEn($key).$si,$value);
					/*设置对齐方式*/
					$objActSheet->getStyle(numToEn($key).$si)->getAlignment($i)->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
					/*设置字体加粗*/
					//$objActSheet->getStyle(numToEn($key).$si)->getFont($i)->setBold(true);
					
					/*设置设置填充颜色*/
					$objActSheet->getStyle(numToEn($key).$si)->getFill($i)->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor($i)->setARGB('FFDDDDDD');

					//设置宽度
					$width=$width_old;
					if(numToEn($key)=='A'){$width=25;}
					if(numToEn($key)=='B'){$width=45;}
					
					$width == "auto"? $objActSheet->getColumnDimension(numToEn($key))->getAutoSize(true): $objActSheet->getColumnDimension(numToEn($key))->setWidth($width);
					
				}
				
				/*将数据添加到excel里*/
				$si++;//顶部的第9行-----
				if($data)
				{
					foreach($data as $key=>$value)
					{
						foreach($txArrEn as $k => $val)
						{
							$objActSheet->getStyle(numToEn($k).($key+$si))->getNumberFormat($i)->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_TEXT);
							$objActSheet->setCellValue(numToEn($k).($key+$si)," ".$value[$val]); //在写入Excels单元格的内容之前加一个空格，防止长数字被转化成科学计数法
							$objActSheet->getStyle(numToEn($k).($key+$si))->getAlignment($i)->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
						}
					}
				}
				
				
				
				
				
				//工作表底部模板--开始=====================================================
				$bi=$key+$si;//$key在上面赋值,因此这部分必须放后面
				
				$bi++;//底部的第1行-----
				$lt='A';//列名
				$objActSheet->mergeCells($lt.$bi.':E'.$bi); //合并单元格  
				$objActSheet->getStyle($lt.$bi)->getAlignment($bi)->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);//水平居右
				$objActSheet->getStyle($lt.$bi)->getFill($bi)->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor($bi)->setARGB('FFDDDDDD');//设置设置填充颜色
				$objActSheet->getStyle($lt.$bi)->getFont($bi)->setBold(true);//设置字体加粗
				$objActSheet->setCellValue($lt.$bi, 'Total Declared Value（JPY）');//写入内容

				$lt='F';//列名
				$objActSheet->getStyle($lt.$bi)->getAlignment($bi)->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);//水平居右
				$objActSheet->getStyle($lt.$bi)->getFill($bi)->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor($bi)->setARGB('FFDDDDDD');//设置设置填充颜色
				$objActSheet->getStyle($lt.$bi)->getFont($bi)->setBold(true);//设置字体加粗
				$objActSheet->setCellValue($lt.$bi, $table_totalPrice[$i]);//货物总价值
				
				
				$bi++;//底部的第2行-----
				$lt='A';//列名
				$objActSheet->mergeCells($lt.$bi.':B'.$bi); //合并单元格  
				$objActSheet->getStyle($lt.$bi)->getAlignment($bi)->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);//水平居左
				$objActSheet->getStyle($lt.$bi)->getFont($bi)->setBold(true);//设置字体加粗
				$objActSheet->setCellValue($lt.$bi, 'Country of Origin：JAPAN');//写入内容
				
				//底部的H列的第2行
				$lt='C';
				$objActSheet->mergeCells($lt.$bi.':F'.$bi); //合并单元格  
				$objActSheet->getStyle($lt.$bi)->getAlignment($bi)->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);//水平居左
				$objActSheet->getStyle($lt.$bi)->getFont($bi)->setBold(true);//设置字体加粗
				$objActSheet->setCellValue($lt.$bi, 'Reason for Sending：Non-business');//写入内容
				
				$bi++;//底部的第3行-----
				$lt='A';//列名
				$objActSheet->mergeCells($lt.$bi.':F'.$bi); //合并单元格  
				$objActSheet->getStyle($lt.$bi)->getAlignment($bi)->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);//水平居左
				$objActSheet->getStyle($lt.$bi)->getAlignment($bi)->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);//垂直居中
				$objExcel->getActiveSheet($i)->getRowDimension($bi)->setRowHeight(30);//设置行的高度
				$objActSheet->setCellValue($lt.$bi, 'I hereby certify the information on this declaration is true and correct; and the contents of this shipments　are as stated above.');//写入内容

				
				$bi++;//底部的第4行-----
				$lt='A';//列名
				$objActSheet->mergeCells($lt.$bi.':B'.$bi); //合并单元格  
				$objActSheet->setCellValue($lt.$bi, 'SIGNATURE：'.CompanySend('sendName'));//写入内容
				
				$lt='E';//列名
				$objActSheet->setCellValue($lt.$bi, 'DATE:');//写入内容
				
				$lt='F';//列名
				$objActSheet->setCellValue($lt.$bi, DateYmd(time(),2));//写入内容

				//工作表底部模板--结束=====================================================



				//------内容处理-结束------
				
				
				
				
				
				
				
				$i++;		
			}
		}
		//------------------------------------托盘表导出-结束-----------------------------------
		
		
		
		
		
		
		
		
		
		//------------------------------------Jan＆成分表导出-开始-----------------------------------
		global $gd_enTable,$gd_cnTable,$gd_data,$gd_table_name;
		
		$data=$gd_data;
		//$i=$i+1;
		$txArr=$gd_cnTable;
		$txArrEn=$gd_enTable;
		
		
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
		$objActSheet->setTitle($gd_table_name);
	
		/*设置表头宽度，将表头内容添加到excel文件里*/
		foreach($txArr as $key =>$value)
		{
			$objActSheet->setCellValue(numToEn($key)."1",$value);
			/*设置对齐方式*/
			$objActSheet->getStyle(numToEn($key)."1")->getAlignment($i)->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			/*设置字体加粗*/
			$objActSheet->getStyle(numToEn($key)."1")->getFont($i)->setBold(true);
			$width == "auto"? $objActSheet->getColumnDimension(numToEn($key))->getAutoSize(true): $objActSheet->getColumnDimension(numToEn($key))->setWidth($width);
		}
		
		/*将数据添加到excel里*/
		if($data)
		{
			foreach($data as $key=>$value)
			{
				foreach($txArrEn as $k => $val)
				{
					$objActSheet->getStyle(numToEn($k).($key+2))->getNumberFormat($i)->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_TEXT);
					$objActSheet->setCellValue(numToEn($k).($key+2)," ".$value[$val]); //在写入Excels单元格的内容之前加一个空格，防止长数字被转化成科学计数法
					$objActSheet->getStyle(numToEn($k).($key+2))->getAlignment($i)->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
				}
			}
		}
		
		//------------------------------------Jan＆成分表导出-结束-----------------------------------







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
$table_bclassidName=array();
$table_totalNumber=array();
$table_totalWeight=array();
$table_totalPrice=array();

$classid=0;
$table_i=0;//第X个工作表
$goodsdata='';

//托盘数据-查询开始--------------------------------------------------------
$order='order by classid asc,ydh asc';//因为按classid做为分表,必须classid asc 为首
$query="select * from yundan where 1=1 {$where} ".whereCS()."   {$Xwh} {$order}";
$sql=$xingao->query($query);
while($rs=$sql->fetch_array())
{
	$excel_i++;
	$first=0;
	
	//清关资料资料
	$goodsdata=editContent($goodsdata,cadd($rs['goodsdata']),1,':::','|||');//删除重复商品ID,累加商品数量


	
	//工作表基本资料
	if(!$classid){$classid=$rs['classid'];}
	
	//新工作表
	require($_SERVER['DOCUMENT_ROOT'].'/xingao/yundan/excelExport/tem/gd_japan_call.php');
	$serial++;//放到gd_japan_call.php后面
	
	
	//运单总计计算
	$totalWeight+=$rs['weight'];


	//子表查询－开始
	$gdid='';
	if($rs['goodsdata'])
	{
		$gdid=yundan_goodsdata(cadd($rs['goodsdata']),'',1);//获取全部ID
		$goodsdata_number=arrcount($gdid);
	}

	if($gdid)
	{
		$query_gd="select * from gd_japan where gdid in ({$gdid}) order by find_in_set(gdid,'{$gdid}')";
		$sql_gd=$xingao->query($query_gd);
		while($gd=$sql_gd->fetch_array())
		{
			
			$totalNumber+=spr($gdid_data[1]);
			$totalPrice+=($gdid_data[1]*$gd['price']);
	
			$gdid_data=yundan_goodsdata($rs['goodsdata'],$gd['gdid'],2);//获取资料,返回数组
			$data[] = array(
				'list1'=>cadd($rs['ydh']),
				'list2'=>cadd($gd['nameEN']),
				'list3'=>cadd($gd['barcode']),
				'list4'=>spr($gdid_data[1]),
				'list5'=>spr($gd['price']),
				'list6'=>spr($gdid_data[1]*$gd['price']),
			);	
			
			//主单基本数据留空
			$serial='';
			$rs['ydh']='';
		}
		unset($gd);
	}else{
		//没物品数据时:下面的$data[]代码跟上面的$data[]完全一样
		unset($gd);	unset($gdid_data);
		$data[] = array(
			'list1'=>cadd($rs['ydh']),
			'list2'=>cadd($gd['nameEN']),
			'list3'=>cadd($gd['barcode']),
			'list4'=>spr($gdid_data[1]),
			'list5'=>spr($gd['price']),
			'list6'=>spr($gdid_data[1]*$gd['price']),
		);	
	}
	//子表查询－结束
	
	
	

	
	//读取身份证文件
	require($_SERVER['DOCUMENT_ROOT'].'/xingao/yundan/excelExport/card_file.php'); 
	
	
}//while($rs=$sql->fetch_array())


//新工作表-最后一个表
require($_SERVER['DOCUMENT_ROOT'].'/xingao/yundan/excelExport/tem/gd_japan_call.php');




$enTable = array('list1','list2','list3','list4','list5','list6');//保存内容
$cnTable=array(
	'Bags',//list1
	'Description',//list2
	'Jan Code',//list3
	'Quantity',//list4
	'UNIT PRICE (JPY)',//list5
	'TOTAL PRICE (JPY)',//list6
);//列表名
//托盘数据-查询结束--------------------------------------------------------












//Jan＆成分-查询开始----------------------------------------------------------------------------------
$gdid='';
$goodsdata=DelStr($goodsdata,'|||',0);
if($goodsdata)
{
	$gdid=yundan_goodsdata($goodsdata,'',1);//获取全部ID
	//$goodsdata_number=arrcount($gdid);
}

//查询-开始
if($gdid)
{
	$query_gd="select * from gd_japan where gdid in ({$gdid}) order by find_in_set(gdid,'{$gdid}')";
	$sql_gd=$xingao->query($query_gd);
	while($gd=$sql_gd->fetch_array())
	{
		$gdid_data=yundan_goodsdata($goodsdata,$gd['gdid'],2);//获取资料,返回数组
		
		$img='';
		if($gd['img'])
		{
			$img=$siteurl.'/public/ShowImg.php?img='.urlencode(cadd($gd['img']));//有防盗连功能,必须用这种链接
		}
		
		$gd_data[] = array(
			'list1'=>cadd($gd['nameEN']),
			'list2'=>cadd($gd['barcode']),
			'list3'=>cadd($gdid_data[1]),
			'list4'=>spr($gd['price']),
			'list5'=>spr($gdid_data[1]*$gd['price']),
			'list6'=>cadd($gd['nameJP']),
			'list7'=>cadd($gd['composition']),
			'list8'=>$img,
			'list9'=>cadd($gd['imgurl']),
		);	
	}
}
//查询－结束

$gd_enTable = array('list1','list2','list3','list4','list5','list6','list7','list8','list9');//保存内容
$gd_cnTable=array(
	'英文名称',//list1
	'JanCode',//list2
	'数量',//list3
	'单价('.$XAsc.')',//list4
	'合计价格('.$XAsc.')',//list5
	'日文名称',//list6
	'成分',//list7
	'图片网址',//list8
	'商品网址'//list9
);//列表名

$gd_table_name='Jan＆成分';//表名

//Jan＆成分-查询结束----------------------------------------------------------------------------------








$excel->getExcel($data,$cnTable,$enTable,'other',15);

if (!$excel_i)
{ 
	exit ("<script>alert('{$LG['NoDataExported']}');goBack('c');</script>");
} 
$success=1;
?>
