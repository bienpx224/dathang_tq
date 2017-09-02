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
require_once($_SERVER['DOCUMENT_ROOT'].'/public/wl_expresses.php');//物流公司编号






//删除运单时退费和退积分:优惠券不能退
function yundan_refund($rs)
{
	require($_SERVER['DOCUMENT_ROOT'].'/public/global.php');

	/*提示:为了统计数据,就算删除运单了,也要保存所属运单ID*/
	
	//退费
	$kf=FeData('money_kfbak',"count(*) as total,sum(`fromMoney`) as fromMoney","fromtable='yundan' and fromid='{$rs['ydid']}'");
	$cz=FeData('money_czbak',"count(*) as total,sum(`fromMoney`) as fromMoney","fromtable='yundan' and fromid='{$rs['ydid']}'");
	$money=spr($kf['fromMoney']-$cz['fromMoney']);//已扣费-已退费=需要退的费
	if($money>0){MoneyCZ($rs['userid'],'yundan',$rs['ydid'],$money,'','删除运单退回所扣费用','',51);}
	
	
	
	//退积分
	$cz=FeData('integral_czbak',"count(*) as total,sum(`integral`) as integral","fromtable='yundan' and fromid='{$rs['ydid']}'");
	$kf=FeData('integral_kfbak',"count(*) as total,sum(`integral`) as integral","fromtable='yundan' and fromid='{$rs['ydid']}'");
	$integral=spr($kf['integral']-$cz['integral']);//已扣费-已退费=需要退的费
		if($integral>0){
		integralCZ($rs['userid'],'yundan',$rs['ydid'],$integral,'','删除运单退回所扣积分',1);
	}elseif($integral<0){
		integralKF($rs['userid'],'yundan',$rs['ydid'],$integral,'','删除运单扣除所送积分',1);
	}

}



//验证收件人资料,地址验证
function CheckReceive($typ='address')
{
	require($_SERVER['DOCUMENT_ROOT'].'/public/global.php');
	global $_POST;
	if($typ=='yundan')
	{
		$name=trim($_POST['s_name']);
		$country=trim($_POST['country']);
		$mobile=trim($_POST['s_mobile']);
		$zip=trim($_POST['s_zip']);
		$add_dizhi=trim(($_POST['s_add_shengfen'].$_POST['s_add_chengshi'].$_POST['s_add_quzhen'].$_POST['s_add_dizhi']));
		
		$shenfenhaoma=trim($_POST['s_shenfenhaoma']);
		
		$shenfenimg_z=trim($_POST['s_shenfenimg_z']);
		$shenfenimg_z_add=trim($_POST['s_shenfenimg_z_add']);
		$old_shenfenimg_z=trim($_POST['old_s_shenfenimg_z']);
		
		$shenfenimg_b=trim($_POST['s_shenfenimg_b']);
		$shenfenimg_b_add=trim($_POST['s_shenfenimg_b_add']);
		$old_shenfenimg_b=trim($_POST['old_s_shenfenimg_b']);
	}elseif($typ=='address'){
		$name=trim($_POST['truename']);
		$country=trim($_POST['country']);
		$mobile=trim($_POST['mobile']);
		$zip=trim($_POST['zip']);
		$add_dizhi=trim(($_POST['add_shengfen'].$_POST['add_chengshi'].$_POST['add_quzhen'].$_POST['add_dizhi']));
		
		$shenfenhaoma=trim($_POST['shenfenhaoma']);
		
		$shenfenimg_z=trim($_POST['shenfenimg_z']);
		$shenfenimg_z_add=trim($_POST['shenfenimg_z_add']);
		$old_shenfenimg_z=trim($_POST['old_shenfenimg_z']);

		$shenfenimg_b=trim($_POST['shenfenimg_b']);
		$shenfenimg_b_add=trim($_POST['shenfenimg_b_add']);
		$old_shenfenimg_b=trim($_POST['old_shenfenimg_b']);
	}
	
	$ppt='';
	
	//是否要验证身份证号码
	if($_POST['warehouse']&&$_POST['channel'])
	{
		//运单表单时
		$cert=channelPar($_POST['warehouse'],$_POST['channel'],'shenfenzheng');
	}else{
		//地址簿表单时
		if($shenfenhaoma){$cert=1;}
	}
	
	/*
		香港台湾澳门 地址允许填写英文
	*/
	
	//大陆
	if($country=='86'||$country=='142')
	{
		if($name&&!CheckChinese($name)){$ppt.=$LG['function.120'];}
		if($mobile&&!CheckTelephone($mobile)){$ppt.=$LG['function.121'];}
		if($zip&&!CheckDigital($zip,6)){$ppt.=$LG['function.122'];}
		if($add_dizhi&&!CheckChinese($add_dizhi)){$ppt.=$LG['function.123'];}
		if($cert&&strtoupper($shenfenhaoma)!='LATE'&&!CheckIDCard($shenfenhaoma)){$ppt.=$LG['function.124'];}
	}
	
	//香港
	elseif($country=='852'||$country=='110')
	{
		if($name&&!CheckChinese($name)){$ppt.=$LG['function.120'];}
		if($mobile&&!CheckTelephone_hk($mobile)){$ppt.=$LG['function.125'];}
		//if(!CheckDigital($zip,6)){$ppt.=$LG['function.126'];}//无邮编
		//if($add_dizhi&&!CheckChinese($add_dizhi)){$ppt.=$LG['function.123'];}
		if($cert&&strtoupper($shenfenhaoma)!='LATE'&&!CheckIDCard_hk($shenfenhaoma)){$ppt.=$LG['function.127'];}
	}
	
	//澳门
	elseif($country=='853'||$country=='121')
	{
		if($name&&!CheckChinese($name)){$ppt.=$LG['function.120'];}
		if($mobile&&!CheckTelephone_m($mobile)){$ppt.=$LG['function.128'];}
		//if(!CheckDigital($zip,6)){$ppt.=$LG['function.126'];}//无邮编
		//if($add_dizhi&&!CheckChinese($add_dizhi)){$ppt.=$LG['function.123'];}
		if($cert&&strtoupper($shenfenhaoma)!='LATE'&&!CheckIDCard_m($shenfenhaoma)){$ppt.=$LG['function.129'];}
	}
	
	//台湾
	elseif($country=='886'||$country=='143')
	{
		if($name&&!CheckChinese($name)){$ppt.=$LG['function.120'];}
		if($mobile&&!CheckTelephone_t($mobile)){$ppt.=$LG['function.130'];}
		if($zip&&!CheckDigital($zip,3)&&!CheckDigital($zip,5)){$ppt.=$LG['function.131'];}//邮编格式:3位或5位数字
		//if($add_dizhi&&!CheckChinese($add_dizhi)){$ppt.=$LG['function.123'];}
		if($cert&&strtoupper($shenfenhaoma)!='LATE'&&!CheckIDCard_t($shenfenhaoma)){$ppt.=$LG['function.132'];}
	}
	
	//是否要上传证件
	if($cert&&strtoupper($shenfenhaoma)!='LATE')
	{
		if(!$shenfenimg_z&&!$shenfenimg_z_add&&!$old_shenfenimg_z){$ppt.=$LG['function.156'];}
		if(!$shenfenimg_b&&!$shenfenimg_b_add&&!$old_shenfenimg_b){$ppt.=$LG['function.157'];}
	}
	
	return trim($ppt);
}




