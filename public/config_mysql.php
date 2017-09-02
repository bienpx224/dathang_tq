<?php
//-----------------------数据库设置(可修改:以下都必填)---------------------------------------------
$xa_config=array();
$xa_config['db']['server']		='127.0.0.1';			//数据库地址,本地用127.0.0.1不要用localhost
$xa_config['db']['name']		='ceshi';					//数据库名(function NextId 用到，变量修改时注意) 
$xa_config['db']['username']	='root';				//登录名
$xa_config['db']['password']	='';				//登录密码

$xa_config['db']['port']		='3306';				//端口
$xa_config['db']['timeout']		=30;					//设置超时时间（秒）
$sqlerrorshow					=1;						//显示SQL错误(0为不显示,1为显示)

//----------------------数据库链接,无需修改(以下全部勿乱修改)---------------------------------------
//链接数据库
$xingao=new mysqli($xa_config['db']['server'],$xa_config['db']['username'],$xa_config['db']['password'],$xa_config['db']['name'],$xa_config['db']['port']);

if(mysqli_connect_errno()){
   echo '数据库连接出错，错误信息：';
   if($sqlerrorshow)
   {
       echo mysqli_connect_error();
   }else{
	   echo "未开启显示错误信息";
   }
}

$xingao->select_db('xingao');
$xingao->set_charset('utf8');//编码
$xingao->options(MYSQLI_OPT_CONNECT_TIMEOUT,$xa_config['db']['timeout']);//设置超时时间（秒）
?>