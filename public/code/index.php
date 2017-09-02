<?php
require($_SERVER['DOCUMENT_ROOT'].'/public/config_top.php');
require($_SERVER['DOCUMENT_ROOT'].'/public/function.php');

/*
	如不能显示,检查function.php里调用的文档 在?>前面或后面不能有空格或回车
	如:/cache/config_top.php  
	/cache/config.php
	/Language/index.php
	/Language/CN.php
	
	
	测试
	/public/code/?v=login
	<img src="/public/code/?v=login&amp;rm=0.03805404994636774" onclick="codeimg.src='/public/code/?v=login&amp;rm='+Math.random()" id="codeimg" title="看不清，点击换一张(不分大小写)" width="100" height="35">
	
	exit("== {$_SESSION['checkloginkey']}");
*/

//验证码类
class ValidateCode {
 private $charset = 'abcdefghkmnprstuvwxyzABCDEFGHKMNPRSTUVWXYZ23456789';//随机因子
 private $code;//验证码
 private $codelen = 5;//验证码长度(不能小于4位)
 private $width = 100;//宽度
 private $height = 35;//高度
 private $img;//图形资源句柄
 private $font;//指定的字体
 private $fontsize = 18;//指定字体大小
 private $fontcolor;//指定字体颜色
 //构造方法初始化
 public function __construct() {
  $this->font = $_SERVER['DOCUMENT_ROOT'].'/public/font/code4.ttf';//注意字体路径要写对，否则显示不了图片
 }
 //生成随机码
 private function createCode() {
  $_len = strlen($this->charset)-1;
  for ($i=0;$i<$this->codelen;$i++) {
   $this->code .= $this->charset[mt_rand(0,$_len)];
  }
 }
 //生成背景
 private function createBg() {
  $this->img = imagecreatetruecolor($this->width, $this->height);
  $color = imagecolorallocate($this->img, mt_rand(157,255), mt_rand(157,255), mt_rand(157,255));
  imagefilledrectangle($this->img,0,$this->height,$this->width,0,$color);
 }
 //生成文字
 private function createFont() {
  $_x = $this->width / $this->codelen;
  for ($i=0;$i<$this->codelen;$i++) {
   $this->fontcolor = imagecolorallocate($this->img,mt_rand(0,156),mt_rand(0,156),mt_rand(0,156));
   imagettftext($this->img,$this->fontsize,mt_rand(-30,30),$_x*$i+mt_rand(1,5),$this->height / 1.4,$this->fontcolor,$this->font,$this->code[$i]);
  }
 }
 //生成线条、雪花
 private function createLine() {
  //线条
  for ($i=0;$i<1;$i++) {
   $color = imagecolorallocate($this->img,mt_rand(0,156),mt_rand(0,156),mt_rand(0,156));
   imageline($this->img,mt_rand(0,$this->width),mt_rand(0,$this->height),mt_rand(0,$this->width),mt_rand(0,$this->height),$color);
  }
  //雪花
  for ($i=0;$i<10;$i++) {
   $color = imagecolorallocate($this->img,mt_rand(200,255),mt_rand(200,255),mt_rand(200,255));
   imagestring($this->img,mt_rand(1,5),mt_rand(0,$this->width),mt_rand(0,$this->height),'*',$color);
  }
 }
 //输出
 private function outPut() {
  header('Content-type:image/png');
  imagepng($this->img);
  imagedestroy($this->img);
 }
 //对外生成
 public function doimg() {
  $this->createBg();
  $this->createCode();
  $this->createLine();
  $this->createFont();
  $this->outPut();
 }

 //获取验证码
 public function getCode() {
  return strtolower($this->code);
 }
 
}

$v=par($_GET['v']);
$_vc = new ValidateCode();  //实例化一个对象
$_vc->doimg();  
$vname=xaReturnKeyVarname($v);
$key= $_vc->getCode();
$key= strtolower($key);
$_SESSION[$vname] =$key;//验证码保存到SESSION中
?>