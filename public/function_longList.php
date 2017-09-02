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
//=======================================长列表==============================================





//____________________________寄往国家/仓库所在国家__________________________________
/*
	$lx=1 显示下拉:全部国家
	$lx=2 显示下拉:支持的国家
	$lx=3 输出:数组多个名称
	$lx=''输出:单个名称
	
	$language='' 返回默认语种
	$language='en' 返回英文语种(打印时需要英文),不分大小写
	
	数字号码是用海关国别(地区)代码表的号码
*/


function Country($zhi,$lx='',$language='')
{
	$language=strtoupper($language);
	if($language)
	{
		$loadFile='/Language/'.$language.'.php';
		if(HaveFile($loadFile)){require($_SERVER['DOCUMENT_ROOT'].$loadFile);}
	}
	if(!$LG){global $LG;}
			
	
	
	if($lx==1||$lx==2){
		
		if($lx==2){global $openCountry;$openCountry=ToArr($openCountry);}
		$zhi=ToArr($zhi);
		  Country_option('142',$zhi,$lx,$openCountry);//中国
		  Country_option('110',$zhi,$lx,$openCountry);//中国香港
		  Country_option('143',$zhi,$lx,$openCountry);//中国台湾
		  Country_option('121',$zhi,$lx,$openCountry);//中国澳门
		  Country_option('',$zhi,$lx,$openCountry);//空一行
		  
		  Country_option('101',$zhi,$lx,$openCountry);
		  Country_option('102',$zhi,$lx,$openCountry);
		  Country_option('103',$zhi,$lx,$openCountry);
		  Country_option('104',$zhi,$lx,$openCountry);
		  Country_option('105',$zhi,$lx,$openCountry);
		  Country_option('106',$zhi,$lx,$openCountry);
		  Country_option('107',$zhi,$lx,$openCountry);
		  Country_option('108',$zhi,$lx,$openCountry);
		  Country_option('109',$zhi,$lx,$openCountry);
		  Country_option('111',$zhi,$lx,$openCountry);
		  Country_option('112',$zhi,$lx,$openCountry);
		  Country_option('113',$zhi,$lx,$openCountry);
		  Country_option('114',$zhi,$lx,$openCountry);
		  Country_option('115',$zhi,$lx,$openCountry);
		  Country_option('116',$zhi,$lx,$openCountry);
		  Country_option('117',$zhi,$lx,$openCountry);
		  Country_option('118',$zhi,$lx,$openCountry);
		  Country_option('119',$zhi,$lx,$openCountry);
		  Country_option('120',$zhi,$lx,$openCountry);
		  Country_option('122',$zhi,$lx,$openCountry);
		  Country_option('123',$zhi,$lx,$openCountry);
		  Country_option('124',$zhi,$lx,$openCountry);
		  Country_option('125',$zhi,$lx,$openCountry);
		  Country_option('126',$zhi,$lx,$openCountry);
		  Country_option('127',$zhi,$lx,$openCountry);
		  Country_option('128',$zhi,$lx,$openCountry);
		  Country_option('129',$zhi,$lx,$openCountry);
		  Country_option('130',$zhi,$lx,$openCountry);
		  Country_option('131',$zhi,$lx,$openCountry);
		  Country_option('132',$zhi,$lx,$openCountry);
		  Country_option('133',$zhi,$lx,$openCountry);
		  Country_option('134',$zhi,$lx,$openCountry);
		  Country_option('135',$zhi,$lx,$openCountry);
		  Country_option('136',$zhi,$lx,$openCountry);
		  Country_option('137',$zhi,$lx,$openCountry);
		  Country_option('138',$zhi,$lx,$openCountry);
		  Country_option('139',$zhi,$lx,$openCountry);
		  Country_option('141',$zhi,$lx,$openCountry);
		  Country_option('144',$zhi,$lx,$openCountry);
		  Country_option('145',$zhi,$lx,$openCountry);
		  Country_option('146',$zhi,$lx,$openCountry);
		  Country_option('147',$zhi,$lx,$openCountry);
		  Country_option('148',$zhi,$lx,$openCountry);
		  Country_option('149',$zhi,$lx,$openCountry);
		  Country_option('199',$zhi,$lx,$openCountry);
		  Country_option('201',$zhi,$lx,$openCountry);
		  Country_option('202',$zhi,$lx,$openCountry);
		  Country_option('203',$zhi,$lx,$openCountry);
		  Country_option('204',$zhi,$lx,$openCountry);
		  Country_option('205',$zhi,$lx,$openCountry);
		  Country_option('206',$zhi,$lx,$openCountry);
		  Country_option('207',$zhi,$lx,$openCountry);
		  Country_option('208',$zhi,$lx,$openCountry);
		  Country_option('209',$zhi,$lx,$openCountry);
		  Country_option('210',$zhi,$lx,$openCountry);
		  Country_option('211',$zhi,$lx,$openCountry);
		  Country_option('212',$zhi,$lx,$openCountry);
		  Country_option('213',$zhi,$lx,$openCountry);
		  Country_option('214',$zhi,$lx,$openCountry);
		  Country_option('215',$zhi,$lx,$openCountry);
		  Country_option('216',$zhi,$lx,$openCountry);
		  Country_option('217',$zhi,$lx,$openCountry);
		  Country_option('218',$zhi,$lx,$openCountry);
		  Country_option('219',$zhi,$lx,$openCountry);
		  Country_option('220',$zhi,$lx,$openCountry);
		  Country_option('221',$zhi,$lx,$openCountry);
		  Country_option('222',$zhi,$lx,$openCountry);
		  Country_option('223',$zhi,$lx,$openCountry);
		  Country_option('224',$zhi,$lx,$openCountry);
		  Country_option('225',$zhi,$lx,$openCountry);
		  Country_option('226',$zhi,$lx,$openCountry);
		  Country_option('227',$zhi,$lx,$openCountry);
		  Country_option('228',$zhi,$lx,$openCountry);
		  Country_option('229',$zhi,$lx,$openCountry);
		  Country_option('230',$zhi,$lx,$openCountry);
		  Country_option('231',$zhi,$lx,$openCountry);
		  Country_option('232',$zhi,$lx,$openCountry);
		  Country_option('233',$zhi,$lx,$openCountry);
		  Country_option('234',$zhi,$lx,$openCountry);
		  Country_option('235',$zhi,$lx,$openCountry);
		  Country_option('236',$zhi,$lx,$openCountry);
		  Country_option('237',$zhi,$lx,$openCountry);
		  Country_option('238',$zhi,$lx,$openCountry);
		  Country_option('239',$zhi,$lx,$openCountry);
		  Country_option('240',$zhi,$lx,$openCountry);
		  Country_option('241',$zhi,$lx,$openCountry);
		  Country_option('242',$zhi,$lx,$openCountry);
		  Country_option('243',$zhi,$lx,$openCountry);
		  Country_option('244',$zhi,$lx,$openCountry);
		  Country_option('245',$zhi,$lx,$openCountry);
		  Country_option('246',$zhi,$lx,$openCountry);
		  Country_option('247',$zhi,$lx,$openCountry);
		  Country_option('248',$zhi,$lx,$openCountry);
		  Country_option('249',$zhi,$lx,$openCountry);
		  Country_option('250',$zhi,$lx,$openCountry);
		  Country_option('251',$zhi,$lx,$openCountry);
		  Country_option('252',$zhi,$lx,$openCountry);
		  Country_option('253',$zhi,$lx,$openCountry);
		  Country_option('254',$zhi,$lx,$openCountry);
		  Country_option('255',$zhi,$lx,$openCountry);
		  Country_option('256',$zhi,$lx,$openCountry);
		  Country_option('257',$zhi,$lx,$openCountry);
		  Country_option('258',$zhi,$lx,$openCountry);
		  Country_option('259',$zhi,$lx,$openCountry);
		  Country_option('299',$zhi,$lx,$openCountry);
		  Country_option('301',$zhi,$lx,$openCountry);
		  Country_option('302',$zhi,$lx,$openCountry);
		  Country_option('303',$zhi,$lx,$openCountry);
		  Country_option('304',$zhi,$lx,$openCountry);
		  Country_option('305',$zhi,$lx,$openCountry);
		  Country_option('306',$zhi,$lx,$openCountry);
		  Country_option('307',$zhi,$lx,$openCountry);
		  Country_option('308',$zhi,$lx,$openCountry);
		  Country_option('309',$zhi,$lx,$openCountry);
		  Country_option('310',$zhi,$lx,$openCountry);
		  Country_option('311',$zhi,$lx,$openCountry);
		  Country_option('312',$zhi,$lx,$openCountry);
		  Country_option('313',$zhi,$lx,$openCountry);
		  Country_option('314',$zhi,$lx,$openCountry);
		  Country_option('315',$zhi,$lx,$openCountry);
		  Country_option('316',$zhi,$lx,$openCountry);
		  Country_option('318',$zhi,$lx,$openCountry);
		  Country_option('320',$zhi,$lx,$openCountry);
		  Country_option('321',$zhi,$lx,$openCountry);
		  Country_option('322',$zhi,$lx,$openCountry);
		  Country_option('323',$zhi,$lx,$openCountry);
		  Country_option('324',$zhi,$lx,$openCountry);
		  Country_option('325',$zhi,$lx,$openCountry);
		  Country_option('326',$zhi,$lx,$openCountry);
		  Country_option('327',$zhi,$lx,$openCountry);
		  Country_option('328',$zhi,$lx,$openCountry);
		  Country_option('329',$zhi,$lx,$openCountry);
		  Country_option('330',$zhi,$lx,$openCountry);
		  Country_option('331',$zhi,$lx,$openCountry);
		  Country_option('334',$zhi,$lx,$openCountry);
		  Country_option('335',$zhi,$lx,$openCountry);
		  Country_option('336',$zhi,$lx,$openCountry);
		  Country_option('337',$zhi,$lx,$openCountry);
		  Country_option('338',$zhi,$lx,$openCountry);
		  Country_option('339',$zhi,$lx,$openCountry);
		  Country_option('340',$zhi,$lx,$openCountry);
		  Country_option('343',$zhi,$lx,$openCountry);
		  Country_option('344',$zhi,$lx,$openCountry);
		  Country_option('347',$zhi,$lx,$openCountry);
		  Country_option('349',$zhi,$lx,$openCountry);
		  Country_option('350',$zhi,$lx,$openCountry);
		  Country_option('351',$zhi,$lx,$openCountry);
		  Country_option('352',$zhi,$lx,$openCountry);
		  Country_option('353',$zhi,$lx,$openCountry);
		  Country_option('354',$zhi,$lx,$openCountry);
		  Country_option('355',$zhi,$lx,$openCountry);
		  Country_option('356',$zhi,$lx,$openCountry);
		  Country_option('357',$zhi,$lx,$openCountry);
		  Country_option('358',$zhi,$lx,$openCountry);
		  Country_option('359',$zhi,$lx,$openCountry);
		  Country_option('399',$zhi,$lx,$openCountry);
		  Country_option('401',$zhi,$lx,$openCountry);
		  Country_option('402',$zhi,$lx,$openCountry);
		  Country_option('403',$zhi,$lx,$openCountry);
		  Country_option('404',$zhi,$lx,$openCountry);
		  Country_option('405',$zhi,$lx,$openCountry);
		  Country_option('406',$zhi,$lx,$openCountry);
		  Country_option('408',$zhi,$lx,$openCountry);
		  Country_option('409',$zhi,$lx,$openCountry);
		  Country_option('410',$zhi,$lx,$openCountry);
		  Country_option('411',$zhi,$lx,$openCountry);
		  Country_option('412',$zhi,$lx,$openCountry);
		  Country_option('413',$zhi,$lx,$openCountry);
		  Country_option('414',$zhi,$lx,$openCountry);
		  Country_option('415',$zhi,$lx,$openCountry);
		  Country_option('416',$zhi,$lx,$openCountry);
		  Country_option('417',$zhi,$lx,$openCountry);
		  Country_option('418',$zhi,$lx,$openCountry);
		  Country_option('419',$zhi,$lx,$openCountry);
		  Country_option('420',$zhi,$lx,$openCountry);
		  Country_option('421',$zhi,$lx,$openCountry);
		  Country_option('422',$zhi,$lx,$openCountry);
		  Country_option('423',$zhi,$lx,$openCountry);
		  Country_option('424',$zhi,$lx,$openCountry);
		  Country_option('425',$zhi,$lx,$openCountry);
		  Country_option('426',$zhi,$lx,$openCountry);
		  Country_option('427',$zhi,$lx,$openCountry);
		  Country_option('428',$zhi,$lx,$openCountry);
		  Country_option('429',$zhi,$lx,$openCountry);
		  Country_option('430',$zhi,$lx,$openCountry);
		  Country_option('431',$zhi,$lx,$openCountry);
		  Country_option('432',$zhi,$lx,$openCountry);
		  Country_option('433',$zhi,$lx,$openCountry);
		  Country_option('434',$zhi,$lx,$openCountry);
		  Country_option('435',$zhi,$lx,$openCountry);
		  Country_option('436',$zhi,$lx,$openCountry);
		  Country_option('437',$zhi,$lx,$openCountry);
		  Country_option('438',$zhi,$lx,$openCountry);
		  Country_option('439',$zhi,$lx,$openCountry);
		  Country_option('440',$zhi,$lx,$openCountry);
		  Country_option('441',$zhi,$lx,$openCountry);
		  Country_option('442',$zhi,$lx,$openCountry);
		  Country_option('443',$zhi,$lx,$openCountry);
		  Country_option('444',$zhi,$lx,$openCountry);
		  Country_option('445',$zhi,$lx,$openCountry);
		  Country_option('446',$zhi,$lx,$openCountry);
		  Country_option('447',$zhi,$lx,$openCountry);
		  Country_option('448',$zhi,$lx,$openCountry);
		  Country_option('449',$zhi,$lx,$openCountry);
		  Country_option('499',$zhi,$lx,$openCountry);
		  Country_option('501',$zhi,$lx,$openCountry);
		  Country_option('502',$zhi,$lx,$openCountry);
		  Country_option('503',$zhi,$lx,$openCountry);
		  Country_option('504',$zhi,$lx,$openCountry);
		  Country_option('599',$zhi,$lx,$openCountry);
		  Country_option('601',$zhi,$lx,$openCountry);
		  Country_option('602',$zhi,$lx,$openCountry);
		  Country_option('603',$zhi,$lx,$openCountry);
		  Country_option('604',$zhi,$lx,$openCountry);
		  Country_option('605',$zhi,$lx,$openCountry);
		  Country_option('606',$zhi,$lx,$openCountry);
		  Country_option('607',$zhi,$lx,$openCountry);
		  Country_option('608',$zhi,$lx,$openCountry);
		  Country_option('609',$zhi,$lx,$openCountry);
		  Country_option('610',$zhi,$lx,$openCountry);
		  Country_option('611',$zhi,$lx,$openCountry);
		  Country_option('612',$zhi,$lx,$openCountry);
		  Country_option('613',$zhi,$lx,$openCountry);
		  Country_option('614',$zhi,$lx,$openCountry);
		  Country_option('615',$zhi,$lx,$openCountry);
		  Country_option('616',$zhi,$lx,$openCountry);
		  Country_option('617',$zhi,$lx,$openCountry);
		  Country_option('618',$zhi,$lx,$openCountry);
		  Country_option('619',$zhi,$lx,$openCountry);
		  Country_option('620',$zhi,$lx,$openCountry);
		  Country_option('621',$zhi,$lx,$openCountry);
		  Country_option('622',$zhi,$lx,$openCountry);
		  Country_option('623',$zhi,$lx,$openCountry);
		  Country_option('625',$zhi,$lx,$openCountry);
		  Country_option('699',$zhi,$lx,$openCountry);
		  Country_option('701',$zhi,$lx,$openCountry);
		  Country_option('702',$zhi,$lx,$openCountry);
		  Country_option('999',$zhi,$lx,$openCountry);


	}elseif($lx==3){
		
		
		$arr=$zhi;
		if($arr)
		{
			if(!is_array($arr)){$arr=explode(',',$arr);}//转数组
			foreach($arr as $arrkey=>$value)
			{
				$r.=Country($value).',';
			}
		}
		return DelStr($r);
		
		
	}else{
		switch($zhi)
		{
		  case '':return '';
		  case '101':return $LG['country.101'];
		  case '102':return $LG['country.102'];
		  case '103':return $LG['country.103'];
		  case '104':return $LG['country.104'];
		  case '105':return $LG['country.105'];
		  case '106':return $LG['country.106'];
		  case '107':return $LG['country.107'];
		  case '108':return $LG['country.108'];
		  case '109':return $LG['country.109'];
		  case '110':return $LG['country.110'];
		  case '111':return $LG['country.111'];
		  case '112':return $LG['country.112'];
		  case '113':return $LG['country.113'];
		  case '114':return $LG['country.114'];
		  case '115':return $LG['country.115'];
		  case '116':return $LG['country.116'];
		  case '117':return $LG['country.117'];
		  case '118':return $LG['country.118'];
		  case '119':return $LG['country.119'];
		  case '120':return $LG['country.120'];
		  case '121':return $LG['country.121'];
		  case '122':return $LG['country.122'];
		  case '123':return $LG['country.123'];
		  case '124':return $LG['country.124'];
		  case '125':return $LG['country.125'];
		  case '126':return $LG['country.126'];
		  case '127':return $LG['country.127'];
		  case '128':return $LG['country.128'];
		  case '129':return $LG['country.129'];
		  case '130':return $LG['country.130'];
		  case '131':return $LG['country.131'];
		  case '132':return $LG['country.132'];
		  case '133':return $LG['country.133'];
		  case '134':return $LG['country.134'];
		  case '135':return $LG['country.135'];
		  case '136':return $LG['country.136'];
		  case '137':return $LG['country.137'];
		  case '138':return $LG['country.138'];
		  case '139':return $LG['country.139'];
		  case '141':return $LG['country.141'];
		  case '142':return $LG['country.142'];
		  case '143':return $LG['country.143'];
		  case '144':return $LG['country.144'];
		  case '145':return $LG['country.145'];
		  case '146':return $LG['country.146'];
		  case '147':return $LG['country.147'];
		  case '148':return $LG['country.148'];
		  case '149':return $LG['country.149'];
		  case '199':return $LG['country.199'];
		  case '201':return $LG['country.201'];
		  case '202':return $LG['country.202'];
		  case '203':return $LG['country.203'];
		  case '204':return $LG['country.204'];
		  case '205':return $LG['country.205'];
		  case '206':return $LG['country.206'];
		  case '207':return $LG['country.207'];
		  case '208':return $LG['country.208'];
		  case '209':return $LG['country.209'];
		  case '210':return $LG['country.210'];
		  case '211':return $LG['country.211'];
		  case '212':return $LG['country.212'];
		  case '213':return $LG['country.213'];
		  case '214':return $LG['country.214'];
		  case '215':return $LG['country.215'];
		  case '216':return $LG['country.216'];
		  case '217':return $LG['country.217'];
		  case '218':return $LG['country.218'];
		  case '219':return $LG['country.219'];
		  case '220':return $LG['country.220'];
		  case '221':return $LG['country.221'];
		  case '222':return $LG['country.222'];
		  case '223':return $LG['country.223'];
		  case '224':return $LG['country.224'];
		  case '225':return $LG['country.225'];
		  case '226':return $LG['country.226'];
		  case '227':return $LG['country.227'];
		  case '228':return $LG['country.228'];
		  case '229':return $LG['country.229'];
		  case '230':return $LG['country.230'];
		  case '231':return $LG['country.231'];
		  case '232':return $LG['country.232'];
		  case '233':return $LG['country.233'];
		  case '234':return $LG['country.234'];
		  case '235':return $LG['country.235'];
		  case '236':return $LG['country.236'];
		  case '237':return $LG['country.237'];
		  case '238':return $LG['country.238'];
		  case '239':return $LG['country.239'];
		  case '240':return $LG['country.240'];
		  case '241':return $LG['country.241'];
		  case '242':return $LG['country.242'];
		  case '243':return $LG['country.243'];
		  case '244':return $LG['country.244'];
		  case '245':return $LG['country.245'];
		  case '246':return $LG['country.246'];
		  case '247':return $LG['country.247'];
		  case '248':return $LG['country.248'];
		  case '249':return $LG['country.249'];
		  case '250':return $LG['country.250'];
		  case '251':return $LG['country.251'];
		  case '252':return $LG['country.252'];
		  case '253':return $LG['country.253'];
		  case '254':return $LG['country.254'];
		  case '255':return $LG['country.255'];
		  case '256':return $LG['country.256'];
		  case '257':return $LG['country.257'];
		  case '258':return $LG['country.258'];
		  case '259':return $LG['country.259'];
		  case '299':return $LG['country.299'];
		  case '301':return $LG['country.301'];
		  case '302':return $LG['country.302'];
		  case '303':return $LG['country.303'];
		  case '304':return $LG['country.304'];
		  case '305':return $LG['country.305'];
		  case '306':return $LG['country.306'];
		  case '307':return $LG['country.307'];
		  case '308':return $LG['country.308'];
		  case '309':return $LG['country.309'];
		  case '310':return $LG['country.310'];
		  case '311':return $LG['country.311'];
		  case '312':return $LG['country.312'];
		  case '313':return $LG['country.313'];
		  case '314':return $LG['country.314'];
		  case '315':return $LG['country.315'];
		  case '316':return $LG['country.316'];
		  case '318':return $LG['country.318'];
		  case '320':return $LG['country.320'];
		  case '321':return $LG['country.321'];
		  case '322':return $LG['country.322'];
		  case '323':return $LG['country.323'];
		  case '324':return $LG['country.324'];
		  case '325':return $LG['country.325'];
		  case '326':return $LG['country.326'];
		  case '327':return $LG['country.327'];
		  case '328':return $LG['country.328'];
		  case '329':return $LG['country.329'];
		  case '330':return $LG['country.330'];
		  case '331':return $LG['country.331'];
		  case '334':return $LG['country.334'];
		  case '335':return $LG['country.335'];
		  case '336':return $LG['country.336'];
		  case '337':return $LG['country.337'];
		  case '338':return $LG['country.338'];
		  case '339':return $LG['country.339'];
		  case '340':return $LG['country.340'];
		  case '343':return $LG['country.343'];
		  case '344':return $LG['country.344'];
		  case '347':return $LG['country.347'];
		  case '349':return $LG['country.349'];
		  case '350':return $LG['country.350'];
		  case '351':return $LG['country.351'];
		  case '352':return $LG['country.352'];
		  case '353':return $LG['country.353'];
		  case '354':return $LG['country.354'];
		  case '355':return $LG['country.355'];
		  case '356':return $LG['country.356'];
		  case '357':return $LG['country.357'];
		  case '358':return $LG['country.358'];
		  case '359':return $LG['country.359'];
		  case '399':return $LG['country.399'];
		  case '401':return $LG['country.401'];
		  case '402':return $LG['country.402'];
		  case '403':return $LG['country.403'];
		  case '404':return $LG['country.404'];
		  case '405':return $LG['country.405'];
		  case '406':return $LG['country.406'];
		  case '408':return $LG['country.408'];
		  case '409':return $LG['country.409'];
		  case '410':return $LG['country.410'];
		  case '411':return $LG['country.411'];
		  case '412':return $LG['country.412'];
		  case '413':return $LG['country.413'];
		  case '414':return $LG['country.414'];
		  case '415':return $LG['country.415'];
		  case '416':return $LG['country.416'];
		  case '417':return $LG['country.417'];
		  case '418':return $LG['country.418'];
		  case '419':return $LG['country.419'];
		  case '420':return $LG['country.420'];
		  case '421':return $LG['country.421'];
		  case '422':return $LG['country.422'];
		  case '423':return $LG['country.423'];
		  case '424':return $LG['country.424'];
		  case '425':return $LG['country.425'];
		  case '426':return $LG['country.426'];
		  case '427':return $LG['country.427'];
		  case '428':return $LG['country.428'];
		  case '429':return $LG['country.429'];
		  case '430':return $LG['country.430'];
		  case '431':return $LG['country.431'];
		  case '432':return $LG['country.432'];
		  case '433':return $LG['country.433'];
		  case '434':return $LG['country.434'];
		  case '435':return $LG['country.435'];
		  case '436':return $LG['country.436'];
		  case '437':return $LG['country.437'];
		  case '438':return $LG['country.438'];
		  case '439':return $LG['country.439'];
		  case '440':return $LG['country.440'];
		  case '441':return $LG['country.441'];
		  case '442':return $LG['country.442'];
		  case '443':return $LG['country.443'];
		  case '444':return $LG['country.444'];
		  case '445':return $LG['country.445'];
		  case '446':return $LG['country.446'];
		  case '447':return $LG['country.447'];
		  case '448':return $LG['country.448'];
		  case '449':return $LG['country.449'];
		  case '499':return $LG['country.499'];
		  case '501':return $LG['country.501'];
		  case '502':return $LG['country.502'];
		  case '503':return $LG['country.503'];
		  case '504':return $LG['country.504'];
		  case '599':return $LG['country.599'];
		  case '601':return $LG['country.601'];
		  case '602':return $LG['country.602'];
		  case '603':return $LG['country.603'];
		  case '604':return $LG['country.604'];
		  case '605':return $LG['country.605'];
		  case '606':return $LG['country.606'];
		  case '607':return $LG['country.607'];
		  case '608':return $LG['country.608'];
		  case '609':return $LG['country.609'];
		  case '610':return $LG['country.610'];
		  case '611':return $LG['country.611'];
		  case '612':return $LG['country.612'];
		  case '613':return $LG['country.613'];
		  case '614':return $LG['country.614'];
		  case '615':return $LG['country.615'];
		  case '616':return $LG['country.616'];
		  case '617':return $LG['country.617'];
		  case '618':return $LG['country.618'];
		  case '619':return $LG['country.619'];
		  case '620':return $LG['country.620'];
		  case '621':return $LG['country.621'];
		  case '622':return $LG['country.622'];
		  case '623':return $LG['country.623'];
		  case '625':return $LG['country.625'];
		  case '699':return $LG['country.699'];
		  case '701':return $LG['country.701'];
		  case '702':return $LG['country.702'];
		  case '999':return $LG['country.999'];
		
		}
		
	}	
}


