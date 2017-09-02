<div class="form-group">
  <label class="control-label col-md-2">接口币种</label>
  <div class="col-md-10 has-error">
     <select name="currency" class="form-control input-medium select2me" required >
       <!--只支持以下种类,自行添加无效-->
       <option value="USD" <?=$rs['currency']=='USD'?' selected':''?>>美元</option>
       <option value="JPY" <?=$rs['currency']=='JPY'?' selected':''?>>日币</option>
       <option value="HKD" <?=$rs['currency']=='HKD'?' selected':''?>>港币</option>
       <option value="GBP" <?=$rs['currency']=='GBP'?' selected':''?>>英镑</option>
       <option value="EUR" <?=$rs['currency']=='EUR'?' selected':''?>>欧元</option>
       <option value="CAD" <?=$rs['currency']=='CAD'?' selected':''?>>加拿大元</option>
    </select>
  </div>
</div>

<div class="form-group">
  <label class="control-label col-md-2">开启类型</label>
  <div class="col-md-10">
    <div class="radio-list">
    	<!--参数要全部小写-->
       <!--这里的排序影响到前台的排序-->
       <label>
       <input type="checkbox" name="openApi[]" value="alipay" <?=have($rs['openApi'],'alipay',1)?'checked':''?>> 支付宝
       </label>
       
       <label>
       <input type="checkbox" name="openApi[]" value="wechatpay" <?=have($rs['openApi'],'wechatpay',1)?'checked':''?>> 微信
       </label>
       
       <label>
       <input type="checkbox" name="openApi[]" value="unionpay" <?=have($rs['openApi'],'unionpay',1)?'checked':''?>> 银联
       </label>

     
    </div>
    <span class="help-block">以上是NihaoPay版含有接口类型，跟外部的同名接口无关系</span>
    
  </div>
</div>



<div class="form-group">
  <label class="control-label col-md-2">TOKEN</label>
  <div class="col-md-10 has-error">
    <input type="text" class="form-control input-large"  maxlength="100" name="paykey" value="<?=cadd($rs['paykey'])?>" required>
  </div>
</div>
