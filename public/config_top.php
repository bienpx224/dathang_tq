<?php
/*
	此文件是第一位载入,无法使用语种 
*/

//---------------------------------------------运行环境配置(勿乱修改)------------------------------------------
@session_start();//加此行时,火狐浏览器window.history.go(-1);返回后,表单数据会丢失

/*
对Win系统session过期时间可能无效，因此用cookie设置以下
session.gc_maxlifetime 默认1440 (为24分钟)
加个0,14400/60/60=4小时(为4小时)
*/
$manage_cookie=get_cfg_var("session.gc_maxlifetime");//默认获取session的时间
$member_cookie=get_cfg_var("session.gc_maxlifetime");
//$manage_cookie=60*60;//也可单独设置（秒*分*时）
//$member_cookie=60*60*2;

ini_set("session.cookie_httponly",TRUE);//安全设置:开启全局的Cookie的HttpOnly属性(开启后JS一般不能读取PHP的cookie)
ini_set('display_errors','On');//on开启显示错误，off关闭
error_reporting(E_ALL ^ E_NOTICE^ E_WARNING);//显示错误级别：显示除去 E_NOTICE 之外的所有错误信息 

//(秒)运行超时设置 (windows系统时可能无效，只能修改php.ini里的max_execution_time)
@header('Content-Type: text/html; charset=utf-8');//页面编码
@header('X-Frame-Options:SAMEORIGIN');//页面只能被本站页面嵌入到iframe或者frame中
@date_default_timezone_set("PRC");//设置中国时区 




//------------------------------------------------计算费用配置-------------------------------------------------
//默认计费公式
//$moren_gongshi_name="运费*运费折扣+(扣税次数*税费额度)+保价费+服务费+增值收费+其他";
$moren_gongshi_name="((首重价格+((总重-首重1) * 续重价格)) * 运费折扣)+(扣税次数*税费额度)+保价费+服务费+增值收费+其他";
$off_dengjijiage=1;//不同会员组有不同转运价格 (如果开通多收件国家，这里只能0)

//------------------------------------------------系统固定设置-------------------------------------------------
//基本设置
$off_jishu=0;//后台登录是否显示技术支持
//if($LT=='CN'){$jishu= '兴奥转运系统';}else{$jishu='XingAo TS';}//无效
$jishu= '兴奥转运系统';//后台系统名称
$banben='V7.0';//显示版本
$license_key='KVxth22327';//兴奥API查询KEY(请勿修改)



//------------------------------------------------系统/功能开关-------------------------------------------------
//支持的系统/功能
/*以下勿乱改动,如果未支持,表示没有该功能,开启后会使整个系统无法正常运行*/
$ON_demo=0;//演示站模式
$open_gd_mosuda=1;

//生成静态页专用网址 (可以手工指定)
$fileUrl=$_SERVER['HTTP_HOST'];//默认用域名
//$fileUrl=$_SERVER["SERVER_ADDR"];//服务器IP

//禁止访问本站设置
/*
	$limitShowTyp=0 全部禁止;
	$limitShowTyp=1 禁止所有HTML页面(前台);
	$limitShowTyp=2 禁止所有PHP页面 (会员中心和后台);
*/
$limitShowKey='';//禁止访问本站的地区含有关键字,如:杭州,郑州,美国
$ruleOutKey='阿里云,BGP,数据中心';//排除
$limitShowTyp='0';//禁止类型


//需要指定的栏目ID
$news_classid=22;//新闻公告大栏目ID
$daigou_classid=21;//代购栏目ID
$pay_classid=31;//支付说明
$price_classid=30;//服务收费
$peifu_classid=34;//赔付条例
$reg_classid=39;//注册使用条例

//后台隐藏栏目ID
$hide_classid='000';//最少要有一个,可以0

//禁止删除栏目ID
$nodel_classid='4,22,000';//最少要有一个,可以0
$nodel_classid.=",{$daigou_classid},{$pay_classid},{$price_classid},{$peifu_classid},{$news_classid},{$reg_classid}";//加上面指定的栏目ID


require_once($_SERVER['DOCUMENT_ROOT'].'/public/config_mysql.php');//数据库配置文件
?>