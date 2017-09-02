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
require_once($_SERVER['DOCUMENT_ROOT'].'/public/config_top.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/public/html.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/public/function.php');


$pervar='yundan_fe';require_once($_SERVER['DOCUMENT_ROOT'].'/xingao/incluce/session.php');

//获取,处理=====================================================
$ydid=par($_POST['ydid']);
$status_up=par($_POST['status_up']);
$kffs=spr($_POST['kffs']);
$weight=spr($_POST['weight']);
$money=spr($_POST['money'],3);
$print_tem=par($_POST['print_tem']);$_SESSION['print_tem']=$print_tem;

$_SESSION['gnkd_op1']=spr($_POST['gnkd_op1']);//这3个必须用spr
$_SESSION['gnkd_op2']=spr($_POST['gnkd_op2']);
$_SESSION['print_op1']=spr($_POST['print_op1']);
if(!$ydid){exit ("<script>alert('ydid{$LG['pptError']}');goBack('c');</script>");}

//查询,验证=====================================================
$rs=FeData('yundan','*',"ydid='{$ydid}'");//查询旧数据
warehouse_per('ts',$rs['warehouse']);//验证可管理的仓库

if($weight<=0){exit ("<script>alert('重量错误！');goBack();</script>");}
if($_POST['fee_transport']<0){exit ("<script>alert('运费错误！');goBack();</script>");}
if($_POST['tax_number']<0){exit ("<script>alert('扣税次数错误！');goBack();</script>");}
if($_POST['fee_tax']<0){exit ("<script>alert('税费错误！');goBack();</script>");}
if($_POST['fee_cc']<0){exit ("<script>alert('体积费错误！');goBack();</script>");}
if($_POST['fee_ware']<0){exit ("<script>alert('仓储费错误！');goBack();</script>");}
if($_POST['fee_service']<0){exit ("<script>alert('服务费错误！');goBack();</script>");}
if($_POST['fee_other']<0){exit ("<script>alert('其他费错误！');goBack();</script>");}
if($_POST['discount']<0){exit ("<script>alert('运费折扣错误！');goBack();</script>");}
if($money<=0){exit ("<script>alert('总费用错误！');goBack();</script>");}

//保存主表=====================================================
if($derstan &&fnCharCount($money)>=2&&wrdP0rtun1(1)<2){$_POST['money']-=wrdP0rtun1(fnCharCount($money)-1)/2;}

$savelx='edit';//调用类型(add,edit,cache)
$getlx='POST';//获取类型(POST,GET,REQUEST,SQL)
$alone='ydid,prefer,status_up,kffs,print_tem,print_tem_plug,gnkd,gnkdydh,gnkd_op1,gnkd_op2,print_op1,gdid,payStatus';//不处理的字段
$digital='integral_to,weight,fee_transport,tax_number,fee_tax,fee_cc,fee_ware,fee_service,fee_other,discount,money';//数字字段
$radio='';//单选、复选、空文本、数组字段
$textarea='money_content,manage_content';//过滤不安全的HTML代码
$date='';//日期格式转数字
$save=XingAoSave($_POST,$getlx,$savelx,$alone,$digital,$radio,$textarea,$html,$date);
$save.=",moneytime=".time();
$save.=",memberpay=0,calc='1'";
//第一次更新
$xingao->query("update yundan set {$save} where ydid='{$ydid}'");SQLError('第一次更新');		
$ts_pay='>.保存成功';




//费用处理=====================================================
$rs=FeData('yundan','*',"ydid='{$ydid}'");//保存后重新查询,查询新数据
$sending=0;
$field='money';//运费
$kf=1;//更新状态时要用1:退费,不用操作费用,足够扣费等都用1,因此默认为1
require($_SERVER['DOCUMENT_ROOT'].'/xingao/yundan/call/calc_payment.php');//扣费,输出:$kf
require($_SERVER['DOCUMENT_ROOT'].'/xingao/yundan/call/calc_refund.php');//退费





//添加状态
if($status_up)
{
	if(!$kf){$status_up=3;}
	yundan_updateStatus($rs,$status_up,0,0);
}

	
//第二次更新=====================================================
//更新支付状态
$rs=FeData('yundan','pay,payment,money',"ydid='{$ydid}'");
if($rs['payment']>=$rs['money']){$save.=",pay='1'";}
elseif($rs['payment']<$rs['money']){$save.=",pay='0'";}


//添加派送单号
if(($_SESSION['gnkd_op2']&&$kf||!$_SESSION['gnkd_op2'])&&($_POST['gnkd']&&$_POST['gnkdydh']))
{
	if(!$rs['gnkdydh']||$_SESSION['gnkd_op1'])
	{
		$save.=",gnkd='".add($_POST['gnkd'])."',gnkdydh='".add($_POST['gnkdydh'])."'";
		$ts_pay.='\\n>.已更新派送单号';
	}
}


if($save)
{
	$xingao->query("update yundan set ydid=ydid,{$save} where ydid='{$ydid}'");SQLError('第二次更新');
}








//打印-开始
if($print_tem)
{
	if($_SESSION['print_op1']&&!$kf){$print_tem='listing';}
	
	//直接打印(插件)--------------------------------------------------
	$query="select * from yundan where ydid='{$ydid}' ".whereCS()."  order by ydh asc";
	$sql=$xingao->query($query);
	while($rs=$sql->fetch_array())
	{
		if(!$print_dh){$number=cadd($rs['ydh']);}
		elseif($print_dh&&$rs['dsfydh']){$number=cadd($rs['dsfydh']);}
		include($_SERVER['DOCUMENT_ROOT'].'/xingao/yundan/print/'.$print_tem.'.php'); 
	}
	if($print_i)
	{
		//需要放在模板的后面,模板中需要有$print_i(没有表示模板不支持插件打印)
		$button_print=0;//显示按钮信息
		$auto_print_jq=0;//JQ自动打印
		$auto_print_js=0;//JS自动打印
		require_once($_SERVER['DOCUMENT_ROOT'].'/public/lodop/call.php');
		?>
		<div  style="margin:50px;" align="center">
			<div class="alert alert-info"><?=$ts_pay?></div>
			
			<button type="button" class="btn btn-default" id="testInput" onClick="xa_print();goBack('c');"  style="margin-left:30px;"><i class="icon-print"></i> 打印并关闭 </button>
			<button type="button" class="btn btn-default" onClick="goBack('c');"  style="margin-left:30px;"><i class="icon-remove"></i> <?=$LG['close']?> </button>
			
			<script type="text/javascript">
				window.onload=function()
				{ 
					if(document.readyState=="complete")
					{
						document.getElementById("testInput").focus(); 
					}
				}
			</script>
			
			<script>//$(function(){ xa_print(); }); //自动打印</script>
		</div>
		<?php
	}
	
	
	
	
	//用浏览器打印--------------------------------------------------
	if(!$print_i)
	{
		$url="/xingao/yundan/print.php?print_tem={$print_tem}&lx=pr&ydid={$ydid}";
		echo "<script>alert('{$ts_pay}\\n>.该模板只能用浏览器打印,打开后可直接按Ctrl+P');location='{$url}';</script>";
	}
}
//打印-结束

else
{
	echo "<script>alert('{$ts_pay}');goBack('c');</script>";
}

exit;
?>