             <!--表单内容-开始-->
            <div class="form-group">
              <label class="control-label col-md-2"><?=$LG['number'];//数量?></label>
              <div class="col-md-2 has-error">
                <input type="text" class="form-control input-small" name="number" value="<?=spr($rs['number'],0,0)?>" onKeyUp="totalPrice();" onBlur="limitNumber(this,'1,1000000','0');" required>
              </div>
              
              <label class="control-label col-md-2"><?=$LG['mall_order.Xcall_money_payment_9'];//单价?></label>
              <div class="col-md-6 has-error">
                <input type="text" class="form-control input-small" name="price" style="float:left;" value="<?=spr($_POST['price'].$rs['price'],2,0)?>" onKeyUp="totalPrice();" onBlur="limitNumber(this,'0.01,1000000','2');"  required>
              <script>document.writeln(parent.document.getElementsByName('priceCurrency')[0].value)</script>
               
              
               <font style="margin-left:20px;">
                <?=$LG['totalPrice']//总价?><font class="show_price" id="totalPrice_msg"><?=spr($rs['number']*$rs['price'])?></font>
                </font>
				
                
                
                 
                
              <?php if($typ=='edit'&&$callFrom=='manage'&&$rs['pay']){?>
              <span class="help-block"><?=$LG['daigou.109'];//变更品牌、数量、单价需要补款/退款；变更币种不用补款/退款；?></span>
              <?php }?>
              
              
              
              
              
              
               </div>
            </div>
            
            
            
            <div class="form-group">
              <label class="control-label col-md-2"><?=$LG['front.86'];//颜色?></label>
              <div class="col-md-4">
                <select name="color" class="form-control select2me input-small"  data-placeholder="<?=$LG['pleaseSelect'];//请选择?>" onChange="inputOther('color');" style="float:left;">
                   <option value="Other" <?=$rs['color']==0?'selected':''?>><?=$LG['daigou.111'];//自填?></option>
                 <?php  ClassifyAll(8,$rs['color'],0)?>
                </select><!--默认不是自填:<?=$rs['color']==0&&$rs['colorOther']?'selected':''?> -->
                
                <input type="text" class="form-control input-small" name="colorOther" value="<?=cadd($rs['colorOther'])?>" style="float:left;<?=$rs['color']==0?'':'display:none;'?>"><!--默认不是自填:<?=$rs['color']==0&&$rs['colorOther']?'':'display:none;'?> -->
                
                <a href="javascript:void(0)" class=" popovers" data-trigger="hover" data-placement="top"  data-content="<?=$LG['daigou.113']?>"><i class="icon-info-sign"></i></a>
              </div>
              
              
              <label class="control-label col-md-2"><?=$LG['front.85'];//尺寸?></label>
              <div class="col-md-4">
                <select name="size" class="form-control select2me input-small"  data-placeholder="<?=$LG['pleaseSelect'];//请选择?>" onChange="inputOther('size');" style="float:left;">
                  <option value="Other" <?=$rs['size']==0?'selected':''?>><?=$LG['daigou.111'];//自填?></option>
                  <?php  ClassifyAll(7,$rs['size'],0)?>
                </select>
                <!--默认不是自填:<?=$rs['size']==0&&$rs['sizeOther']?'selected':''?> -->
                
                <input type="text" class="form-control input-small" name="sizeOther" value="<?=cadd($rs['sizeOther'])?>" style="float:left; <?=$rs['size']==0?'':'display:none;'?>"><!--默认不是自填:<?=$rs['size']==0&&$rs['sizeOther']?'':'display:none;'?> -->
                
                
                <a href="javascript:void(0)" class=" popovers" data-trigger="hover" data-placement="top"  data-content="<?=$LG['daigou.113']?>"><i class="icon-info-sign"></i></a>
              </div>
            </div>
            
            
            
            <?php if($callFrom=='manage'){?>
            <div class="form-group">
             <label class="control-label col-md-2"><?=$LG['daigou.192'];//单件重量?></label>
              <div class="col-md-10">
                <input type="text" class="form-control input-small" name="weight" value="<?=spr(($rs['weight']?$rs['weight']:$_REQUEST['weight']))?>"><?=$XAwt?><?=$LG['daigou.193'];// /个?>
              </div>
            </div>
            <?php }?>
  

            
            <div class="form-group"><br></div>
            
            <div class="form-group">
              <div class="control-label col-md-2 right"><strong><?=$LG['daigou.114'];//收货资料?></strong></div>
              <div class="col-md-10">
                <span class="help-block"><?=$LG['daigou.115'];//以下也可以待商品入库后下运单发货时再填写。?></span>
              </div>
            </div>
<?php 
//地址资料-----------------------------------------------------------------------------------
if($rs['addid']){$mrs=FeData('member_address','*',"addid='{$rs['addid']}' {$Mmy} order by edittime desc");}
elseif($Mmy&&$typ!='edit'){$mrs=FeData('member_address','*',"1=1 {$Mmy} order by edittime desc");}

$call_table='daigou';$callFrom=$callFrom;require_once($_SERVER['DOCUMENT_ROOT'].'/xingao/address/call/out_form.php');
?>
<input name="addid" type="hidden" value="<?=$mrs['addid']?>">