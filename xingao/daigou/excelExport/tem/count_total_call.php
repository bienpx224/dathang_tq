<?php 
//统计当天-------------------------------------------------------------
if($totalTyp=='small')
{
	if(!$countTime){$countTime=DateYmd($rs['addtime'],2);}
	if($countTime!=DateYmd($rs['addtime'],2))
	{
		$small_money_0_show=''; $small_money_1_show=''; $small_money_2_show=''; $small_money_3_show='';
		
		//所有币种	
		$arr=ToArr($openCurrency,',');
		if($arr)
		{
			foreach($arr as $arrkey=>$value)
			{
				//小计
				if(spr($small_money_0[$value])){$small_money_0_show.=spr($small_money_0[$value]).$value.'
	';}
				if(spr($small_money_1[$value])){$small_money_1_show.=spr($small_money_1[$value]).$value.'
	';}
				if(spr($small_money_2[$value])){$small_money_2_show.=spr($small_money_2[$value]).$value.'
	';}
				if(spr($small_money_3[$value])){$small_money_3_show.=spr($small_money_3[$value]).$value.'
	';}
			}
		}
		
		
		
		//统计
		$data[] = array(
			'list1'=>'当天小计',//会员
			'list2'=>$countTime,//下单时间
			'list3'=>$num.'单',//采购时间
			'list4'=>$small_money_0_show,//代购价格
			'list5'=>$small_money_1_show,//采购成本
			'list6'=>$small_money_2_show,//利润
			'list7'=>$small_money_3_show,//扣费
		);	
		
		//多空一行
		$data[] = array(
			'list1'=>'',//会员
			'list2'=>'',//下单时间
			'list3'=>'',//采购时间
			'list4'=>'',//代购价格
			'list5'=>'',//采购成本
			'list6'=>'',//利润
			'list7'=>'',//扣费
		);	
		
		
		unset($small_money_0);unset($small_money_1);unset($small_money_2);unset($small_money_3);$num=0;
		$countTime=DateYmd($rs['addtime'],2);
	}
}






//统计全部-------------------------------------------------------------
elseif($totalTyp=='all')
{
	$total_money_0_show=''; $total_money_1_show=''; $total_money_2_show=''; $total_money_3_show='';

	//所有币种	
	$arr=ToArr($openCurrency,',');
	if($arr)
	{
		foreach($arr as $arrkey=>$value)
		{
			//小计
			if(spr($total_money_0[$value])){$total_money_0_show.=spr($total_money_0[$value]).$value.'
';}
			if(spr($total_money_1[$value])){$total_money_1_show.=spr($total_money_1[$value]).$value.'
';}
			if(spr($total_money_2[$value])){$total_money_2_show.=spr($total_money_2[$value]).$value.'
';}
			if(spr($total_money_3[$value])){$total_money_3_show.=spr($total_money_3[$value]).$value.'
';}
		}
	}
	
	
	
	//统计
	$data[] = array(
		'list1'=>'全部总计',//会员
		'list2'=>'',//下单时间
		'list3'=>$total_num.'单',//采购时间
		'list4'=>$total_money_0_show,//代购价格
		'list5'=>$total_money_1_show,//采购成本
		'list6'=>$total_money_2_show,//利润
		'list7'=>$total_money_3_show,//扣费
	);	
}

?>