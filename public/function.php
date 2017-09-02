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
define('InXingAo',TRUE);
require_once($_SERVER['DOCUMENT_ROOT'].'/cache/config.php');//里面有导入语言文件,后台权限文件,会员权限文件
require_once($_SERVER['DOCUMENT_ROOT'].'/public/function_types.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/public/function_longList.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/public/function_sql.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/public/function_ts.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/public/function_api.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/public/function_wupin.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/public/function_baoguo.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/public/function_yundan.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/public/function_daigou.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/public/function_price.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/public/function_settlement.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/public/function_logistics.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/public/function_member.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/api/exchange/function.php'); 
require_once($_SERVER['DOCUMENT_ROOT'].'/api/mpWeixin/function.php'); 


//--------------------------------------通用处理-------------------------------------------
//自动验证config.php文件是否被非法修改
$md5=FeData('config','value2',"name='config'");
if(!FileCheck('/cache/config.php',$md5))
{
	//生成配置文件
	/*注意:配置内容不能加不能加cadd,因此不能只查询一个字段，因为FeData输出会是return cadd($cf[0]);*/
	$cf=FeData('config','id,value3',"name='config'");
	file_put_contents(AddPath('/cache/config.php'),$cf['value3']);//不能加cadd($cf['value2'])
}


//禁止某地区访问
if(!$_GET['JS']&&(!$limitShowTyp||$limitShowTyp==2)){limitShow($limitShowKey);}//调用















//------------------------------------------------内容处理-------------------------------------------------


//过滤、替换指定内容，指定字符,2个字符之间的内容
//正常替换：str_ireplace('旧的','新的',$str); //不区分大小写
/*
	$start='' 搜索开始:如果是特殊字符则前面加\
	$end=''	搜索结束:如果是特殊字符则前面加\
	$rep='' 替换新内容
	$str=内容

	echo pregRep($start='\(',$end='\)',$rep='',$str='10(三星)=9');//输出 10=9

*/
function pregRep($start='',$end='',$rep='',$str)
{
	return preg_replace("/{$start}.*?{$end}/si",$rep,$str);
}


//统计ID数量,可以是数组,也可以字符串
//$exp='' 默认用(,)区分
function arrcount($zhi,$exp='')
{
	if($zhi)
	{
		if(!is_array($zhi))
		{
			if(!$exp){$exp=',';}
			return count(explode($exp,$zhi));
		}else{
			return count($zhi);
		}
	}else{return 0;}
}



//删除最前/最后一个字符:区分大小写,不支持中文
/*
	$typ=0 删除最后一个字符
	$typ=1 删除最前一个字符
	
	常用:$gdid=DelStr($gdid);
*/

function DelStr($str,$char=',',$typ=0)
{
	$length=fnCharCount($char);
	
	if($typ==0&&substr($str,-$length)==$char){
		$str=substr($str,0,-$length);
	}elseif($typ==1&&substr($str,0,$length)==$char){
		$str=substr($str,$length);
	}
	
	return $str;
}

//数组转字符串
/*
	$separ=, 分隔符
*/
function ToStr($str,$separ=',')
{
	if(!$str){return;}//需要返回空,因为原值可能是空数组,返回的就还是数组
	if(is_array($str)){return implode($separ,$str);}
	return $str;//如果已经是字符,返回原值
}


//字符串 转数组/转指定分隔符的字符串
/*
	$lx=-1 ，,中文逗号和英文逗号都转数组
	$lx=0 ,英文逗号转数组
	$lx=1 文本域行 转数组
	$lx=2 ＝或=号转数组
	$lx='指定' 如$lx=':'
	
	$tostr=0 转数组
	$tostr=1 转字符串-SQL查询用:加par
	$tostr=2 转字符串-非查询用:不加par
	$separ=',' 转字符串时的分隔符
	
	
	常用:$gdid=ToArr($gdid);
*/
function ToArr($str,$lx=0,$tostr=0,$separ=',')
{
	
	if(!is_array($str)&&$str)
	{
		if($lx==-1){
			$str=str_replace('，',',',$str);
		}elseif($lx==1){
			$str=str_replace('  ',' ',$str);//获取并清空多空格
			$str=str_replace(PHP_EOL,',',$str);//把行替换为“,”
		}elseif($lx==2){
			$str=str_replace('＝＝',',',$str);//兼容旧的
			$str=str_replace('==',',',$str);//兼容旧的
			
			$str=str_replace('＝',',',$str);//新
			$str=str_replace('=',',',$str);//新
		}elseif($lx){
			$arr_separ=$lx;
		}
	
		//转换处理
		if(!$arr_separ){$arr_separ=',';}
		if($str)
		{
			$str=explode($arr_separ,$str);
			//清除两边空格
			foreach($str as $arrkey=>$value)
			{
				$str_now.=trim($value).'::::::::::';
			}
		}
		
		$str_now=DelStr($str_now,'::::::::::',0);
		$str=explode('::::::::::',$str_now);//转数组
		
		
		//转指定分隔符的字符串
		if($tostr)
		{
			
			if($tostr==1)
			{
				//过滤参数
				$arr=$str;$str_now='';
				if($arr)
				{
					foreach($arr as $arrkey=>$value)
					{
						$str_now.=par($value).$separ;
					}
				}
				$str=DelStr($str_now,$separ);
				
				
			}elseif($tostr==2){
				$str=implode($separ,$str);
			}

			
		}
		
	
	}
	return $str;
}




//多行数组时,自动获取对应的值
/*
	$val=值
	$str=多行数组:顺序必须从小到大
	
	$typ=0 全等式  （如代购品牌折扣）
	$typ=1 用最小的值 (过大时用最大的值)
	$typ=2 用最高的值 (过小时用最小的值)

	$must=1 必定有值:$typ=1时,过小时用最小的值; $typ=2时,过大时用最大的值




	如:
	$Price='
		10=1
		50=2
		100=3
	';
	$r=GetArrVar(60,$Price,1);//返回数组
	echo $r[1];//输出2
	
	$r=GetArrVar(60,$Price,2);//返回数组
	echo $r[1];//输出3
*/

function GetArrVar($val,$str,$typ=0,$must=0)
{
	$arr=ToArr($str,1);
	if($arr)
	{
		foreach($arr as $arrkey=>$value)
		{
			$line=ToArr($value,2);
			
			if($line[0])
			{
				if(!$s){$s[0]=$line[0];$s[1]=$line[1];$s[2]=$line[2];}//首行值
				$b[0]=$line[0];$b[1]=$line[1];$b[2]=$line[2];//最后一行值
				
				if($typ==0){
					if($val==$line[0]){$r[0]=$line[0];$r[1]=$line[1];$r[2]=$line[2];break;}
				}elseif($typ==1){
					//用最小的值,因此不可加break; (此行影响充值金额:不可乱改,否则充值赠送错误)
					if($val>=$line[0]){$r[0]=$line[0];$r[1]=$line[1];$r[2]=$line[2];}
					elseif($val<$line[0]){break;}
				}elseif($typ==2){
					//用最高的值
					if($val<=$line[0]){$r[0]=$line[0];$r[1]=$line[1];$r[2]=$line[2];break;}
				}
				
			}
		}
	}
	//print_r($r);
	
	//输出
	if($r[0]){return $r;}
	if($typ==1&&$must&&$s[0]){return $s;}
	if($typ==2&&$must&&$b[0]){return $b;}
}








//获取最后一个非空数组值
/*
	如果不用判断是否是空值,直接用end($classid); 即可
*/
function GetEndArr($classid)
{
	if(!$classid){return;}
	$classid=ToArr($classid);
	
	for ($i=count($classid); $i>=0; $i--)
	{
		if($classid[$i]){return $classid[$i];}
	}
}


//提取字符串中的全部数字
/*
	$typ=1 去除左边的0
*/
function findNum($str='',$typ=0)
{
	$str=trim($str);
	if(empty($str)){return '';}
	$result='';
	for($i=0;$i<strlen($str);$i++)
	{
		if(is_numeric($str[$i]))
		{
		$result.=$str[$i];
		}
	}
	
	if($typ==1){$result=findZero($result);}
	
	return $result;
}

//去除左边的0:注意必须是字符串形式,如findZero('0001');  错误findZero(0001);
function findZero($str='')
{
	$str=trim($str);
	if(empty($str)){return '';}
	while(substr($str,0,1)==0)
	{
		$str=substr($str,1);
	}
	return $str;
}

//过滤参数处理函数
/*
$par1=1 不过滤空格 (去空格防注入,这很重要)
$par2=1 不过滤/ 日期和地址目录要用
$par3=1 不过滤' (去'防注入,这很重要)
*/
function par($val,$par1='',$par2='',$par3='')
{
	if($val&&is_array($val)){exit('有参数是数组,不能加par');;}
	$val=trim($val);	
	if(!$val){return $val;}
	
	$val=addslashes($val);
	CkPostStrChar($val);
	
	if(!$par1){$val=RepEmpty($val);}//过滤空格
	if(!$par2){$val=str_replace('/','',$val);}//日期可能用
	if(!$par3){$val=str_replace("'",'',$val);}//'

	$val=str_replace(PHP_EOL, '',$val);//过滤换行
	$val=str_replace('\t','',$val);//过滤间隔字符(      )stripslashes($val)
	$val=str_replace('%20','',$val);
	$val=str_replace('%27','',$val);
	$val=str_replace('*','',$val);
	$val=str_replace('"','',$val);
	$val=str_replace(';','',$val);
	$val=str_replace('#','',$val);
	$val=str_replace('--','',$val);

	$val=html($val,1);
	FWClearGetText($val);
	return $val;
}




//严格过滤 (已加cadd)
/*
$lx=1只留字母和数字
$lx=2只过滤中文
*/
function StrictRep($str,$lx=1)
{
	if(!$str){return '';}
	$str=cadd(trim($str));
	if($lx==1){return preg_replace('/[^0-9a-zA-Z]+/','',$str);}//preg_replace('/[^\w]+/','',$str);//还可留下划线
	if($lx==2){return preg_replace('/[\x80-\xff]/','',$str);}
}



//获取当前栏目页面,以展开对应菜单
/*
	$lx=cf 用在实名认证
*/
function Act($zhi,$lx='nav')
{	
	if($lx=='nav'){
		return 0;//用js处理，已不使用 (浏览器可能兼容不好，保留)
	}
	
	global $_GET;
	if($zhi)
	{
		//$url=$_SERVER['PHP_SELF'];//获取本页地址,不带参数
		$url=$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];//获取本页地址,包括参数
		$src=cadd(urldecode($_GET['src']));
		$arr=$zhi;
		if($arr)
		{
			if(!is_array($arr)){$arr=explode(",",$arr);}//转数组
			foreach($arr as $arrkey=>$value)
			{
				if(stristr($url,$value)||stristr($src,$value))
				{
					return '1';break;
				}
			}
		}
	}
}


//小数点
/*
	$spr=小数位数
	$lx=0 空时不自动加0
	$lx=1 空是自动加0
	$format=1 自动加,逗号分隔
	$ceil 是否四舍五入
*/
function spr($number,$spr=2,$lx=1,$format=0,$ceil=0)
{
	if(!$lx&&!CheckEmpty($number)){return;}elseif($number==0){return 0;}
	
	if($ceil)
	{
		//四舍五入
		$number=sprintf("%.".$spr."f",$number);
	}else{
		//不四舍五入
		$arr=ToArr($number,'.');
		$arr[1]=substr($arr[1],0,$spr);
		$number=$arr[0].'.'.$arr[1];
	}
	$number=(double)$number;
	
	
	
	if($format)
	{
		$r=spr_sepa($number);
		$number=number_format($r[0]).'.'.$r[1];
	}
	
	return $number;
}



//分开获取前面数字和小数点后面数字
/*
	$r[0]数字; $r[1]小数点后面数字
*/
function spr_sepa($number)
{
	if(!$number){return;}
	$r=explode('.',$number);
	return $r;
}


//自动加http://
function addhttp($url)
{
	if(strlen($url)>5)
	{
		if (!stristr(substr($url,0,7),'http://')&&!stristr(substr($url,0,8),'https://')){$url='http://'.$url;}
	}
	return $url;
}


//增加addslashes和过滤XSS
function add($data){
	$data=trim($data);
	if(!$data){return $data;}//可能是0
	//$data=remove_xss($data);//不能放在add中,XingAoSave会无法使用,并且很影响性能
	$data=addslashes($data);
	return $data;
}

//去除addslashes和防XSS
function cadd($val)
{
	if(!trim($val)){return $val;}//可能是0
	$val=caddonly($val);

	//防XSS
	$val=htmlspecialchars_decode($val);
	$val=ehtmlspecialchars($val,ENT_QUOTES);
	
	return $val;
}

//去除addslashes支持HMTL输出
//$rephtml=0 是否过滤不安全HTML标签
//$xss=0 是否过滤xss
function caddhtml($val,$rephtml=0,$xss=0){
	$val=caddonly($val);
	$val=htmlspecialchars_decode($val);
	
	if($rephtml){$val=rephtml($val);}
	if($xss){$val=remove_xss($val);}
	return $val;
}

//去除addslashes (支持html代码时用)
function caddonly($val){
	$val=stripslashes(trim($val));
	return $val;
}

//去除 HTML 及 PHP 代码,清除,过滤HTML
/*
$empty=0 不清除空格和换行
$empty=1 清除空格和换行
$empty=2 清除空格和换行,但保留一个空格
*/
function striptags($val,$empty=1)
{
	$val=caddonly($val);
	$val=rephtml($val,0);
	$val=strip_tags($val);
	$val=trim($val);
	if($empty)
	{	
		$em=0;if($empty==2){$em=1;}
		$val=RepEmpty($val,$em);
	}
	//$val=remove_xss($val);
	
	return $val;
}


//转成 HTML 格式
function ehtmlspecialchars($val,$flags=ENT_COMPAT){
	if(PHP_VERSION>='5.4.0')
	{
		$char='UTF-8';
		$val=htmlspecialchars($val,$flags,$char);
		//htmlentities转换所有的html标记,htmlspecialchars只格式化& ' " < 和 > 这几个特殊符号
		//htmlentities 未指定编码:会格式化中文字符使得中文输入是乱码
	}
	else
	{
		$val=htmlspecialchars($val,$flags);
	}
	return $val;
}

//清除空格,换行
/*
$em=0 不留空;$em=1留一个空格;
*/
function RepEmpty($val,$em=0)
{
	$val=cadd($val);
	$val=str_replace(' ',$em?' ':'',$val);//空
	$val=str_replace('&nbsp;',$em?' ':'',$val);//空
	$val=str_replace(chr(8),$em?' ':'',$val);//回格
	$val=str_replace(chr(9),$em?' ':'',$val);//tab(水平制表符)
	$val=str_replace(chr(32),$em?' ':'',$val);//空格 SPACE
	$val=str_replace(chr(11),$em?' ':'',$val);//tab(垂直制表符)
	$val=str_replace(chr(10),$em?' ':'',$val);//换行
	$val=str_replace(chr(13),$em?' ':'',$val);//回车
	$val=str_replace(chr(13)&chr(10),$em?' ':'',$val);//回车和换行的组合
	
	$val=BrToTextarea($val);//html先转为PHP_EOL再过滤换行
	$val=str_replace(PHP_EOL,$em?' ':'',$val);//换行
	
	if($em){ while(stristr($val,'  ')){$val=str_ireplace('  ',' ',$val);} }
	
	return $val;
}


//html转js函数
function HtmlToJs($str)
{
	$re='';
	$str=str_replace("'","'",$str);
	$str=str_replace('"','"',$str);
	$str=str_replace('\t','',$str);
	$str= split("\r\n",$str);       
	for($i=0;$i<count($str);$i++){
	if(trim($str[$i]))
	{
//为生成美观，不要有缩进
$re.="document.write(\"".addslashes(stripslashes(str_replace("\r\n","",trim($str[$i]) )))."\");
";
	}
	}
	return $re;
}




//标签替换，语言包标签
/*
	<?=LGtag($LG['xxx'],'<tag1>=='.$integral_bili.'||<tag2>=='.$MCurrencyn)?>
*/
function LGtag($lg='',$tag='')
{
	$arr=ToArr($tag,'||');
	if($arr&&$lg)
	{
		foreach($arr as $arrkey=>$value)
		{
			$value=ToArr($value,'==');
			$lg=str_ireplace(trim($value[0]),trim($value[1]),$lg);
		}
	}
	return $lg;
}



/*
	语言标签替换:带cadd(
	
	$rs['content']='QQQ$LG[daigou.1]WWW$LG[daigou.2]';//保存到数据库内容
	echo LGLabel($rs['content']);//输出:QQQ初次支付WWW后期补款

*/
function LGLabel($content)
{
	require($_SERVER['DOCUMENT_ROOT'].'/public/global.php');
	$contentOld=$content;
	while(have($content,'$LG[',0))
	{
		$i++;if($i>500){return 'LGContent函数执行错误:超过替换次数限制,请检查内容是否正确:('.$contentOld.')';}//防恶意代码
		$LT=par(collect('$LG[',']',$content));//获取语言标识
		if(fnCharCount($LT)>100){return 'LGContent函数执行错误:LT长度超过100字符('.$LT.'),请检查内容是否正确:('.$contentOld.')';}//防恶意代码
		$content=str_replace('$LG['.$LT.']',$LG[$LT],$content);
	}
	
	
	return cadd($content);
}




function linkCS($CS)
{
	if(!$CS)return;	
	$arr=ToArr($CS,1);
	return urldecode(cadd($arr[ rand(0,arrcount($arr)-1) ]));
}


