
<?php $print_i++;?>
<script language="javascript" type="text/javascript">
function CreatePrintPage<?=$print_i?>(){
LODOP.PRINT_INITA("-0.53mm","0.26mm","295.01mm","152.51mm","<?=yundan_print($print_tem)?>-兴奥转运打印");
LODOP.SET_PRINT_PAGESIZE(1,2950,1525,"");//设置纸张高度宽度 (默认单位为0.1mm,如800=80mm)
LODOP.SET_SHOW_MODE("BKIMG_WIDTH","293.95mm");
LODOP.SET_SHOW_MODE("BKIMG_HEIGHT","151.87mm");

LODOP.ADD_PRINT_TEXT("22.75mm","72.76mm","10.05mm","4.76mm","<?=DateYmd(time(),'Y')?>");
LODOP.ADD_PRINT_TEXT("23.28mm","90.22mm","6.88mm","4.5mm","<?=DateYmd(time(),'m')?>");
LODOP.ADD_PRINT_TEXT("23.55mm","100.54mm","6.61mm","3.97mm","<?=DateYmd(time(),'d')?>");
LODOP.ADD_PRINT_TEXT("30.43mm","36.25mm","73.82mm","30.43mm","<?=CompanySend('sendName')?>\n<?=CompanySend('sendAdd')?>\n<?=cadd($rs['ydh'])?>\n<?=cadd($rs['whPlace'])?> (<?=spr($rs['payment']+$rs['tax_payment'])?>)\n<?=channel_name($mr['groupid'],$rs['warehouse'],$rs['country'],$rs['channel'])?>");//换行\n  发送人栏(可用于打印其他备注信息)
LODOP.SET_PRINT_STYLEA(0,"FontSize",10);

LODOP.ADD_PRINT_TEXT("67.2mm","53.45mm","32.54mm","6.09mm","<?=CompanySend('sendZip')?>");
LODOP.ADD_PRINT_TEXT("78.05mm","39.69mm","32.81mm","4.76mm","<?=CompanySend('sendTel')?>");
LODOP.ADD_PRINT_TEXT("78.32mm","76.99mm","33.34mm","4.76mm","<?=CompanySend('sendFax')?>");//发件传真
LODOP.ADD_PRINT_TEXT("129.65mm","37.31mm","48.15mm","6.35mm","<?=CompanySend('sendName')?>");
LODOP.ADD_PRINT_TEXT("114.04mm","181.77mm","24.08mm","5.03mm","<?=spr($rs['declarevalue'])?>");//内容品合计
LODOP.ADD_PRINT_TEXT("108.21mm","227.54mm","39.16mm","9.26mm","<?=spr($rs['insureamount'])?>");//损害要
LODOP.ADD_PRINT_TEXT("22.23mm","141.82mm","11.91mm","4.76mm","<?=DateYmd(time(),'h')?>");
LODOP.ADD_PRINT_TEXT("22.23mm","159.01mm","11.91mm","4.76mm","<?=DateYmd(time(),'i')?>");
LODOP.ADD_PRINT_TEXT("22.23mm","175.42mm","16.4mm","4.76mm","<?=spr($rs['fee_transport'])?>");
<?php 
//按$rs['insureamount']计算保价费用
/*ＥＭＳ有保价功能，≤2万日元免费，大于2万日元，每增加2万日元，其保价金额为增加50日元。如2万-4万为50日元，4万-6万是100日元以此类推。*/
if(!$exchange_JPY){$exchange_JPY=exchange($XAScurrency,'JPY');}//防止每次都查询
$rs['insureamount']=spr($rs['insureamount']*$exchange_JPY);

if($rs['insureamount']<=20000){$rs['insurevalue']=0;}
else{$rs['insurevalue']=ceil($rs['insureamount']/20000-1)*50;}

$rs['insurevalue']=spr($rs['insurevalue']*$exchange_JPY);
?>
LODOP.ADD_PRINT_TEXT("21.7mm","196.59mm","14.02mm","5.03mm","<?=spr($rs['insurevalue'])?>");//储料金
LODOP.ADD_PRINT_TEXT("30.16mm","179.65mm","24.61mm","5.29mm","<?=spr($rs['fee_transport']+$rs['insurevalue'])?>");
LODOP.ADD_PRINT_TEXT("28.05mm","137.32mm","34.66mm","7.67mm","<?=spr($rs['weight'],2,0,1).$XAwt?>");
LODOP.ADD_PRINT_TEXT("41.54mm","113.24mm","98.16mm","28.05mm","<?=cadd($rs['s_name'])?>\n<?=yundan_add_all($rs,'s')?>");
LODOP.SET_PRINT_STYLEA(0,"FontSize",10);

LODOP.ADD_PRINT_TEXT("77.52mm","117.21mm","43.13mm","5.03mm","<?=Country($rs['country'],'','EN')?>");
LODOP.SET_PRINT_STYLEA(0,"FontSize",10);

LODOP.ADD_PRINT_TEXT("78.05mm","167.22mm","41.8mm","5.82mm","<?=cadd($rs['s_mobile_code'])?>-<?=cadd($rs['s_mobile'])?>");
LODOP.SET_PRINT_STYLEA(0,"FontSize",10);

LODOP.ADD_PRINT_TEXT("86.78mm","168.8mm","40.22mm","5.29mm","");//收件传真

LODOP.ADD_PRINT_TEXT("119.59mm","35.72mm","3.97mm","4.76mm","●");
LODOP.ADD_PRINT_TEXT("138.38mm","24.87mm","4.76mm","4.76mm","●");
LODOP.ADD_PRINT_TEXT("96.84mm","169.07mm","3.18mm","3.44mm","●");
LODOP.ADD_PRINT_TEXT(272,443,163,19,"<?=cadd($rs['s_zip'])?>");//收件邮编

/*LODOP.ADD_PRINT_TEXT("101.07mm","169.07mm","3.18mm","3.44mm","●");
LODOP.ADD_PRINT_TEXT("105.04mm","169.07mm","3.18mm","3.44mm","●");
LODOP.ADD_PRINT_TEXT("96.57mm","193.41mm","3.18mm","3.44mm","●");
LODOP.ADD_PRINT_TEXT("101.07mm","193.41mm","3.18mm","3.44mm","●");
LODOP.ADD_PRINT_TEXT("105.3mm","193.41mm","3.18mm","3.44mm","●");
*/
<?php
$wupin_number=0;
$wupin_total=0;

$fromtable='yundan'; $fromid=$rs['ydid'];
if($fromtable&&$fromid)
{
	$location=92;//上边距
	$query_wp="select * from wupin where fromtable='{$fromtable}' and fromid='{$fromid}' order by wupin_name desc  limit 5";
	$sql_wp=$xingao->query($query_wp);
	while($wp=$sql_wp->fetch_array())
	{
		$wupin_number+=$wp['wupin_number'];
		$wupin_total+=$wp['wupin_total'];
?>
LODOP.ADD_PRINT_TEXT("<?=$location?>mm","34.93mm","41.54mm","3.44mm","<?=cadd($wp['wupin_name'])?>");
LODOP.ADD_PRINT_TEXT("<?=$location?>mm","83.08mm","11.11mm","3.44mm","");//HS
LODOP.ADD_PRINT_TEXT("<?=$location?>mm","98.16mm","12.17mm","3.44mm","");//内容
LODOP.ADD_PRINT_TEXT("<?=$location?>mm","115.09mm","12.17mm","3.44mm","<?=cadd($wp['wupin_number'])?>");//数量
LODOP.ADD_PRINT_TEXT("<?=$location?>mm","132.56mm","12.17mm","3.44mm","");//重量G
LODOP.ADD_PRINT_TEXT("<?=$location?>mm","148.96mm","12.17mm","3.44mm","<?=spr($wp['wupin_total'])?>");//总价
<?php
	$location+=4;
	}
	
}
?>


}; 
</script>  