//状态名称
function status_name($status,$statustime='',$statusauto='')
{
	require($_SERVER['DOCUMENT_ROOT'].'/public/global.php');
	$status=str_ireplace('-','0',$status);
	$status_name='status_'.$status;
	global $Xuserid,$$status_name;
	$show=$$status_name;

	if($statustime||$statusauto)
	{
		if($Xuserid){
			if($statusauto){$auto=$LG['function.146'];}else{$auto=$LG['function.147'];}
			$ppt_auto='<br>'.$LG['function.149'].$auto;
		}
		
		$show='<font class=" popovers" data-trigger="hover" data-placement="top"  data-content="'.$LG['function.148'].DateYmd($statustime).$ppt_auto.'">'.$show.'</font>';
	}
	return $show;
}


//派送快递公司
function yundan_gnkd($zhi)
{
	global $expresses;
	foreach($expresses as $key=>$value)
	{
		$selected=$zhi==$key?'selected':''; echo '<option value="'.$key.'" '.$selected.'>'.$key.'-'.$value.'</option>';
	}
}


//筛选菜单
function yundan_Screening($field,$zhi='',$name)
{
	global $_GET,$callFrom,$Muserid,$search;
	$search_now=$search;
	if($field=='status'){$search_now='';}
	
	$CN_where="";
	if($callFrom=='member'){$CN_userid=$Muserid;}else{$CN_userid='';}

	$scr_act='default';
	if($field==par($_GET['field'])&&$zhi==par($_GET['zhi'])){$scr_act='info';}

	echo  ' <button type="button" class="btn btn-'.$scr_act.'" onClick="location.href=\'?'.$search_now.'&so=1&field='.$field.'&zhi='.$zhi.'\';" ';if($field=='status'){echo 'style="margin-bottom:10px;">';}
	
	echo $name;//显示名称
	echo CountNum('yundan',$field,$zhi,$CN_where,$CN_userid,'default');//统计数量
	echo '</button> ';//结束按钮
	

}


//运单发件人/收件人地址
/*
	addressShow($addid,$typ=1) //地址簿：地址读取,显示地址
*/
function yundan_add_all($rs,$lx='s')
{
	$add='';
	if($lx=='s')
	{
		if(trim($rs['s_add_shengfen'])){$add.=cadd($rs['s_add_shengfen']).' ';}
		if(trim($rs['s_add_chengshi'])){$add.=cadd($rs['s_add_chengshi']).' ';}
		if(trim($rs['s_add_quzhen'])){$add.=cadd($rs['s_add_quzhen']).' ';}
		if(trim($rs['s_add_dizhi'])){$add.=cadd($rs['s_add_dizhi']);}
	}else{
		if(trim($rs['f_add_shengfen'])){$add.=cadd($rs['f_add_shengfen']).' ';}
		if(trim($rs['f_add_chengshi'])){$add.=cadd($rs['f_add_chengshi']).' ';}
		if(trim($rs['f_add_quzhen'])){$add.=cadd($rs['f_add_quzhen']).' ';}
		if(trim($rs['f_add_dizhi'])){$add.=cadd($rs['f_add_dizhi']);}
	}
	return $add;
}



//生成物品描述:多字段形式
function goodsdescribe($wupin)
{
	if($wupin)
	{
		$j=0;
		$mvf_record=explode('|||',$wupin);
		$mvf_count=count($mvf_record);
		for($i=0;$i<$mvf_count;$i++)
		{
			$j=$i+1;
			$mvf_field=explode(':::',$mvf_record[$i]);
	
			$line.=$mvf_field[2].' '.$mvf_field[1].' X'.$mvf_field[6].'；';// 品牌 品名 X10(数量)；
		}
		return $line;
	}
}

//生成物品描述:数据库形式
function goodsdescribe2($fromtable,$fromid)
{
	require($_SERVER['DOCUMENT_ROOT'].'/public/global.php');
	if($fromtable&&$fromid)
	{
		$line='';
		$query_wp="select * from wupin where fromtable='{$fromtable}' and fromid='{$fromid}' order by wupin_name desc";
		$sql_wp=$xingao->query($query_wp);
		while($wp=$sql_wp->fetch_array())
		{
			$line.=cadd($wp['wupin_brand']).' '.cadd($wp['wupin_name']).' X'.cadd($wp['wupin_number']).'；';// 品牌 品名 X10(数量)；
		}
		return $line;
	}
}


//计算物品总价(外站币种)
function wupin_total_price($wupin)
{
	$total_price=0;
	if($wupin)
	{
		$j=0;
		$mvf_record=explode('|||',$wupin);
		$mvf_count=count($mvf_record);
		for($i=0;$i<$mvf_count;$i++)
		{
			$j=$i+1;
			$mvf_field=explode(':::',$mvf_record[$i]);
			$total_price+=$mvf_field[4]*$mvf_field[6];//单价*数量；
		}
	}
	return $total_price;
}

//验证申请价值(外站币种)
function declarevalue($value,$wupin)
{
	$wupin_total_price=spr(wupin_total_price($wupin),3);
	$value=spr($value);if($value>$wupin_total_price){$value=$wupin_total_price;}
	return $value;
}

//验证填写的保价区间(外站币种)
function insureamount($whid,$channel,$value,$wupin_value,$declarevalue)
{
	$baoxian_4='warehouse_'.$whid.'_baoxian_4_'.$channel;
	$baoxian_5='warehouse_'.$whid.'_baoxian_5_'.$channel;
	
	global $$baoxian_4,$$baoxian_5;
	
	$baoxian_4=$$baoxian_4;
	$baoxian_5=$$baoxian_5;

	$value=spr($value,3);
	if($value>$wupin_value){$value=$wupin_value;}
	if($value>$declarevalue){$value=$declarevalue;}

	if($value<$baoxian_4){$value=0;}
	elseif($value>$baoxian_5){$value=$baoxian_5;}
	
	return spr($value);
}


