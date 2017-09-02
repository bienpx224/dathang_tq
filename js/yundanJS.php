<script type="text/javascript">
//-------------------------------------------------通用部分------------------------------------------
function limitInput(o)
{
	var value=o.value;
	var min=0;
	var max=declareValue_max;
	if(parseInt(value)<min||parseInt(value)>max)
	{
		o.value=declareValue_max;
		document.getElementById('text').innerHTML = "<?=$LG['js.10']//不能超过物品总价值?>";/*在页面显示*/
	}else{document.getElementById('text').innerHTML = "";}
}


//判断输入是否为数字
function onlyNum()
{
	if(!(event.keyCode==46)&&!(event.keyCode==8)&&!(event.keyCode==37)&&!(event.keyCode==39))
	if(!((event.keyCode>=48&&event.keyCode<=57)||(event.keyCode>=96&&event.keyCode<=105)))
	event.returnValue=false;
}
</script>






<script type="text/javascript">
//-------------------------------------------显示国家下拉菜单(并非只有表单页用)-----------------------------
function country_show(groupid,country)
{
	//有多国家时
	<?php if($ON_country){?>
	var userid=0;var username='';
	if(!groupid)
	{
		var username=document.getElementsByName("username")[0].value;
		var userid=document.getElementsByName("userid")[0].value;
	}
	var warehouse=document.getElementsByName("warehouse")[0].value;
	
	$.ajax({
        type: "POST",
        cache: false,
        data: 'lx=country&groupid='+groupid+'&username='+username+'&userid='+userid+'&warehouse='+warehouse+'&country=<?=$country?>',
        async: false,//true导步处理;false为同步处理
        url: "/public/ajax.php",
        success: function (data) 
		{
			result = data;
		}
    });
	<?php }?>


	if($('[id="country"]').length>0)
	{
		document.getElementById('country').innerHTML='<select  class="form-control select2me xa_select2" data-placeholder="<?=$LG['yundan.form_18'];//请选择?>" name="country" <?=$CountryRequired?'required':''?> style="max-width:240px;" onChange="<?=$forForm?'refresh_form();':'channel_show();'?>">'+result+'</select>'; 
	}
	
	if($('[id="channel"]').length>0)
	{
		channel_show();//显示渠道下拉
	}

	
	<?php if($ON_cardInstead&&$callFrom=='manage'){?>
	if($('[id="cardInstead_msg"]').length>0)
	{
		cardInstead();//代替证件
	}
	<?php }?>
}





<?php if($forForm){?>
//------------------------------------显示渠道下拉(表单专用)------------------------------------------
/*其他页面,每个页面都有各自不同功能的channel_show()*/
function channel_show() 
{
	if($('[name="warehouse"]').length<=0){return;}//不存在
	if($('#channel').length<=0){return;}//不存在
	var warehouse=document.getElementsByName("warehouse")[0].value;
	var country=document.getElementsByName("country")[0].value;
	
	//后台用
	var username='';	if($('[name="username"]').length>0)		{username=$('[name="username"]')[0].value;}
	var userid='';		if($('[name="userid"]').length>0)		{userid=$('[name="userid"]')[0].value;}
	
	$.ajax({
        type: "POST",
        cache: false,
        data: 'lx=channel&callFrom=member&channel=<?=$channel?>&warehouse='+warehouse+'&country='+country+'&username='+username+'&userid='+userid+'',
        async: false,//true导步处理;false为同步处理
        url: "/public/ajax.php",
        success: function (data) 
		{
			result = data;
		}
    });

	document.getElementById('channel').innerHTML='<select  class="form-control input-medium select2me" data-placeholder="<?=$LG['yundan.form_18'];//请选择?>" name="channel" required onChange="refresh_form();">'+result+'</select>'; //channelPar();  //后台没有calc(); 不要加

}
<?php }?>

</script>


