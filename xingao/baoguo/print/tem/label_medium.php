<!--
包裹中标签 (10CM*15CM) 10.16CM*15.24CM 用 500PX
-->
<?php $print_i++;?>
<script language="javascript" type="text/javascript">
function CreatePrintPage<?=$print_i?>(){
LODOP.PRINT_INITA("0","0","101.6mm","152.4mm","<?=baoguo_print($print_tem)?>-兴奥转运打印");
LODOP.SET_PRINT_PAGESIZE(1,"101.6mm","152.4mm","");//设置纸张高度
LODOP.SET_SHOW_MODE("BKIMG_WIDTH","1016mm");
LODOP.SET_SHOW_MODE("BKIMG_HEIGHT","1524mm");


LODOP.ADD_PRINT_TEXT(12,20,73,27,"会员ID：");
LODOP.SET_PRINT_STYLEA(0,"FontSize",12);
LODOP.SET_PRINT_STYLEA(0,"Alignment",3);
LODOP.SET_PRINT_STYLEA(0,"Bold",1);
LODOP.ADD_PRINT_TEXT(45,23,67,27,"仓位：");
LODOP.SET_PRINT_STYLEA(0,"FontSize",12);
LODOP.SET_PRINT_STYLEA(0,"Alignment",3);
LODOP.SET_PRINT_STYLEA(0,"Bold",1);
LODOP.ADD_PRINT_TEXT(78,21,70,27,"重量：");
LODOP.SET_PRINT_STYLEA(0,"FontSize",12);
LODOP.SET_PRINT_STYLEA(0,"Alignment",3);
LODOP.SET_PRINT_STYLEA(0,"Bold",1);
LODOP.ADD_PRINT_TEXT(12,101,263,27,"<?=cadd($rs['userid'])?>");
LODOP.SET_PRINT_STYLEA(0,"FontSize",12);
LODOP.SET_PRINT_STYLEA(0,"Bold",1);
LODOP.ADD_PRINT_TEXT(45,100,260,27,"<?=cadd($rs['whPlace'])?>");
LODOP.SET_PRINT_STYLEA(0,"FontSize",12);
LODOP.SET_PRINT_STYLEA(0,"Bold",1);
LODOP.ADD_PRINT_TEXT(79,99,265,27,"<?=$rs['weight']>0?spr($rs['weight']).$XAwt:''?>");
LODOP.SET_PRINT_STYLEA(0,"FontSize",12);
LODOP.SET_PRINT_STYLEA(0,"Bold",1);
LODOP.ADD_PRINT_TEXT(188,97,270,247,"<?=goodsdescribe2('baoguo',$rs['bgid'])?>");
LODOP.SET_PRINT_STYLEA(0,"FontSize",12);
LODOP.SET_PRINT_STYLEA(0,"Bold",1);
LODOP.SET_PRINT_STYLEA(0,"LineSpacing",7);
LODOP.ADD_PRINT_TEXT(145,101,263,27,"<?=DateYmd($rs['rukutime'])?>");
LODOP.SET_PRINT_STYLEA(0,"FontSize",12);
LODOP.SET_PRINT_STYLEA(0,"Bold",1);
LODOP.ADD_PRINT_TEXT(111,100,263,27,"<?=baoguo_addSource($rs['addSource'])?>");
LODOP.SET_PRINT_STYLEA(0,"FontSize",12);
LODOP.SET_PRINT_STYLEA(0,"Bold",1);
LODOP.ADD_PRINT_TEXT(111,19,74,27,"来源：");
LODOP.SET_PRINT_STYLEA(0,"FontSize",12);
LODOP.SET_PRINT_STYLEA(0,"Alignment",3);
LODOP.SET_PRINT_STYLEA(0,"Bold",1);
LODOP.ADD_PRINT_TEXT(145,21,70,27,"入库：");
LODOP.SET_PRINT_STYLEA(0,"FontSize",12);
LODOP.SET_PRINT_STYLEA(0,"Alignment",3);
LODOP.SET_PRINT_STYLEA(0,"Bold",1);
LODOP.ADD_PRINT_TEXT(187,22,67,30,"物品：");
LODOP.SET_PRINT_STYLEA(0,"FontSize",12);
LODOP.SET_PRINT_STYLEA(0,"Alignment",3);
LODOP.SET_PRINT_STYLEA(0,"Bold",1);
LODOP.ADD_PRINT_IMAGE(450,30,331,65,'<img src="/public/barcode/?number=<?=$number?>" />');

}; 
</script>  



<?php if(1==2){?>
<style>
.print_body { font-size: 24px; line-height: 30px; }
</style>
<?php $callFrom=$callFrom;//member ($callFrom print.php传值)?>
<table width="500" align="center" cellspacing="10" class="print_body">
  <tr>
    <td valign="top"><table border="1" cellspacing="0" bordercolor="#E7E7E7" width="100%" class="gray_border_table">
        <tr>
          <td  align="right" width="80" bgcolor="#F2F2F2" ><strong>会员：</strong></td>
          <td  align="left" bgcolor="#F2F2F2" ><font style="float:left"> <?=cadd($rs['username'])?> <font class="gray2">(<?=cadd($rs['userid'])?>)</font> </font></td>
        </tr>
        <tr>
          <td align="right" valign="top"><strong>仓位：</strong></td>
          <td  align="left" ><?=cadd($rs['whPlace'])?></td>
        </tr>
        <tr>
          <td align="right" valign="top"><strong>重量：</strong></td>
          <td  align="left" ><?=$rs['weight']>0?spr($rs['weight']).$XAwt:''?></td>
        </tr>
        <tr>
          <td align="right" valign="top"><strong>来源：</strong></td>
          <td  align="left" ><?=baoguo_addSource($rs['addSource'])?></td>
        </tr>
        <tr>
          <td align="right" valign="top"><strong>入库：</strong></td>
          <td  align="left" ><?=DateYmd($rs['rukutime'])?></td>
        </tr>
        <tr>
          <td align="right" valign="top"><strong>物品：</strong></td>
          <td  align="left" ><?=goodsdescribe2('baoguo',$rs['bgid'])?></td>
        </tr>
        <tr>
          <td colspan="2" align="center" valign="top"><img src="/public/barcode/?number=<?=$number?>" /></td>
        </tr>
      </table></td>
  </tr>
</table>
<?php }?>