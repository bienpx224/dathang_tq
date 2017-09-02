<!--打包清单-->
<style>.yundan_body{font-size:24px; line-height:30px;}</style>
<?php $callFrom=$callFrom;//member 会员中心($callFrom print.php传值)
$mr=FeData('member','groupid,useric',"userid='{$rs['userid']}'");
?>
<table width="1000" align="center" cellspacing="10" class="yundan_body">
	<tr>
		<td valign="top">        <table border="1" cellspacing="0" bordercolor="#E7E7E7" width="100%" class="gray_border_table">

		  <tbody>
		    <tr>
		      <td class="print_title_big_1" width="150" >客户编号：</td>
		      <td class="print_title_big_1" ><?=cadd($rs['userid'])?></td>
		      <td colspan="2" align="right" class="print_title_big_1" > 分箱数量：
<?php 
//显示已生成的运单号
$yd_i=0;
$query_yd="select ydh from yundan where bgid='{$rs[bgid]}' order by ydh asc";
$sql_yd=$xingao->query($query_yd);
$num=mysqli_num_rows($sql_yd);
while($yd=$sql_yd->fetch_array())
{
	$yd_i++;
	if($yd['ydh']==$rs['ydh']){echo $yd_i;}
}
?>

               /<?=$num?></td>
	        </tr>
		    <tr>
		      <td rowspan="2">运单号：</td>
		      <td rowspan="2"><img src="/public/barcode/?number=<?=$number?>" /></td>
		      <td width="180">运单生成日期：</td>
		      <td><?=DateYmd($rs['addtime'])?></td>
	        </tr>
		    <tr>
		      <td>运单打印日期：</td>
		      <td><?=DateYmd(time())?></td>
	        </tr>
		    <tr>
		      <td>运单状态：</td>
		      <td><?=status_name(spr($rs['status']))?>	</td>
		      <td>&nbsp;</td>
		      <td>&nbsp;</td>
	        </tr>
		    <tr>
		      <td>发货渠道：</td>
		      <td colspan="3"><?=channel_name($mr['groupid'],$rs['warehouse'],$rs['country'],$rs['channel'])?></td>
	        </tr>
		    <tr>
		      <td>分箱运单号：</td>
		      <td colspan="3">
<?php 
//显示已生成的运单号
$yd_i=0;
$query_yd="select ydid,ydh,status from yundan where bgid='{$rs[bgid]}'  order by ydh asc";
$sql_yd=$xingao->query($query_yd);
while($yd=$sql_yd->fetch_array())
{
	$yd_i++;
	echo "({$yd_i})".cadd($yd['ydh']).'('.status_name(spr($yd['status'])).')<span class="xa_sep"> | </span>';
}
?>
              </td>
	        </tr>
		    <tr>
		      <td>申报物品： </td>
		      <td colspan="3">
 
             <table width="100%">
                  <tbody>
                    <tr>
    <?php if($ON_wupin_brand){?><td align="left" class="title"><strong><?=$LG['wupin.brand']?></strong></td><?php }?>
    <?php if($ON_wupin_name){?><td align="center" class="title"><strong><?=$LG['wupin.name']?></strong></td><?php }?>
    <?php if($ON_wupin_number){?><td align="center" class="title"><strong><?=$LG['wupin.number']?></strong></td><?php }?>
    <?php if($ON_wupin_price){?><td align="center" class="title"><strong><?=$LG['wupin.price']?> <?="(".$XAsc.")"?></strong></td><?php }?>
                    </tr>
                    
    <?php
	$fromtable='yundan';$fromid=$rs['ydid'];
    if($fromtable&&$fromid)
    {
        $query_wp="select * from wupin where fromtable='{$fromtable}' and fromid='{$fromid}' order by wupin_brand asc,wupin_name asc";
        $sql_wp=$xingao->query($query_wp);
        while($wp=$sql_wp->fetch_array())
        {
        ?>
        <tr>
            <?php if($ON_wupin_brand){?><td align="left"><?=cadd($wp['wupin_brand'])?></td><?php }?>
            <?php if($ON_wupin_name){?><td align="center"><?=cadd($wp['wupin_name'])?></td><?php }?>
            <?php if($ON_wupin_number){?><td align="center"><?=spr($wp['wupin_number'])?></td><?php }?>
            <?php if($ON_wupin_price){?><td align="center"><?=spr($wp['wupin_price'])?></td><?php }?>
        </tr>
        <?php
        }
    }
    ?>
                  </tbody>
                </table>   
                             
    
              </td>
	        </tr>
		    <tr>
		      <td>包裹明细：</td>
		      <td colspan="3">
              
                <?php yundan_bg_list($rs['bgid'],'manage')?>
                
</td>
	        </tr>
		    <tr>
		      <td>收货地址：</td>
		      <td colspan="3"><?php if($ON_country){echo yundan_Country($rs['country']);echo '<br>'; }?> 
			  <?=yundan_add_all($rs)?></td>
	        </tr>
		    <tr>
		      <td>客人备注：</td>
		      <td colspan="3"><?php  
		//基本资料
		$callFrom=$callFrom;//member 会员中心($callFrom print.php传值)
		$call_payment=0;//费用及付款情况
		$call_basic=0;//基本资料
		$call_op=1;//操作要求
		$call_baoguo=0;//包裹
		$call_goodsdescribe=0;//货物
		$call_content=1;//备注
		$call_reply=1;//回复
		$call_print=1;//打印调用
		$callFrom_show=1;//显示全部文字内容
		require($_SERVER['DOCUMENT_ROOT'].'/xingao/yundan/call/basic_show.php');
		?></td>
	        </tr>
	      </tbody>
		  </table>
          
          
          
<div align="center"><br><img src="/public/barcode/?number=<?=$number?>" width="400"/></div>
<div align="center"><br><img src="/public/barcode/?number=<?=$number?>" width="400"/></div>          
          
          
      </td>
	</tr>
</table>

