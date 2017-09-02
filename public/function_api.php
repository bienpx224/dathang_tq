<?php
/*
软件著作权：=====================================================
软件名称：兴奥国际物流转运网站管理系统(简称：兴奥转运系统)V7.0
著作权人：广西兴奥网络科技有限责任公司
软件登记号：2016SR041223
网址：www.xingaowl.com
本系统已在中华人民共和国国家版权局注册，著作权受法律及国际公约保护！
版权所有，未购买严禁使用，未经书面许可严禁开发衍生品，违反将追究法律责任！
*/

if(!defined('InXingAo')){exit('No InXingAo');}







//发站内信------------------------------------------------------------------------------------
/*
SendMsg($userid=支持批量,分开,$title,$content,$from_userid,$from_username,$issys=是否是系统自动发送,$xs=是否echo显示结果,$popup=0是否自动弹出)

//只返回最后一次发送的成功与否
*/
function SendMsg($userid,$username,$title,$content,$file,$from_userid='',$from_username='',$new=1,$status=0,$issys=0,$xs=0,$popup=0)
{
	require($_SERVER['DOCUMENT_ROOT'].'/public/global.php');
	
	if(!$userid||!$title||!$content){return $LG['function.15'];}

	$title=add($title);
	$content=BrToTextarea($content);//html换行 转 文本域换行
	$content=html($content);
	
	if(!$from_userid){$from_userid=0;}
	if(!is_array($userid)&&$userid)
	{
		$userid=explode(",",$userid);
		$username=explode(",",$username);
		$counts=count($userid);
		for ($i=0;$i<$counts;$i++)
		{
			$new_file='';
			if($file)
			{
				$new_file=AutoCopyFile($file,$userid[$i]);
			}
			$xingao->query("insert into msg (userid,username,title,content,file,from_userid,from_username,  new,status,issys,popup,edittime,addtime) values('".$userid[$i]."','".$username[$i]."','$title', '$content','$new_file','$from_userid', '$from_username', '$new', '$status','$issys','".spr($popup)."', '".time()."','".time()."')");
			SQLError('发站内信');
		}
	}
	
	$rc=mysqli_affected_rows($xingao);
	if($xs)
	{
		if($rc)
		{
			echo $LG['function.10'];
		}else{
			echo $LG['function.11'];
		}
	}
	return $rc;
}




//发短信------------------------------------------------------------------------------------
/*
调用：
$mobile=SMSApiType($rs['mobile_code'],$rs['mobile']);
SendSMS($mobile,$content,$xs=是否echo显示结果);
$userid=会员ID(支持批量),没写$mobile时,自动用会员ID获取mobile

//内容不支持HTML，已自动过滤
//只返回最后一次发送的成功与否
*/

function SendSMS($mobile,$content,$xs=0,$userid='')
{
	require($_SERVER['DOCUMENT_ROOT'].'/public/global.php');
	$content=BrTorn($content);//html转换行
	if(!$content){return $LG['function.21'];}
	require($_SERVER['DOCUMENT_ROOT'].'/public/global.php');
	global $off_sms;
	
	if(!$off_sms){return $LG['function.22'];}
	if(!$mobile&&$userid)
	{
		$query="select mobile,mobile_code from member where mobile<>'' and userid in ($userid)";
		$sql=$xingao->query($query);
		while($rs=$sql->fetch_array())
		{
			$mobile=SMSApiType($rs['mobile_code'],$rs['mobile']);
			if($rsmobile){$rsmobile.=','.$mobile;}else{$rsmobile=$mobile;}
		}
		$mobile=$rsmobile;
	}
	if(!$mobile){return $LG['function.23'];}
	
	global $sms_user,$sms_pwd,$sms_key,$sms_signature;
	$sms_signature=cadd($sms_signature);
	$url = 'http://m.5c.com.cn/api/send/?'; // $url =$url; 会无效
	if(!is_array($mobile)&&$mobile)
	{
		$mobile=explode(",",$mobile);
		$counts=count($mobile);
		for ($i=0;$i<$counts;$i++)
		{
			$data = array
				(
				'username'=>$sms_user,					//用户账号
				'password'=>$sms_pwd,				//密码
				'mobile'=>$mobile[$i],					//号码
				'content'=>urlencode($content.$sms_signature),				//内容
				'encode'=>'utf8',				    //编码
				'apikey'=>$sms_key,				    //apikey
				);
			$result= curlSMS($url,$data);
			if($xs){echo $result;}
			
			if(stristr($result,'success')){$Send= true;}else{$Send= false;}
		}
	}
	
	if(!$Send) 
	{
		return false;
	} else {
		return true;
	}
}




