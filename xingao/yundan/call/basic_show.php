<?php if($call_payment){?>
	<div class="gray modal_border">
    <!--是否月结-->
    <?=$rs['tally']>0?Tally($rs['tally']):''?>
    
    <!--运费部分---------------------------------------------------------------------------> 
    <?php 
    $total_price=spr($rs['money']);
    $payment=spr($rs['payment']);
    $pay=$rs['pay'];
    $payment_time=$rs['payment_time'];
    
    if(!$total_price)
    {
        $pay_status= '<span class="label label-sm label-default">'.$LG['yundan.Xcall_basic_show_1'].'</span>';
    }
    elseif(!$pay&&!$payment)
    {
		$pay_status= '<span class="label label-sm label-default">'.$LG['yundan.Xcall_basic_show_2'].'</span>';
    }
    elseif((!$pay&&$payment)||($pay&&$total_price>$payment))
    {
        $pay_status= '<span class="label label-sm label-warning tooltips" data-container="body" data-placement="top" data-original-title="'.$LG['yundan.Xcall_basic_show_3'].($total_price-$payment).$XAmc.'">'.$LG['yundan.Xcall_basic_show_4'].'</span>';
    }
    elseif($pay&&$total_price<$payment)
    {
        $pay_status= '<span class="label label-sm label-danger tooltips" data-container="body" data-placement="top" data-original-title="'.$LG['yundan.Xcall_basic_show_5'].($payment-$total_price).$XAmc.'">'.$LG['yundan.Xcall_basic_show_6'].'</span>';
    }
    elseif($pay&&$total_price==$payment)
    {
		$payppt=$LG['yundan.Xcall_basic_show_29'];if($rs['memberpay']>0){$payppt=$LG['yundan.Xcall_basic_show_30'];}
		$pay_status= '<span class="label label-sm label-success tooltips" data-container="body" data-placement="top" data-original-title="'.$LG['yundan.Xcall_basic_show_7'].$payppt.'；'.$LG['yundan.Xcall_basic_show_15'].DateYmd($payment_time,1).'">'.$LG['yundan.Xcall_basic_show_8'].'</span>';
    }
    ?>
    <font title="<?=$rs['money_content']?>">
    <?=$LG['yundan.Xcall_basic_show_33']?>
    <?php if(spr($rs['money'])>0){?>
        <a data-target="#ajax<?=$rs['ydid']?>" data-toggle="modal" href="/xingao/yundan/call/money_modal.php?ydid=<?=$rs['ydid']?>&callFrom=<?=$callFrom?>">
            <font class="show_price"><?=$total_price?></font>
        </a>
        <?=$XAmc?>
        
        <div class="modal fade" id="ajax<?=$rs['ydid']?>" tabindex="-1" role="basic" aria-hidden="true">
            <img src="/images/ajax-modal-loading.gif" alt="" class="loading">
        </div>
    <?php }?>
    </font>
    
    <?=$pay_status?>
    
    <font class="gray">
       ，
        <?php if($rs['prefer']==1){echo $LG['useCoupons'];}elseif($rs['prefer']==2){echo $LG['useDiscount'];}elseif($rs['prefer']==3){echo $LG['yundan.Xcall_basic_show_35'];}else{echo $LG['yundan.Xcall_basic_show_37'];}?>
        
    <?php if($off_integral){?>
        <!--送积分情况-->
        <?php if($integral_yundan>0){?>
            <span title="<?php if($rs['integral_to']==1&&$rs['prefer']==3){ echo $LG['yundan.Xcall_basic_show_10'].$integral_yundan;}
            elseif($rs['integral_to']==1){ echo $LG['yundan.Xcall_basic_show_12'].$integral_yundan;}
            elseif(!$rs['integral_to']){ echo $LG['yundan.Xcall_basic_show_13'];}
            ?>">(<?php if(!$rs['integral_to']){ echo $LG['yundan.Xcall_basic_show_13'];}else{echo $LG['yundan.Xcall_basic_show_38'];}?>)</span>
        <?php }?>
        
    <?php }?>
    </font>
    
    
    
    
    <!--税费部分----------------------------------------------------------------------> 
    
    <?php if($status_on_14){?> 
        <span class="xa_sep"> | </span> 
        <?php 
        $total_price=spr($rs['tax_money']);
        $payment=spr($rs['tax_payment']);
        $pay=$rs['tax_pay'];
        $payment_time=$rs['tax_payment_time'];
        
        if(!$total_price)
        {
            $pay_status= '<span class="label label-sm label-default">'.$LG['yundan.Xcall_basic_show_14'].'</span>';
        }
        elseif(!$pay&&!$payment)
    {
		$pay_status= '<span class="label label-sm label-default">'.$LG['yundan.Xcall_basic_show_2'].'</span>';
    }
    elseif((!$pay&&$payment)||($pay&&$total_price>$payment))
    {
            $pay_status= '<span class="label label-sm label-warning tooltips" data-container="body" data-placement="top" data-original-title="'.$LG['yundan.Xcall_basic_show_3'].($total_price-$payment).$XAmc.'">'.$LG['yundan.Xcall_basic_show_4'].'</span>';
        }
        elseif($pay&&$total_price<$payment)
        {
            $pay_status= '<span class="label label-sm label-danger tooltips" data-container="body" data-placement="top" data-original-title="'.$LG['yundan.Xcall_basic_show_5'].($payment-$total_price).$XAmc.'">'.$LG['yundan.Xcall_basic_show_6'].'</span>';
        }
        elseif($pay&&$total_price==$payment)
        {
            $pay_status= '<span class="label label-sm label-success tooltips" data-container="body" data-placement="top" data-original-title="'.$LG['yundan.Xcall_basic_show_15'].DateYmd($payment_time,1).'">'.$LG['yundan.Xcall_basic_show_8'].'</span>';
        }
        ?>
        
        <?=$LG['yundan.Xcall_basic_show_39']?>
        <?php if(spr($rs['tax_money'])>0){?>
            <font class="show_price"><?=$total_price?></font><?=$XAmc?>
        <?php }?>
        
        <?=$pay_status?>
        <?php if($rs['tax_img']){EnlargeImg($rs['tax_img'],$rs['ydid'],'');}?>
    <?php }?> 
    
    
    
    
    
    
    
    
    <!--重量和尺寸部分---------------------------------------------------------------------------> 
    <span class="xa_sep"> | </span>
    
    <font class="tooltips" data-container="body" data-placement="top" data-original-title="<?=$LG['yundan.Xcall_basic_show_40']?><?=spr($rs['weightEstimate'],3)?><?=$XAwt?>">
    
    <?php 
	$calcWeight=yundan_calcWeight($rs);
    if($calcWeight!=spr($rs['weight'])){
        echo $LG['yundan.Xcall_basic_show_16'].$calcWeight.$XAwt;
        if($callFrom=='manage'){echo $LG['yundan.Xcall_basic_show_17'].spr($rs['weight']).$XAwt;}
    }else{
        echo $LG['yundan.Xcall_basic_show_16'].spr($rs['weight']).$XAwt;
    }
    ?>
    </font> 
    
    <?php 
    if($rs['cc_chang']||$rs['cc_kuan']||$rs['cc_gao'])
    {
        echo '(';
        if($rs['cc_chang']){echo $LG['length'];echo cadd($rs['cc_chang']).$XAsz.' * ';}
        if($rs['cc_kuan']){echo $LG['width'];echo cadd($rs['cc_kuan']).$XAsz.' * ';}
        if($rs['cc_gao']){echo $LG['high'];echo cadd($rs['cc_gao']).$XAsz;}
        echo ')';
    }
    ?>
    

	</div>
<?php }?>













