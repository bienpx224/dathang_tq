<!--
小面单(10CM*15CM)
10.16CM*15.24CM 用 500PX
-->
<table width="500" align="center" cellspacing="10"  class="yundan_body">
  <tr>
    <td align="center" valign="top"><p><img src="/public/barcode/?number=<?=$number?>" /></p></td>
  </tr>
  <tr>
    <td valign="top"><table border="1" cellspacing="0" bordercolor="#E7E7E7" width="100%">
      <tr>
      	<td width="130" align="right"><strong>Sent TO 收件人</strong></td>
      	<td align="left">
      		<?=cadd($rs['s_name'])?>
      		</td>
      	</tr>
      <tr>
        <td align="right"><strong>Tel NO. 电话</strong></td>
        <td align="left">
          <?=cadd($rs['s_mobile_code'])?>-<?=cadd($rs['s_mobile'])?>
          <br />
          <?=cadd($rs['s_tel'])?></td>
      </tr>
      <tr>
        <td align="right"><strong>Address 地址</strong></td>
        <td align="left">
           <?php if($ON_country){echo yundan_Country($rs['country']);echo '<br>'; }?>
             <?=yundan_add_all($rs)?>
        </td>
      </tr>
       <tr>
        <td align="right"><strong>Zip/Post 邮编</strong></td>
        <td align="left">
             <?=cadd($rs['s_zip'])?>
        </td>
      </tr>
       <tr>
        <td align="right"><strong>Order No. 运单号</strong></td>
        <td align="left">
             <?=$number?>
        </td>
      </tr>
       <tr>
        <td align="right"><strong>Weight 重量</strong></td>
        <td align="left">
            <?=spr($rs['weight']).$XAwt?>
        </td>
      </tr>

   </table></td>
  </tr>
</table>
