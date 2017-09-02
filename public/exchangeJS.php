<?php
require_once($_SERVER['DOCUMENT_ROOT'].'/public/config_top.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/public/function.php');

//---------------此页专为前台JS展示用，无其他作用---------------

$from=$JSCurrency;//设置原币种


//验证：防站外调用，占用资源-----------------------------
$hlurl = isset($_SERVER['HTTP_REFERER']) ? trim($_SERVER['HTTP_REFERER']) : '';
if(!$hlurl){$hlurl =$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];}
if(!stristr(str_ireplace(httpSite(),'',$hlurl),str_ireplace(httpSite(),'',$siteurl))&&!stristr($hlurl,'http://zy')&&!stristr($hlurl,'xingaowl.com'))
{
	echo 'document.writeln("<li>访问域名错误</li>");';
	exit('访问域名与后台设置域名不一致');
}


//载入缓存文件-----------------------------
$fileName=$_SERVER["DOCUMENT_ROOT"].'/cache/exchange.php';
include($fileName);


$update_auto=update_time('exchange_update','-3 hours');//多久更新一次:1 week 3 days 7 hours 30 minutes 5 seconds
$update=0;	if(spr($_GET['up'])&&$update_auto){$update=1;}

//更新和获取所有开启的币种-----------------------------
$arr=ToArr($openCurrency,',');
if($arr)
{
	foreach($arr as $arrkey=>$to)
	{
		if($from!=$to)
		{
			//更新
			if($update)
			{
				$exchange=exchange($from,$to);
				if($exchange)
				{
					$data.='
					$'.$from.'_'.$to.'="'.$exchange.'";
					';
				}
			}else{
				$joint=$from.'_'.$to; $exchange=$$joint;
			}
			
			//输出
			if(!$exchange){$exchange=exchange($from,$to);}
			$showJS.='document.writeln(" <li>1 '.$from.'='.$exchange.' '.$to.'</li>");';

		}
	}
}



//保存到缓存文件：由于前台经常访问，为节省资源，因此不直接从数据库读取-----------------------------
if($data)
{
	$data='<?php 
	$update_time="'.date('Y-m-d H:i:s',time()).'";
	'.$data.' 
	?>';
	file_put_contents($fileName,$data);
}




//JS输出-----------------------------
echo 'document.writeln(" <ul class=\'infoList\'>");';
echo $showJS;
echo 'document.writeln(" </ul>");';
?> 