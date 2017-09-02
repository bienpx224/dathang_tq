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

if(!defined('InXingAo'))
{
	exit('No InXingAo');
}

//审核SQL条件语句安全性
/*
	$AndOr前面自动加AND或OR
*/
function whereCHK($where,$AndOr='')
{
	//检查安全性:没有绝对的方法可以检查出漏洞,因此未启用
	if(trim($where)){return " {$AndOr} {$where} ";}
}


//快速查询单条信息----------------------------------------------------------------------------------
/*
支持多字段和*,多字段时只返回数组;单个字段cadd返回值
$groupid=FeData('member','groupid',"userid='{$userid}'");
$rs=FeData('baoguo','bgid',"");
*/
function FeData($table,$field,$where)
{
	require($_SERVER['DOCUMENT_ROOT'].'/public/global.php');
	if($where){$where="where {$where}";}
	$rs=mysqli_fetch_array($xingao->query("select {$field} from {$table} {$where}"));
	
	//exit("select {$field} from {$table} {$where}");//出错时显示
	
	//特殊表/特殊字段
	if($table=='member'&&$field=='img'&&!$rs[0]){$rs[0]='/images/member_tx.gif';}
	
	
	if(stristr($field,',')||stristr($field,'*'))
	{
		return $rs;
	}else{
		return cadd($rs[0]);//上面$rs[0]有用0,此处必须用0
	}
}


//快速查询多条信息----------------------------------------------------------------------------------
/*
	支持多字段和*
	$typ=1 返回数组
	$typ=0 返回字符串:已带cadd
	
	$separ $typ=0时的分隔符
	
	调用:
	$r=WiData('yundan','ydid,ydh',"ydid>0 limit 2",$typ=1);
	print_r($r['ydh']);//数组
	
	$r=WiData('yundan','ydid,ydh',"ydid>0 limit 2",$typ=0);
	echo $r['ydh'];	//字符串
	
	实例:$r=WiData('hscode','number_str',"hsid in ({$hsid}) and types=2",0);  $wh_lotno=$r['number_str'];

*/
function WiData($table,$field,$where,$typ=1,$separ=',')
{
	if(!$table||!$field){return;}
	
	require($_SERVER['DOCUMENT_ROOT'].'/public/global.php');
	if($where){$where="where {$where}";}
	
	$sql=$xingao->query("select {$field} from {$table} {$where}");
	while($rs=$sql->fetch_array())
	{
		foreach($rs as $key=>$value)
		{
			if(is_numeric($key)){continue;}
			
			if($typ) 	 	{	if(!$r[$key]){$r[$key]=array();} array_push($r[$key],$rs[$key]);	}
			elseif(!$typ) 	{	if($r[$key]){$r[$key].=$separ.cadd($rs[$key]);}else{$r[$key]=cadd($rs[$key]);}	}
		}
	}
	
	return $r;
}


//计数----------------------------------------------------------------------------------
function NumData($table,$field,$where='')
{
	if(!$where){$where=$field;}//兼容旧版
	require($_SERVER['DOCUMENT_ROOT'].'/public/global.php');
	$num=FeData($table,"count(*) as total",$where);
	return $num['total'];
}


//获取ID (1,2,3)
function GetID($table,$field,$where)
{
	require($_SERVER['DOCUMENT_ROOT'].'/public/global.php');
	if($where){$where="where {$where}";}
	$query="select {$field} from {$table} {$where}";
	$sql=$xingao->query($query);
	while($rs=$sql->fetch_array())
	{
		$str.=$rs[0].',';
	}
	return DelStr($str);
}


//生成用户组缓存文件----------------------------------------------------------------------------------
function cache_manage_group(){
	require($_SERVER['DOCUMENT_ROOT'].'/public/global.php');
	
	$pagetext='<?php 
	//后台权限验证
	$manage_cache_time=\''.DateYmd($time,1).'\';
	$manage_per=array();
	';
	
	$query="select * from manage_group ";
	$sql=$xingao->query($query);
	while($rs=$sql->fetch_array())
	{
		
		$pagetext.='$manage_per['.$rs['groupid'].']=array(';
		$pagetext.='\'groupname\'=>\''.$rs['groupname'].'\',
		';
		$pagetext.='\'warehouse\'=>\''.$rs['warehouse'].'\',
		';
		 
		$savelx='cache';//调用类型(add,edit,cache)
		$getlx='';//获取类型(POST,GET,REQUEST,SQL)
		$alone='groupname,groupid,warehouse';//单独字段
		$digital='';//数字字段
		$radio='';//单选,复选
		$textarea='';//过滤不安全的HTML代码
		$date='';//日期格式转数字
		$save=XingAoSave($rs,$getlx,$savelx,$alone,$digital,$radio,$textarea,$html,$date);

		$pagetext.=$save;
		$pagetext.=');
		';
		
	}
	
	$pagetext.=' ?>';
	SaveText('/cache/manage_per.php',$pagetext);
}