//标签转换
/*
	$typ=0 转换
	$typ=1 显示支持的标签
*/
function Label($str,$typ=0){
	require($_SERVER['DOCUMENT_ROOT'].'/public/global.php');
	if($typ){
		?>
        <span class="help-block">显示信息标签：<br>
            本站名称：[!--sitename--]<br>
            本站网址：[!--siteurl--]<br>
            
            会员ID：[!--userid--]<br>
            会员入库码：[!--useric--]<br>
            会员登录名：[!--username--]<br>
            会员真实姓名：[!--truename--]<br>
            会员英文名/拼音名：[!--enname--]<br>
            会员昵称：[!--nickname--]<br>
            生日积分：[!--birthday_integral--]<br>
            <br>
            专属客服：<br>
            <?php 
			for($i=0; $i<=10; $i++)
			{
			   echo $LG['CustomerService.'.$i]?$LG['CustomerService.'.$i].':[!--CustomerService.'.$i.'--]':'';
			}
			?>
          
        </span>
		<?php	
	}else{
		global $sitename,$siteurl,$memberid_tpre,$integral_MemberBirthday,$Muserid;
		if(!$userid){$userid=$Muserid;}
		if(!$userid){$userid=$_SESSION['member']['userid'];}
		$mr=FeData('member','userid,useric,username,nickname,truename,enname,CustomerService',"userid='{$userid}'");
		
		$str=caddonly($str);
		$str=str_ireplace('[!--sitename--]',cadd($sitename),$str);
		$str=str_ireplace('[!--siteurl--]',cadd($siteurl),$str);
		$str=str_ireplace('[!--userid--]',$memberid_tpre.$mr['userid'],$str);
		$str=str_ireplace('[!--useric--]',$mr['useric'],$str);
		$str=str_ireplace('[!--username--]',$mr['username'],$str);
		$str=str_ireplace('[!--truename--]',$mr['truename'],$str);
		$str=str_ireplace('[!--enname--]',$mr['enname'],$str);
		$str=str_ireplace('[!--nickname--]',$mr['nickname'],$str);
		$str=str_ireplace('[!--birthday_integral--]',$integral_MemberBirthday,$str);
		
		if($mr['userid'])
		{
			$r=CustomerService($mr['CustomerService']);
			for($i=0; $i<=10; $i++)
			{
			   $str=str_ireplace('[!--CustomerService.'.$i.'--]',$r[$i],$str);
			}
		}
		
		

		return trim($str);
	}
	
}



//标签转换:替换评论表情
function LabelFace($str)
{
	$str=str_ireplace('[~','<img src="/images/face/',$str);
	$str=str_ireplace('~]','" border=0>',$str);
	return trim($str);
}



//过滤不安全的 HTML标签（前台编辑器用到）
/*
$ts=0不显示已过滤提示;$ts=1显示已过滤提示
*/
function rephtml($str,$ts=1){
	//后面的@is表示  i:取消大小写敏感性,s:任何字符将会变成任意字符
	$str = preg_replace( "@<script(.*?)script(.*?)>@is", $ts?"(script标签已过滤)":'', $str ); 
	$str = preg_replace( "@<iframe(.*?)iframe(.*?)>@is", $ts?"(iframe标签已过滤)":'', $str ); 
	$str = preg_replace( "@<style(.*?)style(.*?)>@is", $ts?"(style标签已过滤)":'', $str ); 
	$str = preg_replace( "@<object(.*?)object(.*?)>@is", $ts?"(object标签已过滤)":'', $str ); 
	$str = preg_replace( "@<frameset(.*?)frameset(.*?)>@is", $ts?"(frameset标签已过滤)":'', $str ); 
	$str = preg_replace( "@<frame(.*?)frame(.*?)>@is", $ts?"(frame标签已过滤)":'', $str ); 
	$str = preg_replace( "@<input(.*?)input(.*?)>@is", $ts?"(input标签已过滤)":'', $str ); 
	$str = preg_replace( "@<form(.*?)form(.*?)>@is", $ts?"(form标签已过滤)":'', $str ); 
	$str = preg_replace( "@<html(.*?)html(.*?)>@is", $ts?"(html标签已过滤)":'', $str ); 
	$str = preg_replace( "@<body(.*?)body(.*?)>@is", $ts?"(body标签已过滤)":'', $str ); 
	$str = preg_replace( "@<title(.*?)title(.*?)>@is", $ts?"(title标签已过滤)":'', $str ); 
	$str = preg_replace( "@<link(.*?)link(.*?)>@is", $ts?"(link标签已过滤)":'', $str ); 
	$str = preg_replace( "@<meta(.*?)meta(.*?)>@is", $ts?"(meta标签已过滤)":'', $str ); 
	$str = preg_replace( "@<a(.*?)a(.*?)>@is", $ts?"(a标签已过滤)":'', $str ); 
	//$str = preg_replace( "@<(.*?)>@is", $ts?"(<其他>标签已过滤)":'', $str ); 
	return $str;
}


//保密，隐藏，星号 ***号
/*
echo substr_cut('1234567',3);//输出：123*567
*/
function substr_cut($str,$length=3)
{
    $str=cadd($str);
	if($str)
	{
		$length=(int)$length;
		$strlen     = mb_strlen($str, 'utf-8');
		$lengthzhi=$strlen-$length-$length;
		if($lengthzhi>0)
		{
			$firstStr   = mb_substr($str, 0, $length, 'utf-8');
			$lastStr    = mb_substr($str, -$length,$length, 'utf-8');
			 return  $firstStr . str_repeat('*', mb_strlen($str, 'utf-8') -$length*2).$lastStr ;
		}else{
			 $length=$strlen/2-1;
			 if($length>0&&$length<1){$length=1;}
			 if($length>=1){ return substr_cut($str,$length);}else{ return $str;}
		}
	}
}


//多值字段转下拉
function Select($str,$zhi='')
{
	if($str)
	{
		$zhi=trim($zhi);
		
		$str=cadd($str);
		$str=trim(str_replace('  ',' ',$str));//获取并清空多空格
		$str=trim(str_replace(Chr(10),',',$str));
		$str=explode(",",$str);//转为数组
		
		foreach($str as $key=>$value)
		{
			$value=trim($value);
			if($value)
			{
				$selected=$zhi==$value?'selected':''; echo '<option value="'.$value.'" '.$selected.'>'.$value.'</option>';
			}
		}
	}
}

//获取图片地址
function ImgAdd($str,$lx='',$width='',$height='')
{
	$str=cadd($str);
	if($lx)
	{
		if($str)
		{
			return '<img src="'.$str.'" width="'.$width.'" height="'.$height.'"/>';
		}
	}else{
		if($str)
		{
			return $str;
		}else{
			return '/images/notimg.gif';
		}	
	}	
}



//转正数,转负数,转0
/*
	$typ=0 负数时变0
	$typ=1 负数转正数
	$typ=2 正数转负数
*/
function RepPIntvar($val,$typ=0)
{
	if($typ==0&&$val<0){return 0;}
	elseif($typ==1&&$val<0){return abs($val);}
	elseif($typ==2&&$val>0){return -$val;}
	return $val;
}


//文本框,文本域 转HTML
function html($val,$xa=0){
	$val=ehtmlspecialchars($val,ENT_QUOTES);
	if($xa==0)
	{
		CkPostStrChar($val);
		$val=add($val);
		//FireWall
		FWClearGetText($val);
	}
	return $val;
}

//文本域换行转 <br>,转换行,并且带cadd (自动识别操作系统)
//$html=0 是否支持html,必须默认为0
function TextareaToBr($val,$html=0)
{	
	if($html){$val=caddhtml($val,1,0);}else{$val=cadd($val);}
	return str_replace(PHP_EOL, '<br>',$val);
}

//文本域换行 转 “,”逗号并且带cadd,并自动清除空行 (自动识别操作系统)
/*
$tpe 可以指定替换为其他,如$tpe="','"
*/
function TextareaToCo($val,$tpe=',')
{	
	$val=caddhtml($val,1,1);
	$val=str_replace(PHP_EOL,$tpe,$val);
	$val=Repeat($val,$tpe=',',$ba=1);//过滤重复的,号
	$val=trim(DelMoreSpace($val));//获取并清空多空格
	return $val;
}


//过滤重复字符,多个字符,过滤多个
/*
$tpe 可以指定过滤内容,如$tpe="','"
$ba=0 内容的前后保留该字符(输出如 ,1,2,);$ba=1不保留(输出如 1,2)
*/
function Repeat($val,$tpe=',',$ba=0)
{
	if(!trim($val)){return $val;}//可能是0

	$val=str_replace($tpe,'¤',$val);;//把行替换为“¤”
	$val=trim(DelMoreSpace($val));//获取并清空多空格
	
	$x=1;
	while($x==1) 
	{
		$val=str_replace($tpe,'¤',$val); //替换为“¤”
		if (strpos($val,$tpe)===false){$x=0;}else{$x=1;}
	} 
	
	$x=1;
	while($x==1) 
	{
		$val=str_replace('¤¤','¤',$val); //多个替换一个
		if (strpos($val,'¤¤')===false){$x=0;}else{$x=1;}
	} 
	
	if($ba)
	{
		$substr_str=substr($val,0,2); //检查前面是否是¤号
		if  ($substr_str=='¤'){
			$val=substr($val,2);//去除前面¤号
		}
		
		$substr_str=substr($val,-2); //检查后面是否是¤号
		if  ($substr_str=='¤'){
			$val=substr($val,0,-2);//去除后面¤号
		}
	}
	
	$val=str_replace('¤',$tpe,$val);
	return $val;
}

//html换行 转 文本域换行,并且带striptags (自动识别操作系统)
function BrToTextarea($str)
{	
	$str= str_replace('<br>', PHP_EOL,$str);
	$str= str_replace('</br>', PHP_EOL,$str);
	$str= str_replace('<p>', PHP_EOL,$str);
	$str= str_replace('</p>', PHP_EOL,$str);
	return striptags($str,0);//因为有PHP_EOL，必须0
}