//保险处理
/*
$lx=1获取全部
$lx=0只获取保价费
*/
function insurance($whid,$channel,$insureamount,$lx=0)
{
	$baoxian_1='warehouse_'.$whid.'_baoxian_1_'.$channel;
	$baoxian_2='warehouse_'.$whid.'_baoxian_2_'.$channel;
	$baoxian_3='warehouse_'.$whid.'_baoxian_3_'.$channel;
	$baoxian_4='warehouse_'.$whid.'_baoxian_4_'.$channel;
	$baoxian_5='warehouse_'.$whid.'_baoxian_5_'.$channel;
	$insuranceFormula='warehouse_'.$whid.'_insuranceFormula_'.$channel;
	$insuranceFormulaType='warehouse_'.$whid.'_insuranceFormulaType_'.$channel;
	
	require($_SERVER['DOCUMENT_ROOT'].'/public/global.php');
	global $$baoxian_1,$$baoxian_2,$$baoxian_3,$$baoxian_4,$$baoxian_5,$$insuranceFormula,$$insuranceFormulaType;
	
	$baoxian_1=$$baoxian_1;
	$baoxian_2=$$baoxian_2;
	$baoxian_3=$$baoxian_3;
	$baoxian_4=$$baoxian_4;
	$baoxian_5=$$baoxian_5;
	$insuranceFormula=$$insuranceFormula;
	$insuranceFormulaType=$$insuranceFormulaType;
	
	$insurevalue=0;$insureamount=spr($insureamount,3);
	
	if($insureamount<$baoxian_4){$insureamount=$baoxian_4;}//小于保价区间
	if($insureamount>$baoxian_5){$insureamount=$baoxian_5;}//大于保价区间
	
	if($insuranceFormulaType==0)
	{
		//默认公式-----------
		//计算保险收费
		if($insureamount)
		{
			/*物品保价是$baoxian_1之内的，保价为$baoxian_2 */
			if($insureamount<=$baoxian_1){$insurevalue=$insureamount*($baoxian_2*0.01);}
			/*物品保价是大于$baoxian_1，保价为$baoxian_3*/
			else{$insurevalue=$insureamount*($baoxian_3*0.01);}
		}
		$baoxian_2.='%';
		$baoxian_3.='%';
		
	}elseif($insuranceFormulaType==1){
		//自定公式-----------
		$r=GetArrVar($insureamount,$insuranceFormula,2,2);
		if($r[1])
		if(have($r[1],'%')){$insurevalue=$insureamount*substr($r[1],-1)/100;}else{$insurevalue=$r[1];}
		
		//以下只是输出展示,无计算作用
		$baoxian_1=$r[0];
		$baoxian_2=$r[1];	if(!have($baoxian_2,'%')){$baoxian_2.=$XAsc;}
		$r2=GetArrVar($r[0]+1,$insuranceFormula,2,2);
		$baoxian_3=$r2[1];	if(!have($baoxian_3,'%')){$baoxian_3.=$XAsc;}
	}
	
	$insurevalue*=exchange($XAScurrency,$XAMcurrency);//转主币种
	
	//输出
	if(!$lx){
		return spr($insurevalue);
	}else{
		return spr($insurevalue).','.spr($baoxian_1).','.$baoxian_2.','.$baoxian_3.','.spr($baoxian_4).','.spr($baoxian_5);
	}
	
}


//显示包裹单号,仓位,来源
/*
$lx='' 显示,并返回; $lx=1 不显示,只返回
$qh='m' 前台;$qh='x'后台
*/
function yundan_bg($bgid,$lx='',$qh='member')
{
	require($_SERVER['DOCUMENT_ROOT'].'/public/global.php');
	if($bgid)
	{
		$url='xingao'; if($qh=='member'){$url='xamember';}
				
		$query="select bgydh,bgid,addSource,whPlace,weight from baoguo where bgid in ({$bgid})  order by bgydh asc";
		$sql=$xingao->query($query);
		while($rs=$sql->fetch_array())
		{
			if(!$lx)
			{
				$num=mysqli_num_rows($xingao->query("select * from yundan where find_in_set('{$rs['bgid']}',bgid) "));
				
				echo '<a href="/'.$url.'/baoguo/show.php?bgid='.$rs['bgid'].'" target="_blank">'.$rs['bgydh'].'</a>';
				echo ' (<font title="'.$LG['function.138'].'">'.spr($rs['weight']).$XAwt.'</font>';
				echo '，<font title="'.$LG['function.139'].'">'.$num.$LG['function.140'].'</font> ';
				
				if($rs['whPlace']){echo '，<font title="'.$LG['function.141'].'">'.$rs['whPlace'].'</font>';}
				echo ') <span class="xa_sep"> | </span> ';//直接用空格，不要用&nbsp;否则不会自动换行
			}
			
			if($rs['addSource']==3){
				$r['bg_number_mall']++;//统计商城包裹数量
			}else{
				$r['bg_number_other']++;//统计非商城包裹数量
			}
		}
		return $r;
	}
}



//显示包裹列表
function yundan_bg_list($bgid,$callFrom='member')
{
	require($_SERVER['DOCUMENT_ROOT'].'/public/global.php');
	if($bgid){
		?>
        <table class="table table-striped table-bordered table-hover" width="100%">
          <tbody>
            <tr>
              <th align="left"><?=$LG['daigou.71']//包裹单号?></th>
              <th align="center"><?=$LG['positions']//仓位?></th>
              <th align="center"><?=$LG['weight']//重量?></th>
              <th align="center"><?=$LG['baoguo.singular']//已下运单数?></th>
            </tr>
		<?php
		$bg_i=0;
		$url='xingao'; if($callFrom=='member'){$url='xamember';}
		
		$query_bg="select bgydh,bgid,addSource,whPlace,weight from baoguo where bgid in ({$bgid})  order by bgydh asc";
		$sql_bg=$xingao->query($query_bg);
		while($rs=$sql_bg->fetch_array())
		{
			$bg_i++;
			if($rs['addSource']==3){
				$r['bg_number_mall']++;//统计商城包裹数量
			}else{
				$r['bg_number_other']++;//统计非商城包裹数量
			}

			$num=mysqli_num_rows($xingao->query("select * from yundan where find_in_set('{$rs['bgid']}',bgid) "));
			?>
				<tr>
				  <td align="left">(<?=$bg_i?>) <a href="/<?=$url?>/baoguo/show.php?bgid=<?=$rs['bgid']?>" target="_blank"><?=cadd($rs['bgydh'])?></a></td>
				  <td align="center"><?=cadd($rs['whPlace'])?></td>
				  <td align="center"><?=spr($rs['weight']).$XAwt?></td>
				  <td align="center"><?=$num.$LG['function.140']?></td>
				</tr>
			<?php
		}
		?>
          </tbody>
        </table>
		<?php
		return $r;
	}
}