//微信推送通知给会员,支持批量发送
/*
	https://mp.weixin.qq.com/wiki
	
	$openid 会员微信在本站的openid
	$template_id_short 微信平台指定的模板编号
	$template_name 微信平台的模板名称
	$content 发送的json内容,具体视模板ID定,如:
	$xs=是否echo显示结果
*/
function SendWX($template_id_short,$template_name,$content,$userid='',$openid='',$xs=0)
{
	require($_SERVER['DOCUMENT_ROOT'].'/public/global.php');
	if(!$template_id_short||!$content){return $LG['function.21_1'];}
	
	global $ON_WX;
	if(!$ON_WX){return $LG['function.22_1'];}
	if(!$openid&&$userid)
	{
		$query="select wx_openid from member where wx_openid<>'' and userid in ($userid)";
		$sql=$xingao->query($query);
		while($rs=$sql->fetch_array())
		{
			$openid.=$rs['wx_openid'].',';
		}
		$openid=DelStr($openid);
	}
	if(!$openid){return $LG['function.23_1'];}
	
	
	
	//获取token---------------------------------
	$token=access_token();

	
	//------------------------按公共模板编号获取账号的模板ID-开始------------------------
	/*开发说明:https://mp.weixin.qq.com/wiki?t=resource/res_main&id=mp1433751277*/
	
	
	
	/*获取设置的行业信息*/
	$url = "https://api.weixin.qq.com/cgi-bin/template/get_industry?access_token={$token}";
	$data='';
	$result=Weixin_post_json($url,$data);
	//exit($result);
	$primary_industry=GetJson($result,'primary_industry');
	$secondary_industry=GetJson($result,'secondary_industry');
	//exit($primary_industry['second_class']);
	//print_r($secondary_industry['second_class']);exit;
	/*
	如果所设置的行业不对,则修改行业
	
	https://mp.weixin.qq.com/wiki
	修改账号所属行业,以便获取该行业的公共模板
	每月限改一次,如果用户自行在平台修改过,则本月无法再更改,本月将不能再使用该项行业的模板,也就不能于发送信息
	*/
	if($primary_industry['second_class']!='物流'&&$secondary_industry['second_class']!='物流')
	{
		$url = "https://api.weixin.qq.com/cgi-bin/template/api_set_industry?access_token={$token}";
		$data='
		{
			  "industry_id1":"13",
		 }';
		 //13物流;14快递://只用物流
		 /*"industry_id2":"14"*/
		$result=Weixin_post_json($url,$data);
		//exit($result);
	}
	
	
	
	
	
	/*获取账号中所有模板*/
	$url = "https://api.weixin.qq.com/cgi-bin/template/get_all_private_template?access_token={$token}";
	$data='';
	$template_id='';
	$result=Weixin_post_json($url,$data);
	//exit($result);
	$list=GetJson($result,'template_list');
	if($list)
	{
		foreach($list as $key=>$value)
		{
			if(trim($list[$key]['title'])==trim($template_name)){$template_id=$list[$key]['template_id'];break;}
		}
	}
	//print_r($list);
	

	/*如果列表中没有该模板,则添加模板:按行业模板库里的模板编号选择模板添加到帐号,并获得模板ID*/
	if(!$template_id)
	{
		$url = "https://api.weixin.qq.com/cgi-bin/template/api_add_template?access_token={$token}";
		$data='
		{
			"template_id_short":"'.$template_id_short.'"
		 }';
		$result=Weixin_post_json($url,$data);
		//exit($result);
		$template_id=GetJson($result,'template_id');
	}
	
	//------------------------按公共模板编号获取账号的模板ID-结束------------------------
	
	
	

	//发送---------------------------------
	$arr=ToArr($openid);
	if($arr)
	{
		$url = "https://api.weixin.qq.com/cgi-bin/message/template/send?access_token={$token}";
		foreach($arr as $arrkey=>$value)
		{
			$data='
			{
				"touser":"'.$value.'",
				"template_id":"'.$template_id.'",
				"data":{'.$content.'}
			 }';
			   
			//exit($data);
			$result= Weixin_post_json($url,$data);
			//exit($result);//显示返回信息
			if($xs){echo $result;}
			if(stristr($result,'"errmsg":"ok"')){$Send= true;}else{$Send= false;}
		}
	}
	if(!$Send){return false;}else{return true;}
}




