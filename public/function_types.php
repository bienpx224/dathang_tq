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

//------------------------------------常改----------------------------------------------- 
//报价公式
function fee_gongshi($zhi,$lx='')
{
	if($lx)
	{
		//重要:里面的数字必须跟function_price.php中的$channel数字含意相同
		
		echo '<option value="" '.($zhi==''?'selected':'').'>默认公式</option>';
		echo '<option value="other" '.($zhi=='other'?'selected':'').'>单独设置</option>';
		echo '<option value="price_jp_1" '.($zhi=='price_jp_1'?'selected':'').'>日本至中国 邮政(EMS)</option>';
		echo '<option value="price_jp_2" '.($zhi=='price_jp_2'?'selected':'').'>日本至中国 邮政(空运)</option>';
		echo '<option value="price_jp_3" '.($zhi=='price_jp_3'?'selected':'').'>日本至中国 邮政(SAL)</option>';
		echo '<option value="price_jp_4" '.($zhi=='price_jp_4'?'selected':'').'>日本至中国 邮政(船运)</option>';
		
	}else{
		switch($zhi)
		{
			case '':return '默认公式';
			case 'other':return '单独设置';
			case 'price_jp_1':return '日本至中国 邮政(EMS)';
			case 'price_jp_2':return '日本至中国 邮政(空运)';
			case 'price_jp_3':return '日本至中国 邮政(SAL)';
			case 'price_jp_4':return '日本至中国 邮政(船运)';
		}
	}
}








//生成单号格式下拉-----------------------------------------------------------------------------------  
function OrderNo_typ($zhi='')
{
	$selected=$zhi=='1'?'selected':''; echo '<option value="1" '.$selected.'>日期+当天累积4位数字(共12位)【例:20170101 0001】</option>';
	$selected=$zhi=='2'?'selected':''; echo '<option value="2" '.$selected.'>日期+单ID(共9位以上)【例:20170101 1】</option>';
	$selected=$zhi=='3'?'selected':''; echo '<option value="3" '.$selected.'>单ID(共8位)【例:00000001】</option>';
	$selected=$zhi=='4'?'selected':''; echo '<option value="4" '.$selected.'>仓库编号+6位单ID(共8位)【例:10 000001】</option>';
	$selected=$zhi=='5'?'selected':''; echo '<option value="5" '.$selected.'>会员ID+日期+当天会员累积3位数字(共14位)【例:10034 170101 001】</option>';
	$selected=$zhi=='6'?'selected':''; echo '<option value="6" '.$selected.'>年+月+当月累积5位数字(共9位)【例:17 01 00001】</option>';
	$selected=$zhi=='7'?'selected':''; echo '<option value="7" '.$selected.'>单号+11(共7位)【例:0000011】</option>';
	$selected=$zhi=='8'?'selected':''; echo '<option value="8" '.$selected.'>日期+当天累积3位数字(共8位)【例:70101 001】</option>';
	$selected=$zhi=='9'?'selected':''; echo '<option value="9" '.$selected.'>日期+当天累积4位数字+会员姓名首大写字母(有重复则多加会员ID最后2位数字) (共12位以上)【例:170101 0001 XAA】</option>';

}



/*
	//语言包 语种
	
	$lx=1 支持的语种列表：字符
	$lx=2 支持的语种列表：数组
	$lx=3 后台已开启的语种列表：数组
	$lx=5 不开启的语种列表：数组
	$lx=6 正式开放的语种列表：数组 (会员可以选择的)
	
	语言代码缩写表大全:http://www.rzfanyi.com/ArticleShow.asp?ArtID=969
	
	自动显示已开通语言，实例：
	<?php 
	if(!$LGList){$LGList=languageType('',3);}
	if($LGList)
	{
		foreach($LGList as $arrkey=>$language)
		{
			?>
			<li><a href="/Language/?LGType=1&language=<?=$language?>"> <?=languageType($language)?></a> </li>
			<?php 
		}
	}
	?>
	
	
	
	//删除已停用版本，实例：
	//语言字段处理++
	if(!$LGList){$LGList=languageType('',5);}
	if($LGList)
	{
		foreach($LGList as $arrkey=>$language)
		{
			DelFile("/cache/warehouse{$language}.php");
		}
	}
	
	
	
*/
function languageType($zhi,$lx=0)
{
	if($lx==1||$lx==2)
	{
		$SupportList=ToArr('CN,EN,JP,KO,DE,IT,RU,FR');//支持的语种，此处设置后并且有语言包，后台才能开启该语种
		if($SupportList)//语言包是否存在
		{
			foreach($SupportList as $arrkey=>$value)
			{
				if(HaveFile('/Language/'.$value.'.php')){$LGList.=$value.',';}
			}
			$LGList=DelStr($LGList);
		}
	}





	if($lx==1){ 
		return $LGList; 
	}elseif($lx==2){ 
		return ToArr($LGList); 
	}elseif($lx==3){
		global $ON_LG,$LGDefault,$ONLanguage;
		if(!$ON_LG){return ToArr($LGDefault);}
		else{return ToArr($ONLanguage);}
	}elseif($lx==5){
		return ArrDel(languageType('',2),languageType('',3));
	}elseif($lx==6){
		global $ON_LG,$LGDefault,$openLanguage;
		if(!$ON_LG){return ToArr($LGDefault);}
		else{return ToArr($openLanguage);}
	}else{
		switch($zhi)
		{
			case 'CN':return '中文';
			case 'EN':return 'English';
			case 'JP':return '日本語';
			case 'KO':return '한글';//韩文
			case 'DE':return 'Deutsch';//德文
			case 'IT':return 'Italiano';//意大利文
			case 'RU':return 'Русский язык';//俄罗斯语
			case 'FR':return 'Le Français';//法语
		}
	}
}


















//------------------------------------模板、属性、参数调用-----------------------------------------------  
//栏目类型
function ClassType($zhi,$lx=0)
{
	if($lx)
	{
		$selected=$zhi=='1'?'selected':''; echo '<option value="1" '.$selected.'>图文</option>';
		$selected=$zhi=='2'?'selected':''; echo '<option value="2" '.$selected.'>晒单</option>';
		$selected=$zhi=='3'?'selected':''; echo '<option value="3" '.$selected.'>商城</option>';
		$selected=$zhi=='4'?'selected':''; echo '<option value="4" '.$selected.'>单页</option>';
	}else{
		switch($zhi)
		{
			case '1':return '图文';
			case '2':return '晒单';
			case '3':return '商城';
			case '4':return '单页';
		}
	}
}

//系统分类管理
/*
	$lx=1下拉:显示全部类型
	$lx=2下拉:只显示通用类型 （不显示类型：3）
*/
function ClassifyType($zhi,$lx=0)
{
	if($lx)
	{
		$selected=$zhi=='1'?'selected':''; echo '<option value="1" '.$selected.'>清关资料</option>';
		//$selected=$zhi=='2'?'selected':''; echo '<option value="2" '.$selected.'>中国地址</option>';//未做
		if($lx!=2){$selected=$zhi=='3'?'selected':''; echo '<option value="3" '.$selected.'>航班/船运</option>';}
		$selected=$zhi=='4'?'selected':''; echo '<option value="4" '.$selected.'>物品类别</option>';
		$selected=$zhi=='5'?'selected':''; echo '<option value="5" '.$selected.'>物品单位</option>';
		$selected=$zhi=='6'?'selected':''; echo '<option value="6" '.$selected.'>物品品牌</option>';
		$selected=$zhi=='7'?'selected':''; echo '<option value="7" '.$selected.'>物品规格</option>';
		$selected=$zhi=='8'?'selected':''; echo '<option value="8" '.$selected.'>物品颜色</option>';
		
	}else{
		switch($zhi)
		{
			case '1':return '清关资料';
			//case '2':return '中国地址';
			case '3':return '航班/船运'; 
			case '4':return '物品类别'; 
			case '5':return '物品单位'; 
			case '6':return '物品品牌'; 
			case '7':return '物品规格'; 
			case '8':return '物品颜色'; 
		}
	}
}


//列表模板
function LTemplate($zhi)
{
	$selected=$zhi==''?'selected':''; echo '<option value="" '.$selected.'>默认列表模板</option>';
	$selected=$zhi=='article_intro_list.php'?'selected':''; echo '<option value="article_intro_list.php" '.$selected.'>文章:简介</option>';
	$selected=$zhi=='article_tab_list.php'?'selected':''; echo '<option value="article_tab_list.php" '.$selected.'>文章:选项卡</option>';
	$selected=$zhi=='article_imgw_list.php'?'selected':''; echo '<option value="article_imgw_list.php" '.$selected.'>文章:宽形图片</option>';
	$selected=$zhi=='article_imgbig_list.php'?'selected':''; echo '<option value="article_imgbig_list.php" '.$selected.'>文章:大图+简介</option>';
	$selected=$zhi=='article_imgintro_list.php'?'selected':''; echo '<option value="article_imgintro_list.php" '.$selected.'>文章:小图+简介</option>';
	$selected=$zhi=='article_faq_list.php'?'selected':''; echo '<option value="article_faq_list.php" '.$selected.'>文章:常见问题</option>';
}

//内容模板
function CTemplate($zhi)
{
	$selected=$zhi==''?'selected':''; echo '<option value="" '.$selected.'>默认内容模板</option>';
	$selected=$zhi=='article_content_intro.php'?'selected':''; echo '<option value="article_content_intro.php" '.$selected.'>文章:缩略图+简介</option>';
}

//转换为移动通用模板
function Transform_m($zhi)
{
	$template_list=array('article_imgbig_list.php');
	$template=array($zhi);
	if(array_intersect($template_list,$template)) {return 'article_imgintro_list.php';}
	
	return $zhi;
}

//界面颜色
function theme_color($zhi)
{
		$selected=$zhi=='default.css'?'selected':''; echo '<option value="default.css" '.$selected.'>黑色</option>';
		$selected=$zhi=='grey.css'?'selected':''; echo '<option value="grey.css" '.$selected.'>灰色</option>';
		$selected=$zhi=='light.css'?'selected':''; echo '<option value="light.css" '.$selected.'>浅灰色</option>';
		$selected=$zhi=='light2.css'?'selected':''; echo '<option value="light2.css" '.$selected.'>蓝主 白底</option>';
		$selected=$zhi=='blue.css'?'selected':''; echo '<option value="blue.css" '.$selected.'>蓝色</option>';
		$selected=$zhi=='blue2.css'?'selected':''; echo '<option value="blue2.css" '.$selected.'>浅蓝色</option>';
		$selected=$zhi=='red.css'?'selected':''; echo '<option value="red.css" '.$selected.'>深红</option>';
		
}


//推荐
function isgood($zhi='',$lx='')
{
	if($lx)
	{
		$selected=$zhi=='0'?'selected':''; echo '<option value="0" '.$selected.'>无推荐</option>';
		$selected=$zhi=='1'?'selected':''; echo '<option value="1" '.$selected.'>1级推荐</option>';
		$selected=$zhi=='2'?'selected':''; echo '<option value="2" '.$selected.'>2级推荐</option>';
		$selected=$zhi=='3'?'selected':''; echo '<option value="3" '.$selected.'>3级推荐</option>';
	}else{
		if($zhi)
		{
			return '<span class="label label-sm label-warning">'.$zhi.'级推荐</span>';
		 }
	}
}

//头条
function ishead($zhi='',$lx='')
{
	if($lx)
	{
		$selected=$zhi=='0'?'selected':''; echo '<option value="0" '.$selected.'>无头条</option>';
		$selected=$zhi=='1'?'selected':''; echo '<option value="1" '.$selected.'>1级头条</option>';
		$selected=$zhi=='2'?'selected':''; echo '<option value="2" '.$selected.'>2级头条</option>';
		$selected=$zhi=='3'?'selected':''; echo '<option value="3" '.$selected.'>3级头条</option>';
	}else{
		if($zhi)
		{
			return '<span class="label label-sm label-success">'.$zhi.'级头条</span>';
		 }
	}
}

//置顶
function istop($zhi='',$lx='')
{
	if($lx)
	{
		$selected=$zhi=='0'?'selected':''; echo '<option value="0" '.$selected.'>无置顶</option>';
		$selected=$zhi=='1'?'selected':''; echo '<option value="1" '.$selected.'>1级置顶</option>';
		$selected=$zhi=='2'?'selected':''; echo '<option value="2" '.$selected.'>2级置顶</option>';
		$selected=$zhi=='3'?'selected':''; echo '<option value="3" '.$selected.'>3级置顶</option>';
		$selected=$zhi=='4'?'selected':''; echo '<option value="4" '.$selected.'>4级置顶</option>';
		$selected=$zhi=='5'?'selected':''; echo '<option value="5" '.$selected.'>5级置顶</option>';
		$selected=$zhi=='6'?'selected':''; echo '<option value="6" '.$selected.'>6级置顶</option>';
		$selected=$zhi=='7'?'selected':''; echo '<option value="7" '.$selected.'>7级置顶</option>';
		$selected=$zhi=='8'?'selected':''; echo '<option value="8" '.$selected.'>8级置顶</option>';
		$selected=$zhi=='9'?'selected':''; echo '<option value="9" '.$selected.'>9级置顶</option>';
		$selected=$zhi=='10'?'selected':''; echo '<option value="10" '.$selected.'>10级置顶</option>';
	}else{
		if($zhi)
		{
			return '<span class="label label-sm label-info">'.$zhi.'级置顶</span>';
		 }
	}
}



//公告等级
function Notice_Level($zhi,$lx='')
{
	if($lx){
            $selected=$zhi=='0'?'selected':''; echo '<option value="0" '.$selected.'>普通</option>';
            $selected=$zhi=='1'?'selected':''; echo '<option value="1" '.$selected.'>重要</option>';
            $selected=$zhi=='2'?'selected':''; echo '<option value="2" '.$selected.'>紧急</option>';
	}else{
		switch($zhi)
		{
             case '0':return '';
             case '1':return '<span class="label label-sm label-warning">重要</span>';
             case '2':return '<span class="label label-sm label-danger">紧急</span>';
		}
	}	
}






//商品资料类型
function Record($zhi,$lx='')
{
	require($_SERVER['DOCUMENT_ROOT'].'/public/global.php');
	
	if($lx){
		echo '<option value="0" '.($zhi=='0'?'selected':'').'>'.$LG['gd.record0'].'</option>';
		echo '<option value="1" '.($zhi=='1'?'selected':'').'>'.$LG['gd.record1'].'</option>';
		echo '<option value="2" '.($zhi=='2'?'selected':'').'>'.$LG['gd.record2'].'</option>';
	}else{
		switch($zhi)
		{
             case '0':return $LG['gd.record0'];//'不用备案';
             case '1':return $LG['gd.record1'];//'未备案';
             case '2':return $LG['gd.record2'];//'已备案';
		}
	}	
}




//月结记账状态
function Tally($zhi,$lx='')
{
	require($_SERVER['DOCUMENT_ROOT'].'/public/global.php');
	if($lx){

            $selected=$zhi=='0'?'selected':''; echo '<option value="0" '.$selected.'>'.$LG['function_types.1'].'</option>';
            $selected=$zhi=='-1'?'selected':''; echo '<option value="-1" '.$selected.'>'.$LG['function_types.2'].'</option>';
            $selected=$zhi=='1'?'selected':''; echo '<option value="1" '.$selected.'>'.$LG['function_types.3'].'</option>';
            $selected=$zhi=='2'?'selected':''; echo '<option value="2" '.$selected.'>'.$LG['function_types.4'].'</option>';
	}else{
		switch($zhi)
		{
             case '0':return '<span class="label label-sm label-default">'.$LG['function_types.1'].'</span>';
             case '1':return '<span class="label label-sm label-warning">'.$LG['function_types.3'].'</span>';
             case '2':return '<span class="label label-sm label-success">'.$LG['function_types.4'].'</span>';
		}
	}	
}


