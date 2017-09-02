<?php $print_i++;?>
<script language="javascript" type="text/javascript">
function CreatePrintPage<?=$print_i?>() {

LODOP.PRINT_INITA("-3.44mm","-1.32mm","295.01mm","152.51mm","<?=yundan_print($print_tem)?>-兴奥转运打印");
LODOP.SET_PRINT_PAGESIZE(1,2950,1525,"");//设置纸张高度宽度 (默认单位为0.1mm,如800=80mm)
LODOP.SET_SHOW_MODE("BKIMG_WIDTH","293.95mm");
LODOP.SET_SHOW_MODE("BKIMG_HEIGHT","151.87mm");





}; 
</script>  