		<?php 
		$mr=FeData('member','settlement_yundan_bill,settlement_yundan_money,settlement_other_bill,settlement_other_money',"userid='{$Muserid}'");
		
		$settlement_bill=$mr['settlement_yundan_bill'];
		if($settlement_bill){?>
            <!--显示销账按钮-开始-->
            <a href="pay.php?fromtable=yundan"  target="XingAobox" class="btn btn-danger showdiv"><i class="icon-money"></i> <?=$LG['settlement.call_menu_1'];//销账 运单账单?></a>
            <!--显示销账按钮-结束-->
            
            <!--显示账单按钮-开始-->
            <?php if(is_json($settlement_bill)){$bi=(array)json_decode($settlement_bill,true);//不能加cadd?>
                <a href="?classid=<?=cadd($bi['classid'])?>&lotno=<?=cadd($bi['lotno'])?>&sf_name=<?=cadd($bi['sf_name'])?>&tally=1&stime=<?=cadd($bi['stime'])?>&etime=<?=cadd($bi['etime'])?>"  class="btn btn-default"><i class="icon-file-text"></i> <?=$LG['settlement.call_menu_2'];//查看 运单账单?></a>
            <?php }?>
            <!--显示账单按钮-结束-->
        <?php }?>



		<?php 
		$settlement_bill=$mr['settlement_other_bill'];
		if($settlement_bill){?>
            <!--显示销账按钮-开始-->
            <a href="pay.php?fromtable=other"  target="XingAobox" class="btn btn-danger showdiv" style="margin-left:50px;"><i class="icon-money"></i> <?=$LG['settlement.call_menu_3'];//销账 其他账单?></a>
            <!--显示销账按钮-结束-->
            
            <!--显示账单按钮-开始-->
            <?php if(is_json($settlement_bill)){$bi=(array)json_decode($settlement_bill,true);//不能加cadd ?>
                <a href="?tally=1&stime=<?=cadd($bi['stime'])?>&etime=<?=cadd($bi['etime'])?>"  class="btn  btn-default"><i class="icon-file-text"></i> <?=$LG['settlement.call_menu_4'];//查看 其他账单?></a>
            <?php }?>
            <!--显示账单按钮-结束-->
        <?php }?>