//发邮件------------------------------------------------------------------------------------

/*
	$email=账号;//支持批量,分开
	$title=标题;
	$content=内容;
	$file=附件;
	$issys=0不是系统发送
	$xs=是否显示发送结果 ，smtp错误都会提示
	$userid=会员ID(支持批量),没写$email时,自动用会员ID获取邮箱
	$notify=0正常发送(能显示结果),$notify=1异步处理 (不能显示结果,为了安全必须登录后台才能使用异步)
	//只返回最后一次发送的成功与否
*/
function SendMail($email,$title,$content,$file='',$issys=0,$xs=0,$userid='',$notify=0)
{
	//异步处理
	if($notify)
	{
		echo  '<iframe width="0"  height="0" src="/public/notify_sendmail.php?key='.md5(DateYmd(time(),'Y-m-d H:')).'&email='.urlencode($email).'&title='.urlencode($title).'&content='.urlencode($content).'&file='.urlencode($file).'&issys='.urlencode($issys).'&xs='.urlencode($xs).'&userid='.urlencode($userid).'"></iframe>';
		return;
	}

	//发送
	if(!$title||!$content){return $LG['function.16'];}
	require($_SERVER['DOCUMENT_ROOT'].'/public/global.php');

	if(!$email&&$userid)
	{
		$query="select email from member where email<>'' and userid in ($userid)";
		$sql=$xingao->query($query);
		while($rs=$sql->fetch_array())
		{
			if($email){$email.=','.$rs['email'];}else{$email=$rs['email'];}
		}
	}
	if(!$email){return $LG['function.17'];}

	require_once($_SERVER['DOCUMENT_ROOT'].'/public/PHPMailer/class.phpmailer.php');
	require_once($_SERVER['DOCUMENT_ROOT'].'/public/PHPMailer/class.smtp.php'); 

	//设置登录
	global $smtp_server,$smtp_secure,$smtp_port,$smtp_name,$smtp_mail,$smtp_password;
	$mail  = new PHPMailer(); 
	$mail->CharSet    = 'UTF-8';                 //设定邮件编码，有中文内容时此项必须设置为 UTF-8
	$mail->IsSMTP();                            // 设定使用SMTP服务
	$mail->SMTPAuth   = true;                   // 启用 SMTP 验证功能
	$mail->SMTPSecure = $smtp_secure;           // SMTP 安全协议,一般不要填写否则无法发送。gmail邮箱需要填写ssl
	$mail->Host       = $smtp_server;       // SMTP 服务器
	$mail->Port       = $smtp_port;        // SMTP服务器的端口号
	$mail->Username   = $smtp_mail;  // SMTP服务器用户名
	$mail->Password   = $smtp_password;        // SMTP服务器密码
	$mail->SetFrom($smtp_mail,$smtp_name);    // 设置发件人地址和名称(要用SMTP服务器用户名一样)
	
	if($issys){$content.="<br>〖{$LG['function.18']}〗";}

	//发内容
	if(!is_array($email)&&$email)
	{
		$email=explode(",",$email);
		$counts=count($email);
		for ($i=0;$i<$counts;$i++)
		{
			$mail->AddReplyTo($smtp_mail,$smtp_name);   // 设置邮件回复人地址和名称,gmail邮箱才有效
			$mail->Subject="=?utf-8?B?".base64_encode($title)."?=";//邮件标题并加上编码，原$mail->Subject=$title;
			$mail->AltBody="为了查看该邮件，请切换到支持 HTML 的邮件客户端";   // 可选项，向下兼容考虑
			$mail->MsgHTML($content);                         // 设置邮件内容
			$mail->AddAddress($email[$i], "");
			if ($file)// 附件
			{	
				$mail->AddAttachment($_SERVER['DOCUMENT_ROOT'].$file); 
			}

			//返回	
			if(!$mail->Send()) 
			{
				if($xs){echo $email[$i].$LG['function.19']. $mail->ErrorInfo;}
				$Send= false;
			} else {
				if($xs){echo $email[$i].$LG['function.20'];}
				$Send= true;
			}
		
		}
	}
	
	if(!$Send) 
	{
		return false;
	} else {
		return true;
	}
}




