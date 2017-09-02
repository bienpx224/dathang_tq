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
require_once($_SERVER['DOCUMENT_ROOT'].'/xamember/incluce/session.php');
$headtitle=$LG['name.nav_36'];//在线充值
require_once($_SERVER['DOCUMENT_ROOT'].'/xamember/incluce/head.php');

$lx=par($_REQUEST['lx']);
$money=spr($_REQUEST['money']); if($Mcurrency=='JPY'){$money=spr($money,0,1,0,1);}
$content=par($_REQUEST['content']);
$payid=spr($_REQUEST['payid']);
$openApi=par($_REQUEST['openApi']);



if($payid)
{
	$payr=FeData('payapi','*'," payid='{$payid}' and checked=1");
	$exchange=exchange($payr['currency'],$Mcurrency);
}else{
	$ppt=$LG['money.money_cz_14'];//选择充值方式后才能计算
}


if($payid==8)
{
	//SoftBank时需要用https
	$action=str_ireplace('http://','https://',$siteurl).'api/pay/';
}else{
	$action='/api/pay/';
}

//$action='/api/pay/';//测试用
?>

<div class="page_ny"> 
  <!-- BEGIN PAGE HEADER-->
	<div class="row"><!--单页时去掉 class="row",不然会有横滚动条,并且form 加 style="margin:20px;"-->
		<div class="col-md-12"> 
<h3 class="page-title"> <a href="javascript:void(0)" onClick="javascript:window.location.href=window.location.href;" title="<?=$LG['refresh']?>" class="gray">
        <?=$headtitle?>
        </a> </h3>
    </div>
  </div>
  <!-- END PAGE HEADER-->
  
  <div class="tab-content">
    <div class="tab-pane active" id="tab_1">
      <div class="form">
        <div class=""><!--form-body-->
          <div class="portlet">
            <div class="portlet-body article_ny">
              <form action="<?=$action?>" method="post" class="form-horizontal form-bordered" name="xingao" <?=!$lx?'target="_blank"':'' ?>>
                <input name="payid" type="hidden">
                <input name="lx" type="hidden" value="tj">
                <input name="content" type="hidden" value="<?=$content?>">
                <input name="openApi" type="hidden" value="<?=$openApi?>">
                <div>
                  <div class="portlet-body form" style="display: block;"> 
                    <!--表单内容-->

                    <div class="form-group">
                      <div class="control-label col-md-2 right"><?=$LG['money.money_cz_1'];//请先选充值方式?></div>
                      <div class="col-md-10"> 
<?php
//获取支付接口
$query="select payid,payname,currency,openApi from payapi  where checked=1 order by myorder desc,payname desc";
$sql=$xingao->query($query);
while($rs=$sql->fetch_array())
{
	//SoftBank-开始
	if($rs['payid']==8)
	{
		$arr=ToArr($rs['openApi']);
		if($arr)
		{
			foreach($arr as $arrkey=>$value)
			{
				//如要修改排序,在后台表单(form_SoftBank.php)正常修改即可.
				$img=$value; if($value=='alipay'){$img='3';}elseif($value=='paypal'){$img='6';}elseif($value=='unionpay'){$img='unionpay_re';}
				$hover=0;if($rs['payid']==$payid&&$openApi==$value){$hover=1;}
				payButton($value);
			}
		}
	}
	//SoftBank-结束
	
	
	//NihaoPay-开始
	elseif($rs['payid']==9)
	{
		$arr=ToArr($rs['openApi']);
		if($arr)
		{
			foreach($arr as $arrkey=>$value)
			{
				//如要修改排序,在后台表单(form_NihaoPay.php)正常修改即可.
				$img=$value; if($value=='alipay'){$img='3';}elseif($value=='wechatpay'){$img='7';}
				$hover=0;if($rs['payid']==$payid&&$openApi==$value){$hover=1;}
				payButton($value);
			}
		}
	}
	//NihaoPay-结束

	else{
		//通用----------
		$hover=0;if($rs['payid']==$payid){$hover=1;}
		$img=$rs['payid'];
		payButton();
	}
}