<script type="text/javascript">
//-------------------------------------------------代替证件------------------------------------------
function cardInstead(typ)
{
	var country=document.getElementsByName('country')[0].value; 
	var s_name=document.getElementsByName('s_name')[0].value; 
	//var cardYdid=document.getElementsByName('s_mobile_code')[0].value;//旧版
	
	var cardYdid;
	if(document.getElementsByName('cardYdid').value)	
	{
		cardYdid=document.getElementsByName('cardYdid')[0].value;
	}else{
		cardYdid='<?=$rs['cardYdid']?>';
	}
	
	var xmlhttp=createAjax(); 
	if (xmlhttp) 
	{
		xmlhttp.open('POST','/public/ajax.php?n='+Math.random(),true); 
		xmlhttp.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
		xmlhttp.send('lx=cardInstead&typ='+typ+'&country='+country+'&s_name='+s_name+'&cardYdid='+cardYdid+'');

		xmlhttp.onreadystatechange=function() 
		{  
			if (xmlhttp.readyState==4 && xmlhttp.status==200) 
			{ 
				//输出:innerHTML输出到页面；value输出到文本框；
				document.getElementById('cardInstead_msg').innerHTML=unescape(xmlhttp.responseText); 
			}
		}
	}
}
</script>



<script type="text/javascript">
//-------------------------------------------------预估费用(会员表单)------------------------------------------
function calc()
{
	//下面是获取单选按钮值
	var op_bgfee1=0;
	if($('[name="op_bgfee1"]').length>0)
	{
		var eless = document.getElementsByName("op_bgfee1");
		for(var i=0;i<eless.length;i++){ if(eless[i].checked){op_bgfee1=eless[i].value;break;} }
	}
		
	var op_bgfee2=0;
	if($('[name="op_bgfee2"]').length>0)
	{
		var eless = document.getElementsByName("op_bgfee2");
		for(var i=0;i<eless.length;i++){ if(eless[i].checked){op_bgfee2=eless[i].value;break;} }
	}
		
	var op_wpfee1=0;
	if($('[name="op_wpfee1"]').length>0)
	{
		var eless = document.getElementsByName("op_wpfee1");
		for(var i=0;i<eless.length;i++){ if(eless[i].checked){op_wpfee1=eless[i].value;break;} }
	}
		
	var op_wpfee2=0;
	if($('[name="op_wpfee2"]').length>0)
	{
		var eless = document.getElementsByName("op_wpfee2");
		for(var i=0;i<eless.length;i++){ if(eless[i].checked){op_wpfee2=eless[i].value;break;} }
	}
		
	var op_ydfee1=0;
	if($('[name="op_ydfee1"]').length>0)
	{
		var eless = document.getElementsByName("op_ydfee1");
		for(var i=0;i<eless.length;i++){ if(eless[i].checked){op_ydfee1=eless[i].value;break;} }
	}
		
	var op_ydfee2=0;
	if($('[name="op_ydfee2"]').length>0)
	{
		var eless = document.getElementsByName("op_ydfee2");
		for(var i=0;i<eless.length;i++){ if(eless[i].checked){op_ydfee2=eless[i].value;break;} }
	}

	//获取物品资料-开始
	var gdid='';		var go_number='';
	var goid='';		var go_number='';
	var wp_number=0;
	if($('[name="noCheck[]"]').length>0)
	{
		var noCheck_arr = document.getElementsByName("noCheck[]");
		if($('[name="wupin_number[]"]').length>0){ var wupin_number_arr = document.getElementsByName("wupin_number[]"); }
		if($('[name="gdid[]"]').length>0){ var gdid_arr = document.getElementsByName("gdid[]"); }
		if($('[name="goid[]"]').length>0){ var goid_arr = document.getElementsByName("goid[]"); }
		
		for(var i=0;i<noCheck_arr.length;i++)
		{
			if(Number(noCheck_arr[i].value)!=1)
			{
				//获取资料库ID和对应种数
				if($('[name="gdid[]"]').length>0){ gdid+=gdid_arr[i].value+','; }
				if($('[name="wupin_number[]"]').length>0){ go_number+=wupin_number_arr[i].value+','; }
				
				//获取代购ID和对应数量
				if($('[name="goid[]"]').length>0){ goid+=goid_arr[i].value+',';}
				if($('[name="wupin_number[]"]').length>0){ go_number+=wupin_number_arr[i].value+','; }
				
				//获取全部物品总数
				if($('[name="wupin_number[]"]').length>0){ wp_number+=Number(wupin_number_arr[i].value); }
			}
		}
	}
	//获取物品资料-结束
	

	var country=document.getElementsByName('country')[0].value;
	var channel=document.getElementsByName('channel')[0].value;
	var userid=<?=$Muserid?>;
	var insurevalue=$('#insurevalue').val();
	var weight=0;			if($('[name="weightEstimate"]').length>0)		{weight=$('[name="weightEstimate"]')[0].value;}
	var bg_number=0;		if($('[name="bg_number"]').length>0)		{bg_number=$('[name="bg_number"]')[0].value;}
	var baoguo_hx_fee=0;	if($('[name="baoguo_hx_fee"]').length>0)	{baoguo_hx_fee=$('[name="baoguo_hx_fee"]')[0].value;}
	var warehouse=document.getElementsByName('warehouse')[0].value;

	if(!channel){ 
		document.getElementById('msg_fee').innerHTML='<?=$LG['js.11']//请选择渠道?>';//总费用
		return false;
	}
	if(parseFloat(weight)<=0){ 
		document.getElementById('msg_fee').innerHTML='<?=$LG['js.12']//请填写重量?>';//总费用
		return false;
	}
	
	var xmlhttp_fee=createAjax(); 
	if (xmlhttp_fee) 
	{ 
		xmlhttp_fee.open('POST','/xingao/yundan/call/calc.php?n='+Math.random(),true); 
		xmlhttp_fee.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
		xmlhttp_fee.send('yf=1&warehouse='+warehouse+'&country='+country+'&weight='+weight+'&op_bgfee1='+op_bgfee1+'&op_bgfee2='+op_bgfee2+'&op_wpfee1='+op_wpfee1+'&op_wpfee2='+op_wpfee2+'&op_ydfee1='+op_ydfee1+'&op_ydfee2='+op_ydfee2+'&channel='+channel+'&userid='+userid+'&insurevalue='+insurevalue+'&baoguo_hx_fee='+baoguo_hx_fee+'&bg_number='+bg_number+'&gdid='+gdid+'&go_number='+go_number+'&goid='+goid+'&go_number='+go_number+'&wp_number='+wp_number+'');
		
		xmlhttp_fee.onreadystatechange=function() 
		{  
			if (xmlhttp_fee.readyState==4 && xmlhttp_fee.status==200) 
			{ 
				var all_fee=unescape(xmlhttp_fee.responseText);
				var arr=all_fee;arr=arr.split(",");//字符串转数组
				
				//总费
				if($('#msg_fee').length>0){
					$('#msg_fee').html(arr[0]);
				}

				//单运费
				if($('#msg_fee_freight').length>0){
					var zhi=arr[1];if (isNaN(zhi)||zhi==''){zhi=0;}
					if(zhi>0){ $('#msg_fee_freight').html('<?=$LG['yundan.25']?>'+decimalNumber(zhi,2)+'<?=$XAmc?>'); }
					else{ $('#msg_fee_freight').html(''); }
				}
				
				//单税费
				if($('#msg_fee_tax').length>0){
					var zhi=arr[6];if (isNaN(zhi)||zhi==''){zhi=0;}
					if(zhi>0){ $('#msg_fee_tax').html('+<?=$LG['yundan.23']?>'+decimalNumber(zhi,2)+'<?=$XAmc?>'); }
					else{ $('#msg_fee_tax').html(''); }
				}
				
				//单仓储费
				if($('#msg_fee_ware').length>0){
					var zhi=arr[7];if (isNaN(zhi)||zhi==''){zhi=0;}
					if(zhi>0){ $('#msg_fee_ware').html('+<?=$LG['yundan.28']?>'+decimalNumber(zhi,2)+'<?=$XAmc?>'); }
					else{ $('#msg_fee_ware').html(''); }
					
				}
				
				//其他费
				if($('#msg_fee_other').length>0){
					var zhi=Number(arr[0])-Number(arr[1])-Number(arr[6])-Number(arr[7]);
					if (isNaN(zhi)||zhi==''){zhi=0;}
					if(zhi>0){ $('#msg_fee_other').html('+<?=$LG['yundan.24']?>'+decimalNumber(zhi,2)+'<?=$XAmc?>');}
					else{ $('#msg_fee_other').html(''); }
				}
			}
		}
	}
}
</script>





