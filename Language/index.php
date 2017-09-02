<?php
/*
在config.php载入,除config_top.php外,都能调用语种 

说明:
	增加语种:
	public\function_types.php \\languageType($zhi,$lx=0)
	public\language 目录增加语言包文件
	数据字段，对应的系统，如文章系统
	
	此页默认不能用function.php 的函数:测试是可用的
*/

@session_start();
//显示错误
ini_set('display_errors','On');//on开启显示错误，off关闭
error_reporting(E_ALL ^ E_NOTICE^ E_WARNING);//显示错误级别：显示除去 E_NOTICE 之外的所有错误信息 
	
//获取------------------------------------------------------------------------------------
if(!$LGType){$LGType=preg_replace('/[^0-9a-zA-Z]+/','',$_GET['LGType']);}
if(!$LGDefault){$LGDefault='CN';}//从config.php文件获取$LGDefault
if($LGType!=3)
{
	require_once($_SERVER['DOCUMENT_ROOT'].'/public/config_top.php');
	require_once($_SERVER['DOCUMENT_ROOT'].'/public/function.php');
}

if(!$ON_LG){
	$language=$LGDefault;
}else{
	
	//手工保存------------------------------------------------------------------------------------
	if($LGType==1)
	{
		if(!$language){$language=preg_replace('/[^a-zA-Z]+/','',$_GET['language']);}
		
		if($language)
		{
			if($_SESSION['member']['userid']){
				$xingao->query("update member set language='{$language}' where userid='{$_SESSION['member']['userid']}'");SQLError('会员保存语种');
			}
			
			if($_SESSION['manage']['userid']){
				$xingao->query("update manage set language='{$language}' where userid='{$_SESSION['manage']['userid']}'");SQLError('后台保存语种');
			}
	
			$_SESSION['language']=$language;
			setcookie('language',$language,time()+60*60*1,'/');
		}
		
		$prevurl = isset($_SERVER['HTTP_REFERER']) ? trim($_SERVER['HTTP_REFERER']) : 'main.php';
		
		//上一页是静态页时:转向新语种:语种修改0601
		$LGList=languageType('',2);
		if($LGList)
		{
			foreach($LGList as $arrkey=>$value)
			{
				$prevurl=str_replace("{$value}.html","{$language}.html",$prevurl,$count);
				if($count>0){break;}
			}
		}

		
		exit ("<script LANGUAGE='javascript'>location='{$prevurl}';</script>");
	}
	
	
	
	
	//按IP自动保存语种------------------------------------------------------------------------------
	if($LGType==2&&!$_COOKIE['language'])
	{
		setcookie('language','CN',time()+60*60*1,'/');//未有多种语言
	
		$lg_country=convertIP(GetIP());
		
		//保存1小时
		if(1==2){}
		
		elseif(have($openLanguage,'EN',1)&&(stristr($lg_country,'美国')||stristr($lg_country,'英国')||stristr($lg_country,'澳大利亚')))
		{setcookie('language','EN',time()+60*60*1,'/');}
		
		elseif(have($openLanguage,'JP',1)&&stristr($lg_country,'日本'))
		{setcookie('language','JP',time()+60*60*1,'/');}
		
		elseif(have($openLanguage,'KR',1)&&stristr($lg_country,'韩国'))
		{setcookie('language','KR',time()+60*60*1,'/');}
		
		elseif(have($openLanguage,'CN',1))
		{setcookie('language','CN',time()+60*60*1,'/');}
		
		if(!$_COOKIE['language']){setcookie('language',$LGDefault,time()+60*60*1,'/');}
		
	}
	
	
	
	
	//载入对应语种和其他文件 ----------------------------------------------------------
	/*在config.php调用*/
	if($LGType==3)
	{
		if(!$language&&$_SESSION['language']){$language=$_SESSION['language'];}
		elseif(!$language&&$_COOKIE['language']){$language=$_COOKIE['language'];}
		else{@setcookie('language',$language,time()+60*60*1,'/');}//1小时,需要加@
		
		if($_GET['language']){$language=$_GET['language'];}//临时
		
		$language=strtoupper(preg_replace('/[^a-zA-Z]+/','',$language));//只留字母
		
		if($language&&!file_exists($_SERVER['DOCUMENT_ROOT'].'/Language/'.$language.'.php') ){$language=$LGDefault;}

		//安全验证:只能载入已开启的语种版本
		if( !have($ONLanguage,$language,1) ){$language=$LGDefault;}

		//载入：主语种
		$loadFile='/Language/'.$language.'.php';
		if(HaveFile($loadFile)){require_once($_SERVER['DOCUMENT_ROOT'].$loadFile);}
		
		//载入：定制语种
		$loadFile='/Language/'.$language.'rep.php';
		if(HaveFile($loadFile)){require_once($_SERVER['DOCUMENT_ROOT'].$loadFile);}

		
		//载入：仓库配置文件
		$loadFile='/cache/warehouse'.$language.'.php';
		$loadFile_Default='/cache/warehouse'.$LGDefault.'.php';
		if(HaveFile($loadFile)){require_once($_SERVER['DOCUMENT_ROOT'].$loadFile);}
		elseif(HaveFile($loadFile_Default)){require_once($_SERVER['DOCUMENT_ROOT'].$loadFile_Default);}
		
		
	}
	
}//if(!$ON_LG){
$LGType='';
?>