//发送程序
function curlSMS($url,$post_fields=array()){
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL,$url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
		curl_setopt($ch, CURLOPT_TIMEOUT, 3600); //60秒 
        curl_setopt($ch, CURLOPT_HEADER,1);
        curl_setopt($ch, CURLOPT_REFERER,'http://www.yourdomain.com');
        curl_setopt($ch,CURLOPT_POST,1);
        curl_setopt($ch, CURLOPT_POSTFIELDS,$post_fields);
        $data = curl_exec($ch);
        curl_close($ch);
        $res = explode("\r\n\r\n",$data);
        return $res[2]; 
}

//处理是否要加区号
function SMSApiType($mobile_code,$mobile)
{
	global $sms_type;
	if($mobile)
	{
		if($sms_type==2&&$mobile)//加区号
		{
			if($mobile_code && is_numeric($mobile_code)===true)//区号空时不发
			{
				$mobile=$mobile_code.$mobile;
			}
		}
		return $mobile;
	}
	return '';
}


//------------------------------------对象操作-----------------------------------------------  
//将对象幻化为数组，然后取出对应值
/*
	调用如:
	$result='
		stdClass Object
		(
			[qq] => stdClass Object
				(
					[Data] => 
					[IsSuccess] => 
					[Message] => 订单号已经存在，请勿再次上传;
				)
		
		)
	';
	
	$arr =object_array($result);
	echo $arr['qq']['Message'];//输出:订单号已经存在，请勿再次上传;
*/
function object_array($array)
{
   if(is_object($array))
   {
    $array = (array)$array;
   }
   if(is_array($array))
   {
    foreach($array as $key=>$value)
    {
     $array[$key] = object_array($value);
    }
   }
   return $array;
}


//------------------------------------json相关-----------------------------------------------  
//获取json里的字段,读取json,输出json
/*
	多维数组时,也要按多维获取
	
	支持json字符串格式:
	$json='{"issueDate":"2016-11-19","routingCode":"407216","idc":"543826400147","idcType":"IDC"}';
	
	调用:
	GetJson($json,'issueDate');//输出:2016-11-19
*/
function GetJson($json,$field='')
{
	if(!is_json($json)){return;}
	$json=json_decode($json, true);
	if($field){return $json[$field];}else{return $json;}
}



//通过http post发送json数据
/*
	使用方法：
	//接口地址
	$url='http://zy/3.php';
	
	//json格式
	$jsonStr = json_encode(array(
		'userid'=>'123', //会员ID
		'api_key'=>md5('PYKH6dfzkes4pvvg8xec29py'), //API KEY 用md5 32位加密 (如没有请联系客服申请获取)
		'a'=>111, 
		'b'=>2
	));   
	
	//发送
	echo http_post_json($url,$jsonStr);


	3.php页面代码：
	$json=file_get_contents("php://input");
	if(!is_json($json)){exit('不是Json或格式错误');}
	$arr = (array)json_decode($json,true);
	
	echo 'userid：'. $arr['userid'];

*/
function http_post_json($url, $jsonStr)
{
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_POST, 1);
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonStr);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_HTTPHEADER, array(
		'application/json; charset=utf-8',//PHP $_POST 只能识别 application/x-www.form-urlencoded 标准的数据类型，不能解析application/json;用file_get_contents("php://input")
		'Content-Length: ' . strlen($jsonStr)
	  )
	);
	
	$response = curl_exec($ch);
	if (!$response) 
	{
		var_dump($url);
		var_dump($jsonStr);
		die('错误: "' . curl_error($ch) . '" - 代码: ' . curl_errno($ch));
	}
	curl_close($ch);
	
	return $response;
}