<!--基本资料----------------------------------------------------------------------------->
<?php if($call_basic){?>
	<div class="gray modal_border">
		<?php 
		if($rs['warehouse']){echo '<font class=" tooltips" data-container="body" data-placement="top" data-original-title="'.$LG['warehouse'].'">'.warehouse($rs['warehouse']).'</font>'; }
		if($callFrom=='manage'&&$rs['whPlace']){echo '<font class=" tooltips" data-container="body" data-placement="top" data-original-title="'.$LG['positions'].'">('.cadd($rs['whPlace']).')</font>'; }
		
		if($rs['declarevalue']>0){echo '<span class="xa_sep"> | </span>'.$LG['yundan.Xcall_basic_show_41'];echo spr($rs['declarevalue']).$XAsc;}
		if($rs['insureamount']>0||$rs['insurevalue']>0)
		{
			echo $LG['yundan.Xcall_basic_show_19'];echo spr($rs['insureamount']).$XAsc;
			echo $LG['yundan.Xcall_basic_show_20'].spr($rs['insurevalue']).$XAmc.'';
		}
		echo '<span class="xa_sep"> | </span>';if($rs['kffs']){echo $LG['yundan.kffs'];}else{echo $LG['yundan.Xcall_basic_show_42'];}

		if($rs['addSource']){echo '<span class="xa_sep"> | </span><font class=" tooltips" data-container="body" data-placement="top" data-original-title="'.$LG['yundan.list_4'].'">'.yundan_addSource($rs['addSource']).'</font>';}
		if($rs['addtime']){echo '<span class="xa_sep"> | </span><font class=" tooltips" data-container="body" data-placement="top" data-original-title="'.$LG['yundan.Xcall_basic_show_21'].'">'.DateYmd($rs['addtime'],1).'</font>';}
		if($rs['chukutime']){echo '<span class="xa_sep"> | </span><font class=" tooltips" data-container="body" data-placement="top" data-original-title="'.$LG['yundan.Xcall_basic_show_22'].'">'.DateYmd($rs['chukutime'],1).'</font>';}
		?>
	</div>
<?php }?>