//-------------
function payButton($openApi='')
{	
	global $rs,$hover,$img;
?>
    <label class="payimg"  style=" <?=$hover?'border:1px solid #FF6600;':''?>">
    <span><input type="radio" name="payid"  value="<?=$rs['payid']?>" onClick="url(<?=$rs['payid']?>,'<?=$openApi?>');" <?=$hover?'checked':''?> required></span>
    
    <b><img id="a<?=$rs['payid']?>" src="/images/pay_<?=$img?>.jpg" onClick="url(<?=$rs['payid']?>,'<?=$openApi?>');" ></b>
     
    <font><?=$rs['currency']?></font>
    </label>
<?php }?>


<script>
function url(payid,openApi) 
{
  money=document.getElementById('czhb').value;
  location.href='?payid='+payid+'&openApi='+openApi+'&money='+money+'&content=<?=$content?>';
}
</script>                 


<style>
.payimg{ margin-right:20px;width:130px; margin-bottom:20px; }
.payimg img{width:120px;}
.payimg b{display:block; width:100%; height:50px; text-align:center; }
.payimg font{ display:block; width:100%; height:30px; text-align:center; font-size:14px; color:#FF6633}
.payimg span{ display:block; width:100%; height:30px; text-align:center;}
</style>

                      </div>
                    </div>
                    

                    
                    <div class="form-group" style="display:none">
                      <label class="control-label col-md-2"><?=$LG['money.money'];//充值?></label>
                      <div class="col-md-10  has-error">
                        <input  type="text" required class="form-control input-small" id="money" onkeyup="value=value.replace(/[^\d.]/g,'');paymoney1();" maxlength="8"/>
                        <?=$payr['currency']?> 
                        </div>
                    </div>
					
                    <div class="form-group">
                      <div class="control-label col-md-2 right"><?=$LG['money.money'];//充值?></div>
                      <div class="col-md-10 has-error"> 
					  <input type="text" class="form-control input-small" name="money" id="czhb" onkeyup="value=value.replace(/[^\d.]/g,'');paymoney2();" maxlength="12" value="<?=$money?>"/><?=$Mcurrency?>
                      
                      <font class="gray_prompt2">
                        <?php 
						  if($ppt){$ex=$ppt;}else{$ex='1'.$payr['currency'].'='.$exchange.$Mcurrency;}
						  echo LGtag($LG['money.money_cz_17'],'<tag1>=='.$ex);//参考汇率:<tag1> (支付时按接口实际汇率计算)
						 ?>
                      </font> 
                        
                      </div>
                    </div>
                    
                    
                    
                    
                    <?php 
					$zengsong=cadd($member_per[$Mgroupid]['zengsong']);
					if($zengsong){?>
                    <div class="form-group">
                      <div class="control-label col-md-2 right"><?=$LG['money.money_cz_3'];//目前有优惠活动?></div>
                      <div class="col-md-10 red2"> 
						<?php 
						$arr=ToArr($zengsong,1);
						foreach($arr as $arrkey=>$value)
						{
							$line=ToArr($value,2);
							echo LGtag($LG['money.money_cz_4'],
							'<tag1>=='.$line[0].$XAmc.'||'.
							'<tag2>=='.$line[1].$XAmc
						   );
						}
						?>
                      </div>
                    </div>
                    <?php }?>


 
 
 
 
 
                    
<!--SoftBank 信用卡表单-开始-->  
<?php if($payid==8&&$openApi=='credit'){?>  
<div class="form-group">
    <label class="control-label col-md-2"><?=$LG['api.pay_24'];//信用卡号?></label>
    <div class="col-md-2 has-error">
        <input type="text" class="form-control input-medium tooltips" data-container="body" data-placement="top" data-original-title="<?=$LG['api.pay_25'];//所输入的信息都会加密发送,且保证不会做任何记录,请放心输入?>" name="cc_number" required autocomplete="off">
    </div>
    
    <label class="control-label col-md-2"><?=$LG['api.pay_26'];//卡有效期?></label>
    <div class="col-md-2 has-error">
        <input type="text" class="form-control input-small tooltips" data-container="body" data-placement="top" data-original-title="<?=$LG['api.pay_27'];//年月(如:201701)?>" name="cc_expiration" required autocomplete="off">
    </div>
    
    <label class="control-label col-md-2"><?=$LG['api.pay_28'];//卡背3位码?></label>
    <div class="col-md-2 has-error">
        <input type="text" class="form-control input-small tooltips" data-container="body" data-placement="top" data-original-title="<?=$LG['api.pay_29'];//卡背面最后的3位码(如:785)?>" name="security_code" required autocomplete="off">
    </div>
</div>
<?php }?>
<!--SoftBank 信用卡表单-结束-->                     
  
  
  
  
  
  
                   
                    
                    <div class="form-group">
                      <div class="control-label col-md-2 right"></div>
                      <div class="col-md-10"> 
                    
<button type="submit"  class="btn btn-primary input-medium" style="margin-right:30px;"> 
<i class="icon-ok"></i> <?=$LG['money.money_cz_6'];//去充值?> 
</button>

<?php if($ON_bankAccount){?>
<a class="btn btn-success  input-medium" href="/xamember/transfer/form.php"  target="_blank">【<?=$LG['money.money_cz_7']?>】</a>
<?php }?>

                      </div>
                    </div>
                    
                  </div>
                </div>
              </form>
 
 
 
 
 
              
              
              
            </div>
          </div>

        <font class="gray_prompt"> 
            &raquo; <?=$LG['money.money_cz_12']?><a href="<?=ClassData($pay_classid,'path')?>" target="_blank" style="color:#FF6600"><?=$LG['money.money_cz_10'];//其他支付方式?></a><br>
            
            <?php if($member_per[$Mgroupid]['off_tixian']){?>
            &raquo; <?=$LG['money.money_cz_11']?><br>
            <?php }?>
            
            <?php if(spr($payr['payIncMoney'])){?>
            &raquo; 
				<?=LGtag($LG['money.money_cz_13'],
                    '<tag1>=='.spr($payr['payIncMoney']).$payr['currency']
                 );?>
            <br>
            <?php }?>
        </font> 

        </div>
      </div>
    </div>
  </div>
</div>
<script>
function paymoney1()
{
	var amount=document.getElementById("money").value
	var fen;
	
	<?php if($payr['currency']=='JPY'){?>
	if(amount.indexOf(".")>=0){
		amount=Math.ceil(amount);
		document.getElementById("money").value=amount;
		alert('<?=$LG['money.money_cz_16']//该接口币种不支持小数点?>');//需要用弹出形式,防止会员不看屏幕直接输入提交
	}
	<?php }?>
	
	fen=amount*<?=$exchange?>;
	document.getElementById("czhb").value=decimalNumber(fen,2);
}


function paymoney2()
{
	var amount=document.getElementById("czhb").value
	var fen;
	fen=amount/<?=$exchange?>;
	var money=decimalNumber(fen,2);
	
	<?php if($payr['currency']=='JPY'){?>	money=Math.ceil(money);	<?php }?>
	document.getElementById("money").value=money;

}
$(function(){ paymoney2(); });
</script>





<!--弹窗口-开始:触发事件在提交处理页执行-->
<a data-toggle="modal" data-target="#payModal"  href="#payModal" id="clickModal"></a> 
<div class="modal fade" tabindex="-1" id="payModal" data-backdrop="static" data-keyboard="false">    
  <div class="modal-dialog modal-wide">
     <div class="modal-content">
        <div class="modal-header">
           <button type="button" class="close" data-dismiss="modal" aria-hidden="true" onClick="window.location.href=window.location.href;"></button>
           <h4 class="modal-title"><?=$LG['api.pay_23']//请在新页面进行充值操作?></h4>
        </div>
        <div class="modal-body">
        
<a class="btn btn-success" href="/xamember/yundan/list.php?status=3"><?=$LG['money.money_cz_8'];//充值完成：支付运单费用?></a>
<a class="btn btn-success" href="/xamember/money/money_czbak.php"><?=$LG['money.money_cz_9'];//充值完成：查看充值记录?></a>
          
        </div>
        <div class="modal-footer">
           <button type="button" class="btn btn-danger" data-dismiss="modal"> <?=$LG['api.pay_19_1']//充值失败?> </button>
        </div>
     </div>
  </div>
</div>
<!--弹窗口-结束-->



<?php
require_once($_SERVER['DOCUMENT_ROOT'].'/xamember/incluce/foot.php');
?>
