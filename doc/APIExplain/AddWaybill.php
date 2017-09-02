<?php 
/*
软件著作权：=====================================================
软件名称：兴奥国际物流转运网站管理系统(简称：兴奥转运系统)
著作权人：广西兴奥网络科技有限责任公司
软件登记号：2016SR041223
网址：www.xingaowl.com
本系统已在中华人民共和国国家版权局注册，著作权受法律及国际公约保护！
版权所有，未购买严禁使用，未经书面许可严禁开发衍生品，违反将追究法律责任！
*/


//本页面需要是utf-8，请设置utf-8编码
header("Content-Type: text/html; charset=utf-8");

//基本配置
$url='http://xxx/api/yundan/add_save.php';//接口地址：请把xxx改为我们网址
$userid='100000';//会员ID
$key='PYKH6dfzkes4pvvg8xec29py'; //API KEY (如没有请联系客服申请获取)

//运单数据:如果数据不是utf-8，请先转换utf-8
$post_data = array(  
	'userid'=>$userid, 
	'key'=>md5(md5($key).'张三'.'13211111111'),
	
	//字段含意请看说明文档
	'warehouse'=>'17',
	'country'=>'86',
	'channel'=>'3',
	's_name'=>'张三',
	's_mobile_code'=>'86',
	's_mobile'=>'13211111111',
	's_add_shengfen'=>'广西',
	's_add_chengshi'=>'南宁',
	's_add_quzhen'=>'高新区',
	's_add_dizhi'=>'高新路17号',
	's_tel'=>'0771-6753236',
	's_zip'=>'530000',
	
	's_shenfenhaoma'=>'450121111111111111',
	's_shenfenimg_z'=>'https://www.baidu.com/img/baidu_jgylogo1.gif',
	's_shenfenimg_b'=>'https://www.baidu.com/img/baidu_jgylogo2.gif',
	
	'wupin'=>'衣服:::上衣:::A牌:::L:::200:::件:::2:::400:::100|||衣服:::裤子:::B牌:::32码:::300:::条:::2:::600:::100',
	
	'kffs'=>'1',
	'integral_use'=>'1',
	'declarevalue'=>'1000',
	'insureamount'=>'500',
	
	'weightEstimate'=>'5',
	'cc_chang'=>'100',
	'cc_kuan'=>'50',
	'cc_gao'=>'50',
	
	'op_bgfee1'=>'0',
	'op_bgfee2'=>'0',
	'op_wpfee1'=>'0',
	'op_wpfee2'=>'0',
	'op_ydfee1'=>'0',
	'op_ydfee2'=>'0',
	'op_free'=>'1',
	'op_overweight'=>'1',
	
	'f_name'=>'',
	'f_mobile_code'=>'',
	'f_mobile'=>'',
	'f_tel'=>'',
	'f_zip'=>'',
	'f_add_shengfen'=>'',
	'f_add_chengshi'=>'',
	'f_add_quzhen'=>'',
	'f_add_dizhi'=>'',
	
	'content'=>'这是测试用的运单'

);  

//发送
$send=send_post($url,$post_data);//返回结果: 状态|||运单号
if(!is_array($send)&&$send){$send=explode('|||',$send);}//转数组


if($send[0]==1){
	//下运单成功
	//请按业务需要处理-开始----------------------
	
		//执行代码……
		echo $send[1];//运单号
	
	//请按业务需要处理-结束----------------------
}else{
	//下运单失败
	echo '下运单失败，错误代码：'.$send[0];//错误代码含意请看接口说明文档
}




//PHP post发送函数
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
?>