<!--操作要求------------------------------------------------------------------------------->
<?php if($call_op){?>
	<div class="gray modal_border">
		<?php 
		if($rs['op_bgfee1']>0){
			echo '<span class="xa_sep"> | </span>';
			echo yundan_service('op_bgfee1','name'); 
			echo ':';
			echo yundan_service('op_bgfee1',$rs['op_bgfee1']);
		}
		if($rs['op_bgfee2']>0){
			echo '<span class="xa_sep"> | </span>';
			echo yundan_service('op_bgfee2','name'); 
			echo ':';
			echo yundan_service('op_bgfee2',$rs['op_bgfee2']);
		}
		if($rs['op_wpfee1']>0){
			echo '<span class="xa_sep"> | </span>';
			echo yundan_service('op_wpfee1','name'); 
			echo ':';
			echo yundan_service('op_wpfee1',$rs['op_wpfee1']);
		}
		if($rs['op_wpfee2']>0){
			echo '<span class="xa_sep"> | </span>';
			echo yundan_service('op_wpfee2','name'); 
			echo ':';
			echo yundan_service('op_wpfee2',$rs['op_wpfee2']);
		}
		if($rs['op_ydfee1']>0){
			echo '<span class="xa_sep"> | </span>';
			echo yundan_service('op_ydfee1','name'); 
			echo ':';
			echo yundan_service('op_ydfee1',$rs['op_ydfee1']);
		}
		if($rs['op_ydfee2']>0){
			echo '<span class="xa_sep"> | </span>';
			echo yundan_service('op_ydfee2','name'); 
			echo ':';
			echo yundan_service('op_ydfee2',$rs['op_ydfee2']);
		}
		if($rs['op_freearr']){
			echo '<span class="xa_sep"> | </span>';
			echo op_freearr('name'); 
			echo ':';
			echo op_freearr($rs['op_freearr']);
		}
		if($rs['op_free']>0){
			echo '<span class="xa_sep"> | </span>';
			echo op_free('name'); 
			echo ':';
			echo op_free($rs['op_free']);
		}
		if($rs['op_overweight']>0){
			echo '<span class="xa_sep"> | </span>';
			echo op_overweight('name'); 
			echo ':';
			echo op_overweight($rs['op_overweight']);
		}
		?>
	</div>
<?php }?>












<!--显示备注等文字内容:包裹------------------------------------------------------------------------------->
<?php if ($call_baoguo){?>
	<?php if ($rs['bgid']){?>
		<div class="gray modal_border"> <strong><?=$LG['total']?><?=arrcount($rs['bgid'])?><?=$LG['yundan.Xcall_basic_show_23'];//个包裹：?></strong>
		<?php yundan_bg($rs['bgid'],'',$callFrom);?>
		</div>
	<?php }?>
<?php }?>











