<?php
/*调用:
	<img src="/public/barcode/?number=内容123" />
	或
	<img src="/public/barcode/?number=内容123&codebar=BCGcode39&height=高度" />
	
	条码与数字分开显示方法:
	<font style="height:50px; width:500px; overflow:hidden; display:block;">
	<img src="/public/barcode/?number=<?=$number?>" />
	</font>
	<strong>(Y)<?=$number?></strong>
	
	---------------------------------------------------------------------
	codebar=指定条码类型 (空时默认为常用的BCGcode39),支持以下类型:
	
	支持的条码和对应的调用:如条码Codabar,调用为BCGcodabar
	
	<option value="BCGcodabar.php">Codabar</option>
	<option value="BCGcode11.php">Code 11</option>
	<option value="BCGcode39.php">Code 39</option>
	<option value="BCGcode39extended.php">Code 39 Extended</option>
	<option value="BCGcode93.php">Code 93</option>
	<option value="BCGcode128.php">Code 128</option>
	<option value="BCGean8.php">EAN-8</option>
	<option value="BCGean13.php">EAN-13</option>
	<option value="BCGgs1128.php">GS1-128 (EAN-128)</option>
	<option value="BCGisbn.php">ISBN</option>
	<option value="BCGi25.php">Interleaved 2 of 5</option>
	<option value="BCGs25.php">Standard 2 of 5</option>
	<option value="BCGmsi.php">MSI Plessey</option>
	<option value="BCGupca.php">UPC-A</option>
	<option value="BCGupce.php">UPC-E</option>
	<option value="BCGupcext2.php">UPC Extenstion 2 Digits</option>
	<option value="BCGupcext5.php">UPC Extenstion 5 Digits</option>
	<option value="BCGpostnet.php">Postnet</option>
	<option value="BCGintelligentmail.php">Intelligent Mail</option>
	<option value="BCGothercode.php" selected="selected">Other Barcode</option>
*/



//$_GET['codebar']='BCGi25';
require_once('class/BCGFontFile.php');
require_once('class/BCGColor.php');
require_once('class/BCGDrawing.php');

//显示错误
ini_set('display_errors','On');//on开启显示错误，off关闭
error_reporting(E_ALL ^ E_NOTICE^ E_WARNING);//显示错误级别：显示除去 E_NOTICE 之外的所有错误信息 

//获取处理
$height=(int)$_GET['height'];
if(!$height){$height=23;}
$codebar =preg_replace('/[^0-9a-zA-Z]+/','',$_GET['codebar']); //条形码类型
$number =$_GET['number']; //生成的条形码内容
if(!$codebar){$codebar='BCGcode128';}//默认条码类型







//程序开始
require_once('class/'.$codebar.'.barcode.php');

// Loading Font
$font = new BCGFontFile('./font/Arial.ttf', 18);

// The arguments are R, G, B for color.
$color_black = new BCGColor(0, 0, 0);
$color_white = new BCGColor(255, 255, 255);

$drawException = null;
try {
    $code = new $codebar();//实例化对应的编码格式
    $code->setScale(2); //分辨率
    $code->setThickness($height); // 高度
    $code->setForegroundColor($color_black); // Color of bars
    $code->setBackgroundColor($color_white); // Color of spaces
    $code->setFont($font); // Font (or 0)
    $code->parse($number);//条形码将要数据的内容
} catch(Exception $exception) {
    $drawException = $exception;
}

/* Here is the list of the arguments
- Filename (empty : display on screen)
- Background color */
$drawing = new BCGDrawing('', $color_white);
if($drawException) {
    $drawing->drawException($drawException);
} else {
    $drawing->setBarcode($code);
    $drawing->draw();
}

// Header that says it is an image (remove it if you save the barcode to a file)
header('Content-Type: image/png');
//header('Content-Disposition: inline; filename="barcode.png"');

// Draw (or save) the image into PNG format.
$drawing->finish(BCGDrawing::IMG_FORMAT_PNG);
?>