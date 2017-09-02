<?php
/*
	$data内容中支持一张图片
	内容格式:<XAimg>/images/claims.jpg
*/

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
		{exit($LG['headerError']);}
	
		// 创建一个处理对象实例   
		$objExcel = new PHPExcel();
		
		//设置当前的sheet索引，用于后续的内容操作。一般只有在使用多个sheet的时候才需要显示调用。缺省情况下，PHPExcel会自动创建第一个sheet被设置SheetIndex=0   
		$objExcel->setActiveSheetIndex(0);   
	
		//单元格操作对象
		$objActSheet = $objExcel->getActiveSheet();
			
			
			
		/*设置表头宽度，将表头内容添加到excel文件里*/
		foreach($txArr as $key =>$value)
		{
			$objActSheet->setCellValue(numToEn($key)."1",$value);
			/*设置对齐方式*/
			$objActSheet->getStyle(numToEn($key)."1")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);//水平居中 
			$objActSheet->getStyle(numToEn($key)."1")->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);//垂直居中
			 
			/*设置字体加粗*/
			$objActSheet->getStyle(numToEn($key)."1")->getFont()->setBold(true);
			$width == "auto"? $objActSheet->getColumnDimension(numToEn($key))->getAutoSize(true): $objActSheet->getColumnDimension(numToEn($key))->setWidth($width);
		}
		
		
		/*将内容添加到excel里*/
		foreach($data as $key=>$value)
		{
			foreach($txArrEn as $k => $val)
			{
				
				
				//导出图片---------------------------
				$imgAddress='';
				//图片存在时导出
				if(have($value[$val],'<XAimg>',0)&&HaveFile(str_ireplace('<XAimg>','',$value[$val])))
				{
					$imgAddress=str_ireplace('<XAimg>','',$value[$val]);
					
					$objDrawing = new PHPExcel_Worksheet_Drawing();//实例化插入图片类
					$objDrawing->setName('ZealImg');
					$objDrawing->setDescription('Image inserted by Zeal');
					$objDrawing->setPath($_SERVER["DOCUMENT_ROOT"].$imgAddress);//不能是外网图片
					$objDrawing->setCoordinates(numToEn($k).($key+2));//单元格,后面1是行数
					//$objDrawing->setWidth(100);//(单位pt)设置图片宽度(按比例显示,只设置一种即可)
					$objDrawing->setHeight(60);//(单位pt)设置图片高度(按比例显示,只设置一种即可)
					$objDrawing->setOffsetX(3);//图片偏移距离
					$objDrawing->setOffsetY(3);//图片偏移距离
					$objDrawing->setRotation(0);//设定旋转
					$objDrawing->getShadow()->setVisible(true);//是否启用阴影 (不起作用)
					$objDrawing->getShadow()->setDirection(36);//阴影:后面数字是方向
					$objDrawing->getShadow()->setDistance(20);//阴影:设置距离 
					$objDrawing->setWorksheet($objActSheet);
					
					$objActSheet->getColumnDimension(numToEn($k))->setWidth(20);//指定该列宽度
					$objExcel->getActiveSheet()->getRowDimension($key+2)->setRowHeight(50);//指定该行高度
					
				}else{
					//正常导出---------------------------
					if(have($value[$val],'<XAimg>',0)){$value[$val]='';}//图片不存在时清空该字段值

					/*设置对齐方式*/
					$objActSheet->getStyle(numToEn($k).($key+2))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);//水平居中 
					$objActSheet->getStyle(numToEn($k).($key+2))->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);//垂直居中
					
					$objActSheet->getStyle(numToEn($k).($key+2))->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_TEXT);
					$objActSheet->setCellValue(numToEn($k).($key+2)," ".$value[$val]);  //在写入Excels单元格的内容之前加一个空格，防止长数字被转化成科学计数法
					$objActSheet->getStyle(numToEn($k).($key+2))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
					
				}
				
				
				
				
			}
		}
		
		
		
		/*判断生成excel文件版本*/
		$objWriter = "";
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