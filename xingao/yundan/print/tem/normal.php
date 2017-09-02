<!--
10.16CM*15.24CM
-->
<style>
body{ font-weight: 900;font-family: "微软雅黑","黑体",Arial, Helvetica, sans-serif; margin:0px;}
.yundan_body{font-size:16px; line-height:18px;}
td, th {padding: 0px;}
</style>

<table width="500" align="center" cellspacing="10" class="yundan_body">
  
  <tr>
    <td valign="top"><table width="100%">
    	<tbody>
    		<tr>
    			<td align="center">
                <font class="print_title_big_1"><strong>标准快递</strong></font>
                <br>
                <font class="print_title_small_1">打印:<?=DateYmd(time())?></font>
                </td>
    			<td align="right"><img src="/public/barcode/?number=<?=$number?>" width="330" height="50"/></td>
		  </tr>
   		</tbody>
    	</table>
	<span class="bottom_border"></span>
	<table width="100%">
      <tr>
      	<td align="left" width="50%">寄件人：<?=cadd($rs['f_name'])?></td>
      	<td align="left">邮编：<?=cadd($rs['f_zip'])?></td>
      	</tr>
      <tr>
      	<td colspan="2" align="left"> 地址：<?=yundan_add_all($rs,'f')?></td>
      	</tr>
      <tr>
        <td colspan="2" align="left">重量：<?=spr($rs['weight']*$XAwtkg)?>KG</td>
      </tr>
   	</table>
	<span class="bottom_border"></span>
	<table width="100%">
      <tr>
      	<td colspan="2" align="left">收件人：<?=cadd($rs['s_name'])?></td>
      	<td width="50%" align="left">寄达市：<?=cadd($rs['s_add_shengfen']).cadd($rs['s_add_chengshi'])?></td>
      	</tr>
      <tr>
        <td colspan="2" align="left">电话：<?=cadd($rs['s_mobile_code'])?>-<?=cadd($rs['s_mobile'])?></td>
        <td align="left">邮编：<?=cadd($rs['s_zip'])?></td>
      </tr>
      <tr>
        <td width="50" align="left"> 地址：</td>
        <td colspan="2" align="left"><?=yundan_add_all($rs,'s')?></td>
        </tr>
</table>
    <hr style="height:1px;border-top:1px dashed #000000;width:100%;">
	<table width="100%">
		<tr>
			<td align="left">收件人签名：</td>
			<td align="right">签收时间：<span style="padding-left:50px;">年</span><span style="padding-left:10px;">月</span><span style="padding-left:10px;">日</span><span style="padding-left:10px;">时</span></td>
		</tr>
		</table>
        <span class="bottom_border"></span>
	<table width="100%">
	  <tr>
	    <td align="left" valign="top">订单ID：</td>
	    <td align="left"><img src="/public/barcode/?number=<?=$number?>" width="330" height="50"/></td>
	    </tr>
	  <tr>
	    <td height="50" align="left" valign="top"> 内件：</td>
	    <td align="left" valign="top">物品<!--<?=cadd($rs['goodsdescribe'])?>--></td>
	    </tr>
	  </table>
	<table width="100%">
	  <tr>
	    <td align="left" width="50%">寄件人：<?=cadd($rs['f_name'])?></td>
	    <td align="left">重量：<?=spr($rs['weight']*$XAwtkg)?>KG</td>
	    </tr>
	  <tr>
	    <td colspan="2" align="left"> 地址：<?=yundan_add_all($rs,'f')?></td>
	    </tr>
	  </table>
	<span class="bottom_border"></span>
	<table width="100%">
	  <tr>
	    <td colspan="2" align="left">收件人：<?=cadd($rs['s_name'])?></td>
	    <td width="50%" align="left">电话：<?=cadd($rs['s_mobile_code'])?>-<?=cadd($rs['s_mobile'])?></td>
	    </tr>
	  <tr>
	    <td width="50" align="left"> 地址：</td>
	    <td colspan="2" align="left"><?=yundan_add_all($rs,'s')?></td>
	    </tr>
	  </table>
     <span class="bottom_border"></span>
	<table width="100%">
	  <tbody>
	    <tr>
	      <td align="left"><img src="/public/barcode/?number=<?=$number?>" width="330" height="50"/></td>
	      <td align="right"><font class="print_title_big_1"><strong></strong></font></td>
	      </tr>
	    </tbody>
	  </table>
	</td>
  </tr>
</table>
