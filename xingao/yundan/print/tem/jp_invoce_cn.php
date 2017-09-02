<table width="1000" align="center" cellspacing="0" class="yundan_body">
	<tr>
		<td colspan="2"  align="center" valign="top" ><strong style="font-size:28PX; font-family:Arial, Helvetica, sans-serif; ">INVOCE</strong><br /></td>
	</tr>
	<tr>
		<td width="800"  align="right" valign="top" > 打印时间(Date):<br />
			寄往国家(Place): </td>
		<td   align="left" valign="top" ><?=DateYmd(time(),2) ?> <br />
			China <!--Japan--></td>
	</tr>
</table>
<table width="1000" align="center" cellspacing="0" class="gray_border_table">
	<tr>
		<td colspan="2" rowspan="2" valign="top"><table border="0" cellspacing="0" bordercolor="#E7E7E7"  width="100%">
				<tr>
					<td align="left" ><strong>发送人(Sender):</strong></td>
				</tr>
				<tr>
					<td align="left">
					<?=CompanySend('sendName')?><br>
					<?=CompanySend('sendAdd')?><br>
                    (<?=$number?>)
                    
                        </td>
				</tr>
				<tr>
					<td align="left">TEL: <?=CompanySend('sendTel')?> 
						FAX:</td>
				</tr>
			</table></td>
		<td height="100" colspan="3" valign="top"><strong>邮编(Mail ltem No.):</strong></td>
	</tr>
	<tr>
		<td colspan="3" valign="top"><strong>运输渠道(Shipped Per): </strong><?=channel_name(FeData('member','groupid',"userid='{$rs['userid']}'"),$rs['warehouse'],$rs['country'],$rs['channel'])?></td>
	</tr>
	<tr>
		<td colspan="2" rowspan="2" valign="top"><table border="0" cellspacing="0" bordercolor="#E7E7E7"  width="100%">
				<tr>
					<td align="left" ><strong>收件地址(Addressee):</strong></td>
				</tr>
				<tr>
					<td align="left"><?=cadd($rs['s_name'])?> <br />
						<?php if($ON_country){echo yundan_Country($rs['country']);echo '<br>'; }?> <?=yundan_add_all($rs)?></td>
				</tr>
				<tr>
					<td align="left">TEL: <?=cadd($rs['s_mobile_code'])?> - <?=cadd($rs['s_mobile'])?> ( <?=cadd($rs['s_tel'])?> )<br />
						FAX:</td>
				</tr>
			</table></td>
		<td colspan="3" valign="top"><strong>支付方式(Terms of Payment):</strong></td>
	</tr>
	<tr>
		<td height="180" colspan="3" valign="top"><strong>附注(Remarks):</strong><br />
			<input type="checkbox"/>
			有偿(Commercial value) <br />
			<br />
			<input type="checkbox"/>
			无偿(No Commercial value) <br />
			<DIV style="padding-left:20PX;">
				<input type="checkbox"/>
				礼物(Gift)
				<input type="checkbox"/>
				样品(Sample)
				<input type="checkbox"/>
				其他(Other)</DIV></td>
	</tr>
	<tr>
		<td colspan="5" valign="top">&nbsp;</td>
	</tr>
	<tr>
		<td rowspan="2" align="center" valign="middle"><strong>品名<br />
			(Description)</strong></td>
		<td rowspan="2" align="center" valign="middle"><strong>重量<br />
			(Net Weight Kg)</strong></td>
		<td rowspan="2" align="center" valign="middle"><strong>数量<br />
			(Quantity)</strong></td>
		<td align="center" valign="middle"><strong>单价<br />
			(Unit Price)</strong></td>
		<td rowspan="2" align="center" valign="middle"><strong>合计<br />
			(Total Amount)</strong></td>
	</tr>
	<tr>
		<td align="center" valign="middle"><strong>币种(Currency)<br />
			<?=$XAScurrency.$XAsc?></strong></td>
	</tr>
<?php
$wupin_number=0;
$wupin_total=0;

$fromtable='yundan'; $fromid=$rs['ydid'];
if($fromtable&&$fromid)
{
	$query_wp="select * from wupin where fromtable='{$fromtable}' and fromid='{$fromid}' order by wupin_name desc";
	$sql_wp=$xingao->query($query_wp);
	while($wp=$sql_wp->fetch_array())
	{
		$wupin_number+=$wp['wupin_number'];
		$wupin_total+=$wp['wupin_total'];
?>
	<tr>
		<td align="center" valign="top"><?=cadd($wp['wupin_name'])?></td>
		<td align="center" valign="top">&nbsp;</td>
		<td align="center" valign="top"><?=cadd($wp['wupin_number'])?></td>
		<td align="center" valign="top"><?=cadd($wp['wupin_price'])?></td>
		<td align="center" valign="top"><?=cadd($wp['wupin_total'])?></td>
	</tr>
	<?php
	}
}
?>
	<tr>
		<td width="450" align="center" valign="middle"><strong>合计(Total)</strong></td>
		<td width="500" align="center" valign="middle"><strong><?=spr($rs['weight'])?> </strong></td>
		<td width="500" align="center" valign="middle"><strong><?=$wupin_number?> </strong></td>
		<td width="500" align="center" valign="middle"><strong><?php $joint='warehouse_'.$rs['warehouse'].'_sign';echo $$joint;?></strong></td>
		<td width="500" align="center" valign="middle"><strong><?=$wupin_total?> </strong></td>
	</tr>
</table>
<br />
<table width="1000" align="center">
	<tr>
		<td  valign="top"><strong>邮寄物品数(Number of pieces):<br />
			总重量(Gross weight Kg):<br />
			原产国(gountry of Origin):</strong></td>
		<td width="200" valign="top"><strong>签名(Signature)</strong><br />
			<br />
			<div style="border-bottom-width: 1px;
	border-bottom-style: solid;
	border-bottom-color: #666666;width:150px; height:30px;"></div></td>
	</tr>
</table>