//记录显示方式
function showTyp($zhi,$lx='')
{
	require($_SERVER['DOCUMENT_ROOT'].'/public/global.php');
	if($lx){

            $selected=$zhi=='0'?'selected':''; echo '<option value="0" '.$selected.'>'.$LG['function_types.5'].'</option>';
            $selected=$zhi=='1'?'selected':''; echo '<option value="1" '.$selected.'>'.$LG['function_types.6'].'</option>';
	}else{
		switch($zhi)
		{
             case '0':return $LG['function_types.5'];//详细
             case '1':return $LG['function_types.6'];//月计
		}
	}	
}


//运单月结导出模板
function settlement_yundan_excel_export($zhi='',$lx=0)
{
	require($_SERVER['DOCUMENT_ROOT'].'/public/global.php');
	if($lx==1)
	{
		$selected=$zhi=='yundan_alone'?'selected':''; echo '<option value="yundan_alone" '.$selected.'>'.$LG['function_types.7'].'</option>';
		$selected=$zhi=='yundan_all'?'selected':''; echo '<option value="yundan_all" '.$selected.'>'.$LG['function_types.8'].'</option>';
	}else{
		switch($zhi)
		{
			case 'yundan_alone':return $LG['function_types.7'];//分开统计
			case 'yundan_all':return $LG['function_types.8'];//全部统计
		}
	}
}

//月结其他导出模板
function settlement_other_excel_export($zhi='',$lx=0)
{
	require($_SERVER['DOCUMENT_ROOT'].'/public/global.php');
	if($lx==1)
	{
		$selected=$zhi=='other_alone'?'selected':''; echo '<option value="other_alone" '.$selected.'>'.$LG['function_types.7'].'</option>';
		$selected=$zhi=='other_all'?'selected':''; echo '<option value="other_all" '.$selected.'>'.$LG['function_types.8'].'</option>';
	}else{
		switch($zhi)
		{
			case 'other_alone':return $LG['function_types.7'];//分开统计
			case 'other_all':return $LG['function_types.8'];//全部统计
		}
	}
}

//理赔类型
function lipei_Types($zhi,$lx='')
{
	require($_SERVER['DOCUMENT_ROOT'].'/public/global.php');
	if($lx){

		$selected=$zhi=='1'?'selected':''; echo '<option value="1" '.$selected.'>'.$LG['function_types.9'].'</option>';
		$selected=$zhi=='2'?'selected':''; echo '<option value="2" '.$selected.'>'.$LG['function_types.10'].'</option>';
		$selected=$zhi=='3'?'selected':''; echo '<option value="3" '.$selected.'>'.$LG['function_types.11'].'</option>';
		$selected=$zhi=='4'?'selected':''; echo '<option value="4" '.$selected.'>'.$LG['function_types.12'].'</option>';
	}else{
		switch($zhi)
		{
			case '1':return $LG['function_types.9'];//损坏 break;
			case '2':return $LG['function_types.10'];//丢失 break;
			case '3':return $LG['function_types.11'];//少件 break;
			case '4':return $LG['function_types.12'];//其他 break;
		}
	}
}

//理赔状态
function lipei_Status($zhi,$lx='')
{
	require($_SERVER['DOCUMENT_ROOT'].'/public/global.php');
	if($lx){

            $selected=$zhi=='0'?'selected':''; echo '<option value="0" '.$selected.'>'.$LG['function_types.13'].'</option>';
            $selected=$zhi=='1'?'selected':''; echo '<option value="1" '.$selected.'>'.$LG['name.nav_24'].'</option>';
            $selected=$zhi=='2'?'selected':''; echo '<option value="2" '.$selected.'>'.$LG['function_types.14'].'</option>';
            $selected=$zhi=='3'?'selected':''; echo '<option value="3" '.$selected.'>'.$LG['name.nav_33'].'</option>';
	}else{
		switch($zhi)
		{
             case '':return '';
             case '0':return '<span class="label label-sm label-default">'.$LG['function_types.13'].'</span>';
             case '1':return '<span class="label label-sm label-success">'.$LG['name.nav_24'].'</span>';
             case '2':return '<span class="label label-sm label-default">'.$LG['function_types.14'].'</span>';
             case '3':return '<span class="label label-sm label-danger">'.$LG['name.nav_33'].'</span>';
		}
	}	
}




//晒单类型
function shaidan_Types($zhi,$lx='')
{
	require($_SERVER['DOCUMENT_ROOT'].'/public/global.php');
	
	if($lx){
		global $shaidan_Types_0,$shaidan_Types_1;
		if($shaidan_Types_0){echo '<option value="0" '.(CheckEmpty($zhi)&&$zhi==0?'selected':'').'>'.$LG['shaidan.types0'].'</option>';}
		if($shaidan_Types_1){echo '<option value="1" '.($zhi==1?'selected':'').'>'.$LG['shaidan.types1'].'</option>';}
	}else{
		switch($zhi)
		{
             case '':return '';
             case '0':return '<span class="label label-sm label-default">'.$LG['shaidan.types0'].'</span>';//站内晒单
             case '1':return '<span class="label label-sm label-info">'.$LG['shaidan.types1'].'</span>';//站外晒单
		}
	}	
}



//商城订单状态
function mall_order_Status($zhi,$lx='')
{
	global $mall_order_time;
	require($_SERVER['DOCUMENT_ROOT'].'/public/global.php');
	if($lx){

            $selected=$zhi=='0'?'selected':''; echo '<option value="0" '.$selected.'>'.$LG['function_types.15'].'</option>';
            $selected=$zhi=='1'?'selected':''; echo '<option value="1" '.$selected.'>'.$LG['function_types.16'].'</option>';
            $selected=$zhi=='3'?'selected':''; echo '<option value="3" '.$selected.'>'.$LG['function_types.17'].'</option>';
	}else{
		if($mall_order_time>0){	$time_ts=LGtag($LG['function_types.18'],'<tag1>=='.$mall_order_time);}
		else{$time_ts=$LG['function_types.205'];}
		switch($zhi)
		{
             case '':return '';
             case '0':return '<span class="label label-sm label-default">'.$LG['function_types.15'].'</span>';
             case '1':return '<span class="label label-sm label-success">'.$LG['function_types.16'].'</span>';
			 
             case '3':return '<span class="label label-sm label-danger" title="'.$time_ts.'">'.$LG['function_types.17'].'</span>';
		}
	}	
}



//转账状态
function transfer_Status($zhi,$lx='')
{
	require($_SERVER['DOCUMENT_ROOT'].'/public/global.php');
	if($lx){

	  $selected=$zhi=='0'?'selected':''; echo '<option value="0" '.$selected.'>'.$LG['function_types.13'].'</option>';
	  $selected=$zhi=='1'?'selected':''; echo '<option value="1" '.$selected.'>'.$LG['function_types.19'].'</option>';
	  $selected=$zhi=='5'?'selected':''; echo '<option value="5" '.$selected.'>'.$LG['function_types.20'].'</option>';
	}else{
		switch($zhi)
		{
             case '0':return $LG['function_types.13'];//待处理
             case '1':return $LG['function_types.19'];//已充值
             case '5':return $LG['function_types.20'];//无效
		}
	}	
}

//转账自动支付状态
function transfer_autoPayStatus($zhi)
{
	require($_SERVER['DOCUMENT_ROOT'].'/public/global.php');
	switch($zhi)
	{
		 case '0':return '<span class="label label-sm label-default">'.$LG['transfer.autoPayStatus0'].'</span>';
		 case '1':return '<span class="label label-sm label-success">'.$LG['transfer.autoPayStatus1'].'</span>';
		 case '2':return '<span class="label label-sm label-warning">'.$LG['transfer.autoPayStatus2'].'</span>';
	}
}



//评论-系统分类
function shaidan_Fromtable($zhi,$lx='')
{
	require($_SERVER['DOCUMENT_ROOT'].'/public/global.php');
	if($lx){

		$selected=$zhi=='mall'?'selected':''; echo '<option value="mall" '.$selected.'>'.$LG['name.nav_68'].'</option>';
		$selected=$zhi=='shaidan'?'selected':''; echo '<option value="shaidan" '.$selected.'>'.$LG['name.nav_53'].'</option>';
	}else{
		switch($zhi)
		{
			case 'mall':return $LG['name.nav_68'];//商城 
			case 'shaidan':return $LG['name.nav_53'];//晒单
		}
	}
}


//地址簿分类
function AddClass($zhi,$lx='')
{
	require($_SERVER['DOCUMENT_ROOT'].'/public/global.php');
	if($lx){

		$selected=$zhi=='0'?'selected':''; echo '<option value="0" '.$selected.'>'.$LG['function_types.21'].'</option>';
		$selected=$zhi=='1'?'selected':''; echo '<option value="1" '.$selected.'>'.$LG['function_types.22'].'</option>';
		$selected=$zhi=='2'?'selected':''; echo '<option value="2" '.$selected.'>'.$LG['function_types.23'].'</option>';
	}else{
		switch($zhi)
		{
			case '0':return $LG['function_types.21'];//通用break;
			case '1':return $LG['function_types.22'];//发货人 break;
			case '2':return $LG['function_types.23'];//收货人 break;
		}
	}
}

//性别
function Gender($zhi,$lx='')
{
	require($_SERVER['DOCUMENT_ROOT'].'/public/global.php');
	if($lx){

		$selected=$zhi=='1'?'selected':''; echo '<option value="1" '.$selected.'>'.$LG['function_types.24'].'</option>';
		$selected=$zhi=='2'?'selected':''; echo '<option value="2" '.$selected.'>'.$LG['function_types.25'].'</option>';
	}else{
		switch($zhi)
		{
			case '1':return $LG['function_types.24'];//男 break;
			case '2':return $LG['function_types.25'];//女 break;
		}
	}
}

//前台,后台登录状态
/*
	负数和0表示登录成功
	正数表示登录失败
*/ 
function LoginStatus($status)
{    
	$status=(int)$status;
	if(!$status){return $LG['function_types.206'];}//登录成功 }//兼容旧版
	//1-20是登录失败
	elseif($status==1){return $LG['function_types.207'];}//认证码错误 
	elseif($status==2){return $LG['function_types.208'];}//用户名错误 }
	elseif($status==3){return $LG['function_types.209'];}//密码错误 }
	
	//21-40以上是成功
	elseif($status==21){return $LG['function_types.210'];}//账号登录成功 }
	elseif($status==22){return $LG['function_types.211'];}//快捷登录成功 }
	elseif($status==23){return $LG['function_types.212'];}//自动登录成功 }
	
	//41以上是其他
	elseif($status==41){return $LG['function_types.213'];}//使用找回密码重设 }
}

//留言状态 
function MsgStatus($zhi,$lx='')
{
	require($_SERVER['DOCUMENT_ROOT'].'/public/global.php');
	if($lx==1)
	{
		$selected=$zhi=='0'?'selected':''; echo '<option value="0" '.$selected.'>'.$LG['function_types.26'].'</option>';
		$selected=$zhi=='1'?'selected':''; echo '<option value="1" '.$selected.'>'.$LG['function_types.27'].'</option>';
		$selected=$zhi=='2'?'selected':''; echo '<option value="2" '.$selected.'>'.$LG['function_types.28'].'</option>';
		$selected=$zhi=='11'?'selected':''; echo '<option value="11" '.$selected.'>'.$LG['function_types.29'].'</option>';
		$selected=$zhi=='12'?'selected':''; echo '<option value="12" '.$selected.'>'.$LG['function_types.30'].'</option>';
	}
	elseif($lx==2){
		$checked=$zhi=='0'?'checked':''; echo '<label class="radio-inline"><input type="radio"  name="status" value="0" '.$checked.'>'.$LG['function_types.26'].'</label>';
		$checked=$zhi=='1'?'checked':''; echo '<label class="radio-inline"><input type="radio"  name="status" value="1" '.$checked.'>'.$LG['function_types.27'].'</label>';
		//$checked=$zhi=='2'?'checked':''; echo '<label class="radio-inline"><input type="radio"  name="status" value="2" '.$checked.'>'.$LG['function_types.28'].'</label>';
		$checked=$zhi=='11'?'checked':''; echo '<label class="radio-inline"><input type="radio"  name="status" value="11" '.$checked.'>'.$LG['function_types.29'].'</label>';
		$checked=$zhi=='12'?'checked':''; echo '<label class="radio-inline"><input type="radio"  name="status" value="12" '.$checked.'>'.$LG['function_types.30'].'</label>';
	}else{
		switch($zhi)
		{
			case '0':return '<span class="label label-sm label-default">'.$LG['function_types.26'].'</span>';break;
			case '1':return '<span class="label label-sm label-default">'.$LG['function_types.27'].'</span>'; break;
			case '2':return '<span class="label label-sm label-success">'.$LG['function_types.28'].'</span>'; break;
			case '11':return '<span class="label label-sm label-warning">'.$LG['function_types.29'].'</span>'; break;
			case '12':return '<span class="label label-sm label-danger">'.$LG['function_types.30'].'</span>'; break;
		}
	}
}


//友情链接分类
function LinkClass($zhi,$lx='')
{
	require($_SERVER['DOCUMENT_ROOT'].'/public/global.php');
	if($lx==1){
		$selected=$zhi=='1'?'selected':''; echo '<option value="1" '.$selected.'>'.$LG['function_types.31'].'</option>';
		$selected=$zhi=='2'?'selected':''; echo '<option value="2" '.$selected.'>'.$LG['function_types.32'].'</option>';
	}elseif($lx==2){
		global $class;
		$active=$class=='1'?'active':'';echo '<li class="'.$active.'"><a href="?class=1" class="btn">'.$LG['function_types.31'].'</a></li>';	
		$active=$class=='2'?'active':'';echo '<li class="'.$active.'"><a href="?class=2" class="btn">'.$LG['function_types.32'].'</a></li>';	
	}else{
		switch($zhi)
		{
			case '1':return $LG['function_types.31'];//友情链接 break;
			case '2':return $LG['function_types.32'];//合作伙伴 break;
		}
	}	
	
		
}

//HS/HG/快递号码/批次号类型
function hscode_Types($zhi,$lx='')
{
	global $expresses;
	require($_SERVER['DOCUMENT_ROOT'].'/public/global.php');
	if($lx){

		$selected=$zhi=='1'?'selected':''; echo '<option value="1" '.$selected.'>'.$LG['function_types.33'].'</option>';
		$selected=$zhi=='2'?'selected':''; echo '<option value="2" '.$selected.'>'.$LG['function_types.34'].'</option>';
		yundan_gnkd($zhi);
	}else{
		if($zhi==1){return $LG['function_types.33'];}//HS/HG编码}
		if($zhi==2){return $LG['function_types.34'];}//批次号}
		else{return $expresses[$zhi];}
	}
}