<!-----------------------------------------物品JS代码部分---------------------------------------------------->
<script src="/js/AntongJQ.js" type="text/javascript"></script>
<script type="text/javascript">
//删除节点
function delProductDetail(node) 
{
	$(node).parent().parent().remove();
	
	CalcDeclareValue();
}


//复制节点
function addProductDetail(node) {
	$tr = $(node).parent().parent().removeClass().clone();
	$table = $(node).parent().parent().parent();
	$tr.find('input').val('');
	$tr.find('[name=deleteHref]').show();
	$tr.appendTo($table);
	
	$bigNode = $('#tableProduct tr:last').find("#ProductType");
	ChangeProductBigType($bigNode);
}


function addEditProductDetail(node) {

	$tr = $(node).parent().parent().removeClass().clone();
	$table = $(node).parent().parent().parent();
	$tr.find('input').val('');
	$tr.find('[name=deleteHref]').show();
	$tr.appendTo($table);

	$bigNode = $('#table1 tr:last').find("#ProductTypeC");
	ChangeEditProductBigType($bigNode);
}

function CalcTotalPrice(node) {
	$tr = $(node).parent().parent();
	var num = $.trim($tr.find('[id=productNum]').val());
	if (isNaN(num)) {
		num = 0;
		$tr.find('[id=productNum]').val(num);
	}
	
	var pri = $.trim($tr.find('[id=productPrice]').val());
	if (isNaN(pri)) {
		pri = 0;
		$tr.find('[id=productPrice]').val(pri);
	}

	$tr.find('[id=productTotalPrice]').val( decimalNumber(accMul(num, pri),2) );
}

