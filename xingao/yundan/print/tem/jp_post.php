<?php $print_i++;?>
<script language="javascript" type="text/javascript">
function CreatePrintPage<?=$print_i?>() {

LODOP.PRINT_INITA("-3.44mm","-1.32mm","295.01mm","152.51mm","<?=yundan_print($print_tem)?>-兴奥转运打印");
LODOP.SET_PRINT_PAGESIZE(1,2950,1525,"");//设置纸张高度宽度 (默认单位为0.1mm,如800=80mm)
LODOP.SET_SHOW_MODE("BKIMG_WIDTH","293.95mm");
LODOP.SET_SHOW_MODE("BKIMG_HEIGHT","151.87mm");
LODOP.ADD_PRINT_TEXT("29.37mm","30.43mm","84.67mm","19.84mm","<?=CompanySend('sendName')?>\n<?=CompanySend('sendAdd')?>\n<?=cadd($rs['ydh'])?>\n<?=cadd($rs['whPlace'])?> (<?=spr($rs['payment']+$rs['tax_payment'])?>)\n<?=channel_name($mr['groupid'],$rs['warehouse'],$rs['country'],$rs['channel'])?>");// 换行\n  发送人栏(可用于打印其他备注信息)
LODOP.SET_PRINT_STYLEA(0,"FontSize",10);

LODOP.ADD_PRINT_TEXT("24.34mm","122.77mm","91.02mm","23.28mm","<?=cadd($rs['s_name'])?>\n<?=yundan_add_all($rs,'s')?>");
LODOP.SET_PRINT_STYLEA(0,"FontSize",10);

LODOP.ADD_PRINT_TEXT("53.18mm","43.13mm","19.31mm","6.09mm","<?=CompanySend('sendZip')?>");
LODOP.ADD_PRINT_TEXT("52.65mm","91.28mm","22.49mm","6.61mm","<?=CompanySend('sendTel')?>");
LODOP.ADD_PRINT_TEXT("106.36mm","159.54mm","54.77mm","6.88mm","<?=CompanySend('sendName')?>");

LODOP.ADD_PRINT_TEXT("53.18mm","157.96mm","53.18mm","5.82mm","<?=Country($rs['country'],'','EN')?>");
LODOP.SET_PRINT_STYLEA(0,"FontSize",10);

LODOP.ADD_PRINT_TEXT("53.71mm","121.71mm","28.84mm","4.76mm","<?=cadd($rs['s_mobile_code'])?>-<?=cadd($rs['s_mobile'])?>");
LODOP.SET_PRINT_STYLEA(0,"FontSize",10);

LODOP.ADD_PRINT_TEXT("19.84mm","221.99mm","27.52mm","4.23mm","<?=spr($rs['weight'],2,0,1).$XAwt?>");
LODOP.ADD_PRINT_TEXT("28.05mm","221.99mm","14.55mm","5.29mm","<?=spr($rs['fee_transport'])?>");
LODOP.ADD_PRINT_TEXT("28.05mm","239.18mm","12.7mm","4.76mm","<?=spr($rs['insurevalue'])?>");
LODOP.ADD_PRINT_TEXT("37.57mm","221.72mm","28.31mm","4.5mm","<?=spr($rs['fee_transport']+$rs['insurevalue'])?>");
LODOP.ADD_PRINT_TEXT("88.9mm","141.29mm","28.05mm","5.29mm","<?=spr($rs['declarevalue'])?>");
LODOP.ADD_PRINT_TEXT("87.84mm","30.69mm","5.03mm","4.76mm","●");
LODOP.ADD_PRINT_TEXT("115.09mm","30.69mm","5.03mm","4.76mm","●");
//LODOP.ADD_PRINT_TEXT("120.65mm","30.69mm","4.23mm","4.76mm","●");//右记
//LODOP.ADD_PRINT_TEXT("125.94mm","30.43mm","4.76mm","4.76mm","●");//运送
LODOP.ADD_PRINT_TEXT("142.61mm","18.79mm","6.09mm","6.35mm","●");

LODOP.ADD_PRINT_TEXT("65.35mm","171.71mm","5.56mm","4.5mm","●");
LODOP.ADD_PRINT_TEXT("127.53mm","125.94mm","5.56mm","4.76mm","●");
//LODOP.ADD_PRINT_TEXT("114.83mm","144.99mm","4.76mm","5.03mm","●");//放弃
LODOP.ADD_PRINT_TEXT(178,597,201,22,"<?=cadd($rs['s_zip'])?>");//收件邮编

<?php 
$JPChannel=GetJPChannel($rs['warehouse'],$rs['channel']);
if(!$JPChannel==1){
?>
//无
<?php }elseif($JPChannel==2){?>
	LODOP.ADD_PRINT_TEXT("49.48mm","228.34mm","4.5mm","4.76mm","●");//空运
<?php }elseif($JPChannel==3){?>
	LODOP.ADD_PRINT_TEXT("54.5mm","228.34mm","4.76mm","4.76mm","●");//SAL
<?php }elseif($JPChannel==4){?>
	LODOP.ADD_PRINT_TEXT("60.5mm","228.34mm","4.5mm","4.76mm","●");//船运
<?php }?>

LODOP.ADD_PRINT_TEXT("108.21mm","123.3mm","9mm","5.03mm","<?=DateYmd(time(),'Y')?>");
LODOP.ADD_PRINT_TEXT("108.21mm","134.94mm","6.35mm","4.76mm","<?=DateYmd(time(),'m')?>");
LODOP.ADD_PRINT_TEXT("108.48mm","146.31mm","6.35mm","4.5mm","<?=DateYmd(time(),'d')?>");

<?php
$wupin_number=0;
$wupin_total=0;

$fromtable='yundan'; $fromid=$rs['ydid'];
if($fromtable&&$fromid)
{
	$location=68.5;//上边距
	$query_wp="select * from wupin where fromtable='{$fromtable}' and fromid='{$fromid}' order by wupin_name desc  limit 5";
	$sql_wp=$xingao->query($query_wp);
	while($wp=$sql_wp->fetch_array())
	{
		$wupin_number+=$wp['wupin_number'];
		$wupin_total+=$wp['wupin_total'];
		
?>
LODOP.ADD_PRINT_TEXT("<?=$location?>mm","29.9mm","52.12mm","3.44mm","<?=cadd($wp['wupin_name'])?>");
LODOP.ADD_PRINT_TEXT("<?=$location?>mm","84.93mm","14.02mm","3.44mm","<?=cadd($wp['wupin_number'])?>");
LODOP.ADD_PRINT_TEXT("<?=$location?>mm","100.28mm","14.02mm","3.44mm","");//hs
LODOP.ADD_PRINT_TEXT("<?=$location?>mm","115.62mm","18.52mm","3.44mm","");//产国
LODOP.ADD_PRINT_TEXT("<?=$location?>mm","137.85mm","16.67mm","3.44mm","");//重量+单位
LODOP.ADD_PRINT_TEXT("<?=$location?>mm","158.22mm","12.96mm","3.44mm","<?=spr($wp['wupin_total'])?>");//总价
<?php
	$location+=4;
	}
	
}
?>



}; 
</script>  