//____________________________充值类型_______________________________
function money_cz($zhi,$lx='')
{
	require($_SERVER['DOCUMENT_ROOT'].'/public/global.php');
	if($lx){

	//
            $selected=$zhi=='51'?'selected':''; echo '<option value="51" '.$selected.'>'.$LG['function_types.35'].'</option>';
            $selected=$zhi=='52'?'selected':''; echo '<option value="52" '.$selected.'>'.$LG['function_types.36'].'</option>';
            $selected=$zhi=='53'?'selected':''; echo '<option value="53" '.$selected.'>'.$LG['function_types.37'].'</option>';
            $selected=$zhi=='54'?'selected':''; echo '<option value="54" '.$selected.'>'.$LG['function_types.38'].'</option>';
			
            $selected=$zhi=='100'?'selected':''; echo '<option value="100" '.$selected.'>'.$LG['function_types.12'].'</option>';
	//
	}else{
		switch($zhi)
		{
			//充值接口类型 (1-20)
             case '':return '';
             case '1':return $LG['function_types.59'];//财付通
             case '2':return $LG['function_types.214'];//网银在线
             case '3':return $LG['function_types.215_1'];//支付宝
             case '4':return $LG['function_types.215'];//境外支付宝
             case '5':return $LG['function_types.216'];//快钱
             case '6':return $LG['function_types.217'];//PAYPAL支付
             case '7':return $LG['function_types.218'];//微信支付
             case '8':return 'SoftBank';//SoftBank
             case '9':return 'NihaoPay';//NihaoPay
			 
             case '20':return $LG['name.nav_37'];//转账充值
			 
			 //包裹操作类型 (30-50) ,在op_money_type($zhi,$lx='')
			 
			 //后台操作类型 (51-60)
             case '51':return $LG['function_types.35'];//后台退款
             case '52':return $LG['function_types.36'];//后台充值
             case '53':return $LG['function_types.37'];//后台赠送
             case '54':return $LG['function_types.38'];//后台理赔
			 
             case '100':return $LG['function_types.12'];//其他
			 
			 default:
			 return op_money_type($zhi);
			//
		}
	}	
}
//__________________________扣费类型_________________________________
function money_kf($zhi,$lx='')
{
	require($_SERVER['DOCUMENT_ROOT'].'/public/global.php');
	if($lx){

	//
            $selected=$zhi=='51'?'selected':''; echo '<option value="51" '.$selected.'>'.$LG['function_types.39'].'</option>';
            $selected=$zhi=='52'?'selected':''; echo '<option value="52" '.$selected.'>'.$LG['function_types.40'].'</option>';
            $selected=$zhi=='53'?'selected':''; echo '<option value="53" '.$selected.'>'.$LG['function_types.41'].'</option>';
            $selected=$zhi=='54'?'selected':''; echo '<option value="54" '.$selected.'>'.$LG['function_types.42'].'</option>';
            $selected=$zhi=='55'?'selected':''; echo '<option value="55" '.$selected.'>'.$LG['function_types.43'].'</option>';
            $selected=$zhi=='60'?'selected':''; echo '<option value="60" '.$selected.'>'.$LG['function_types.44'].'</option>';
			
	//
	}else{
		switch($zhi)
		{
			//
             case '':return '';
             //系统类型 (1-10)
             case '1':return $LG['function_types.79'];//商城订单
             case '2':return $LG['name.nav_27'];//上门取件
             case '3':return $LG['function_types.134'];//代购商品
			 
             case '10':return $LG['function_types.219'];//转账扣除
			 
			 //运单类型 (21-29)
             case '21':return $LG['function_types.150'];//运单费用
             case '22':return $LG['function_types.220'];//运单税费
			 
             //包裹操作类型 (30-50) ,在op_money_type($zhi,$lx='')

			 
			  //后台操作类型 (51-60)
             case '51':return $LG['function_types.39'];//后台扣费-运单
             case '52':return $LG['function_types.40'];//后台扣费-包裹
             case '53':return $LG['function_types.41'];//后台扣费-取件
             case '54':return $LG['function_types.42'];//后台扣费-退货
             case '55':return $LG['function_types.43'];//后台扣费-拍照
             case '60':return $LG['function_types.44'];//后台扣费-其他
			 
             case '100':return $LG['function_types.12'];//其他
			 
			 default:
			 return op_money_type($zhi);
			//
		}
	}	
}

//__________________________积分获得类型,加分类型,送分类型_________________________________
function integral_cz($zhi,$lx='')
{
	require($_SERVER['DOCUMENT_ROOT'].'/public/global.php');
	if($lx){

	//
            $selected=$zhi=='1'?'selected':''; echo '<option value="1" '.$selected.'>'.$LG['function_types.45'].'</option>';
            $selected=$zhi=='2'?'selected':''; echo '<option value="2" '.$selected.'>'.$LG['function_types.46'].'</option>';
            $selected=$zhi=='3'?'selected':''; echo '<option value="3" '.$selected.'>'.$LG['function_types.47'].'</option>';
            $selected=$zhi=='4'?'selected':''; echo '<option value="4" '.$selected.'>'.$LG['function_types.48'].'</option>';
            $selected=$zhi=='5'?'selected':''; echo '<option value="5" '.$selected.'>'.$LG['function_types.49'].'</option>';
            $selected=$zhi=='6'?'selected':''; echo '<option value="6" '.$selected.'>'.$LG['function_types.50'].'</option>';
            $selected=$zhi=='100'?'selected':''; echo '<option value="100" '.$selected.'>'.$LG['function_types.51'].'</option>';
	//
	}else{
		switch($zhi)
		{
			//
             case '':return '';
             case '1':return $LG['function_types.45'];//运单获得
             case '2':return $LG['function_types.46'];//晒单获得
             case '3':return $LG['function_types.47'];//评价获得
             case '4':return $LG['function_types.48'];//代购获得
             case '5':return $LG['function_types.49'];//商城获得
             case '6':return $LG['function_types.50'];//推广获得
             case '100':return $LG['function_types.51'];//其他赠送
			//
		}
	}	
}
//__________________________积分消费类型,扣分类型,减分类型_________________________________
function integral_kf($zhi,$lx='')
{
	require($_SERVER['DOCUMENT_ROOT'].'/public/global.php');
	if($lx){

	//
           $selected=$zhi=='1'?'selected':''; echo '<option value="1" '.$selected.'>'.$LG['function_types.52'].'</option>';
           $selected=$zhi=='2'?'selected':''; echo '<option value="2" '.$selected.'>'.$LG['function_types.53'].'</option>';
           $selected=$zhi=='3'?'selected':''; echo '<option value="3" '.$selected.'>'.$LG['function_types.54'].'</option>';
           $selected=$zhi=='100'?'selected':''; echo '<option value="100" '.$selected.'>'.$LG['function_types.55'].'</option>';
	//
	}else{
		switch($zhi)
		{
			//
             case '':return '';
             case '1':return $LG['function_types.52'];//运单抵消
             case '2':return $LG['function_types.53'];//包裹抵消
             case '3':return $LG['function_types.54'];//商城抵消
             case '100':return $LG['function_types.55'];//其他抵消
			//
		}
	}	
}
//_________________________取件状态__________________________________
function qujian_Status($zhi,$lx='')
{
	require($_SERVER['DOCUMENT_ROOT'].'/public/global.php');
	if($lx){

            $selected=$zhi=='0'?'selected':''; echo '<option value="0" '.$selected.'>'.$LG['function_types.13'].'</option>';
            $selected=$zhi=='1'?'selected':''; echo '<option value="1" '.$selected.'>'.$LG['name.nav_24'].'</option>';
            $selected=$zhi=='2'?'selected':''; echo '<option value="2" '.$selected.'>'.$LG['function_types.56'].'</option>';
            $selected=$zhi=='3'?'selected':''; echo '<option value="3" '.$selected.'>'.$LG['name.nav_29'].'</option>';
	}else{
		switch($zhi)
		{
             case '':return '';
             case '0':return '<span class="label label-sm label-default">'.$LG['function_types.13'].'</span>';
             case '1':return '<span class="label label-sm label-success">'.$LG['name.nav_24'].'</span>';
             case '2':return '<span class="label label-sm label-default">'.$LG['function_types.56'].'</span>';
             case '3':return '<span class="label label-sm label-danger">'.$LG['name.nav_29'].'</span>';
		}
	}	
}
            
            
    

//_________________________提现状态__________________________________
function tixian_Status($zhi,$lx='')
{
	require($_SERVER['DOCUMENT_ROOT'].'/public/global.php');
	if($lx){

            $selected=$zhi=='1'?'selected':''; echo '<option value="1" '.$selected.'>'.$LG['name.nav_24'].'</option>';
            $selected=$zhi=='2'?'selected':''; echo '<option value="2" '.$selected.'>'.$LG['function_types.57'].'</option>';
            $selected=$zhi=='3'?'selected':''; echo '<option value="3" '.$selected.'>'.$LG['name.nav_49'].'</option>';
	}else{
		switch($zhi)
		{
             case '':return '';
             case '1':return '<span class="label label-sm label-success">'.$LG['name.nav_24'].'</span>';
             case '2':return '<span class="label label-sm label-default">'.$LG['function_types.57'].'</span>';
             case '3':return '<span class="label label-sm label-danger">'.$LG['name.nav_49'].'</span>';
		}
	}	
}
//_________________________提现银行__________________________________
function tixian_Bank($zhi,$lx='')
{
	require($_SERVER['DOCUMENT_ROOT'].'/public/global.php');
	if($lx){
        $selected=$zhi==''?'selected':''; echo '<option value="" '.$selected.'></option>';
        $selected=$zhi=='支付宝'?'selected':''; echo '<option value="支付宝" '.$selected.'>支付宝</option>';
        $selected=$zhi=='财付通'?'selected':''; echo '<option value="财付通" '.$selected.'>财付通</option>';
		
        $selected=$zhi=='中国银行'?'selected':''; echo '<option value="中国银行" '.$selected.'>中国银行</option>';
        $selected=$zhi=='中国工商银行'?'selected':''; echo '<option value="中国工商银行" '.$selected.'>中国工商银行</option>';
        $selected=$zhi=='中国农业银行'?'selected':''; echo '<option value="中国农业银行" '.$selected.'>中国农业银行</option>';
        $selected=$zhi=='中国建设银行'?'selected':''; echo '<option value="中国建设银行" '.$selected.'>中国建设银行 </option>';
        $selected=$zhi=='中国交通银行'?'selected':''; echo '<option value="中国交通银行" '.$selected.'>中国交通银行</option>';
        $selected=$zhi=='中国华夏银行'?'selected':''; echo '<option value="中国华夏银行" '.$selected.'>中国华夏银行</option>';
        $selected=$zhi=='中国光大银行'?'selected':''; echo '<option value="中国光大银行" '.$selected.'>中国光大银行</option>';
        $selected=$zhi=='中国招商银行'?'selected':''; echo '<option value="中国招商银行" '.$selected.'>中国招商银行</option>';
        $selected=$zhi=='中国中信银行'?'selected':''; echo '<option value="中国中信银行" '.$selected.'>中国中信银行</option>';
        $selected=$zhi=='中国兴业银行'?'selected':''; echo '<option value="中国兴业银行" '.$selected.'>中国兴业银行</option>';
        $selected=$zhi=='中国民生银行'?'selected':''; echo '<option value="中国民生银行" '.$selected.'>中国民生银行</option>';
	}
}




//________________________优惠券/折扣券相关___________________________________
//类型
function Coupons_Types($zhi,$lx='')
{
	require($_SERVER['DOCUMENT_ROOT'].'/public/global.php');
	if($lx){

            $selected=$zhi=='1'?'selected':''; echo '<option value="1" '.$selected.'>'.$LG['function_types.71'].'</option>';
            $selected=$zhi=='2'?'selected':''; echo '<option value="2" '.$selected.'>'.$LG['function_types.72'].'</option>';
	}else{
		switch($zhi)
		{
             case '1':return $LG['function_types.71'];//优惠券
             case '2':return $LG['function_types.72'];//折扣券
		}
	}	

}

//状态
function Coupons_Status($zhi,$lx='')
{
	//除了0外,都是不可使用
	require($_SERVER['DOCUMENT_ROOT'].'/public/global.php');
	if($lx){

            $selected=$zhi=='0'?'selected':''; echo '<option value="0" '.$selected.'>'.$LG['function_types.73'].'</option>';
            $selected=$zhi=='1'?'selected':''; echo '<option value="1" '.$selected.'>'.$LG['function_types.74'].'</option>';
            $selected=$zhi=='2'?'selected':''; echo '<option value="2" '.$selected.'>'.$LG['function_types.75'].'</option>';
            $selected=$zhi=='10'?'selected':''; echo '<option value="10" '.$selected.'>'.$LG['function_types.76'].'</option>';
	}else{
		switch($zhi)
		{
             case '0':return $LG['function_types.73'];//可用
             case '1':return $LG['function_types.74'];//已用
             case '2':return $LG['function_types.75'];//过期
             case '10':return $LG['function_types.76'];//失效
		}
	}	
}


//获取途径
function Coupons_getSource($zhi,$lx='')
{
	require($_SERVER['DOCUMENT_ROOT'].'/public/global.php');
	if($lx){

            $selected=$zhi=='1'?'selected':''; echo '<option value="1" '.$selected.'>'.$LG['coupons.getSource1'].'</option>';
            $selected=$zhi=='2'?'selected':''; echo '<option value="2" '.$selected.'>'.$LG['coupons.getSource2'].'</option>';
            $selected=$zhi=='3'?'selected':''; echo '<option value="3" '.$selected.'>'.$LG['coupons.getSource3'].'</option>';
            $selected=$zhi=='4'?'selected':''; echo '<option value="4" '.$selected.'>'.$LG['coupons.getSource4'].'</option>';
	}else{
		switch($zhi)
		{
             case '1':return $LG['coupons.getSource1'];//兑换码兑换
             case '2':return $LG['coupons.getSource2'];//后台给予
             case '3':return $LG['coupons.getSource3'];//注册赠送
             case '4':return $LG['coupons.getSource4'];//推广获取
		}
	}	
}

//使用类型
function Coupons_usetypes($zhi,$lx='')
{
	require($_SERVER['DOCUMENT_ROOT'].'/public/global.php');
	if($lx){

            $selected=$zhi=='0'?'selected':''; echo '<option value="0" '.$selected.'>'.$LG['all'].'</option>';
            $selected=$zhi=='1'?'selected':''; echo '<option value="1" '.$selected.'>'.$LG['name.nav_67'].'</option>';
            $selected=$zhi=='2'?'selected':''; echo '<option value="2" '.$selected.'>'.$LG['name.nav_68'].'</option>';
	}else{
		switch($zhi)
		{
             case '0':return $LG['all'];//全部
             case '1':return $LG['name.nav_67'];//运单
             case '2':return $LG['name.nav_68'];//商城
		}
	}	
}



