<table width="1000" align="center" cellspacing="10"  class="yundan_body">
  <tr>
    <td valign="top"  width="450px">
    
    <table border="1" align="center" cellspacing="0" bordercolor="#E7E7E7" width="100%">
      <tr>
        <td colspan="4"  class="Gray_bg"><div align="left"><strong>收货人</strong></div></td>
      </tr>
      <tr>
        <td  width="45"><div align="left">姓名</div></td>
        <td width="100"><div align="left">
          <?=cadd($rs['s_name'])?>
        </div></td>
        <td rowspan="2" width="45"><div align="left">地址</div></td>
        <td rowspan="2">
		  <div align="left">
		   <?php if($ON_country){echo yundan_Country($rs['country']);echo '<br>'; }?>
		    
		       <?=yundan_add_all($rs)?>
	      </div></td>
      </tr>
      <tr>
        <td><div align="left">手机</div></td>
        <td><div align="left"><?=cadd($rs['s_mobile'])?>
        </div></td>
      </tr>
      <tr>
        <td><div align="left">固话</div></td>
        <td><div align="left">
          <?=cadd($rs['s_tel'])?>
        </div></td>
        <td><div align="left">邮编</div></td>
        <td><div align="left">
          <?=cadd($rs['s_zip'])?>
        </div></td>
      </tr>
    </table>
  <br />

<table border="1" align="center" cellspacing="0" bordercolor="#E7E7E7"  width="100%">
          <tr>
            <td colspan="4"  class="Gray_bg"><div align="left"><strong>发货人</strong></div></td>
          </tr>
          <tr>
            <td width="45"><div align="left">姓名</div></td>
            <td  width="100"><div align="left">
              <?=cadd($rs['f_name'])?>
            </div></td>
            <td width="45" rowspan="2"><div align="left">地址</div></td>
            <td rowspan="2"><div align="left">
             <?=yundan_add_all($rs,'f')?>
            </div></td>
          </tr>
          <tr>
            <td><div align="left">手机</div></td>
            <td><div align="left"><?=cadd($rs['f_mobile'])?>
            </div></td>
          </tr>
          <tr>
            <td><div align="left">固话</div></td>
            <td><div align="left">
              <?=cadd($rs['f_tel'])?>
            </div></td>
            <td><div align="left">邮编</div></td>
            <td><div align="left">
              <?=cadd($rs['f_zip'])?>
            </div></td>
          </tr>
        </table>

    </td>
    <td valign="top" width="500"><table width="100%" border="1" cellspacing="0" bordercolor="#E7E7E7">
      <tr>
        <td  class="Gray_bg"><div align="center" style="font-size:20px;"><strong><?=cadd($sitename)?></strong></div></td>
      </tr>
    </table>
        <br />
        <table width="100%" border="1" cellspacing="0" bordercolor="#E7E7E7">
          <tr>
            <td  class="Gray_bg"><div align="left"><strong>货品描述</strong></div></td>
          </tr>
          <tr>
            <td><div align="left">
              <?=cadd($rs['goodsdescribe'])?>
            </div></td>
          </tr>
        </table>
    <br />
        <table width="100%" border="1" cellspacing="0" bordercolor="#E7E7E7">
          <tr>
            <td colspan="2"  class="Gray_bg"><div align="left"><strong>备注</strong></div></td>
          </tr>
          <tr>
            <td rowspan="2"><div align="left">
              <?=TextareaToBr($rs['content'])?>
            </div></td>
            <td width="170"><div align="left">重量：
             <?=spr($rs['weight'])?><?=$XAwt?>
            </div></td>
          </tr>
          <tr>
            <td><div align="left">运单号：
              <?=cadd($rs['ydh'])?>
            </div></td>
          </tr>
    </table></td>
  </tr>
  <tr>
    <td colspan="2"><table width="100%" border="1" cellspacing="0" bordercolor="#E7E7E7">
      <tr>
        <td width="592"  class="Gray_bg"><div align="left"><strong>运单号</strong></div></td>
      </tr>
      <tr bordercolor="#CCCCCC">
        <td align="center"><div align="center"><img src="/public/barcode/?number=<?=$number?>" /></div></td>
      </tr>
      <tr bordercolor="#CCCCCC">
        <td><table width="100%" border="0" cellspacing="0" bordercolor="#CCCCCC">
          <tr>
            <td align="center"><div align="center"><img src="/public/barcode/?number=<?=$number?>" /></div></td>
            <td align="center"><div align="center"><img src="/public/barcode/?number=<?=$number?>" /></div></td>
          </tr>
        </table></td>
      </tr>
    </table></td>
  </tr>
</table>
