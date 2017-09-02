
<table width="100%"  style=" margin-bottom:10px;">
    <tr>
      <td width="110">
		<button type="button" class="btn btn-info"  onClick="document.getElementById('code_tg').select();document.execCommand('Copy');alert('<?=$LG['main.42']?>');"><i class="icon-copy"></i> <?=$LG['main.41'];//复制链接?> </button>
      </td>
      <td>
      <?php 
	  //鼠标移上,显示赠送提示说明
	  if($tgycp_types==1){$tgycp_value.=$XAmc;}else{$tgycp_value.=$LG['fold'];}
	  if($xhycp_types==1){$xhycp_value.=$XAmc;}else{$xhycp_value.=$LG['fold'];}
	  $tgppt=$tuiguang_tgy.$LG['integral']//X积分
	  .($tgycp_number?$LG['and'].$tgycp_number.$LG['coupons.zhang'].$tgycp_value.Coupons_Types($tgycp_types):'')//,X张优惠券/折扣券
	  .($LG['main.60'].$tuiguang_xhy.$LG['integral'])//；您的朋友可获得X积分
	  .($xhycp_number?$LG['and'].$xhycp_number.$LG['coupons.zhang'].$xhycp_value.Coupons_Types($xhycp_types):'')//,X张优惠券/折扣券
	  ?>


     <input id="code_tg" readonly class="tgfz popovers" data-trigger="hover" data-placement="top"  
      data-content="<?=$LG['main.59'];//您的朋友从该链接注册新会员时，您就可获得?><?=$tgppt?>"
      value="<?=$siteurl?>xamember/reg.php?tg=<?=$Muserid?>"  
      onClick="select();" style="color:#666666;">
      </td>
      
       <td width="50" align="right">
       
       <!--二维码-->
       <?php 
	   $promoteQR=cadd($mr['promoteQR']);	if(!$promoteQR){$promoteQR=cadd($rs['promoteQR']);}
	   
	   //生成
	   if(!$promoteQR||spr($_GET['promoteQR_create'])||!HaveFile($promoteQR))
	   {
		  $qr_logo=FeData('member','img',"userid='{$Muserid}'");
		  qrcode(
			  $qr_text="{$siteurl}xamember/reg.php?tg={$Muserid}",
			  'M','','',
			  $qr_logo,
			  $qr_outfile='',
			  $promoteQR="/upxingao/qrcode/URLtg{$Muserid}.png"
		  );
		  $xingao->query("update member set promoteQR='".add($promoteQR)."' where userid='{$Muserid}'");SQLError('保存promoteQR');
	   }
	   ?>
       <li class="dropdown"> <a href="javascript:void(0)" class="dropdown-toggle" data-toggle="dropdown" data-hover="dropdown"> <img src="/images/qrico.png" width="35" height="35" /> </a>
        <ul class="dropdown-menu" style="width:210px; text-align: center; left:-150px;">
        <a href="<?=$promoteQR?>" target="_blank"><img src="<?=$promoteQR?>" width="200" height="200" title="<?=$LG['member.9']//下载保存:鼠标右键另存为...?>"/></a>
        <li><a href="?promoteQR_create=1"><?=$LG['member.8']//重新生成?></li>
        </ul>
      </li>
      
      
      </td>
      
  </tr>
</table>

<?=$LG['main.61']//我的邀请号码:?>
<font style="color:#000000" class="tghm popovers" data-trigger="hover" data-placement="top"  data-content="<?=$LG['main.62'];//只要在注册时填写该号码，您就可获得?><?=$tgppt?>">
<?=$Muserid?>
</font>


<font class="gray_prompt3">( <?=$LG['main.73']//总共成功邀请?>
<font class="tghm">
<?php
echo mysqli_num_rows($xingao->query("select userid from tuiguang_bak where status='1' {$Mmy}"));
?>
</font>
<?=$LG['main.74']//位，今天成功邀请了?>

<font class="tghm">
<?php
$tg_start =strtotime(date('Y-m-d')." 00:00:00");
echo mysqli_num_rows($xingao->query("select userid from tuiguang_bak where status='1' and addtime>='{$tg_start}' {$Mmy}"));
?>
</font><?=$LG['main.76']//位?>,<font class="popovers" data-trigger="hover" data-placement="top"  data-content="<?=$LG['main.44'];//系统怀疑为作弊注册的无效邀请，不送积分?>"><?=$LG['main.75']//无效邀请共有?><strong>
<?php
echo mysqli_num_rows($xingao->query("select userid from tuiguang_bak where status='0' {$Mmy}"));
?>
</strong><?=$LG['main.76']//位?>)</font>
</font> 