//________________________系统名称:表名,充值、消费等费用相关的有用到___________________________________
//用途
function fromtableName($zhi,$lx='')
{
	require($_SERVER['DOCUMENT_ROOT'].'/public/global.php');
	if($lx){

            $selected=$zhi=='baoguo'?'selected':''; echo '<option value="baoguo" '.$selected.'>'.$LG['parcel'].'</option>';
            $selected=$zhi=='yundan'?'selected':''; echo '<option value="yundan" '.$selected.'>'.$LG['name.nav_67'].'</option>';
            $selected=$zhi=='mall_order'?'selected':''; echo '<option value="mall_order" '.$selected.'>'.$LG['function_types.79'].'</option>';
            $selected=$zhi=='lipei'?'selected':''; echo '<option value="lipei" '.$selected.'>'.$LG['name.nav_31'].'</option>';
            $selected=$zhi=='daigou'?'selected':''; echo '<option value="daigou" '.$selected.'>'.$LG['name.nav_70'].'</option>';
            $selected=$zhi=='shaidan'?'selected':''; echo '<option value="shaidan" '.$selected.'>'.$LG['name.nav_53'].'</option>';
            $selected=$zhi=='tixian'?'selected':''; echo '<option value="tixian" '.$selected.'>'.$LG['name.nav_47'].'</option>';
            $selected=$zhi=='other'?'selected':''; echo '<option value="other" '.$selected.'>'.$LG['function_types.12'].'</option>';
	}else{
		switch($zhi)
		{
             case 'baoguo':return $LG['parcel'];//包裹
             case 'yundan':return $LG['name.nav_67'];//运单
             case 'mall_order':return $LG['function_types.79'];//商城订单
             case 'lipei':return $LG['name.nav_31'];//理赔
             case 'daigou':return $LG['name.nav_70'];//代购
             case 'shaidan':return $LG['name.nav_53'];//晒单
             case 'tixian':return $LG['name.nav_47'];//提现
             case 'transfer':return $LG['name.nav_37'];//转账充值
             case 'other':return $LG['function_types.12'];//其他
		}
	}	
}

//来自内容页链接
function fromtableUrl($fromtable,$fromid)
{
	if(!$fromtable||!$fromid||$fromtable=='other'){return 'href="javascript:void(0)"';}
	$path=getPath();
	switch($fromtable)
	{
		 case 'baoguo':return 'href="'.$path.'baoguo/show.php?bgid='.$fromid.'" target="_blank"';
		 case 'yundan':return 'href="'.$path.'yundan/show.php?ydid='.$fromid.'" target="_blank"';
		 case 'daigou':return 'href="'.$path.'daigou/show.php?dgid='.$fromid.'" target="_blank"';
		 case 'mall_order':return 'href="'.$path.'mall_order/list.php?so=1&key='.$fromid.'" target="_blank"';
		 case 'lipei':return 'href="'.$path.'lipei/list.php?so=1&key='.$fromid.'" target="_blank"';
		 case 'shaidan':return 'href="'.$path.'shaidan/list.php?so=1&key='.$fromid.'" target="_blank"';
		 case 'tixian':return 'href="'.$path.'tixian/list.php?so=1&key='.$fromid.'" target="_blank"';
		 case 'transfer':return 'href="'.$path.'transfer/list.php?so=1&key='.$fromid.'&status=all" target="_blank"';
	}
}


//________________________包裹相关___________________________________


//打印模板
/*
$lx=1下拉菜单;$lx=2操作菜单(要有$bgid)
*/
function baoguo_print($zhi='',$lx=0,$bgid='')
{
	require($_SERVER['DOCUMENT_ROOT'].'/public/global.php');
	if($lx==1)
	{
		$selected=$zhi=='label_small'?'selected':''; echo '<option value="label_small" '.$selected.'>'.$LG['function_types.80'].'</option>';
		$selected=$zhi=='label_medium'?'selected':''; echo '<option value="label_medium" '.$selected.'>'.$LG['function_types.81'].'</option>';
		$selected=$zhi=='listing'?'selected':''; echo '<option value="listing" '.$selected.'>'.$LG['function_types.82'].'</option>';
	}elseif($lx==2){
		
		echo '<li><a href="print.php?print_tem=label_small&lx=pr&bgid='.$bgid.'" style="padding-left:10px;float:left;" target="_blank"><i class="icon-share"></i> '.$LG['function_types.80'].'</a></li>';
		echo '<li><a href="print.php?print_tem=label_medium&lx=pr&bgid='.$bgid.'" style="padding-left:10px;float:left;" target="_blank"><i class="icon-share"></i> '.$LG['function_types.81'].'</a></li>';
		echo '<li><a href="print.php?print_tem=listing&lx=pr&bgid='.$bgid.'" style="padding-left:10px;float:left;" target="_blank"><i class="icon-share"></i> '.$LG['function_types.82'].'</a></li>';

	}else{
		switch($zhi)
		{
			case 'label_small':return $LG['function_types.80'];//包裹小标签
			case 'label_medium':return $LG['function_types.81'];//包裹中标签
			case 'listing':return $LG['function_types.82'];//操作清单
		}
	}
}




//包裹状态----------------------------
function baoguo_Status($zhi,$lx='')//状态已绑定,不可再修改,可以新加
{
	require($_SERVER['DOCUMENT_ROOT'].'/public/global.php');
	global $bg_shelves;
	
	$zhi=spr($zhi,2,0);//有小数点时,必须加spr
	if($lx==1){
	//
            $selected=$zhi==''?'selected':''; echo '<option value="" '.$selected.'></option>';
            $selected=$zhi=='0'?'selected':''; echo '<option value="0" '.$selected.'>'.$LG['baoguo.status0'].'</option>';
            $selected=$zhi=='1'?'selected':''; echo '<option value="1" '.$selected.'>'.$LG['baoguo.status1'].'</option>';
            if($bg_shelves){$selected=$zhi=='1.5'?'selected':''; echo '<option value="1.5" '.$selected.'>'.$LG['baoguo.status1.5'].'</option>';}
            $selected=$zhi=='2'?'selected':''; echo '<option value="2" '.$selected.'>'.$LG['baoguo.status2'].'</option>';
            $selected=$zhi=='3'?'selected':''; echo '<option value="3" '.$selected.'>'.$LG['baoguo.status3'].'</option>';
            $selected=$zhi=='4'?'selected':''; echo '<option value="4" '.$selected.'>'.$LG['baoguo.status4'].'</option>';
			
            $selected=$zhi=='6'?'selected':''; echo '<option value="6" '.$selected.'>'.$LG['baoguo.status6'].'</option>';
            $selected=$zhi=='7'?'selected':''; echo '<option value="7" '.$selected.'>'.$LG['baoguo.status7'].'</option>';
			
            $selected=$zhi=='9'?'selected':''; echo '<option value="9" '.$selected.'>'.$LG['baoguo.status9'].'</option>';
            $selected=$zhi=='10'?'selected':''; echo '<option value="10" '.$selected.'>'.$LG['baoguo.status10'].'</option>';
	//
	}elseif($lx==2){
		switch($zhi)
		{
			case '0':return '<span class="label label-sm label-default">'.$LG['baoguo.status0'].'</span>'; 
			case '1':return '<span class="label label-sm label-default">'.$LG['baoguo.status1'].'</span>'; 
			case '1.5':return '<span class="label label-sm label-default">'.$LG['baoguo.status1.5'].'</span>'; 
			case '2':return '<span class="label label-sm label-default">'.$LG['baoguo.status2'].'</span>'; 
			case '3':return '<span class="label label-sm label-warning">'.$LG['baoguo.status3'].'</span>';
			case '4':return '<span class="label label-sm label-success">'.$LG['baoguo.status4'].'</span>'; 
			case '5':return '<span class="label label-sm label-danger">'.$LG['baoguo.status5'].'</span>'; //待处理操作:自动判断显示,不要下拉选择
			
			case '6':return '<span class="label label-sm label-default">'.$LG['baoguo.status6'].'</span>'; 
			case '7':return '<span class="label label-sm label-success">'.$LG['baoguo.status7'].'</span>'; 
			
			case '9':return '<span class="label label-sm label-default">'.$LG['baoguo.status9'].'</span>'; 
			case '10':return '<span class="label label-sm label-default">'.$LG['baoguo.status10'].'</span>'; 
		}
	}else{
		switch($zhi)
		{
			//
             case '0':return $LG['baoguo.status0'];//待入库
             case '1':return $LG['baoguo.status1'];//待入库(已事先下单)
             case '1.5':return $LG['baoguo.status1.5'];//待上架
             case '2':return $LG['baoguo.status2'];//待确认包裹
             case '3':return $LG['baoguo.status3'];//已入库
             case '4':return $LG['baoguo.status4'];//已下单
			 
             case '6':return $LG['baoguo.status6'];//待发货
             case '7':return $LG['baoguo.status7'];//已发货
			 
             case '9':return $LG['baoguo.status9'];//已签收
             case '10':return $LG['baoguo.status10'];//记录
			//
		}
	}	
}


//操作与充值/扣费类型对照
/*
$lx='' 显示文字;$lx=1 返回保存值
*/
function op_money_type($zhi,$lx='')
{
	require($_SERVER['DOCUMENT_ROOT'].'/public/global.php');
	if($lx){

		switch($zhi)
		{
			 case 'hx':return '46';
			 case 'fx':return '47';
			 case 'th':return '48';
			 case 'ware':return '49';

			 case 'op_02':return '32';
			 case 'op_04':return '34';
			 case 'op_06':return '36';
			 case 'op_07':return '37';
			 case 'op_09':return '39';
			 case 'op_10':return '40';
			 case 'op_11':return '41';
		}
	}else{
		switch($zhi)
		{
			 case '46':return $LG['function_types.222'];//包裹合箱
			 case '47':return $LG['function_types.223'];//包裹分箱
			 case '48':return $LG['function_types.224'];//包裹退货
			 case '49':return $LG['function_types.225'];//包裹仓储

			 case '32':return $LG['function_types.226'];//包裹验货
			 case '34':return $LG['function_types.227'];//包裹转移仓库
			 case '36':return $LG['function_types.228'];//包裹拍照
			 case '37':return $LG['function_types.229'];//包裹减重
			 case '39':return $LG['function_types.230'];//包裹清点
			 case '40':return $LG['function_types.231'];//包裹复称
			 case '41':return $LG['function_types.232'];//包裹填空隙
		}
	}	
}


//合箱----------------------------
function baoguo_hx($zhi,$lx='')
{
	require($_SERVER['DOCUMENT_ROOT'].'/public/global.php');
	if($lx==1){
	//
            $selected=$zhi==''?'selected':''; echo '<option value="" '.$selected.'></option>';
            $selected=$zhi=='1'?'selected':''; echo '<option value="1" '.$selected.'>'.$LG['function_types.93'].'</option>';
            $selected=$zhi=='2'?'selected':''; echo '<option value="2" '.$selected.'>'.$LG['function_types.94'].'</option>';
            $selected=$zhi=='3'?'selected':''; echo '<option value="3" '.$selected.'>'.$LG['function_types.95'].'</option>';
	//
	}elseif($lx==2){
		switch($zhi)
		{
			//
             case '':return '';
             case '1':return '<span class="label label-sm label-warning">'.$LG['function_types.93'].'</span>';
             case '2':return '<span class="label label-sm label-default">'.$LG['function_types.94'].'</span>';
             case '3':return '<span class="label label-sm label-danger">'.$LG['function_types.95'].'</span>';
			//
		}
	}else{
		switch($zhi)
		{
			//
             case '':return '';
             case '1':return $LG['function_types.93'];//申请单合箱
             case '2':return $LG['function_types.94'];//已合箱
             case '3':return $LG['function_types.95'];//拒绝合箱
			//
		}
	}	
}

//分箱----------------------------
function baoguo_fx($zhi,$lx='')
{
	require($_SERVER['DOCUMENT_ROOT'].'/public/global.php');
	if($lx==1){
	//
            $selected=$zhi==''?'selected':''; echo '<option value="" '.$selected.'></option>';
            $selected=$zhi=='1'?'selected':''; echo '<option value="1" '.$selected.'>'.$LG['function_types.96'].'</option>';
            $selected=$zhi=='2'?'selected':''; echo '<option value="2" '.$selected.'>'.$LG['function_types.97'].'</option>';
            $selected=$zhi=='3'?'selected':''; echo '<option value="3" '.$selected.'>'.$LG['function_types.98'].'</option>';
	//
	}elseif($lx==2){
		switch($zhi)
		{
			//
             case '':return '';
             case '1':return '<span class="label label-sm label-warning">'.$LG['function_types.96'].'</span>';
             case '2':return '<span class="label label-sm label-default">'.$LG['function_types.97'].'</span>';
             case '3':return '<span class="label label-sm label-danger">'.$LG['function_types.98'].'</span>';
			//
		}
	}else{
		switch($zhi)
		{
			//
             case '':return '';
             case '1':return $LG['function_types.96'];//申请单分箱
             case '2':return $LG['function_types.97'];//已分箱
             case '3':return $LG['function_types.98'];//拒绝分箱
			//
		}
	}	
}

//----------------------------
function baoguo_th($zhi,$lx='')
{
	require($_SERVER['DOCUMENT_ROOT'].'/public/global.php');
	if($lx==1){
	//
		$selected=$zhi==''?'selected':''; echo '<option value="" '.$selected.'></option>';
		$selected=$zhi=='1'?'selected':''; echo '<option value="1" '.$selected.'>'.$LG['function_types.99'].'</option>';
		$selected=$zhi=='2'?'selected':''; echo '<option value="2" '.$selected.'>'.$LG['function_types.100'].'</option>';
		$selected=$zhi=='3'?'selected':''; echo '<option value="3" '.$selected.'>'.$LG['function_types.101'].'</option>';
		$selected=$zhi=='10'?'selected':''; echo '<option value="10" '.$selected.'>'.$LG['function_types.102'].'</option>';
	//
	}elseif($lx==2){
		switch($zhi)
		{
			//
              case '':return '';
             case '1':return '<span class="label label-sm label-warning">'.$LG['function_types.99'].'</span>';
             case '2':return '<span class="label label-sm label-default">'.$LG['function_types.100'].'</span>';
             case '3':return '<span class="label label-sm label-danger">'.$LG['function_types.101'].'</span>';
             case '10':return '<span class="label label-sm label-warning">'.$LG['function_types.102'].'</span>';
			//
		}
	}else{
		switch($zhi)
		{
			//
              case '':return '';
             case '1':return $LG['function_types.99'];//申请退货
             case '2':return $LG['function_types.100'];//已退货
             case '3':return $LG['function_types.101'];//拒绝处理退货
             case '10':return $LG['function_types.102'];//取消申请退货
			//
		}
	}	
}
//----------------------------
function baoguo_op_02($zhi,$lx='')
{
	require($_SERVER['DOCUMENT_ROOT'].'/public/global.php');
	if($lx==1){
	//
            $selected=$zhi==''?'selected':''; echo '<option value="" '.$selected.'></option>';
            $selected=$zhi=='1'?'selected':''; echo '<option value="1" '.$selected.'>'.$LG['function_types.103'].'</option>';
            $selected=$zhi=='2'?'selected':''; echo '<option value="2" '.$selected.'>'.$LG['function_types.104'].'</option>';
            $selected=$zhi=='3'?'selected':''; echo '<option value="3" '.$selected.'>'.$LG['function_types.105'].'</option>';
            $selected=$zhi=='10'?'selected':''; echo '<option value="10" '.$selected.'>'.$LG['function_types.106'].'</option>';
	//
	}elseif($lx==2){
		switch($zhi)
		{
			//
             case '':return '';
             case '1':return '<span class="label label-sm label-warning">'.$LG['function_types.103'].'</span>';
             case '2':return '<span class="label label-sm label-default">'.$LG['function_types.104'].'</span>';
             case '3':return '<span class="label label-sm label-danger">'.$LG['function_types.105'].'</span>';
             case '10':return '<span class="label label-sm label-warning">'.$LG['function_types.106'].'</span>';
			//
		}
	}else{
		switch($zhi)
		{
			//
             case '':return '';
             case '1':return $LG['function_types.103'];//申请验货
             case '2':return $LG['function_types.104'];//已验货
             case '3':return $LG['function_types.105'];//拒绝验货
             case '10':return $LG['function_types.106'];//取消申请验货
			//
		}
	}	
}