var declareValue_max = document.getElementsByName('declarevalue')[0].value;
function CalcDeclareValue(LimitOP) 
{
	var declareValue = 0;
	var num = "";
	var totalPrice = 0;
	var weightEstimate = 0;
	var wupin_weight = 0;
	$('#tableProduct tr').not(':first').each(function () {
		num = $(this).find('[id=productNum]').val();
		totalPrice = $(this).find('[id=productTotalPrice]').val();
		wupin_weight = $(this).find('[id=wupin_weight]').val();
		
		declareValue = parseFloat(accAdd(declareValue ,(totalPrice == null ? 0 : totalPrice)));
		declareValue_max = declareValue;
		weightEstimate = accAdd(weightEstimate ,(wupin_weight == null ? 0 : wupin_weight*num));
	});
	

	$('[id=declarevalue]').val(declareValue);	if($('#baoxian_5').length>0){calc_insurance();}
	lblinsureamounte.innerHTML=declareValue;
	$('[id=declareValue_max]').val(declareValue_max);
	if($('#weightEstimate').length>0&&LimitOP){ $('#weightEstimate').val(weightEstimate); }
    
}

</script>      







<script type="text/javascript">
//-----------------------------------------显示该渠道参数--------------------------
function channelPar()
{
	if($('[name="warehouse"]').length<=0||$('[name="channel"]').length<=0){return;}//不存在
	
	var warehouse=document.getElementsByName('warehouse')[0].value;
	var channel=document.getElementsByName('channel')[0].value;
	if(!channel){return;}//不存在

	$.ajax({
        type: "POST",
        cache: false,
        data: 'lx=channelPar&warehouse='+warehouse+'&channel='+channel+'',
        async: false,//true导步处理;false为同步处理
        url: "/public/ajax.php",
        success: function (data) 
		{
			arr = data;
		}
    });
	
	arr=arr.split(":::");
	/*
		字符串转数组
		arr[0]=是否要上传证件;
		arr[1]=所用清关公司
		arr[2]=渠道说明
	*/
	
	
	//是否要上传证件
	if(Number(arr[0])==1)
	{
		document.getElementById("sfz_hide").style.display="block";
		document.getElementsByName("s_shenfenhaoma")[0].setAttribute("required",true);
	}else{
		document.getElementById("sfz_hide").style.display='none';
		document.getElementsByName("s_shenfenhaoma")[0].removeAttribute("required");
	}
	
	
	
	if($('[id="channel_content"]').length>0&&arr[2])
	{
		
		document.getElementById("channel_content").innerHTML=' &raquo; '+arr[2];
	}
}