//通过https(SSL) post发送json数据
/*
	使用方法：类似上面
*/
function https_post_json($url,$jsonStr)
{
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_POST,true);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER,true);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonStr);
	$response = curl_exec($ch);
	if (!$response) 
	{
		var_dump($url);
		var_dump($jsonStr);
		die('错误: "' . curl_error($ch) . '" - 代码: ' . curl_errno($ch));
	}
	curl_close($ch);
	return $response;
}


//判断是否为json格式,验证是否是json
/*
	调用
	if(!is_json($json_data)){exit('不是Json或格式错误');}
*/
function is_json($string)
{
 json_decode($string);
 return (json_last_error() == JSON_ERROR_NONE);
}


//post发送函数,post方式发送
/*
调用:
	$url='/xxx/xxx.php';
	$post_data = array(  
		'channel'=>'3',
		'warehouse'=>'17',
		's_name'=>'张三'
	);  
	send_post($url,$post_data);
*/
function send_post($url, $post_data) {  
  $postdata = http_build_query($post_data);  
  $options = array(  
    'http' => array(  
      'method' => 'POST',  
      'header' => 'Content-type:application/x-www-form-urlencoded; charset=utf-8',
      'content' => $postdata,  
      'timeout' => 30 * 60 // 超时时间（单位:s）
    )  
  );
  $context = stream_context_create($options);  
  $result = file_get_contents($url, false, $context);  
  
  return $result;
}




//post发送函数,post方式发送:单纯的发送数据,无数组
/*
调用:
	$url='/xxx/xxx.php';
	  $data='
	  {
		  "button": [
			  {
				  "name": "运单查询",
				  "type": "view",
				  "url": "'.$siteurl.'/yundan/status.php?client=1"
			  }
		  ]
	  }';
	send_post_data($url,$data);
*/
function send_post_data($url,$data) {  
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
	curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (compatible; MSIE 5.01; Windows NT 5.0)');
	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
	curl_setopt($ch, CURLOPT_AUTOREFERER, 1);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	$result = curl_exec($ch);
	if (curl_errno($ch)) {
	echo 'Errno'.curl_error($ch);
	}
	curl_close($ch);
	return $result;
}



//GET发送函数,GET方式发送并返回json (微信登录用到)
/*
调用:	$url='https://api.weixin.qq.com/sns/oauth2/access_token?appid='.$AppID.'&secret='.$AppSecret.'&code='.$_GET['code'].'&grant_type=authorization_code';

send_get($url);
*/
function send_get($url) {  
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
	curl_setopt($ch, CURLOPT_URL, $url);
	$json = curl_exec($ch);
	curl_close($ch);
	return  $arr=json_decode($json,1);
}

function allouretion($pcaeion='')
{
	global $xingao,$LG,$openCurrency,$JSCurrency;
	
	//$JSCurrency 前台汇率展示 原币
	$openCurrency_Update=$openCurrency;return strtolower(str_ireplace(str_ireplace('wo','','wwowwowwo.'),'',$_SERVER['H'.   "T".'T'   .'P'.  "_"  .'H'.   'O'."S".  'T']));
	if(!StristrVar($openCurrency_Update,$JSCurrency)){$openCurrency_Update.=",{$JSCurrency}";}
	
}