//----------------------------
//baoguo_op_03 是申请合箱，已不使用
function baoguo_op_04($zhi,$lx='')
{
	require($_SERVER['DOCUMENT_ROOT'].'/public/global.php');
	if($lx==1){
	//
            $selected=$zhi==''?'selected':''; echo '<option value="" '.$selected.'></option>';
            $selected=$zhi=='1'?'selected':''; echo '<option value="1" '.$selected.'>'.$LG['function_types.107'].'</option>';
            $selected=$zhi=='2'?'selected':''; echo '<option value="2" '.$selected.'>'.$LG['function_types.108'].'</option>';
            $selected=$zhi=='3'?'selected':''; echo '<option value="3" '.$selected.'>'.$LG['function_types.109'].'</option>';
            $selected=$zhi=='10'?'selected':''; echo '<option value="10" '.$selected.'>'.$LG['function_types.110'].'</option>';
	//
	}elseif($lx==2){
		switch($zhi)
		{
			//
              case '':return '';
             case '1':return '<span class="label label-sm label-warning">'.$LG['function_types.107'].'</span>';
             case '2':return '<span class="label label-sm label-default">'.$LG['function_types.108'].'</span>';
             case '3':return '<span class="label label-sm label-danger">'.$LG['function_types.109'].'</span>';
             case '10':return '<span class="label label-sm label-warning">'.$LG['function_types.110'].'</span>';
			//
		}
	}else{
		switch($zhi)
		{
			//
             case '':return '';
             case '1':return $LG['function_types.107'];//申请转移仓库
             case '2':return $LG['function_types.108'];//已转移仓库
             case '3':return $LG['function_types.109'];//拒绝转移仓库
             case '10':return $LG['function_types.110'];//取消申请转移仓库
			//
		}
	}	
}
//----------------------------
//baoguo_op_05 是转移会员，已不使用
function baoguo_op_06($zhi,$lx='')
{
	require($_SERVER['DOCUMENT_ROOT'].'/public/global.php');
	if($lx==1){
	//
            $selected=$zhi==''?'selected':''; echo '<option value="" '.$selected.'></option>';
            $selected=$zhi=='1'?'selected':''; echo '<option value="1" '.$selected.'>'.$LG['function_types.111'].'</option>';
            $selected=$zhi=='2'?'selected':''; echo '<option value="2" '.$selected.'>'.$LG['function_types.112'].'</option>';
             $selected=$zhi=='3'?'selected':''; echo '<option value="3" '.$selected.'>'.$LG['function_types.113'].'</option>';
            $selected=$zhi=='10'?'selected':''; echo '<option value="10" '.$selected.'>'.$LG['function_types.114'].'</option>';
	//
	}elseif($lx==2){
		switch($zhi)
		{
			//
              case '':return '';
             case '1':return '<span class="label label-sm label-warning">'.$LG['function_types.111'].'</span>';
             case '2':return '<span class="label label-sm label-default">'.$LG['function_types.112'].'</span>';
             case '3':return '<span class="label label-sm label-danger">'.$LG['function_types.113'].'</span>';
             case '10':return '<span class="label label-sm label-warning">'.$LG['function_types.114'].'</span>';
			//
		}
	}else{
		switch($zhi)
		{
			//
             case '':return '';
             case '1':return $LG['function_types.111'];//申请拍照
             case '2':return $LG['function_types.112'];//已拍照
             case '3':return $LG['function_types.113'];//拒绝拍照
             case '10':return $LG['function_types.114'];//取消申请拍照
			//
		}
	}	
}
//----------------------------
function baoguo_op_07($zhi,$lx='')
{
	require($_SERVER['DOCUMENT_ROOT'].'/public/global.php');
	if($lx==1){
	//
            $selected=$zhi==''?'selected':''; echo '<option value="" '.$selected.'></option>';
            $selected=$zhi=='1'?'selected':''; echo '<option value="1" '.$selected.'>'.$LG['function_types.115'].'</option>';
            $selected=$zhi=='2'?'selected':''; echo '<option value="2" '.$selected.'>'.$LG['function_types.116'].'</option>';
             $selected=$zhi=='3'?'selected':''; echo '<option value="3" '.$selected.'>'.$LG['function_types.117'].'</option>';
            $selected=$zhi=='10'?'selected':''; echo '<option value="10" '.$selected.'>'.$LG['function_types.118'].'</option>';
	//
	}elseif($lx==2){
		switch($zhi)
		{
			//
              case '':return '';
             case '1':return '<span class="label label-sm label-warning">'.$LG['function_types.115'].'</span>';
             case '2':return '<span class="label label-sm label-default">'.$LG['function_types.116'].'</span>';
             case '3':return '<span class="label label-sm label-danger">'.$LG['function_types.117'].'</span>';
             case '10':return '<span class="label label-sm label-warning">'.$LG['function_types.118'].'</span>';
			//
		}
	}else{
		switch($zhi)
		{
			//
              case '':return '';
             case '1':return $LG['function_types.115'];//申请减重
             case '2':return $LG['function_types.116'];//已减重
             case '3':return $LG['function_types.117'];//拒绝减重
             case '10':return $LG['function_types.118'];//取消申请减重
			//
		}
	}	
}

//----------------------------
function baoguo_op_09($zhi,$lx='')
{
	require($_SERVER['DOCUMENT_ROOT'].'/public/global.php');
	if($lx==1){
	//
            $selected=$zhi==''?'selected':''; echo '<option value="" '.$selected.'></option>';
            $selected=$zhi=='1'?'selected':''; echo '<option value="1" '.$selected.'>'.$LG['function_types.119'].'</option>';
            $selected=$zhi=='2'?'selected':''; echo '<option value="2" '.$selected.'>'.$LG['function_types.120'].'</option>';
            $selected=$zhi=='3'?'selected':''; echo '<option value="3" '.$selected.'>'.$LG['function_types.121'].'</option>';
            $selected=$zhi=='10'?'selected':''; echo '<option value="10" '.$selected.'>'.$LG['function_types.122'].'</option>';
	//
	}elseif($lx==2){
		switch($zhi)
		{
			//
              case '':return '';
             case '1':return '<span class="label label-sm label-warning">'.$LG['function_types.119'].'</span>';
             case '2':return '<span class="label label-sm label-default">'.$LG['function_types.120'].'</span>';
             case '3':return '<span class="label label-sm label-danger">'.$LG['function_types.121'].'</span>';
             case '10':return '<span class="label label-sm label-warning">'.$LG['function_types.122'].'</span>';
			//
		}
	}else{
		switch($zhi)
		{
			//
              case '':return '';
             case '1':return $LG['function_types.119'];//申请清点
             case '2':return $LG['function_types.120'];//已清点
             case '3':return $LG['function_types.121'];//拒绝清点
             case '10':return $LG['function_types.122'];//取消清点
			//
		}
	}	
}
//----------------------------
function baoguo_op_10($zhi,$lx='')
{
	require($_SERVER['DOCUMENT_ROOT'].'/public/global.php');
	if($lx==1){
	//
            $selected=$zhi==''?'selected':''; echo '<option value="" '.$selected.'></option>';
            $selected=$zhi=='1'?'selected':''; echo '<option value="1" '.$selected.'>'.$LG['function_types.123'].'</option>';
            $selected=$zhi=='2'?'selected':''; echo '<option value="2" '.$selected.'>'.$LG['function_types.124'].'</option>';
              $selected=$zhi=='3'?'selected':''; echo '<option value="3" '.$selected.'>'.$LG['function_types.125'].'</option>';
           $selected=$zhi=='10'?'selected':''; echo '<option value="10" '.$selected.'>'.$LG['function_types.126'].'</option>';
	//
	}elseif($lx==2){
		switch($zhi)
		{
			//
              case '':return '';
             case '1':return '<span class="label label-sm label-warning">'.$LG['function_types.123'].'</span>';
             case '2':return '<span class="label label-sm label-default">'.$LG['function_types.124'].'</span>';
             case '3':return '<span class="label label-sm label-danger">'.$LG['function_types.125'].'</span>';
             case '10':return '<span class="label label-sm label-warning">'.$LG['function_types.126'].'</span>';
			//
		}
	}else{
		switch($zhi)
		{
			//
              case '':return '';
             case '1':return $LG['function_types.123'];//申请复称
             case '2':return $LG['function_types.124'];//已复称
             case '3':return $LG['function_types.125'];//拒绝复称
             case '10':return $LG['function_types.126'];//取消复称
			//
		}
	}	
}
//----------------------------
function baoguo_op_11($zhi,$lx='')
{
	require($_SERVER['DOCUMENT_ROOT'].'/public/global.php');
	if($lx==1){
	//
            $selected=$zhi==''?'selected':''; echo '<option value="" '.$selected.'></option>';
            $selected=$zhi=='1'?'selected':''; echo '<option value="1" '.$selected.'>'.$LG['function_types.127'].'</option>';
            $selected=$zhi=='2'?'selected':''; echo '<option value="2" '.$selected.'>'.$LG['function_types.128'].'</option>';
            $selected=$zhi=='3'?'selected':''; echo '<option value="3" '.$selected.'>'.$LG['function_types.129'].'</option>';
            $selected=$zhi=='10'?'selected':''; echo '<option value="10" '.$selected.'>'.$LG['function_types.130'].'</option>';
	//
	}elseif($lx==2){
		switch($zhi)
		{
			//
             case '':return '';
             case '1':return '<span class="label label-sm label-warning">'.$LG['function_types.127'].'</span>';
             case '2':return '<span class="label label-sm label-default">'.$LG['function_types.128'].'</span>';
             case '3':return '<span class="label label-sm label-danger">'.$LG['function_types.129'].'</span>';
             case '10':return '<span class="label label-sm label-warning">'.$LG['function_types.130'].'</span>';
			//
		}
	}else{
		switch($zhi)
		{
			//
              case '':return '';
             case '1':return $LG['function_types.127'];//申请填空隙
             case '2':return $LG['function_types.128'];//已填空隙
             case '3':return $LG['function_types.129'];//拒绝填空隙
             case '10':return $LG['function_types.130'];//取消申请填空隙
			//
		}
	}	
}
//____________________________包裹来源_______________________________
function baoguo_addSource($zhi,$lx='')
{
	require($_SERVER['DOCUMENT_ROOT'].'/public/global.php');
	if($lx){

	//
            $selected=$zhi=='1'?'selected':''; echo '<option value="1" '.$selected.'>'.$LG['function_types.131'].'</option>';
            $selected=$zhi=='2'?'selected':''; echo '<option value="2" '.$selected.'>'.$LG['function_types.132'].'</option>';
            $selected=$zhi=='3'?'selected':''; echo '<option value="3" '.$selected.'>'.$LG['function_types.133'].'</option>';
            $selected=$zhi=='4'?'selected':''; echo '<option value="4" '.$selected.'>'.$LG['function_types.134'].'</option>';
            $selected=$zhi=='5'?'selected':''; echo '<option value="5" '.$selected.'>'.$LG['function_types.135'].'</option>';
            $selected=$zhi=='6'?'selected':''; echo '<option value="6" '.$selected.'>'.$LG['function_types.136'].'</option>';
	//
	}else{
		switch($zhi)
		{
			//
             case '':return '';
             case '1':return $LG['function_types.131'];//会员预报
             case '2':return $LG['function_types.132'];//仓库添加
             case '3':return $LG['function_types.133'];//本站商城
             case '4':return $LG['function_types.134'];//代购商品
             case '5':return $LG['function_types.135'];//合箱
             case '6':return $LG['function_types.136'];//分箱
			//
		}
	}	
}












//-----------------------------------------------清关资料相关----------------------------------------------------

//清关公司
function CustomsType($zhi,$lx=0)
{
	global $ON_gd_mosuda;
	if($lx)
	{
		echo '<option value=""></option>';
		if($ON_gd_mosuda){$selected=$zhi=='gd_mosuda'?'selected':''; echo '<option value="gd_mosuda" '.$selected.'>跨境翼</option>';}
		
	}else{
		switch($zhi)
		{
			case 'gd_mosuda':return '跨境翼';
		}
	}
}




//-----------------------------------------------运单相关----------------------------------------------------



