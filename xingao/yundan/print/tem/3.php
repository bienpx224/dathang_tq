<table width="1000" align="center" cellspacing="0" class="yundan_body">
  <tr>
    <td colspan="2"><table width="100%" cellspacing="0">
      <tr>
        <td ><table align="left" width="100%">
          <tr>
            <td align="left" valign="bottom"><img src="/images/logo.png" height="86" /><br />
<br /><br />


              <font style="font-size:20px;"><strong>Shipper’S Account No: 50000355</strong></font></td>
            <td align="right"><img src="/public/barcode/?number=<?=cadd($rs['hscode'])?>" width="280" height="120" title="HG码/HS码" /></td>
          </tr>
        </table>        </td>
        </tr>
    </table></td>
  </tr>
  <tr>
    <td valign="top"><table align="center" width="100%" class="gray_border_table" height="100%">
  <tr>
    <td bgcolor="#EBEBEB" ><div align="center"><strong>Consignor 寄件人</strong></div></td>
    <td bgcolor="#EBEBEB" ><div align="center"><strong>Consignee 收件人</strong></div></td>
  </tr>
  <tr>
    <td align="left" valign="top" ><div align="left">CompanyName 公司名称</div></td>
    <td align="left" valign="top" ><div align="left">CompanyName 公司名称</div></td>
  </tr>
  <tr>
    <td valign="top" ><div align="left">Sent By 发件人：
        <?=cadd($rs['f_name'])?>
    </div></td>
    <td valign="top" ><div align="left">Sent To 收件人：
        <?=cadd($rs['s_name'])?>
    </div></td>
  </tr>
  <tr>
    <td height="46" valign="top"><div align="left">Address 地址：
        <?=yundan_add_all($rs,'f')?>
    </div></td>
    <td valign="top"><div align="left">Address 地址：
        <?php if($ON_country){echo yundan_Country($rs['country']);}?>
         <?=yundan_add_all($rs,'s')?>
    </div></td>
  </tr>
  <tr>
    <td valign="top"><div align="left">Zip/Post  邮编：
        <?=cadd($rs['f_zip'])?>
    </div></td>
    <td valign="top"> <div align="left">City/Sate/Country　城市/州/国家</div></td>
  </tr>
  <tr>
    <td height="48" valign="top"><div align="left">Tel No. 电话号码：
        <?=cadd($rs['f_mobile_code'])?>
      -
      <?=cadd($rs['f_mobile'])?>
      <br />
      <?=cadd($rs['f_tel'])?>
    </div></td>
    <td valign="top"><div align="left">Tel No. 电话号码：
        <?=cadd($rs['s_mobile_code'])?>
      -
      <?=cadd($rs['s_mobile'])?>
      <br />
      <?=cadd($rs['s_tel'])?>
    </div></td>
  </tr>
  <tr>
    <td valign="top"><table align="center" width="100%">
      <tr>
        <td ><div align="left">Time AM:<br />
          时间 Pm:</div></td>
        <td><div align="left">Date Issued:<br />
          发件日期:</div></td>
      </tr>
      
    </table></td>
    <td valign="top"><table align="center" width="100%">
      <tr>
        <td colspan="2" ><div align="left">Time AM:<br />
          时间 Pm:</div></td>
        <td><div align="left">Date Received:<br />
          收件日期:</div></td>
      </tr>
      
    </table></td>
  </tr>
  <tr>
    <td valign="top"><div align="left"> Content Desciption 品名：<br />
          <?=cadd($rs['goodsdescribe'])?>
    </div></td>
    <td><table align="center" width="100%">
      
      <tr>
        <td valign="top"><div align="center">&nbsp;Pieces<br />
          件数</div></td>
        <td valign="top"><div align="center">Weight<br />
          毛重(千克)<br />
          <br />
		   <?=spr($rs['weight']*$XAwtkg,3)?>
          </div>
            <div align="center"></div></td>
        <td valign="top"><div align="center">Dimensions 体积</div></td>
      </tr>
    </table></td>
  </tr>
  <tr>
    <td colspan="2"><table align="center" width="100%">
      <tr>
        <td valign="top" width="150" height="50">&nbsp;</td>
        <td valign="top"><div align="left"> Courier Signature:<br />
          速递签收: </div></td>
        <td valign="top"><div align="left"> Consignee's Signature:<br />
          收件人签收: </div></td>
      </tr>
    </table></td>
  </tr>
</table>
</td>
    <td  width="350" valign="top"><table width="100%" border="1" cellspacing="0" bordercolor="#E7E7E7">
         <tr>
            <td bgcolor="#EBEBEB"><div align="center"><strong>Service Tyep</strong></div></td>
          </tr>
         <tr>
           <td><table width="100%">
             
             <tr>
               <td><div align="center">
                   <p>Document<br />
                       <input type="checkbox" name="checkbox3" id="checkbox3" />
                   </p>
               </div></td>
               <td><div align="center">
                   <p>Parcel<br />
                       <input name="checkbox2" type="checkbox" id="checkbox2" checked="checked" />
                   </p>
               </div></td>
               <td><div align="center">
                   <p>Others<br />
                       <label>
                       <input type="checkbox" name="checkbox" id="checkbox" />
                       </label>
                   </p>
               </div></td>
             </tr>
           </table></td>
         </tr>  
          <tr>
            <td bgcolor="#EBEBEB"><div align="center"><strong>Declared Value For Customs</strong></div></td>
          </tr>
          <tr>
            <td align="left"><?=cadd($rs['declarevalue'])*exchange($XAScurrency,$XAMcurrency);?><?=$XAmc?></td>
          </tr>
          <tr>
            <td bgcolor="#EBEBEB">&nbsp;</td>
          </tr>
          <tr>
            <td height="61"><div align="left"></div></td>
          </tr>
          <tr>
            <td bgcolor="#EBEBEB">&nbsp;</td>
          </tr>
          <tr>
            <td><div align="center" style="font-size:23px; line-height:35px;">
			<strong>
              <?=cadd($rs['whPlace'])?>
              </strong>
            </div></td>
          </tr>
          <tr>
            <td><div align="center"><img src="/public/barcode/?number=<?=$number?>" width="340" height="120"  title="运单号"/></div></td>
          </tr>
    </table>    </td>
  </tr>
  
  
  
</table>