//重量取整类型
function weight_int($lx,$value,$weight=0)
{
	global $XAwt;
	
	if($lx==1){
		
		if($value==1){echo "【>0.1进0.5】0.1{$XAwt}或以上进0.5{$XAwt}，0.6{$XAwt}或以上进1{$XAwt}，0.1{$XAwt}以下进0{$XAwt}（比如2.1{$XAwt}算2.5{$XAwt}，2.5{$XAwt}算3{$XAwt}，2.09{$XAwt}算2{$XAwt}）";}
		elseif($value==2){echo "【>0.1进1】0.1{$XAwt}或以上进1{$XAwt}，0.1{$XAwt}以下进0{$XAwt}（比如2.1{$XAwt}算3{$XAwt}）";}
		elseif($value==3){echo "【>0.2进1】0.2{$XAwt}或以上进1{$XAwt}，0.2{$XAwt}以下进0{$XAwt}（比如2.2{$XAwt}算3{$XAwt}，2.15{$XAwt}算2{$XAwt}）";}
		
	}elseif($lx==2){
		
		if($value==1)//向0.5取
		{
			if(get_extension($weight))
			{
				$ceil="0.".get_extension($weight);
				if($ceil<0.1){$ceil=0;}elseif($ceil<=0.5){$ceil=0.5;}elseif($ceil>0.5){$ceil=1;}
				$ceil=(int)$weight+$ceil;
				$weight=$ceil;
			}
		}
		elseif($value==2){//向1取
			if(get_extension($weight))
			{
				$ceil="0.".get_extension($weight);
				if($ceil<0.1){$ceil=0;}elseif($ceil>=0.1){$ceil=1;}
				$ceil=(int)$weight+$ceil;
				$weight=$ceil;
			}
		}
		elseif($value==3){//向0.2取
			if(get_extension($weight))
			{
				$ceil="0.".get_extension($weight);
				if($ceil<0.2){$ceil=0;}elseif($ceil>=0.2){$ceil=1;}
				$ceil=(int)$weight+$ceil;
				$weight=$ceil;
			}
		}
		
		return $weight;
		
	}elseif($lx==3){
		$selected=$value=='0'?'selected':''; echo '<option value="0" '.$selected.'>不取整</option>';
		$selected=$value=='1'?'selected':''; echo '<option value="1" '.$selected.'>>0.1进0.5</option>';
		$selected=$value=='2'?'selected':''; echo '<option value="2" '.$selected.'>>0.1进1</option>';
		$selected=$value=='3'?'selected':''; echo '<option value="3" '.$selected.'>>0.2进1</option>';
	}
}


//该渠道参数
/*
	如:
	$typ='shenfenzheng' 该渠道是否要上传证件
	$typ='customs' 该渠道所用清关公司
	$typ='signday' 出库多少天后会员可自行设置运单为已签收
	$typ='weight_limit' 限重
	$typ='weight_limit_ppt' 限重提示
*/
function channelPar($whid,$channel,$typ='shenfenzheng')
{
	$val="warehouse_{$whid}_{$typ}_{$channel}";
	global $$val;
	return cadd($$val);
}




//获取HS/HG/快递单号-------------------------------------------------
function NumberCode($types)
{
	require($_SERVER['DOCUMENT_ROOT'].'/public/global.php');
	$number='';$err=0;
	$rs=FeData('hscode','*',"checked=1 and types='{$types}' order by hsid asc");//先用旧的,所以必须hsid asc

	if(!$rs['hsid'] && !$err)
	{
		$err=1;$ppt=$LG['function.153'];
	}
	
	//单个号码--------------------------------------------------------------------------------------
	if(!$rs['number_end'] && !$number && !$err)
	{
		$number=$rs['number_str'];
		$save="checked='0'";
	}
	
	//号段处理--------------------------------------------------------------------------------------
	elseif($rs['number_end'] && !$number && !$err)
	{
		
		if(($rs['number_end']==$rs['number_use']||$rs['number_end']<$rs['number_use'])){
			$err=1;$ppt=$LG['function.154'];
			$save="checked='0',number_use=number_str";
		}else{
			
			//生成号码
			if($rs['number_use'])
			{
				$number=findNum($rs['number_use']);//提取数字部分
				$number=str_replace($number,$number+1,$rs['number_use']);//替换掉原数字,也就是生成号码
				if($number==$rs['number_use']){$err=1;$ppt=$LG['function.155'];}
			}else{
				$number=findNum($rs['number_str']);//提取数字部分
				$number=str_replace($number,$number+1,$rs['number_str']);//替换掉原数字,也就是生成号码
				if($number==$rs['number_str']){$err=1;$ppt=$LG['function.155'];}
			}
			
	
			//验证是否超过结尾
			if(findNum($number)>=findNum($rs['number_end']))
			{
				if(findNum($number)>findNum($rs['number_end'])){$err=1;$ppt=$LG['function.154'];}
				$save="checked='0',number_use=number_str";
			}else{
				$xingao->query("update hscode set number_use='{$number}' where hsid='{$rs[hsid]}' ");SQLError('hscode更新已用到');
			}
		}
	}
	
	if($save&&$rs['hsid'])
	{
		$xingao->query("update hscode set {$save} where hsid='{$rs[hsid]}' ");SQLError('hscode设为已用');
	}
	
	if(!$ppt){$ppt=$number;}
	$ret['err']=$err;$ret['number']=$ppt;
	return $ret;
}