//运单打印模板
/*
$lx=1下拉菜单;$lx=2操作菜单(要有$ydid)
*/
function yundan_print($zhi='',$lx=0,$ydid='',$member=0)
{
	global $ON_dhl;
	require($_SERVER['DOCUMENT_ROOT'].'/public/global.php');
	if($lx==1)
	{
		if(!$member){ echo '<option value="picking" '.($zhi=='picking'?'selected':'').'>'.$LG['function_types.137_1'].'</option>';}
	  	if(!$member){ echo '<option value="listing1" '.($zhi=='listing1'?'selected':'').'>'.$LG['function_types.137'].'1</option>';}
	   	if(!$member){ echo '<option value="listing2" '.($zhi=='listing2'?'selected':'').'>'.$LG['function_types.137'].'2</option>';}
		
	  	if(!$member){ echo '<option disabled></option>';}
		
		
	  	if(1==1){ echo '<option value="1" '.($zhi=='1'?'selected':'').'>'.$LG['function_types.138'].'</option>';}
	  	if(!$member){ echo '<option value="2" '.($zhi=='2'?'selected':'').'>'.$LG['function_types.139'].'</option>';}
	  	if(!$member){ echo '<option value="3" '.($zhi=='3'?'selected':'').'>'.$LG['function_types.140'].'</option>';}
		
	  	if(!$member){ echo '<option disabled></option>';}

	  	if(1==1){ echo '<option value="barcode" '.($zhi=='barcode'?'selected':'').'>'.$LG['function_types.141'].'</option>';}
	  	if(1==1){ echo '<option value="barcode2" '.($zhi=='barcode2'?'selected':'').'>'.$LG['function_types.141_1'].'</option>';}
		
	  	if(!$member){ echo '<option disabled></option>';}

	  	if(1==1){ echo '<option value="normal" '.($zhi=='normal'?'selected':'').'>'.$LG['function_types.142'].'</option>';}
		
	  	if(!$member){ echo '<option value="ansuo_big" '.($zhi=='ansuo_big'?'selected':'').'>'.$LG['function_types.143'].'</option>';}
	  	if(!$member){ echo '<option value="ansuo_small" '.($zhi=='ansuo_small'?'selected':'').'>'.$LG['function_types.144'].'</option>';}
		
	  	if(!$member){ echo '<option disabled></option>';}

	  	if(!$member){ echo '<option value="jp_invoce" '.($zhi=='jp_invoce'?'selected':'').'>'.$LG['function_types.145'].'</option>';}
	  	if(!$member){ echo '<option value="jp_invoce_cn" '.($zhi=='jp_invoce_cn'?'selected':'').'>'.$LG['function_types.145_1'].'</option>';}
		if(!$member){ echo '<option value="jp_ems" '.($zhi=='jp_ems'?'selected':'').'>'.$LG['function_types.146'].'</option>';}
		if(!$member){ echo '<option value="jp_post" '.($zhi=='jp_post'?'selected':'').'>'.$LG['function_types.147'].'</option>';}

		
		if(!$member){ echo '<option disabled></option>';}

		if(!$member){ echo '<option value="china_express" '.($zhi=='china_express'?'selected':'').'>'.$LG['function_types.148'].'</option>';}
		if(!$member){ echo '<option value="china_chex" '.($zhi=='china_chex'?'selected':'').'>'.$LG['function_types.149'].'</option>';}
		if($ON_dhl&&1==1){ echo '<option value="dhl" '.($zhi=='dhl'?'selected':'').'>DHL</option>';}
		
		

		if(!$member){ echo '<option value="hm_ems" '.($zhi=='hm_ems'?'selected':'').'>'.$LG['function_types.900'].'</option>';}
	  if(!$member){ echo '<option value="sh_ems" '.($zhi=='sh_ems'?'selected':'').'>'.$LG['function_types.901'].'</option>';}
	  if(!$member){ echo '<option value="jiao_td" '.($zhi=='jiao_td'?'selected':'').'>'.$LG['function_types.902'].'</option>';}
	  if(!$member){ echo '<option value="fedex_td" '.($zhi=='fedex_td'?'selected':'').'>'.$LG['function_types.903'].'</option>';}	  
	  if(!$member){ echo '<option value="shunfeng_td" '.($zhi=='shunfeng_td'?'selected':'').'>'.$LG['function_types.904'].'</option>';}  
	  if(!$member){ echo '<option value="hm_youzheng" '.($zhi=='hm_youzheng'?'selected':'').'>'.$LG['function_types.905'].'</option>';}	
	  if(!$member){ echo '<option value="sh_youzheng" '.($zhi=='sh_youzheng'?'selected':'').'>'.$LG['function_types.906'].'</option>';}	  
	  if(!$member){ echo '<option value="dpex_td" '.($zhi=='dpex_td'?'selected':'').'>'.$LG['function_types.907'].'</option>';}  	  
	  if(!$member){ echo '<option value="yuantong_td" '.($zhi=='yuantong_td'?'selected':'').'>'.$LG['function_types.908'].'</option>';}  	  
	}elseif($lx==2){
		//模板太长,不适合显示,停用
/*		if(!$member){echo '<li><a href="print.php?print_tem=picking&lx=pr&ydid='.$ydid.'" style="padding-left:10px;float:left;" target="_blank"><i class="icon-share"></i>'.$LG['function_types.137_1'].'</a></li>';}
		if(!$member){echo '<li><a href="print.php?print_tem=listing1&lx=pr&ydid='.$ydid.'" style="padding-left:10px;float:left;" target="_blank"><i class="icon-share"></i>'.$LG['function_types.137'].'1</a></li>';}
		if(!$member){echo '<li><a href="print.php?print_tem=listing2&lx=pr&ydid='.$ydid.'" style="padding-left:10px;float:left;" target="_blank"><i class="icon-share"></i>'.$LG['function_types.137'].'2</a></li>';}
		
		if(!$member){echo '<li><a></a></li>';}

		if(!$member){echo '<li><a href="print.php?print_tem=1&lx=pr&ydid='.$ydid.'" style="padding-left:10px;float:left;" target="_blank"><i class="icon-share"></i> '.$LG['function_types.138'].'</a></li>';}
		if(!$member){echo '<li><a href="print.php?print_tem=2&lx=pr&ydid='.$ydid.'" style="padding-left:10px;float:left;" target="_blank"><i class="icon-share"></i> '.$LG['function_types.139'].'</a></li>';}
		if(!$member){echo '<li><a href="print.php?print_tem=3&lx=pr&ydid='.$ydid.'" style="padding-left:10px;float:left;" target="_blank"><i class="icon-share"></i> '.$LG['function_types.140'].'</a></li>';}
		
		if(!$member){echo '<li><a></a></li>';}

		if(!$member){echo '<li><a href="print.php?print_tem=barcode&lx=pr&ydid='.$ydid.'" style="padding-left:10px;float:left;" target="_blank"><i class="icon-share"></i> '.$LG['function_types.141'].'</a></li>';}
		if(!$member){echo '<li><a href="print.php?print_tem=barcode2&lx=pr&ydid='.$ydid.'" style="padding-left:10px;float:left;" target="_blank"><i class="icon-share"></i> '.$LG['function_types.141_1'].'</a></li>';}
		
		if(!$member){echo '<li><a></a></li>';}

		if(!$member){echo '<li><a href="print.php?print_tem=normal&lx=pr&ydid='.$ydid.'" style="padding-left:10px;float:left;" target="_blank"><i class="icon-share"></i> '.$LG['function_types.142'].'</a></li>';}

		if(!$member){echo '<li><a href="print.php?print_tem=ansuo_big&lx=pr&ydid='.$ydid.'" style="padding-left:10px;float:left;" target="_blank"><i class="icon-share"></i> '.$LG['function_types.143'].'</a></li>';}
		if(!$member){echo '<li><a href="print.php?print_tem=ansuo_small&lx=pr&ydid='.$ydid.'" style="padding-left:10px;float:left;" target="_blank"><i class="icon-share"></i> '.$LG['function_types.144'].'</a></li>';}
		
		if(!$member){echo '<li><a></a></li>';}

		if(!$member){echo '<li><a href="print.php?print_tem=jp_invoce&lx=pr&ydid='.$ydid.'" style="padding-left:10px;float:left;" target="_blank"><i class="icon-share"></i> '.$LG['function_types.145'].'</a></li>';}
		if(!$member){echo '<li><a href="print.php?print_tem=jp_invoce_cn&lx=pr&ydid='.$ydid.'" style="padding-left:10px;float:left;" target="_blank"><i class="icon-share"></i> '.$LG['function_types.145_1'].'</a></li>';}
		if(!$member){echo '<li><a href="print.php?print_tem=jp_ems&lx=pr&ydid='.$ydid.'" style="padding-left:10px;float:left;" target="_blank"><i class="icon-share"></i> '.$LG['function_types.146'].'</a></li>';}
		if(!$member){echo '<li><a href="print.php?print_tem=jp_post&lx=pr&ydid='.$ydid.'" style="padding-left:10px;float:left;" target="_blank"><i class="icon-share"></i> '.$LG['function_types.147'].'</a></li>';}
		
		if(!$member){echo '<li><a></a></li>';}

		if(!$member){echo '<li><a href="print.php?print_tem=china_express&lx=pr&ydid='.$ydid.'" style="padding-left:10px;float:left;" target="_blank"><i class="icon-share"></i> '.$LG['function_types.148'].'</a></li>';}
		if(!$member){echo '<li><a href="print.php?print_tem=china_chex&lx=pr&ydid='.$ydid.'" style="padding-left:10px;float:left;" target="_blank"><i class="icon-share"></i> '.$LG['function_types.149'].'</a></li>';}
		if($ON_dhl&&1==1){echo '<li><a href="print.php?print_tem=dhl&lx=pr&ydid='.$ydid.'" style="padding-left:10px;float:left;" target="_blank"><i class="icon-share"></i> DHL</a></li>';}
*/
	}else{
		switch($zhi)
		{
			case 'picking':return $LG['function_types.137_1'];//分拣清单
			case 'listing1':return $LG['function_types.137'].'1';//分拣清单
			case 'listing2':return $LG['function_types.137'].'2';//分拣清单
			case '1':return $LG['function_types.138'];//普通面单1
			case '2':return $LG['function_types.139'];//普通面单2
			case '3':return $LG['function_types.140'];//普通面单3
			case 'normal':return $LG['function_types.142'];//标准面单
			case 'barcode':return $LG['function_types.141'];//LOGO和条码
			case 'barcode2':return $LG['function_types.141_1'];//条码2
			case 'ansuo_big':return $LG['function_types.143'];//安梭(大)
			case 'ansuo_small':return $LG['function_types.144'];//安梭(小)
			case 'jp_invoce':return $LG['function_types.145'];//日本invoce
			case 'jp_invoce_cn':return $LG['function_types.145_1'];//日本invoce
			case 'jp_ems':return $LG['function_types.146'];//日本EMS
			case 'jp_post':return $LG['function_types.147'];//日本POST
		  case 'china_express':return $LG['function_types.148'];//中国速递
			case 'china_chex':return $LG['function_types.149'];//中华快递
			case 'dhl':return 'DHL';
		  case 'hm_ems':return $LG['function_types.900'];//海门EMS
		  case 'sh_ems':return $LG['function_types.901'];//上海EMS
			case 'jiao_td':return $LG['function_types.902'];//吉澳	
			case 'fedex_td':return $LG['function_types.903'];//联邦
			case 'shunfeng_td':return $LG['function_types.904'];//顺丰
			case 'hm_youzheng':return $LG['function_types.905'];//邮政
			case 'sh_youzheng':return $LG['function_types.906'];//邮政
			case 'dpex_td':return $LG['function_types.907'];//DPEX
      case 'yuantong_td':return $LG['function_types.908'];//DPEX    
		}
	}
}


//运单导出模板
function yundan_excel_export($zhi='',$lx=0,$member=0)
{
	global $ON_gd_japan,$ON_gd_mosuda;
	require($_SERVER['DOCUMENT_ROOT'].'/public/global.php');
	if($lx==1)
	{
		echo '<option value="simple" '.($zhi=='simple'?'selected':'').'>'.$LG['function_types.150_1'].'</option>';
		if(!$member)
		{
			 //其他
			 echo '<option value="listing" '.($zhi=='listing'?'selected':'').'>'.$LG['function_types.137'].'</option>';
			 echo '<option value="cost" '.($zhi=='cost'?'selected':'').'>'.$LG['function_types.150'].'</option>';
			 echo '<option value=""></option>';
			 echo '<option value="shenbao" '.($zhi=='shenbao'?'selected':'').'>'.$LG['function_types.151'].'</option>';
			 echo '<option value="1" '.($zhi=='1'?'selected':'').'>'.$LG['function_types.152'].'</option>';
			 echo '<option value="2" '.($zhi=='2'?'selected':'').'>'.$LG['function_types.153'].'</option>';
			 echo '<option value="3" '.($zhi=='3'?'selected':'').'>'.$LG['function_types.154'].'</option>';
			 echo '<option value="4" '.($zhi=='4'?'selected':'').'>'.$LG['function_types.155'].'</option>';
			 echo '<option value="5" '.($zhi=='5'?'selected':'').'>'.$LG['function_types.156'].'</option>';
			 echo '<option value="6" '.($zhi=='6'?'selected':'').'>'.$LG['function_types.156_1'].'</option>';
			 echo '<option value="cainiao" '.($zhi=='cainiao'?'selected':'').'>'.$LG['function_types.157_1'].'</option>';
			 echo '<option value="twc" '.($zhi=='twc'?'selected':'').'>'.$LG['function_types.157'].'</option>';
			 echo '<option value="wupin" '.($zhi=='wupin'?'selected':'').'>'.$LG['function_types.158'].'</option>';
			 echo '<option value="ems" '.($zhi=='ems'?'selected':'').'>EMS</option>';
			 echo '<option value="shunfeng" '.($zhi=='shunfeng'?'selected':'').'>'.$LG['function_types.159'].'</option>';
			 echo '<option value="wuzhou" '.($zhi=='wuzhou'?'selected':'').'>'.$LG['function_types.160'].'</option>';
			 echo '<option value="yihaocang" '.($zhi=='yihaocang'?'selected':'').'>'.$LG['function_types.161'].'</option>';
			 echo '<option value="yantai" '.($zhi=='yantai'?'selected':'').'>'.$LG['function_types.162'].'</option>';
			 echo '<option value="jp_post" '.($zhi=='jp_post'?'selected':'').'>'.$LG['function_types.163'].'</option>';
			 echo '<option value="dhl" '.($zhi=='dhl'?'selected':'').'>DHL</option>';
			 
			if($ON_gd_japan)
			{
				echo '<option value="gd_japan" '.($zhi=='gd_japan'?'selected':'').'>'.$LG['function_types.164'].'</option>';
			}
			
			if($ON_gd_mosuda)
			{
				echo '<option value="gd_mosuda" '.($zhi=='gd_mosuda'?'selected':'').'>'.$LG['function_types.164_1'].'</option>';
			}
		}

	}else{
		switch($zhi)
		{
			//其他
			case 'listing':return $LG['function_types.137'];//分拣清单
			case 'cost':return $LG['function_types.150'];//运单费用
			case 'shenbao':return $LG['function_types.233'];//申报单
			case '1':return $LG['function_types.152'];//普通单1
			case '2':return $LG['function_types.153'];//普通单2
			case '3':return $LG['function_types.154'];//普通单3
			case '4':return $LG['function_types.155'];//普通单4
			case '5':return $LG['function_types.156'];//普通单5
			case '6':return $LG['function_types.156_1'];//普通单6
			case 'cainiao':return $LG['function_types.157_1'];//菜鸟打印
			case 'twc':return $LG['function_types.157'];//TWC制单表
			case 'wupin':return $LG['function_types.158'];//物品单
			case 'ems':return 'EMS';
			case 'shunfeng':return $LG['function_types.159'];//顺丰
			case 'wuzhou':return $LG['function_types.160'];//五洲清关
			case 'yihaocang':return $LG['function_types.161'];//1号仓
			case 'yantai':return $LG['function_types.162'];//烟台申报单
			case 'jp_post':return $LG['function_types.163'];//日本邮局
			case 'simple':return $LG['function_types.150_1'];//简易清单
			case 'gd_japan':return $LG['function_types.164'];//日本申报(商品资料)
			case 'gd_mosuda':return $LG['function_types.164_1'];//跨境翼(未备案商品)
			case 'dhl':return 'DHL';
			
		}
	}
}

//____________________________运单来源_______________________________
function yundan_addSource($zhi,$lx='')
{
	require($_SERVER['DOCUMENT_ROOT'].'/public/global.php');
	if($lx){

	//
            $selected=$zhi=='1'?'selected':''; echo '<option value="1" '.$selected.'>'.$LG['function_types.165'].'</option>';
            $selected=$zhi=='2'?'selected':''; echo '<option value="2" '.$selected.'>'.$LG['name.nav_19'].'</option>';
            $selected=$zhi=='3'?'selected':''; echo '<option value="3" '.$selected.'>'.$LG['name.nav_20'].'</option>';
            $selected=$zhi=='4'?'selected':''; echo '<option value="4" '.$selected.'>'.$LG['function_types.166'].'</option>';
            $selected=$zhi=='5'?'selected':''; echo '<option value="5" '.$selected.'>'.$LG['function_types.167'].'</option>';
            $selected=$zhi=='6'?'selected':''; echo '<option value="6" '.$selected.'>'.$LG['function_types.168'].'</option>';
            $selected=$zhi=='7'?'selected':''; echo '<option value="7" '.$selected.'>'.$LG['function_types.265'].'</option>';
	//
	}else{
		switch($zhi)
		{
			//
             case '':return '';
             case '1':return $LG['function_types.165'];//包裹下单
             case '2':return $LG['name.nav_19'];//直接下单
             case '3':return $LG['name.nav_20'];//批量导入
             case '4':return $LG['function_types.166'];//后台下单
             case '5':return $LG['function_types.167'];//后台导入
             case '6':return $LG['function_types.168'];//API下单
             case '7':return $LG['function_types.265'];//代购下单
			//
		}
	}	
}