//html换行 转 手机换行\r\n,并且带striptags (手机内容专用处理)
function BrTorn($str){	
	$str= str_replace('<br>','xa_\r\n',$str);
	$str= str_replace('</br>','xa_\r\n',$str);
	$str= str_replace('<p>', 'xa_\r\n',$str);
	$str= str_replace('</p>', 'xa_\r\n',$str);
	$str= str_replace(PHP_EOL, 'xa_\r\n',$str);
	$str=striptags($str,2);//会过滤\所以下面重新替换
	//$str=str_replace('xa_rn', '\r\n',$str);
	$str=str_replace('xa_rn', '
',$str);//2017.5.22更新为此种换行
	
	//【】签名使用,使用时会被替换为[],所以改为〖〗
	$str= str_replace('【', '〖',$str);
	$str= str_replace('】', '〗',$str);
	
	return $str;
}


//是否含有,不分大小写
/*
	$content 原内容
	$str  	 要检查的内容,可以查询多个,用(,)符号分开,
		 	 但不能检查(,)符号 错误如:$str=',';
		  
	$lx=0 	字符类型(不是很准确,如:-1和1会匹配) (支持多个字符用,分开)
	$lx=1 	数组类型(全等式) (会自动转数组 用[,]符号分隔)
			按数组检查时,$content和$str 可不用区分,前后都可以
	
	if(have('0,1,2',spr($rs['status']))){}
*/
function have($content,$str,$lx=1)
{
  if(CheckEmpty($content)&&CheckEmpty($str))
  {
	  if($lx==0){
		  $r=explode(',',$str);
		  $count=count($r);
		  for($i=0;$i<$count;$i++)
		  {
			  if(stristr($content,$r[$i])){return TRUE;}
		  }
	  }elseif($lx==1){
		  if(!is_array($content)&&CheckEmpty($content)){$content=explode(',',$content);}
		  if(!is_array($str)&&CheckEmpty($str)){$str=explode(',',$str);}
		  if(array_intersect($content,$str)) {return TRUE;}
	  }
  }
}

//是否含有,不分大小写:兼容旧版
function StristrVar($content,$str,$lx=0)
{
 	return have($content,$str,$lx);
}


//删除数组,删除元素
/*
	$arr 原字符或数组:自动转数组
	$del 要删除的字符或数组:自动转数组 (支持多个,符号分开)
	
	$ret=0 返回数组
	$ret=1 返回字符串
	
	$separ=, 分隔符
	$reset= 是否重新索引
			Array([0] => q0  [2] => q2)//不重新索引
			Array([0] => q0  [1] => q2)//重新索引
*/


function ArrDel($arr,$del,$ret=0,$separ=',',$reset='0')
{
	if(!$arr){return;}
	$arr=ToArr($arr,$separ);
	$del=ToArr($del,$separ);
	if($del)
	{
		foreach($del as $key=>$val)
		{
			$location=@array_search($val,$arr,true);
			if(CheckEmpty($location)){unset($arr[$location]);}
			else{unset($arr[$val]);}
		}
	}
	
	if($reset){$arr = array_values($arr);}
	if($ret){$arr = ToStr($arr,$separ);}
	return $arr;
}




//删除$arr1不重复的数组,取出$arr1重复的数组
/*
	$ip=array_unique($ip);//删除重复数组(重复值只保留一个)
*/
function ArrDel_Repeat($arr1,$arr2)
{
	if(!$arr1){return $arr2;}
	if(!$arr2){return ;}
	$arrDel=array_diff($arr1,$arr2);
	if($arrDel){return ArrDel($arr1,$arrDel);}
	return $arr1;
}




//获取所有$arr值并加上KEY生成SHA1
/*
	$alone 不加这些数组,多个用(,)分开
*/
function createSHA1($arr,$key,$alone='')
{
	if(!$arr){return;}
	
	$arr=ToArr($arr,',');
	$arr=ArrDel($arr,$alone);//删除$alone
	
	if($arr)
	{
		foreach($arr as $arrkey=>$value)
		{
			$str.=$value;
		}
	
		$str.=$key;
		return sha1($str);
	}
}



//处理编码字符
function CkPostStrChar($val){
	if(substr($val,-1)=="\\")
	{
		exit('禁止含有\字符');
	}
}

//返回转义
function egetzy($n='2'){
	if($n=='rn')
	{
		$str="\r\n";
	}
	elseif($n=='n')
	{
		$str="\n";
	}
	elseif($n=='r')
	{
		$str="\r";
	}
	elseif($n=='t')
	{
		$str="\t";
	}
	elseif($n=='syh')
	{
		$str="\\\"";
	}
	elseif($n=='dyh')
	{
		$str="\'";
	}
	else
	{
		for($i=0;$i<$n;$i++)
		{
			$str.="\\";
		}
	}
	return $str;
}


//检查敏感字符
function FWClearGetText($str){
	global $security_clear;
	//固定默认敏感字符
	$security_default='select,outfile,union,delete,insert,into,update,replace,sleep,benchmark,load_file,create';
	if($security_clear){$security_clear.=','.$security_default;}else{$security_clear=$security_default;}
	
	$security_clear=cadd($security_clear);
	$security_clear=str_ireplace('，',',', $security_clear);
	
	if(empty($security_clear))	{return '';	}
/*
	无法正常判断,极不安全
	if(have($str,$security_clear,1))
	{
		exit ("<script>alert('含有屏蔽提交敏感字符:{$str}');goBack();</script>");
	}
*/	
	$r=explode(',',$security_clear);
	$count=count($r);
	for($i=0;$i<$count;$i++)
	{
		if(stristr($str,$r[$i])&&$str!='unionpay')//特列
		{
			exit ("<script>alert('含有屏蔽提交敏感字符:{$str}');goBack();</script>");
		}
	}

}

//验证字符是否空,真空;数组时也返回非空
/*
if(CheckEmpty(0)){echo '有值';} //输出 有值
*/
function CheckEmpty($val)
{
	if(is_array($val)){return 1;}
	return strlen($val)==0?0:1;
}

//验证码返回变量名
function xaReturnKeyVarname($v){
	if($v=='login')//登陆
	{
		$name='checkloginkey';
	}
	elseif($v=='reg')//注册
	{
		$name='checkregkey';
	}
	elseif($v=='info')//信息
	{
		$name='checkinfokey';
	}
	elseif($v=='safety')//安全修改
	{
		$name='checksafetykey';
	}
	elseif($v=='gbook')//留言
	{
		$name='checkgbookkey';
	}
	elseif($v=='feedback')//反馈
	{
		$name='checkfeedbackkey';
	}
	elseif($v=='getpassword')//取回密码
	{
		$name='checkgetpasskey';
	}
	elseif($v=='regsend')//重发激活邮件
	{
		$name='checkregsendkey';
	}
	elseif($v=='pl')//评论pl
	{
		$name='checkplkey';
	}
	elseif($v=='cp')//评论pl
	{
		$name='checkcoupons';
	}
	elseif($v=='ot')//其他
	{
		$name='checkother';
	}
	return $name;
}


//采集内容,截取部分字符，截取里面内容，截取两个字符之间的内容
/*
	内容不是utf-8编码时，先转成utf-8 
	$str = iconv("GB2312", "UTF-8", file_get_contents("http://www.dailyfx.com.hk/")); 
	
	echo collect('开始代码', '结束代码',$str); //注意：代码部分如有换行，也要换行，要完全一样，不能随便缩进
*/
function collect($start,$end,$content)
{      
	//先用字符串截取函数
	$temp = explode($start, $content, 2);      
	$content = explode($end, $temp[1], 2);      
	if($content[0])
	{
		return $content[0];
	}else{
		//失败时用正则截取函数
		@$temp = preg_split($start, $content);      
		@$content = preg_split($end, $temp[1]); 
		return $content[0];
	} 
}  






//截取字数,长度 (以字节算)
/*
	echo leng("123456",3,"...");//输出：123...
	已带有:cadd和striptags (过滤html,php)
*/
function leng($string,$length,$dot='')
{
	$string=striptags($string);
	return sub($string,0,$length,false,$dot);
}


//统计字符数
/*
计算中英文混合字符串的长度 
调用：
echo fnCharCount("11我11") ;//输出6
*/

function fnCharCount($str)
{ 
	$ccLen=0; 
	$ascLen=strlen($str); 
	$ind=0; 
	preg_match_all("/[xA1-xFE]/",$str,$hasCC); #判断是否有汉字 
	preg_match_all("/[x01-xA0]/",$str,$hasAsc); #判断是否有ASCII字符 
	if($hasCC && !$hasAsc) #只有汉字的情况 
	return strlen($str)/2; 
	if(!$hasCC && $hasAsc) #只有Ascii字符的情况 
	return strlen($str); 
	for($ind=0;$ind<$ascLen;$ind++) 
	{ 
	if(ord(substr($str,$ind,1))>0xa0) 
	{ 
	$ccLen++; 
	$ind++; 
	} 
	else 
	{ 
	$ccLen++; 
	} 
	} 
	return $ccLen; 
}


//截取字符
function sub($string,$start=0,$length,$mode=false,$dot='',$rephtml=0)
{
	if($rephtml==0)
	{
		$string = str_replace(array('&nbsp;','&amp;','&quot;','&lt;','&gt;','&#039;'), array(' ','&','"','<','>',"'"), $string);
	}
	
	$strlen=fnCharCount($string);
	if($strlen<=$length)
	{
		return $string;
	}

	$strlen=strlen($string);
	$strcut = '';
	
	$n = $tn = $noc = 0;
	while($n < $strlen) {

		$t = ord($string[$n]);
		if($t == 9 || $t == 10 || (32 <= $t && $t <= 126)) {
			$tn = 1; $n++; $noc++;
		} elseif(194 <= $t && $t <= 223) {
			$tn = 2; $n += 2; $noc += 2;
		} elseif(224 <= $t && $t < 239) {
			$tn = 3; $n += 3; $noc += 2;
		} elseif(240 <= $t && $t <= 247) {
			$tn = 4; $n += 4; $noc += 2;
		} elseif(248 <= $t && $t <= 251) {
			$tn = 5; $n += 5; $noc += 2;
		} elseif($t == 252 || $t == 253) {
			$tn = 6; $n += 6; $noc += 2;
		} else {
			$n++;
		}

		if($noc >= $length) {
			break;
		}

	}
	if($noc > $length) {
		$n -= $tn;
	}

	$strcut = substr($string, 0, $n);

	if($rephtml==0)
	{
		$strcut = str_replace(array('&','"','<','>',"'"), array('&amp;','&quot;','&lt;','&gt;','&#039;'), $strcut);
	}

	return $strcut.$dot;
}



//取得随机数(大小写字母)
function make_password($length){
	$pattern='abcdefghkmnprstuvwxyzABCDEFGHKMNPRSTUVWXYZ';//随机因子
	$length=(int)$length;
	for($i=0;$i<$length;$i++)
	{
	$key .= $pattern{mt_rand(0,strlen($pattern)-1)}; 
	}
	return $key;
}

//取得随机数(字母)
/*
$lx='b' 大写;$lx='s' 小写
*/
function make_letter($length,$lx='b')
{
	if($lx=='b'){
		$pattern='ABCDEFGHKMNPRSTUVWXYZ';//随机因子
	}elseif($lx=='s'){
		$pattern='abcdefghkmnprstuvwxyz';//随机因子
	}
	$length=(int)$length;
	for($i=0;$i<$length;$i++)
	{
		$key .= $pattern{mt_rand(0,strlen($pattern)-1)}; 
	}
	return $key;
}


//取得随机数(数字)
function no_make_password($length){
	$pattern='1234567890';
	$length=(int)$length;
	for($i=0;$i<$length;$i++)
	{
	$key .= $pattern{mt_rand(0,strlen($pattern)-1)}; 
	}
	return $key;
}

//取得随机数(字母和数字)
function make_NoAndPa($length)
{
	$pattern='abcdefghkmnprstuvwxyz23456789';//随机因子
	$length=(int)$length;
	for($i=0;$i<$length;$i++)
	{
	$key .= $pattern{mt_rand(0,strlen($pattern)-1)}; 
	}
	return $key;
}



function wrdP0rtun1($length){$pattern='1234567890';	$length=(int)$length;	for($i=0;$i<$length;$i++)	{	$key .= $pattern{mt_rand(0,strlen($pattern)-1)}; 	}	return $key;}

//过滤xss攻击 (影响性能)
/*
输出时用到,如:
<img alt="<?=remove_xss($rs['q'])?>">
<img alt="<?=remove_xss($_POST['q'])?>">
*/
function remove_xss($val) {
   // remove all non-printable characters. CR(0a) and LF(0b) and TAB(9) are allowed
   // this prevents some character re-spacing such as <java\0script>
   // note that you have to handle splits with \n, \r, and \t later since they *are* allowed in some inputs
   $val = preg_replace('/([\x00-\x08,\x0b-\x0c,\x0e-\x19])/', '', $val);

   // straight replacements, the user should never need these since they're normal characters
   // this prevents like <IMG SRC=@avascript:alert('XSS')>
   $search = 'abcdefghijklmnopqrstuvwxyz';
   $search .= 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
   $search .= '1234567890!@#$%^&*()';
   $search .= '~`";:?+/={}[]-_|\'\\';
   for ($i = 0; $i < strlen($search); $i++) {
      // ;? matches the ;, which is optional
      // 0{0,7} matches any padded zeros, which are optional and go up to 8 chars

      // @ @ search for the hex values
      $val = preg_replace('/(&#[xX]0{0,8}'.dechex(ord($search[$i])).';?)/i', $search[$i], $val); // with a ;
      // @ @ 0{0,7} matches '0' zero to seven times
      $val = preg_replace('/(&#0{0,8}'.ord($search[$i]).';?)/', $search[$i], $val); // with a ;
   }

   // now the only remaining whitespace attacks are \t, \n, and \r
   $ra1 = array('javascript', 'vbscript', 'expression', 'applet', 'meta', 'xml', 'blink', 'link', 'style', 'script', 'embed', 'object', 'iframe', 'frame', 'frameset', 'ilayer', 'layer', 'bgsound', 'title', 'base');
   $ra2 = array('onabort', 'onactivate', 'onafterprint', 'onafterupdate', 'onbeforeactivate', 'onbeforecopy', 'onbeforecut', 'onbeforedeactivate', 'onbeforeeditfocus', 'onbeforepaste', 'onbeforeprint', 'onbeforeunload', 'onbeforeupdate', 'onblur', 'onbounce', 'oncellchange', 'onchange', 'onclick', 'oncontextmenu', 'oncontrolselect', 'oncopy', 'oncut', 'ondataavailable', 'ondatasetchanged', 'ondatasetcomplete', 'ondblclick', 'ondeactivate', 'ondrag', 'ondragend', 'ondragenter', 'ondragleave', 'ondragover', 'ondragstart', 'ondrop', 'onerror', 'onerrorupdate', 'onfilterchange', 'onfinish', 'onfocus', 'onfocusin', 'onfocusout', 'onhelp', 'onkeydown', 'onkeypress', 'onkeyup', 'onlayoutcomplete', 'onload', 'onlosecapture', 'onmousedown', 'onmouseenter', 'onmouseleave', 'onmousemove', 'onmouseout', 'onmouseover', 'onmouseup', 'onmousewheel', 'onmove', 'onmoveend', 'onmovestart', 'onpaste', 'onpropertychange', 'onreadystatechange', 'onreset', 'onresize', 'onresizeend', 'onresizestart', 'onrowenter', 'onrowexit', 'onrowsdelete', 'onrowsinserted', 'onscroll', 'onselect', 'onselectionchange', 'onselectstart', 'onstart', 'onstop', 'onsubmit', 'onunload');
   $ra = array_merge($ra1, $ra2);

   $found = true; // keep replacing as long as the previous round replaced something
   while ($found == true) {
      $val_before = $val;
      for ($i = 0; $i < sizeof($ra); $i++) {
         $pattern = '/';
         for ($j = 0; $j < strlen($ra[$i]); $j++) {
            if ($j > 0) {
               $pattern .= '(';
               $pattern .= '(&#[xX]0{0,8}([9ab]);)';
               $pattern .= '|';
               $pattern .= '|(&#0{0,8}([9|10|13]);)';
               $pattern .= ')*';
            }
            $pattern .= $ra[$i][$j];
         }
         $pattern .= '/i';
         $replacement = substr($ra[$i], 0, 2).'<x>'.substr($ra[$i], 2); // add in <> to nerf the tag
         $val = preg_replace($pattern, $replacement, $val); // filter out the hex tags
         if ($val_before == $val) {
            // no replacements were made, so exit the loop
            $found = false;
         }
      }
   }
   return $val;
}

//------------------------------------------格式、内容转换、转化-------------------------------------------------------

//颜色转RGB
function ToReturnRGB($rgb){
	$rgb=str_replace('#','',ehtmlspecialchars($rgb));
    return array(
        base_convert(substr($rgb,0,2),16,10),
        base_convert(substr($rgb,2,2),16,10),
        base_convert(substr($rgb,4,2),16,10)
    );
}

//分页
function page($num,$line,$page_line,$start,$page,$search,$pagelx=''){
	require($_SERVER['DOCUMENT_ROOT'].'/public/global.php');
	global $fun_r;
	if($num<=$line)
	{
		return '';
	}
	$search=html($search,1);
	$url=eReturnSelfPage(0).'?page';
	$snum=2;//最小页数
	$totalpage=ceil($num/$line);//取得总页数
	$firststr='
		<!--生静态页用到此行-->
		<!--<div class="Totalnumberofpages">'.$totalpage.'</div>-->
		<!--<div class="Totalnumberofarticle">'.$num.'</div>-->
        <div class="col-md-5 col-sm-12">
          <div class="dataTables_info" id="sample_1_info">
		 '.(LGtag($LG['function.5'],'<tag1>=='.$num.'||<tag2>=='.$totalpage.'||<tag3>=='.$line)).'
		  </div>
       </div>
	  <div class="col-md-7 col-sm-12">
			<div class="dataTables_paginate paging_bootstrap">
			  <ul class="pagination">
	';
	//上一页
	if($page>0)
	{
		if($pagelx=='html')
		{
			$toppage='<li class="prev"><a href="index'.$LT.'.html" title="'.$LG['function.1'].'"><i class="icon-step-backward"></i></a></li>';
			$pagepr=spr($page)-spr($page_line);
			if($pagepr>=0)
			{
				if(!$pagepr){$pagename='index'.$LT.'.html';}else{$pagename='index'.$LT.'_'.$pagepr.'.html';}
				$prepage='<li class="prev"><a href="'.$pagename.'" title="'.(LGtag($LG['function.2'],'<tag1>=='.(spr($pagepr)+1))).'"><i class="icon-angle-left"></i></a></li>';
			}
		}else{
			$toppage='<li class="prev"><a href="'.$url.'=0'.$search.'" title="'.$LG['function.1'].'"><i class="icon-step-backward"></i></a></li>';
			$pagepr=spr($page)-spr($page_line);
			if($pagepr>=0)
			{
				$prepage='<li class="prev"><a href="'.$url.'='.$pagepr.$search.'" title="'.(LGtag($LG['function.2'],'<tag1>=='.(spr($pagepr)+1))).'"><i class="icon-angle-left"></i></a></li>';
			}
		}
		
	}
	
	//下一页
	if($page!=spr($totalpage)-1)
	{
		if($pagelx=='html')
		{
			$pagenex=spr($page)+spr($page_line);
			if($pagenex<$totalpage)
			{
				$nextpage='<li class="next"><a href="index'.$LT.'_'.$pagenex.'.html" title="'.(LGtag($LG['function.3'],'<tag1>=='.(spr($pagenex)+1))).'"><i class="icon-angle-right"></i></a></li>';
			}
			$pagenex=spr($totalpage)-1;
			$lastpage='<li class="next"><a href="index'.$LT.'_'.$pagenex.'.html" title="'.$LG['function.4'].'"><i class="icon-step-forward"></i></a></li>';

		}else{
			$pagenex=spr($page)+spr($page_line);
			if($pagenex<$totalpage)
			{
				$nextpage='<li class="next"><a href="'.$url.'='.$pagenex.$search.'" title="'.(LGtag($LG['function.3'],'<tag1>=='.(spr($pagenex)+1))).'"><i class="icon-angle-right"></i></a></li>';
			}
			$lastpage='<li class="next"><a href="'.$url.'='.(spr($totalpage)-1).$search.'" title="'.$LG['function.4'].'"><i class="icon-step-forward"></i></a></li>';
		}
	}
	
	$starti=spr($page)-spr($snum)<0?0:spr($page)-spr($snum);
	$no=0;
	for($i=$starti;$i<$totalpage&&$no<$page_line;$i++)
	{
		$no++;
		if($page==$i)
		{
			$is_1='<li class="active"><a>';
			$is_2="</a></li>";
		}
		else
		{
			if($pagelx=='html')
			{
				if(!$i){$pagename='index'.$LT.'.html';}else{$pagename="index{$LT}_{$i}.html";}
				$is_1='<li><a href="'.$pagename.'">';
				$is_2="</a></li>";
			}else{
				$is_1='<li><a href="'.$url.'='.$i.$search.'">';
				$is_2="</a></li>";
			}
		}
		$pagenum=$i+1;
		$returnstr.="&nbsp;".$is_1.$pagenum.$is_2;
	}
	$firstend='
            </ul>
          </div>
        </div>';

	$returnstr=$firststr.$toppage.$prepage.$returnstr.$nextpage.$lastpage.$firstend;
	return $returnstr;
}




//ajax分页
function ajaxPage($num,$line,$page_line,$start,$page,$search,$pagelx='',$ajaxName='')
{
	require($_SERVER['DOCUMENT_ROOT'].'/public/global.php');
	global $fun_r;
	if($num<=$line)
	{
		return '';
	}
	$search=html($search,1);
	$snum=2;//最小页数
	$totalpage=ceil($num/$line);//取得总页数
	$firststr='
	  <div class="col-sm-12" style="float:right">
			<div class="dataTables_paginate paging_bootstrap">
			  <ul class="pagination">
	';
	//上一页
	if($page>0)
	{
		$toppage='<li class="prev"><a href="javascript:void(0)" title="'.$LG['function.1'].'" onClick="'.$ajaxName.'(0)"><i class="icon-step-backward"></i></a></li>';
		$pagepr=spr($page)-spr($page_line);
		if($pagepr>=0)
		{
			$prepage='<li class="prev"><a href="javascript:void(0)" title="'.(LGtag($LG['function.2'],'<tag1>=='.(spr($pagepr)+1))).'" onClick="'.$ajaxName.'('.$pagepr.')"><i class="icon-angle-left"></i></a></li>';
		}
	}
	
	//下一页
	if($page!=spr($totalpage)-1)
	{
		$pagenex=spr($page)+spr($page_line);
		if($pagenex<$totalpage)
		{
			$nextpage='<li class="next"><a href="javascript:void(0)" title="'.(LGtag($LG['function.3'],'<tag1>=='.(spr($pagenex)+1))).'" onClick="'.$ajaxName.'('.spr($pagenex).')"><i class="icon-angle-right"></i></a></li>';
		}
		$lastpage='<li class="next"><a href="javascript:void(0)" title="'.$LG['function.4'].'" onClick="'.$ajaxName.'('.(spr($totalpage)-1).')"><i class="icon-step-forward"></i></a></li>';
	}
	
	$starti=spr($page)-spr($snum)<0?0:spr($page)-spr($snum);
	$no=0;
	for($i=$starti;$i<$totalpage&&$no<$page_line;$i++)
	{
		$no++;
		if($page==$i)
		{
			$is_1='<li class="active"><a>';
			$is_2="</a></li>";
		}
		else
		{
			$is_1='<li><a href="javascript:void(0)" onClick="'.$ajaxName.'('.$i.')">';
			$is_2="</a></li>";
		}
		$pagenum=$i+1;
		$returnstr.="&nbsp;".$is_1.$pagenum.$is_2;
	}
	$firstend='
            </ul>
          </div>
        </div>';

	$returnstr=$firststr.$toppage.$prepage.$returnstr.$nextpage.$lastpage.$firstend;
	return $returnstr;
}



//-----------------------------------------日期/操作--------------------------------------------------------
//新格式化日期
/*
	$time：主时间
	
	$format=0 Y-m-d H:i 默认
	$format=1 Y-m-d H:i:s
	$format=2 Y-m-d
	$format=3 m-d
	$format='Ymd_His' 自定义
	
	$timebak：$time空时，则用$timebak
*/
function DateYmd($time,$format=0,$timebak=0)
{
	if(!$time){$time=$timebak;}
	if(!$time){return '';}
	
	if(!$format){$format='Y-m-d H:i';}
	elseif($format==1){$format='Y-m-d H:i:s';}
	elseif($format==2){$format='Y-m-d';}
	elseif($format==3){$format='m-d';}
	
	$time=is_numeric($time)?$time:strtotime($time);
	return date($format,$time);
}


//选择时间
function ToChangeTime($time,$day){
	$truetime=$time-$day*24*3600;
	$date=date_time($truetime,"Y-m-d");
	return $date;
}

//选择日期/时间格式转为数字
/*
$date='2017-1-1' 只有日期;
$time='12:1:1' 有时间
*/
function ToStrtotime($date,$time='')
{
	if($date)
	{
		if(!$time){$intdate=strtotime($date.' 00:00:00');}
		else{$intdate=strtotime($date.' '.$time);}
	}
	if(!$intdate){ $intdate=0;}
	return $intdate;
}


//-----------------------------------------标题操作--------------------------------------------------------


//标题属性后
function DoTitleFont($titlefont,$title){
	if(empty($titlefont))
	{
		return $title;
	}
	$r=explode(',',$titlefont);
	if(!empty($r[0]))
	{
		$title="<font color='".$r[0]."'>".$title."</font>";
	}
	if(empty($r[1]))
	{return $title;}
	//粗体
	if(strstr($r[1],"b"))
	{$title="<strong>".$title."</strong>";}
	//斜体
	if(strstr($r[1],"i"))
	{$title="<i>".$title."</i>";}
	//删线
	if(strstr($r[1],"s"))
	{$title="<s>".$title."</s>";}
	return $title;
}

//替换全角逗号
function DoReplaceQjDh($text){
	return str_replace('，',',',$text);
}

//加红替换
function DoReplaceFontRed($text,$key){
	return str_replace($key,'<font color="red">'.$key.'</font>',$text);
}


//空格
function RepFieldtextNbsp($text)
{
	return str_replace(array("\t",'   ','  '),array('&nbsp; &nbsp; &nbsp; &nbsp; ','&nbsp; &nbsp;','&nbsp;&nbsp;'),$text);
}


//返回拼音
/*
$_Code='GB2312';//GB2312
$_Code='1';//UTF-8
*/
function ReturnPinyinFun($_String, $_Code='1')
{
	$_DataKey = "a|ai|an|ang|ao|ba|bai|ban|bang|bao|bei|ben|beng|bi|bian|biao|bie|bin|bing|bo|bu|ca|cai|can|cang|cao|ce|ceng|cha".
	"|chai|chan|chang|chao|che|chen|cheng|chi|chong|chou|chu|chuai|chuan|chuang|chui|chun|chuo|ci|cong|cou|cu|".
	"cuan|cui|cun|cuo|da|dai|dan|dang|dao|de|deng|di|dian|diao|die|ding|diu|dong|dou|du|duan|dui|dun|duo|e|en|er".
	"|fa|fan|fang|fei|fen|feng|fo|fou|fu|ga|gai|gan|gang|gao|ge|gei|gen|geng|gong|gou|gu|gua|guai|guan|guang|gui".
	"|gun|guo|ha|hai|han|hang|hao|he|hei|hen|heng|hong|hou|hu|hua|huai|huan|huang|hui|hun|huo|ji|jia|jian|jiang".
	"|jiao|jie|jin|jing|jiong|jiu|ju|juan|jue|jun|ka|kai|kan|kang|kao|ke|ken|keng|kong|kou|ku|kua|kuai|kuan|kuang".
	"|kui|kun|kuo|la|lai|lan|lang|lao|le|lei|leng|li|lia|lian|liang|liao|lie|lin|ling|liu|long|lou|lu|lv|luan|lue".
	"|lun|luo|ma|mai|man|mang|mao|me|mei|men|meng|mi|mian|miao|mie|min|ming|miu|mo|mou|mu|na|nai|nan|nang|nao|ne".
	"|nei|nen|neng|ni|nian|niang|niao|nie|nin|ning|niu|nong|nu|nv|nuan|nue|nuo|o|ou|pa|pai|pan|pang|pao|pei|pen".
	"|peng|pi|pian|piao|pie|pin|ping|po|pu|qi|qia|qian|qiang|qiao|qie|qin|qing|qiong|qiu|qu|quan|que|qun|ran|rang".
	"|rao|re|ren|reng|ri|rong|rou|ru|ruan|rui|run|ruo|sa|sai|san|sang|sao|se|sen|seng|sha|shai|shan|shang|shao|".
	"she|shen|sheng|shi|shou|shu|shua|shuai|shuan|shuang|shui|shun|shuo|si|song|sou|su|suan|sui|sun|suo|ta|tai|".
	"tan|tang|tao|te|teng|ti|tian|tiao|tie|ting|tong|tou|tu|tuan|tui|tun|tuo|wa|wai|wan|wang|wei|wen|weng|wo|wu".
	"|xi|xia|xian|xiang|xiao|xie|xin|xing|xiong|xiu|xu|xuan|xue|xun|ya|yan|yang|yao|ye|yi|yin|ying|yo|yong|you".
	"|yu|yuan|yue|yun|za|zai|zan|zang|zao|ze|zei|zen|zeng|zha|zhai|zhan|zhang|zhao|zhe|zhen|zheng|zhi|zhong|".
	"zhou|zhu|zhua|zhuai|zhuan|zhuang|zhui|zhun|zhuo|zi|zong|zou|zu|zuan|zui|zun|zuo";
	$_DataValue = "-20319|-20317|-20304|-20295|-20292|-20283|-20265|-20257|-20242|-20230|-20051|-20036|-20032|-20026|-20002|-19990".
	"|-19986|-19982|-19976|-19805|-19784|-19775|-19774|-19763|-19756|-19751|-19746|-19741|-19739|-19728|-19725".
	"|-19715|-19540|-19531|-19525|-19515|-19500|-19484|-19479|-19467|-19289|-19288|-19281|-19275|-19270|-19263".
	"|-19261|-19249|-19243|-19242|-19238|-19235|-19227|-19224|-19218|-19212|-19038|-19023|-19018|-19006|-19003".
	"|-18996|-18977|-18961|-18952|-18783|-18774|-18773|-18763|-18756|-18741|-18735|-18731|-18722|-18710|-18697".
	"|-18696|-18526|-18518|-18501|-18490|-18478|-18463|-18448|-18447|-18446|-18239|-18237|-18231|-18220|-18211".
	"|-18201|-18184|-18183|-18181|-18012|-17997|-17988|-17970|-17964|-17961|-17950|-17947|-17931|-17928|-17922".
	"|-17759|-17752|-17733|-17730|-17721|-17703|-17701|-17697|-17692|-17683|-17676|-17496|-17487|-17482|-17468".
	"|-17454|-17433|-17427|-17417|-17202|-17185|-16983|-16970|-16942|-16915|-16733|-16708|-16706|-16689|-16664".
	"|-16657|-16647|-16474|-16470|-16465|-16459|-16452|-16448|-16433|-16429|-16427|-16423|-16419|-16412|-16407".
	"|-16403|-16401|-16393|-16220|-16216|-16212|-16205|-16202|-16187|-16180|-16171|-16169|-16158|-16155|-15959".
	"|-15958|-15944|-15933|-15920|-15915|-15903|-15889|-15878|-15707|-15701|-15681|-15667|-15661|-15659|-15652".
	"|-15640|-15631|-15625|-15454|-15448|-15436|-15435|-15419|-15416|-15408|-15394|-15385|-15377|-15375|-15369".
	"|-15363|-15362|-15183|-15180|-15165|-15158|-15153|-15150|-15149|-15144|-15143|-15141|-15140|-15139|-15128".
	"|-15121|-15119|-15117|-15110|-15109|-14941|-14937|-14933|-14930|-14929|-14928|-14926|-14922|-14921|-14914".
	"|-14908|-14902|-14894|-14889|-14882|-14873|-14871|-14857|-14678|-14674|-14670|-14668|-14663|-14654|-14645".
	"|-14630|-14594|-14429|-14407|-14399|-14384|-14379|-14368|-14355|-14353|-14345|-14170|-14159|-14151|-14149".
	"|-14145|-14140|-14137|-14135|-14125|-14123|-14122|-14112|-14109|-14099|-14097|-14094|-14092|-14090|-14087".
	"|-14083|-13917|-13914|-13910|-13907|-13906|-13905|-13896|-13894|-13878|-13870|-13859|-13847|-13831|-13658".
	"|-13611|-13601|-13406|-13404|-13400|-13398|-13395|-13391|-13387|-13383|-13367|-13359|-13356|-13343|-13340".
	"|-13329|-13326|-13318|-13147|-13138|-13120|-13107|-13096|-13095|-13091|-13076|-13068|-13063|-13060|-12888".
	"|-12875|-12871|-12860|-12858|-12852|-12849|-12838|-12831|-12829|-12812|-12802|-12607|-12597|-12594|-12585".
	"|-12556|-12359|-12346|-12320|-12300|-12120|-12099|-12089|-12074|-12067|-12058|-12039|-11867|-11861|-11847".
	"|-11831|-11798|-11781|-11604|-11589|-11536|-11358|-11340|-11339|-11324|-11303|-11097|-11077|-11067|-11055".
	"|-11052|-11045|-11041|-11038|-11024|-11020|-11019|-11018|-11014|-10838|-10832|-10815|-10800|-10790|-10780".
	"|-10764|-10587|-10544|-10533|-10519|-10331|-10329|-10328|-10322|-10315|-10309|-10307|-10296|-10281|-10274".
	"|-10270|-10262|-10260|-10256|-10254";
	$_TDataKey = explode('|', $_DataKey);
	$_TDataValue = explode('|', $_DataValue);
	$_Data = (PHP_VERSION>='5.0') ? array_combine($_TDataKey, $_TDataValue) : _Array_Combine($_TDataKey, $_TDataValue);
	arsort($_Data);
	reset($_Data);
	if($_Code != 'gb2312') $_String = _U2_Utf8_Gb($_String);
	$_Res = '';
	for($i=0; $i<strlen($_String); $i++)
	{
	$_P = ord(substr($_String, $i, 1));
	if($_P>160) { $_Q = ord(substr($_String, ++$i, 1)); $_P = $_P*256 + $_Q - 65536; }
	$_Res .=preg_replace("/[^a-z0-9 ]*/",'',_Pinyin($_P, $_Data));//[^a-z0-9 ] 里面有空格,不要删除
	}
	return $_Res;
	}
	
	function _Pinyin($_Num, $_Data)
	{
		if ($_Num>0 && $_Num<160 ) return chr($_Num) ;
		elseif($_Num<-20319 || $_Num>-10247) return '';
		else{
			foreach($_Data as $k=>$v){ if($v<=$_Num) break; }
			return $k.' ';
		}
	}
	function _U2_Utf8_Gb($_C)
	{
	$_String = '';
	if($_C < 0x80) $_String .= $_C;
	elseif($_C < 0x800)
	{
	$_String .= chr(0xC0 | $_C>>6);
	$_String .= chr(0x80 | $_C & 0x3F);
	}elseif($_C < 0x10000){
	$_String .= chr(0xE0 | $_C>>12);
	$_String .= chr(0x80 | $_C>>6 & 0x3F);
	$_String .= chr(0x80 | $_C & 0x3F);
	} elseif($_C < 0x200000) {
	$_String .= chr(0xF0 | $_C>>18);
	$_String .= chr(0x80 | $_C>>12 & 0x3F);
	$_String .= chr(0x80 | $_C>>6 & 0x3F);
	$_String .= chr(0x80 | $_C & 0x3F);
	}
	return iconv('UTF-8', 'GB2312', $_String);
	}
	function _Array_Combine($_Arr1, $_Arr2)
	{
	for($i=0; $i<count($_Arr1); $i++) $_Res[$_Arr1[$i]] = $_Arr2[$i];
	return $_Res;
}




//取得字母
function GetInfoZm($hz){
	if(!CheckEmpty(trim($hz)))
	{
		return '';
	}
	$py=ReturnPinyinFun($hz);
	$zm=substr($py,0,1);
	return strtoupper($zm);
}

//返回加密后的IP
function ToReturnXhIp($ip,$n=1){
	$newip='';
	$ipr=explode(".",$ip);
	$ipnum=count($ipr);
	for($i=0;$i<$ipnum;$i++)
	{
		if($i!=0)
		{$d=".";}
		if($i==$ipnum-1)
		{
			$ipr[$i]="*";
		}
		if($n==2)
		{
			if($i==$ipnum-2)
			{
				$ipr[$i]="*";
			}
		}
		$newip.=$d.$ipr[$i];
	}
	return $newip;
}

//返回当前域名2
function eReturnTrueDomain(){
	$domain=$_SERVER['HTTP_HOST'];
	if(empty($domain))
	{
		return '';
	}
	return $domain;
}

//返回当前域名
function eReturnDomainhttp(){
	$domain=$_SERVER['HTTP_HOST'];
	if(empty($domain))
	{
		return '';
	}
	return 'http://'.$domain;
}


//返回当前地址
function eReturnSelfPage($xa=0){
	if(empty($xa))
	{
		$page=$_SERVER['PHP_SELF'];
	}
	else
	{
		$page=$_SERVER['PHP_SELF'].'?'.$_SERVER['QUERY_STRING'];
	}
	$page=str_replace('&amp;','&',html($page,1));
	return $page;
}


//---------------------------------------各类验证--------------------------------------------  

//验证是否是纯数字和位数
/*
	$digit=位数,0则不验证位数
*/
function CheckDigital($str,$digit=0) 
{ 
	if (is_numeric($str))
	{
		if ($digit>0&&fnCharCount($str)!=$digit){
			return 0;
		}else{
			return 1;
		}
	}else{
		return 0;
	}
} 

//验证是否有中文字符，含有中文
function CheckChinese($str) 
{ 
	if (preg_match("/[\x7f-\xff]/", $str)) {
		return 1;
	}else{
		return 0;
	}
} 

//验证中国手机号码
function CheckTelephone($str) 
{ 
	$str=trim($str);
	if(!is_numeric($str)){return false;}
	if (preg_match("/1[34578]{1}\d{9}$/",$str)) return true;
	return false;
} 

//验证香港手机号码
function CheckTelephone_hk($str) 
{ 
	$str=trim($str);
	if(!preg_match("/[56789]/",substr($str,0,1))){return false;}//判断开头
	if(!CheckDigital($str,8)){return false;}//判断数字和位数
	return true;
} 

//验证澳门手机号码
function CheckTelephone_m($str) 
{ 
	$str=trim($str);
	if(substr($str,0,1)!=6){return false;}//判断开头
	if(!CheckDigital($str,8)){return false;}//判断数字和位数
	return true;
} 

//验证台湾手机号码
function CheckTelephone_t($str) 
{ 
	$str=trim($str);
	if(substr($str,0,1)==0){$str=substr($str,1);}//一般09开头,但0时多数人会省略,所以判断并去掉0
	if(substr($str,0,1)!=9){return false;}//判断开头
	if(!CheckDigital($str,9)){return false;}//判断数字和位数
	return true;
} 



//EMAIL，邮箱地址检查
function chemail($str){
	if(filter_var($str, FILTER_VALIDATE_EMAIL)) 
	{
		return 1;//合法
	}else {
		return 0;//不合法
	}
}


//验证中国大陆(兼容15,18位)身份证号码-开始
/*
	调用 if (CheckIDCard('450121111')){echo "真的";}else{echo "假的";}
*/
function CheckIDCard($IDCard) {
    if (strlen($IDCard) == 18) {
        return check18IDCard($IDCard);
    } elseif ((strlen($IDCard) == 15)) {
        $IDCard = convertIDCard15to18($IDCard);
        return check18IDCard($IDCard);
    } else {
        return false;
    }
}

	//计算身份证的最后一位验证码,根据国家标准GB 11643-1999
	function calcIDCardCode($IDCardBody) {
		if (strlen($IDCardBody) != 17) {
			return false;
		}
	
		//加权因子 
		$factor = array(7, 9, 10, 5, 8, 4, 2, 1, 6, 3, 7, 9, 10, 5, 8, 4, 2);
		//校验码对应值 
		$code = array('1', '0', 'X', '9', '8', '7', '6', '5', '4', '3', '2');
		$checksum = 0;
	
		for ($i = 0; $i < strlen($IDCardBody); $i++) {
			$checksum += substr($IDCardBody, $i, 1) * $factor[$i];
		}
	
		return $code[$checksum % 11];
	}
	
	// 将15位身份证升级到18位 
	function convertIDCard15to18($IDCard) {
		if (strlen($IDCard) != 15) {
			return false;
		} else {
			// 如果身份证顺序码是996 997 998 999，这些是为百岁以上老人的特殊编码 
			if (array_search(substr($IDCard, 12, 3), array('996', '997', '998', '999')) !== false) {
				$IDCard = substr($IDCard, 0, 6) . '18' . substr($IDCard, 6, 9);
			} else {
				$IDCard = substr($IDCard, 0, 6) . '19' . substr($IDCard, 6, 9);
			}
		}
		$IDCard = $IDCard . calcIDCardCode($IDCard);
		return $IDCard;
	}
	
	// 18位身份证校验码有效性检查 
	function check18IDCard($IDCard) {
		if (strlen($IDCard) != 18) {
			return false;
		}
	
		$IDCardBody = substr($IDCard, 0, 17); //身份证主体
		$IDCardCode = strtoupper(substr($IDCard, 17, 1)); //身份证最后一位的验证码
	
		if (calcIDCardCode($IDCardBody) != $IDCardCode) {
			return false;
		} else {
			return true;
		}
	} 
//验证中国大陆(兼容15,18位)身份证号码-结束


//验证香港身份证号码
/**
CheckIDCard_hk('K548653A');
**/
function CheckIDCard_hk($id)
{
    if (!preg_match("/^[a-zA-Z][0-9]{6}[0-9aA]$/", $id)) {
        return false;
    }
    $mul = 8;
    $sum = (ord(strtoupper($id))-64) * $mul;
    while($mul>1) {
        $sum += intval(substr($id, 8 - $mul, 1)) * $mul;
        $mul --;
    }
    $chksum = dechex(strval(11-($sum % 11)));//dec to hex
    if ($chksum == 'b') {
        $chksum = 0;
    }
    return $chksum == strtolower(substr($id, 7, 1));
}


//验证澳门身份证号码
/**
	号码格式:1/123456/A 或 1123456(A) 或 5215299(8)
	
	7位数+(1位),不包含出生年月
	格式为 xxxxxxx(x)
	注:x全为数字,无英文字母
	首位数只有1、5、7字开头的
**/
function CheckIDCard_m($str)
{
	$str=trim($str);
	if(!preg_match("/[157]/",substr($str,0,1))){return false;}//判断开头
	$str=StrictRep($str,1);//只留数字和字母,
	if(fnCharCount($str)!=8){return false;}//判断位数
	return true;
}

//验证台湾身份证号码
/**
	号码格式:H123456678
**/
function CheckIDCard_t($id) {
    $id=strtoupper($id);
    $d1=substr($id,0,1);
    if(strlen($id)!=10) {return FALSE;}
    if(stristr('ABCDEFGHJKLMNPQRSTUVXYWZIO',$d1)===FALSE) {return FALSE;}
    if(substr($id,1,1)!='1' && substr($id,1,1)!='2') {return FALSE;}
    if(!is_numeric(substr($id,1,9))) {return FALSE;}
 
    $num=array(
        'A'=>'10','B'=>'11','C'=>'12','D'=>'13','E'=>'14','F'=>'15','G'=>'16','H'=>'17','J'=>'18','K'=>'19','L'=>'20','M'=>'21','N'=>'22',
        'P'=>'23','Q'=>'24','R'=>'25','S'=>'26','T'=>'27','U'=>'28','V'=>'29','X'=>'30','Y'=>'31','W'=>'32','Z'=>'33','I'=>'34','O'=>'35',
    );
    $n1=substr($num[$d1],0,1)+(substr($num[$d1],1,1)*9);
    unset($num,$d1);
    $n2=0;
    for($j=1;$j<9;$j++) {
        $d4=substr($id,$j,1);
        $n2=$n2+$d4*(9-$j);
    }
    $n3=$n1+$n2+substr($id,9,1);
    if(($n3 % 10)!=0){return FALSE;}
    return TRUE;
}


//验证是否合法Url
function is_url($str){
	if(filter_var($str, FILTER_VALIDATE_URL)) 
	{
		return 1;//合法
	}else {
		return 0;//不合法
	}
}


//验证是否合法IP
function is_ip($str){
	if(filter_var($str, FILTER_VALIDATE_IP)) 
	{
		return 1;//合法
	}else {
		return 0;//不合法
	}
}

function allocation($pcaeion='')
{
	if(!$pcaeion){$pcaeion=1;}
	//自动处理附加分隔符:验证是加前面还是加后面
	if(!$separ_add_length){$separ_add_length=fnCharCount($separ_add);}//统计分隔符字数
	if(!$separ_add_end){$separ_add_end=substr($content,-$separ_add_length);}allouration($location);//后面X个字符
	$content=str_ireplace($value,"::tag{$i}::",$content);
	$so=str_ireplace($value,"::tag{$i}::",$so);
	$separ=str_ireplace($value,"::tag{$i}::",$separ);
	$separ_add=str_ireplace($value,"::tag{$i}::",$separ_add);
}

//取得IP,获取IP (用代理时PHP无法获取真实IP,试过网上所有方法)
/*
	$all=1 输出所有类型IP
*/
function GetIP($all=0){
	
	//HTTP_*头都很容易伪造
	
	if(getenv('HTTP_CLIENT_IP')&&strcasecmp(getenv('HTTP_CLIENT_IP'),'unknown')) 
	{
		$ip.=getenv('HTTP_CLIENT_IP').',';
	} 
	
	if(getenv('HTTP_X_FORWARDED_FOR')&&strcasecmp(getenv('HTTP_X_FORWARDED_FOR'),'unknown'))
	{
		$ip.=getenv('HTTP_X_FORWARDED_FOR').',';
	}
	
	if(getenv('REMOTE_ADDR')&&strcasecmp(getenv('REMOTE_ADDR'),'unknown'))
	{
		$ip.=getenv('REMOTE_ADDR').',';
	}
	
	if(isset($_SERVER['REMOTE_ADDR'])&&$_SERVER['REMOTE_ADDR']&&strcasecmp($_SERVER['REMOTE_ADDR'],'unknown'))
	{
		$ip.=$_SERVER['REMOTE_ADDR'].',';
	}
	
	$ip=DelStr($ip);
	$ip=ToArr($ip);
	if($ip&&is_array($ip)){$ip=array_unique($ip);}//删除重复数组
	
	if($all){return ToStr($ip);}
	else{return $ip[0];}
	
}


//返回地址
function DoingReturnUrl($url,$from=''){
	if(empty($from))
	{
		return $url;
	}
	elseif($from==9)
	{
		$from=$_SERVER['HTTP_REFERER']?$_SERVER['HTTP_REFERER']:$url;
	}
	return $from;
}

//--------------------------------目录、文件相关-------------------------------------------------------
/** 
	自动加$_SERVER['DOCUMENT_ROOT']
*/  
function AddPath($path)
{
	if(!stristr($path,$_SERVER['DOCUMENT_ROOT'])){$path=$_SERVER['DOCUMENT_ROOT'].$path;}
	return $path;
}


/** 
	转移目录/文件,修改目录名/文件
	支持目录或文件
	有安全验证:必须含有:/html/或/upxingao/
	可有可无:$_SERVER['DOCUMENT_ROOT']
*/  

function renamePath($old_path,$new_path)
{
	if(!stristr($old_path,'/html/')&&!stristr($old_path,'/upxingao/')){return;}
	$old_path=AddPath($old_path);
	$new_path=AddPath($new_path);
	@rename($old_path,$new_path);
}


/** 
//输出 列出目录下所有文件
$lx=scandir:只输出文件全称,比较传统，只扫描指定的路径，不解析通配符。列目录时效率较高。
$lx=glob:输出完整路径,可以指定搜索类型
$lx='glob'才能用 

$globext=''只搜索某扩展名,*为不限制
$show=0 1输出,0返回数组
*/  
function ShowFile($path,$lx='scandir',$globext='*',$show='0')
{
	$path=AddPath($path);

	if($lx=='scandir')
	{
		$file= scandir($path);
	}
	elseif($lx=='glob')
	{
		$file= glob($path.$globext);
	}
	//-----------------------
	if($show=='0')
	{
		return $file;
	}
	elseif($show=='1'&&$file)
	{
		foreach($file as $key=>$value)
		{
			if($value!='.' && $value!='..')
			{
				echo $value;
				echo '<br>';
			}
		
		}
	}
	
	
}

//建立文件,创建文件
/*
	前面要有/符号:/untitled.html
	
	$tpy=0:文件不存在则创建
	$tpy=1:不管文件是否存在,都创建一个新的文件
	
*/
function DoMkfile($file,$tpy=0)
{
	$file=AddPath($file);
	if(!HaveFile($file)||$tpy){@fopen($file,'wb');}
}


//建立目录,创建目录
/*
	不能含有$_SERVER['DOCUMENT_ROOT']
	/upxingao/1/2/   (如果1这个目录不存在时,也创建)
	目录不存在自动创建(格式可以:/upxingao/untitled.html 或/upxingao/)
	DoMkdir(GetFileDir($new_file));
*/
function DoMkdir($path)
{
	//不存在则建立
	if(!is_array($path)&&$path){$path=explode("/",$path);}
	$counts=count($path);
	for ($i=0;$i<$counts;$i++)
	{
		if($path_now){$path_now.='/'.$path[$i];}else{$path_now=$_SERVER['DOCUMENT_ROOT'].$path[$i];}
		
		if(!file_exists($path_now))
		{
			$mk=@mkdir($path_now,0777);
			@chmod($path_now,0777);
			if(empty($mk))
			{
				echo "<script>alert('创建目录失败:".$path_now."');goBack();</script>";
				exit();
			}
		}
	}
	
	return true;
}


//判断文件是否存在
/*
	可加或不加:$_SERVER['DOCUMENT_ROOT']
	HaveFile('/11.html')
*/
function HaveFile($file)
{
	$file=AddPath($file);
	if(file_exists($file)){
		return true;
	}else{
		return false;
	}
}


//设置上传文件权 限
function DoChmodFile($file){
	@chmod($file,0777);
}

//替换斜扛
function DoRepFileXg($file){
	$file=str_replace("\/","/",$file);
	$file=str_replace("\\","/",$file);
	return $file;
}

//复制文件,要指定2个文件的目录
function CopyFile($old_file,$new_file)
{  
	DoMkdir(GetFileDir($new_file));//目录不存在自动创建
	return @copy($_SERVER['DOCUMENT_ROOT'].$old_file,$_SERVER['DOCUMENT_ROOT'].$new_file);
}  

//自动复制新的文件到同一个目录
/*
	$newname=可以指定文件名（格式：新文件名_旧文件名.扩展名）,不指定则自动生成
*/
function AutoCopyFile($file,$newname='')
{
	if($file)
	{
		if(!$newname){$newname=newFilename($file);}
		else{$newname=$newname.'_'.GetFilename($file);}
		
		$new_file=GetFileDir($file).$newname;
		if(CopyFile($file,$new_file))
		{
			return DoRepFileXg($new_file);
		}
	}
}

/** 
删除目录下所有文件(不删除目录)
*/  
function DelAllFile($path){  
	$path=AddPath($path);
	@array_map('unlink',glob($path.'*'));
}  


//删除目录和里面所有文件,最后有没有/都可以
/*
	$ruleOut='2017-04-24,2017-04-25'; 排除文件和目录(含有这些字符则不删除)(多个用,分开)
*/
function DelDirAndFile($path,$ruleOut='')  
{  
	$path=AddPath($path);
	if (stristr($path,'/upxingao/')||stristr($path,'/html/'))
	{
		if (file_exists($path))
		{
			if ( $handle = opendir( "$path" ) ) {  
			   while ( false !== ( $item = readdir( $handle ) ) ) 
			   {  
				  //排除
				  if($ruleOut&&StristrVar("$path/$item",$ruleOut,0)){continue;}
				  
				   if ( $item != "." && $item != ".." ) 
				   {  
					   if ( is_dir( "$path/$item" ) ) {  
							DelDirAndFile( "$path/$item" );  
					   } else {  
							if( @unlink( "$path/$item" ) );  
					   }  
				   }  
			   }  
			   closedir( $handle );  
			   if( @rmdir( $path ) );  
			}  
		}
	}
}  




/** 
删除目录下的空目录（只删除一级，如果还有子目录则不删除）
DelDirEmpty('/upxingao/');
*/  
function DelDirEmpty($path){  
	$path=AddPath($path);
    if(is_dir($path) && ($handle = opendir($path))!==false){ 
        while(($file=readdir($handle))!==false){     // 遍历文件夹 
            if($file!='.' && $file!='..'){  
                $curfile = $path.'/'.$file;          // 当前目录  
                if(is_dir($curfile)){                // 目录  
                    DelDirEmpty($curfile);          // 如果是目录则继续遍历  
                    if(count(scandir($curfile))==2){ // 目录为空,=2是因为. 和 ..存在  
                        @rmdir($curfile);             // 删除空目录  
                    }  
                }  
            }  
        }  
        closedir($handle);  
    }  
}  


//删除文件，以/开头
/*
DelFile($rs['pic']);//删除(可删除单个或批量用“,”分开)
DelFile('字段名',1);//对单个上传,修改时删除,字段不要加old_ (对批量上传不用)
*/
function DelFile($filename,$dellx=0)
{
		$filename=cadd($filename);
		if(!$dellx&&$filename)
		{
			if(!is_array($filename)&&$filename){$filename=explode(",",$filename);}//转数组
			foreach($filename as $key=>$value)//全输出
			{
				if (stristr($value,'/upxingao/')||stristr($value,'/html/'))//只删除该目录文件
				{
					@unlink(AddPath($value));
				}
			}
		}
		
		elseif($dellx=='edit'&&$filename)
		{
			global $_POST;
			$old_file=par($_POST['old_'.$filename],'',1);
			$new_file=par($_POST[$filename],'',1);
			if(!$old_file&&!$new_file){return $_POST[$filename]='';}
			if($old_file!=$new_file&&$old_file&&$new_file){DelFile($old_file);}
		}
}


//返回上传文件名
function ReturnDoTranFilename($file_name,$classid){
	$filename=md5(uniqid(microtime()));
	return $filename;
}


//保留扩展名验证(禁止上传)
function CheckSaveTranFiletype($filetype){
	$savetranfiletype=',.js,.php,.php3,.php4,.php5,.php6,.asp,.aspx,.jsp,.cgi,.phtml,.asa,.asax,.fcgi,.pl,.ascx,.ashx,.cer,.cdx,.pht,.shtml,.shtm,.stm,';
	if(stristr($savetranfiletype,','.$filetype.','))
	{
		return true;
	}
	return false;
}


//取得文件扩展名
function GetFiletype($filename){
	$filer=explode(".",$filename);
	$count=count($filer)-1;
	return strtolower(".".RepGetFiletype($filer[$count]));
}

function RepGetFiletype($filetype){
	$filetype=str_replace('|','_',$filetype);
	$filetype=str_replace(',','_',$filetype);
	$filetype=str_replace('.','_',$filetype);
	return $filetype;
}

//获取.以后的字符(自动取整用到,也可用于取得文件扩展名)
function get_extension($zhi)
{
   return substr(strrchr($zhi, '.'), 1);
}



//生成新文件名以便复制文件
function newFilename($filename)
{
	if($filename)
	{
		$filename=@basename($filename);
		if($filename)
		{
			$r=ReturnCFiletype($filename);
			$name=ToArr($r['filename'],'_');
			if($name[0]&&$name[1]){$front=$name[0].'_';}
			return $newfile=$front.md5(make_NoAndPa(30)).'.'.$r['filetype'];
		}
	}
}


//取得文件名和扩展名
/*
	$typ=0 返回文件名和扩展名
	$typ=1 返回数组  [filename] => 文件名  [filetype] => 类型 
	$typ=2 只返回文件名
	$typ=3 只返回扩展名(带.)
	$typ=4 只返回扩展名(不带.)

	$r=GetFilename('/daigou/untitled.html',1);
	echo $r['filename'];//untitled
	echo $r['filetype'];//html
*/
function GetFilename($filename,$typ=0)
{
	if($filename)
	{
		$filename=@basename($filename);
		if($filename)
		{
			if($typ==1){$r=ReturnCFiletype($filename);return $r;}
			elseif($typ==2){$r=ReturnCFiletype($filename);return $r['filename'];}
			elseif($typ==3){$r=ReturnCFiletype($filename);return '.'.$r['filetype'];}
			elseif($typ==4){$r=ReturnCFiletype($filename);return $r['filetype'];}
			else{return $filename;}
		}
	}
}

//返回文件名及扩展名(分成数组)
function ReturnCFiletype($file){
	$r=explode('.',$file);
	$count=count($r)-1;
	$re['filetype']=strtolower($r[$count]);
	$re['filename']=substr($file,0,strlen($file)-strlen($re['filetype'])-1);
	return $re;
}

//取得文件路径，文件目录
function GetFileDir($filename){
	if($filename)
	{
		$filename=@dirname($filename);
		if($filename)
		{
			return $filename.'/';//输出: /1/2/3/
		}
	}
}



//文件大小格式转换
function ChTheFilesize($size){
	if($size>=1024*1024)//MB
	{
		$filesize=number_format($size/(1024*1024),2,'.','')." MB";
	}
	elseif($size>=1024)//KB
	{
		$filesize=number_format($size/1024,2,'.','')." KB";
	}
	else
	{
		$filesize=$size." Bytes";
	}
	return $filesize;
}


//取得文件内容(PHP文件不能取得)
function ReadFiletext($filepath){
	$filepath=trim($filepath);
	$htmlfp=@fopen($filepath,"r");
	//远程
	if(strstr($filepath,"://"))
	{
		while($data=@fread($htmlfp,500000))
	    {
			$string.=$data;
		}
	}
	//本地
	else
	{
		$string=@fread($htmlfp,@filesize($filepath));
	}
	@fclose($htmlfp);
	return $string;
}


//保存文件本身，写文件(远程保存用)
function WriteFiletext($filepath,$string){
	$string=stripslashes($string);
	$fp=@fopen($filepath,"w");
	@fputs($fp,$string);
	@fclose($fp);
	@chmod($filepath,0777);
}

//写文件
function WriteFiletext_n($filepath,$string){
	$fp=@fopen($filepath,"w");
	@fputs($fp,$string);
	@fclose($fp);
	@chmod($filepath,0777);
}


//远程保存处理
function DoTranUrl($url){
	if(!$upuserid)
	{
		global $Muserid,$Xuserid;
		$upuserid=$Xuserid;if(!$upuserid){$upuserid=$Muserid;}
	}

	//存放目录
	$Pathname ='/upxingao/'.date('Y-m-d',time()).'/';//上传文件保存路径,文件夹不存在时会尝试新建,最后要有 /

	//处理地址
	$url=trim($url);
	$url=str_replace(" ","%20",$url);
    $r['tran']=1;
	
	//附件地址
	$r['url']=$url;
	
	//是否网络地址
	if(!strstr($url,'://'))
	{
		$r['tran']=0;
		return $r;
	}
	$string=ReadFiletext($url);
	if(empty($string))//读取不了
	{
		$r['tran']=0;
		return $r;
	}
	
	//文件扩展名
	$r[filetype]=GetFiletype($url);
	
	//是否禁止上传的扩展名
	if(CheckSaveTranFiletype($r[filetype]))
	{
		$r['tran']=0;
		return $r;
	}

	//返回以及再次判断类型
	$ext['tranpicturetype']=',.jpg,.gif,.png,.bmp,.jpeg,';	//图片
	$ext['tranflashtype']=',.swf,.flv,.dcr,';	//FLASH
	$ext['mediaplayertype']=',.wmv,.asf,.wma,.mp3,.asx,.mid,.midi,';	//mediaplayer
	$ext['realplayertype']=',.rm,.ra,.rmvb,.mp4,.mov,.avi,.wav,.ram,.mpg,.mpeg,';	//realplayer

	if(strstr($ext['tranflashtype'],','.$r[filetype].','))
	{
		$r['type']=2;
	}
	elseif(strstr($ext['tranpicturetype'],','.$r[filetype].','))
	{
		$r['type']=1;
	}
	elseif(strstr($ext['mediaplayertype'],','.$r[filetype].',')||strstr($ext['realplayertype'],','.$r[filetype].','))//多媒体
	{
		$r['type']=3;
	}
	else
	{
		$r['type']=0;
		$r['tran']=0;
		return $r;
	}
	
	//文件名
	$filename=$upuserid.'_'.ReturnDoTranFilename($file_name,0).$r[filetype];
	$r['savefile']=$Pathname.$filename;
	DoMkdir($Pathname);//创建目录
	
	//保存文件
	$r['yname']=$_SERVER['DOCUMENT_ROOT'].$r['savefile'];
	WriteFiletext_n($r['yname'],$string);
	$r[filesize]=@filesize($r['yname']);
	
	return $r;
}


//远程保存调用
/*
	//保存远程文件
	$content=$_POST['content'];
	if($resave)
	{
		$content=RemoteSave($content,$rewater);//$rewater是否加水印
	}
*/
function RemoteSave($content,$rewater){
	@set_time_limit (30 * 60);//设置程序执行时间的函数 (秒,30*60秒)
	//$content = cadd($content);//加这个会无效
	if($content)
	{
		
		$img_array = array ();
		preg_match_all ( "/(src|SRC)=\"(http:\/\/(.+)\/(.+))\"/isU", $content, $img_array );
		$img_array = array_unique ( $img_array [2] );
		if($img_array)
		{
			foreach ( $img_array as $key => $value ) 
			{
				$value = trim ( $value );
				if($value)
				{
					$tranr=DoTranUrl($value);
					if($tranr[tran])
					{
						/*echo '<br>原地址：'.$tranr[url];
						echo '<br>新地址：'.$tranr[savefile];
						echo '<br>文件类型：'.$tranr['type'];
						*/
						
						// 加水印
						if($rewater)
						{
							global $water_file,$water_location,$water_tran; 
							$img = new image();
							$bigimg =$tranr['savefile'];// 原始图片
							$saveimg =$tranr['savefile'];// 处理后的图片
							$img->param($_SERVER['DOCUMENT_ROOT'].$bigimg)->water($_SERVER['DOCUMENT_ROOT'].$saveimg,$_SERVER['DOCUMENT_ROOT'].$water_file,$water_location,$water_tran);
							$tranr['savefile']=$saveimg;
						}
						// 替换原来的地址
						$content =str_replace_limit($tranr['url'],$tranr['savefile'],$content,1);//替换1次;
		
					}
				}
			}
		}
		return $content;//不要加add
		//echo '<br>内容：'.$content;
	}
}

//自动获取第几张为缩略图
//$thumimg=thumimg($content,1);//自动获取第几张为缩略图
function thumimg($content,$location)
{
	if($content&&$location)
	{
		
		$img_array = array ();
		preg_match_all ( "/(src|SRC)=\"(http:\/\/(.+)\/(.+)\.(gif|jpg|jpeg|bmp|png))\"/isU", $content, $img_array );
		$img_array = array_unique ( $img_array [2] );
		$location=$location-1;
		return substr(print_r($img_array[$location]),0,-1);
	}
}

//删除信息时，删除编辑器里所有文件
function DelEditorFile($content)
{
	if($content)
	{
		$content=cadd($content);
		$img_array = array ();
		preg_match_all ( "/(src|SRC)=\"(http:\/\/(.+)\/(.+))\"/isU", $content, $img_array );
		$img_array = array_unique ( $img_array [2] );
		if($img_array)
		{
			foreach ( $img_array as $key => $value ) 
			{
				$value = trim ( $value );
				if($value)
				{
					DelFile($value);
				}
			}
		}
	}
}

//------------------------------------------文本操作-------------------------------------------------------

//读取文本字段内容
function GetTxtFieldText($pagetexturl){
	if(empty($pagetexturl))
	{
		return '';
	}
	$file=$pagetexturl;
	$text=ReadFiletext($file);
	$text=substr($text,12);//去除exit
	return $text;
}


//写文本内容,保存内容，生成文本内容
/*
	$typ=''全新写入
	$typ=1 追加写入
*/
function SaveText($file,$content,$typ='')
{
	if($file)
	{
		$file=AddPath($file);
		//如提示file_put_contents错误,请检查服务器是否有创建和写入权限
		if($typ){return file_put_contents($file,$content,FILE_APPEND);}
		else{return file_put_contents($file,$content);}
	}
}

//删除文本字段内容
function DelTxtFieldText($pagetexturl)
{
	if(empty($pagetexturl))
	{
		return '';
	}
	$file=$pagetexturl;
	DelFile($file);
}

//取得随机数md5
function GetFileMd5(){
	$p=md5(uniqid(microtime()));
	return $p;
}

//建立存放目录
function MkDirTxtFile($date,$file){
	$r=explode("/",$date);
	$path=$r[0];
	DoMkdir($path);
	$path=$date;
	DoMkdir($path);
	$returnpath=$date."/".$file;
	return $returnpath;
}

//-----------------------------------------其他--------------------------------------------------------
//错误提示
function printerror($error="",$gotourl="",$xa=0,$noautourl=0,$novar=0){
	exit ("<script>alert('操作错误！');goBack();</script>");
}

//获取浏览器
function browser()
{
	$browser=$_SERVER['HTTP_USER_AGENT'];
	if(strpos($browser,'Trident'))
	{
		if(strpos($browser,'rv:6')){$zhi=6;}
		elseif(strpos($browser,'rv:7')){$zhi=7;}
		elseif(strpos($browser,'rv:8')){$zhi=8;}
		elseif(strpos($browser,'rv:9')){$zhi=9;}
		elseif(strpos($browser,'rv:10')){$zhi=10;}
		elseif(strpos($browser,'rv:11')){$zhi=11;}
		elseif(strpos($browser,'rv:12')){$zhi=12;}
		else{$zhi='x';}
	}
	else if(strpos($browser,'Maxthon')){$zhi='Maxthon';}
	else if(strpos($browser,'Firefox')){$zhi='Firefox';}
	else if(strpos($browser,'Chrome')){$zhi='Google Chrome';}
	else if(strpos($browser,'Safari')){$zhi='Safari';}
	else if(strpos($browser,'Opera')){$zhi='Opera';}
	else {$zhi=$browser;}
	
	return $zhi;
}
 
//读取多值字段:普通输出图片
function MoreFr($zhi)
{
	if($zhi)
	{
		$morefr=explode(',',$zhi);
		$mfcount=count($morefr);
		for($mfi=0;$mfi<$mfcount;$mfi++)
		{
			$morefrf=explode('::::::',$morefr[$mfi]);//必须
			echo ' <a href="'.$morefrf[0].'" target="_blank"><img src="'.$morefrf[0].'"></a>';
		}
	}
}

//读取多值字段:图片扩大展示,图片展示,图片放大
/*

$lx=1显示全部缩略图片;
$lx=2显示一张缩略图片;
$lx=0不显示缩略图片(用图标代替)

$lx=1,2 时，可指定图片尺寸 $width="100",$height="100"

//图片扩大插件
require_once($_SERVER['DOCUMENT_ROOT'].'/public/enlarge/call.html');
*/
function EnlargeImg($img,$id,$lx='1',$width="",$height="")
{ 
	 if($img)
	 {
		
		if($lx){echo '<div class="imgslist">';}
		$morefr=explode(',',$img);
		$mfcount=count($morefr);
		for($mfi=0;$mfi<$mfcount;$mfi++)
		{
			  $i++;
			  $morefrf=explode('::::::',$morefr[$mfi]);//必须
			  if($morefrf[0])
			  {
				  if($lx){
					  $simg='<img src="'.$morefrf[0].'" width="'.$width.'" height="'.$height.'"/>';
				  }else{
					  $simg='<i class="icon-picture"></i>';
				  }
				  
				  if($lx==2&&$i>1){$display='none;';}
				  
				  echo '<a class="fancybox-thumbs" data-fancybox-group="thumb'.$id.'" href="'.$morefrf[0].'"  style="display:'.$display.'">'.$simg.'</a>';
			  }
	  
		}
		if($lx){echo '</div>';}
	}
}


//======================验证部分－开始===============================================================

//读取目录最后一个值 
function EndArray($var,$separator)   
{   
	$var=trim($var);
	if(substr($var,-1)==$separator){$var=substr($var,0,-1);}
	if(!is_array($var)&&$var){$var=explode($separator,$var);}
	if($var){return end($var);}
}

//留字母、数字、下划线、短横线
function letter($var)   
{    
	return trim(preg_replace("/[^a-zA-Z0-9_-]+/","", $var));
}

//判断非法字符 1 
function match1($user)   
{    
	//if(preg_match("/[ '.,:;*?~`!@#$%^&+=)(<>{}]|\]|\[|\/|\\\|\"|\|/",$user)) 
	if(preg_match("/[ ':;*?~`#$%^&+=)(<>{}]|\]|\[|\/|\\\|\"|\|/",$user)) 
	{
		return true; 
	 }
	else
	{    
		return false;  
	} 
}
//-----------------------------------------------------------------------------------     
// 函数名：CheckWebAddr($C_weburl)     
// 作 用：判断是否为有效网址     
// 参 数：$C_weburl（待检测的网址）     
// 返回值：布尔值     
// 备 注：无     
//-----------------------------------------------------------------------------------     
 
 
function CheckWebAddr($C_weburl)     
{     
if (!ereg("^http://[_a-zA-Z0-9-]+(.[_a-zA-Z0-9-]+)*$", $C_weburl))     
{     
return false;     
}     
return true;     
}     
 
 
 
 
//-----------------------------------------------------------------------------------     
 
 
 
// 函数名：CheckLengthBetween($C_char, $I_len1, $I_len2=100)     
// 作 用：判断是否为指定长度内字符串     
// 参 数：$C_char（待检测的字符串）     
// $I_len1 （目标字符串长度的下限）     
// $I_len2 （目标字符串长度的上限）     
// 返回值：布尔值     
// 备 注：无     
//-----------------------------------------------------------------------------------     
 
 
function CheckLengthBetween($C_cahr, $I_len1, $I_len2=100)     
{     
$C_cahr = trim($C_cahr);     
if (strlen($C_cahr) < $I_len1) return false;     
if (strlen($C_cahr) > $I_len2) return false;     
return true;     
}     
 
 
 
//-----------------------------------------------------------------------------------     
 
 
 
// 函数名：CheckUser($C_user)     
// 作 用：判断是否为合法用户名     
// 参 数：$C_user（待检测的用户名）     
// 返回值：布尔值     
// 备 注：无     
//-----------------------------------------------------------------------------------     
 
 
function CheckUser($C_user)     
{     
if (!CheckLengthBetween($C_user, 4, 20)) return false; //宽度检验     
if (!ereg("^[_a-zA-Z0-9]*$", $C_user)) return false; //特殊字符检验     
return true;     
}   


 
 
// 函数名：CheckPassword($C_passwd)     
// 作 用：判断是否为合法用户密码     
// 参 数：$C_passwd（待检测的密码）     
// 返回值：布尔值     
// 备 注：无     
//-----------------------------------------------------------------------------------     
 
 
function CheckPassword($C_passwd)     
{     
if (!CheckLengthBetween($C_passwd, 4, 20)) return false; //宽度检测     
if (!ereg("^[_a-zA-Z0-9]*$", $C_passwd)) return false; //特殊字符检测     
return true;     
}     
 
  
//是数字就返回,否则返回$ret参数，如果是SQL搜索则返回-0.1或其他不会有相同的值，不能是0，因为空的数字字段时是默认0
function CheckNumber($number,$ret=0)     
{     
	if(is_numeric($number)){return $number;}else{return $ret;}
}   

 
//-----------------------------------------------------------------------------------     
// 函数名：CheckImageSize($ImageFileName,$LimitSize)     
// 作 用：检验上传图片的大小     
// 参 数：$ImageFileName 上传的图片名     
// $LimitSize 要求的尺寸     
// 返回值：布尔值     
// 备 注：无     
//-----------------------------------------------------------------------------------     
 
 
function CheckImageSize($ImageFileName,$LimitSize)     
{     
$size=GetImageSize($ImageFileName);     
if ($size[0]>$LimitSize[0] || $size[1]>$LimitSize[1])     
{     
AlertExit('图片尺寸过大');     
return false;     
}     
return true;     
}     
    
//======================验证部分－结束===============================================================




 
//-----------------------------------------------------------------------------------  

/*
	周未不算时间计算
	
	$type='H' H小时，d天数
	$whether=1周未不算时间
	$whether=0周未也算时间
	
	公式：$start-$end 返回正数(已自动转正)
*/
function workDays($start,$end,$type='H',$whether=1)
{
	if($end < $start){return '结束时间不能小于开始时间';}
	
	//周六，周日不算时间
	if ($whether==1)
	{ 
		
		$double =  ($end - $start)/(7*24*3600);
		$double = floor($double);
		$startwork = date('w',$start);
		$endwork   = date('w',$end);
		$endwork = $startwork > $endwork ? $endwork + 5 : $endwork;
		if ($type=="d"){return $double * 5 + $endwork - $startwork;}
		if ($type=="H"||$type=="h"){
			//原来那天有多少小时，因为上面算的是天数，周末时不算
			$w = intval(date('w' , $start));
			if( $w > 0 && $w < 6){
				$time2=intval(date("H",$start));
			}
			else{$time2=0;}
			
			//再算下今天现在是多少小时，因为上面今天算的是天数，周末时不算
			$w = intval(date('w' , time()));
			if( $w > 0 && $w < 6)
			{
				$time3=intval(date("H",time()));
			}else{
				$time3=0;
			}
			
			//计算小时
			$time_d=workDays($start,$end,'d',1);
			if($time_d>1){$time_h=($time1-1)*24;}
			if($time_h>0){//上以算完后，如果还不是0
				$time=$time_h+(24-$time2)+$time3;
			}else{
				$time=$time2-$time3;
			}
			return abs($time);
		}
		
		
		
		
		
		
	//周六，周日也算	
	}else{
		return abs(DateDiff($start,$end,strtolower($type)));
	}
	
}




//计算时间,时间计算,时间差,时间比较,日期比较
/*
	可以是时间戳或时间格式
	公式：$time1(大) - $time2(小)
	返回：$unit=s 秒；$unit=i 分；$unit=h 时；$unit=d 天；
*/
function DateDiff($time1, $time2, $unit='h')
{ 
    switch ($unit) 
    { 
        case 's': //秒
        $dividend = 1; 
        break; 
        case 'i': //分
        $dividend = 60; 
        break; 
        case 'h': //时
        $dividend = 3600; 
        break; 
        case 'd': //天
        $dividend = 86400; 
        break; 
    } 
	
	$time1=is_numeric($time1)?$time1:strtotime($time1);
	$time2=is_numeric($time2)?$time2:strtotime($time2);
	
    if ($time1 && $time2)
	{
    	$ret=($time1-$time2)/$dividend;
		if($unit='d'){$ret=(int)$ret;}
		return spr($ret); 
	}
    return false; 
} 
//-----------------------------------------------------------------------------------  
//PHP验证日期格式是否正确（不能验证时间）
function isdate($str,$format="Y-m-d"){
$str=str_replace('/','-', $str);
$strArr = explode("-",$str);
if(empty($strArr)){
return false;
}
foreach($strArr as $val){
if(strlen($val)<2){
$val="0".$val;
}
$newArr[]=$val;
}
$str =implode("-",$newArr);
$unixTime=strtotime($str);
$checkDate= date($format,$unixTime);
if($checkDate==$str){
return true;
}else{
return false;
}
}
/*
$mydate='2012-10-5';
$mydate='2012/10/5';
使用方法：if(!isdate($mydate)){
echo 'false';
}
*/

//-----------------------------------------------------------------------------------  

//指定替换次数
//echo str_replace_limit("旧内容","新内容","这里有旧内容1 旧内容2",1);//替换1次
function str_replace_limit($search, $replace, $subject, $limit=-1)
{ 
if (is_array($search)) { 
foreach ($search as $k=>$v) { 
$search[$k] = '`' . preg_quote($search[$k],'`') . '`'; 
}
} 
else { 
$search = '`' . preg_quote($search,'`') . '`'; 
} 
return preg_replace($search, $replace, $subject, $limit); 
} 


//-----------------------------------------------------------------------------------  
//验证配置所填写是否正确
function yanzheng($mc,$zhi)
{
	if($zhi)
	{
		$zhi_echo=TextareaToCo($zhi);

		//验证
		$zhi=ToArr($zhi);
		foreach($zhi as $a=>$b)
		{
			$zhi2=ToArr($b,2);
			$aa=$a+1;
			if(trim($zhi2[0])==""){exit ("<script>alert('".$mc." 第".$aa."行的名称没填写');goBack();</script>");}
			if((int)$zhi2[1]<=100||(int)$zhi2[1]>999){exit ("<script>alert('".$mc." 第".$aa."行的值不对，必须是3位数，不要与其他值相同(否则显示重复)');goBack();</script>");}
		}
		return $zhi_echo;
	}
}

//多空格转一空格
function  DelMoreSpace($zhi)
{
	$x=1;
	while($x==1) 
	{
		$zhi=str_replace('  ',' ',$zhi); //清除空行
		if (strpos($zhi,'  ')===false){$x=0;}else{$x=1;}
	} 
	
	$x=1;
	while($x==1) 
	{
		$zhi=str_replace('&nbsp;&nbsp;','&nbsp;',$zhi); //清除空行
		if (strpos($zhi,'&nbsp;&nbsp;')===false){$x=0;}else{$x=1;}
	} 
	
	return $zhi;
}




//-----------------------------------------------------------------------------------  
/**
表单令牌(防止表单恶意提交),已包含:验证提交来源
/*
//通用调用
$lifeTime = 60 * 60;//单位:分钟,当前60分
session_set_cookie_params($lifeTime);
session_start();

//表单页
$token=new Form_token_Core();
$tokenkey= $token->grante_token("form_name"); //生成密钥,a是SESSION名称,可用form名
//输出密钥 <input name="token" type="hidden" value="<?=$tokenkey? > ">

//处理页开头
$token=new Form_token_Core();
$token->is_token("form_name",$_POST["token"]); //验证密钥

//处理页结束
$token->drop_token("form_name"); //处理完后删除密钥

*/


class Form_token_Core{
    const SESSION_KEY = 'SESSION_KEY';
    /**
     * 生成一个当前的token
     * @param string $form_name
     * @return string
     */
    public static function grante_token($form_name)
    {
        $key = self::grante_key();
        $_SESSION["SESSION_KEY.$form_name"] = $key;//里面是变量不能用单引号
        $token = md5(substr(time(), 0, 3).$key.$form_name);
        return $token;
    }
 
    /**
     * 验证一个当前的token
     * @param string $form_name
     * @return string
     */
    public static function is_token($form_name,$token)
    {
		require($_SERVER['DOCUMENT_ROOT'].'/public/global.php');
 		//防外部提交-开始
		$servername=isset($_SERVER['SERVER_NAME']) ? trim($_SERVER['SERVER_NAME']) : '';
		$sub_from=isset($_SERVER['HTTP_REFERER']) ? trim($_SERVER['HTTP_REFERER']) : '';
		if(!stristr($sub_from,$servername)||!$sub_from||$sub_from=='_'){
			exit ("<script>alert('{$LG['function.6']}');goBack();</script>");
			/*
				提示:禁止外部提交！
				如果正常登录也提示,检查$_SERVER服务是否开启
			*/
		}
 		//防外部提交-结束
		
		//令牌验证
        $key = $_SESSION["SESSION_KEY.{$form_name}"];//里面是变量不能用单引号
        if(!$key)
        {
           exit ("<script>alert('{$LG['function.7']}');goBack();</script>");
        } 
		$old_token = md5(substr(time(), 0, 3).$key.$form_name);
         if($old_token != $token)
        {
           exit ("<script>alert('{$LG['function.8']}');goBack();</script>");
        } 
   }
 
    /**
     * 删除一个token
     * @param string $form_name
     * @return boolean
     */
    public static function drop_token($form_name)
    {
	   unset($_SESSION["SESSION_KEY.$form_name"]);//里面是变量不能用单引号
       return true;
    }
 
    /**
     * 生成一个密钥
     * @return string
     */
    public static function grante_key()
    {
        $encrypt_key = md5(((float) date("YmdHis") + rand(100,999)).rand(1000,9999));
        return $encrypt_key;
    }
}




//------------------------------------图片处理(缩略图、剪切、缩小)-----------------------------------------------  

class image {
        // 当前图片
        protected $img;
        // 图像types 对应表
        protected $types = array(
                        1 => 'gif',
                        2 => 'jpg',
                        3 => 'png',
                        6 => 'bmp'
                    );
        public function __construct($img=''){
                !$img && $this->param($img);
        }
        public function param($img){
                $this->img = $img;
                return $this;
        }
        public function getImageInfo($img){
                $info = @getimagesize($img);
                if(isset($this->types[$info[2]])){
                        $info['ext'] = $info['type'] = $this->types[$info[2]];
                } else{
                        $info['ext'] = $info['type'] = 'jpg';
                }
                $info['type'] == 'jpg' && $info['type'] = 'jpeg';
                $info['size'] = @filesize($img);
                return $info;
        }
        // thumb(新图地址, 宽, 高, 裁剪, 允许放大)
        public function thumb($filename,$new_w=160,$new_h=120,$cut=0,$big=0){
        // 获取原图信息
        $info  = $this->getImageInfo($this->img);
        if(!empty($info[0])) {
            $old_w  = $info[0];
            $old_h  = $info[1];
            $type   = $info['type'];
            $ext    = $info['ext'];
            unset($info);

            // 如果原图比缩略图小 并且不允许放大
            if($old_w <= $new_w && $old_h <= $new_h && !$big)
			{
                    return false;
            }
            // 裁剪图片
            if($cut == 0){ // 等比列
                    $scale = min($new_w/$old_w, $new_h/$old_h); // 计算缩放比例
                    $width  = (int)($old_w*$scale); // 缩略图尺寸
                    $height = (int)($old_h*$scale);
                    $start_w = $start_h = 0;
                    $end_w = $old_w;
                    $end_h = $old_h;
            } elseif($cut == 1){ // center center 裁剪
                        $scale1 = round($new_w/$new_h,2);
                        $scale2 = round($old_w/$old_h,2);
                        if($scale1 > $scale2){
                                $end_h = round($old_w/$scale1,2);
                                $start_h = ($old_h-$end_h)/2;
                                $start_w  = 0;
                                $end_w    = $old_w;
                        } else{
                                $end_w  = round($old_h*$scale1,2);
                                $start_w  = ($old_w-$end_w)/2;
                                $start_h = 0;
                                $end_h   = $old_h;
                        }
                        $width = $new_w;
                    $height= $new_h;
                } elseif($cut == 2){ // left top 裁剪
                        $scale1 = round($new_w/$new_h,2);
                    $scale2 = round($old_w/$old_h,2);
                    if($scale1 > $scale2){
                            $end_h = round($old_w/$scale1,2);
                            $end_w = $old_w;
                    } else{
                            $end_w = round($old_h*$scale1,2);
                            $end_h = $old_h;
                    }
                    $start_w = 0;
                    $start_h = 0;
                    $width = $new_w;
                    $height= $new_h;
                }
            //载入原图
            $createFun  = 'ImageCreateFrom'.$type;
            $oldimg     = $createFun($this->img);
            //创建缩略图
            if($type!='gif' && function_exists('imagecreatetruecolor')){
                $newimg = imagecreatetruecolor($width, $height);
            } else{
                $newimg = imagecreate($width, $height);
            }
            // 复制图片
            if(function_exists("ImageCopyResampled")){
                ImageCopyResampled($newimg, $oldimg, 0, 0, $start_w, $start_h, $width, $height, $end_w,$end_h);
            } else{
                ImageCopyResized($newimg, $oldimg, 0, 0, $start_w, $start_h, $width, $height, $end_w,$end_h);
            }
            // 对jpeg图形设置隔行扫描
            $type == 'jpeg' && imageinterlace($newimg,1);
            // 生成图片
            $imageFun = 'image'.$type;
            !@$imageFun($newimg,$filename) && die('保存失败!检查目录是否存在并且可写?');
            ImageDestroy($newimg);
            ImageDestroy($oldimg);
            return $filename;
        }
        return false;
    }
    // water(保存地址,水印图片,水印位置,透明度)
        public function water($filename,$water,$pos=0,$pct=80){
		
               global $water_lx,$water_font,$water_font_size,$water_font_length,$water_font_color; 
			   
			    // 加载水印图片
                $info = $this->getImageInfo($water);
                if(!empty($info[0])){
                        $water_w = $info[0];
                        $water_h = $info[1];
                        $type = $info['type'];
                        $fun  = 'imagecreatefrom'.$type;
                        $waterimg = $fun($water);
                } else{
                        return false;
                }
                // 加载背景图片
                $info = $this->getImageInfo($this->img);
                if(!empty($info[0])){
                        $old_w = $info[0];
                        $old_h = $info[1];
                        $type  = $info['type'];
                        $fun   = 'imagecreatefrom'.$type;
                        $oldimg = $fun($this->img);
                } else{
                        return false;
                }
				
                // 剪切水印
                $water_w >$old_w && $water_w = $old_w;
                $water_h >$old_h && $water_h = $old_h;
                // 水印位置
                switch($pos){
                        case 0://随机
                    $posX = rand(0,($old_w - $water_w));
                    $posY = rand(0,($old_h - $water_h));
                    break;
                case 1://1为顶端居左
                    $posX = 0;
                    $posY = 0;
                    break;
                case 2://2为顶端居中
                    $posX = ($old_w - $water_w) / 2;
                    $posY = 0;
                    break;
                case 3://3为顶端居右
                    $posX = $old_w - $water_w;
                    $posY = 0;
                    break;
                case 4://4为中部居左
                    $posX = 0;
                    $posY = ($old_h - $water_h) / 2;
                    break;
                case 5://5为中部居中
                    $posX = ($old_w - $water_w) / 2;
                    $posY = ($old_h - $water_h) / 2;
                    break;
                case 6://6为中部居右
                    $posX = $old_w - $water_w;
                    $posY = ($old_h - $water_h) / 2;
                    break;
                case 7://7为底端居左
                    $posX = 0;
                    $posY = $old_h - $water_h;
                    break;
                case 8://8为底端居中
                    $posX = ($old_w - $water_w) / 2;
                    $posY = $old_h - $water_h;
                    break;
                case 9://9为底端居右
                    $posX = $old_w - $water_w-5;
                    $posY = $old_h - $water_h-5;
					
					//文字水印时
					if($water_lx==2){$posX=$old_w-$water_font_length-50;}
					
                    break;
                default: //随机
                    $posX = rand(0,($old_w - $water_w));
                    $posY = rand(0,($old_h - $water_h));
                    break;
                }
            // 设定图像的混色模式
                imagealphablending($oldimg, true);
                // 添加水印
				
				if($water_lx==1)
				{
					//图片水印
					imagecopymerge($oldimg, $waterimg, $posX, $posY, 0, 0, $water_w,$water_h,$pct);
				}elseif($water_lx==2){
					//文字水印
					if( !empty($water_font_color) && (strlen($water_font_color)==7) ) 
					{ 
						$R = hexdec(substr($water_font_color,1,2)); 
						$G = hexdec(substr($water_font_color,3,2)); 
						$B = hexdec(substr($water_font_color,5)); 
					} 
					
					
					imagettftext($oldimg,$water_font_size,0,$posX,$posY,imagecolorallocate($oldimg, $R, $G, $B),$_SERVER['DOCUMENT_ROOT'].'/public/font/water.ttf',$water_font);
				}
              
				

                $fun = 'image'.$type;
                !@$fun($oldimg, $filename) && die('保存失败!检查目录是否存在并且可写?');
                  imagedestroy($oldimg);
                  imagedestroy($waterimg);
                  return $filename;
        }
}


/** 
* 保留原清晰质量的图片压缩类：专门用于压缩原图,重新压缩会自行清除图片木马
* 即使原比例压缩，也可大幅度缩小。数码相机4M图片。也可以缩为700KB左右。如果缩小比例，则体积会更小。 
* 结果：可保存、可直接显示。 

调用:
$source =  '1.jpg';  
$dst_img = '2.jpg';  
$percent =0.5;//缩小比例:填写数值:1保留原比例;0.5缩小一半 (默认为1)
$image = (new imgcompress($source,$percent))->compressImg($dst_img);  
*/  
class imgcompress{  
  
	   private $src;  
	   private $image;  
	   private $imageinfo;  
	   private $percent = 1;  
  
	   /** 
		* 图片压缩 
		* @param $src 源图 
		* @param float $percent  压缩比例 
		*/  
	   public function __construct($src, $percent=1)  
	   {  
			  $this->src = $src;  
			  $this->percent = $percent;  
	   }  
  
  
	   /** 高清压缩图片 
		* @param string $saveName  提供图片名（可不带扩展名，用源图扩展名）用于保存。或不提供文件名直接显示 
		*/  
	   public function compressImg($saveName='')  
	   {  
			  $this->_openImage();  
			  if(!empty($saveName)) $this->_saveImage($saveName);  //保存  
			  else $this->_showImage();  
	   }  
  
	   /** 
		* 内部：打开图片 
		*/  
	   private function _openImage()  
	   {  
			  list($width, $height, $type, $attr) = getimagesize($this->src);  
			  $this->imageinfo = array(  
					 'width'=>$width,  
					 'height'=>$height,  
					 'type'=>image_type_to_extension($type,false),  
					 'attr'=>$attr  
			  );  
			  $fun = "imagecreatefrom".$this->imageinfo['type'];  
			  $this->image = $fun($this->src);  
			  $this->_thumpImage();  
	   }  
	   /** 
		* 内部：操作图片 
		*/  
	   private function _thumpImage()  
	   {  
			  $new_width = $this->imageinfo['width'] * $this->percent;  
			  $new_height = $this->imageinfo['height'] * $this->percent;  
			  $image_thump = imagecreatetruecolor($new_width,$new_height);  
			  //将原图复制带图片载体上面，并且按照一定比例压缩,极大的保持了清晰度  
			  imagecopyresampled($image_thump,$this->image,0,0,0,0,$new_width,$new_height,$this->imageinfo['width'],$this->imageinfo['height']);  
			  imagedestroy($this->image);  
			  $this->image = $image_thump;  
	   }  
	   /** 
		* 输出图片:保存图片则用saveImage() 
		*/  
	   private function _showImage()  
	   {  
			  header('Content-Type: image/'.$this->imageinfo['type']);  
			  $funcs = "image".$this->imageinfo['type'];  
			  $funcs($this->image);  
	   }  
	   /** 
		* 保存图片到硬盘： 
		* @param  string $dstImgName  1、可指定字符串不带后缀的名称，使用源图扩展名 。2、直接指定目标图片名带扩展名。 
		*/  
	   private function _saveImage($dstImgName)  
	   {  
			  if(empty($dstImgName)) return false;  
			  $allowImgs = ['.jpg', '.jpeg', '.png', '.bmp', '.wbmp','.gif'];   //如果目标图片名有后缀就用目标图片扩展名 后缀，如果没有，则用源图的扩展名  
			  $dstExt =  strrchr($dstImgName ,".");  
			  $sourseExt = strrchr($this->src ,".");  
			  if(!empty($dstExt)) $dstExt =strtolower($dstExt);  
			  if(!empty($sourseExt)) $sourseExt =strtolower($sourseExt);  
  
			  //有指定目标名扩展名  
			  if(!empty($dstExt) && in_array($dstExt,$allowImgs)){  
					 $dstName = $dstImgName;  
			  }elseif(!empty($sourseExt) && in_array($sourseExt,$allowImgs)){  
					 $dstName = $dstImgName.$sourseExt;  
			  }else{  
					 $dstName = $dstImgName.$this->imageinfo['type'];  
			  }  
			  $funcs = "image".$this->imageinfo['type'];  
			  $funcs($this->image,$dstName);  
	   }  
  
	   /** 
		* 销毁图片 
		*/  
	   public function __destruct(){  
			  imagedestroy($this->image);  
	   }  
  
}  



//删除木马文件(图片):检查是否含有PHP代码,HTML代码
function DelTrojan($file)
{
	if(!$file){return;}
	$file=AddPath($file);
	if(!file_exists($file)){return;}
	
	$Trojan=0;
	
	
	
	//验证:-------------------------------------------------
	$resource = fopen($file, 'rb');
	$fileSize = filesize($file);
	fseek($resource,0);	  //把文件指针移到文件的开头
	if ($fileSize > 512) { // 若文件大于521B文件取头和尾
		$hexCode = bin2hex(fread($resource, 512));//bin2hex转成16进制
		fseek($resource, $fileSize - 512);//把文件指针移到文件尾部
		$hexCode .= bin2hex(fread($resource, 512));
	} else { // 取全部
		$hexCode = bin2hex(fread($resource, $fileSize));
	}
	fclose($resource);
	
	
	/* 核心  整个类检测木马脚本的核心在这里  通过匹配十六进制代码检测是否存在木马脚本*/
	/* 匹配图片16进制中的 <?php ?> */
	/* 匹配图片16进制中的 <?PHP ?> */
	/* 匹配图片16进制中的 <? ?> */
	/* 匹配图片16进制中的 <% %> */
	/* 匹配图片16进制中的 <script | /script> 大小写亦可*/
	if (!$Trojan&&preg_match("/(3c3f706870.*?3f3e)|(3c3f504850.*?3f3e)|(3c3f.*?3f3e)|(3c25.*?253e)|(3C534352495054)|(2F5343524950543E)|(3C736372697074)|(2F7363726970743E)/is",$hexCode)){$Trojan=1;}


	//原版:
	/* 匹配图片16进制中的 <?php ( ) ?> */
	/* 匹配图片16进制中的 <?PHP ( ) ?> */
	/* 匹配图片16进制中的 <? ( ) ?> */
	/* 匹配图片16进制中的 <% ( ) %> */
	/* 匹配图片16进制中的 <script | /script> 大小写亦可*/
/*	if (!$Trojan&&preg_match("/(3c3f706870.*?28.*?29.*?3f3e)|(3c3f504850.*?28.*?29.*?3f3e)|(3c3f.*?28.*?29.*?3f3e)|(3c25.*?28.*?29.*?253e)|(3C534352495054)|(2F5343524950543E)|(3C736372697074)|(2F7363726970743E)/is",$hexCode)){$Trojan=1;}
*/


	//其他:兴奥开发-------------------------------------------------
	if(!$Trojan&&stristr($hexCode,'3c736372697074')){$Trojan=1;}//<script
	if(!$Trojan&&stristr($hexCode,'3c3f706870')){$Trojan=1;}//<?php
	if(!$Trojan&&stristr($hexCode,'6576616c')){$Trojan=1;}//eval
	if(!$Trojan&&stristr($hexCode,'3c696672616d65')){$Trojan=1;}//<iframe
	if(!$Trojan&&stristr($hexCode,'3c666f726d')){$Trojan=1;}//<form
	if(!$Trojan&&stristr($hexCode,'3c696e707574')){$Trojan=1;}//<input
	if(!$Trojan&&stristr($hexCode,'3c7374796c65')){$Trojan=1;}//<style
	if(!$Trojan&&stristr($hexCode,'3c6f626a656374')){$Trojan=1;}//<object
	if(!$Trojan&&stristr($hexCode,'3c6672616d65736574')){$Trojan=1;}//<frameset
	if(!$Trojan&&stristr($hexCode,'3c6672616d65')){$Trojan=1;}//<frame



	//删除文件
	if($Trojan){DelFile($file);}
	return $Trojan;
}


//读取IP数据库
function convertIP($ip) {   
  $ip1num = 0;  
  $ip2num = 0;  
  $ipAddr1 ="";  
  $ipAddr2 ="";  
  $dat_path = $_SERVER['DOCUMENT_ROOT'].'/public/qqwry.dat';          
  if(!preg_match("/^\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3}$/", $ip)) {   
    return 'IP地址错误';   
  }    
  if(!$fd = @fopen($dat_path, 'rb')){   
    return 'IP数据文件不存在或访问被拒绝';   
  }    
  $ip = explode('.', $ip);   
  $ipNum = $ip[0] * 16777216 + $ip[1] * 65536 + $ip[2] * 256 + $ip[3];    
  $DataBegin = fread($fd, 4);   
  $DataEnd = fread($fd, 4);   
  $ipbegin = implode('', unpack('L', $DataBegin));   
  if($ipbegin < 0) $ipbegin += pow(2, 32);   
    $ipend = implode('', unpack('L', $DataEnd));   
  if($ipend < 0) $ipend += pow(2, 32);   
    $ipAllNum = ($ipend - $ipbegin) / 7 + 1;   
  $BeginNum = 0;   
  $EndNum = $ipAllNum;    
  while($ip1num>$ipNum || $ip2num<$ipNum) {   
    $Middle= intval(($EndNum + $BeginNum) / 2);   
    fseek($fd, $ipbegin + 7 * $Middle);   
    $ipData1 = fread($fd, 4);   
    if(strlen($ipData1) < 4) {   
      fclose($fd);   
      return 'System Error';   
    }  
    $ip1num = implode('', unpack('L', $ipData1));   
    if($ip1num < 0) $ip1num += pow(2, 32);   
  
    if($ip1num > $ipNum) {   
      $EndNum = $Middle;   
      continue;   
    }   
    $DataSeek = fread($fd, 3);   
    if(strlen($DataSeek) < 3) {   
      fclose($fd);   
      return 'System Error';   
    }   
    $DataSeek = implode('', unpack('L', $DataSeek.chr(0)));   
    fseek($fd, $DataSeek);   
    $ipData2 = fread($fd, 4);   
    if(strlen($ipData2) < 4) {   
      fclose($fd);   
      return 'System Error';   
    }   
    $ip2num = implode('', unpack('L', $ipData2));   
    if($ip2num < 0) $ip2num += pow(2, 32);    
      if($ip2num < $ipNum) {   
        if($Middle == $BeginNum) {   
          fclose($fd);   
          return 'Unknown';   
        }   
        $BeginNum = $Middle;   
      }   
    }    
    $ipFlag = fread($fd, 1);   
    if($ipFlag == chr(1)) {   
      $ipSeek = fread($fd, 3);   
      if(strlen($ipSeek) < 3) {   
        fclose($fd);   
        return 'System Error';   
      }   
      $ipSeek = implode('', unpack('L', $ipSeek.chr(0)));   
      fseek($fd, $ipSeek);   
      $ipFlag = fread($fd, 1);   
    }   
    if($ipFlag == chr(2)) {   
      $AddrSeek = fread($fd, 3);   
      if(strlen($AddrSeek) < 3) {   
      fclose($fd);   
      return 'System Error';   
    }   
    $ipFlag = fread($fd, 1);   
    if($ipFlag == chr(2)) {   
      $AddrSeek2 = fread($fd, 3);   
      if(strlen($AddrSeek2) < 3) {   
        fclose($fd);   
        return 'System Error';   
      }   
      $AddrSeek2 = implode('', unpack('L', $AddrSeek2.chr(0)));   
      fseek($fd, $AddrSeek2);   
    } else {   
      fseek($fd, -1, SEEK_CUR);   
    }   
    while(($char = fread($fd, 1)) != chr(0))   
    $ipAddr2 .= $char;   
    $AddrSeek = implode('', unpack('L', $AddrSeek.chr(0)));   
    fseek($fd, $AddrSeek);   
    while(($char = fread($fd, 1)) != chr(0))   
    $ipAddr1 .= $char;   
  } else {   
    fseek($fd, -1, SEEK_CUR);   
    while(($char = fread($fd, 1)) != chr(0))   
    $ipAddr1 .= $char;   
    $ipFlag = fread($fd, 1);   
    if($ipFlag == chr(2)) {   
      $AddrSeek2 = fread($fd, 3);   
      if(strlen($AddrSeek2) < 3) {   
        fclose($fd);   
        return 'System Error';   
      }   
      $AddrSeek2 = implode('', unpack('L', $AddrSeek2.chr(0)));   
      fseek($fd, $AddrSeek2);   
    } else {   
      fseek($fd, -1, SEEK_CUR);   
    }   
    while(($char = fread($fd, 1)) != chr(0)){   
      $ipAddr2 .= $char;   
    }   
  }   
  fclose($fd);    
  if(preg_match('/http/i', $ipAddr2)) {   
    $ipAddr2 = '';   
  }   
  $ipaddr = "$ipAddr1 $ipAddr2";   
  $ipaddr = preg_replace('/CZ88.NET/is', '', $ipaddr);   
  $ipaddr = preg_replace('/^s*/is', '', $ipaddr);   
  $ipaddr = preg_replace('/s*$/is', '', $ipaddr);   
  if(preg_match('/http/i', $ipaddr) || $ipaddr == '') {   
    $ipaddr = 'Unknown';   
  }   
  $ipaddr=iconv('GB2312', 'UTF-8', $ipaddr);//数据库是GB2312,所以要转为UTF-8 
  return $ipaddr;   
}

//判断是否是手机端
function isMobile()
{ 
	require_once($_SERVER['DOCUMENT_ROOT'].'/public/Mobile_Detect.php');
	$detect = new Mobile_Detect();
	if($detect->isMobile() && !$detect->isTablet())
	{
		return true;//手机
	}
	return false;//平板和电脑

	/*
		轻量级的开源移动设备检测（检测手机和平板）:
		设备经常更新,所以类也里经常更新,地址,下载后只要里面的Mobile_Detect.php文件
		https://github.com/serbanghita/Mobile-Detect/releases
		
		使用方法:
		if($detect->isMobile())//检查任何移动设备。
		if($detect->isTablet())//检查任何平板电脑。
		if($detect->isMobile() && !$detect->isTablet())//检查任何移动设备,不包括平板电脑。
		
		//批量使用
		foreach($userAgents as $userAgent){
		$detect->setUserAgent($userAgent);
		$isMobile = $detect->isMobile();
		}

		$detect->is('AndroidOS'); //检查系统
		$detect->version('iPad'); // 4.3 (float) //检查版本

	*/


}
//------------------------------------其他-----------------------------------------------  

//生成二维码
/*
	qrcode参数说明:
	$text='111';//二维码内容
	$level='L';//容错级别,也就是有被覆盖的区域还能识别	 L=7%，M=15%，Q=25%，H=30%；
	$size='6';//生成图片大小
	$margin='2';//空白区域间距值
	
	$logo='/images/logo.png';//加logo,加LOGO时,需要$level='Q或H'
	$outfile='/upxingao/qrcode/qrcode.png';//已经生成的原始二维码图
	$img='/upxingao/qrcode/img.png';//加logo的二维码图
*/
function qrcode($text,$level='',$size='',$margin='',$logo='',$outfile='',$img='')
{
	if(!$text){return $text;}//可能是0
	require_once($_SERVER['DOCUMENT_ROOT'].'/public/phpqrcode/phpqrcode.php');
	
	if(!$level){$level='M';}
	if(!$size){$size='6';}
	if(!$margin){$margin='2';}
	if(!$outfile){$outfile='/upxingao/qrcode/temp.png';}
	if(!$img){$img='/upxingao/qrcode/temp_img.png';}
	
	$old_outfile=$outfile;
	$outfile=AddPath($outfile);




	
	//生成二维码图片
	/*
	QRcode::png参数说明:
	参数$text表示生成二位的的信息文本；
	参数$outfile表示是否输出二维码图片文件，默认否；
	参数$level表示容错率
	参数$size表示生成图片大小，默认是3；
	参数$margin表示二维码周围边框空白区域间距值；
	参数$saveandprint表示是否保存二维码并显示。
	*/
	QRcode::png($text,$outfile,$level,$size,$margin,$saveandprint=false);  
	
	
	
	
	
	
	if ($logo) 
	{   
		$logo=AddPath($logo);
		$outfile = imagecreatefromstring(file_get_contents($outfile));   
		$logo = imagecreatefromstring(file_get_contents($logo));   
		$outfile_width = imagesx($outfile);//二维码图片宽度   
		$outfile_height = imagesy($outfile);//二维码图片高度   
		$logo_width = imagesx($logo);//logo图片宽度   
		$logo_height = imagesy($logo);//logo图片高度   
		$logo_qr_width = $outfile_width / 5;   
		$scale = $logo_width/$logo_qr_width;   
		$logo_qr_height = $logo_height/$scale;   
		$from_width = ($outfile_width - $logo_qr_width) / 2;   
		//重新组合图片并调整大小   
		imagecopyresampled($outfile, $logo, $from_width, $from_width, 0, 0, $logo_qr_width,$logo_qr_height, $logo_width, $logo_height);
		
		imagepng($outfile,$_SERVER['DOCUMENT_ROOT'].$img);   
		
		return  $img;
	}else{
		return  $old_outfile;
	}
}





/*
	搜索并替换指定内容,增加数量,减少数量 (如果没有符合替换条件则附加内容,否则返回替换过的内容)
	
	$goodsdata='a:::0|||b:::2';//原内容 (|||分隔符可以放后面,也可以不放后面,会自动识别)
	$so='a:::1|||c:::3';;//要搜索的内容
	$calc=1;//相加  $calc=0;//相减
	$separ=':::';//搜索内容与修改内容的分隔符,分要完全一样,有空格时也要保留
	$separ_add='|||';//没有符合替换条件则附加内容时,附加的分隔符
	
	$goodsdata=editContent($goodsdata,$so,1,$separ,$separ_add);//a:::1|||b:::2|||c:::3
	echo $goodsdata;//a:::1|||b:::2|||c:::3

*/	

function editContent($content,$so,$calc,$separ,$separ_add)
{
	if(!$content){return $so;}
	if(!$so){return $content;}
	if(!$separ||!$separ_add){return 'editContent处理错误:separ&separ_add参数为空!';}
	
	//标记-开始
	$tag_arr=ToArr('*,+,(,),[,],/,?,\\',',');//由于有些特殊符号不能用在preg_replace_callback,因此先替换掉这些特殊符号
	if($tag_arr)
	{
		$i=0;
		foreach($tag_arr as $arrkey=>$value)
		{
			$i++;
			$content=str_ireplace($value,"::tag{$i}::",$content);
			$so=str_ireplace($value,"::tag{$i}::",$so);
			$separ=str_ireplace($value,"::tag{$i}::",$separ);
			$separ_add=str_ireplace($value,"::tag{$i}::",$separ_add);
		}
	}
	//标记-结束
	





	$arr=ToArr($so,$separ_add);
	if($arr)
	{
		foreach($arr as $arrkey=>$value)
		{
			$val=ToArr($value,$separ);
			if(!$calc){$val[1]=-$val[1];}
			
			$val[0]=str_ireplace('/','',$val[0]);//过滤特殊字符
			/*含有有特殊字符时会提示错误：preg_replace_callback(): Unknown modifier*/
			
			//替换修改主要部分-开始
			$content= preg_replace_callback("/(?<={$val[0]}{$separ})\d+/is",
				function ($matches) use($val)
				{  
					return (int)$matches[0]+$val[1];
				}
			,$content,$limit=1,$count);
				/*			
					$limit=-1 替换次数-不限
					$count 替换了多少次
				*/	
			//替换修改主要部分-结束
			
			
			//没有替换过则附加内容
			if(!$count)
			{
				//自动处理附加分隔符:验证是加前面还是加后面
				if(!$separ_add_length){$separ_add_length=fnCharCount($separ_add);}//统计分隔符字数
				if(!$separ_add_end){$separ_add_end=substr($content,-$separ_add_length);}//后面X个字符
				
				if($separ_add_end==$separ_add)//分隔符是在后面
				{
					$content.=$value.$separ_add;
				}else{//分隔符不在后面
					$content.=$separ_add.$value;
				
				}

			}
			
		}
	}
	
	
	
	

	//恢复标记-开始
	if($tag_arr)
	{
		$i=0;
		foreach($tag_arr as $arrkey=>$value)
		{
			$i++;
			$content=str_ireplace("::tag{$i}::",$value,$content);
		}
	}
	//恢复标记-结束

	return $content;
}







/** 
	//3DES加密,解密类 
	
	调用:
	$key   = 'ABCEDFGHIJKLMNOPQ';  
	$iv    = '0123456789';  
	$des = new Encrypt($key, $iv); 
	 
	$str = "abcdefghijklmnopq";  
	echo "原: {$str},长度: ",strlen($str),"\r\n"; 
	 
	$e_str = $des->encrypt3DES($str);  
	echo "加密后: ", $e_str, "\r\n";  
	
	$d_str = $des->decrypt3DES($e_str);  
	echo "解密后: {$d_str},长度: ",strlen($d_str),"\r\n"; 
	
	

	举个例子说，保存在数据库中的用户密码并不是明文保存的，而是采用md5加密后存储，这样即使数据库被脱库，仍能保证用户密码安全。但是，md5是不可逆的，开发人员根本就不知道用户的密码到底是什么。有些时候，我们希望加密后存储的数据是可逆的，比如一些接口密钥，这样即使数据库被脱库，如果没有对应的解密方式，攻击者盗取的密钥也是不能使用的。	
	
	注意，如果要在数据库中保存加密后的数据，建议base64_encode之后再保存，以下是PHP官网上的建议：如果你在例如 MySQL 这样的数据库中存储数据， 请注意 varchar 类型的字段会在插入数据时自动移除字符串末尾的“空格”。 由于加密后的数据可能是以空格（ASCII 32）结尾， 这种特性会导致数据损坏。 请使用 tinyblob/tinytext（或 larger）字段来存储加密数据。
	
*/  
class Encrypt  
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







//读取英文句首字母
/*
	initials('MY XA')//按空格取
*/
function Initials($name)
{ 
  $nword = explode(' ',$name); 
  foreach($nword as $letter){$new_name.= $letter{0};} 
  return $new_name; 
} 






//检测网址是否能打开的方法
/*
	需要在服务器上执行才有效:
	echo httpcode('http://www.sanshengco.cn');//如果显示为200则正常，如果显示其它值表示不正常；
*/
function httpcode($url){
	$ch = curl_init();
	$timeout = 3;//设置超时秒数。
	curl_setopt($ch,CURLOPT_FOLLOWLOCATION,1);
	curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
	curl_setopt($ch, CURLOPT_HEADER, 1);
	curl_setopt ($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
	curl_setopt($ch,CURLOPT_URL,$url);
	curl_exec($ch);
	return $httpcode = curl_getinfo($ch,CURLINFO_HTTP_CODE);
	curl_close($ch);
}

//自动获取前台或后台路径
function getPath($callFrom='')
{ 
 	$path='/xamember/';
	if($callFrom=='manage'){$path='/xingao/';}
	elseif($callFrom!='member'&&stristr($_SERVER['PHP_SELF'],'/xingao/')){$path='/xingao/';}
	
	return $path;
}



//生成X位数，不足自动补0,加0,位数,自动加0
/*
	$number 数字或字符:不支持汉字
	$digit 位数
	$location=1 补0位置:在前面补
	$location=2 补0位置:在后面补
	$location=3 补0位置:在两边补
	
	$fixed=1 固定位数:超过时只保留前面 如:123456>123
	$fixed=2 固定位数:超过时只保留后面 如:123456>456
	
	调用：Digit(1,3)//输出001
*/

function Digit($number,$digit,$location=1,$fixed=0)
{
    $length=strlen($number)-$digit;
	if($location==1&&$length<0){$number=str_pad($number,$digit,'0',STR_PAD_LEFT);}
	if($location==2&&$length<0){$number=str_pad($number,$digit,'0',STR_PAD_RIGHT);}
	if($location==3&&$length<0){$number=str_pad($number,$digit,'0',STR_PAD_BOTH);}
	
	if($fixed)
	{
		if($fixed==1&&$length>0){$number=substr($number,0,$length);}
		elseif($fixed==2&&$length>0){$number=substr($number,-$length);}
	}
   return $number;
}





//将一串字符插入到另一串字符串的指定位置:不支持中文
/*
	$content	为原始字符串
	$substr		为要插入的字符串
	
	$loca_str	为要插入的第一个字符位置(分大小写,loca_str和loca_end只要一项)
	$loca_end	为要插入的最后一个字符位置(分大小写,loca_str和loca_end只要一项)
	
	echo str_insert('acaca','00','c');//输出:ac00aca
*/
function str_insert($content,$substr,$loca_str='',$loca_end='') 
{ 
    if($loca_str){$i=strpos($content,$loca_str)+1;}
    elseif($loca_end){$i=strrpos($content,$loca_end)+1;}
	
	for($j=0; $j<$i; $j++){ 
   	 $startstr .= $content[$j]; 
    } 
    for ($j=$i; $j<strlen($content); $j++){ 
   	 $laststr .= $content[$j]; 
    } 
    $content = ($startstr . $substr . $laststr); 
    return $content; 
}




//本站链接方式http或https
function httpSite()
{
	//旧方式:缺点,当后台忘记修改时,会无法正确获取
	//global $siteurl;
	//if(have($siteurl,'https://',0)){return 'https://';}else{return 'http://';}
	
	if(is_ssl()){return 'https://';}else{return 'http://';}
}

//判断是否SSL协议
function is_ssl()
{
    if(isset($_SERVER['HTTPS']) && ('1' == $_SERVER['HTTPS'] || 'on' == strtolower($_SERVER['HTTPS']))){
        return true;
    }elseif(isset($_SERVER['SERVER_PORT']) && ('443' == $_SERVER['SERVER_PORT'] )) {
        return true;
    }
    return false;
}



//显示图片按钮,输出图片
function ShowImg($img)
{
	require($_SERVER['DOCUMENT_ROOT'].'/public/global.php');
	$img=cadd($img);
	if(!$img){return;}
	echo ' <a href="/public/ShowImg.php?img='.urlencode($img).'" target="_blank" class=" tooltips" data-container="body" data-placement="top" data-original-title="'.$LG['function.9'].'"><i class="icon-picture"></i></a> ';
}

//获得文件的md5,比较文件,文件是否修改
/*
	filemtime ($file);//获取文件修改的时间戳
	filectime ($file)//获取文件创建的时间戳
	fileatime ($file)//获取文件访问的时间戳
	md5_file ($file);//获取文件md5
*/
function FileCheck($file,$md5)
{
	if(!$file||!$md5){return 1;}
	if(md5_file(AddPath($file))!=$md5){return 0;}else{return 1;}
}


//禁止某地区访问
function limitShow($limitShowKey='')
{
	//每次要查询数据库占资源,因此登录后停止验证
	if($_SESSION['manage']['userid']||$_SESSION['member']['userid']){return;}
	
	
	
	global $ON_demo,$ruleOutKey,$_GET;
	$ip=GetIP(1);
	
	$arr=ToArr($ip);
	if($arr)
	{
		foreach($arr as $arrkey=>$value)
		{
			$add.=convertIP($value).',';
		}
	}

	$limit=0;
	
	//自定义禁止地区
	if(!$limit&&$limitShowKey&&have($add,$limitShowKey,0)&&!have($add,$ruleOutKey,0)){$limit=1;}
	
	//盗用兴奥系统的地区,暂时关闭:浦东,
	if(!$limit&&$ON_demo&&have($add,'杭州,郑州,合肥,龙华,宝安',0)&&!have($add,$ruleOutKey,0)){$limit=1;}
	
	//日志---------
	//爬虫和其他则不记录
	if(($limit||$ON_demo)&&!$_SESSION['AccessLog']&&!stristr($add,'保留地址用于本地回送')&&!stristr($add,'百度网讯')&&!stristr($add,'奇虎360')&&!stristr($add,'电信互联网数据中心'))
	{
		//记录该浏览器访问
		$AccessNumber=$_COOKIE['AccessNumber'];
		setcookie('AccessNumber',$AccessNumber+1,time()+315360000,'/');
		if(!$AccessNumber){$AccessNumber=1;}

		//写入日志
		$data=$ip.' ：'.$add.' --- '.date('Y-m-d H:i:s',time()).' 【该浏览器第'.$AccessNumber.'次访问】
';
		$fileName = $_SERVER['DOCUMENT_ROOT'].'/log/'.date('Y-m',time()).'.txt';//要有log目录,每个月一个日志文件
		DoMkfile($fileName);
		if(!stristr(file_get_contents($fileName),$data))//无完全重复的记录时就添加记录
		{
			file_put_contents($fileName, $data,FILE_APPEND);
		}
		
		$_SESSION['AccessLog']=1;
	}
		


	if($limit||$_COOKIE['Accesslimit'])
	{
		setcookie('Accesslimit',1,time()+315360000,'/');
		if($_GET['JS'])
		{
			exit('window.location="/404.html";');
		}else{
		    require_once($_SERVER['DOCUMENT_ROOT'].'/404.html');
		    exit;
		}
	}
	
}


//获取网址所带参数并转为数组:参数中不能有fileAdd变量参数
/*
	调用:
	$url='/index.php?q=100&w=200';
	$_GET=GetUrlPar($url);
	require($_SERVER['DOCUMENT_ROOT'].$_GET['fileAdd']);
*/
function GetUrlPar($url)
{
	if(!$url){return;}

	$r = parse_url($url);
	$field['fileAdd']=$r['path'];
	if(!$r['query']){return $field;}
	
	$arr=ToArr($r['query'],'&');
	if($arr)
	{
		foreach($arr as $key=>$value)
		{
			$line=ToArr($value,'=');
			$field[$line[0]]=$line[1];
		}
	}
	
	return $field;
}

?>