<table width="950" align="center" cellspacing="10">
  <tr>
    <td valign="top"><table border="1" cellspacing="0" bordercolor="#E7E7E7" width="100%" class="gray_border_table">
        <tr>
          <td width="99" align="right" bgcolor="#F2F2F2">ID<br /></td>
          <td width="261" align="left" bgcolor="#F2F2F2" >
           <?=$rs['qjid']?>
         </td>
          <td width="353" align="left" bgcolor="#F2F2F2" >会员:
            <?=$rs['username']?>
            (
            <?=$rs['userid']?>
            )</td>
          <td width="195" align="left" bgcolor="#F2F2F2" >取件:
             <?=DateYmd($rs['qjdate'],2)?>
              
                <?=cadd($rs['qjtime'])?></td>
        </tr>
        <tr>
          <td width="99" align="right">联系方式</td>
          <td colspan="3" align="left"><?=cadd($rs['truename'])?>
               
                <?=$rs['mobile']?>
               </td>
        </tr>
        <tr>
          <td align="right">大约重量</td>
          <td colspan="3" align="left" ><?=cadd($rs['weight'])?> <?=$XAwt?></td>
        </tr>
        <tr>
          <td width="99" align="right">取件地址<br /></td>
          <td colspan="3" align="left"><?=cadd($rs['address'])?></td>
        </tr>
        <?php
if($rs['content']) 
{
?>
        <tr>
          <td width="99" align="right" valign="top">会员备注</td>
          <td colspan="3" align="left" valign="top"><?=TextareaToBr($rs['content'])?></td>
        </tr>
        <?php
}
?>
        <?php
if($rs['reply']) 
{
?>
        <tr>
          <td width="99" align="right" valign="top">管理回复</td>
          <td colspan="3" align="left" valign="top"><?=TextareaToBr($rs['reply'])?></td>
        </tr>
        <?php
}
?>
      </table></td>
  </tr>
</table>


