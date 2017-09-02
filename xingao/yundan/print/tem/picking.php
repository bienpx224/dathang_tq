<!--打包清单-->
<?php $first_i++;?>

<?php if($first_i==1){?>
	<style>
    .yundan_body { font-size: 14px; line-height: 25px; }
    </style>
    <?php $callFrom=$callFrom;//member 会员中心($callFrom print.php传值)
    $mr=FeData('member','groupid,useric',"userid='{$rs['userid']}'");?>
    <table width="1000" align="center" cellspacing="10" class="yundan_body">
      <tr>
        <td valign="top">
      <div class="print_title_big_1" align="center"><?=warehouse($rs['warehouse'])?> <?=yundan_print('picking')?></div>   
        <table border="1" cellspacing="0" bordercolor="#E7E7E7" width="100%" class="gray_border_table">
            <tbody>
              <tr>
                <td align="center"><strong>序号</strong></td>
                <td align="left"><strong>包裹号</strong></td>
                <td align="center"><strong>仓位</strong></td>
                <td align="center"><strong>入库码</strong></td>
                <td align="center"><strong>会员</strong></td>
                <td align="center"><strong>重量</strong></td>
                <td align="center"><strong>下运单时间</strong></td>
                <td align="center"><strong>运单号</strong></td>
              </tr>
<?php }?>          
          
          
          
          
<?php if($rs['bgid']){
	
		if(!$bgid){$bgid=0;}
		if(!$bgid_old){$bgid_old=0;}
		
		$bgid.=','.$rs['bgid'];//待显示的包裹
		
		$query_bg="select bgydh,bgid,addSource,whPlace,weight from baoguo where bgid in ({$bgid}) and bgid not in ({$bgid_old})  order by bgydh asc";
		$sql_bg=$xingao->query($query_bg);
		while($bg=$sql_bg->fetch_array())
		{
			$bg_i++;
		?>
        <!--包裹下单-包裹列表-->
          <tr>
            <td align="center"><?=$bg_i?></td>
            <td align="left"><?=cadd($bg['bgydh'])?></td>
            <td align="center"><?=cadd($bg['whPlace'])?></td>
            <td align="center"><?=cadd($mr['useric'])?></td>
            <td align="center"><?=cadd($rs['username'])?> <font class="gray2">(<?=cadd($rs['userid'])?>)</font></td>
            <td align="center"><?=spr($bg['weight']).$XAwt?></td>
            <td align="center"><?=DateYmd($rs['addtime'])?></td>
            <td align="center">
	<?php 
    //显示已生成的运单号
    $yd_i=0;
    $query_yd="select ydid,ydh,status from yundan where find_in_set('{$bg[bgid]}',bgid) order by ydh asc";
    $sql_yd=$xingao->query($query_yd);
    while($yd=$sql_yd->fetch_array())
    {
        $yd_i++;
        echo "({$yd_i})".cadd($yd['ydh']).'('.status_name(spr($yd['status'])).')<br>';
    }
    ?>
            </td>
          </tr>
<?php
	}//while($bg=$sql_bg->fetch_array())
	$bgid_old.=','.$rs['bgid'];//已显示的包裹


}else{?>
        <!--直接下单-无包裹-->
          <tr>
            <td align="center"></td>
            <td align="left">直接下单</td>
            <td align="center"><?=cadd($rs['whPlace'])?></td>
            <td align="center"><?=cadd($mr['useric'])?></td>
            <td align="center"><?=cadd($rs['username'])?> <font class="gray2">(<?=cadd($rs['userid'])?>)</font></td>
            <td align="center"><?=spr($rs['weight']).$XAwt?></td>
            <td align="center"><?=DateYmd($rs['addtime'])?></td>
            <td align="center"><?=cadd($rs['ydh']).'('.status_name(spr($rs['status'])).')';?></td>
          </tr>
<?php }?>








<?php if($YDnumber==$first_i){?>
        </tbody>
      </table></td>
  </tr>
</table>
<?php }?>