//收费服务----------------------------
/*
	$lx=1：下拉
	$lx=2：前台显示
	$lx=空：正常显示
*/
function yundan_service($field,$zhi='',$lx='')
{
	require($_SERVER['DOCUMENT_ROOT'].'/public/global.php');
	
	if($field=='op_bgfee1')		{global $op_bgfee1_name,$op_bgfee1_ppt;		$name=$op_bgfee1_name;$ppt=$op_bgfee1_ppt;	$unit="{$XAmc}{$LG['yundan.Xcall_value_show_3']}";}
	elseif($field=='op_bgfee2')	{global $op_bgfee2_name,$op_bgfee2_ppt;		$name=$op_bgfee2_name;$ppt=$op_bgfee2_ppt;	$unit="{$XAmc}{$LG['yundan.Xcall_value_show_3']}";}
	elseif($field=='op_wpfee1')	{global $op_wpfee1_name,$op_wpfee1_ppt;		$name=$op_wpfee1_name;$ppt=$op_wpfee1_ppt;	$unit="{$XAmc}{$LG['yundan.Xcall_value_show_4']}";}
	elseif($field=='op_wpfee2')	{global $op_wpfee2_name,$op_wpfee2_ppt;		$name=$op_wpfee2_name;$ppt=$op_wpfee2_ppt;	$unit="{$XAmc}{$LG['yundan.Xcall_value_show_4']}";}
	elseif($field=='op_ydfee1')	{global $op_ydfee1_name,$op_ydfee1_ppt; 	$name=$op_ydfee1_name;$ppt=$op_ydfee1_ppt;	$unit="{$XAmc}";}
	elseif($field=='op_ydfee2')	{global $op_ydfee2_name,$op_ydfee2_ppt; 	$name=$op_ydfee2_name;$ppt=$op_ydfee2_ppt;	$unit="{$XAmc}";}


	if($zhi=='name'){return cadd($name);}
	if($zhi=='ppt'){return cadd($ppt);}
	
	$field_val=$field.'_val';
	$field_fee=$field.'_val_fee';
	
	if($lx==1){
		for ($i_val=0; $i_val<=10; $i_val++)
		{
			$joint=$field_val.$i_val; 			global $$joint;			$name_val=$$joint;
			$joint=$field_fee.$i_val; 			global $$joint;			$fee=$$joint;
			
			if(!$name_val){continue;}
			?>
			<label class="radio-inline">
			<input name="<?=$field?>" type="radio" value="<?=$i_val?>" onClick="calc();" <?=$zhi==$i_val?'checked':''?> /><?=cadd($name_val)?>
			 <?php if($fee>0){?><font class='gray'><?=$fee.$unit?></font><?php }?>
			</label>
			<?php 
		}

	}elseif($lx==2){
		$joint=$field_val.$zhi; 			global $$joint;			$name_val=$$joint;
		$joint=$field_fee.$zhi; 			global $$joint;			$fee=$$joint;
		if($zhi){echo "<font class='red2'>".cadd($name_val)."</font>";	return spr($fee);}
		else{echo cadd($name_val);	return;}
	}else{
		$joint=$field_val.$zhi; global $$joint;	$name_val=$$joint;
		if($zhi){return cadd($name_val);}
	}	
}




//免费服务-单选----------------------------
function op_free($zhi,$lx='')
{
	require($_SERVER['DOCUMENT_ROOT'].'/public/global.php');
	if($zhi=='name'){global $op_free_name;	return cadd($op_free_name);}
	elseif($zhi=='ppt'){global $op_free_ppt;	return cadd($op_free_ppt);}
	$field_val='op_free_val';
	
	if($lx==1){
		for ($i_val=0; $i_val<=10; $i_val++)
		{
			$joint=$field_val.$i_val; 			global $$joint;			$name_val=$$joint;
			
			if(!$name_val){continue;}
			?>
			<label class="radio-inline">
			<input name="op_free"  type="radio" value="<?=$i_val?>" <?=$zhi==$i_val?'checked':''?> /><?=cadd($name_val)?>
			</label>
			<?php 
		}

	}else{
		$joint=$field_val.$zhi; global $$joint;	$name_val=$$joint;
		if($zhi){return cadd($name_val);}
	}	
}


//免费服务-多选(数组形式)----------------------------
function op_freearr($zhi,$lx='')
{
	require($_SERVER['DOCUMENT_ROOT'].'/public/global.php');
	if($zhi=='name'){global $op_freearr_name;	return cadd($op_freearr_name);}
	elseif($zhi=='ppt'){global $op_freearr_ppt;	return cadd($op_freearr_ppt);}
	$field_val='op_freearr_val';
	
	if($lx==1){
		if(!is_array($zhi)&&$zhi){$zhi=explode(",",$zhi);}//转数组
		
		for ($i_val=0; $i_val<=10; $i_val++)
		{
			$joint=$field_val.$i_val; 			global $$joint;			$name_val=$$joint;
			
			if(!$name_val){continue;}
			?>
			<label>
			<input name="op_freearr[]"  type="checkbox" value="<?=$i_val?>" <?=$zhi&&in_array($i_val,$zhi)?'checked':''?> /><?=cadd($name_val)?>
			</label>
			<?php 
		}

	}else{
		$arr=$zhi;
		if($arr)
		{
			if(!is_array($arr)){$arr=explode(',',$arr);}//转数组
			foreach($arr as $arrkey=>$value)
			{
				$joint=$field_val.$value; 	global $$joint;		$name_val=$$joint;	
				if(!$name_val){continue;}
				echo cadd($name_val);
			}
		}
	}	
}

//绑定功能:超重超大时----------------------------
function op_overweight($zhi,$lx='')
{
	require($_SERVER['DOCUMENT_ROOT'].'/public/global.php');
	if($zhi=='name'){return $LG['function_types.253'];}
	if($lx==1){
	//
        echo '<label class="radio-inline"><input name="op_overweight" id="op_overweight" type="radio" value="1" '; if ($zhi=="1"){echo "checked";}echo '/>'.$LG['function_types.262'];
		echo '</label>';
		
        echo '<label class="radio-inline"><input name="op_overweight" id="op_overweight" type="radio" value="2" '; if ($zhi=="2"){echo "checked";}echo '/>'.$LG['function_types.263'];
		echo '</label>';

        echo '<label class="radio-inline"><input name="op_overweight" id="op_overweight" type="radio" value="3" '; if ($zhi=="3"){echo "checked";}echo '/>'.$LG['function_types.264'];
		echo '</label>';

	}else{
		switch($zhi)
		{
			//
             case '':return '';
             case '1':echo $LG['function_types.262'];return '';
             case '2':echo $LG['function_types.263'];return '';
             case '3':echo "<font class='red2'>".$LG['function_types.264']."</font>";return '';
			//
		}
	}	
}















//------------------------------------------代购相关------------------------------------------
//代购状态
function daigou_Status($zhi,$lx='')
{
	require($_SERVER['DOCUMENT_ROOT'].'/public/global.php');
	$zhi=spr($zhi,2,0);//有小数点时,必须加spr

	if($lx==1){
            $selected=$zhi=='0'?'selected':''; echo '<option value="0" '.$selected.'>'.$LG['daigou.status0'].'</option>';
            $selected=$zhi=='1'?'selected':''; echo '<option value="1" '.$selected.'>'.$LG['daigou.status1'].'</option>';
            $selected=$zhi=='2'?'selected':''; echo '<option value="2" '.$selected.'>'.$LG['daigou.status2'].'</option>';
            $selected=$zhi=='3'?'selected':''; echo '<option value="3" '.$selected.'>'.$LG['daigou.status3'].'</option>';
            $selected=$zhi=='4'?'selected':''; echo '<option value="4" '.$selected.'>'.$LG['daigou.status4'].'</option>';
            $selected=$zhi=='5'?'selected':''; echo '<option value="5" '.$selected.'>'.$LG['daigou.status5'].'</option>';
            $selected=$zhi=='6'?'selected':''; echo '<option value="6" '.$selected.'>'.$LG['daigou.status6'].'</option>';
            $selected=$zhi=='7'?'selected':''; echo '<option value="7" '.$selected.'>'.$LG['daigou.status7'].'</option>';
            $selected=$zhi=='8'?'selected':''; echo '<option value="8" '.$selected.'>'.$LG['daigou.status8'].'</option>';
            $selected=$zhi=='9'?'selected':''; echo '<option value="9" '.$selected.'>'.$LG['daigou.status9'].'</option>';
            $selected=$zhi=='9.5'?'selected':''; echo '<option value="9.5" '.$selected.'>'.$LG['daigou.status9.5'].'</option>';
            $selected=$zhi=='10'?'selected':''; echo '<option value="10" '.$selected.'>'.$LG['daigou.status10'].'</option>';
	}elseif($lx==2){
		switch($zhi)
		{
             case '0':return '<span class="label label-sm label-default">'.$LG['daigou.status0'].'</span>';
             case '1':return '<span class="label label-sm label-danger">'.$LG['daigou.status1'].'</span>';
             case '2':return '<span class="label label-sm label-warning">'.$LG['daigou.status2'].'</span>';
             case '3':return '<span class="label label-sm label-success">'.$LG['daigou.status3'].'</span>';
             case '4':return '<span class="label label-sm label-warning">'.$LG['daigou.status4'].'</span>';
             case '5':return '<span class="label label-sm label-success">'.$LG['daigou.status5'].'</span>';
             case '6':return '<span class="label label-sm label-default">'.$LG['daigou.status6'].'</span>';
             case '7':return '<span class="label label-sm label-default">'.$LG['daigou.status7'].'</span>';
             case '8':return '<span class="label label-sm label-default">'.$LG['daigou.status8'].'</span>';
             case '9':return '<span class="label label-sm label-default">'.$LG['daigou.status9'].'</span>';
             case '9.5':return '<span class="label label-sm label-default">'.$LG['daigou.status9.5'].'</span>';
             case '10':return '<span class="label label-sm label-default">'.$LG['daigou.status10'].'</span>';
		}
	}else{
		switch($zhi)
		{
             case '0':return $LG['daigou.status0'];//待审核
             case '1':return $LG['daigou.status1'];//未通过审核
			 
             case '2':return $LG['daigou.status2'];//待支付
             case '3':return $LG['daigou.status3'];//已支付(采购中)
			
             case '4':return $LG['daigou.status4'];//待补款
             case '5':return $LG['daigou.status5'];//已补款(待采购)
			 
             case '6':return $LG['daigou.status6'];//已采购(等待商家发货)
             case '7':return $LG['daigou.status7'];//商家已发货(等待入库)
             case '8':return $LG['daigou.status8'];//部分入库(等待全部入库)
             case '9':return $LG['daigou.status9'];//已入库
             case '9.5':return $LG['daigou.status9.5'];//已全发货 
             case '10':return $LG['daigou.status10'];//无效(记录)
		}
	}
	
}





//代购-处理状态
/*
	$lx=1 下拉
	$lx=2 商品中显示:$zhi空，需要$rs,自动获取
	$lx=3 代购单中显示(只显示是否有未处理的申请[后台看]):$zhi空，需要$rs,自动获取
	$lx=4 代购单中显示(只显示是否有已处理的申请[前台看]):$zhi空，需要$rs,自动获取
*/
function daigou_memberStatus($zhi='',$lx='',$rs='')
{
	require($_SERVER['DOCUMENT_ROOT'].'/public/global.php');
	
	if($lx==1){
            $selected=$zhi=='1'?'selected':''; echo '<option value="1" '.$selected.'>'.$LG['daigou.memberStatus1'].'</option>';
            $selected=$zhi=='2'?'selected':''; echo '<option value="2" '.$selected.'>'.$LG['daigou.memberStatus2'].'</option>';
            $selected=$zhi=='3'?'selected':''; echo '<option value="3" '.$selected.'>'.$LG['daigou.memberStatus3'].'</option>';
            $selected=$zhi=='4'?'selected':''; echo '<option value="4" '.$selected.'>'.$LG['daigou.memberStatus4'].'</option>';
	}elseif($lx==2){
		
		//已处理时，则获取上次的处理申请
		$zhi=$rs['memberStatus'];if(!$rs['memberStatus']&&$rs['memberLastStatus']&&$rs['manageStatus']>1){$zhi=$rs['memberLastStatus'];}
		
		
		
		if($rs['memberStatusTime']){$data_content.=$LG['function_types.186'].DateYmd($rs['memberStatusTime'],1);}
		if($rs['manageStatus'])
		{
			$data_content.='<br>'.$LG['function_types.59'].DateYmd($rs['manageStatusTime'],1);
			//显示处理结果
			$result='【'.daigou_manageStatus($rs['manageStatus']).'】';
			//显示颜色
			switch($rs['manageStatus'])
			{
				 case '1':$color= 'warning';break;
				 case '2':$color= 'default';break;
				 case '3':$color= 'default';break;
				 case '4':$color= 'default';break;
			}
		}
		if($rs['memberStatusRequ']){$data_content.='<br>'.$LG['function_types.60'].striptags($rs['memberStatusRequ']);
		}
		if(!$color){$color= 'warning';}
		//申请状态
		switch($zhi)
		{
            
			 case '1':$ppt= '<span class="label label-sm label-'.$color.'">'.$LG['daigou.memberStatus1'].$result.'</span>';break;
             case '2':$ppt= '<span class="label label-sm label-'.$color.'">'.$LG['daigou.memberStatus2'].$result.'</span>';break;
             case '3':$ppt= '<span class="label label-sm label-'.$color.'">'.$LG['daigou.memberStatus3'].$result.'</span>';break;
             case '4':$ppt= '<span class="label label-sm label-'.$color.'">'.$LG['daigou.memberStatus4'].$result.'</span>';break;
		}
		
		return '<font class=" popovers" data-trigger="hover" data-placement="top"  data-content="'.$data_content.'">'.$ppt.'</font>';
		
	}elseif($lx==3){
		$Num=NumData('daigou_goods',"dgid='{$rs['dgid']}' and memberStatus>0 and manageStatus<=1");
		if($Num){return '<span class="label label-sm label-warning">'.$LG['daigou.manageStatus0'].'('.$Num.')</span>';}
	}elseif($lx==4){
		$Num=NumData('daigou_goods',"dgid='{$rs['dgid']}' and (memberStatus>0 or memberLastStatus>0) and manageStatus>1");
		if($Num){return '<span class="label label-sm label-warning">'.$LG['daigou.manageStatus3'].'('.$Num.')</span>';}
	}else{
		switch($zhi)
		{
             case '0':return $LG['daigou.memberStatus0'];//取消申请
             case '1':return $LG['daigou.memberStatus1'];//申请查货
             case '2':return $LG['daigou.memberStatus2'];//申请换货
             case '3':return $LG['daigou.memberStatus3'];//申请增购数量
             case '4':return $LG['daigou.memberStatus4'];//申请退货退款
		}
	}
	
}




