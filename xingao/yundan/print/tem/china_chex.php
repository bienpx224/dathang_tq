<!--
7.5CM*15.5CM
-->
<style>
.yundan_body{font-size:16px; line-height:20px;}
td, th {padding: 2px;}
</style>
<table width="400" align="center" cellspacing="10" class="yundan_body">
  
  <tr>
    <td valign="top"><table width="100%">
    	<tbody>
    		<tr>
    			<td><img src="/images/tem_china_chex.gif"/></td>
    			<td align="right"><img src="/public/barcode/?number=<?=cadd($rs['gnkdydh'])?>" width="250" height="80"/></td>
    			</tr>
    		</tbody>
    	</table>
	<hr size="1">
	<table width="100%">
      <tr>
      	<td align="left" width="50%">寄件人：<?=cadd($rs['f_name'])?><br>
      		电话：<?=cadd($rs['f_mobile_code'])?>-<?=cadd($rs['f_mobile'])?> <?=cadd($rs['f_tel'])?><br>
      		<?=yundan_add_all($rs,'f')?></td>
      	</tr>
      </table>
	<hr size="1">
	<table width="100%">
      <tr>
      	<td align="left" width="50%">收件人：<?=cadd($rs['s_name'])?><br>
      		电话：<?=cadd($rs['s_mobile_code'])?>-<?=cadd($rs['s_mobile'])?> <?=cadd($rs['s_tel'])?><br>
      		<?=yundan_add_all($rs,'s')?></td>
      	</tr>
      </table><hr size="1">
<table width="100%">
      <tr>
      	<td width="50" colspan="2" align="left"><img src="/public/barcode/?number=<?=$number?>" width="350" height="80"/></td>
      	</tr>
</table><hr size="1">
<table width="100%">
	<tr>
		<td align="left" width="50%">物品价值：<?=spr($rs['declarevalue']).$XAsc?></td>
		<td align="left">重量：<?=spr($rs['weight']*$XAwtkg,3)?>kg</td>
	</tr>
	<tr>
		<td colspan="2" align="left">内装物品：<?=cadd($rs['goodsdescribe'])?></td>
	</tr>
</table>
<hr size="1">
	<table width="100%">
		<tr>
			<td width="50" align="left" style="line-height:30px;">若无法投递时，寄件人之指定事项<br>
				口 退回寄件人并由寄件人付运费<br>
				口 抛弃<br>
				1.兹证明本人所填写资料属实且无装寄任何危险及禁寄物品<br>
				2.本人已审阅并同意载运契约一切条款<br>
				(未保价货件每单赔偿上限为100美元)<br>
				寄件人签署_____________<br>
				日期:_______年___月___日</td>
		</tr>
		</table></td>
  </tr>
</table>