<!--显示备注等文字内容:货物------------------------------------------------------------------------------->
<?php if ($call_goodsdescribe){?>
	<?php
	$zhi=cadd($rs['goodsdescribe']);
	if($zhi){
	?>
	<div class="gray modal_border"> <strong><?=$LG['yundan.Xcall_basic_show_24'];//物品：?></strong>
    
	  <?php 
		if($callFrom_show)
		{
			echo TextareaToBr($zhi);
		}else{
			echo leng($zhi,100,"...");
			Modals($zhi,$title=$LG['yundan.Xcall_basic_show_25'],$time='',$nameid='goodsdescribe'.$rs['ydid'],$count=100);
		}?>
        
		<?php 
		//已扫描商品
        if($ON_gd_japan&&$callFrom=='manage')
		{
			$goodsdata_number=0;
			if($rs['goodsdata'])
			{
				$yd_gdid=yundan_goodsdata(cadd($rs['goodsdata']),'',1);//获取全部ID
				$goodsdata_number=arrcount($yd_gdid);
			}
			?>
            <a href="/xingao/yundan/scanGoodsdata.php?typ=so&yd_number=<?=cadd($rs['ydh'])?>" target="_blank">(<b><?=LGtag($LG['yundan.Xcall_basic_show_43'],'<tag1>=='.$goodsdata_number)?></b>)</a>
            <?php 
		}
        ?>
        
        
		<?php 
		//未备案商品
        if($ON_gd_mosuda&&$callFrom=='manage'&&$rs['ydid'])
		{
			$record_num=NumData('wupin',"fromtable='yundan' and fromid='{$rs['ydid']}' and gdid>0 and record='1'");
			if($record_num>0){echo "(<font class='red2'>有{$record_num}个物品未备案</font>)";}
		}
        ?>
        
	</div>
	<?php }?>
<?php }?>









<!--显示备注等文字内容:备注------------------------------------------------------------------------------->
<?php if ($call_content){?>

	
	<?php 
	$zhi=cadd($rs['fx_content']);
	if($zhi||$call_print){
	?>
	<div class="gray modal_border"><strong><?=$LG['yundan.Xcall_basic_show_26'];//分包：?></strong>
		<?php 
		if($callFrom_show&&$zhi)
		{
			echo TextareaToBr($zhi);
		}elseif($zhi){
			echo leng($zhi,100,"...");
			Modals($zhi,$title=$LG['yundan.fx_content'],$time=$rs['addtime'],$nameid='fx_content'.$rs['ydid'],$count=100);
		}elseif(!$rs['fx']){
			echo $LG['yundan.form_10'];}else{
			echo $LG['yundan.Xcall_basic_show_28'];}
		?>
	</div>
	<?php }?>


	<div>
	<?php 
	$zhi=cadd($rs['content']);
	if($zhi||!$callFrom_show)
	{
	?>
	</div>
    
    <!--有必要-->
	<div class="gray modal_border"> <strong><?=$LG['yundan.Xcall_basic_show_31']?>：</strong>
		<?php 
		if($callFrom_show)
		{
			echo TextareaToBr($zhi);
		}else{
			echo leng($zhi,100,"...");
			Modals($zhi,$title=$LG['yundan.Xcall_basic_show_31'],$time=$rs['addtime'],$nameid='content'.$rs['ydid'],$count=100);
		}
	}
	?>
	
	<?php if(!$callFrom_show&&$callFrom=='member'&&have(spr($rs['status']),'-1,0,1',1)){?>	
	<a href="../Tool/edit.php?typ=1&table=yundan&fieldEdit=content&fieldId=ydid&id=<?=$rs['ydid']?>" target="XingAobox" class="btn btn-xs btn-default showdiv"><?=$LG['edit'];//修改?></a>
	<?php }?>
	</div>
<?php }?>










<!--显示备注等文字内容:回复------------------------------------------------------------------------------->
<?php if ($call_reply){?>
	<?php
	$zhi=cadd($rs['reply']);
	if($zhi){
	?>
	<div class="gray modal_border"> <strong><?=$LG['yundan.Xcall_basic_show_34']?>：</strong>
		<?php 
		if($callFrom_show)
		{
			echo TextareaToBr($zhi);
		}else{
			echo leng($zhi,100,"...");
			Modals($zhi,$title=$LG['yundan.Xcall_basic_show_34'],$time=$rs['replytime'],$nameid='reply'.$rs['ydid'],$count=100);
		}?>
	</div>
	<?php }?>
<?php }?>











<!--显示备注等文字内容:回复------------------------------------------------------------------------------->
<?php if ($call_reply&&$callFrom=='manage'){?>
	<?php
	$zhi=cadd($rs['manage_content']);
	if($zhi){
	?>
	<div class="gray modal_border"> <strong><?=$LG['yundan.Xcall_basic_show_36']?>：</strong>
		<?php 
		if($callFrom_show)
		{
			echo TextareaToBr($zhi);
		}else{
			echo leng($zhi,100,"...");
			Modals($zhi,$title=$LG['yundan.Xcall_basic_show_36'],$time='',$nameid='manage_content'.$rs['ydid'],$count=100);
		}?>
	</div>
	<?php }?>
<?php }?>