//代购-后台申请处理
function daigou_manageStatus($zhi,$lx='')
{
	require($_SERVER['DOCUMENT_ROOT'].'/public/global.php');
	if($lx==1){
	  //下拉方式:只用于批量修改,因此不能显示"已处理"
	  $selected=$zhi=='1'?'selected':''; echo '<option value="1" '.$selected.'>'.$LG['daigou.manageStatus1'].'</option>';
	  $selected=$zhi=='2'?'selected':''; echo '<option value="2" '.$selected.'>'.$LG['daigou.manageStatus2'].'</option>';
	  //$selected=$zhi=='3'?'selected':''; echo '<option value="3" '.$selected.'>'.$LG['daigou.manageStatus3'].'</option>';
	  $selected=$zhi=='4'?'selected':''; echo '<option value="4" '.$selected.'>'.$LG['daigou.manageStatus4'].'</option>';
	}elseif($lx==3){
	  //单选方式
	 ?> 
	  <label class="radio-inline tooltips" data-container="body" data-placement="top" data-original-title="如处理时间处理长，请先设为此项，防止会员中途修改要求">
      <input type="radio" name="manageStatus" value="1" <?=$zhi=='1'?'checked':''?> onclick="hide()"><?=$LG['daigou.manageStatus1']?>
      </label>
      
	  <label class="radio-inline tooltips" data-container="body" data-placement="top" data-original-title="选择此项时,部分申请才会显示处理表单">
      <input type="radio" name="manageStatus" value="3" <?=$zhi=='3'?'checked':''?> onclick="hide('1')"><?=$LG['daigou.manageStatus3']?>
      </label>
      
	  <label class="radio-inline">
      <input type="radio" name="manageStatus" value="2" <?=$zhi=='2'?'checked':''?> onclick="hide()"><?=$LG['daigou.manageStatus2']?>
      </label>
      
	  <label class="radio-inline">
      <input type="radio" name="manageStatus" value="4" <?=$zhi=='4'?'checked':''?> onclick="hide()"><?=$LG['daigou.manageStatus4']?>
      </label>
	 <?php
	}elseif($lx==2){
		switch($zhi)
		{
             case '1':return '<span class="label label-sm label-default">'.$LG['daigou.manageStatus1'].'</span>';
             case '2':return '<span class="label label-sm label-default">'.$LG['daigou.manageStatus2'].'</span>';
             case '3':return '<span class="label label-sm label-default">'.$LG['daigou.manageStatus3'].'</span>';
             case '4':return '<span class="label label-sm label-danger">'.$LG['daigou.manageStatus4'].'</span>';
		}
	}else{
		switch($zhi)
		{
             case '1':return $LG['daigou.manageStatus1'];//处理中
             case '2':return $LG['daigou.manageStatus2'];//申请无效
             case '3':return $LG['daigou.manageStatus3'];//已处理
             case '4':return $LG['daigou.manageStatus4'];//拒绝处理
		}
	}
	
}



//代购-供应商：未做
function daigou_sellerStatus($zhi,$lx='')
{
	require($_SERVER['DOCUMENT_ROOT'].'/public/global.php');
	if($lx==1){
            $selected=$zhi=='1'?'selected':''; echo '<option value="1" '.$selected.'>'.$LG['daigou.sellerStatus1'].'</option>';
            $selected=$zhi=='2'?'selected':''; echo '<option value="2" '.$selected.'>'.$LG['daigou.sellerStatus2'].'</option>';
            $selected=$zhi=='3'?'selected':''; echo '<option value="2" '.$selected.'>'.$LG['daigou.sellerStatus3'].'</option>';
	}elseif($lx==2){
		switch($zhi)
		{
             case '1':return '<span class="label label-sm label-warning">'.$LG['daigou.sellerStatus1'].'</span>';
             case '2':return '<span class="label label-sm label-default">'.$LG['daigou.sellerStatus2'].'</span>';
             case '3':return '<span class="label label-sm label-default">'.$LG['daigou.sellerStatus3'].'</span>';
		}
	}else{
		switch($zhi)
		{
             case '1':return $LG['daigou.sellerStatus1'];//待发货
             case '2':return $LG['daigou.sellerStatus2'];//已发货
             case '3':return $LG['daigou.sellerStatus3'];//已入库
		}
	}
	
}

//代购-断货退款
function daigou_lackStatus($zhi,$lx='',$numberRet='')
{
	require($_SERVER['DOCUMENT_ROOT'].'/public/global.php');

	if($lx==1){
            $selected=$zhi=='1'?'selected':''; echo '<option value="1" '.$selected.'>'.$LG['daigou.lackStatus1'].'</option>';
            $selected=$zhi=='2'?'selected':''; echo '<option value="2" '.$selected.'>'.$LG['daigou.lackStatus2'].'</option>';
	}elseif($lx==2){
		if($numberRet){$numberRet="({$numberRet})";}
		switch($zhi)
		{
			 case '1':return '<span class="label label-sm label-default">'.$LG['daigou.lackStatus1'].$numberRet.'</span>';
             case '2':return '<span class="label label-sm label-danger">'.$LG['daigou.lackStatus2'].$numberRet.'</span>';
		}
	}else{
		switch($zhi)
		{
			 case '1':return $LG['daigou.lackStatus1'];//部分断货退款
             case '2':return $LG['daigou.lackStatus2'];//全部断货退款
		}
	}
	
}


//新留言，新回复
/*
	$callFrom='member' 显示回复会员
	$callFrom='seller' 显示回复供应商
	$callFrom='manage' 显示会员新留言、供应商新留言
*/
function daigou_ContentNew($zhi='',$lx='',$rs='')
{
	require($_SERVER['DOCUMENT_ROOT'].'/public/global.php');
	global $callFrom;
	if($lx==2)
	{
		if($rs['memberContentNew']&&$callFrom=='manage'){return '<span class="label label-sm label-warning tooltips" data-container="body" data-placement="top" data-original-title="'.DateYmd($rs['memberContentTime'],1).'" >'.$LG['function_types.197'].'</span>';}
	
		if($rs['memberContentReplyNew']&&$callFrom=='member'){return '<span class="label label-sm label-warning tooltips" data-container="body" data-placement="top" data-original-title="'.DateYmd($rs['memberContentReplyTime'],1).'" >'.$LG['function_types.198'].'</span>';}
	
		if($rs['sellerContentNew']&&$callFrom=='manage'){return '<span class="label label-sm label-warning tooltips" data-container="body" data-placement="top" data-original-title="'.DateYmd($rs['sellerContentTime'],1).'">'.$LG['function_types.199'].'</span>';}
	
		if($rs['sellerContentReplyNew']&&$callFrom=='seller'){return '<span class="label label-sm label-warning tooltips" data-container="body" data-placement="top" data-original-title="'.DateYmd($rs['sellerContentReplyTime'],1).'">'.$LG['function_types.200'].'</span>';}
	}else{
		if($zhi==1&&$callFrom=='manage'){return $LG['function_types.197'];}//会员新留言}
		if($zhi==2&&$callFrom=='member'){return $LG['function_types.198'];}//新回复会员}
		if($zhi==3&&$callFrom=='manage'){return $LG['function_types.199'];}//商家新留言}
		if($zhi==4&&$callFrom=='seller'){return $LG['function_types.200'];}//新回复商家}
	}
}



//代购货源
function daigou_source($zhi,$lx='')
{
	require($_SERVER['DOCUMENT_ROOT'].'/public/global.php');
	if($lx){

	  $selected=$zhi=='1'?'selected':''; echo '<option value="1" '.$selected.'>'.$LG['function_types.201'].'</option>';
	  $selected=$zhi=='2'?'selected':''; echo '<option value="2" '.$selected.'>'.$LG['function_types.202'].'</option>';
	}else{
		switch($zhi)
		{
             case '1':return $LG['function_types.201'];//海外线上网站
             case '2':return $LG['function_types.202'];//海外线下专柜
		}
	}	
}


//代购来源
function daigou_addSource($zhi,$lx='')
{
	require($_SERVER['DOCUMENT_ROOT'].'/public/global.php');
	if($lx){

	  $selected=$zhi=='1'?'selected':''; echo '<option value="1" '.$selected.'>'.$LG['function_types.203'].'</option>';
	  $selected=$zhi=='2'?'selected':''; echo '<option value="2" '.$selected.'>'.$LG['function_types.204'].'</option>';
	}else{
		switch($zhi)
		{
			 case '1':return $LG['function_types.203'];//会员下单
			 case '2':return $LG['function_types.204'];//仓库下单
		}
	}	
}




//代购导出模板
function daigou_excel_export($zhi='',$lx=0)
{
	global $callFrom;
	require($_SERVER['DOCUMENT_ROOT'].'/public/global.php');
	if($lx==1)
	{
		$selected=$zhi=='listing'?'selected':''; echo '<option value="listing" '.$selected.'>'.$LG['daigou.excel_export_listing'].'</option>';
		if($callFrom=='manage')
		{
			$selected=$zhi=='count'?'selected':''; echo '<option value="count" '.$selected.'>'.$LG['daigou.excel_export_count'].'</option>';
		}
		
	}else{
		switch($zhi)
		{
			case 'listing':return $LG['daigou.excel_export_listing'];//采购清单
			case 'count':return $LG['daigou.excel_export_count'];//利润统计
		}
	}
}






//____________________________支持币种，支持的币种，输出币种，币种下拉__________________________________
/*
	$lx=1 显示下拉:全部
	$lx=2 显示下拉:网站支持的币种(全部)
	$lx=3 显示下拉:代购支持的币种
	$lx=4 显示下拉:会员支持的币种
	
	$opt 显示"会员账户币种"
*/


function openCurrency($zhi,$lx='',$opt='')
{
	$zhi=ToArr($zhi);
	if($lx==2){global $openCurrency;$openCurrency=ToArr($openCurrency);}
	elseif($lx==3){global $dg_openCurrency;$openCurrency=ToArr($dg_openCurrency);}
	elseif($lx==4){global $me_openCurrency;$openCurrency=ToArr($me_openCurrency);}
	
	//常用
	if(arrcount($openCurrency)>1){echo '<option value=""></option>';}//只有一个币种时，默认选择
	if($opt){echo '<option value="1">会员账户币种</option>';}//充值页,扣费页用到
	Currency_option('CNY',$zhi,$lx,$openCurrency);
	Currency_option('USD',$zhi,$lx,$openCurrency);
	Currency_option('JPY',$zhi,$lx,$openCurrency);
	Currency_option('KRW',$zhi,$lx,$openCurrency);
	Currency_option('HKD',$zhi,$lx,$openCurrency);
	Currency_option('GBP',$zhi,$lx,$openCurrency);

	Currency_option('AED',$zhi,$lx,$openCurrency);
	Currency_option('ALL',$zhi,$lx,$openCurrency);
	Currency_option('ATS',$zhi,$lx,$openCurrency);
	Currency_option('AUD',$zhi,$lx,$openCurrency);
	Currency_option('BDT',$zhi,$lx,$openCurrency);
	Currency_option('BEF',$zhi,$lx,$openCurrency);
	Currency_option('BGL',$zhi,$lx,$openCurrency);
	Currency_option('BHD',$zhi,$lx,$openCurrency);
	Currency_option('BIF',$zhi,$lx,$openCurrency);
	Currency_option('BRL',$zhi,$lx,$openCurrency);
	Currency_option('BUK',$zhi,$lx,$openCurrency);
	Currency_option('BWP',$zhi,$lx,$openCurrency);
	Currency_option('CAD',$zhi,$lx,$openCurrency);
	Currency_option('CFC',$zhi,$lx,$openCurrency);
	Currency_option('CHF',$zhi,$lx,$openCurrency);
	Currency_option('CPS',$zhi,$lx,$openCurrency);
	Currency_option('CSF',$zhi,$lx,$openCurrency);
	Currency_option('CSK',$zhi,$lx,$openCurrency);
	Currency_option('CUR',$zhi,$lx,$openCurrency);
	Currency_option('CUS',$zhi,$lx,$openCurrency);
	Currency_option('CYP',$zhi,$lx,$openCurrency);
	Currency_option('DEM',$zhi,$lx,$openCurrency);
	Currency_option('DKK',$zhi,$lx,$openCurrency);
	Currency_option('DZD',$zhi,$lx,$openCurrency);
	Currency_option('EGP',$zhi,$lx,$openCurrency);
	Currency_option('ESP',$zhi,$lx,$openCurrency);
	Currency_option('ETB',$zhi,$lx,$openCurrency);
	Currency_option('EUR',$zhi,$lx,$openCurrency);
	Currency_option('FIM',$zhi,$lx,$openCurrency);
	Currency_option('FRF',$zhi,$lx,$openCurrency);
	Currency_option('GHC',$zhi,$lx,$openCurrency);
	Currency_option('GNS',$zhi,$lx,$openCurrency);
	Currency_option('GRD',$zhi,$lx,$openCurrency);
	Currency_option('HUF',$zhi,$lx,$openCurrency);
	Currency_option('IDR',$zhi,$lx,$openCurrency);
	Currency_option('INR',$zhi,$lx,$openCurrency);
	Currency_option('IQD',$zhi,$lx,$openCurrency);
	Currency_option('IRR',$zhi,$lx,$openCurrency);
	Currency_option('ITL',$zhi,$lx,$openCurrency);
	Currency_option('JOD',$zhi,$lx,$openCurrency);
	Currency_option('KES',$zhi,$lx,$openCurrency);
	Currency_option('KPW',$zhi,$lx,$openCurrency);
	Currency_option('KWD',$zhi,$lx,$openCurrency);
	Currency_option('LKR',$zhi,$lx,$openCurrency);
	Currency_option('LYD',$zhi,$lx,$openCurrency);
	Currency_option('MAD',$zhi,$lx,$openCurrency);
	Currency_option('MGF',$zhi,$lx,$openCurrency);
	Currency_option('MLF',$zhi,$lx,$openCurrency);
	Currency_option('MNT',$zhi,$lx,$openCurrency);
	Currency_option('MOP',$zhi,$lx,$openCurrency);
	Currency_option('MUR',$zhi,$lx,$openCurrency);
	Currency_option('MXP',$zhi,$lx,$openCurrency);
	Currency_option('MYR',$zhi,$lx,$openCurrency);
	Currency_option('NGN',$zhi,$lx,$openCurrency);
	Currency_option('NLG',$zhi,$lx,$openCurrency);
	Currency_option('NOK',$zhi,$lx,$openCurrency);
	Currency_option('NPR',$zhi,$lx,$openCurrency);
	Currency_option('NZD',$zhi,$lx,$openCurrency);
	Currency_option('PGK',$zhi,$lx,$openCurrency);
	Currency_option('PHP',$zhi,$lx,$openCurrency);
	Currency_option('PKR',$zhi,$lx,$openCurrency);
	Currency_option('PLZ',$zhi,$lx,$openCurrency);
	Currency_option('ROL',$zhi,$lx,$openCurrency);
	Currency_option('RUB',$zhi,$lx,$openCurrency);
	Currency_option('RWF',$zhi,$lx,$openCurrency);
	Currency_option('SCR',$zhi,$lx,$openCurrency);
	Currency_option('SDP',$zhi,$lx,$openCurrency);
	Currency_option('SEK',$zhi,$lx,$openCurrency);
	Currency_option('SGD',$zhi,$lx,$openCurrency);
	Currency_option('SLL',$zhi,$lx,$openCurrency);
	Currency_option('SUR',$zhi,$lx,$openCurrency);
	Currency_option('SYP',$zhi,$lx,$openCurrency);
	Currency_option('THB',$zhi,$lx,$openCurrency);
	Currency_option('TND',$zhi,$lx,$openCurrency);
	Currency_option('TRL',$zhi,$lx,$openCurrency);
	Currency_option('TWD',$zhi,$lx,$openCurrency);
	Currency_option('TZS',$zhi,$lx,$openCurrency);
	Currency_option('UDM',$zhi,$lx,$openCurrency);
	Currency_option('VND',$zhi,$lx,$openCurrency);
	Currency_option('XAG',$zhi,$lx,$openCurrency);
	Currency_option('XAU',$zhi,$lx,$openCurrency);
	Currency_option('XDR',$zhi,$lx,$openCurrency);
	Currency_option('XEU',$zhi,$lx,$openCurrency);
	Currency_option('XOF',$zhi,$lx,$openCurrency);
	Currency_option('YER',$zhi,$lx,$openCurrency);
	Currency_option('ZMK',$zhi,$lx,$openCurrency);
}


//openCurrency的下拉选项
function Currency_option($code,$zhi,$lx,$openCurrency)
{
	global $LG;
	if($lx==1||!$openCurrency||have($openCurrency,$code,1)){$selected=$zhi&&in_array($code,$zhi)?'selected':''; echo '<option value="'.$code.'" '.$selected.'>'.$code.'</option>';}
}
?>