
<link href="/xingao/yundan/print/style/ansuo_small/css/style.css" rel="stylesheet" type="text/css" />

<div class="yundan_body">
	<div class="head">
		<div class="head_left"><img src="/xingao/yundan/print/style/ansuo_small/images/company_name.jpg" /></div>
		<div class="head_center">TEL電話 <br />FAX傳真</div>
		<div class="head_right"><img src="/public/barcode/?number=<?=cadd($rs['hscode'])?>" height="50" width="330" /></div>
	</div>
	
	<div class="main">
		<div class="main_title"><img src="/xingao/yundan/print/style/ansuo_small/images/yundan_title.jpg" /></div>
		<div class="main_center">
			<div class="main_left">
				<div class="left_lie">
					<ul class="number">1</ul><font>SENDER’S NAME 發件人姓名</font>
					<input name="" type="text" class="left_input" /><ul class="left_ul">TEL 電話</ul>  
					<input name="" type="text" class="left_input" style="width:100px;" /> 
				</div>
				<div class="left_lie">
					<ul class="left_ul">COMPANYNAME 單位名稱</ul>
					<input name="" type="text" class="left_input" />
					<ul class="left_ul">ACCOUNT 賬號</ul>
					<input name="" type="text" class="left_input" style="width:100px;" />
				</div>
				<div class="left_lie1">
					<div class="left_ul">ADDRESS 詳細地址</div>
					<div class="left_text"></div>
					<div class="left_ul">CITY 城市</div>  <input name="" type="text" class="left_input" style="width:30px;" /> 
					<div class="left_ul">COUNTRY 國家</div>  <input name="" type="text" class="left_input" style="width:30px;" /> 
					<div class="left_ul">POSTALCODE 郵政編碼</div>  <input name="" type="text" class="left_input" style="width:30px;" /> 
					<div class="clear"></div>
				</div>
				<div class="left_lie">
					<ul class="number">2</ul><font>CONTACTNAME 收件人姓名</font>
					<input name="" type="text" class="left_input" /><ul class="left_ul" style="border-left:1px solid #363636">TEL 電話</ul>  
					<input name="" type="text" class="left_input" style="width:100px;" /> 
				</div>
				<div class="left_lie">
					<ul class="left_ul">COMPANYNAME 單位名稱</ul>
					<input name="" type="text" class="left_input" />
					<ul class="left_ul" style="border-left:1px solid #363636">ACCOUNT 賬號</ul>
					<input name="" type="text" class="left_input" style="width:100px;"/>
				</div>
				<div class="left_lie1">
					<div class="left_ul">ADDRESS 詳細地址</div>
					<div class="left_text"></div>
					<ul class="left_ul">CITY 城市</ul>  <input name="" type="text" class="left_input" style="width:30px;" /> 
					<ul class="left_ul">COUNTRY 國家</ul>  <input name="" type="text" class="left_input" style="width:30px;" /> 
					<ul class="left_ul">POSTALCODE 郵政編碼</ul>  <input name="" type="text" class="left_input" style="width:30px;" /> 
					<div class="clear"></div>
				</div>
				<div class="left_lie">
					<ul class="number">3</ul><font style="padding-left:100px;">SHIPMENT INFORMATION 物品概況</font>
				</div>
				<div class="left_lie1" style="padding-bottom:5px">
					<ul class="left_ul">NO OF PIECES 件數</ul>  <input name="" type="text" class="left_input" style="width:10px;" /> 
					<ul class="left_ul">WEIGHT 重量</ul>  <input name="" type="text" class="left_input" style="width:20px;" /> 
					<ul class="left_ul">WOL WEIGHT 材重（kg）</ul>  <input name="" type="text" class="left_input" style="width:20px;" /> 
					<div class="clear"></div>
				</div>
				<div class="left_lie2" >
					<div class="left_tiao">
						DESCRIPTIONOFCONTEMENTS貨物描述<br />
	<?php
	$fromtable='yundan'; $fromid=$rs['ydid'];
	if($fromtable&&$fromid)
	{
		$query_wp="select * from wupin where fromtable='{$fromtable}' and fromid='{$fromid}' order by wupin_name desc";
		$sql_wp=$xingao->query($query_wp);
		while($wp=$sql_wp->fetch_array())
		{
			echo cadd($wp['wupin_name']).'；';
		}
	}
	?>
					  <br />
						请如实填写您的货物描述
					</div>
					<div class="left_tiao1">
					DIMENSIONS 尺寸<br /><br />
						<div class="kong">L長</div><input name="" type="text" class="left_input" style="width:3px; margin-top:1px" /><div class="kong">xW寬</div><input name="" type="text" class="left_input" style="width:3px;margin-top:1px" /><div class="kong">xH高</div><input name="" type="text" class="left_input" style="width:3px;margin-top:1px" />(CM)
					</div>
					<div class="left_tiao" style="border:none; width:100PX;">
						報關金額(USD)<br /><br />
					</div>
				</div>
				
			</div>
			<div class="main_right">
				<div class="right_lie" >
					<div class="right_tiao"><ul class="number">4</ul><font style="text-align:center">BASICIMFORMATION<br />基本資訊</font></div>
					<div class="right_tiao1">
						<div class="tiao_up">
							<div class="up_div">ORIGIN 始發地<input name="" type="text" class="right_input"  /></div>
							<div class="up_div">DESTiNATION 目的地<input name="" type="text" class="right_input"  /></div>
						</div>
					</div>
				</div>	
				<div class="right_lie" >
					<ul class="number" style="margin-top:5px">5</ul><font style="margin-top:5px; font-size:11.2px; margin-right:6px;">PACKGING包裝方式</font>
					<div class="right_kuai"><img src="/xingao/yundan/print/style/ansuo_small/images/dian.jpg" />&nbsp;LETTER文件袋 <img src="/xingao/yundan/print/style/ansuo_small/images/dian.jpg" />&nbsp;BOXANDTUBE紙箱 <img src="/xingao/yundan/print/style/ansuo_small/images/dian.jpg" />&nbsp;OTHER其他</div>
				</div>
				<div class="right_lie" style="border:none; height:30PX;" >
					<ul class="number" style="margin-top:5px">6</ul><font style="margin-top:5px">INSURANCE 保險事項</font>
					<div class="right_kuai" style="padding-left:30px;"><img src="/xingao/yundan/print/style/ansuo_small/images/dian.jpg" />&nbsp;YES&nbsp;&nbsp;&nbsp;&nbsp;<img src="/xingao/yundan/print/style/ansuo_small/images/dian.jpg" />&nbsp;NO</div>
				</div>
				<div class="right_lie" style="border:none; height:20PX; font-size:11px; text-align:center" >
					<b style="color:#d92727;">如需報價，</b>請據實填寫內件名稱及數量，據實申報保險金額并交納保價費。 
				</div>
				<div class="right_lie1" >
				INSURANCEVALUE 保險金額<input name="" type="text" class="right_input" style="border-bottom:1px solid #464646; width:130px;"  />USD美元
				</div>
				<div class="right_lie" style="border:none; height:20PX;" >
					<ul class="number" >7</ul><font style="margin-top:2px">PAYMENTTEMS 付款方式</font>
				</div>
				<div class="right_lie2"  style="border:none">
					<div class="right_kuai1"><img src="/xingao/yundan/print/style/ansuo_small/images/dian.jpg" />&nbsp;SEDER 發件人付&nbsp;&nbsp;&nbsp;<img src="/xingao/yundan/print/style/ansuo_small/images/dian.jpg" />&nbsp;COLLECT 收件人付&nbsp;&nbsp;&nbsp;<img src="/xingao/yundan/print/style/ansuo_small/images/dian.jpg" />&nbsp;THIRDPARTY 第三方付<br />
					<img src="/xingao/yundan/print/style/ansuo_small/images/dian.jpg" />&nbsp;MONTHLY 月結&nbsp;&nbsp;&nbsp;<img src="/xingao/yundan/print/style/ansuo_small/images/dian.jpg" />&nbsp;CASH 現金&nbsp;&nbsp;&nbsp;<img src="/xingao/yundan/print/style/ansuo_small/images/dian.jpg" />&nbsp;REMIT/CHECK 匯款/支票  
					</div>
					<div class="clear"></div>
				</div>
				<div class="right_lie1" style="padding-top:5px;">
					ANSONACCOUNT 安梭賬號<input name="" type="text" class="right_input" style="border-bottom:1px solid #464646; width:200px;"  />
				</div>	
				<div class="right_lie" style="height:35px;" >
					<div class="right_tiao2" style="padding-left:0px;"><ul class="number">8</ul>CHARGES 運費<br />
					<input name="" type="text" class="right_input" style="width:70px;"  />
					</div>
					<div class="right_tiao2">INSURANCE 保險費<br />
					<input name="" type="text" class="right_input" style="width:70px;"  />
					</div>
					<div class="right_tiao2">OTHER/VAT 其他<br />
					<input name="" type="text" class="right_input" style="width:70px;"  />
					</div>
					<div class="right_tiao2" style="border:none">TOTAL 費用總計<br />
					<input name="" type="text" class="right_input" style="width:70px;"  />
					</div>
				</div>
				<div class="right_lie" style="border:none; height:20PX;" >
					<ul class="number" >9</ul><font style="margin-top:2px">TRANSPORT MODE 運輸方式</font>
				</div>
				<div class="right_lie2"  style=" height:25PX;" >
					<div class="right_kuai1">EXPRESS 快遞&nbsp;&nbsp;&nbsp;<img src="/xingao/yundan/print/style/ansuo_small/images/dian.jpg" />&nbsp;AIR空運&nbsp;&nbsp;&nbsp;<img src="/xingao/yundan/print/style/ansuo_small/images/dian.jpg" />&nbsp;TRUCK卡車&nbsp;&nbsp;&nbsp;<img src="/xingao/yundan/print/style/ansuo_small/images/dian.jpg" />&nbsp;SEA海運&nbsp;&nbsp;&nbsp;<img src="/xingao/yundan/print/style/ansuo_small/images/dian.jpg" />&nbsp;OTHER其他  
					</div>
					<div class="clear"></div>
				</div>
				<div class="right_lie" style="padding-top:5px; padding-bottom:3px; " >
					<div class="right_tiao3"><ul class="number">10</ul><font style="padding-right:5px; color:#444;">SHIPPER’S SIGNATURE</font><br />發件人簽章</div>
					<div class="right_kuai">
						<input name="" type="text" class="right_input" style=" width:60px;"  />
					</div>
					<div class="right_kuai">
					<input name="" type="text" class="right_input" style="width:20px;"  />M月
					<input name="" type="text" class="right_input" style="width:20px;"  />D日
					<input name="" type="text" class="right_input" style="width:20px;"  />H时
					</div>
				</div>	
				<div class="right_lie" style="padding-top:5px;  padding-bottom:3px; " >
					<div class="right_tiao3"><ul class="number">11</ul>ONSIGNEE’S SIGNATURE<br />收件人簽章</div>
					<div class="right_kuai">
						<input name="" type="text" class="right_input" style=" width:160px;"  />
					</div>
				</div>
				<div class="right_lie" style="padding-top:5px; height:auto;  border:none" >
					<div class="right_tiao3" style="padding-left:20PX"> REMARKS 備註 </div>
					<div class="right_kuai2" >
						<img src="/public/barcode/?number=<?=$number?>" height="50"  />
					</div>
				</div>		
			</div>
			<div class="clear"></div>
		</div>
	</div>
</div>
