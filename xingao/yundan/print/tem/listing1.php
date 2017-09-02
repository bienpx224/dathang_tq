<!--打包清单-->
<style>.yundan_body{font-size:24px; line-height:30px;}</style>
<?php $callFrom=$callFrom;//member 会员中心($callFrom print.php传值)?>
<table width="1000" align="center" cellspacing="10" class="yundan_body">
	<tr>
		<td valign="top">
        <div class="print_title_big_1" align="center"><!--<?=warehouse($rs['warehouse'])?>--><?php $r=CustomerService($rs['userid']);echo $r[0]."({$r[1]})";?> <?=yundan_print('listing1')?> (<?=$number?>)</div>
        <table border="1" cellspacing="0" bordercolor="#E7E7E7" width="100%" class="gray_border_table">
				<tr>
					<td  align="center" width="130"><strong>下单时间：</strong></td>
					<td  align="left" >
					<?=DateYmd($rs['addtime'])?>
					&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
					<strong>完成时间：</strong>
					</td>
					
				</tr>
				<tr>
					<td align="center" valign="top"><strong>所属会员：</strong></td>
					<td  align="left" ><?=cadd($rs['username'])?><?=cadd($rs['truename'])?> <?=cadd($rs['nickname'])?><font class="gray2">(<?=cadd($rs['userid'])?>)</font>
					</td>
				</tr>
				<tr>
					<td rowspan="2" align="center"><strong>包裹信息：</strong><img  src="/public/barcode/?number=<?=$number?>" /></td>

					<td height="300"  align="left" valign="top">
	
		  
                 
		      
		  			
		
<?php if(!$rs['bgid']){
	echo '非包裹下单(无包裹)';
}else{
		$bgid=$rs['bgid'];	$bg_i=0;
		$url='xingao'; if($callFrom=='member'){$url='xamember';}
		$query_bg="select bgydh,bgid,addSource,whPlace,weight from baoguo where bgid in ({$bgid})  order by bgydh asc";
		$sql_bg=$xingao->query($query_bg);
		while($bg=$sql_bg->fetch_array())
		{
				$bg_i++;
				$num=mysqli_num_rows($xingao->query("select * from yundan where find_in_set('{$bgid}',bgid) "));
				?>
				
				
				
<table width="100%">
  <tbody>
    <tr>
      <td><strong>(<?=$bg_i?>) 包裹单号</strong></td>
      <td><a href="/<?=$url?>/baoguo/show.php?bgid=<?=$bg['bgid']?>" target="_blank"><?=cadd($bg['bgydh'])?></a></td>
      <td><?=cadd($bg['whPlace'])?></td>
      <td><?php if($num>0){?>分包<?=$num?>单<?php }?> </td>
      <td><?=spr($bg['weight']).$XAwt?></td>
    </tr>
    
    <?php if($bg['content']){?>
     <tr>
      <td>会员备注</td>
      <td colspan="10"><?=cadd($bg['content'])?></td>
      </tr>
   <?php }?> 
   
   <?php if($bg['reply']){?>
     <tr>
      <td>后台回复</td>
      <td colspan="10"><?=cadd($bg['reply'])?></td>
      </tr>
   <?php }?> 
   
 </tbody>
</table>

<?php
	}
}?>


</td>
				</tr>
				<tr>
				  <td  align="right" bgcolor="#F2F2F2" >
				  【包裹单号确认：口已核对 <span style="margin-left:20px"></span>责任人签字：________________ 】
                  </td>
		  </tr>
				<tr>
					<td rowspan="2"  align="center"><strong>操作要求：</strong></td>
					<td  align="left" >
		<?php  
		//基本资料
		$callFrom=$callFrom;//member 会员中心($callFrom print.php传值)
		$call_payment=0;//费用及付款情况
		$call_basic=0;//基本资料
		$call_op=1;//操作要求
		$call_baoguo=0;//包裹
		$call_goodsdescribe=1;//货物
		$call_content=1; //备注
		$call_reply=1;//回复
		$call_print=1;//打印调用
		$callFrom_show=1;//显示全部文字内容
		require($_SERVER['DOCUMENT_ROOT'].'/xingao/yundan/call/basic_show.php');
		?>
       
					</td>
				</tr>
				
				<tr>
				  <td  align="right" bgcolor="#F2F2F2" >
                  【操作要求确认：口已处理<span style="margin-left:20px"></span>责任人签字：________________ 】
                  </td>
		  </tr>
				
				<tr>
					<td align="right"><strong>运输渠道：</strong></td>
					<td  align="left" >
					<?=channel_name(FeData('member','groupid',"userid='{$rs['userid']}'"),$rs['warehouse'],$rs['country'],$rs['channel'])?>
					&nbsp; <font class="gray2">(状态:<?=status_name(spr($rs['status']))?>)</font>
					</td>
				</tr>
                
                
               <tr>
					<td rowspan="2" align="right"><strong>收货信息：</strong></td>
					<td  align="left">
                    
    <table width="100%">
      <tr>
      	<td width="100"><strong>收件人</strong></td>
      	<td align="left">
      		<?=cadd($rs['s_name'])?>
      		</td>
      	</tr>
      <tr>
        <td><strong>电话</strong></td>
        <td align="left">
          <?=cadd($rs['s_mobile_code'])?>-<?=cadd($rs['s_mobile'])?>
         
         <span style="margin-left:30px;"></span>
          <?=cadd($rs['s_tel'])?></td>
      </tr>
      <tr>
        <td><strong>地址</strong></td>
        <td align="left">
           <?php if($ON_country){echo yundan_Country($rs['country']);echo '<br>'; }?>
             <?=yundan_add_all($rs)?>
        </td>
      </tr>
       <tr>
        <td><strong>邮编</strong></td>
        <td align="left">
             <?=cadd($rs['s_zip'])?>
        </td>
      </tr>
       <tr>
         <td><strong>物品</strong></td>
         <td align="left">
           
           <div style="height:10px"></div>
           <?=TextareaToBr($rs['goodsdescribe'])?>   
           </td>
       </tr>
    </table>                
					</td>
				</tr>
               <tr>
                  <td  align="right" bgcolor="#F2F2F2" >
                  
【配送方式：口已核对 <span style="margin-left:10px;"></span> 收货信息：口已核对<span style="margin-left:20px"></span>签字：________________ 】

</td>
               </tr>
                
				<tr>
					<td  align="center"><strong>包裹信息：</strong></td>
					<td bgcolor="#DDDDDD">
                    体积：_____________
					<span style="margin-left:10px"></span>
                    重量：_____________ <?=$rs['weightEstimate']>0?'(预估'.spr($rs['weightEstimate']).$XAwt.')':''?>
					<br>

					备注：______________________________________________________________
					</td>
				</tr>
				
			</table>
           
            
            </td>
	</tr>
</table>