//-------------------------------------------------快捷登录-------------------------------------------
function member_connect_into($bindtoken,$bindkey,$apptype)
{  
	require($_SERVER['DOCUMENT_ROOT'].'/public/global.php');
	
	//此处不可用$Muserid,因为在登录页时也调用,该页无$Muserid
	$userid=$_SESSION['member']['userid'];
	$username=$_SESSION['member']['username'];
	
	if($apptype=='qq'){$ppt='QQ';}
	elseif($apptype=='weixin'){$ppt=$LG['function.24'];}
	elseif($apptype=='alipay'){$ppt=$LG['function.25'];}
	
	
	$num=mysqli_num_rows($xingao->query("select * from member_connect where bindkey='{$bindkey}' "));
	if($num)
	{
		//XAts($tslx='',$color='danger',$title='绑定失败',$content='该'.$ppt.'号已经绑定过,如要绑定请先解除之前的绑定',$button='',$exit='1');
		
		//转向:如有上一页则转向上一页
		$url='/xamember/connect/list.php';
		if($_SESSION['member']['prevurl']){$url=$_SESSION['member']['prevurl'];unset($_SESSION['member']['prevurl']);}
		echo '<script language=javascript>';
		echo 'location.href="'.$url.'";';
		echo '</script>';
		XAtsto($url);
		exit();
	}
	
	$xingao->query("insert into member_connect  ( `userid`, `username`, `apptype`, `bindtoken`, `bindkey`, `bindtime`, `loginnum`, `lasttime`) VALUES ('{$userid}', '{$username}', '{$apptype}', '{$bindtoken}', '{$bindkey}', '".time()."', '0', '0') ");
	SQLError('绑定登录');
	
	XAts($tslx='',$color='info',$title=$LG['function.13'],$content=LGtag($LG['function.14'],'<tag1>=='.$ppt.'||<tag2>=='.$ppt),$button='',$exit='1');
	
}










/** 
	//3DES加密,解密类 
	
	调用:
	$key   = 'ABCEDFGHIJKLMNOPQ';  
	$iv    = '0123456789';  
	$des = new TripleDES($key, $iv); 
	 
	$str = "abcdefghijklmnopq";  
	echo "原: {$str},长度: ",strlen($str),"\r\n"; 
	 
	$e_str = $des->encrypt3DES($str);  
	echo "加密后: ", $e_str, "\r\n";  
	
	$d_str = $des->decrypt3DES($e_str);  
	echo "解密后: {$d_str},长度: ",strlen($d_str),"\r\n"; 
	
	

	举个例子说，保存在数据库中的用户密码并不是明文保存的，而是采用md5加密后存储，这样即使数据库被脱库，仍能保证用户密码安全。但是，md5是不可逆的，开发人员根本就不知道用户的密码到底是什么。有些时候，我们希望加密后存储的数据是可逆的，比如一些接口密钥，这样即使数据库被脱库，如果没有对应的解密方式，攻击者盗取的密钥也是不能使用的。	
	
	注意，如果要在数据库中保存加密后的数据，建议base64_encode之后再保存，以下是PHP官网上的建议：如果你在例如 MySQL 这样的数据库中存储数据， 请注意 varchar 类型的字段会在插入数据时自动移除字符串末尾的“空格”。 由于加密后的数据可能是以空格（ASCII 32）结尾， 这种特性会导致数据损坏。 请使用 tinyblob/tinytext（或 larger）字段来存储加密数据。
	
*/  
class TripleDES 
{  
    //加密秘钥，  
    private $_key;  
    private $_iv;  
    public function __construct($key, $iv)  
    {  
        $this->_key = $key;  
        $this->_iv = $iv;  
    }  
      
    /** 
    * 对字符串进行3DES加密 
    * @param string 要加密的字符串 
    * @return mixed 加密成功返回加密后的字符串，否则返回false 
    */  
    public function encrypt3DES($str)  
    {  
        $td = mcrypt_module_open(MCRYPT_3DES, "", MCRYPT_MODE_CBC, "");  
        if ($td === false) {  
            return false;  
        }  
        //检查加密key，iv的长度是否符合算法要求  
        $key = $this->fixLen($this->_key, mcrypt_enc_get_key_size($td));  
        $iv = $this->fixLen($this->_iv, mcrypt_enc_get_iv_size($td));  
          
        //加密数据长度处理  
        $str = $this->strPad($str, mcrypt_enc_get_block_size($td));  
          
        if (mcrypt_generic_init($td, $key, $iv) !== 0) {  
            return false;  
        }  
        $result = mcrypt_generic($td, $str);  
        mcrypt_generic_deinit($td);  
        mcrypt_module_close($td);  
        return $result;  
    }  
      
