<!--
包裹小标签 80mm*50mm 用 350PX
-->
<?php $print_i++;?>
<script language="javascript" type="text/javascript">
function CreatePrintPage<?=$print_i?>(){
LODOP.PRINT_INITA(0,0,"80mm","50mm","<?=baoguo_print($print_tem)?>-兴奥转运打印");
LODOP.SET_PRINT_PAGESIZE(1,"80mm","50mm","");//设置纸张高度宽度 (默认单位为0.1mm,如800=80mm)
LODOP.SET_SHOW_MODE("BKIMG_WIDTH","80mm");
LODOP.SET_SHOW_MODE("BKIMG_HEIGHT","50mm");

LODOP.ADD_PRINT_TEXT(16,15,270,31,"<?=substr(cadd($rs['bgydh']),-8)?>");//显示单号后面8位数
LODOP.SET_PRINT_STYLEA(0,"FontSize",14);
LODOP.SET_PRINT_STYLEA(0,"Alignment",2);
LODOP.SET_PRINT_STYLEA(0,"Bold",1);
LODOP.ADD_PRINT_TEXT(125,15,87,20,"<?=$rs['weight']>0?spr($rs['weight']).$XAwt:''?>");
LODOP.SET_PRINT_STYLEA(0,"FontSize",10);
LODOP.SET_PRINT_STYLEA(0,"Bold",1);
LODOP.ADD_PRINT_TEXT(126,216,66,20,"<?=cadd($rs['useric'])?>");
LODOP.SET_PRINT_STYLEA(0,"FontSize",10);
LODOP.SET_PRINT_STYLEA(0,"Bold",1);
LODOP.ADD_PRINT_TEXT(127,121,78,20,"<?=spr($rs['userid'])?>");
LODOP.SET_PRINT_STYLEA(0,"FontSize",10);
LODOP.SET_PRINT_STYLEA(0,"Bold",1);
LODOP.ADD_PRINT_TEXT(156,15,87,20,"<?=cadd($rs['whPlace'])?>");
LODOP.SET_PRINT_STYLEA(0,"FontSize",10);
LODOP.SET_PRINT_STYLEA(0,"Bold",1);
LODOP.ADD_PRINT_TEXT(156,121,164,20,"<?=DateYmd(time())?>");
LODOP.ADD_PRINT_SHAPE(4,122,15,270,1,0,1,"#000000");
LODOP.ADD_PRINT_SHAPE(4,149,15,270,1,0,1,"#000000");
LODOP.ADD_PRINT_IMAGE(50,15,270,60,'<img src="/public/barcode/?number=<?=$number?>" style="max-width:250px;"/>');
LODOP.ADD_PRINT_SHAPE(4,123,108,1,56,0,1,"#000000");


}; 
</script>  
