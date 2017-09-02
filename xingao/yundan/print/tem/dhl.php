<!--横向打印:width="1600" height="1000"-->

<?php 
$dhl=GetJson($rs['dhl']);

//直接输出
/*
	header('Content-Type: application/pdf');//要删除之前的,必须用这个
	echo base64_decode($dhl['label']);//解编码
	
*/
if($dhl)
{
?>
<link rel="stylesheet" type="text/css" href="/xingao/yundan/print/style/dhl/style.css">
<table width="1650" height="1000" align="center" cellspacing="0" class="yundan_body dhl">
  <tr>
    <td valign="top" width="100%" style="padding:20px;">
    
    <table width="100%" >
        <tr>
          <td valign="top"><font class="dhl_left_title"> Informationen zu Ihrer Retoure<br>
            </font> <font class="dhl_left_title2">Auftragsnummer: <?=$number?><br>
            Sendungsnummer: <?=$dhl['idc']?><br>
            </font>
            <table width="100%" class="dhl_left_b">
              <tr>
                <td colspan="2" height="60">&nbsp;</td>
              </tr>
              <tr class="dhl_left_title2">
                <td>Absenderadresse</td>
                <td>Empfängeradresse</td>
              </tr>
              <tr>
                <td colspan="2"><span class="dhl_left_border"></span></td>
              </tr>
              <tr>
                <td valign="top">
				<?=cadd($rs['f_name'])?><br>
				<?=cadd($rs['f_mobile'])?><br>
				<?=cadd($rs['f_add_quzhen'])?> <?=cadd($rs['f_add_dizhi'])?><br>
				<?=cadd($rs['f_zip'])?> <?=cadd($rs['f_add_chengshi'])?><br>
                
                </td>
                <td valign="top"><?=TextareaToBr($dhl_ToAddress)?></td>
              </tr>
            </table></td>
        </tr>
      </table></td>
    <td width="28" valign="bottom" background="/xingao/yundan/print/style/dhl/vertical.gif" style="padding:0px;"><img src="/xingao/yundan/print/style/dhl/vertical_2.gif"/></td>
    <td valign="top" style="padding:20px;">
    
    
    <table width="800" height="1000" class="dhl_border_table">
        <tr>
          <td colspan="3">
          
          <table width="100%" >
              <tr>
                <td class="dhl_right_title">DHL Retoure</td>
                <td valign="bottom" class="dhl_right_small">AM-PDF-2.2.0</td>
                <td align="right"><img src="/xingao/yundan/print/style/dhl/logo.gif" height="40"/></td>
              </tr>
            </table>
            
            </td>
        </tr>
        <tr>
          <td colspan="3"><table width="100%"  class="dhl_right_c">
              <tr>
                <td valign="top" width="100">Von/From:</td>
                <td valign="top">
				<?=cadd($rs['f_name'])?><br>
				<?=cadd($rs['f_add_quzhen'])?> <?=cadd($rs['f_add_dizhi'])?><br>
				<?=cadd($rs['f_zip'])?> <?=cadd($rs['f_add_chengshi'])?><br>
                
                  Deutschland</td>
                <td valign="top">Tel. <?=cadd($rs['f_mobile'])?></td>
              </tr>
            </table>
            <table width="100%"  class="dhl_right_c2">
              <tr>
                <td valign="top" width="75"  class="angle_1">An/To:</td>
                <td valign="top"  class="angle_2">
				<?=TextareaToBr($dhl_ToAddress)?><br>
                  Deutschland</td>
              </tr>
            </table></td>
        </tr>
        <tr>
          <td bgcolor="#000000" height="10" style="padding:0"></td>
          <td colspan="2"></td>
        </tr>
        <tr>
          <td colspan="3"><table width="100%"  class="dhl_right_c">
              <tr>
                <td valign="top" width="100">Abrechnungs Nr.:<br>
                  Grösse / Gewicht:<br>
                  Sendungsnummer:<br>
                  Ref. No.:</td>
                <td valign="top"><br>
                  <br>
                  <?=$dhl['idc']?> <br>
                  <?=$number?> </td>
                <td align="right" valign="top">Entgelt Bezahlt<br>
                  Port Payé</td>
              </tr>
              <tr>
                <td colspan="3" align="center" valign="top" class="dhl_right_normal">Auftragsnummer<br>
                  <font style="height:45px; width:500px; overflow:hidden; display:block;">
                  <img src="/public/barcode/?number=<?=$number?>" />
                  </font>
                  (Y)<?=$number?>
                  </td>
              </tr>
            </table></td>
        </tr>
        <tr>
          <td width="20%">&nbsp;</td>
          <td width="60%">&nbsp;</td>
          <td  width="20%" align="center" class="dhl_right_title_big">M</td>
        </tr>
        <tr>
          <td colspan="3" align="center" >
          
          <?php 
		  $routingCode=str_ireplace('.','',$dhl['routingCode']);
		  $routingCode=str_ireplace(' ','',$routingCode);
		  ?>
          <font style="height:145px; width:700px; overflow:hidden; display:block;">
          <img src="/public/barcode/?number=<?=$routingCode?>&codebar=BCGi25&height=70"  style="margin-top:10px;"/>
          </font>
          <strong><?=$dhl['routingCode']?></strong>
          
          <br>
          <img src="/public/barcode/?number=<?=$dhl['idc']?>&codebar=BCGi25&height=70" style="margin-top:10px;" />
            </td>
        </tr>
      </table>
      
      </td>
  </tr>
</table>
<?php }?>