//刷新表单页
function refresh_form() {
	if($('[name="warehouse"]').length<=0||$('[name="channel"]').length<=0){return;}//不存在
	var warehouse=document.getElementsByName('warehouse')[0].value;
	var channel=document.getElementsByName('channel')[0].value;
	
	var fx_number=0;
	if($('[name="fx_number"]').length>0)
	{
		fx_number=document.getElementsByName('fx_number')[0].value;
	}
	
	
	//获取单选值
	var fx=0;
	if($('[name="fx"]').length>0)
	{
		var eless = document.getElementsByName("fx");//必须用Name
		for(var i=0;i<eless.length;i++)
		{
		  if(eless[i].checked)
		  {
			var fx=eless[i].value;//必须加var全局变量 
			break;//获取后退出，不再获取后面 
		  }
		}
		if (typeof(fx) == "undefined"){var fx=0;}//判断
	}
	
	if(fx==0){fx_number=0;}


	//后台用
	var username='';	if($('[name="username"]').length>0)		{username=$('[name="username"]')[0].value;}
	var userid='';		if($('[name="userid"]').length>0)		{userid=$('[name="userid"]')[0].value;}
	
	//通用:URL需要有国家
	var country='';		if($('[name="country"]').length>0)		{country=$('[name="country"]')[0].value;}
						if($('[name="tag"]').length>0)			{tag=$('[name="tag"]')[0].value;}
	//刷新页面
	location='?<?=$status?>&tag='+tag+'&warehouse='+warehouse+'&country='+country+'&channel='+channel+'&username='+username+'&userid='+userid+'&fx='+fx+'&fx_number='+fx_number+'';

}




//显示该渠道支持的附加服务
function yundan_service() {
	if(	$('[name="warehouse"]').length<=0	||	$('[name="channel"]').length<=0){return;}

	var warehouse=$('[name="warehouse"]')[0].value;
	var channel=$('[name="channel"]')[0].value;
	var formlx=$('[name="lx"]')[0].value;
	
	$.ajax({
        type: "POST",
        cache: false,
        data: 'lx=yundan_service&warehouse='+warehouse+'&channel='+channel+'&formlx='+formlx+'&addSource=<?=$addSource?>&ydid=<?=$ydid?>',
        async: false,//true导步处理;false为同步处理
        url: "/public/ajax.php",
        success: function (data) 
		{
			result = data;
			document.getElementById('yundan_service').innerHTML= result;
		}
    });
   
}







