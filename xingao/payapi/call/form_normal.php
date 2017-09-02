
<?php
if($rs['payid']=='1')
{
?>
<div class="form-group">
  <label class="control-label col-md-2">申请类型</label>
  <div class="col-md-10 has-error">
    <select name="paymethod" payid="paymethod">
      <option value="1" <?=$rs['paymethod']==1?' selected':''?>>即时到帐</option>
      <option value="2" <?=$rs['paymethod']==2?' selected':''?>>担保交易</option>
      <option value="3" <?=$rs['paymethod']==3?' selected':''?>>双接口</option>
    </select>
    <span class="help-block">要选择正确,否则充值错误</span> </div>
</div>
<?php
}
?>













<?php if($rs['payid']=='3'){?>
<div class="form-group">
  <label class="control-label col-md-2">申请类型</label>
  <div class="col-md-10 has-error">
    <select name="paymethod" payid="paymethod">
      <option value="1" <?=$rs['paymethod']==1?' selected':''?>>即时到帐(国内)</option>
      <!--<option value="2" <?=$rs['paymethod']==2?' selected':''?>>担保交易(国内)</option>-->
      <option value="3" <?=$rs['paymethod']==3?' selected':''?>>境外支付宝(国外)</option>
    </select>
    <span class="help-block">要选择正确,否则充值错误</span> </div>
</div>
<?php }?>







<div class="form-group">
  <label class="control-label col-md-2">接口币种</label>
  <div class="col-md-10 has-error">
<?php if($rs['payid']=='6'){?>
    <select name="currency" class="form-control input-medium select2me"  data-placeholder="PayPal国际版支持的币种" required >
      <!--只支持以下种类,自行添加无效-->
      <option value="USD"<?=$rs['currency']=='USD'?' selected':''?>>美元</option>
      <option value="DKK"<?=$rs['currency']=='DKK'?' selected':''?>>丹麦克朗</option>
      <option value="ILS"<?=$rs['currency']=='ILS'?' selected':''?>>以色列新谢克尔</option>
      <option value="RUB"<?=$rs['currency']=='RUB'?' selected':''?>>俄罗斯卢布</option>
      <option value="CAD"<?=$rs['currency']=='CAD'?' selected':''?>>加元</option>
      <option value="HUF"<?=$rs['currency']=='HUF'?' selected':''?>>匈牙利福林</option>
      <option value="MXN"<?=$rs['currency']=='MXN'?' selected':''?>>墨西哥比索</option>
      <option value="NOK"<?=$rs['currency']=='NOK'?' selected':''?>>挪威克朗</option>
      <option value="CZK"<?=$rs['currency']=='CZK'?' selected':''?>>捷克克朗</option>
      <option value="SGD"<?=$rs['currency']=='SGD'?' selected':''?>>新加坡元</option>
      <option value="TWD"<?=$rs['currency']=='TWD'?' selected':''?>>新台币</option>
      <option value="NZD"<?=$rs['currency']=='NZD'?' selected':''?>>新西兰元</option>
      <option value="JPY"<?=$rs['currency']=='JPY'?' selected':''?>>日元</option>
      <option value="EUR"<?=$rs['currency']=='EUR'?' selected':''?>>欧元</option>
      <option value="PLN"<?=$rs['currency']=='PLN'?' selected':''?>>波兰兹罗提</option>
      <option value="THB"<?=$rs['currency']=='THB'?' selected':''?>>泰铢</option>
      <option value="HKD"<?=$rs['currency']=='HKD'?' selected':''?>>港币</option>
      <option value="AUD"<?=$rs['currency']=='AUD'?' selected':''?>>澳元</option>
      <option value="SEK"<?=$rs['currency']=='SEK'?' selected':''?>>瑞典克朗</option>
      <option value="CHF"<?=$rs['currency']=='CHF'?' selected':''?>>瑞士法郎</option>
      <option value="GBP"<?=$rs['currency']=='GBP'?' selected':''?>>英镑</option>
      <option value="PHP"<?=$rs['currency']=='PHP'?' selected':''?>>菲律宾比索</option>
    </select>
    <span class="help-block">
    &raquo; PayPal 国际版不支持人民币充值<br>
    &raquo; PayPal 接口账号设置什么币种，就选择什么币种。 (PayPal设置方法请看操作说明文档)<br>
    </span> 
    
<?php }elseif($rs['payid']=='3'){?>
     <select name="currency" class="form-control input-medium select2me"  data-placeholder="支付宝支持的币种" required >
      <!--只支持以下种类,自行添加无效-->
      <option value="CNY"<?=$rs['currency']=='CNY'?' selected':''?>>人民币(国内支付宝时必选)</option>
      <option value="USD"<?=$rs['currency']=='USD'?' selected':''?>>美元</option>
      <option value="GBP"<?=$rs['currency']=='GBP'?' selected':''?>>英镑</option>
      <option value="HKD"<?=$rs['currency']=='HKD'?' selected':''?>>港币</option>
      <option value="CHF"<?=$rs['currency']=='CHF'?' selected':''?>>瑞士法郎</option>
      <option value="SGD"<?=$rs['currency']=='SGD'?' selected':''?>>新加坡元</option>
      <option value="SEK"<?=$rs['currency']=='SEK'?' selected':''?>>瑞典克朗</option>
      <option value="DKK"<?=$rs['currency']=='DKK'?' selected':''?>>丹麦克朗</option>
      <option value="NOK"<?=$rs['currency']=='NOK'?' selected':''?>>挪威克朗</option>
      <option value="JPY"<?=$rs['currency']=='JPY'?' selected':''?>>日元</option>
      <option value="CAD"<?=$rs['currency']=='CAD'?' selected':''?>>加拿大元</option>
      <option value="AUD"<?=$rs['currency']=='AUD'?' selected':''?>>澳大利亚元</option>
      <option value="EUR"<?=$rs['currency']=='EUR'?' selected':''?>>欧元</option>
      <option value="NZD"<?=$rs['currency']=='NZD'?' selected':''?>>新西兰元</option>
      <option value="RUB"<?=$rs['currency']=='RUB'?' selected':''?>>俄罗斯卢布</option>
      <option value="MOP"<?=$rs['currency']=='MOP'?' selected':''?>>澳门元</option>
    </select>
    <span class="help-block">
    &raquo;   境外支付宝接口账号设置什么币种，就选择什么币种<br>
   <!-- &raquo;   境外支付宝不支持人民币，所以不能选择人民币（如果用人民币请用国内支付宝)<br>-->
    &raquo;   国内支付宝只支持人民币，所以只能选择人民币<br>
   </span> 
