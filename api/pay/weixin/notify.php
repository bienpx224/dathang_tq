<?php
/*
注意:原版有问题,需要修改
lib/WxPay.Api.php 414行 修改搜索:修改1
lib/WxPay.Api.php 537行 修改搜索:修改2
*/

ini_set('date.timezone','Asia/Shanghai');
error_reporting(E_ERROR);

require_once($_SERVER['DOCUMENT_ROOT'].'/public/config_top.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/public/function.php');

require_once "lib/WxPay.Api.php";
require_once 'lib/WxPay.Notify.php';
require_once 'log.php';

//初始化日志
//$logHandler= new CLogFileHandler("logs/".date('Y-m-d').'.log');//为防占资源已禁止生成日志文件
//$log = Log::Init($logHandler, 15);//为防占资源已禁止生成日志文件

class PayNotifyCallBack extends WxPayNotify
{
	/*成功返回:业务处理-开始1*/
	public $xingao,$LG,$XAMcurrency,$member_per;
	/*成功返回:业务处理-结束1*/
	
	//查询订单
	public function Queryorder($transaction_id)
	{

		$input = new WxPayOrderQuery();
		$input->SetTransaction_id($transaction_id);
		$result = WxPayApi::orderQuery($input);
		//Log::DEBUG("query:" . json_encode($result));//为防占资源已禁止生成日志文件
		if(array_key_exists("return_code", $result)
			&& array_key_exists("result_code", $result)
			&& $result["return_code"] == "SUCCESS"
			&& $result["result_code"] == "SUCCESS")
		{
			return true;
		}
		return false;
	}
	
	//重写回调处理函数
	public function NotifyProcess($data, &$msg)
	{
		//Log::DEBUG("call back:" . json_encode($data));//为防占资源已禁止生成日志文件
		$notfiyOutput = array();
		
		if(!array_key_exists("transaction_id", $data)){
			$msg = "输入参数不正确";
			return false;
		}
		//查询订单，判断订单真实性
		if(!$this->Queryorder($data["transaction_id"])){
			$msg = "订单查询失败";
			return false;
		}
		
		/*成功返回:业务处理-开始2*/
		global $xingao,$LG,$XAMcurrency,$member_per;
		$ddno =$data['out_trade_no'];
		$money_pay=$data['total_fee']/100;
		$money_yz=1;
		require_once($_SERVER['DOCUMENT_ROOT'].'/api/pay/paySave.php');
		/*成功返回:业务处理-结束2*/

		return true;
	}
}

//Log::DEBUG("begin notify");//为防占资源已禁止生成日志文件
$notify = new PayNotifyCallBack();
$notify->Handle(false);
?>