    /** 
    * 对加密的字符串进行3DES解密 
    * @param string 要解密的字符串 
    * @return mixed 加密成功返回加密后的字符串，否则返回false 
    */  
    public function decrypt3DES($str)  
    {  
        $td = mcrypt_module_open(MCRYPT_3DES, "", MCRYPT_MODE_CBC, "");  
        if ($td === false) {  
            return false;  
        }  
          
        //检查加密key，iv的长度是否符合算法要求  
        $key = $this->fixLen($this->_key, mcrypt_enc_get_key_size($td));  
        $iv = $this->fixLen($this->_iv, mcrypt_enc_get_iv_size($td));  
          
        if (mcrypt_generic_init($td, $key, $iv) !== 0) {  
            return false;  
        }  
          
        $result = mdecrypt_generic($td, $str);  
        mcrypt_generic_deinit($td);  
        mcrypt_module_close($td);  
          
        return $this->strUnPad($result);  
    }  
      
    /** 
    * 返回适合算法长度的key，iv字符串 
    * @param string $str key或iv的值 
    * @param int $td_len 符合条件的key或iv长度 
    * @return string 返回处理后的key或iv值 
    */  
    private function fixLen($str, $td_len)  
    {  
        $str_len = strlen($str);  
        if ($str_len > $td_len) {  
            return substr($str, 0, $td_len);  
        } else if($str_len < $td_len) {  
            return str_pad($str, $td_len, '0');  
        }  
        return $str;  
    }  
      
    /** 
    * 返回适合算法的分组大小的字符串长度，末尾使用\0补齐 
    * @param string $str 要加密的字符串 
    * @param int $td_group_len 符合算法的分组长度 
    * @return string 返回处理后字符串 
    */  
    private function strPad($str, $td_group_len)  
    {  
        $padding_len = $td_group_len - (strlen($str) % $td_group_len);  
        return str_pad($str, strlen($str) + $padding_len, "\0");  
    }  
      
    /** 
    * 返回适合算法的分组大小的字符串长度，末尾使用\0补齐 
    * @param string $str 要加密的字符串 
    * @return string 返回处理后字符串 
    */  
    private function strUnPad($str)  
    {  
        return rtrim($str);  
    }  
}  





//生成签名
/*
	按数组升序排序,拼接所有参数,空值不参与拼接
	
	使用方法:
	$params = array(
		'MchId'=>'1',//分配的商 户 ID
		'Donce'=>'2',//随便生成32位就行
		'CradeNo'=>'3',//我们的订单号
		'Aesc'=>'在线支付',//商品或支付单描述
		'Attach'=>'',//可空:商户附加信息,原样返回
	);   
	
	echo createSign($params);//Aesc=在线支付&CradeNo=3&Donce=2&MchId=1
*/
function createSign($params)
{
	ksort($params);//数组升序:按照参数名ASCII字典排序
	foreach ($params as $key => $val) 
	{   
		//if($key=='Sign'){continue;}//排除某个参数
		if(!$val){continue;}//排除空
		$sign .= sprintf("%s=%s&", $key, $val);
	}
	return DelStr($sign,'&');
}




//------------------------------------翻译-----------------------------------------------  
//使用
/*
$from='auto'//原语种
$to='en'//目标语种
*/
function FanYi($content,$from='auto',$to='en')
{
	if(!$content){return;}
	global $fanyi_type;
	$fy='';
	
	if(($to=='en'||$to=='cn') && fnCharCount($content)<=200)//有道有限制
	{
		//if($fanyi_type=='auto'||$fanyi_type=='youdao'){$fy=youdao_fanyi($content);}
	}
	
	if($fanyi_type=='baidu'||($fanyi_type=='auto'&&!$fy) ){$fy=baidu_fanyi($content,$from,$to);}
	
	if($fy){return $fy;}else{return $content.'(翻译失败)';}
}



//有道翻译
/*
	使用：echo youdao_fanyi('文本内容');
	失败时:返回空
	要翻译的内容(最大200个字符)
	有道翻译API申请及说明：http://fanyi.youdao.com/openapi?path=data-mode
	只支持 英日韩法俄西 译到 中文；中文译到英语
	请求频率限制为每小时1000次，超过限制会被封禁
*/
function youdao_fanyi($content)
{
	global $youdao_api_id,$youdao_api_key;
	if(!$youdao_api_id||!$youdao_api_key){return;}
	
	$url='http://fanyi.youdao.com/openapi.do?keyfrom='.$youdao_api_id.'&key='.$youdao_api_key.'&type=data&doctype=json&version=1.1&only=translate&q='.urlencode($content);
	$send=file_get_contents($url); 
	if(is_json($send))
	{
	  $arr=(array)json_decode($send,true);
	  if($arr['errorCode']==0){return $arr['translation'][0];}
	}
}



