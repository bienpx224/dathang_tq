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
$alonepage=1;//单页形式
require_once($_SERVER['DOCUMENT_ROOT'].'/xamember/incluce/head.php');
if(!$ON_gd_mosuda_apply){exit ("<script>alert('{$LG['pptClose']}');goBack();</script>");}


//++++++++会员申请备案++++++++

//获取,处理-----------------------------------------------------------------------------------------------
$lx=par($_GET['lx']);
$gdid=par($_GET['gdid']);
if(!$lx){$lx='add';}

if($gdid){$rs=FeData('gd_mosuda','*',"gdid='{$gdid}' {$Mmy}");}

//生成令牌密钥(为安全要放在所有验证的最后面)
$token=new Form_token_Core();
$tokenkey= $token->grante_token("goodsdata".$gdid);
?>
<style>html{overflow-x:hidden;}</style>
<!----------------------------------------显示表单-开始------------------------------------------------>
<form action="/xamember/gd_mosuda//save.php" method="post" class="form-horizontal form-bordered" name="xingao" style="width:620px;">
    <input name="lx" type="hidden" value="<?=add($lx)?>">
    <input name="gdid" type="hidden" value="<?=$gdid?>">
    <input name="tokenkey" type="hidden" value="<?=$tokenkey?>">
    
    <div class="form-group">
    <label class="control-label col-md-2"><?=$LG['mall_order.form_23'];//品名?></label>
    <div class="col-md-10 has-error">
    <input name="name" type="text" class="form-control input-medium"  value="<?=cadd($rs['name'])?>" required/>
    </div>
    </div>

    <div class="form-group">
    <label class="control-label col-md-2"><?=$LG['brand'];//品牌?></label>
    <div class="col-md-10 has-error">
    <input name="brand" type="text" class="form-control input-medium"  value="<?=cadd($rs['brand'])?>" required/>
    </div>
    </div>				  

    <div class="form-group">
    <label class="control-label col-md-2"><?=$LG['mall_order.Xcall_money_payment_9'];//单价?></label>
    <div class="col-md-10 has-error">
    <input name="price" type="text" class="form-control input-small tooltips" data-container="body" data-placement="top" data-original-title="<?=$LG['gd_mosuda.1'];//请兑为CNY币种?>"  value="<?=spr($rs['price'],2,0)?>" required/>CNY
    </div>
    </div>				  

    <div class="form-group">
    <label class="control-label col-md-2"><?=$LG['gd_mosuda.url'];//购买链接?></label>
    <div class="col-md-10 has-error">
    <input name="url" type="text" class="form-control input-medium"  value="<?=cadd($rs['url'])?>" required/>
    </div>
    </div>				  

    <div align="center"><button type="submit"  class="btn btn-primary input-xmedium" style=" margin-top:10px; margin-bottom:10px;"> <i class="icon-ok"></i> <?=$LG['gd_mosuda.2'];//提交申请备案?> </button></div>
    
    <span class="help-block">&raquo; <?=$LG['gd_mosuda.3'];//由于备案审核严格，以上必须填写正确且完整，否则备案无法通过！?></span>
</form>


<!----------------------------------------显示表单-结束------------------------------------------------>
<?php require_once($_SERVER['DOCUMENT_ROOT'].'/xamember/incluce/foot.php');?>