//-------------------------------------------------保价相关计算------------------------------------------
function calc_insurance(){
	var whid=document.getElementsByName('warehouse')[0].value;
	var channel=document.getElementsByName('channel')[0].value;
	var declarevalue=parseFloat(document.getElementsByName('declarevalue')[0].value);//物品价值
	var insureamount=parseFloat(document.getElementsByName('insureamount')[0].value);//物品保价
	
	//if(insureamount<=0||declarevalue<=0){return false;}
	
	//baoxian_ts提示
	if (parseFloat(whid)<=0||!whid||parseFloat(channel)<=0||!channel)
	{
		document.getElementById("baoxian_ts").innerHTML='<font class="red2"><?=$LG['js.7']//请先选择仓库和渠道?></font>';
		return false;
	}else{
		document.getElementById("baoxian_ts").innerHTML='<?=$LG['js.8']//不买保险请留空或填0，不能超过发票上的价值；?>';
	}


	//物品保价
	if(insureamount>declarevalue)
	{
		document.getElementsByName("insureamount")[0].value=declarevalue;
		insureamount=declarevalue;
	}
	
	
	var xmlhttp_insu=createAjax(); 
	if (xmlhttp_insu) 
	{ 
		xmlhttp_insu.open('POST','/public/ajax.php?n='+Math.random(),true); 
		xmlhttp_insu.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
		xmlhttp_insu.send('lx=insurance&whid='+whid+'&channel='+channel+'&insureamount='+insureamount+'');
		
		xmlhttp_insu.onreadystatechange=function() 
		{  
			if (xmlhttp_insu.readyState==4 && xmlhttp_insu.status==200) 
			{ 
				var all_fee=unescape(xmlhttp_insu.responseText);
				var arr = all_fee;arr =arr.split(",");//字符串转数组
				
				var zhi=arr[0];if (typeof(zhi) == "undefined"){zhi=arr[0];}
				document.getElementById('msg_insurevalue').innerHTML=zhi;//需付保价费
				document.getElementById('insurevalue').value=zhi;
				
				var zhi=arr[1];if (typeof(zhi) == "undefined"){zhi=arr[1];}
				document.getElementById('baoxian_1').innerHTML=zhi;//物品价值在之内
				
				var zhi=arr[2];if (typeof(zhi) == "undefined"){zhi=arr[2];}
				document.getElementById('baoxian_2').innerHTML=zhi;//保险率为

				var zhi=arr[3];if (typeof(zhi) == "undefined"){zhi=arr[3];}
				document.getElementById('baoxian_3').innerHTML=zhi;//超过则为
				
				var zhi=arr[4];if (typeof(zhi) == "undefined"){zhi=arr[4];}
				document.getElementById('baoxian_4').innerHTML=zhi;//保价区间-起
				var baoxian_4=zhi;
				
				var zhi=arr[5];if (typeof(zhi) == "undefined"){zhi=arr[5];}
				document.getElementById('baoxian_5').innerHTML=zhi;//保价区间-止
				var baoxian_5=zhi;
				
				
				//其他处理-----------------------------------------------------------
				
				//小于保价区间
				if (insureamount<baoxian_4)
				{
					document.getElementsByName('insureamount')[0].value=baoxian_4;
				}
			
				
				//大于保价区间
				if (insureamount>baoxian_5)
				{
					document.getElementsByName('insureamount')[0].value=baoxian_5;
				}
				
				//baoxian_5最大保价提示
				if(declarevalue>0)
				{
					if(declarevalue<baoxian_4){//小于保价区间-起
						document.getElementById("baoxian_5").innerHTML=baoxian_4;
					}else if(declarevalue>baoxian_5){//大于保价区间-止
						document.getElementById("baoxian_5").innerHTML=baoxian_5;
					}else{//小于最小保价区间
						document.getElementById("baoxian_5").innerHTML=declarevalue;
					}
				}else{
					document.getElementById("baoxian_5").innerHTML=0;
				}
				
				
				//baoxian_ts提示
				if (baoxian_4<=0&&baoxian_5<=0)
				{
					document.getElementById("baoxian_ts").innerHTML='<font class="red2"><?=$LG['js.9']//该渠道暂时不支持购买保险?></font>';
				}
				
			}
		}
	}
}






//--------------------------------------表单提交通用验证---------------------------
function submit_chk(typ)
{
	//验证:预估限重
	if(!typ||typ=='forecast_weight_limit')
	{
		var weight=parseInt($('[name="weightEstimate"]')[0].value);
		var result=submit_chk_call('forecast_weight_limit',weight);
		
		if(result!=0)
		{
		   if(confirm(result))
		   {
			 //return true;
		   }else{
			 return false;
		   }
		}
	}


	//验证:商品资料限重
	if(!typ||typ=='customs_weight_limit')
	{
		//获取物品重量
		var weight=0;
		if($('[name="noCheck[]"]').length>0&&$('[name="wupin_weight[]"]').length>0&&$('[name="wupin_number[]"]').length>0)
		{
			var noCheck_arr = document.getElementsByName("noCheck[]");
			var wupin_weight_arr = document.getElementsByName("wupin_weight[]");
			var wupin_number_arr = document.getElementsByName("wupin_number[]");
			for(var i=0;i<noCheck_arr.length;i++)
			{
				if(Number(noCheck_arr[i].value)!=1)
				{
					weight+=Number(wupin_weight_arr[i].value)*Number(wupin_number_arr[i].value);
				}
			}
		}

		var result=submit_chk_call('customs_weight_limit',weight);
		if(result!=0)
		{
			alert(result);
			return false;
		}
	}
	

	
	<?php if($customs){?>
	//验证:备案渠道-是否已经选择物品
	if(1==2&&(!typ||typ=='customs_wp'))
	{
		var ok=0;
		if($('[name="noCheck[]"]').length>0)
		{
			var noCheck_arr = document.getElementsByName("noCheck[]");
			for(var i=0;i<noCheck_arr.length;i++)
			{
				if(Number(noCheck_arr[i].value)!=1)
				{
					var ok=1;
				}
			}
		}
		if(ok==0)
		{
			alert('<?=$LG['yundan.save_10']?>');
			return false;
		}
	}
	<?php }?>
	  
	//验证:收件人资料
	if(!typ||typ=='receive_check')
	{
		receive_check();//收件人资料验证 (receive_check有提交事件)
	}
}




