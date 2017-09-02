<!--处理清单-->
<style>
.baoguo_body { font-size: 24px; line-height: 30px; }
.imgslist img{width:500px;}
</style>
<?php $callFrom=$callFrom;//member ($callFrom print.php传值)?>
<table width="1000" align="center" cellspacing="10" class="baoguo_body">
  <tr>
    <td valign="top"><table border="1" cellspacing="0" bordercolor="#E7E7E7" width="100%" class="gray_border_table">
        <tr>
          <td  align="right" width="140" bgcolor="#F2F2F2" ><strong>所属会员：</strong></td>
          <td  align="left" bgcolor="#F2F2F2" ><?=cadd($rs['username'])?> <font class="gray2">(<?=cadd($rs['userid'])?>)</font> <font style="float:right; font-size:16px;" class="gray2">来源:<?=baoguo_addSource($rs['addSource'])?> &nbsp;&nbsp;  入库时间:<?=DateYmd($rs['rukutime'])?></font></td>
        </tr>
        <tr>
          <td align="right" valign="top"><strong>仓库/仓位：</strong></td>
          <td  align="left" ><?=warehouse(cadd($rs['warehouse']))?> / <?=cadd($rs['whPlace'])?></td>
        </tr>
        <tr>
          <td align="right" valign="top"><strong>操作服务：</strong></td>
          <td  align="left" >
		<?php  
		//底部通用内容调用
		$callFrom_show=1;
		require($_SERVER['DOCUMENT_ROOT'].'/xingao/baoguo/call/basic_show.php');
		require($_SERVER['DOCUMENT_ROOT'].'/xingao/baoguo/call/hx_show.php');
		require($_SERVER['DOCUMENT_ROOT'].'/xingao/baoguo/call/fx_show.php'); 
		?>
        <?=EnlargeImg($rs['th_img'],$rs['bgid'],1)?>
        </td>
        </tr>
        <tr>
          <td  align="right" valign="top"><strong>操作员：</strong></td>
          <td  align="left" ></td>
        </tr>
        <tr>
          <td colspan="2" align="center" valign="top"><img src="/public/barcode/?number=<?=$number?>" /></td>
        </tr>
      </table></td>
  </tr>
</table>
<br>
<br>