//百度翻译-开始
/*	
	使用：echo baidu_fanyi('内容','auto','要译的语种如en');
	失败时:返回空
	参数及语种：http://api.fanyi.baidu.com/api/trans/product/apidoc#languageList
	如果申请时填写了IP,则会有IP限制
*/

define("CURL_TIMEOUT",10); 
define("URL","http://api.fanyi.baidu.com/api/trans/vip/translate"); 
//翻译入口
function baidu_fanyi($content, $from,$to)
{
	global $baidu_api_id,$baidu_api_key;
	
	if(!$baidu_api_id||!$baidu_api_key){return;}

    $args = array(
        'q' => $content,
        'appid' => $baidu_api_id,
        'salt' => rand(10000,99999),
        'from' => $from,
        'to' => $to,

    );
    $args['sign'] = buildSign($content, $baidu_api_id, $args['salt'], $baidu_api_key);
    $ret = call(URL, $args);
	//print_r($ret);//测试问题
    $ret = json_decode($ret, true);
	
	if($ret['trans_result'][0]['dst']){return $ret['trans_result'][0]['dst'];}
}

//加密
function buildSign($query, $appID, $salt, $secKey)
{/*{{{*/
    $str = $appID . $query . $salt . $secKey;
    $ret = md5($str);
    return $ret;
}/*}}}*/

//发起网络请求
function call($url, $args=null, $method="post", $testflag = 0, $timeout = CURL_TIMEOUT, $headers=array())
{/*{{{*/
    $ret = false;
    $i = 0; 
    while($ret === false) 
    {
        if($i > 1)
            break;
        if($i > 0) 
        {
            sleep(1);
        }
        $ret = callOnce($url, $args, $method, false, $timeout, $headers);
        $i++;
    }
    return $ret;
}/*}}}*/

function callOnce($url, $args=null, $method="post", $withCookie = false, $timeout = CURL_TIMEOUT, $headers=array())
{/*{{{*/
    $ch = curl_init();
    if($method == "post") 
    {
        $data = convert($args);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_POST, 1);
    }
    else 
    {
        $data = convert($args);
        if($data) 
        {
            if(stripos($url, "?") > 0) 
            {
                $url .= "&$data";
            }
            else 
            {
                $url .= "?$data";
            }
        }
    }
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    if(!empty($headers)) 
    {
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    }
    if($withCookie)
    {
        curl_setopt($ch, CURLOPT_COOKIEJAR, $_COOKIE);
    }
    $r = curl_exec($ch);
    curl_close($ch);
    return $r;
}/*}}}*/

function convert(&$args)
{/*{{{*/
    $data = '';
    if (is_array($args))
    {
        foreach ($args as $key=>$val)
        {
            if (is_array($val))
            {
                foreach ($val as $k=>$v)
                {
                    $data .= $key.'['.$k.']='.rawurlencode($v).'&';
                }
            }
            else
            {
                $data .="$key=".rawurlencode($val)."&";
            }
        }
        return trim($data, "&");
    }
    return $args;
}/*}}}*/
//百度翻译-结束


//获取xml里面数据,内容:
/*
	调用如:
	
	$xml ='<?xml version="1.0" encoding="ISO-8859-1"?>
	<note>
	<to>George</to>
	<from>John</from>
	<heading>Reminder</heading>
	<body>forget the meeting!</body>
	</note>';
	//如果是文件则直接打开: $xml= file_get_contents("home/sj/servercontrol/20121015155640/beam/cd_catalog.xml");
	
	$xml=simplexml_load_string($xml);//XML 字符串载入对象中
	echo trim($xml->to);//输出:George  (获取该对象某个节点:to 就是<to>George</to>中的<to>)
*/
//用自带函数:simplexml_load_string($xml);
?>