//Country的下拉选项
function Country_option($code,$zhi,$lx,$openCountry)
{
	global $LG;
	if($lx==1||!$openCountry||have($openCountry,$code,1)){$selected=$zhi&&in_array($code,$zhi)?'selected':''; echo '<option value="'.$code.'" '.$selected.'>'.$LG['country.'.$code].'</option>';}
}











//____________________________手机国家区号__________________________________
function mobileCountry($zhi,$lx='')
{
	require($_SERVER['DOCUMENT_ROOT'].'/public/global.php');
	if($lx){
		$selected=$zhi=='86'?'selected':''; echo '<option value="86" '.$selected.'>'.$LG['country.142'].'</option>';
		$selected=$zhi=='852'?'selected':''; echo '<option value="852" '.$selected.'>'.$LG['country.110'].'</option>';
		$selected=$zhi=='853'?'selected':''; echo '<option value="853" '.$selected.'>'.$LG['country.121'].'</option>';
		$selected=$zhi=='886'?'selected':''; echo '<option value="886" '.$selected.'>'.$LG['country.143'].'</option>';
		$selected=$zhi=='1'?'selected':''; echo '<option value="1" '.$selected.'>'.$LG['country.502'].'/'.$LG['country.501'].'</option>';
		$selected=$zhi=='81'?'selected':''; echo '<option value="81" '.$selected.'>'.$LG['country.116'].'</option>';
		$selected=$zhi=='82'?'selected':''; echo '<option value="82" '.$selected.'>'.$LG['country.133'].'</option>';
		$selected=$zhi=='49'?'selected':''; echo '<option value="49" '.$selected.'>'.$LG['country.304'].'</option>';
		$selected=$zhi=='39'?'selected':''; echo '<option value="39" '.$selected.'>'.$LG['country.307'].'</option>';
		$selected=$zhi=='44'?'selected':''; echo '<option value="44" '.$selected.'>'.$LG['country.303'].'</option>';
		$selected=$zhi=='61'?'selected':''; echo '<option value="61" '.$selected.'>'.$LG['country.601'].'</option>';
		$selected=$zhi=='7'?'selected':''; echo '<option value="7" '.$selected.'>'.$LG['country.344'].'</option>';
		$selected=$zhi=='33'?'selected':''; echo '<option value="33" '.$selected.'>'.$LG['country.305'].'</option>';
		$selected=$zhi=='31'?'selected':''; echo '<option value="31" '.$selected.'>'.$LG['country.309'].'</option>';
		$selected=$zhi=='84'?'selected':''; echo '<option value="84" '.$selected.'>'.$LG['country.141'].'</option>';
//以下少用,未启用
/*		
		$selected=$zhi=='46'?'selected':''; echo '<option value="46" '.$selected.'>'.$LG['country.330'].'</option>';
		$selected=$zhi=='41'?'selected':''; echo '<option value="41" '.$selected.'>'.$LG['country.331'].'</option>';
		$selected=$zhi=='355'?'selected':''; echo '<option value="355" '.$selected.'>'.$LG['country.313'].'</option>';
		$selected=$zhi=='213'?'selected':''; echo '<option value="213" '.$selected.'>'.$LG['country.201'].'</option>';
		$selected=$zhi=='93'?'selected':''; echo '<option value="93" '.$selected.'>'.$LG['country.101'].'</option>';
		$selected=$zhi=='54'?'selected':''; echo '<option value="54" '.$selected.'>'.$LG['country.402'].'</option>';
		$selected=$zhi=='971'?'selected':''; echo '<option value="971" '.$selected.'>'.$LG['country.138'].'</option>';
		$selected=$zhi=='968'?'selected':''; echo '<option value="968" '.$selected.'>'.$LG['country.126'].'</option>';
		$selected=$zhi=='994'?'selected':''; echo '<option value="994" '.$selected.'>'.$LG['country.339'].'</option>';
		$selected=$zhi=='20'?'selected':''; echo '<option value="20" '.$selected.'>'.$LG['country.215'].'</option>';
		$selected=$zhi=='251'?'selected':''; echo '<option value="251" '.$selected.'>'.$LG['country.217'].'</option>';
		$selected=$zhi=='353'?'selected':''; echo '<option value="353" '.$selected.'>'.$LG['country.306'].'</option>';
		$selected=$zhi=='372'?'selected':''; echo '<option value="372" '.$selected.'>'.$LG['country.334'].'</option>';
		$selected=$zhi=='376'?'selected':''; echo '<option value="376" '.$selected.'>'.$LG['country.314'].'</option>';
		$selected=$zhi=='244'?'selected':''; echo '<option value="244" '.$selected.'>'.$LG['country.202'].'</option>';
		$selected=$zhi=='1268'?'selected':''; echo '<option value="1268" '.$selected.'>'.$LG['country.401'].'</option>';
		$selected=$zhi=='43'?'selected':''; echo '<option value="43" '.$selected.'>'.$LG['country.315'].'</option>';
		$selected=$zhi=='1246'?'selected':''; echo '<option value="1246" '.$selected.'>'.$LG['country.405'].'</option>';
		$selected=$zhi=='675'?'selected':''; echo '<option value="675" '.$selected.'>'.$LG['country.611'].'</option>';
		$selected=$zhi=='1242'?'selected':''; echo '<option value="1242" '.$selected.'>'.$LG['country.404'].'</option>';
		$selected=$zhi=='92'?'selected':''; echo '<option value="92" '.$selected.'>'.$LG['country.127'].'</option>';
		$selected=$zhi=='595'?'selected':''; echo '<option value="595" '.$selected.'>'.$LG['country.433'].'</option>';
		$selected=$zhi=='973'?'selected':''; echo '<option value="973" '.$selected.'>'.$LG['country.102'].'</option>';
		$selected=$zhi=='507'?'selected':''; echo '<option value="507" '.$selected.'>'.$LG['country.432'].'</option>';
		$selected=$zhi=='55'?'selected':''; echo '<option value="55" '.$selected.'>'.$LG['country.410'].'</option>';
		$selected=$zhi=='375'?'selected':''; echo '<option value="375" '.$selected.'>'.$LG['country.340'].'</option>';
		$selected=$zhi=='1441'?'selected':''; echo '<option value="1441" '.$selected.'>'.$LG['country.504'].'</option>';
		$selected=$zhi=='359'?'selected':''; echo '<option value="359" '.$selected.'>'.$LG['country.316'].'</option>';
		$selected=$zhi=='229'?'selected':''; echo '<option value="229" '.$selected.'>'.$LG['country.203'].'</option>';
		$selected=$zhi=='32'?'selected':''; echo '<option value="32" '.$selected.'>'.$LG['country.301'].'</option>';
		$selected=$zhi=='354'?'selected':''; echo '<option value="354" '.$selected.'>'.$LG['country.322'].'</option>';
		$selected=$zhi=='591'?'selected':''; echo '<option value="591" '.$selected.'>'.$LG['country.408'].'</option>';
		$selected=$zhi=='1787'?'selected':''; echo '<option value="1787" '.$selected.'>'.$LG['country.435'].'</option>';
		$selected=$zhi=='48'?'selected':''; echo '<option value="48" '.$selected.'>'.$LG['country.327'].'</option>';
		$selected=$zhi=='267'?'selected':''; echo '<option value="267" '.$selected.'>'.$LG['country.204'].'</option>';
		$selected=$zhi=='501'?'selected':''; echo '<option value="501" '.$selected.'>'.$LG['country.406'].'</option>';
		$selected=$zhi=='226'?'selected':''; echo '<option value="226" '.$selected.'>'.$LG['country.251'].'</option>';
		$selected=$zhi=='257'?'selected':''; echo '<option value="257" '.$selected.'>'.$LG['country.205'].'</option>';
		$selected=$zhi=='850'?'selected':''; echo '<option value="850" '.$selected.'>'.$LG['country.109'].'</option>';
		$selected=$zhi=='45'?'selected':''; echo '<option value="45" '.$selected.'>'.$LG['country.302'].'</option>';
		$selected=$zhi=='228'?'selected':''; echo '<option value="228" '.$selected.'>'.$LG['country.248'].'</option>';
		$selected=$zhi=='1890'?'selected':''; echo '<option value="1890" '.$selected.'>'.$LG['country.418'].'</option>';
		$selected=$zhi=='593'?'selected':''; echo '<option value="593" '.$selected.'>'.$LG['country.419'].'</option>';
		$selected=$zhi=='689'?'selected':''; echo '<option value="689" '.$selected.'>'.$LG['country.623'].'</option>';
		$selected=$zhi=='594'?'selected':''; echo '<option value="594" '.$selected.'>'.$LG['country.420'].'</option>';
		$selected=$zhi=='63'?'selected':''; echo '<option value="63" '.$selected.'>'.$LG['country.129'].'</option>';
		$selected=$zhi=='679'?'selected':''; echo '<option value="679" '.$selected.'>'.$LG['country.603'].'</option>';
		$selected=$zhi=='358'?'selected':''; echo '<option value="358" '.$selected.'>'.$LG['country.318'].'</option>';
		$selected=$zhi=='220'?'selected':''; echo '<option value="220" '.$selected.'>'.$LG['country.219'].'</option>';
		$selected=$zhi=='242'?'selected':''; echo '<option value="242" '.$selected.'>'.$LG['country.213'].'</option>';
		$selected=$zhi=='57'?'selected':''; echo '<option value="57" '.$selected.'>'.$LG['country.413'].'</option>';
		$selected=$zhi=='506'?'selected':''; echo '<option value="506" '.$selected.'>'.$LG['country.415'].'</option>';
		$selected=$zhi=='1809'?'selected':''; echo '<option value="1809" '.$selected.'>'.$LG['country.421'].'</option>';
		$selected=$zhi=='995'?'selected':''; echo '<option value="995" '.$selected.'>'.$LG['country.337'].'</option>';
		$selected=$zhi=='53'?'selected':''; echo '<option value="53" '.$selected.'>'.$LG['country.416'].'</option>';
		$selected=$zhi=='592'?'selected':''; echo '<option value="592" '.$selected.'>'.$LG['country.424'].'</option>';
		$selected=$zhi=='327'?'selected':''; echo '<option value="327" '.$selected.'>'.$LG['country.145'].'</option>';
		$selected=$zhi=='509'?'selected':''; echo '<option value="509" '.$selected.'>'.$LG['country.425'].'</option>';
		$selected=$zhi=='599'?'selected':''; echo '<option value="599" '.$selected.'>'.$LG['country.449'].'</option>';
		$selected=$zhi=='504'?'selected':''; echo '<option value="504" '.$selected.'>'.$LG['country.426'].'</option>';
		$selected=$zhi=='253'?'selected':''; echo '<option value="253" '.$selected.'>'.$LG['country.214'].'</option>';
		$selected=$zhi=='331'?'selected':''; echo '<option value="331" '.$selected.'>'.$LG['country.146'].'</option>';
		$selected=$zhi=='224'?'selected':''; echo '<option value="224" '.$selected.'>'.$LG['country.221'].'</option>';
		$selected=$zhi=='233'?'selected':''; echo '<option value="233" '.$selected.'>'.$LG['country.220'].'</option>';
		$selected=$zhi=='241'?'selected':''; echo '<option value="241" '.$selected.'>'.$LG['country.218'].'</option>';
		$selected=$zhi=='855'?'selected':''; echo '<option value="855" '.$selected.'>'.$LG['country.107'].'</option>';
		$selected=$zhi=='420'?'selected':''; echo '<option value="420" '.$selected.'>'.$LG['country.352'].'</option>';
		$selected=$zhi=='263'?'selected':''; echo '<option value="263" '.$selected.'>'.$LG['country.254'].'</option>';
		$selected=$zhi=='237'?'selected':''; echo '<option value="237" '.$selected.'>'.$LG['country.206'].'</option>';
		$selected=$zhi=='974'?'selected':''; echo '<option value="974" '.$selected.'>'.$LG['country.130'].'</option>';
		$selected=$zhi=='1345'?'selected':''; echo '<option value="1345" '.$selected.'>'.$LG['country.411'].'</option>';
		$selected=$zhi=='225'?'selected':''; echo '<option value="225" '.$selected.'>'.$LG['country.223'].'</option>';
		$selected=$zhi=='965'?'selected':''; echo '<option value="965" '.$selected.'>'.$LG['country.118'].'</option>';
		$selected=$zhi=='254'?'selected':''; echo '<option value="254" '.$selected.'>'.$LG['country.224'].'</option>';
		$selected=$zhi=='682'?'selected':''; echo '<option value="682" '.$selected.'>'.$LG['country.602'].'</option>';
		$selected=$zhi=='371'?'selected':''; echo '<option value="371" '.$selected.'>'.$LG['country.335'].'</option>';
		$selected=$zhi=='266'?'selected':''; echo '<option value="266" '.$selected.'>'.$LG['country.255'].'</option>';
		$selected=$zhi=='856'?'selected':''; echo '<option value="856" '.$selected.'>'.$LG['country.119'].'</option>';
		$selected=$zhi=='370'?'selected':''; echo '<option value="370" '.$selected.'>'.$LG['country.336'].'</option>';
		$selected=$zhi=='423'?'selected':''; echo '<option value="423" '.$selected.'>'.$LG['country.323'].'</option>';
		$selected=$zhi=='262'?'selected':''; echo '<option value="262" '.$selected.'>'.$LG['country.237'].'</option>';
		$selected=$zhi=='352'?'selected':''; echo '<option value="352" '.$selected.'>'.$LG['country.308'].'</option>';
		$selected=$zhi=='40'?'selected':''; echo '<option value="40" '.$selected.'>'.$LG['country.328'].'</option>';
		$selected=$zhi=='261'?'selected':''; echo '<option value="261" '.$selected.'>'.$LG['country.227'].'</option>';
		$selected=$zhi=='356'?'selected':''; echo '<option value="356" '.$selected.'>'.$LG['country.324'].'</option>';
		$selected=$zhi=='960'?'selected':''; echo '<option value="960" '.$selected.'>'.$LG['country.123'].'</option>';
		$selected=$zhi=='265'?'selected':''; echo '<option value="265" '.$selected.'>'.$LG['country.228'].'</option>';
		$selected=$zhi=='60'?'selected':''; echo '<option value="60" '.$selected.'>'.$LG['country.122'].'</option>';
		$selected=$zhi=='223'?'selected':''; echo '<option value="223" '.$selected.'>'.$LG['country.229'].'</option>';
		$selected=$zhi=='1670'?'selected':''; echo '<option value="1670" '.$selected.'>'.$LG['country.229'].'</option>';
		$selected=$zhi=='596'?'selected':''; echo '<option value="596" '.$selected.'>'.$LG['country.428'].'</option>';
		$selected=$zhi=='230'?'selected':''; echo '<option value="230" '.$selected.'>'.$LG['country.231'].'</option>';
		$selected=$zhi=='976'?'selected':''; echo '<option value="976" '.$selected.'>'.$LG['country.124'].'</option>';
		$selected=$zhi=='1664'?'selected':''; echo '<option value="1664" '.$selected.'>'.$LG['country.430'].'</option>';
		$selected=$zhi=='880'?'selected':''; echo '<option value="880" '.$selected.'>'.$LG['country.103'].'</option>';
		$selected=$zhi=='51'?'selected':''; echo '<option value="51" '.$selected.'>'.$LG['country.434'].'</option>';
		$selected=$zhi=='95'?'selected':''; echo '<option value="95" '.$selected.'>'.$LG['country.106'].'</option>';
		$selected=$zhi=='373'?'selected':''; echo '<option value="373" '.$selected.'>'.$LG['country.343'].'</option>';
		$selected=$zhi=='212'?'selected':''; echo '<option value="212" '.$selected.'>'.$LG['country.232'].'</option>';
		$selected=$zhi=='377'?'selected':''; echo '<option value="377" '.$selected.'>'.$LG['country.325'].'</option>';
		$selected=$zhi=='258'?'selected':''; echo '<option value="258" '.$selected.'>'.$LG['country.233'].'</option>';
		$selected=$zhi=='52'?'selected':''; echo '<option value="52" '.$selected.'>'.$LG['country.429'].'</option>';
		$selected=$zhi=='264'?'selected':''; echo '<option value="264" '.$selected.'>'.$LG['country.234'].'</option>';
		$selected=$zhi=='27'?'selected':''; echo '<option value="27" '.$selected.'>'.$LG['country.244'].'</option>';
		$selected=$zhi=='674'?'selected':''; echo '<option value="674" '.$selected.'>'.$LG['country.606'].'</option>';
		$selected=$zhi=='977'?'selected':''; echo '<option value="977" '.$selected.'>'.$LG['country.125'].'</option>';
		$selected=$zhi=='505'?'selected':''; echo '<option value="505" '.$selected.'>'.$LG['country.431'].'</option>';
		$selected=$zhi=='227'?'selected':''; echo '<option value="227" '.$selected.'>'.$LG['country.235'].'</option>';
		$selected=$zhi=='234'?'selected':''; echo '<option value="234" '.$selected.'>'.$LG['country.236'].'</option>';
		$selected=$zhi=='47'?'selected':''; echo '<option value="47" '.$selected.'>'.$LG['country.326'].'</option>';
		$selected=$zhi=='351'?'selected':''; echo '<option value="351" '.$selected.'>'.$LG['country.311'].'</option>';
		$selected=$zhi=='503'?'selected':''; echo '<option value="503" '.$selected.'>'.$LG['country.440'].'</option>';
		$selected=$zhi=='232'?'selected':''; echo '<option value="232" '.$selected.'>'.$LG['country.242'].'</option>';
		$selected=$zhi=='221'?'selected':''; echo '<option value="221" '.$selected.'>'.$LG['country.240'].'</option>';
		$selected=$zhi=='357'?'selected':''; echo '<option value="357" '.$selected.'>'.$LG['country.108'].'</option>';
		$selected=$zhi=='248'?'selected':''; echo '<option value="248" '.$selected.'>'.$LG['country.241'].'</option>';
		$selected=$zhi=='966'?'selected':''; echo '<option value="966" '.$selected.'>'.$LG['country.131'].'</option>';
		$selected=$zhi=='239'?'selected':''; echo '<option value="239" '.$selected.'>'.$LG['country.239'].'</option>';
		$selected=$zhi=='1758'?'selected':''; echo '<option value="1758" '.$selected.'>'.$LG['country.437'].'</option>';
		$selected=$zhi=='378'?'selected':''; echo '<option value="378" '.$selected.'>'.$LG['country.329'].'</option>';
		$selected=$zhi=='1784'?'selected':''; echo '<option value="1784" '.$selected.'>'.$LG['country.439'].'</option>';
		$selected=$zhi=='94'?'selected':''; echo '<option value="94" '.$selected.'>'.$LG['country.134'].'</option>';
		$selected=$zhi=='421'?'selected':''; echo '<option value="421" '.$selected.'>'.$LG['country.353'].'</option>';
		$selected=$zhi=='386'?'selected':''; echo '<option value="386" '.$selected.'>'.$LG['country.350'].'</option>';
		$selected=$zhi=='268'?'selected':''; echo '<option value="268" '.$selected.'>'.$LG['country.257'].'</option>';
		$selected=$zhi=='597'?'selected':''; echo '<option value="597" '.$selected.'>'.$LG['country.441'].'</option>';
		$selected=$zhi=='677'?'selected':''; echo '<option value="677" '.$selected.'>'.$LG['country.613'].'</option>';
		$selected=$zhi=='992'?'selected':''; echo '<option value="992" '.$selected.'>'.$LG['country.147'].'</option>';
		$selected=$zhi=='66'?'selected':''; echo '<option value="66" '.$selected.'>'.$LG['country.136'].'</option>';
		$selected=$zhi=='255'?'selected':''; echo '<option value="255" '.$selected.'>'.$LG['country.247'].'</option>';
		$selected=$zhi=='676'?'selected':''; echo '<option value="676" '.$selected.'>'.$LG['country.614'].'</option>';
		$selected=$zhi=='1809'?'selected':''; echo '<option value="1809" '.$selected.'>'.$LG['country.442'].'</option>';
		$selected=$zhi=='216'?'selected':''; echo '<option value="216" '.$selected.'>'.$LG['country.249'].'</option>';
		$selected=$zhi=='90'?'selected':''; echo '<option value="90" '.$selected.'>'.$LG['country.137'].'</option>';
		$selected=$zhi=='993'?'selected':''; echo '<option value="993" '.$selected.'>'.$LG['country.148'].'</option>';
		$selected=$zhi=='502'?'selected':''; echo '<option value="502" '.$selected.'>'.$LG['country.423'].'</option>';
		$selected=$zhi=='58'?'selected':''; echo '<option value="58" '.$selected.'>'.$LG['country.445'].'</option>';
		$selected=$zhi=='673'?'selected':''; echo '<option value="673" '.$selected.'>'.$LG['country.105'].'</option>';
		$selected=$zhi=='256'?'selected':''; echo '<option value="256" '.$selected.'>'.$LG['country.250'].'</option>';
		$selected=$zhi=='380'?'selected':''; echo '<option value="380" '.$selected.'>'.$LG['country.347'].'</option>';
		$selected=$zhi=='598'?'selected':''; echo '<option value="598" '.$selected.'>'.$LG['country.444'].'</option>';
		$selected=$zhi=='233'?'selected':''; echo '<option value="233" '.$selected.'>'.$LG['country.149'].'</option>';
		$selected=$zhi=='34'?'selected':''; echo '<option value="34" '.$selected.'>'.$LG['country.312'].'</option>';
		$selected=$zhi=='30'?'selected':''; echo '<option value="30" '.$selected.'>'.$LG['country.310'].'</option>';
		$selected=$zhi=='65'?'selected':''; echo '<option value="65" '.$selected.'>'.$LG['country.132'].'</option>';
		$selected=$zhi=='64'?'selected':''; echo '<option value="64" '.$selected.'>'.$LG['country.609'].'</option>';
		$selected=$zhi=='36'?'selected':''; echo '<option value="36" '.$selected.'>'.$LG['country.321'].'</option>';
		$selected=$zhi=='963'?'selected':''; echo '<option value="963" '.$selected.'>'.$LG['country.135'].'</option>';
		$selected=$zhi=='1876'?'selected':''; echo '<option value="1876" '.$selected.'>'.$LG['country.427'].'</option>';
		$selected=$zhi=='374'?'selected':''; echo '<option value="374" '.$selected.'>'.$LG['country.338'].'</option>';
		$selected=$zhi=='967'?'selected':''; echo '<option value="967" '.$selected.'>'.$LG['country.139'].'</option>';
		$selected=$zhi=='964'?'selected':''; echo '<option value="964" '.$selected.'>'.$LG['country.114'].'</option>';
		$selected=$zhi=='972'?'selected':''; echo '<option value="972" '.$selected.'>'.$LG['country.115'].'</option>';
		$selected=$zhi=='91'?'selected':''; echo '<option value="91" '.$selected.'>'.$LG['country.111'].'</option>';
		$selected=$zhi=='62'?'selected':''; echo '<option value="62" '.$selected.'>'.$LG['country.112'].'</option>';
		$selected=$zhi=='962'?'selected':''; echo '<option value="962" '.$selected.'>'.$LG['country.117'].'</option>';
		$selected=$zhi=='260'?'selected':''; echo '<option value="260" '.$selected.'>'.$LG['country.253'].'</option>';
		$selected=$zhi=='235'?'selected':''; echo '<option value="235" '.$selected.'>'.$LG['country.211'].'</option>';
		$selected=$zhi=='350'?'selected':''; echo '<option value="350" '.$selected.'>'.$LG['country.320'].'</option>';
		$selected=$zhi=='56'?'selected':''; echo '<option value="56" '.$selected.'>'.$LG['country.412'].'</option>';
		$selected=$zhi=='236'?'selected':''; echo '<option value="236" '.$selected.'>'.$LG['country.209'].'</option>';
*/		
	}else{
       
		switch($zhi)
        {
            case '':return '';
			case '86':return $LG['country.142'];
			case '852':return $LG['country.110'];
			case '853':return $LG['country.121'];
			case '886':return $LG['country.143'];
			case '1':return $LG['country.502'].'/'.$LG['country.501'];
			case '81':return $LG['country.116'];
			case '82':return $LG['country.133'];
			case '39':return $LG['country.307'];
			case '44':return $LG['country.303'];
			case '49':return $LG['country.304'];
			case '61':return $LG['country.601'];
			case '7':return $LG['country.344'];
			case '33':return $LG['country.305'];
			case '31':return $LG['country.309'];
			case '84':return $LG['country.141'];
/*			
			case '355':return $LG['country.313'];
			case '213':return $LG['country.201'];
			case '93':return $LG['country.101'];
			case '54':return $LG['country.402'];
			case '971':return $LG['country.138'];
			case '968':return $LG['country.126'];
			case '994':return $LG['country.339'];
			case '20':return $LG['country.215'];
			case '251':return $LG['country.217'];
			case '353':return $LG['country.306'];
			case '372':return $LG['country.334'];
			case '376':return $LG['country.314'];
			case '244':return $LG['country.202'];
			case '1268':return $LG['country.401'];
			case '43':return $LG['country.315'];
			case '1246':return $LG['country.405'];
			case '675':return $LG['country.611'];
			case '1242':return $LG['country.404'];
			case '92':return $LG['country.127'];
			case '595':return $LG['country.433'];
			case '973':return $LG['country.102'];
			case '507':return $LG['country.432'];
			case '55':return $LG['country.410'];
			case '375':return $LG['country.340'];
			case '1441':return $LG['country.504'];
			case '359':return $LG['country.316'];
			case '229':return $LG['country.203'];
			case '32':return $LG['country.301'];
			case '354':return $LG['country.322'];
			case '591':return $LG['country.408'];
			case '1787':return $LG['country.435'];
			case '48':return $LG['country.327'];
			case '267':return $LG['country.204'];
			case '501':return $LG['country.406'];
			case '226':return $LG['country.251'];
			case '257':return $LG['country.205'];
			case '850':return $LG['country.109'];
			case '45':return $LG['country.302'];
			case '228':return $LG['country.248'];
			case '1890':return $LG['country.418'];
			case '593':return $LG['country.419'];
			case '689':return $LG['country.623'];
			case '594':return $LG['country.420'];
			case '63':return $LG['country.129'];
			case '679':return $LG['country.603'];
			case '358':return $LG['country.318'];
			case '220':return $LG['country.219'];
			case '242':return $LG['country.213'];
			case '57':return $LG['country.413'];
			case '506':return $LG['country.415'];
			case '1809':return $LG['country.421'];
			case '995':return $LG['country.337'];
			case '53':return $LG['country.416'];
			case '592':return $LG['country.424'];
			case '327':return $LG['country.145'];
			case '509':return $LG['country.425'];
			case '599':return $LG['country.449'];
			case '504':return $LG['country.426'];
			case '253':return $LG['country.214'];
			case '331':return $LG['country.146'];
			case '224':return $LG['country.221'];
			case '233':return $LG['country.220'];
			case '241':return $LG['country.218'];
			case '855':return $LG['country.107'];
			case '420':return $LG['country.352'];
			case '263':return $LG['country.254'];
			case '237':return $LG['country.206'];
			case '974':return $LG['country.130'];
			case '1345':return $LG['country.411'];
			case '225':return $LG['country.223'];
			case '965':return $LG['country.118'];
			case '254':return $LG['country.224'];
			case '682':return $LG['country.602'];
			case '371':return $LG['country.335'];
			case '266':return $LG['country.255'];
			case '856':return $LG['country.119'];
			case '370':return $LG['country.336'];
			case '423':return $LG['country.323'];
			case '262':return $LG['country.237'];
			case '352':return $LG['country.308'];
			case '40':return $LG['country.328'];
			case '261':return $LG['country.227'];
			case '356':return $LG['country.324'];
			case '960':return $LG['country.123'];
			case '265':return $LG['country.228'];
			case '60':return $LG['country.122'];
			case '223':return $LG['country.229'];
			case '1670':return $LG['country.229'];
			case '596':return $LG['country.428'];
			case '230':return $LG['country.231'];
			case '976':return $LG['country.124'];
			case '1664':return $LG['country.430'];
			case '880':return $LG['country.103'];
			case '51':return $LG['country.434'];
			case '95':return $LG['country.106'];
			case '373':return $LG['country.343'];
			case '212':return $LG['country.232'];
			case '377':return $LG['country.325'];
			case '258':return $LG['country.233'];
			case '52':return $LG['country.429'];
			case '264':return $LG['country.234'];
			case '27':return $LG['country.244'];
			case '674':return $LG['country.606'];
			case '977':return $LG['country.125'];
			case '505':return $LG['country.431'];
			case '227':return $LG['country.235'];
			case '234':return $LG['country.236'];
			case '47':return $LG['country.326'];
			case '351':return $LG['country.311'];
			case '46':return $LG['country.330'];
			case '41':return $LG['country.331'];
			case '503':return $LG['country.440'];
			case '232':return $LG['country.242'];
			case '221':return $LG['country.240'];
			case '357':return $LG['country.108'];
			case '248':return $LG['country.241'];
			case '966':return $LG['country.131'];
			case '239':return $LG['country.239'];
			case '1758':return $LG['country.437'];
			case '378':return $LG['country.329'];
			case '1784':return $LG['country.439'];
			case '94':return $LG['country.134'];
			case '421':return $LG['country.353'];
			case '386':return $LG['country.350'];
			case '268':return $LG['country.257'];
			case '597':return $LG['country.441'];
			case '677':return $LG['country.613'];
			case '992':return $LG['country.147'];
			case '66':return $LG['country.136'];
			case '255':return $LG['country.247'];
			case '676':return $LG['country.614'];
			case '1809':return $LG['country.442'];
			case '216':return $LG['country.249'];
			case '90':return $LG['country.137'];
			case '993':return $LG['country.148'];
			case '502':return $LG['country.423'];
			case '58':return $LG['country.445'];
			case '673':return $LG['country.105'];
			case '256':return $LG['country.250'];
			case '380':return $LG['country.347'];
			case '598':return $LG['country.444'];
			case '233':return $LG['country.149'];
			case '34':return $LG['country.312'];
			case '30':return $LG['country.310'];
			case '65':return $LG['country.132'];
			case '64':return $LG['country.609'];
			case '36':return $LG['country.321'];
			case '963':return $LG['country.135'];
			case '1876':return $LG['country.427'];
			case '374':return $LG['country.338'];
			case '967':return $LG['country.139'];
			case '964':return $LG['country.114'];
			case '972':return $LG['country.115'];
			case '91':return $LG['country.111'];
			case '62':return $LG['country.112'];
			case '962':return $LG['country.117'];
			case '260':return $LG['country.253'];
			case '235':return $LG['country.211'];
			case '350':return $LG['country.320'];
			case '56':return $LG['country.412'];
			case '236':return $LG['country.209'];
*/

        }
	}	
}


?>