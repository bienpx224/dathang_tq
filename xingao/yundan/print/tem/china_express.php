<!--
10.16CM*15.24CM
-->
<style>
.yundan_body{font-size:16px; line-height:20px;}
td, th {padding: 2px;}
</style>

<table width="500" align="center" cellspacing="10" class="yundan_body">
  
  <tr>
    <td valign="top" height="430"><table width="100%">
    	<tbody>
    		<tr>
    			<td><img src="/images/tem_china_express.gif"/></td>
    			<td align="right"><img src="/public/barcode/?number=<?=cadd($rs['gnkdydh'])?>" width="250" height="80"/></td>
    			</tr>
    		</tbody>
    	</table>
	<hr size="1">
	<table width="100%">
      <tr>
      	<td align="left" width="50%">寄件人/From：<?=cadd($rs['f_name'])?></td>
      	<td align="left">电话/Tel：<?=cadd($rs['f_mobile_code'])?>-<?=cadd($rs['f_mobile'])?>
     
      <?=cadd($rs['f_tel'])?>
      		
      		</td>
      	</tr>
      <tr>
      	<td colspan="2" align="left"> 地址/Address：<?=yundan_add_all($rs,'f')?></td>
      	</tr>

   	</table>
	<hr size="1">
	<table width="100%">
      <tr>
      	<td align="left" width="50%">收件人/To：<?=cadd($rs['s_name'])?></td>
      	<td align="left">电话/Tel：<?=cadd($rs['s_mobile_code'])?>-<?=cadd($rs['s_mobile'])?>
     
      <?=cadd($rs['s_tel'])?>
      		
      		</td>
      	</tr>
      <tr>
      	<td colspan="2" align="left"> 地址/Address：<?=yundan_add_all($rs,'s')?></td>
      	</tr>
<tr>
      	<td align="left" width="50%">大客户代码：shht_001_17</td>
      	<td align="left">邮编/Post Code：<?=cadd($rs['f_zip'])?>
      		
      		</td>
      	</tr>
   	</table>

	<table width="100%" border="1" cellpadding="0" cellspacing="0">
	<tbody>
		<tr>
			<td colspan="2" rowspan="3" valign="top">内件描述/Name &amp; Description of Contents：<br>

<?=cadd($rs['goodsdescribe'])?>
</td>
			<td colspan="3">实际重量：<?=spr($rs['weight']*$XAwtkg,3)?>kg</td>
			</tr>
		<tr>
			<td colspan="3">体积重量：</td>
			</tr>
		<tr>
			<td>长/L</td>
			<td>宽/W</td>
			<td>高/H</td>
		</tr>
		<tr>
			<td>物品价值/Value：<?=spr($rs['declarevalue']).$XAsc?></td>
			<td>原产地/Origin：日本</td>
			<td><?=cadd($rs['cc_chang']*$XAszcm)?>cm</td>
			<td><?=cadd($rs['cc_kuan']*$XAszcm)?>cm</td>
			<td><?=cadd($rs['cc_gao']*$XAszcm)?>cm</td>
		</tr>
	</tbody>
</table>
<table width="100%">
		<tr>
			<td align="left">收件人签名：</td>
			<td align="right">签收时间：<span style="padding-left:50px;">年</span><span style="padding-left:10px;">月</span><span style="padding-left:10px;">日</span><span style="padding-left:10px;">时</span></td>
		</tr>
		</table>
	</td>
  </tr>
  <tr>
  	<td valign="top">
	<hr style="height:1px;border:none;border-top:1px dashed #000000;">

	<table width="100%">
		<tr>
			<td align="left" width="50%">寄件人/From：<?=cadd($rs['f_name'])?></td>
			<td align="left">电话/Tel：<?=cadd($rs['f_mobile_code'])?>-<?=cadd($rs['f_mobile'])?> <?=cadd($rs['f_tel'])?></td>
		</tr>
		<tr>
			<td colspan="2" align="left"> 地址/Address：<?=yundan_add_all($rs,'f')?></td>
		</tr>
	</table>
	<hr size="1">
	<table width="100%">
		<tr>
			<td align="left" width="50%">收件人/To：<?=cadd($rs['s_name'])?></td>
			<td align="left">电话/Tel：<?=cadd($rs['s_mobile_code'])?>-<?=cadd($rs['s_mobile'])?> <?=cadd($rs['s_tel'])?></td>
		</tr>
		<tr>
			<td colspan="2" align="left"> 地址/Address：<?=yundan_add_all($rs,'s')?></td>
		</tr>
		</table>
	<hr size="1" style="margin-bottom:0px; padding-bottom:0px;">
<table width="100%">
		<tr>
			<td  align="left" valign="top">
			<img src="/public/barcode/?number=<?=cadd($rs['gnkdydh'])?>" width="220" height="50"/>
			<br>进口口岸：上海<br>
客户电话：11183
			</td>
			<td align="left" valign="top"><hr  size=90  width="1"  style="margin-top:0px; padding-top:0px;"></td>
			<td align="left" valign="top">关联单号：<br><?=$number?>
<br>
原寄地：</td>
		</tr>
		
	</table>
	</td>
  	</tr>
</table>
