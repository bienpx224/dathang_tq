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
$noper='member_re';require_once($_SERVER['DOCUMENT_ROOT'].'/xingao/incluce/session.php');
$headtitle="转账申请";
require_once($_SERVER['DOCUMENT_ROOT'].'/xingao/incluce/head.php');
if(!$ON_bankAccount){exit ("<script>alert('转账充值系统已关闭！');goBack();</script>");}

//获取,处理
$lx=par($_GET['lx']);
$tfid=par($_GET['tfid']);

if(!$lx){exit ("<script>alert('lx参数错误！');goBack('uc');</script>");}
if($lx=='edit')
{
	if(!$tfid){exit ("<script>alert('tfid{$LG['pptError']}');goBack();</script>");}
	$rs=FeData('transfer','*'," tfid='{$tfid}' ");
	if(spr($rs['status'])==5){exit ("<script>alert('该信息已无效，不能再修改！');goBack('uc');</script>");}
	
	$m_currency=FeData('member','currency',"userid='{$rs['userid']}'");//获取会员币种
	
	if(spr($rs['status'])==1&&$rs['toCurrency']&&$m_currency!=$rs['toCurrency']){exit ("<script>alert('会员账户币种已变更，不能再修改充值！');goBack('uc');</script>");}
	
	if(spr($rs['status'])==1&&(!$bankAccountLock||abs(DateDiff($rs['optime'],time(),'d'))>$bankAccountLock)){exit ("<script>alert('首次处理后已超过{$bankAccountLock}天，不可再修改！');goBack('uc');</script>");}
}

//生成令牌密钥(为安全要放在所有验证的最后面)
$token=new Form_token_Core();
$tokenkey=$token->grante_token('transfer'.$tfid);
?>

<div class="page_ny"> 
  <!-- BEGIN PAGE HEADER-->
	<div class="row"><!--单页时去掉 class="row",不然会有横滚动条,并且form 加 style="margin:20px;"-->
		<div class="col-md-12"> 
<h3 class="page-title"> <a href="javascript:history.go(-1)" title="<?=$LG['back']?>"><i class="icon-reply"></i></a> <a href="list.php" class="gray" target="_parent"><?=$LG['backList']?></a> > <a href="javascript:void(0)" onClick="javascript:window.location.href=window.location.href;" title="<?=$LG['refresh']?>" class="gray">
        <?=$headtitle?>
        </a> </h3>
    </div>
  </div>
  <!-- END PAGE HEADER--> 
  
  <!-- BEGIN PAGE CONTENT-->
  
  <form action="save.php" method="post" class="form-horizontal form-bordered" name="xingao">
    <input name="lx" type="hidden" value="<?=add($lx)?>">
    <input name="tfid" type="hidden" value="<?=$rs['tfid']?>">
    <input name="tokenkey" type="hidden" value="<?=$tokenkey?>">
    <div><!-- class="tabbable tabbable-custom boxless" -->

     <div class="tab-content">
        <div class="tab-pane active" id="tab_1">
          <div class="form">
            <div class="form-body">
              <div class="portlet">
                <div class="portlet-body form" style="display: block;"> 
                  <!--表单内容-->
			  
                  <div class="form-group">
                    <label class="control-label col-md-2"><?=$LG['status']//状态?></label>
                    <div class="col-md-10 has-error">
					  <?php if(spr($rs['status'])==0){?>
                            <select  class="form-control input-small select2me" name="status" data-placeholder="状态" onChange="show_topup()">
                              <?=transfer_Status(spr($rs['status']),1)?>
                            </select>
                        
                            <span class="help-block">
                            &raquo; 注意选择，提交后不可再更改。
                            </span>
                      <?php }else{?>
                          <input type="hidden" name="status" value="<?=spr($rs['status'])?>">
                          <?=transfer_Status(spr($rs['status']))?>
                      <?php }?>
                   </div>
                  </div>
                  <script>
                  function show_topup()
                  {
                      var status=document.getElementsByName('status')[0].value;
                      
                      if(status==1){
                          document.getElementById("show_topup").style.display ='block';
                      }else{
                          document.getElementById("show_topup").style.display ='none';
                      }
                  }
                  
                  $(function(){  
                      show_topup();
                  });
                  </script>












             <div id="show_topup" style="display:none;">
                  <div class="form-group">
                    <label class="control-label col-md-2">转账</label>
                    <div class="col-md-10 has-error">
                    <input type="text" class="form-control input-small" name="fromMoney" value="<?=spr($rs['fromMoney'])?>"  onKeyUp="exchangeTopup();">
                    
                    <select  class="form-control input-small select2me" name="fromCurrency" data-placeholder="转账回单币种" onChange="exchangeTopup();">
                    <?=openCurrency(cadd($rs['fromCurrency']),2)?>
                    </select>
                    <span class="help-block">
                    <?php if($bankAccountLock>0){?>
                     &raquo; 在首次处理后的<?=$bankAccountLock?>天内还可以修改！<?=$rs['optime']>0?'(首次处理'.DateYmd($rs['optime']).')':''?><br>
                     &raquo; 充值后如果会员已经消费，已充金额无法退回，会无法修改！<br>
                    <?php }?>
                      
					<?php 
                    if(!$rs['autoPayStatus']&&$rs['autoPay']&&$rs['fromtable']&&$rs['fromid'])
                    {
                       echo '<font class="red2">&raquo; 提交后自动支付'. fromtableName($rs['fromtable']).':'.cadd($rs['fromid']).' </font><br>';
                    }
                    ?>
                   
                    </span>
                    </div>
                  </div>
                  
                  <div class="form-group">
                    <div class="control-label col-md-2 right">充值</div>
                    <div class="col-md-10">
                      <input type="hidden" name="exchange">
                      <input type="hidden" name="toMoney">
                      <font id="moneyMsg"></font> <?=$m_currency?>
                      
                      <span class="help-block">
                          <?php if($rs['toMoney']>0){?>
                          &raquo; 之前已充值金额<font class="red"><strong><?=spr($rs['toMoney'])?></strong></font><?=$m_currency?><br>
                  	      &raquo; 如果转账币种与会员账户币种不相同，则按系统设置的汇率自动兑换。<br>
                          <?php }?>
                      </span>
                      
                    </div>
                  </div>
                  
                   <div class="form-group">
                    <label class="control-label col-md-2">转账回单编号</label>
                    <div class="col-md-10 has-error">
                    <input type="text" class="form-control input-medium" name="orderNo" value="<?=cadd($rs['orderNo'])?>">
                    <span class="help-block">
                    &raquo; 必须填写正确，用于日后自动判断该转账回单是否有充值过（防止会员用同一张转账回单多次上传重复充值）
                    </span>
                    </div>
                  </div>
                  
                  
             </div>
             
            
                  
                  
                  <div class="form-group">
                    <label class="control-label col-md-2">回复</label>
                    <div class="col-md-10">
                      <textarea  class="form-control" rows="3" name="reply"><?=cadd($rs['reply'])?></textarea>
                    </div>
                  </div>
                  
                  
                  
                  
                  <div class="form-group">&nbsp;</div>
                  

                 
                  <div class="form-group">
                    <div class="control-label col-md-2 right">留言</div>
                    <div class="col-md-10">
                      <?=TextareaToBr($rs['content'])?>
                      
                    </div>
                  </div>
                  
                  <div class="form-group">
                    <div class="control-label col-md-2 right">转账回单</div>
                    <div class="col-md-10">
                     	<a href="/public/ShowImg.php?img=<?=urlencode(cadd($rs['img']))?>" target="_blank" class=" tooltips" data-container="body" data-placement="top" data-original-title="查看图片">
                        <img src="<?=cadd($rs['img'])?>" style="max-width:800px;"/> 
                        </a>
                    </div>
                  </div>

                  
                </div>
              </div>
            </div>
          </div>
        </div>        
        
                