//________________________寄往国家___________________________________
/*
	$lx=1 显示下拉时必须有:$groupid='',$warehouse=''
	$lx=2 显示支持的国家编号
	$lx='' 显示单个国家名称
*/
function yundan_Country($zhi='',$lx='',$groupid='',$warehouse='')
{
	require($_SERVER['DOCUMENT_ROOT'].'/public/global.php');
	global $member_warehouse;
	if($lx==1&&$groupid&&$warehouse)
	{
		$country=$member_warehouse[$groupid][$warehouse]['allCountry'];
		$arr=$country;
		if($arr)
		{
			if(!is_array($arr)){$arr=explode(',',$arr);}//转数组
			foreach($arr as $arrkey=>$code)
			{
				$selected=$zhi==$code?'selected':''; echo '<option value="'.$code.'" '.$selected.'>'.Country($code).'</option>';
			}
		}else{
			echo '<option value="" selected>'.$LG['function.150'].'</option>';
		}
		
		
	}elseif($lx==2){
		global $openCountry;
		$country=$openCountry;
		
		if(!$country){echo $LG['function.151'];return;}
		$arr=$country;
		if($arr)
		{
			if(!is_array($arr)){$arr=explode(',',$arr);}//转数组
			foreach($arr as $arrkey=>$code)
			{
				echo Country($code).'：'.$code.'<br>';
			}
		}
	}else{
		return Country($zhi);
	}

}


//获取区域标识-------------------------------------------------
/*
	GetArea($groupid,$warehouse,$country=国家)
*/
function GetArea($groupid,$warehouse,$country)
{
	global $member_warehouse;
	for ($i=1;$i<=$member_warehouse[$groupid][$warehouse]['area']; $i++) 
	{
		$val=ToArr($member_warehouse[$groupid][$warehouse][$i]['country']);
		if($val&&in_array($country,$val)){return $i;break;}
	}
}

/*
输出渠道下拉菜单
$per=0显示全部渠道;$per=1 只显示有权限的渠道;
$callFrom= manage member
*/
function yundan_Channel($warehouse,$country,$userid,$val=0,$callFrom='member',$per=1)
{
	require($_SERVER['DOCUMENT_ROOT'].'/public/global.php');
	global $manage_per,$member_per,$member_warehouse;
	global $Xgroupid,$Mgroupid;
	
	$groupid=$Mgroupid;if($userid){$groupid=FeData('member','groupid',"userid='{$userid}'");}
	
	$admin=$manage_per[$Xgroupid]['admin'];
	$warehouse_arr=explode(",",$manage_per[$Xgroupid]['warehouse']);
	$warehouse=spr($warehouse);
	
	if($warehouse)
	{	
		$query_wh="select * from warehouse where checked='1' and whid='{$warehouse}' order by myorder desc,whid desc";
		$sql_wh=$xingao->query($query_wh);
		while($wh=$sql_wh->fetch_array())
		{
			$wh_name='warehouse_'.$wh['whid'];
			$rs_channel=$wh['channel'.$LT];if(!is_array($rs_channel)&&$rs_channel){$rs_channel=explode(':::',$rs_channel);}
			for ($i=1; $i<=20; $i++) 
			{
				$channel='channel_'.$i;
				if($rs_channel[$i])
				{
					if ($val==$i){
						echo "<option value='{$i}'  selected>".cadd($rs_channel[$i])."</option>"; 
					}else{
						if($per)
						{
							
							$show=0;
							//管理员权限
							if($callFrom=='manage')
							{
								if ($Xgroupid&&($admin||!$per||in_array($wh['whid'],$warehouse_arr))){$show=1;}
							}
							
							//会员权限
							elseif($callFrom=='member')
							{
								$area=GetArea($groupid,$wh['whid'],$country);
								if ($member_warehouse[$groupid][$wh['whid']][$area][$channel.'_checked']){$show=1;}
							}
							
						}else{
							$show=1;
						}
						
						if ($show)
						{
							echo "<option value='{$i}'>".cadd($rs_channel[$i])."</option>";
						}
					}
				}
			}
		}
	}
}
//渠道-结束


//渠道名称
/*
$lx=1 显示渠道名称与渠道编号 $per=0显示全部渠道;$per=1 只显示有权限的渠道;

$lx=0 显示渠道名称,也可用于验证:该会员组,仓库,国家,渠道 是否填写正确(不正确时不会显示渠道名称),实例:
if(!channel_name(FeData('member','groupid',"userid='{$userid}'"),$_POST['warehouse'],$_POST['country'],$_POST['channel'])){exit('会员ID,仓库,国家,渠道 其中有错误,无权下单到该渠道');}

*/
function channel_name($groupid,$warehouse,$country,$zhi,$lx='',$per=1)
{
	if($lx)
	{
		require($_SERVER['DOCUMENT_ROOT'].'/public/global.php');
		global $manage_per,$member_per,$member_warehouse;
		global $Xgroupid,$Mgroupid;
		
		if(!$groupid){$groupid=$Mgroupid;}
		
		$admin=$manage_per[$Xgroupid]['admin'];
		$warehouse_arr=explode(",",$manage_per[$Xgroupid]['warehouse']);
		
		//循环显示
		$query_wh="select * from warehouse where checked='1' order by myorder desc,whid desc";
		$sql_wh=$xingao->query($query_wh);
		while($wh=$sql_wh->fetch_array())
		{
			$wh_name='warehouse_'.$wh['whid'];
			echo '<strong>'.cadd($wh['name'.$LT]).'：</strong><br>';
			
			$rs_channel=$wh['channel'.$LT];if(!is_array($rs_channel)&&$rs_channel){$rs_channel=explode(':::',$rs_channel);}
			for ($i=1; $i<=20; $i++) 
			{
				$channel='channel_'.$i;
				if($rs_channel[$i])
				{
					if($per){
						$show=0;
						
						if($Xgroupid&&($admin||!$per||in_array($wh['whid'],$warehouse_arr))){$show=1;}//管理员权限
						else{
							//会员权限
							if($country)
							{
								$area=GetArea($groupid,$wh['whid'],$country);
								if($member_warehouse[$groupid][$wh['whid']][$area][$channel.'_checked']){$show=1;}
							}else{
								for ($i_area=1;$i_area<=$wh['area']; $i_area++) 
								{
									if($member_warehouse[$groupid][$wh['whid']][$i_area][$channel.'_checked']){$show=1;break;}
								}
							}
						}
						
					}else{$show=1;}
					
					if ($show)
					{
						echo cadd($rs_channel[$i]).'：'.$i.'<br>'; 
					}
				}
			}
			echo '<br>';
		}
	}else{
		global $member_warehouse;
		$area=GetArea($groupid,$warehouse,$country);
		$checked=$member_warehouse[$groupid][$warehouse][$area]['channel_'.$zhi.'_checked'];
		if($checked)
		{
			//名称在warehouse.php已生成缓存文件
			$name='warehouse_'.$warehouse.'_channel_'.$zhi;
			global $$name;
			return cadd($$name);
		}
	}
}