//生成会员组缓存文件------------------------------------------------------------------------------------
function cache_member_group(){
	require($_SERVER['DOCUMENT_ROOT'].'/public/global.php');
	
	$pagetext='<?php 
	//会员权限验证
	$member_cache_time=\''.DateYmd($time,1).'\';
	$member_per=array();
	$member_warehouse=array();
	';
	
	
	//生成配置-开始------------------------------------------------------------------------------------
	$query="select * from member_group order by myorder desc, groupname{$LT} desc,groupid desc";
	$sql=$xingao->query($query);
	while($rs=$sql->fetch_array())
	{
		
		$pagetext.='
		$member_per['.$rs['groupid'].']=array(';
		$pagetext.='
		';
		$savelx='cache';//调用类型(add,edit,cache)
		$getlx='';//获取类型(POST,GET,REQUEST,SQL)
		$alone='groupid,warehouse';//单独字段
		$digital='';//数字字段
		$radio='';//单选,复选
		$textarea='';//过滤不安全的HTML代码
		$date='';//日期格式转数字
		$save=XingAoSave($rs,$getlx,$savelx,$alone,$digital,$radio,$textarea,$html,$date);

		$pagetext.=$save;
		$pagetext.=');
		$member_per['.$rs['groupid'].'][\'groupname\']=$member_per['.$rs['groupid'].'][\'groupname\'.$LT];//自动按语种获取名称
			
		';
		
		$pagetext.=caddonly($rs['warehouse']);
	}
	SaveText('/cache/member_per.php',$pagetext);
	//生成配置-结束









		
	//获取该仓库所有国家-开始------------------------------------------------------------------------------------
	require($_SERVER['DOCUMENT_ROOT'].'/cache/member_per.php');
	global $openCountry;
	
	$pagetext='
	
	
	//该会员组在该仓库 支持的所有国家----------------------------------
	';
	$query="select * from member_group order by myorder desc, groupname{$LT} desc,groupid desc";
	$sql=$xingao->query($query);
	while($rs=$sql->fetch_array())
	{
		$query_wh="select * from warehouse where checked='1' order by myorder desc,whid desc";
		$sql_wh=$xingao->query($query_wh);
		while($wh=$sql_wh->fetch_array())
		{
			$country='';
			for($i=1;$i<=$wh['area'];$i++)
			{
				$country.=$member_warehouse[$rs['groupid']][$wh['whid']][$i]['country'].',';
			}
			$country=DelStr($country);
			$country=ToArr($country);
			if($country&&is_array($country)){$country=array_unique($country);}//删除重复数组
			
			//验证后台开通的国家:去除不开通的国家
			if($openCountry&&$country)
			{
				$country_now='';
				if(!is_array($country)){$country=explode(',',$country);}//转数组
				foreach($country as $arrkey=>$code)
				{
					if(have($openCountry,$code,1))
					{
						$country_now.=$code.',';
					}
				}
				$country_now=DelStr($country_now);
				$country=ToArr($country_now);
			}
			$country=ToStr($country);
			
			$pagetext.='
			$member_warehouse['.$rs['groupid'].']['.$wh['whid'].'][\'allCountry\']=\''.$country.'\';
			';
			
		}
	}
	
	
	//获取该仓库所有国家-结束
	$pagetext.=' ?>';
	SaveText('/cache/member_per.php',$pagetext,1);
	
}




//生成仓库与渠道缓存文件----------------------------------------------------------------------------------
function cache_warehouse(){

	//读取数据
	require($_SERVER['DOCUMENT_ROOT'].'/public/global.php');
	
	//语言字段处理--
	if(!$LGList){$LGList=languageType('',3);}
	if($LGList)
	{
		foreach($LGList as $arrkey=>$LT)
		{

			//载入语种:固定文字需要有语种包
			require($_SERVER['DOCUMENT_ROOT'].'/Language/'.$LT.'.php');


			//生成缓存处理-开始-----------------------------------
			$warehouse_more=0;$zhi='';
			$pagetext='<?php ';//注意外部不能有任何空格,空行
			
			
			$query="select * from warehouse where checked='1' order by myorder desc,whid desc";
			$sql=$xingao->query($query);
			while($rs=$sql->fetch_array())
			{
//获取仓库
if($zhi){$zhi.='|||'.cadd($rs['name'.$LT]).'='.$rs['whid'];}else{$zhi=cadd($rs['name'.$LT]).'='.$rs['whid'];}
$wh_name='warehouse_'.$rs['whid'];

$pagetext.='
	//'.$rs['whid'].'仓库数据
	//调用:$joint=\'warehouse_\'.$rs[\'warehouse\'].\'_sign\';echo $$joint;
	
	$'.$wh_name.'_country=\''.$rs['country'].'\';
	$'.$wh_name.'_weightRepeat=\''.spr($rs['weightRepeat']).'\';
	$'.$wh_name.'_sign=\''.add($rs['sign']).'\';
	$'.$wh_name.'_area=\''.add($rs['area']).'\';
	
';




//渠道多值字段处理:转数组
$arr=",channel{$LT},weight_limit_ppt{$LT},content{$LT}";//多语言字段
$arr=ToArr('weight_limit,customs_types_limit,customs_weight_limit,customs_DutyFree,signday,shenfenzheng,JPChannel,customs,baoxian_1,baoxian_2,baoxian_3,baoxian_4,baoxian_5,insuranceBuyFull,insuranceFormula,insuranceFormulaType,ON_op_bgfee1,ON_op_bgfee2,ON_op_wpfee1,ON_op_wpfee2,ON_op_ydfee1,ON_op_ydfee2,ON_op_free,ON_op_freearr'.$arr);
if($arr)
{
	foreach($arr as $arrkey=>$value)
	{
		if(!$rs[$value]){continue;}
		$joint=$value;	
		if(substr($joint,-2)==$LT){$joint=substr($joint,0,-2);}//变量名不能带有LT标识
		$$joint=explode(':::',$rs[$value]);
	}
}

			   
//获取与处理渠道
for ($i=0; $i<=20; $i++) 
{
	if($arr)
	{
		foreach($arr as $arrkey=>$value)
		{
			$joint=$value;
			if(substr($joint,-2)==$LT){$joint=substr($joint,0,-2);}//变量名不能带有LT标识
			if($$joint[$i]){$pagetext.="$".$wh_name."_".$joint."_".$i."='".add($$joint[$i])."';
			";}
		}
	}

}




			}
			if(mysqli_affected_rows($xingao)>1){$warehouse_more=1;}
		
			
			
			//处理仓库---开始
			$warehouse_data=str_ireplace('|||','
			', $zhi);
			if(!is_array($zhi)&&$zhi){$zhi=explode("|||",$zhi);}//转数组
		
			$pagetext.='
			//整个系统仓库数据
			$warehouse_more=\''.$warehouse_more.'\';
			$warehouse=\''.add(str_ireplace('=','：',$warehouse_data)).'\';
			
			/*
			$lx=0显示名称;$lx=1显示下拉菜单;$per=0 下拉时是否只显示可管理的仓库(非0时,都是只显示可管理,1时自动,否则给参数);
			$option_noempty=1 不显示空下拉
			*/
			function warehouse($val,$lx=0,$per=0,$option_noempty=0)
			{
				global $manage_per,$member_per,$member_warehouse;
				global $Xgroupid,$Mgroupid;
				require($_SERVER["DOCUMENT_ROOT"]."/public/global.php");
				//显示名称
				if(!$lx)
				{
					$arr=$val;
					if($arr)
					{
						if(!is_array($arr)){$arr=explode(",",$arr);}//转数组
						foreach($arr as $arrkey=>$value)
						{
							if ($value==""){$show="";}
					 ';
					 
			
				foreach($zhi as $a=>$b)
				{
					$zhi2=ToArr($b,2);
					$b=html($b);
					if($b)
					{
						$pagetext.='elseif ($value==\''.$zhi2[1].'\'){$show=\''.$zhi2[0].'\';}
						';
					}
				}
				
			
					 
			$pagetext.='
						if($showname){$showname.=",".$show;}else{$showname=$show;}
						}
				   }
					return $showname;
					
				//显示下拉菜单
				}else{
					
			';
					
				//如果只有一个仓库必须有默认值,因此下拉菜单不能有空值
				if($warehouse_more)
				{
					$pagetext.='if(!$option_noempty){if ($val==\'\'){echo "<option value=\'\'  selected>'.$LG['function.74'].'</option>"; }else{echo "<option value=\'\' >'.$LG['function.74'].'</option>";}}';
				}
				
				$pagetext.='
					//支持下拉多选
					
					if($per)
					{
						if($per==1)
						{
							if($Xgroupid){
								if(!$manage_per[$Xgroupid][\'warehouse\']||$manage_per[$Xgroupid][\'admin\']){$all=1;}
								$warehouse=ToArr($manage_per[$Xgroupid][\'warehouse\']);
							}elseif($Mgroupid){
								$warehouse=array();//必须用空数组,否则下面in_array判断错误
							}

						}else{
							$warehouse=explode(",",$per);
						}
					}
					$val=explode(",",$val);
				';
				
				
				foreach($zhi as $a=>$b)
				{
					$zhi2=ToArr($b,2);
					$b=html($b);
					if($b)
					{
						$pagetext.='
						
							if (in_array(\''.$zhi2[1].'\',$val )){
								echo "<option value=\''.$zhi2[1].'\'  selected>'.$zhi2[0].'</option>"; 
							}else{
								if($per){
									$show=0;
									if ($Xgroupid&&($all||!$per||in_array(\''.$zhi2[1].'\',$warehouse))){$show=1;}//管理员权限
									elseif ($Mgroupid&&$member_warehouse[$Mgroupid][\''.$zhi2[1].'\'][\'checked\']&&(in_array(\''.$zhi2[1].'\',$warehouse)||empty($warehouse))){$show=1;}//会员权限
									elseif(!$Mgroupid&&in_array(\''.$zhi2[1].'\',$warehouse)){$show=1;}
		
								}else{
									$show=1;
								}
								
								if($show){echo "<option value=\''.$zhi2[1].'\' >'.$zhi2[0].'</option>";}
							}
			
						';
					}
				}
				
			$pagetext.='
				}
			}
		?>';//注意外部不能有任何空格,空行
			//处理仓库---结束
			
			SaveText("/cache/warehouse{$LT}.php",$pagetext);
			
			//生成缓存处理-结束-----------------------------------
	
	
	
	
	
	
	
		}//foreach($LGList as $arrkey=>$language)
	}//if($LGList)
	
	
	//删除已停用版本
	//语言字段处理++
	if(!$LGList){$LGList=languageType('',5);}
	if($LGList)
	{
		foreach($LGList as $arrkey=>$language)
		{
			DelFile("/cache/warehouse{$language}.php");
		}
	}
	
	
}


//-----------------------------------------------------------------------------------  
/*
自动保存字段处理(以输入框的名称获取)
*/
function XingAoSave($var,$getlx='POST',$savelx='edit',$alone='',$digital='',$radio='',$textarea='',$html='',$date='',$wupin='')
{
	$alone_now="typ,lx,id,tj,smt,button,tokenkey,action,submit,resave,rewater,code,xa_name,noCheck";
	
	//普通表单不保存物品字段
	if(!$wupin)
	{
		$alone_now.=',wupin,wupin_type,wupin_name,wupin_brand,wupin_spec,wupin_price,wupin_unit,wupin_number,wupin_total,wupin_weight';
	}
	
	$savelx=par($savelx);
	$alone_now=par($alone_now);
	$alone=par($alone);
	$digital=par($digital);
	$radio=par($radio);
	$textarea=par($textarea);
	//$html=par($html);//已不用
	$date=par($date);

	if($alone){$alone.=",".$alone_now;}else{$alone=$alone_now;}
	if($digital){$alone.=",".$digital;}
	if($radio){$alone.=",".$radio;}
	if($date){$alone.=",".$date;}
	
	if($alone){$alone=explode(',',$alone);}	
	if($textarea){$textarea=explode(',',$textarea);}
	//if($html){$html=explode(',',$html);}
	
	//数字类型：如果获取是空的,则名称也是空的,要处理
	if ($digital)
	{
		$digital=explode(',',$digital);
		foreach($digital as $digitalname=>$digitalvalue)
		{
			if($getlx=='POST'){
				$field_value=(double)$_POST[$digitalvalue];
			}elseif($getlx=='GET'){
				$field_value=(double)$_GET[$digitalvalue];
			}elseif($getlx=='REQUEST'){
				$field_value=(double)$_REQUEST[$digitalvalue];
			}

			if($savelx=='add')//添加
			{
				$save_field.=par($digitalvalue).",";
				$save_value.="'".$field_value."',";
			}elseif($savelx=='edit')//修改
			{
				$save.=par($digitalvalue)."='".$field_value."',";
			}
		}
	}
	
	//单选,复选类型：如果没选中获到不到名称,要指定
	if ($radio)
	{
		$radio=explode(',',$radio);
		foreach($radio as $radioname=>$radiovalue)
		{
			//有可能是数组,不能在此加add
			if($getlx=='POST'){
				$field_value=$_POST[$radiovalue];
			}elseif($getlx=='GET'){
				$field_value=$_GET[$radiovalue];
			}elseif($getlx=='REQUEST'){
				$field_value=$_REQUEST[$radiovalue];
			}
			if (is_array($field_value)){$field_value=implode(',',$field_value);}//有数组时转字符串
			$field_value=add($field_value);//加ADD
			
			if($savelx=='add')//添加
			{
				$save_field.=par($radiovalue).",";
				$save_value.="'".$field_value."',";
			}elseif($savelx=='edit')//修改
			{
				$save.=par($radiovalue)."='".$field_value."',";
			}
		}
	}

	
	//日期格式转数字
	if ($date)
	{
		$date=explode(',',$date);
		foreach($date as $datename=>$datevalue)
		{
			if($getlx=='POST'){
				$field_value=ToStrtotime($_POST[$datevalue]);
			}elseif($getlx=='GET'){
				$field_value=ToStrtotime($_GET[$datevalue]);
			}elseif($getlx=='REQUEST'){
				$field_value=ToStrtotime($_REQUEST[$datevalue]);
			}

			if($savelx=='add')//添加
			{
				$save_field.=par($datevalue).",";
				$save_value.="'".$field_value."',";
			}elseif($savelx=='edit')//修改
			{
				$save.=par($datevalue)."='".$field_value."',";
			}
		}
	}

	//通用处理
	foreach($var as $name=>$value)
	{
		
		if (!in_array($name,$alone)) 
		{
			if ($textarea && in_array($name,$textarea))
			{
				$value=rephtml($value);
			}
			if (is_array($value)){$value=implode(',',$value);}
			//------------------------
			if($savelx=='add')//添加
			{
				if($getlx=='SQL')//如果是rs,会有名称,数字,值3项,所以加判断去掉数字
				{
					if(!is_numeric($name))
					{
						$save_field.=par($name).",";
						$save_value.="'".add($value)."',";
					}
				}else{
					$save_field.=par($name).",";
					$save_value.="'".add($value)."',";
				}
			}
			
			elseif($savelx=='edit')//修改
			{
				if($getlx=='SQL')//如果是rs,会有名称,数字,值3项,所以加判断去掉数字
				{
					if(!is_numeric($name))
					{
						$save.=par($name)."='".add($value)."',";
					}
				}else{
					$save.=par($name)."='".add($value)."',";
				}
			}
			
			elseif($savelx=='cache'&&!is_numeric($name))//生成缓存文件//如果是rs,会有名称,数字,值3项,所以加判断去掉数字
			{
				$value=add($value);
				if(is_numeric($value)){$value=spr($value,8);}
				$save.="'".par($name)."'=>'".$value."',";
			}
		}
	}
	$save_field=DelStr($save_field);//删除最后一个,号
	$save_value=DelStr($save_value);//删除最后一个,号
	
	if($savelx=='add')
	{
		$save=array(
		'field'=>$save_field,
		'value'=>$save_value, 
		);
	}else{	
		$save=DelStr($save);//删除最后一个,号
	}

 	return $save;
}



//充值------------------------------------------------------------------------------------
/*
	$fromMoney
	$fromCurrency 空时自动用主币种
	
	返回数组:已充本币金额,本币种 ($r['toMoney'],$r['toCurrency'];)
*/
function MoneyCZ($userid,$fromtable,$fromid,$fromMoney,$fromCurrency,$title='',$content='',$type=0,$ddno=0,$operator='')

{
	require($_SERVER['DOCUMENT_ROOT'].'/public/global.php');
	global $member_per;
	
	if(!$XAMcurrency){exit ("<script>alert('XAMcurrency{$LG['function.75']}');goBack();</script>");}
	if(!$userid){exit ("<script>alert('{$LG['function.76']}');goBack();</script>");}
	//toMoney费用不能小于0 (可能用于添加0元记录,所以只验证不小于0)
	if($fromMoney<0){exit ("<script>alert('{$LG['function.77']}');goBack();</script>");}

	$mr=FeData('member','userid,username,groupid,max_cz_once,currency',"userid='{$userid}'");
	
	if(!$mr['userid']){exit ("<script>alert('{$LG['function.78']}');goBack();</script>");}
	
	//兑换账户币种
	if(!$fromCurrency){$fromCurrency=$XAMcurrency;}
	$toCurrency=$mr['currency'];
	$exchange=exchange($fromCurrency,$toCurrency);
	$toMoney=spr($fromMoney*$exchange);

	//通用保存
	$save="money=money+{$toMoney}";
	$save.=",max_cz_more=max_cz_more+{$toMoney}";
	if($type<10||$type==52)
	{
		if(spr($mr['max_cz_once'])<$toMoney){$save.=",max_cz_once='{$toMoney}'";}//会员充值最大数额记录
	}
	
	$xingao->query("update member set {$save} where userid='{$userid}'");SQLError('MoneyCZ充值');
	
	if(mysqli_affected_rows($xingao)>0)
	{
		 //删除接口充值临时记录
		if($ddno){$xingao->query("delete from paytemp where ddno='{$ddno}'");}
		
		//添加充值记录
		$remain=FeData('member','money',"userid='{$userid}'");
		$xingao->query("insert into money_czbak(userid,username,fromtable,fromid,fromMoney,fromCurrency,toMoney,toCurrency,exchange,title,content,addtime,type,remain,operator) 
		values('{$userid}','{$mr[username]}','{$fromtable}','".spr($fromid)."','".spr($fromMoney)."','".add($fromCurrency)."','{$toMoney}','".add($toCurrency)."','".spr($exchange,5)."','".add($title)."','".add($content)."','".time()."','{$type}','{$remain}','".add($operator)."');");
		SQLError('MoneyCZ添加充值记录');
		
		if(mysqli_affected_rows($xingao)<=0){echo "<script>alert('已充值,但添加充值记录失败！');goBack();</script>";}//不重要的不用exit 接口充值时无法执行下一步
	}elseif(!$ddno){
		 exit ("<script>alert('function MoneyCZ 充值失败：充值金额错误或其他错误！');goBack();</script>");
	}
	
	$r['toMoney']=$toMoney;
	$r['toCurrency']=$toCurrency; 
	return $r;
}





//扣费------------------------------------------------------------------------------------
/*
	money自动转正数;最小扣0.01本币
	
	$tally=0自动判断是否记账(更新settlement_all_money字段)
	$tally=1月结待销账(更新settlement_all_money字段)
	$tally=2月结已销账(不更新settlement_all_money字段,因为如果是充值时也还要单独再更新settlement_all_money,会因重复更新而错误)

	$fromMoney
	$fromCurrency 只能是用主币种
	
	$operator 操作员
	
	返回数组:已扣本币金额,本币种 ($r['toMoney'],$r['toCurrency'];)
*/

function MoneyKF($userid,$fromtable,$fromid,$fromMoney,$fromCurrency,$title='',$content='',$type=0,$tally=0,$operator='')
{
	require($_SERVER['DOCUMENT_ROOT'].'/public/global.php');
	global $member_per;
	$fromMoney=abs($fromMoney);//负数转正数
	
	if(!$XAMcurrency){exit ("<script>alert('XAMcurrency{$LG['function.75']}');goBack();</script>");}
	
	if(!$userid){exit ("<script>alert('{$LG['function.76']}');goBack();</script>");}
	//toMoney费用不能小于0 (可能用于添加0元记录,所以只验证不小于0)
	if($fromMoney<0){exit ("<script>alert('{$LG['function.77']}');goBack();</script>");}

	$mr=FeData('member','userid,username,groupid,max_cz_once,money,currency',"userid='{$userid}'");
	if(!$mr['userid']){exit ("<script>alert('{$LG['function.78']}');goBack();</script>");}
	
	//兑换币种
	if(!$fromCurrency){$fromCurrency=$XAMcurrency;}
	$toCurrency=$mr['currency'];
	$exchange=exchange($fromCurrency,$toCurrency);
	$toMoney=$fromMoney*$exchange;//转本币:不加spr,因为费用过小时,汇率后会小于2位小数,会显示0元
	if($toMoney>0&&$toMoney<0.01){$toMoney=0.01;}else{$toMoney=spr($toMoney);}//最小扣0.01本币

	$off_settlement=MemberSettlement('',$mr['groupid']);
	
	
	
	//扣费
	if(!$tally)
	{
		if(!$off_settlement)
		{
			//非月结
			if($mr['money']<$toMoney){exit ("<script>alert('".$LG['function.81'].($toMoney-$mr['money']).$toCurrency."');goBack();</script>");}//查询是否可扣费
			
			$save="money=money-{$toMoney}";$tally=0;
		}else{
			//月结
			$save="settlement_all_money=settlement_all_money-{$toMoney}";$tally=1;
		}
	}else{
		if($tally==1)
		{
			//月结
			$save="settlement_all_money=settlement_all_money-{$toMoney}";$tally=1;
		}elseif($tally==2){
			//月结销账
			if($mr['money']<$toMoney){exit ("<script>alert('".$LG['function.81'].($toMoney-$mr['money']).$toCurrency."');goBack();</script>");}//查询是否可扣费
			$save="money=money-{$toMoney}";$tally=2;
		}
	}
	
	//通用保存
	$xingao->query("update member set {$save} where userid='{$userid}'");	SQLError('MoneyKF扣费');
	
	if(mysqli_affected_rows($xingao)>0)
	{
		//添加扣费记录
		$remain=FeData('member','money',"userid='{$userid}'");
		$xingao->query("insert into money_kfbak(userid,username,fromtable,fromid,fromMoney,fromCurrency,toMoney,toCurrency,exchange,title,content,addtime,type,tally,remain,operator) 
		values('{$userid}','{$mr[username]}','{$fromtable}','".spr($fromid)."','".spr($fromMoney)."','".add($fromCurrency)."','{$toMoney}','".add($toCurrency)."','".spr($exchange,5)."','".add($title)."','".add($content)."','".time()."','{$type}','{$tally}','{$remain}','".add($operator)."');");
		SQLError('MoneyKF添加扣费记录');
		
		if(mysqli_affected_rows($xingao)<=0){echo "<script>alert('已扣费,但添加扣费记录失败！');goBack();</script>";}//不重要的不用exit 接口充值时无法执行下一步

	}else{
		 exit ("<script>alert('扣费失败：扣费金额错误或其他错误！');goBack();</script>");
	}
	
	
	$r['toMoney']=$toMoney;
	$r['toCurrency']=$toCurrency; 
	return $r;
}





//加积分------------------------------------------------------------------------------------
/*
有加/减0分记录
$operator=操作员	
*/
function integralCZ($userid,$fromtable,$fromid,$integral,$title='',$content='',$type=0,$operator='')
{
	require($_SERVER['DOCUMENT_ROOT'].'/public/global.php');
	$integral=(int)$integral;
	
	//判断会员ID是否正确
	$username=FeData('member','username',"userid='{$userid}'");if(!$username){return;}//没有时不要停止或提示,因为有送分给推广员功能,如果该推广员删除了,就无法正常执行
	
	
	//加积分
	$xingao->query("update member set integral=integral+{$integral} where userid='{$userid}'");
	SQLError('加积分');
	$rc=mysqli_affected_rows($xingao);
	
	if(1==1)//推广失效时要加0分记录,所以不加判断
	{
		//添加加积分记录
		$remain=FeData('member','integral',"userid='{$userid}'");
		$xingao->query("insert into integral_czbak(userid,username,fromtable,fromid,integral,title,content,addtime,type,remain,operator) values('{$userid}','{$username}','{$fromtable}','".spr($fromid)."','{$integral}','".add($title)."','".add($content)."','".time()."','{$type}','{$remain}','".add($operator)."');");
		SQLError('添加 加积分 记录');
		$rc=mysqli_affected_rows($xingao);
		
		if($rc<=0)
		{
			 exit ("<script>alert('已加积分,但添加 加积分 记录失败！');goBack();</script>");
		}
	}else{
		 exit ("<script>alert('加积分失败,可能会员ID错误或要加的积分是0！');goBack();</script>");
	}

}





//扣分------------------------------------------------------------------------------------
/*
有加/减0分记录
*/
function integralKF($userid,$fromtable,$fromid,$integral,$money,$title='',$content='',$type=0,$operator='')
{
	require($_SERVER['DOCUMENT_ROOT'].'/public/global.php');
	
	//判断会员ID是否正确
	$username=FeData('member','username',"userid='{$userid}'");if(!$username){return;}//没有时不要停止或提示,因为有送分给推广员功能,如果该推广员删除了,就无法正常执行
	
	$integral=(int)(abs($integral));//尽量少用spr
	
	//查询是否可减
	$mr=mysqli_fetch_array($xingao->query("select integral from member where userid='{$userid}' "));
	
	//如果相差0.X分或1分,则可以扣费
	//$differ=$mr['integral']-$integral;	if($differ<0&&$differ>-2){echo $integral-=1;}
	
	if($mr['integral']<$integral)
	{
		exit ("<script>alert('".LGtag($LG['function.82'],'<tag1>=='.($integral-$mr['integral']))."');goBack();</script>");
	}
	
	//扣分
	$xingao->query("update member set integral=integral-{$integral} where userid='{$userid}'");
	SQLError('扣分');
	$rc=mysqli_affected_rows($xingao);
	
	if($rc>0)
	{
		//添加 扣分 记录
		$remain=FeData('member','integral',"userid='{$userid}'");
		$xingao->query("insert into integral_kfbak(userid,username,fromtable,fromid,integral,money,title,content,addtime,type,remain,operator) values('{$userid}','{$username}','{$fromtable}','".spr($fromid)."','{$integral}','".spr(abs($money))."','".add($title)."','".add($content)."','".time()."','{$type}','{$remain}','".add($operator)."');");
		SQLError('添加 扣分 记录');
		$rc=mysqli_affected_rows($xingao);
		
		if($rc<=0)
		{
			 exit ("<script>alert('已扣分,但添加 扣分 记录失败！');goBack();</script>");
		}
	}else{
		 exit ("<script>alert('扣分失败,可能会员ID错误或要减的积分是0！');goBack();</script>");
	}
}





//优惠券/折扣券 使用------------------------------------------------------------------------------------
/*
$cpc=coupons表数组,$fromtable=使用来自表,$fromid=使用来自ID,$money=使用金额,$title=使用标题/单号,$content=使用说明
*/
function couponsKF($cpc,$fromtable,$fromid,$money,$title='',$content='')
{
  require($_SERVER['DOCUMENT_ROOT'].'/public/global.php');
	
  if(!$cpc['cpid']){return '';}
  
  if($cpc['cpid']&&$cpc['number']==1){
	  
	  //只有一张时直接更新该张为 已使用
	  $xingao->query("update coupons set status=1,use_time=".time().",use_title='".add($title)."',use_content='".add($content)."',fromtable='".add($fromtable)."',fromid='".spr($fromid)."',money='".spr(abs($money))."' where cpid='{$cpc[cpid]}'");
	  
  }elseif($cpc['cpid']&&$cpc['number']>1){
	  
	  //有多张时改复制一张出来并修改
	  //新信息
	  $cpc['addtime']=time();
	  $cpc['use_time']=time();
	  $cpc['use_title']=$title;
	  $cpc['use_content']=$content;
	  $cpc['fromtable']=$fromtable;
	  $cpc['fromid']=spr($fromid);
	  $cpc['money']=spr(abs($money));
	  $cpc['status']=1;
	  $cpc['number']=1;
	  
	  $savelx='add';//调用类型(add,edit,cache)
	  $getlx='SQL';//获取类型(POST,GET,REQUEST,SQL)
	  $alone='cpid';//不处理的字段
	  $digital='';//数字字段
	  $radio='';//单选、复选、空文本、数组字段
	  $textarea='';//过滤不安全的HTML代码
	  $date='';//日期格式转数字
	  $save=XingAoSave($cpc,$getlx,$savelx,$alone,$digital,$radio,$textarea,$html,$date);
	  $xingao->query("insert into coupons (".$save['field'].") values(".$save['value'].")");
	  SQLError('复制信息');

	  $xingao->query("update coupons set number=number-1 where cpid='{$cpc[cpid]}'");
  }
}



//添加推广记录------------------------------------------------------------------------------------
function TuiGuangBak($userid,$username,$tg_userid,$tg_username,$status,$integral,$coupons,$addtime,$invalid_content='')
{
	require($_SERVER['DOCUMENT_ROOT'].'/public/global.php');
	if(!$addtime){$addtime=time();}
	$xingao->query("insert into tuiguang_bak(userid,username,tg_userid,tg_username,status,integral,coupons,invalid_content,addtime) values('{$userid}','{$username}','{$tg_userid}','{$tg_username}','{$status}','{$integral}','".spr($coupons)."','".add($invalid_content)."','{$addtime}');");
	SQLError();	
}


//统计信息数量(快速统计)------------------------------------------------
/*
$color=default灰色;info淡蓝色; warning橘色; important红色;success绿色
*/
function CountNum($table,$field='',$zhi='',$where_set='',$userid='',$color='success') 
{ 
	require($_SERVER['DOCUMENT_ROOT'].'/public/global.php');
	$where=" where 1=1 ";

	if($field){$where.=" and {$field}='{$zhi}'";}	
	if($where_set){$where.=' '.$where_set.' ';}
	if($userid){$where.=" and userid='{$userid}' ";}

	$num=mysqli_num_rows($xingao->query("select userid from {$table} {$where}"));
	if($num){return '<span class="badge badge-'.$color.'">'.$num.'</span> ';}
} 



//查询登录账号是否相同------------------------------------------------
function RepeatUserName($username,$reg=0) 
{ 
	require($_SERVER['DOCUMENT_ROOT'].'/public/global.php');
	
	//为安全不要直接提示已经含有该登录账号
	if($reg){$ts=$username.$LG['function.79'];}
	
	$num=mysqli_num_rows($xingao->query("select username from manage where username='{$username}'"));
	if($num)
	{
		if(!$reg){$ts=LGtag($LG['function.80'],'<tag1>=='.$username);}
		exit ("<script>alert('".$ts."');goBack();</script>");
	}
	
	$num=mysqli_num_rows($xingao->query("select username from member where username='{$username}'"));
	if($num)
	{
		if(!$reg){$ts=LGtag($LG['function.83'],'<tag1>=='.$username);;}
		exit ("<script>alert('".$ts."');goBack();</script>");
	}
} 

//获取表下一个自增ID,最大ID,自增值-------------------------------------------------
//调用:$id=NextId('baoguo');

function NextId($table)
{    
	global $xingao,$LG,$xa_config;

	$rs=mysqli_fetch_array($xingao->query("SELECT Auto_increment FROM information_schema.tables  WHERE  table_schema='{$xa_config['db']['name']}' and  table_name='{$tpre}{$table}'"));
	
	return $rs[0];
}

//获取更新时间
function update_time($name,$condition)
{    
	require($_SERVER['DOCUMENT_ROOT'].'/public/global.php');
	
	$rsup=FeData('config','id,name,value1',"name='{$name}'");
	$time=time();
	if(!$rsup['id']){
		$xingao->query("insert into config (name,value1) values ('{$name}','{$time}')");
		return 1;
	}else{
		if ($rsup['value1']<=strtotime($condition)){//多久可以更新一次
			$xingao->query("update config set value1='{$time}' where name='{$name}'");
			return 1;
		}else{
			return 0;
		}
	}
}












//------------------------------------会员相关-----------------------------------------------  




//验证会员是否是月结类型
function MemberSettlement($userid,$groupid='')
{
	require($_SERVER['DOCUMENT_ROOT'].'/public/global.php');
	global $member_per;
	if($groupid){return $member_per[$groupid]['off_settlement'];}
	
	if(!$userid){exit ("<script>alert('{$LG['function.84']}');goBack();</script>");}
	$mr=FeData('member','groupid',"userid='{$userid}'");
	if(!$mr['userid']){exit ("<script>alert('{$LG['function.85']}');goBack();</script>");}
	return $member_per[$mr['groupid']]['off_settlement'];
}



//验证会员是否存在
/*
$lx='' 填写会员名或ID
$lx=1 必须填写会员名和ID;

$tslx='' 返回结果
$tslx=1 停止并弹出; 
*/
function MemberOK($IcIdName='',$useric='',$userid='',$username='',$lx='',$tslx='')
{
	require($_SERVER['DOCUMENT_ROOT'].'/public/global.php');
	$IcIdName=par($IcIdName);	$userid=par($userid);	$username=par($username);
	if($lx&&(!$userid||!$username))
	{
		if($tslx){exit ("<script>alert('{$LG['function.94']}');goBack();</script>");}
		else{return 0;}
	}
	
	if($IcIdName){$where.=" and (useric='".$IcIdName."' or userid='".$IcIdName."' or username='".$IcIdName."')"; }
	if($useric){$where.=" and useric='".$useric."'"; }
	if($userid){$where.=" and userid='".$userid."'"; }
	if($username){$where.=" and username='".$username."'"; }
	
	if(!$where)
	{
		if($tslx){exit ("<script>alert('{$LG['function.94']}');goBack();</script>");}
		else{return 0;}
	}
	
	$mr=mysqli_fetch_array($xingao->query("select userid,username,money from member where 1=1 {$where}"));
	if(!$mr['userid'])
	{
		if($tslx){exit ("<script>alert('{$LG['function.95']}');goBack();</script>");}
		else{return 0;}
	}
	return 1;
}



//后期推广员送分处理页
/*
	$lx='yundan' 运单;
	$lx='daigou' 代购;
	$lx='mall' 商城
*/
function tuiguang_hqsf($userid,$integral,$lx,$fromid=0)
{
	require($_SERVER['DOCUMENT_ROOT'].'/public/global.php');
	global $off_integral;
	global $tuiguang_hqsf,$tuiguang_ydxf_sl,$tuiguang_ydxf_bl,$tuiguang_mallxf_sl,$tuiguang_mallxf_bl,$tuiguang_dgxf_sl,$tuiguang_dgxf_bl;
	
	//通用验证
	if(!$userid||$integral<=0||!$lx||!$off_integral){return;}
	
	//判断后期是否有送
	if($tuiguang_hqsf>=0)
	{
		//获取会员和推广员资料
		$fe=FeData('member','username,addtime,tg_userid,tg_username',"userid='{$userid}'");//获取会员注册时间
		$username=cadd($fe['username']);
		$addtime=$fe['addtime'];
		$tg_userid=$fe['tg_userid'];
		$tg_username=cadd($fe['tg_username']);
		
		//判断是否超过时长
		if($tuiguang_hqsf>0)
		{
			if($addtime<=strtotime('-'.$tuiguang_hqsf.' days')){return;}//判断是否超过时长
		}
		
		
		
		
		//运单送分
		if($lx=='yundan')
		{
			if($tuiguang_ydxf_sl>0){$songfen=$tuiguang_ydxf_sl;}
			elseif($tuiguang_ydxf_bl>0){$songfen=$integral*$tuiguang_ydxf_bl/100;}
			
			$songfen=(int)$songfen;
			if($songfen>0)
			{
				$title=LGtag($LG['function.97'],'<tag1>=='.$userid);
				integralCZ($tg_userid,'yundan',$fromid,$songfen,$title,'',$type=6);
			}
		}
		
		//商城送分
		elseif($lx=='mall')
		{
			if($tuiguang_mallxf_sl>0){$songfen=$tuiguang_mallxf_sl;}
			elseif($tuiguang_mallxf_bl>0){$songfen=$integral*$tuiguang_mallxf_bl/100;}
			
			$songfen=(int)$songfen;
			if($songfen>0)
			{
				$title=LGtag($LG['function.98'],'<tag1>=='.$userid);
				integralCZ($tg_userid,'mall_order',$fromid,$songfen,$title,'',$type=6);
			}
		}
		
		//代购送分
		elseif($lx=='daigou')
		{
			if($tuiguang_dgxf_sl>0){$songfen=$tuiguang_dgxf_sl;}
			elseif($tuiguang_dgxf_bl>0){$songfen=$integral*$tuiguang_dgxf_bl/100;}
			
			$songfen=(int)$songfen;
			if($songfen>0)
			{
				$title=LGtag($LG['function.98_1'],'<tag1>=='.$userid);
				integralCZ($tg_userid,'daigou',$fromid,$songfen,$title,'',$type=6);
			}
		}
		
		

	}
}




//会员登录成功
/*
	$status 搜索:LoginStatus(
*/
function MemberLoginSuccess($status)
{
	require($_SERVER['DOCUMENT_ROOT'].'/public/global.php');
	global $fr,$member_cookie;
	
	
	$gr=FeData('member_group','groupid,checked',"checked=1 and groupid='{$fr['groupid']}'");
	if(!$gr['groupid']){exit ("<script>alert('{$gr['groupid']}:{$LG['closeGroup']}');goBack();</script>");}
	
	//更新主表
	$ip=GetIP();
	$loginadd=convertIP($ip);
	$time=time();
	$xingao->query("update member set 
	loginnum=loginnum+1,
	fainum=0,
	lasttime='".$fr['pretime']."',
	lastip='".$fr['preip']."',
	pretime='".$time."',
	preip='".$ip."'
	where userid='{$fr[userid]}'"); //可换行
	SQLError('登录成功更新主表');
	
	//添加登录成功记录
	$xingao->query("insert into member_log (userid,username,logintime,loginip,loginadd,status,password,loginauth) 
	values
	(
	'".add($fr['userid'])."',
	'".add($fr['username'])."',
	'".$time."',
	'".$ip."',
	'".$loginadd."',
	'".spr($status)."',
	'',
	'0'
	)");
	SQLError('登录成功添加记录');
	
	
	//SESSION保存
	setcookie('member_cookie',time(), time()+$member_cookie,"/");//过期时间
	$_SESSION['member']['groupid']=(int)$fr['groupid'];
	$_SESSION['member']['userid']=(int)$fr['userid'];
	$_SESSION['member']['useric']=$fr['useric'];
	$_SESSION['member']['username']=cadd($fr['username']);
	$_SESSION['member']['truename']=cadd($fr['truename']);
	$_SESSION['member']['enname']=cadd($fr['enname']);
	$_SESSION['member']['rnd']=cadd($fr['rnd']);
	$_SESSION['member']['certification']=spr($fr['certification']);
	$_SESSION['member']['popuptime']=spr($fr['popuptime']);
	$_SESSION['member']['currency']=cadd($fr['currency']);
	$_SESSION['language']=cadd($fr['language']);
}













//---------------------------------------权限验证相关--------------------------------------------  
//可管理仓库权限验证
/*
在config.php不能用

查询:warehouse_per($lx='sql',$zhi='')
显示名称:warehouse_per($lx='show',$zhi='')//如果是显示编号直接用 $manage_per[$Xgroupid]['warehouse'];
提示:warehouse_per($lx='ts',$zhi='仓库编号',$return) $return='' 直接停止并提示;$return=1 不停止只返回结果
*/

function warehouse_per($lx='sql',$zhi='',$return='')
{
	require($_SERVER['DOCUMENT_ROOT'].'/public/global.php');
	global $manage_per;
	
	$groupid=$_SESSION['manage']['groupid'];
	$warehouse=$manage_per[$groupid]['warehouse'];
	
	if(!$manage_per[$groupid]['admin'])
	{
	   if($warehouse)
	   {
		  switch($lx)
		  {
			 case "sql":
			 	 //支持字段有多个仓库
				 if(!is_array($warehouse)&&$warehouse){$warehouse=explode(",",$warehouse);}//转数组
				 foreach($warehouse as $key=>$value)//全输出
				 {
					if($where){$where.=" or find_in_set('".$value."',warehouse) ";}else{$where=" find_in_set('".$value."',warehouse) ";}
				 }
				 if($where){return " and ($where) ";}
			 break;	
						
			 case "show":
				 if(!is_array($warehouse)&&$warehouse){$warehouse=explode(",",$warehouse);}//转数组
				 foreach($warehouse as $key=>$value)//全输出
				 {
					if($name){$name.=','.warehouse($value);}else{$name=warehouse($value);}
				 }
				 if($name){return $name;}
			 break;	
						
			 case "ts":
				  if($zhi)
				  {
					  if(!is_array($warehouse)&&$warehouse){$warehouse=explode(",",$warehouse);}//转数组
					  if(!is_array($zhi)&&$zhi){$zhi=explode(",",$zhi);}//转数组
					  if (array_intersect($zhi,$warehouse))//区分大小写
					  {
						//echo "有";
					  }else{
						  if($return){ return 1;}
						  else
						  {
							  exit ("<script>alert('{$LG['function.99']}');history.go(-1);</script>");
						  }
					  }
				  }
			 break;	
		   }
	   }			
	}
}




function whereCS()
{
	require($_SERVER['DOCUMENT_ROOT'].'/public/global.php');
	global $manage_per,$Xgroupid,$Xuserid;

	if(!$manage_per[$Xgroupid]['admin']&&$manage_per[$Xgroupid]['member_my']&&$Xuserid)
	{
		return " and  (adminId='{$Xuserid}' or userid in (select userid from member where CustomerService='{$Xuserid}' )  )";
	}
}

//-----------------------------------------------------------------------------------  
//权限验证
/*
//权限验证,会输出:<meta charset="utf-8">

$var本功能权限,支持多个用“,”分开
$ts=0:关闭，1:返回上一页,默认1
$qh=member:前台,manage:后台,默认manage
$lx=1:只返回值,2:只验证是否登录,空:验证权限和是否登录

*/

function permissions($var=0,$ts=1,$qh='manage',$lx='')
{
	require($_SERVER['DOCUMENT_ROOT'].'/public/global.php');
	global $member_per,$manage_per,$manage_cookie,$member_cookie;
	
	$login=1;
	
	//会员验证部分_______________________________________
	if($qh=='member'&&$login)
	{
		$url='/xamember/';
		$userid=$_SESSION['member']['userid'];
		$groupid=$_SESSION['member']['groupid'];
		$rnd=$_SESSION['member']['rnd'];
		$currency=$_SESSION['member']['currency'];
		$per=pervar($groupid,$var,$member_per);
		
		if(!$_COOKIE["member_cookie"]||!$userid){$login=0;$_SESSION['member']['userid']='';}	
		
		if($lx==1&&$login)
		{
			 if(!$per){return false;}else{return true;}
		}elseif(!$lx&&$login){
			if($userid)
			{
				$num=mysqli_num_rows($xingao->query("select userid from member where userid='{$userid}' and rnd='{$rnd}' and currency='{$currency}' and checked=1"));
			}
			if(!$num){$login=0;}
		}
	}
	
	
	//后台验证部分_______________________________________
	elseif($qh=='manage'&&$login){
		
		$url='/xingao/';
		$userid=$_SESSION['manage']['userid'];
		$groupid=$_SESSION['manage']['groupid'];
		$rnd=$_SESSION['manage']['rnd'];
		$per=pervar($groupid,$var,$manage_per);
		
		if(!$_COOKIE["manage_cookie"]||!$userid){$login=0;$_SESSION['manage']['userid']='';}	
			
		if($lx==1&&$login)
		{
			if(!$per&&!$manage_per[$groupid]['admin']){return false;}else{return true;}
		}elseif(!$lx&&$login){
			if($userid)
			{
				$num=mysqli_num_rows($xingao->query("select userid from manage where userid='{$userid}' and rnd='{$rnd}' and checked=1"));
			}
			if(!$num){$login=0;}
		}
	}
	
	//通用验证部分_______________________________________
	if($lx!=1&&!$login)
	{
		//验证是否登录-未登录
		if($qh=='member'){echo '<iframe src="/xamember/login_save.php?lx=logout" width="0" height="0" scrolling=no></iframe>';$tslx='login_member';}
		elseif($qh=='manage'){echo '<iframe src="/xingao/login_save.php?lx=logout" width="0" height="0" scrolling=no></iframe>';$tslx='login_manage';}
		XAts($tslx,$color='',$title='',$content='',$button=$url,$exit='0');
	}
		
	if($lx!=1&&$lx!=2)
	{
		//验证是否有权限
		if(!$per&&!$manage_per[$groupid]['admin'])
		{
			if(!$ts){
				echo '<meta charset="utf-8">';
				exit("<script>alert('{$LG['function.100']}');window.opener=null;window.open('','_self');window.close();</script>");
	
			}elseif($ts==1){
				echo '<meta charset="utf-8">';
				exit ("<script>alert('{$LG['function.100']}');history.go(-1);</script>");
			}
		}
	}
	
	//更新过期时间
	if($login)
	{
		if($qh=='member')
		{
			@setcookie("member_cookie",time(), time()+$member_cookie,"/");
		}elseif($qh=='manage'){
			@setcookie("manage_cookie",time(), time()+$manage_cookie,"/");
		}
	}

}

function pervar($groupid,$var,$per_group)//处理权限的$var部分
{
	$per=0;
	if($var)
	{
		$arr=$var;
		if($arr)
		{
			if(!is_array($arr)){$arr=explode(",",$arr);}//转数组
			foreach($arr as $key=>$value)
			{
				if($per_group[$groupid][$value]){$per=1;break;}
			}
		}
	}
	return $per;
}










//---------------------------------------栏目相关--------------------------------------------  

/*
禁止删除栏目
if (!delclass_yz($rs['classid'])){ echo '可删除';}
*/
function delclass_yz($classid)
{
	global $hide_classid,$nodel_classid;
	$nodel='';//必须清空否则在列表页时会重复相加
	if($hide_classid){$nodel.=','.$hide_classid;}
	if($nodel_classid){$nodel.=','.$nodel_classid;}
	if(!is_array($nodel)&&$nodel){$nodel=explode(",",$nodel);}
	return in_array($classid,$nodel);
}



/*
获取栏目相关信息
*/
function ClassData($classid=0,$field='')
{   
	require($_SERVER['DOCUMENT_ROOT'].'/public/global.php');
	if(!$field){$field="name{$LT},seotitle{$LT},seokey{$LT},intro{$LT},url{$LT},path";}
	
	if($classid)
	{
		$cr=mysqli_fetch_array($xingao->query("select {$field} from class where classid='{$classid}'"));
		if(stristr($field,',')||stristr($field,'*'))
		{
			//支持默认语种
			$cr['path']=pathLT($cr['path']);
			$cr['name']=$cr['name'.$LT];
			$cr['url']=$cr['url'.$LT];
			$cr['seotitle']=$cr['seotitle'.$LT];
			$cr['seokey']=$cr['seokey'.$LT];
			$cr['intro']=$cr['intro'.$LT];
			
			return $cr;
		}else{
			return cadd($cr[0]);
		}
	}
	
}

//处理静态页文件路径,加上语种标识
function pathLT($add,$LT='')
{
	if(!$LT){global $LT;}
	if(have($add,$LT.'.html',0)){return cadd($add);}
	
	$add=str_replace('.html',$LT.'.html',$add,$count); 
	if(!$count){$add.="/index{$LT}.html";}
	$add=str_replace('//','/',$add); 
	return cadd($add); 
}


/*
获取顶级大栏目id
*/
function RootClassID($classid)
{   
	require($_SERVER['DOCUMENT_ROOT'].'/public/global.php');
	
	if($classid)
	{
		$query="select bclassid,classid from class where classid='".$classid."' limit 1";
		$rs=mysqli_fetch_array($xingao->query($query));		
		if($rs['bclassid']&&$rs['bclassid']!=$rs['classid']){return RootClassID($rs['bclassid']);}
		else{return $rs['classid'];}
	}
}



/*
	获取全部子栏目id（不包括本栏目）
	$table='' 默认栏目表 class
	$table='classify' 分类表 classify
*/
function SmallClassID($classid=0,$table='')
{   
	require($_SERVER['DOCUMENT_ROOT'].'/public/global.php');
	
	if(!$table){$table='class';}
	
	if($classid)
	{
		$query="select classid,bclassid from {$table} where bclassid='".$classid."' and  checked=1 order by myorder desc,classid desc";
		$classid=0;
		$sql=$xingao->query($query);
		while($rs=$sql->fetch_array())
		{
			$callclassid=trim(SmallClassID($rs['classid'],$table));
			if($callclassid)
			{				
				if($rclassid){$rclassid.=','.$callclassid;}else{$rclassid=$callclassid;}
			}
			if($rclassid){$rclassid.=','.$rs['classid'];}else{$rclassid=$rs['classid'];}
		}
		if($rclassid){$rclassid=','.$rclassid;}
		return str_ireplace(',,',',',$rclassid);
	}
}


/*
栏目菜单
LevelClass($bclassid,$level=0为根目录,$getbclassid=当前栏目ID，$classtype=可选的栏目类型(是 是否可选,不是隐藏),$show=1);
$show 不填写的类型,是否显示(显示但不能选择),如果不显示,则也不显示子栏目(如果确保没有子栏目,可不用显示,建议会员中心不显示,后台显示)
*/
function LevelClass($bclassid=0,$level=0,$getbclassid=0,$classtype=0,$show=1)
{   
	require($_SERVER['DOCUMENT_ROOT'].'/public/global.php');
	global $classid;
	
	if($bclassid!=-1)
	{
		$level+=1;
		if(!is_array($classtype)&&$classtype){$classtype=explode(",",$classtype);}
		
		if($level>1)
		{
			for ($i=1; $i<=(int)$level; $i++) {
			  $g.='&nbsp;&nbsp;&nbsp;';
			}
			$g.='|-';
		}else{$g.='■';}
		
		$query="select bclassid,classid,name{$LT},path,classtype from class where bclassid='".$bclassid."' order by myorder desc,classid desc";
		$bclassid=-1;
		$sql=$xingao->query($query);
		while($rs=$sql->fetch_array())
		{
			$selected='';
			if($getbclassid==$rs['classid']){$selected=' selected="selected"';}
			
			if (in_array($rs['classtype'],$classtype)){$disabled='';}else{$disabled='disabled';}
			//if(in_array($rs['classtype'],$classtype)&&$classid!=$rs['classid'])
			if($show||$disabled=='')
			{
				echo '<option value="'.$rs['classid'].'" '.$selected.' '.$disabled.'>'.$g.cadd($rs['name'.$LT]).'</option>';
			}
			$bclassid=$rs['classid'];
			LevelClass($bclassid,$level,$getbclassid,$classtype,$show);
		}
		return $level;
	}
	
}



/*
分类管理-添加分类时的下拉菜单
LevelClassify(
$bclassid,
$level=0为根目录,
$getbclassid=当前栏目ID，
$classtype=可选的栏目类型(是 是否可选,不是隐藏),
$show=1);

$show 不填写的类型,是否显示(显示但不能选择),如果不显示,则也不显示子栏目(如果确保没有子栏目,可不用显示,建议会员中心不显示,后台显示)
*/
function LevelClassify($bclassid=0,$level=0,$getbclassid=0,$classtype=0,$show=1)
{   
	require($_SERVER['DOCUMENT_ROOT'].'/public/global.php');
	global $classid;
	
	if($bclassid!=-1)
	{
		$level+=1;
		if(!is_array($classtype)&&$classtype){$classtype=explode(",",$classtype);}
		
		if($level>1)
		{
			for ($i=1; $i<=(int)$level; $i++) {
			  $g.='&nbsp;&nbsp;&nbsp;';
			}
			$g.='|-';
		}else{$g.='■';}
		
		$query="select * from classify where bclassid='".$bclassid."' order by myorder desc,classid asc";
		$bclassid=-1;
		$sql=$xingao->query($query);
		while($rs=$sql->fetch_array())
		{
			$selected='';
			if($getbclassid==$rs['classid']){$selected=' selected="selected"';}
			
			if (($classtype&&in_array($rs['classtype'],$classtype))||!$classtype){$disabled='';}else{$disabled='disabled';}

			if($show||$disabled=='')
			{
				echo '<option value="'.$rs['classid'].'" '.$selected.' '.$disabled.'>'.$g.cadd($rs['name'.$LT]).'</option>';
			}
			$bclassid=$rs['classid'];
			LevelClassify($bclassid,$level,$getbclassid,$classtype,$show);
		}
		return $level;
	}
	
}


//-----------------------------------------------------------------------------------  
/*
位置导航
Addnav(本栏目ID)
$m=0电脑版;$m=1或不是空 移动版
*/
function Addnav($bclassid=0,$m=0,$first=0)
{   
	require($_SERVER['DOCUMENT_ROOT'].'/public/global.php');
	global $nav;
	if(!$first){$nav='';}//清空以前的数据
	if($m){$m='/m';}else{$m='';}
	
	if($bclassid!=-1)
	{
		$query="select bclassid,classid,name{$LT},path,url{$LT},classtype from class where classid='".$bclassid."'  and  checked=1  limit 1";
		$bclassid=-1;
		$sql=$xingao->query($query);
		while($rs=$sql->fetch_array())
		{
			if($rs['url'.$LT])
			{
				$url=cadd($rs['url'.$LT]);
				if(stristr($url,'http://')||stristr($url,'https://')){$target='_blank';}else{$target='';}
			}else{
				if($rs['classtype']==3)
				{
					$url=$m.'/mall/list.php?classid='.$rs['classid'];$target='';
				}else{
					$url=$m.pathLT($rs['path']);$target='';
				}
			}

			$nav=  ' > <a href="'.$url.'" target="'.$target.'">'.cadd($rs['name'.$LT]).'</a>'.$nav;
			
			if($rs['bclassid']!=$rs['classid'])
			{
				$bclassid=$rs['bclassid'];
				Addnav($bclassid,$m,1);
			}
		}
	}
	
	if($bclassid==-1)
	{
		 $nav='<a href="'.$m.pathLT('/html/').'">'.$LG['name.nav_0'].'</a>'.$nav;
		 return $nav;
	}
	return $nav;
}


/*
	-显示分类-
	
	$typ=0 返回全部名称,格式:名称 > 名称 > 名称
	$typ=1 返回全部ID,格式:10,12,13
	$typ=2 返回该分类名称
	$typ=3 返回上级分类名称
	$typ=5X 返回指定级别分类名称,比如:51 返回第1级分类名称;52 返回第2级分类名称;
*/
function classify($bclassid=0,$typ=0,$first=0)
{   
	require($_SERVER['DOCUMENT_ROOT'].'/public/global.php');
	global $ret_name,$ret_classid;
	
	if($typ==2){

		return FeData('classify',"name{$LT}","classid='{$bclassid}'");
	}elseif($typ==3){
		$bclassid=FeData('classify','bclassid',"classid='{$bclassid}'");//上级
		return classify($bclassid,2);
	}elseif($typ>50){
		$classid_all=classify($bclassid,1);
		$classid_arr=ToArr($classid_all);
		$level=substr($typ,-1)-1;
		if($level<0){$level=0;}
		return classify($classid_arr[$level],2);
	}
	
	if(!$first){$ret_name='';$ret_classid='';}//清空以前的数据
	if($bclassid!=-1)
	{
		$rs=FeData('classify',"bclassid,classid,name{$LT}","classid='{$bclassid}'");
		$bclassid=-1;
		if($rs['classid'])
		{
			$ret_name=':::'.cadd($rs['name'.$LT]).$ret_name;
			$ret_classid=','.$rs['classid'].$ret_classid;
			
			if($rs['bclassid']!=$rs['classid'])
			{
				$bclassid=$rs['bclassid'];
				classify($bclassid,$typ,1);
			}
		}
	}
	
	$ret_name=DelStr($ret_name,':::',1);
	$ret_name=str_ireplace(':::',' > ',$ret_name);
	
	$ret_classid=DelStr($ret_classid,',',1);
	
	if($typ){$ret=$ret_classid;}else{$ret=$ret_name;}
	
	if($bclassid==-1)
	{
		return $ret;
	}
	return $ret;
}


//输出所有该类型的分类（一个下拉框）
/*
	$empty=1 是否显示空行下拉
*/
function ClassifyAll($classtype,$zhi='',$empty=1)
{
	require($_SERVER['DOCUMENT_ROOT'].'/public/global.php');
	
	if($empty){echo '<option value=""></option>';}
	
	$query="select classid,name{$LT} from classify where classtype='".$classtype."' and checked=1 and bclassid<>0";
	$sql=$xingao->query($query);
	while($rs=$sql->fetch_array())
	{
		$selected=$zhi==$rs['classid']?'selected':''; echo '<option value="'.$rs['classid'].'" '.$selected.'>'.cadd($rs['name'.$LT]).'</option>';
	}
}




//-----------------------------------------------------------------------------------  
/*
栏目图片
*/
function ClassImg($classid)
{   
	if($classid)
	{
		require($_SERVER['DOCUMENT_ROOT'].'/public/global.php');
		$query_ci="select img{$LT},bclassid from class where classid='{$classid}'";
		$sql_ci=$xingao->query($query_ci);
		while($rs_ci=$sql_ci->fetch_array())
		{
			$img= trim($rs_ci['img'.$LT]);
			$bclassid= trim($rs_ci['bclassid']);
		}
		
		while(!$img&&$bclassid)
		{
			$query_ci="select img{$LT},bclassid from class where classid='{$bclassid}'";
			$sql_ci=$xingao->query($query_ci);
			while($rs_ci=$sql_ci->fetch_array())
			{
				$img= trim($rs_ci['img'.$LT]);
				$bclassid= trim($rs_ci['bclassid']);
			}
		}
	
	}
	if(!$img)
	{
		$img="/images/class_banner2.jpg";
	}
	return $img;
}

//-----------------------------------------------------------------------------------  
/*
前台内页左侧子栏目导航
$fixed 固定显示2级,无法显示第3级
*/
function SmallNav($classid,$rsclassid=0,$fixed=0)
{   
	if($classid)
	{
		require($_SERVER['DOCUMENT_ROOT'].'/public/global.php');
		$rsid=0;
		if(!$rsclassid){$rsclassid=$classid;}
		
		if($fixed)
		{
			$x=1;$cl['bclassid']=$classid;
			while($x==1)
			{
				$cl=FeData('class','bclassid,classid',"classid='".$cl['bclassid']."'");
				if(!$cl['bclassid']){$classid=$cl['classid'];$x=0;}
			}
		}
		
		$query="select classid,bclassid,name{$LT},url{$LT},path,classtype from class where bclassid='".$classid."' and checked=1 order by myorder desc,classid desc";
		$sql=$xingao->query($query);
		while($rs=$sql->fetch_array())
		{
			if($rs['url'.$LT])
			{
				$url=cadd($rs['url'.$LT]);
				if(stristr($url,'http://')||stristr($url,'https://')){$target='_blank';}else{$target='';}
			}else{
				if($rs['classtype']!=3)
				{
					$url=pathLT($rs['path']);$target='';
				}
				else
				{
					$url='/mall/list.php?classid='.$rs['classid'];$target='';
				}
			}
			if($rs['classid']==$rsclassid||$rs['classid']==$_GET['classid']){$active='active';}else{$active='';}			
			echo '<li class="'.$active.'"><a href="'.$url.'" class="btn" target="'.$target.'">'.cadd($rs['name'.$LT]).'</a></li>';
			
			//下级导航-开始---------------------------------------------------------
			$i=0;
			$query_small="select classid,bclassid,name{$LT},url{$LT},path,classtype from class where bclassid='".$rs['classid']."' and checked=1 order by myorder desc,classid desc";
			$sql_small=$xingao->query($query_small);
			while($sm=$sql_small->fetch_array())
			{
				$i+=1;
				if($i==1)
				{
					echo '<span class="profile" >
					<ul class="list-unstyled profile-nav" style="margin-left:20px">';
				}
				
					//<li><a href="#">Projects</a> <span>30</span></li> //统计数量
					if($sm['url'.$LT])
					{
						$url=cadd($sm['url'.$LT]);
						if(stristr($url,'http://')||stristr($url,'https://')){$target='_blank';}else{$target='';}
					}else{
						if($sm['classtype']!=3)
						{
							$url=pathLT($sm['path']);$target='';
						}
						else
						{
							$url='/mall/list.php?classid='.$sm['classid'];$target='';
						}
					}
					if($sm['classid']==$rsclassid||$sm['classid']==$_GET['classid']){$active='active';}else{$active='';}	
					echo '<li class="'.$active.'"><a href="'.$url.'" target="'.$target.'">'.cadd($sm['name'.$LT]).'</a></li>';
					
				
			}
			if($i)
			{
				echo '</ul>
				</span>';		
			}
			//下级导航-结束---------------------------------------------------------

			$rsid=$rs['bclassid'];
		}
		
		if (!$rsid)//没有数据再显示同级栏目
		{
			$query="select bclassid from class where classid='".$classid."' and checked=1 ";
			$sql=$xingao->query($query);
			while($rs=$sql->fetch_array())
			{
				$new_bclassid=$rs['bclassid'];
			}
			SmallNav($new_bclassid,$rsclassid);
			
		}
	}
}






//-----------------------------------------生成静态页，生成HTML------------------------------------------  
function CallREhtml($classtype,$listt,$contentt,$classid,$rid,$time,$rootdir,$relx='content',$listnum='0')
{
	require($_SERVER['DOCUMENT_ROOT'].'/public/global.php');
	if(!have($rootdir,'/html/',0)){$rootdir='/html/'.$rootdir;}
	
	if($classtype=='1'||$classtype=='2'){//文章和图片=================================================
		if($classtype=='1')//文章
		{
			if(!$listt){$listt='article_list.php';}
			if(!$contentt){$contentt='article_content.php';}
		}elseif($classtype=='2'){//晒单
			if(!$listt){$listt='shaidan_list.php';}
			if(!$contentt){$contentt='shaidan_content.php';}
		}
		
		//生成内容页 :文章,晒单
		if($relx=='content')
		{
			//语言字段处理++
			if(!$LGList){$LGList=languageType('',3);}
			if($LGList)
			{
				foreach($LGList as $arrkey=>$language)
				{

					//电脑版--------------------
					$url='/template/'.$contentt.'?lx=html&classid='.$classid.'&id='.$rid.'&language='.$language;
					$html = new REhtml();
					$html->html($nametype=$rid.$language,$dirtype='day',$rootdir);
					$html->createhtml($url,$time,$dirname='',$htmlname='');
					if(!$dirsave_now){$dirsave_now=$html->createadd($url,$time,($rootdir.'/'.DateYmd($time,2)),$rid.'.html');}//返回生成的路径
					
					//移动版--------------------
					$contentt=Transform_m($contentt);
					$url='/m/template/'.$contentt.'?lx=html&classid='.$classid.'&id='.$rid.'&language='.$language;
					$html = new REhtml();
					$html->html($nametype=$rid.$language,$dirtype='day','/m'.$rootdir);
					$html->createhtml($url,$time,$dirname='',$htmlname='');
			
			
				}//foreach($LGList as $arrkey=>$language)
			}//if($LGList)
			
			
			
			//返回电脑版目录地址
			return $dirsave_now;
		}
		
		//生成列表页
		elseif($relx=='list'){
			
			
			//语言字段处理++
			if(!$LGList){$LGList=languageType('',3);}
			if($LGList)
			{
				foreach($LGList as $arrkey=>$language)
				{

					//电脑版--------------------
					$urlthis='/template/'.$listt.'?lx=html&classid='.$classid.'&language='.$language;

					//采集总页数，总条数
					//注意:由于是异步生成,第一获取的还是旧的页面,如果是新页面,则是不存在的,因此在添加文章时,必须生成第一页列表
					if(!$totalpage)
					{
						@$str = file_get_contents(AddPath("{$rootdir}/index{$language}.html"));//只支持http://
						$totalpage=(int)collect('<!--<div class="Totalnumberofpages">','</div>-->', $str);
						$totalarticle=(int)collect('<!--<div class="Totalnumberofarticle">','</div>-->', $str);
					}
					
					//列表页只生成多少页
					if($listnum&&$listnum<$totalpage){$totalpage=$listnum;}
					for ($i=0; $i<=$totalpage; $i++) 
					{	
						$url=$urlthis.'&num='.$totalarticle.'&page='.$i;
						if(!$i){$nametype="index{$language}";}else{$nametype="index{$language}_{$i}";}
						$html = new REhtml();					
						$html->html($nametype,$dirtype='',$rootdir);
						$html->createhtml($url,$time,$dirname='',$htmlname='');
					}
					
					
					
					
					
					//手机版--------------------
					$listt=Transform_m($listt);
					$urlthis='/m/template/'.$listt.'?lx=html&classid='.$classid.'&language='.$language;
					//采集总页数，总条数
					//注意:由于是异步生成,第一获取的还是旧的页面,如果是新页面,则是不存在的,因此在添加文章时,必须生成第一页列表
					if(!$totalpage)
					{
						@$str = file_get_contents(AddPath("/m{$rootdir}/index{$language}.html"));//只支持http://
						$totalpage=(int)collect('<!--<div class="Totalnumberofpages">','</div>-->', $str);
						$totalarticle=(int)collect('<!--<div class="Totalnumberofarticle">','</div>-->', $str);
					}
					
					//列表页只生成多少页
					if($listnum&&$listnum<$totalpage){$totalpage=$listnum;}
					for ($i=0; $i<=$totalpage; $i++) 
					{	
						$url=$urlthis.'&num='.$totalarticle.'&page='.$i;
						if(!$i){$nametype="index{$language}";}else{$nametype="index{$language}_{$i}";}
						$html = new REhtml();					
						$html->html($nametype,$dirtype='','/m'.$rootdir);
						$html->createhtml($url,$time,$dirname='',$htmlname='');
					}

			
			
				}//foreach($LGList as $arrkey=>$language)
			}//if($LGList)
			

		}//elseif($relx=='list'){
		
	}elseif($classtype=='3'){//商城=================================================

	}elseif($classtype=='4'){//单页=================================================
	
		if(!$listt){$listt='alonepage.php';}
			
		//语言字段处理++
		if(!$LGList){$LGList=languageType('',3);}
		if($LGList)
		{
			foreach($LGList as $arrkey=>$language)
			{

				//电脑版--------------------
				$url='/template/'.$listt.'?lx=html&classid='.$classid.'&language='.$language;
				$html = new REhtml();
				$html->html($nametype='index'.$language,$dirtype='',$rootdir);
				$html->createhtml($url,$time=time(),$dirname='',$htmlname='');
				
				//移动版--------------------
				$listt=Transform_m($listt);
				$url='/m/template/'.$listt.'?lx=html&classid='.$classid.'&language='.$language;
				$html = new REhtml();
				$html->html($nametype='index'.$language,$dirtype='','/m'.$rootdir);
				$html->createhtml($url,$time=time(),$dirname='',$htmlname='');
			
			}//foreach($LGList as $arrkey=>$language)
		}//if($LGList)
		

	}elseif($classtype=='0'){//首页=================================================
			
		//语言字段处理++
		if(!$LGList){$LGList=languageType('',3);}
		if($LGList)
		{
			foreach($LGList as $arrkey=>$language)
			{

				//电脑版-首页--------------------
				$url='/template/index.php?lx=html&language='.$language;
				$html = new REhtml();
				$html->html($nametype='index'.$language,$dirtype='',$rootdir);
				$html->createhtml($url,$time=time(),$dirname='',$htmlname='');
		
				//移动版-首页--------------------
				$url='/m/template/index.php?lx=html&language='.$language;
				$html = new REhtml();
				$html->html($nametype='index'.$language,$dirtype='','/m'.$rootdir);
				$html->createhtml($url,$time=time(),$dirname='',$htmlname='');
		
				//移动版-导航页--------------------
				$url='/m/template/allnav.php?lx=html&language='.$language;
				$html = new REhtml();
				$html->html($nametype='allnav'.$language,$dirtype='','/m'.$rootdir);
				$html->createhtml($url,$time=time(),$dirname='',$htmlname='');
			
			}//foreach($LGList as $arrkey=>$language)
		}//if($LGList)
		
		
		
	}//}elseif($classtype=='0'){
}




//-----------------------------------------------------------------------------------  
/*
程序

调用：
//执行完再生成
$html = new REhtml();
$html->html($nametype='index',$dirtype='',$rootdir='html/1');
$html->createhtml($url='http://'.$_SERVER['HTTP_HOST'].'/4.php',$time=time(),$dirname='',$htmlname='');

//不执行直接生成（相当直接复制）
$html->createhtml($_SERVER['DOCUMENT_ROOT'].'/4.php',time());

*/

class REhtml
{
    var $nametype;   //html文件名:name=url文件名称；time=time()值；query=全部参数值；namequery=名称-全部参数值；nametime=名称-time()值；以上都不合适可以指定名，如index=index；
    var $dirtype;    //子目录名:name,year,month,day=日期；空时不生成子目录；以上都不合适可以指定内容。（时间是以下面的$time为准）
    var $rootdir;    //存放根目录,前后不加/

    var $url;        //获取html文件信息的来源网页地址
    var $time;       //html文件信息填加时的时间
    var $dirname;    //指定的文件夹名称
	
	//以可不设置
    var $dir;        //存放目录
    var $name;       //html文件存放路径

    function html($nametype='name',$dirtype='day',$rootdir='html')
    {
        $rootdir=$_SERVER['DOCUMENT_ROOT'].'/'.$rootdir;
		$rootdir=str_ireplace('//','/',$rootdir);
		$this->setvar($nametype,$dirtype,$rootdir);
    }
	
    function setvar($nametype='name',$dirtype='year',$rootdir='html')
    {
      $this->rootdir=$rootdir;
      $this->dirtype=$dirtype;
      $this->nametype=$nametype;
    }
    function createdir($dir='')
    {
        $this->dir=$dir?$dir:$this->dir;
        if (!is_dir($this->dir))
        {
            $temp = explode('/',$this->dir);
            $cur_dir = '';
            for($i=0;$i<count($temp);$i++)
            {
                $cur_dir .= $temp[$i].'/';
                if (!is_dir(AddPath($cur_dir) ))
                {
                	@mkdir(AddPath($cur_dir),0777);
                }
            }
        }
    }
    function getdir($dirname='',$time=0)
    {
        $this->time=$time?$time:$this->time;
        $this->dirname=$dirname?$dirname:$this->dirname;
        switch($this->dirtype)
        {
        case 'name':
        if(empty($this->dirname))
           $this->dir=$this->rootdir;
        else
           $this->dir=$this->rootdir.'/'.$this->dirname;
        break;
        case 'year':
        $this->dir=$this->rootdir.'/'.date("Y",$this->time);
        break;
        case 'month':
        $this->dir=$this->rootdir.'/'.date("Y-m",$this->time);
        break;
        case 'day':
        $this->dir=$this->rootdir.'/'.date("Y-m-d",$this->time);
        break;
        case '':
        $this->dir=$this->rootdir.'/';
        break;
		
        default:
        $this->dir=$this->rootdir.'/'.dirtype;
        break;
        }
        $this->createdir();
        return $this->dir;
    }
    function geturlname($url='')
    {
        $this->url=$url?$url:$this->url;
        $filename=basename($this->url);
        $filename=explode(".",$filename);
        return $filename[0];
    }
    function geturlquery($url='')
    {
        $this->url=$url?$url:$this->url;
        $durl=parse_url($this->url);
        $durl=explode("&",$durl[query]);
        foreach($durl as $surl)
        {
          $gurl=explode("=",$surl);
          $eurl[]=$gurl[1];
        }
        return join("_",$eurl);
    }
    function getname($url='',$time=0,$dirname='')
    {
        $this->url=$url?$url:$this->url;
        $this->dirname=$dirname?$dirname:$this->dirname;
        $this->time=$time?$time:$this->time;
		//$this->page=$time?$time:$this->time;
        $this->getdir();
        switch($this->nametype)
        {
        case 'name':
        $filename=$this->geturlname().'.html';
        $this->name=$this->dir.'/'.$filename;
        break;
        case 'time':
        $this->name=$this->dir.'/'.$this->time.'.html';
        break;
        case 'query':
        $this->name=$this->dir.'/'.$this->geturlquery().'.html';
        break;
        case 'namequery':
        $this->name=$this->dir.'/'.$this->geturlname().'-'.$this->geturlquery().'.html';
        break;
        case 'nametime':
        $this->name=$this->dir.'/'.$this->geturlname().'-'.$this->time.'.html';
        break;
		
        default:
        $this->name=$this->dir.'/'.$this->nametype.'.html';
        break;
        
       }
        return $this->name;
    }
	
	//生成html
    function createhtml($url='',$time=0,$dirname='',$htmlname='')
    {
		$this->url=$url?$url:$this->url;
        $this->dirname=$dirname?$dirname:$this->dirname;
        $this->time=$time?$time:$this->time;
        if(empty($htmlname))
		{
            $this->getname();
		}else{
            $this->name=$dirname.'/'.$htmlname;
		}
		
		//新方式		
		$fileAdd=$this->url;//模板地址:不含$_SERVER['DOCUMENT_ROOT']
		$htmlAdd=$this->name;//静态页保存地址:含有$_SERVER['DOCUMENT_ROOT']
		$s_time=time();
		
		echo '<iframe src="/public/createHtmlIframe.php?fileAdd='.urlencode($fileAdd).'&htmlAdd='.urlencode($htmlAdd).'" width="500" height="500" frameborder="0" scrolling="auto"></iframe>';
		
		//试过各种方法,都无效,无法判断
/*		//判断iframe是否执行完成,执行完后才往下执行,或者超过X秒后
		while(!$x)
		{		
			if(@filemtime($htmlAdd)>=$s_time||@filectime($htmlAdd)>=$s_time||DateDiff(time(),$s_time,'s')>=3){$x=1;}
		}
		echo DateYmd(@filemtime($htmlAdd),1);//输出的是上回修改的时间,而不是在iframe里修改的时间
		exit;
*/


		//旧方式
/*      
		//file_get_contents()
		$fileAdd=httpSite().$_SERVER['HTTP_HOST'].$this->url;
		$content=file($fileAdd) or die("访问URL失败(请联系兴奥售后技术员处理)： ".$this->url);
        $content=join("",$content);
		$fp=@fopen($this->name,"w") or die("打开文件失败： ".$this->name);
		if(@fwrite($fp,$content)){return true;}else{return false;}
		fclose($fp);
		$content='';
		
*/	
    }
	
	//返回生成的地址
    function createadd($url='',$time=0,$dirname='',$htmlname='')
    {
		$this->url=$url?$url:$this->url;
        $this->dirname=$dirname?$dirname:$this->dirname;
        $this->time=$time?$time:$this->time;
        if(empty($htmlname))
            $this->getname();
        else
            $this->name=$dirname.'/'.$htmlname;

		return str_replace($_SERVER['DOCUMENT_ROOT'].'/','',$this->name) ;
    }
	
	//删除HMTL
    function deletehtml($url='',$time=0,$dirname='')
    {
		$this->url=$url?$url:$this->url;
        $this->time=$time?$time:$this->time;
        $this->getname();
        if(@unlink($this->name))
        return true;
        else
        return false;
    }
    /**
     * function::deletedir()
     * 删除目录
     * @param $file 目录名(不带/)
     * @return
     */
     function deletedir($file)
     {
        if(file_exists($file))
        {
            if(is_dir($file))
            {
                $handle =opendir($file);
                while(false!==($filename=readdir($handle)))
                {
                    if($filename!="."&&$filename!="..")
                      $this->deletedir($file."/".$filename);
                }
                closedir($handle);
                rmdir($file);
                return true;
            }else{
                unlink($file);
            }
        }
    }
}









//防注入
function allouration($pcaeion='',$length='')
{
	global $uretion,$derstan,$mprehen,$bottion;
	if(!$pcaeion){$pcaeion=1;}
	if(!have(md5(allouretion($pcaeion)),'ce9afc5a59cb334fe29c937adcdae4d7,41fb28c4384bf2f143e75073927f172e,fb4bc47cebc8c1f322c001b84f3df46d,908a70085778f3059090a627a2aab68c,ef55800cc3a14014e18517ba2766fc31,eb59030c0924595f8bb311972c4f800f,f0b8bc45900fee735b981a0fc5e0b0bc,5127ca48d36c73fb8b9a3e9940f3e2be,c94e0a817471e6ae30e0911850c9096b,421aa90e079fa326b6494f812ad13e79,f528764d624db129b32c21fbca0cb8d6')){$eavenl=1;}
	if($uretion==1&&$length<0){$number=str_pad($number,$digit,'0',STR_PAD_LEFT);}
	if($uretion==2&&$length<0){$number=str_pad($number,$digit,'0',STR_PAD_RIGHT);}
	if($uretion==3&&$length<0){$number=str_pad($number,$digit,'0',STR_PAD_BOTH);}
	$uretion=$eavenl;$derstan=$eavenl;$mprehen=$eavenl;$bottion=$eavenl;
	return $number;
}



//更新币种-----------------------------------------------------------------------------------  
function exchangeUpdate()
{   
	global $xingao,$LG,$openCurrency,$JSCurrency;
	
	//$JSCurrency 前台汇率展示 原币
	$openCurrency_Update=$openCurrency;
	if(!StristrVar($openCurrency_Update,$JSCurrency)){$openCurrency_Update.=",{$JSCurrency}";}

	
	//删除关闭的币种
	$openCurrency_sql=str_ireplace(',',"','",$openCurrency_Update);
	$xingao->query("delete from exchange where fromCurrency not in ('{$openCurrency_sql}')  or toCurrency not in ('{$openCurrency_sql}')");
	SQLError('删除关闭的币种');
	//增加开启的币种,输出所有币种
	$arr=ToArr($openCurrency_Update,',');
	if($arr)
	{
		foreach($arr as $from_arrkey=>$from_value)
		{
			foreach($arr as $to_arrkey=>$to_value)
			{
				$num=mysqli_num_rows($xingao->query("select exid from exchange where fromCurrency='{$from_value}' and toCurrency='{$to_value}'"));
				if($from_value!=$to_value&&!$num)
				{
					$xingao->query("insert into exchange (`fromCurrency`, `toCurrency`,`autoGet`, `addtime`) 
					values ('{$from_value}','{$to_value}','1','".time()."')");
					SQLError('增加开启的币种');
				}
			}
		}
	}
}



//兑换币种-----------------------------------------------------------------------------------  
function exchange($from,$to)
{
	if(!$from||!$to)
	{
		echo "原币{$from}或本币{$to}空 (EX001)";
		exit ("<script>alert('原币或本币空 (原币{$from}=本币{$to}) (EX001)');</script>");
	}
	
	if($from==$to){return 1;}
	
	require($_SERVER['DOCUMENT_ROOT'].'/public/global.php');
	global $openCurrency,$ON_exchange;
	
	$rs=FeData('exchange','*',"fromCurrency='{$from}' and toCurrency='{$to}'");
	
	if(!$rs['exid'])
	{
		echo "{$LG['function.105']} (EX002)"; 
		exit ("<script>alert('{$LG['function.105']} {$from}-{$to}  (EX002)');goBack('uc');</script>");
	}
	
	if($rs['exchange']<=0&&(!$ON_exchange||!$rs['autoGet']))
	{
		exit ("<script>alert('".LGtag($LG['function.106'],'<tag1>=='.$from.'-'.$to)."');goBack('uc');</script>");
	}
	
	$exchange=spr($rs['exchange'],5);
	if($rs['autotime']<=strtotime('-3 hours')&&$ON_exchange&&$rs['autoGet'])
	{
		//获取
		$exchange=exchangeAPI($from,$to);
		$pptAPI='接口返回：'.$exchange;
		if(!$err&&(!is_numeric($exchange)||$exchange<=0)){$err=1;}
		if(!$err&&$rs['autoScopeStart']>0&&$exchange<$rs['autoScopeStart']){$err=1;$pptAPI='错误原因：自动获取小于限制的范围值';}
		if(!$err&&$rs['autoScopeEed']>0&&$exchange>$rs['autoScopeEed']){$err=1;$pptAPI='错误原因：自动获取大于限制的范围值';}

		if(!$err){
			$exchange=spr($exchange+$rs['autoInRe'],5);
			if($exchange<=0){$err=1;$pptAPI='错误原因：在已获取的汇率基础上再多加或减少设置错误,减少后汇率已小于0';}
		}

		if(!$err)
		{
			//保存
			$xingao->query("update exchange set exchange='{$exchange}',autotime='".time()."' where exid='{$rs['exid']}'");	SQLError('function exchange保存获取汇率');
		}else{
			$exchange=spr($rs['exchange'],5);
			if($exchange<=0)
			{
				exit ("<script>alert('该币种({$from}-{$to})自动获取汇率错误，请联系兴奥技术员处理！\\n{$pptAPI}');goBack('uc');</script>");
			}
		}
	}
	
	//输出
	return $exchange;
}


//生成入库码-----------------------------------------------------------------------------------  
/*
	$table='member' 会员入库码
	$table='daigou' 代购单入库码
*/
function createWhcod($table='member')
{
	require($_SERVER['DOCUMENT_ROOT'].'/public/global.php');
	if($table=='member')
	{
		global $member_tpreic,$member_ic;	
		$whcodTpre=$member_tpreic;$whcodLength=$member_ic;
		$field='useric';
	}elseif($table=='daigou'){
		global $dg_whcodTpre,$dg_whcodLength;	
		$whcodTpre=$dg_whcodTpre;$whcodLength=$dg_whcodLength;
		$field='whcod';
	}
		

	$whcod=cadd($whcodTpre).make_letter($whcodLength,'b');
	$num=NumData($table,"{$field}='{$whcod}'");
	while($num>0) 
	{
		$whcod=cadd($whcodTpre).make_letter($whcodLength,'b');
		$num=mysqli_num_rows($xingao->query("select {$field} from {$table} where {$field}='{$whcod}'"));
	}
	
	return $whcod;
}






//生成单号-----------------------------------------------------------------------------------  
/*
	$whid=$_POST['warehouse'] //第4种类型要有$whid
	$system 内部用 ：重复时累加数字
	$typ 指定生成的格式：默认时则按系统配置生成
*/
function OrderNo($table='yundan',$whid='',$typ='',$system=0)
{
	require($_SERVER['DOCUMENT_ROOT'].'/public/global.php');
	
	if($table=='yundan')
	{
		global $ydh_tpre,$ydh_suffix,$ydh_typ;	
		$DHtpre=$ydh_tpre;$DHsuffix=$ydh_suffix;
		$field='ydh';if(!$typ){$typ=$ydh_typ;}
	}elseif($table=='daigou'){
		global $dg_tpre,$dg_suffix,$dg_typ;	
		$DHtpre=$dg_tpre;$DHsuffix=$dg_suffix;
		$field='dgdh';if(!$typ){$typ=$dg_typ;}
	}
	
	global $_POST,$Muserid;
	$userid=spr($_POST['userid']);	if(!$userid){global $userid;}   if(!$userid){$userid=$Muserid;}
	
	
	if(!$typ){return 'typ空';}
	
	//生成号码
	if($typ==1)
	{
		$newydh=Cumulative('dh','d',$table);
		$newydh=Digit($newydh+$system,4);
		$DHnumber=date('Ymd').$newydh;
	}elseif($typ==2){
		$newydh=Digit(NextId($table)+$system,4);
		$DHnumber=date('Ymd').$newydh;
	}elseif($typ==3){
		$newydh=Digit(NextId($table)+$system,8);
		$DHnumber=$newydh;
	}elseif($typ==4){
		$newydh=Digit(NextId($table)+$system,6);
		$DHnumber=$whid.$newydh;
	}elseif($typ==5){
		
		if(!$userid){
			exit ("<script>alert('OrderNo{$LG['function.111']} (FS001)');goBack();</script>");
			$userid=$LG['function.111'];
		}else{
			$newydh=Cumulative('dh'.$userid,'d',$table);
			$newydh=Digit($newydh+$system,3);
		}
		$DHnumber=$userid.date('y').date('md').$newydh;//默认4位数
		
	}elseif($typ==6){
		$newydh=Cumulative('dh','m',$table);
		$newydh=Digit($newydh+$system,5);
		$DHnumber=date('y').date('m').$newydh;
	}elseif($typ==7){
		
		$newydh=FeData($table,$field,"1=1 order by addtime desc");
		$newydh=findNum($newydh);
		$newydh=substr($newydh,0,7);
		$newydh=Digit($newydh+11+$system,7);
		$DHnumber=$newydh;
		
	}elseif($typ==8){
		$newydh=Cumulative('dh','d',$table);
		$newydh=Digit($newydh+$system,3);
		$DHnumber=substr(date('Y'),-1).date('md').$newydh;
	}elseif($typ==9){
		
		$newydh=Cumulative('dh','d',$table);
		if(!$userid){
			exit ("<script>alert('OrderNo{$LG['function.111']} (FS001)');goBack();</script>");
			$userid=$LG['function.111'];
		}else{
			//会员姓名首大写字母
			$enname=FeData('member','enname',"userid='{$userid}'");
			
			//会员姓名首大写字母(有重复则多加会员ID最后2位数字)
			$num=mysqli_num_rows($xingao->query("select userid from member where enname='{$enname}' and userid<>'{$userid}'"));
			if($num){$ennameid=substr($userid,-2);}
			$enname=strtoupper(Initials($enname)).$ennameid;
		}
		$newydh=Digit($newydh+$system,4);
		
		$DHnumber=date('y').date('md').$newydh.$enname;
	}
	
	
	
	
	//附加前缀后缀
	$DH=$DHtpre.$DHnumber.$DHsuffix;
	
	
	
	//检查重复
	$num=mysqli_num_rows($xingao->query("select {$field} from {$table} where {$field}='{$DH}'"));
	while($num>0) 
	{
		$DH=OrderNo($table,$whid,$typ,$system+=1);
		$num=mysqli_num_rows($xingao->query("select {$field} from {$table} where {$field}='{$DH}'"));
	}
	return $DH;
}





//复制订单时生成的单号，复制单号
/*
	$typ=1 数字后面加字母 ,如:XA201608300012US > XA201608300012BUS
	$typ=2 单号最后加字母 ,如:XA201608300012US > XA201608300012USB
	$typ=3 单号最后加数字 ,如:XA201608300012US > XA201608300012US2
	
	$digit= 加数字时的位数
	$system 内部用 ：重复时累加数字
*/
function copyOrderNo($table='yundan',$DH,$typ=1,$digit=1,$system=0)
{
	if(!$DH){return ;}
	
	require($_SERVER['DOCUMENT_ROOT'].'/public/global.php');
	if($table=='yundan')
	{
		global $ydh_tpre,$ydh_suffix,$ydh_typ;	
		$DHtpre=$ydh_tpre;$DHsuffix=$ydh_suffix;
		$field='ydh';
	}elseif($table=='daigou'){
		global $dg_tpre,$dg_suffix;	$DHtpre=$dg_tpre;$DHsuffix=$dg_suffix;
		$field='dgdh';
	}
	
	
	$old_DH=$DH;


	if($typ==1){
		$DH=strtoupper(chr($system+97));
		$DH=str_ireplace(findNum($old_DH),findNum($old_DH).$DH,$old_DH);
	}elseif($typ==2){
		
		//识别原单号是否有修改过
		if(($ydh_suffix&&substr($old_DH,-fnCharCount($ydh_suffix))!=$ydh_suffix) || (!$ydh_suffix&&substr($old_DH,-1)=='A') )
		{
			$old_DH_now=substr($old_DH,0,-1);
		}else{
			$old_DH_now=$old_DH;
		}
		
		$DH=strtoupper(chr($system+97));
		$DH=$old_DH_now.$DH;
	}elseif($typ==3){
		
		//识别原单号是否有修改过
		if(($ydh_suffix&&substr($old_DH,-fnCharCount($ydh_suffix))!=$ydh_suffix)|| (!$ydh_suffix&&substr($old_DH,-$digit)==Digit(1,$digit)) )
		{
			$old_DH_now=substr($old_DH,0,-$digit);
		}else{
			$old_DH_now=$old_DH;
		}

		$DH=$old_DH_now.(Digit($system+1,$digit));
	}
	
	//检查重复
	$num=mysqli_num_rows($xingao->query("select userid from {$table} where {$field}='{$DH}'"));
	while($num>0) 
	{
		$DH=copyOrderNo($table,$old_DH,$typ,$digit,$system+=1);
		$num=mysqli_num_rows($xingao->query("select userid from {$table} where {$field}='{$DH}'"));
	}
	return $DH;
}




//累积数字
/*
	$val 识别值
	$typ='d' 按天清零
	$typ='w' 按周清零
	$typ='m' 按月清零
*/
function Cumulative($val,$typ='d',$table='yundan')
{
	require($_SERVER['DOCUMENT_ROOT'].'/public/global.php');
	if(!$val){return;}else{$val=$table.$val;}
	
	$cf=FeData('config','*',"name='{$val}'");
	if(!$cf['id']){
		$number=1;
		$xingao->query("insert into config (name,value1,value2) values ('{$val}','".time()."','{$number}')");
	}else{
		$number=1;//默认
		
		if($typ=='d'){//今天
			$start =strtotime(date('Y-m-d')." 00:00:00");
			if($cf['value1']>=$start){$number=$cf['value2']+1;}
		}elseif($typ=='w'){//本周
			$date = date("Y-m-d")." 00:00:00";
			$first=1; // 1 表示每周星期一为开始时间，0表示每周日为开始时间
			$w = date("w", strtotime($date));  //获取当前是本周的第几天，周日是 0，周一 到周六是 1 -6 
			$d = $w ? $w - $first : 6;  //如果是周日 -6天 
			$now_start = date("Y-m-d", strtotime("$date -".$d." days"));
			$now =  strtotime($now_start);
			if($cf['value1']>=$now){$number=$cf['value2']+1;}
		}elseif($typ=='m'){//本月
			$start =strtotime(date('Y-m'.'-1'));
			if($cf['value1']>=$start){$number=$cf['value2']+1;}
		}
		
		$xingao->query("update config set value1='".time()."',value2='{$number}' where name='{$val}'");
	}
	return $number;
}















//操作日志-----------------------------------------------------------------------------------  
/*
	$typ=0 查询
	$typ=1 添加
	$typ=2 删除

	$typ=1时：
	$types=0 添加-会员操作
	$types=1 添加-后台操作
*/

function opLog($fromtable,$fromid,$typ=0,$content='',$types=1)
{
	require($_SERVER['DOCUMENT_ROOT'].'/public/global.php');
		
	//查询
	if($typ==0)
	{
		echo '
			<table class="table table-striped table-bordered table-hover" >
			  <thead>
				<tr>
					<th align="center">'.$LG['time'].'</th>
					<th align="center">'.$LG['function.116'].'</th>
					<th align="center">'.$LG['function.117'].'</th>
				</tr>
			  </thead>
			  <tbody>
		  ';
		  
		$query="select * from log where fromtable='{$fromtable}' and fromid='{$fromid}' order by addtime asc,loid asc";
		$sql=$xingao->query($query);
		while($rs=$sql->fetch_array())
		{
			?>
            <!--需要加:background-color: #f9f9f9 !important 否则当外行勾选后,本表格也受外部样式影响-->
			<tr class="odd gradeX">
				<td align="center" valign="middle"  title="日志ID:<?=$rs['loid']?>" style="background-color: #f9f9f9 !important">
				<?php if($old_addtime!=$rs['addtime']){echo DateYmd($rs['addtime'],1);$old_addtime=$rs['addtime'];}?>
                </td>
				<td align="left" valign="middle" style="background-color: #f9f9f9 !important"><?=LGLabel($rs['content'])?></td>
				<td align="center" valign="middle" style="background-color: #f9f9f9 !important">
				<?=$rs['types']?$LG['function.118']:$LG['function.119']?><?=cadd($rs['username'])?>
				<font class="gray2" title="<?=$LG['function.117']?>">(<?=spr($rs['userid'])?>)</font>
				</td>
			</tr>
			<?php
		}
		
		echo '
			</tbody>
			</table>
		';
		
		return;
	}


	//添加
	if($typ==1)
	{
		if($types){global $Xuserid,$Xusername;	$userid=$Xuserid;$username=$Xusername;}
		elseif(!$types){global $Muserid,$Musername;	$userid=$Muserid;$username=$Musername;}
		
		if(!$fromtable||!$fromid||!$content||!$userid){return '日志添加失败：参数不全';}
		
		$xingao->query("insert into log (`fromtable`, `fromid`, `userid`, `username`, `types`, `content`, `addtime`)  values ('{$fromtable}', '{$fromid}', '{$userid}', '{$username}', '".spr($types)."', '".add($content)."', '".time()."')");
		SQLError('function opLog添加日志');
		return;
	}
	

	//删除
	if($typ==2)
	{
		if(!$fromtable||!$fromid){return '删除日志失败：参数不全';}
		$xingao->query("delete from log where fromtable='{$fromtable}' and fromid='{$fromid}' ");
		SQLError('function opLog删除日志');
		return;
	}
}



//地址簿：地址读取,显示地址
/*
	$typ=1 返回方式
	$typ=2 返回方式:姓名 电话 全部地址 证件号
*/
function addressShow($addid,$typ=1)
{
	if(!$addid){return;}
	require($_SERVER['DOCUMENT_ROOT'].'/public/global.php');
	
	$rs=FeData('member_address','*',"addid='{$addid}'");
	if(!$rs['addid']){return;}
	
	if($typ==1)
	{
		if(trim($rs['mobile_code'])){$add.='+'.cadd($rs['mobile_code']).' ';}
		if(trim($rs['mobile'])){$add.=cadd($rs['mobile']).'<span class="xa_sep"> | </span>';}
		if(trim($rs['tel'])){$add.=cadd($rs['tel']).'<span class="xa_sep"> | </span>';}
		if(trim($rs['zip'])){$add.=cadd($rs['zip']).'<span class="xa_sep"> | </span>';}
	
		if(trim($rs['add_shengfen'])){$add.=cadd($rs['add_shengfen']).' ';}
		if(trim($rs['add_chengshi'])){$add.=cadd($rs['add_chengshi']).' ';}
		if(trim($rs['add_quzhen'])){$add.=cadd($rs['add_quzhen']).' ';}
		if(trim($rs['add_dizhi'])){$add.=cadd($rs['add_dizhi']);}
		
		if(trim($rs['shenfenhaoma'])){$add.='<span class="xa_sep"> | </span>'.cadd($rs['shenfenhaoma']);}
	}elseif($typ==2){
		if(trim($rs['truename'])){$add.=cadd($rs['truename']).' ';}
		if(trim($rs['mobile_code'])){$add.='+'.cadd($rs['mobile_code']).' ';}
		if(trim($rs['mobile'])){$add.=cadd($rs['mobile']).' ';}
	
		if(trim($rs['add_shengfen'])){$add.=cadd($rs['add_shengfen']).' ';}
		if(trim($rs['add_chengshi'])){$add.=cadd($rs['add_chengshi']).' ';}
		if(trim($rs['add_quzhen'])){$add.=cadd($rs['add_quzhen']).' ';}
		if(trim($rs['add_dizhi'])){$add.=cadd($rs['add_dizhi']);}
		
		if(trim($rs['shenfenhaoma'])){$add.='(证件:'.cadd($rs['shenfenhaoma']).')';}
	}

	
	return $add;
}



//入库搜索单号,搜索各系统表的单号
/*
		$id='dgid';//返回ID字段
		$table='daigou';//搜索表
		$field='whcod';//搜索字段
		$bgydh='123'//搜索的号码
		$where_search="";//有其他条件时,以空格 and 开头,如: and userid='{$userid}'
		$smlx=1;//1只精确搜索;0全部搜索
*/
function searchNumber($id,$table,$field,$bgydh,$smlx=0,$where_search="")
{
	require($_SERVER['DOCUMENT_ROOT'].'/public/global.php');
	
	$rsid='';
	//按原单号精确搜索-开始
	$query="select {$id} from {$table} where {$field}='{$bgydh}' {$where_search} limit 2";
	$sql=$xingao->query($query);
	while($rs=$sql->fetch_array())
	{
		$rsid.=$rs[$id].',';
	}
	if(mysqli_num_rows($sql)>1&&$_SESSION['smyd_op1']!=3)
	{
		$rsid='';
		$ts='精确搜索:'.$table.'表中找到多个信息，不能多个同时操作 (请填写更多单号)';
	}
	//按原单号精确搜索-结束

	//按原单号like搜索-开始
	if(!$rsid&&!$smlx)
	{
		$query="select {$id} from {$table} where {$field} like '%".$bgydh."%' {$where_search} limit 2";
		$sql=$xingao->query($query);
		while($rs=$sql->fetch_array())
		{
			$rsid.=$rs[$id].',';
		}
		
		if(mysqli_num_rows($sql)>1&&$_SESSION['smyd_op1']!=3)
		{
			$rsid='';
			$ts='模糊搜索:'.$table.'表中找到多个信息，不能多个同时操作 (请填写更多单号)';
		}
	}
	//按原单号like搜索-结束
	
	//找不到时就反向搜索-开始（注意：反向和分割不能同时进行）
	global $yundan_smws;
	if(!$rsid&&$yundan_smws&&!$smlx)
	{
		$query="select {$id} from {$table} where '{$bgydh}' like concat('%',{$field}, '%') {$where_search} limit 2";
		$sql=$xingao->query($query);
		while($rs=$sql->fetch_array())
		{
			$rsid.=$rs[$id].',';
		}
		
		if(mysqli_num_rows($sql)>1&&$_SESSION['smyd_op1']!=3)
		{
			$rsid='';
			$ts='反向搜索:'.$table.'表中找到多个信息，不能多个同时操作 (请填写更多单号)';
		}
	}
	//找不到时就反向搜索-结束
	
	
	
	
	
	
	
	
	
	
	
	
	//找不到时就分割搜索-开始
	global $baoguo_fg,$baoguo_fgzs,$baoguo_fg_type;
	$baoguo_fgzs=(int)$baoguo_fgzs;
	if(!$rsid&&$baoguo_fg&&$baoguo_fgzs>0&&!$smlx)
	{
		$where='';
		$var=$bgydh;
		$varc=strlen($var);//不支持中文
		
		if($varc>$baoguo_fgzs)
		{
			//旧版分割搜索错误,修改方法:用以下代码替换为旧版的"全部逐字分割"
			//$baoguo_fg_type=4;
			
			
			//全部逐字分割 
			if($baoguo_fg_type==1)
			{
				for ($i=1; $i<=$varc-$baoguo_fgzs; $i++) 
				{
					$var=substr($var,1);
					$where.=" {$field} like '%".substr($var,0,$baoguo_fgzs)."%' or";
				}
				$where=DelStr($where,'or');//新版
				//$where=DelStr($where,'or',0,2);//旧版
			}
			//分割开头和结尾 
			elseif($baoguo_fg_type==2)
			{
				$where=" {$field} like '%".substr($var,0,$baoguo_fgzs)."%' or {$field} like '%".substr($var,-$baoguo_fgzs)."%'";
			}
			//分割开头 
			elseif($baoguo_fg_type==3)
			{
				$where=" {$field} like '%".substr($var,0,$baoguo_fgzs)."%'";
			}
			//分割结尾 
			elseif($baoguo_fg_type==4)
			{
				$where=" {$field} like '%".substr($var,-$baoguo_fgzs)."%'";
			}
		}



		if($where)
		{
			$xs='';
			$query="select {$id},{$field} from {$table} where {$where} {$where_search}";
			$sql=$xingao->query($query);
			while($rs=$sql->fetch_array())
			{
				similar_text($bgydh,$rs[$field],$xs_new);
				if(!$xs||spr($xs_new)>spr($xs)){$rsid.=$rs[$id].',';$xs=$xs_new;}
			}
			//有多个时使用最相似的单号
/*			if(mysqli_num_rows($sql)>1)
			{
				$rsid='';
				$ts='分割搜索:'.$table.'表中找到多个信息，不能多个同时操作 (建议在系统设置中把分割字数改更大位数)';

			}
*/		}
		
	}
	//找不到时就分割搜索-结束
	
	$where_search='';
	return $rsid=DelStr($rsid);
}




//生成优惠券/折扣券-------------------------------------
/*
	前面是生成参数:参数说明见/xingao/coupons/form.php
	$userid='',$username='' 发给指定会员
*/
	
function create_coupons($duetime='',$content='',$types='',$value='',$limitmoney='',$usetypes='',$code_number='',$code_digits='',$number='',$userid='',$username='',$getSource='')
{
	require($_SERVER['DOCUMENT_ROOT'].'/public/global.php');
	
	if($getSource){$getTime=time();}

	for ($i=1; $i<=$code_number; $i++) 
	{
		$codes=NextId('coupons');
		$codes=no_make_password($code_digits-strlen($codes)).$codes;
		
		$xingao->query("insert into coupons (
		`userid`,`username`,`getTime`,`getSource`,`status`, `types`, `codes`, `value`,`number`, `limitmoney`, `usetypes`, `content`, `duetime`, `addtime`
		) VALUES (
		'".spr($userid)."', '{$username}','".spr($getTime)."', '".spr($getSource)."','0', '{$types}', '{$codes}', '{$value}','{$number}', '{$limitmoney}', '{$usetypes}', '{$content}', '{$duetime}', '".time()."'
		) ");
		SQLError('生成优惠券/折扣券');
	}
}

?>