<!--提交按钮固定--> 
<style>body{margin-bottom:50px !important;}</style><!--后台不用隐藏,增高底部高度-->
<div align="center" class="fixed_btn" id="Autohidden">





      <button type="submit" class="btn btn-primary input-small" id="openSmt1" disabled > <i class="icon-ok"></i> <?=$LG['submit']?> </button>
          <button type="reset" class="btn btn-default" style="margin-left:30px;"> <?=$LG['reset']?> </button>
        </div>
      </div>
    </div>
  </form>
</div>

<script type="text/javascript">
//兑换充值金额
function exchangeTopup()
{
	var fromMoney=document.getElementsByName('fromMoney')[0].value;
	var fromCurrency=document.getElementsByName('fromCurrency')[0].value;
	var xmlhttp=createAjax(); 
	if (xmlhttp) 
	{ 
		xmlhttp.open('POST','/public/ajax.php?n='+Math.random(),true); 
		xmlhttp.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
		xmlhttp.send('lx=exchangeTopup&fromMoney='+fromMoney+'&fromCurrency='+fromCurrency+'&toCurrency=<?=$m_currency?>');

		xmlhttp.onreadystatechange=function()
		{  
			if (xmlhttp.readyState==4 && xmlhttp.status==200) 
			{ 
				var arr=unescape(xmlhttp.responseText);
				arr=arr.split(",");//字符串转数组

				var money_old=<?=spr($rs['toMoney'])?>;//已充的金额
				var money=arr[1];//兑换后的金额
				var new_money=money-money_old;
				document.getElementsByName('exchange')[0].value=arr[0];//汇率
				document.getElementsByName('toMoney')[0].value=money;
				
				if(money==-1){
					document.getElementById('moneyMsg').innerHTML='请先填写和选择完整';
				}else if(money_old>0&&new_money>0){
					document.getElementById('moneyMsg').innerHTML='补充差额 '+decimalNumber(new_money,2);
				}else if(money_old>0&&new_money<0){
					document.getElementById('moneyMsg').innerHTML='扣除差额 '+decimalNumber(new_money,2);
				}else{
					document.getElementById('moneyMsg').innerHTML=decimalNumber(new_money,2);
				}
				
			}
		}
	}
}

$(function(){  
	exchangeTopup();
});
</script>


<?php
require_once($_SERVER['DOCUMENT_ROOT'].'/xingao/incluce/foot.php');
?>