//处理清关资料数组信息
/*
	$typ=1 返回所有ID
	$typ=2 返回该ID的数组资料,$gdid要有值 (示例:$r=yundan_goodsdata($goodsdata,'2');echo $r[1];)
	$typ=0 返回该ID的字符串资料,$gdid要有值 (示例:echo yundan_goodsdata($goodsdata,'2');)
	
	$goodsdata格式:  id1:::数量1|||id2:::数量2
*/
function yundan_goodsdata($goodsdata,$gdid='',$typ=0)
{
	if(!$goodsdata){return;}
	if((!$typ||$typ==2)&&!$gdid){return;}
	
	$goodsdata=ToArr($goodsdata,'|||');
	
	//获取所有ID
	
	if($goodsdata)
	{
		foreach($goodsdata as $arrkey=>$value)
		{
			$id=ToArr($value,':::');
			
			if($typ==1)
			{
				$allId.=$id[0].',';
			}elseif($typ==2){
				if($id[0]==$gdid){return $id;}
			}elseif($typ==0){
				if($id[0]==$gdid){return $value;}
			}
			
		}
		if($typ==1){return DelStr($allId);}
	}
	
	
}

//获取日本邮政所用渠道
/*
	返回1:EMS
	返回2:空运
	返回3:SAL
	返回4:船运
*/
function GetJPChannel($warehouse,$channel)
{
	$JPChannel='warehouse_'.$warehouse.'_JPChannel_'.$channel;
	
	global $$JPChannel;
	return $$JPChannel;
}




//代替证件-查找证件
function cardInstead($country,$s_name='',$smt=0)
{
	
	require($_SERVER['DOCUMENT_ROOT'].'/public/global.php');
	global $ON_cardInstead,$cardInstead_time;
	
	if(!$ON_cardInstead){return $LG['function.152'];}
	$cardInstead_time=strtotime('-'.$cardInstead_time.' month');
	
	if(!$smt)
	{
		if($s_name){$ydid=cardInstead($country,$s_name,1);}
		if(!$ydid){$ydid=cardInstead($country,'',1);}
		return $ydid;
	}
	
	
	//查询条件
	$where='1=1';
	$where.=" and s_shenfenhaoma<>'' and s_shenfenhaoma<>'LATE' and s_shenfenimg_z<>'' and s_shenfenimg_b<>'' ";
	$where.=" and country='{$country}'";//新版-同一个国家
	//$where.=" and s_mobile_code='{$country}'";//旧版-同一个国家
	$where.=" and status=30 and statustime<='{$cardInstead_time}'";//X个月完成的
	$where.=" and cardUseTime<='{$cardInstead_time}'";//X个月未被代替的
	$where.=" and cardYdid=''";//没有使用代替
	if($s_name){$where.=" and s_name='{$s_name}'";}

	$longestTime=0;
	$query="select ydid,userid from yundan where {$where}  group by userid    order by cardUseTime asc,statustime asc ";
	$sql=$xingao->query($query);
	while($rs=$sql->fetch_array())
	{
		//查找最久没下单的会员:最新有没有被代替或发货的
		$fe=FeData('yundan','ydid,cardUseTime,statustime',"userid='{$rs['userid']}' order by cardUseTime desc,statustime desc");
		if(!$longestTime||$longestTime>$fe['cardUseTime']){$longestTime=$fe['cardUseTime'];$ydid=$rs['ydid'];}
		if(!$longestTime||$longestTime>$fe['statustime']){$longestTime=$fe['statustime'];$ydid=$rs['ydid'];}
		
	}
	if($longestTime<=$cardInstead_time){return $ydid;}
	return 0;//没有符合的运单证件
}




//代替证件-更新使用时间
function cardUseTime($cardYdid)
{
	require($_SERVER['DOCUMENT_ROOT'].'/public/global.php');
	if(!$cardYdid){return ;}
	$xingao->query("update yundan set cardUseTime='".time()."' where ydid='{$cardYdid}'");
	SQLError('更新代替证件的原运单时间');
}


//调用运单里商品资料ID
/*
	$table=channelPar($rs['warehouse'],$rs['channel'],'customs');//相关功能:获取资料表
*/
function yundan_gdid($ydid)
{
	require($_SERVER['DOCUMENT_ROOT'].'/public/global.php');
	
	$query_wp="select gdid from wupin where fromtable='yundan' and fromid='{$ydid}' and gdid>0";
	$sql_wp=$xingao->query($query_wp);
	while($wp=$sql_wp->fetch_array())
	{
		$gdid.=$wp['gdid'].',';
	}
	return $gdid=DelStr($gdid);
}









