<?php 
/*
	$ret //返回
*/
function gd_mosuda_api($rs,$table)
{
	require($_SERVER['DOCUMENT_ROOT'].'/public/global.php');
	global $gd_mosuda_username,$gd_mosuda_password,$gd_mosuda_key,$gd_mosuda_CountryCode,$gd_mosuda_ShopId,$gd_mosuda_record;
	$exchange=exchange($XAScurrency,'CNY');//CNY
	
	
	
	//获取物品资料并转数组--------------------------------------------------
	$Items=array();
	$query_wp="select gdid,wpid,wupin_number,wupin_name,wupin_price from wupin where fromtable='yundan' and fromid='{$rs['ydid']}' and gdid>0 {$where_wp}";
	$sql_wp=$xingao->query($query_wp);
	while($wp=$sql_wp->fetch_array())
	{
		$gd=FeData($table,'gdid,itemNo',"record in (0,2) and gdid='{$wp['gdid']}'");
		$total_wupin_name.="{$wp['wupin_name']}、";
		$total_wupin_number+=$wp['wupin_number'];
		
		//增加数组
		$Items[]=array(
			/*是否要备案,视口岸而定*/
			//用备案的商品,发送以下
			'ProductCode'    => cadd($gd['itemNo']),//货号
			'UnitPrice'  	 => spr($wp['wupin_price']*$exchange),//单价(CNY)
			'GoodsNumber'    => spr($wp['wupin_number']),//数量

			
			//不用备案的商品,发送以下
			/*
			'ProductName'    => 'ProductName'.time(),//商品名称
			'ProductBrand'   => '商品品牌'.mt_rand(1,999),//商品品牌
			'ProductCount'   => '12',//商品数量  明细相加应该跟总的相等
			'TotalWeight'     => 12,//商品总净重  明细总的相加应该跟踪的相等  大于10
			'Unit'             => '双',//单位
			'Specification'    => 'a',      //规格
			'Price'            => 100 ,//商品单价  大于0
			'Currency'         => 'USD',//币别  默认为USD美元
			'PostTaxNum'       => '01010700',//行邮税号
			'status'           => '',//状态
			*/
		);
	}
	
	//如有开通多语言功能,则自动调用英文版内容发件人资料
	global $sendNameEN; 	if($sendNameEN){$SendLT='EN';}else{$SendLT=$LT;}
	

	//获取运单资料并附加物品数组--------------------------------------------------
	$args = array(
		'CountryCode'       => $gd_mosuda_CountryCode,//接口参数:国家简称(原寄地对应的国家简称两位大写字母)
		'ShopId'			=> $gd_mosuda_ShopId,//接口参数:口岸类型
	
		'OrderCode'  		=> cadd($rs['ydh']),//订单号,运单号
		
		'Sender'            => CompanySend('sendName',$SendLT),//cadd($rs['f_name']),//发件人
		'SendAddress' 	    => CompanySend('sendAdd',$SendLT),//yundan_add_all($rs,'f'),//发件人地址
		'SendPhone'         => CompanySend('sendTel',$SendLT),//cadd($rs['f_mobile']),//发件人电话 cadd("+{$rs['f_mobile_code']} {$rs['f_mobile']}")
		
		'Receiver'          => cadd($rs['s_name']),//收件人
		'ReceivePhone'      => cadd($rs['s_mobile']),//收件人电话
		'ReceiveAddress'    => yundan_add_all($rs),//收件人地址
		'ReceiveProvince'   => cadd($rs['s_add_shengfen']),//收件人省份
		'ReceiveCity'       => cadd($rs['s_add_chengshi']),//收件人城市
		'ReceiveZip'        => cadd($rs['s_zip']),//收件人邮编
		'ReceiveCardNo'		=> cadd($rs['s_shenfenhaoma']),//收件人身份证
	
		'TotalPrice'        => spr($rs['declarevalue']*$exchange),//订单总金额(CNY)等于明细里数量*单价的总和
		'BagName'           => DelStr($total_wupin_name,'、'),//内件名称(根据行邮税所包含的物件名称,以、作为分隔 包包、鞋子)
		'BagCount'          => $total_wupin_number,//内件总数量(订单明细中的数量总和)
		'BagWeight'         => spr($rs['weight']*$XAwtkg),//重量(KG订单包裹的总重量)
		
		//注意如果是不用备案则用:Items,备案用:PortItem
		'PortItem' 			=> $Items //物品资料 数组:
	
	);
	
	
	
	//接口账号参数--------------------------------------------------
	$data=array(
		'order'=>$args,
		'userName'=>$gd_mosuda_username,//接口参数
		'password'=>$gd_mosuda_password,//接口参数
		'key'=>$gd_mosuda_key//接口参数
	);




	//print_r($data);exit;

	
	if($gd_mosuda_record){
		//备案接口--------------------------------------------------
		//发送----------
		$url='http://newb2cservice.mosuda.com/CPOrderService.svc?wsdl';
		$client = new SoapClient($url);
		$result=$client->AddCPOrder($data);
		//print_r($result);exit;
		
		//返回----------
		$arr =object_array($result);
		//echo $arr['AddCPOrderResult']['IsSuccess'].$arr['AddCPOrderResult']['Message'];exit;
		if($arr['AddCPOrderResult']['IsSuccess']){return 1;}else{return $arr['AddCPOrderResult']['Message'];}
	}else{
		//不用备案接口--------------------------------------------------
		//发送----------
		$url='http://newb2cservice.mosuda.com/B2COrderService.svc?wsdl';
		$client = new SoapClient($url);
		$result=$client->AddB2COrder($data);
		
		//返回----------
		$arr =object_array($result);
		if($arr['AddB2COrderResult']['IsSuccess']){return 1;}else{return $arr['AddB2COrderResult']['Message'];}
	}
}