function submit_chk_call(typ,weight)
{
	var warehouse=parseInt($('[name="warehouse"]')[0].value);
	var channel=parseInt($('[name="channel"]')[0].value);
	
	
	$.ajax({
		type: "POST",
		cache: false,
		data: 'lx=submit_chk&typ='+typ+'&warehouse='+warehouse+'&channel='+channel+'&weight='+weight+'',
		async: false,//true导步处理;false为同步处理
		url: "/public/ajax.php",
		success: function (data) 
		{
			result=$.trim(data);
		}
	});
	return result;
	
}



//-------------------------------------------------验证收件人资料------------------------------------------
function receive_check()
{
	var country=document.getElementsByName("country")[0].value ;
	var warehouse=document.getElementsByName("warehouse")[0].value ;
	var channel=document.getElementsByName("channel")[0].value ;
	
	var truename='';	if($('[name="s_name"]').length>0)		{truename=$('[name="s_name"]')[0].value;}
	var mobile='';		if($('[name="s_mobile"]').length>0)		{mobile=$('[name="s_mobile"]')[0].value;}
	var zip='';			if($('[name="s_zip"]').length>0)		{zip=$('[name="s_zip"]')[0].value;}
	var add_dizhi='';	if($('[name="s_add_dizhi"]').length>0)		{add_dizhi=$('[name="s_add_dizhi"]')[0].value;}
	var shenfenhaoma='';	if($('[name="s_shenfenhaoma"]').length>0)		{shenfenhaoma=$('[name="s_shenfenhaoma"]')[0].value;}
	var shenfenimg_z='';	if($('[name="s_shenfenimg_z"]').length>0)		{shenfenimg_z=$('[name="s_shenfenimg_z"]')[0].value;}
	var old_shenfenimg_z='';if($('[name="old_s_shenfenimg_z"]').length>0)	{old_shenfenimg_z=$('[name="old_s_shenfenimg_z"]')[0].value;}
	var shenfenimg_z_add='';if($('[name="s_shenfenimg_z_add"]').length>0)	{shenfenimg_z_add=$('[name="s_shenfenimg_z_add"]')[0].value;}
	
	var shenfenimg_b='';	if($('[name="s_shenfenimg_b"]').length>0)		{shenfenimg_b=$('[name="s_shenfenimg_b"]')[0].value;}
	var old_shenfenimg_b='';if($('[name="old_s_shenfenimg_b"]').length>0)	{old_shenfenimg_b=$('[name="old_s_shenfenimg_b"]')[0].value;}
	var shenfenimg_b_add='';if($('[name="s_shenfenimg_b_add"]').length>0)	{shenfenimg_b_add=$('[name="s_shenfenimg_b_add"]')[0].value;}

	document.getElementById('submit_none').disabled=true;
	
	var xmlhttp_receive=createAjax(); 
	if (xmlhttp_receive) 
	{  
		xmlhttp_receive.open('POST','/public/ajax.php?n='+Math.random(),true); 
		xmlhttp_receive.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
		xmlhttp_receive.send('lx=receive_check&warehouse='+warehouse+'&channel='+channel+'&truename='+truename+'&country='+country+'&mobile='+mobile+'&zip='+zip+'&add_dizhi='+add_dizhi+'&shenfenhaoma='+shenfenhaoma+'&shenfenimg_z='+shenfenimg_z+'&shenfenimg_z_add='+shenfenimg_z_add+'&old_shenfenimg_z='+old_shenfenimg_z+'&shenfenimg_b='+shenfenimg_b+'&shenfenimg_b_add='+shenfenimg_b_add+'&old_shenfenimg_b='+old_shenfenimg_b+'');
		
		xmlhttp_receive.onreadystatechange=function() 
		{  
			if (xmlhttp_receive.readyState==4 && xmlhttp_receive.status==200) 
			{ 
				var ret=unescape(xmlhttp_receive.responseText); 
				if(ret!=0)
				{
					return alert($.trim(ret));
				}else{
					document.getElementById('submit_none').disabled=false;
					document.getElementById ('submit_none').click ();
					document.getElementById('submit_none').disabled=true;
				}
			}
		}
	}
	
}
</script>
