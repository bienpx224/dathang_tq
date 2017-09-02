<div class="form-group">
  <label class="control-label col-md-2">接口币种</label>
  <div class="col-md-10 has-error">
     <select name="currency" class="form-control input-medium select2me" required >
       <!--只支持以下种类,自行添加无效-->
       <option value="JPY" <?=$rs['currency']=='JPY'?' selected':''?>>日币</option>
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
       <input type="checkbox" name="openApi[]" value="unionpay" <?=have($rs['openApi'],'unionpay',1)?'checked':''?>> 银联
       </label>

       <label>
       <input type="checkbox" name="openApi[]" value="alipay" <?=have($rs['openApi'],'alipay',1)?'checked':''?>> 支付宝
       </label>
       
       <label>
       <input type="checkbox" name="openApi[]" value="credit" <?=have($rs['openApi'],'credit',1)?'checked':''?>> 信用卡
       </label>
       
       <label>
       <input type="checkbox" name="openApi[]" value="paypal" <?=have($rs['openApi'],'paypal',1)?'checked':''?>> payPal
       </label>
       
       <label>
       <input type="checkbox" name="openApi[]" value="rakuten" <?=have($rs['openApi'],'rakuten',1)?'checked':''?>> 乐天
       </label>
       
    </div>
    <span class="help-block">以上是SoftBank版含有接口类型，跟外部的同名接口无关系，每种类型都需要跟SoftBank申请（申请成功后再开通）</span>
    
  </div>
</div>


<div class="form-group">
  <label class="control-label col-md-2">ハッシュキー</label>
  <div class="col-md-10 has-error">
    <input type="text" class="form-control input-large"  maxlength="100" name="paykey" value="<?=cadd($rs['paykey'])?>" required>
  </div>
</div>



<div class="form-group">
  <label class="control-label col-md-2">Basic認証ID</label>
  <div class="col-md-10 has-error">
    <input type="text" class="form-control input-large"  maxlength="100" name="payuser" value="<?=cadd($rs['payuser'])?>" required>
  </div>
</div>
