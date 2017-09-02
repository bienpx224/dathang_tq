<?php
/*
	以下增加后,还需要在 兴奥API 接口处增加各接口的快递公司编码 Logistics/call/
*/
$expresses=array (
	//常用
	'ems' => 'EMS(中国件)',
	'emsguoji' => 'EMS(国际件)',
	'japanposten' => '日本邮政',
	'shunfeng' => '顺丰',

	//中国
	'yuantong' => '圆通',
	'zhongtong' => '中通',
	'shentong' => '申通',
	'yunda' => '韵达',
	'zhaijisong' => '宅急送',
	'tiantian' => '天天快递',
	'huitongkuaidi' => '百世汇通',
	'debang' => '德邦物流',

	//新
	'cjkorea' => 'CJ大韩通运',
	'bdt' => '八达通',

	//国际
	'youzhengguonei' => '中国邮政(中国件)',
	'youzhengguoji' => '中国邮政(国际件)',
	'dpex' => 'DPEX(中国件)',
	'dpexen' => 'DPEX国际件',
	'dhl' => 'DHL(中国件)',
	'dhlen' => 'DHL(国际件)',
	'lianbangkuaidi' => 'FedEx(中国件)',
	'fedex' => 'FedEx(国际件)',
	'tnt' => 'TNT(中国件)',
	'tnten' => 'TNT(国际件)',
	'ups' => 'UPS(中国件)',
	'upsen' => 'UPS(国际件)',
	'usps' => 'USPS',
	'auspost' => '澳大利亚邮政',
	'canpost' => '加拿大邮政',
	'ruidianyouzheng' => '瑞典邮政',
	'hkpost' => '香港邮政',
	'singpost' => '新加坡邮政',
	
	'postnlpacle' => '荷兰包裹',
	'postnlchina' => '荷兰邮政(中国件)',
	'bpostinter' => '比利时(国际件)',
	'belgiumpost' => '比利时邮政',
)
?>