//更新状态
/*
	$rs 运单ID或数组
	$status=30;//要更新的状态
	$time=time();//要更新的状态时间
	$notify=1 用框架发邮件
*/
function yundan_updateStatus($rs,$status,$time='',$notify='')
{
	require($_SERVER['DOCUMENT_ROOT'].'/public/global.php');
	
	if(!$time){$time=time();}
	if(!$rs['ydid']){$rs=FeData('yundan','*',"ydid='{$rs}'");}
	
	//更新主表
	$save="status='{$status}',statustime='{$time}'";
	if(spr($rs['status'])<=4&&!$rs['chukutime']&&$status>4){$save.=",chukutime='{$time}'";}//出库时间
	$xingao->query("update yundan set {$save} where ydid='{$rs[ydid]}'");SQLError('更新主表');
	
	 //更新包裹
	if($rs['bgid'])
	{
		$baoguo_updateStatus=0;
		if($status<=4){$baoguo_updateStatus=6;}
		elseif($status>4&&$status<30){$baoguo_updateStatus=7;}
		elseif($status==30){$baoguo_updateStatus=9;}
		elseif(spr($rs['status'])==-1&&$status>-1){$baoguo_updateStatus=4;}
		
		if($baoguo_updateStatus)
		{
			$xingao->query("update baoguo set status='{$baoguo_updateStatus}' where bgid in ({$rs[bgid]}) ");SQLError('更新包裹');
		}
	}
	
	//代替证件-更新使用时间
	cardUseTime($rs['cardYdid']);
				
	
	//删除超过的记录
	$xingao->query("delete from yundan_bak where status>='{$status}' and ydid='{$rs['ydid']}'");
	SQLError('删除超过的记录');
	
	//添加记录
	if($status>1)//待审和拒绝 不添加记录
	{
		$xingao->query("insert into yundan_bak (ydid,status,statustime) values('{$rs[ydid]}','{$status}','{$time}')");SQLError('添加记录');
	}
	
	
	if($status==30)
	{
		//已签收运单,删除未备案物品资料库里资料
		$gdid=yundan_gdid($rs['ydid']);
		$table=channelPar($rs['warehouse'],$rs['channel'],'customs');
		if($gdid&&$table)
		{
			$xingao->query("delete from {$table} where gdid in ({$gdid}) and record='1' and member='1'");
			SQLError('yundan_updateStatus:删除');
		}
		
		
		//更新代购单:该代购单下的所有商品都已签收时,更新为[已全签收]
		if($rs['goid'])
		{
			//查询代购ID并且,代购单状态为8,9
			$query_gd="select dgid from daigou_goods where goid in ({$rs['goid']}) and dgid=(select dgid from daigou where status in (8,9) limit 1) order by dgid asc";
			$sql_gd=$xingao->query($query_gd);
			while($gd=$sql_gd->fetch_array())
			{
				//查询有数量或有库存的:如果都没有,表示已全部发货并签收
				$num=NumData('daigou_goods',"dgid='{$gd['dgid']}' and (number<>0 or inventoryNumber<>0)");
				if(!$num){daigou_upStatus($gd['dgid'],9.5,0,0,0,'manage',1);}//9.5 已全签收
			}
		}
		
		
	}
	
	
	
	
	
	
	
	
	
	//发通知－开始
	$zhi='status_msg'.$status;		global $$zhi;		$send_msg=$$zhi;
	$zhi='status_mail'.$status;		global $$zhi;		$send_mail=$$zhi;	
	$zhi='status_sms'.$status;		global $$zhi;		$send_sms=$$zhi;
	$zhi='status_wx'.$status;		global $$zhi;		$send_wx=$$zhi;
	
	if($send_msg||$send_mail||$send_sms||$send_wx)
	{
		//获取发送通知内容
		$NoticeTemplate='yundan_updateStatus';
		$status_name=status_name($status);
		require($_SERVER['DOCUMENT_ROOT'].'/public/NoticeTemplate.php');
		
		$send_file='';
	
		//发站内信息
		if($send_msg){SendMsg($rs['userid'],$rs['username'],$send_title,$send_content_msg,$send_file,'','',1,0,1,0);}//CALL页不要加$status=0
		
		//发邮件
		if($send_mail){SendMail('',$send_title,$send_content_mail,$send_file,1,0,$rs['userid'],$notify);}
	
		//发短信
		if($send_sms)
		{
			global $status_sms_lx;
			if(!$status_sms_lx){$rsmobile='';}
			elseif($status_sms_lx==1){$rsmobile=SMSApiType($rs['s_mobile_code'],$rs['s_mobile']);}
			elseif($status_sms_lx==2){$rsmobile=SMSApiType($rs['f_mobile_code'],$rs['f_mobile']);}
			SendSMS($rsmobile,$send_content_sms,0,$rs['userid']);
		}
		
		//发微信
		if($send_wx){SendWX($send_WxTemId,$send_WxTemName,$send_content_wx,$rs['userid']);}
	}
	//发通知－结束
}






//发件信息(本公司)
/*
	$val 	显示内容
	$APLT	指定语种
*/
function CompanySend($val,$APLT='')
{
	require($_SERVER['DOCUMENT_ROOT'].'/public/global.php');
	if($APLT){$LT=$APLT;}
	if($val=='sendName'){	$joint='sendName'.$LT;		global $$joint;		return cadd($$joint);}
	if($val=='sendTel'){	$joint='sendTel'.$LT;		global $$joint;		return cadd($$joint);}
	if($val=='sendFax'){	$joint='sendFax'.$LT;		global $$joint;		return cadd($$joint);}
	if($val=='sendZip'){	$joint='sendZip'.$LT;		global $$joint;		return cadd($$joint);}
	if($val=='sendAdd'){	$joint='sendAdd'.$LT;		global $$joint;		return cadd($$joint);}
}





/*
	运单物品备案计税
	$warehouse,$channel 用于获取资料库表
	$gdid=DelStr(par($_POST['gdid']));//商品资料ID(多个用,分开)
	$go_number=DelStr(par($_POST['go_number']));//商品数量(要跟商品资料ID对应)(多个用,分开)
*/
function calc_gd_tax($warehouse,$channel,$gdid,$go_number)
{
	require($_SERVER['DOCUMENT_ROOT'].'/public/global.php');
	
	
	$table=channelPar($warehouse,$channel,'customs');
	
	
	
	
	//通用清关公司
	if($table)
	{
		//该渠道是否免税
		$customs_DutyFree=channelPar($warehouse,$channel,'customs_DutyFree');
		if($customs_DutyFree=='-1'){return 0;}//该渠道全免税
	}
	
	
	
	
	
	
	
	//gd_mosuda清关公司=================================
	if($table=='gd_mosuda')
	{
		global $ON_gd_mosuda,$gd_mosuda_plusTaxes;
		if(!$ON_gd_mosuda){return 0;}
		if($gd_mosuda_plusTaxes=='-100'){return 0;}//该清关公司全部不收税
		
		//计税-开始
		$fee_tax=0;
		$go_number=ToArr($go_number);
		$arr=ToArr($gdid);
		if($arr)
		{
			foreach($arr as $arrkey=>$value)
			{
				//备案价格*物品数量*行邮税率%
				$calc_tax=FeData($table,'taxes,recordPrice',"record in (0,2) and gdid='{$value}'");
				
				$taxes=$calc_tax['taxes'];
				if($taxes>0&&$calc_tax['recordPrice']>0)
				{
					$fee_tax+=($go_number[$arrkey]*$calc_tax['recordPrice']) * ($taxes/100);
				}
			}
		}
		//计税-结束
		
		
		//是否超过免税额度
		if($fee_tax>=$customs_DutyFree)
		{
			//超过免税额度:收税-------------
			//多加多减 后台所设置的X%
			/*$gd_mosuda_plusTaxes有负数或正数*/
			if($gd_mosuda_plusTaxes!=0){$fee_tax+=($fee_tax*($gd_mosuda_plusTaxes/100));}
			
			//此库都是用人民币,兑换成主币
			$fee_tax*=exchange('CNY',$XAMcurrency);
		}else{
			//未超过免税额度:免税-------------
			return 0;
		}
	}
	
	
	
	
	
	
	
	
	
	return spr($fee_tax);
}