<?php }else{?>
     <select name="currency" class="form-control input-medium select2me"  data-placeholder="支持的币种" required >
      <!--只支持以下种类,自行添加无效-->
       <option value="CNY"<?=$rs['currency']=='CNY'?' selected':''?>>人民币</option>
    </select>
<?php }?>
   </div>
</div>








<?php if($rs['payid']=='3'||$rs['payid']=='6'){?>
<div class="form-group">
  <label class="control-label col-md-2">帐号</label>
  <div class="col-md-10 has-error"> 
    <input type="text" class="form-control input-large"  name="payemail" value="<?=cadd($rs['payemail'])?>" required>
  </div>
</div>
<?php }?>


<div class="form-group">
  <label class="control-label col-md-2">
    <?php 
    if($rs['payid']=='3'||$rs['payid']=='4'){echo '合作者身份(parterID)';}
    elseif($rs['payid']=='7'){echo 'MCHID';}
    else{echo '商户号(payid)';}
    ?>
  </label>
  <div class="col-md-10 has-error">
    <input type="text" class="form-control input-large"  maxlength="100" name="payuser" value="<?=cadd($rs['payuser'])?>" required>
  </div>
</div>


<div class="form-group">
  <label class="control-label col-md-2">KEY</label>
  <div class="col-md-10 has-error">
    <input type="text" class="form-control input-large"  maxlength="100" name="paykey" value="<?=cadd($rs['paykey'])?>" required>
  </div>
</div>


<?php if($rs['payid']=='7'){?>
<div class="form-group">
  <label class="control-label col-md-2">APPID</label>
  <div class="col-md-10 has-error">
    <input type="text" class="form-control input-large"  name="payemail" value="<?=cadd($rs['payemail'])?>" required>
  </div>
</div>

<div class="form-group">
  <label class="control-label col-md-2">APPSECRET</label>
  <div class="col-md-10">
    <input type="text" class="form-control input-large"  maxlength="100" name="partner" value="<?=cadd($rs['partner'])?>">
    <span class="help-block">公众帐号secert（仅JSAPI支付的时候需要配置，目前未使用到可留空）</span> 
  </div>
</div>
<?php }?>