/*
	备案资料导入:执行运单相关操作(计税,支付,更新)
	$gdid 单个商品资料ID
*/
function yundan_gd_update($gdid)
{
	require($_SERVER['DOCUMENT_ROOT'].'/public/global.php');
	
	//更新运单费用
	$query_wp="select fromid,gdid,wupin_number from wupin where fromtable='yundan' and gdid='{$gdid}' and record<>'2'";
	$sql_wp=$xingao->query($query_wp);
	while($wp=$sql_wp->fetch_array())
	{
		//只处理已计费的运单
		$yd=FeData('yundan','ydid,warehouse,channel,fee_tax,money,pay',"ydid='{$wp['fromid']}' and money>0");
		if(!$yd['ydid']){continue;}
		$fee_tax=calc_gd_tax($yd['warehouse'],$yd['channel'],$wp['gdid'],$wp['wupin_number']);
		if($fee_tax>0)
		{
			$save="fee_tax=fee_tax+{$fee_tax},money=money+{$fee_tax},moneytime=".time();
			if($yd['pay']){
				//已支付时
				$save.=",pay=0,money_content=concat('{$LG['yundan.10']}； 
',money_content)";//$LG['yundan.10']商品已通过备案,补交税费
				
				//自动扣费:对已支付并且同意自动扣的才自动扣,不送分,需要发通知 (未做)
			}
			
			//保存
			$xingao->query("update yundan set {$save} where ydid='{$yd['ydid']}'");
			SQLError('更新运单费用');
			
			//运单扣费:后台
		}
	}
}


//计费重量
function yundan_calcWeight($rs,$groupid='')
{
	require($_SERVER['DOCUMENT_ROOT'].'/public/global.php');
	global $member_warehouse;
	
	if(!$groupid){$groupid=FeData('member','groupid',"userid='{$rs['userid']}'");}
	$area=GetArea($groupid,$rs['warehouse'],$rs['country']);

	$channel_formula=$member_warehouse[$groupid][$rs['warehouse']][$area]['channel_'.$rs['channel'].'_formula'];
	if($channel_formula){return spr($rs['weight']);}//有指定公式时,没有单独的取整重量,因此就没有单独计费重量
	
	//用默认公式时,有取整重量
	$trunc=$member_warehouse[$groupid][$rs['warehouse']][$area]['channel_'.$rs['channel'].'_weight_int'];
	return spr(weight_int(2,$trunc,$rs['weight']));//重量取整
}


//计费更新:物品描述,保价,申报价值,计算费用等处理
/*
	注:因为有调用计费页面,要调用很多内容,因此此页不能放入function
	$calc=0 不自动计费
	$calc=1 按称重自动计费
	$calc=2 按预估重量自动计费
	
	$onlineAdd=1 在线下单,不更新:物品描述,保价,申报价值
	$onlineAdd=0 导入方式,更新:物品描述,保价,申报价值
*/
function yundan_calc_save($ydid,$calc=0,$onlineAdd=0)
{
	require($_SERVER['DOCUMENT_ROOT'].'/public/global.php');
	global $member_warehouse,$member_per;
	if(!$ydid){return;}
			
ob_start();//开始缓冲(以下有部分直接输出,因此用缓存禁止输出)
//----------------------------------------------------------
	
	
	//获取和处理
	$_POST=FeData('yundan','*',"ydid='{$ydid}'");//xingao/yundan/call/calc.php 需要用$_POST
	
	$save="ydid=ydid";
	if(!$onlineAdd)
	{
		$wupin=wupin_morefield_sql('yundan',$ydid);
		$goodsdescribe=add(goodsdescribe($wupin));//物品描述
		$declarevalue=declarevalue($_POST['declarevalue'],$wupin);//物品价值
		$insureamount=insureamount($_POST['warehouse'],$_POST['channel'],$_POST['insureamount'],$declarevalue,$_POST['declarevalue']);//保价
		$_POST['insurevalue']=insurance($_POST['warehouse'],$_POST['channel'],$insureamount,0);//保价费
		$save.=",goodsdescribe='{$goodsdescribe}',declarevalue='{$declarevalue}',insureamount='{$insureamount}',insurevalue='{$_POST['insurevalue']}'";
	}
	
	
	//计费处理
	if($calc)
	{
		if($calc==1)
		{
			$_POST['weight']=$_POST['weight'];$money_content='按称重重量自动计算';
		}elseif($calc==2){
			$_POST['weight']=$_POST['weightEstimate'];$money_content='按预估重量自动计算';
		}
		
		if($_POST['weight']>0)
		{
			$_POST['yf']=2;
			
			//用于计算增值服务
			$_POST['bg_number']=arrcount($_POST['bgid']);
			
			//用于计算增值服务,税费,其他费用
			$_POST['wp_number']=0;$_POST['gdid']='';$_POST['gd_number']='';$_POST['goid']='';$_POST['go_number']='';
			$query_wp="select gdid,goid,wupin_number from wupin where fromtable='yundan' and fromid='{$_POST['ydid']}'";
			$sql_wp=$xingao->query($query_wp);
			while($wp=$sql_wp->fetch_array())
			{
				$_POST['wp_number']+=$wp['wupin_number'];
				
				if($wp['gdid']){
					$_POST['gdid'].=$wp['gdid'].',';//商品资料ID
					$_POST['gd_number'].=$wp['wupin_number'].',';//商品资料ID对应的数量
				}
				
				if($wp['goid']){
					$_POST['goid'].=$wp['goid'].',';//代购ID
					$_POST['go_number'].=$wp['wupin_number'].',';//代购对应的数量
				}
			}
			
			
			
			
			//合箱费用
			$bgid=$_POST['bgid'];
			$show_small=1;//简洁显示
			$notlist=0;//不输列表,需要带有 $yundan_bg=yundan_bg_list($bgid,$callFrom='manage');
			$groupid=FeData('member','groupid',"userid='{$_POST['userid']}'");
			require_once($_SERVER['DOCUMENT_ROOT'].'/xingao/yundan/call/bg_hx_fh.php');
			$_POST['baoguo_hx_fee']=$baoguo_hx_fee;
			
			
			require($_SERVER['DOCUMENT_ROOT'].'/xingao/yundan/call/calc.php');//返回$fee_rs数组
			
			$save.=",fee_transport='".spr($fee_rs[1])."',fee_cc='".spr($fee_rs[5])."',fee_ware='".spr($fee_rs[7])."',fee_service='".spr($fee_rs[4])."',discount='".spr($fee_rs[2])."',money='".spr($fee_rs[0])."',fee_other='0',tax_number='0',fee_tax='".spr($fee_rs[6])."',money_content='{$money_content}',moneytime='".time()."',calc='{$calc}'";
			
		}
	}
	
	//保存
	$xingao->query("update yundan set {$save} where ydid='{$ydid}'");SQLError('excel_import_update 保存');
		
		
//----------------------------------------------------------
ob_get_contents();//得到缓冲区的数据
ob_end_clean();//结束缓存：清除并关闭缓冲区
}

?>