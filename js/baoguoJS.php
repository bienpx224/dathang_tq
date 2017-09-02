<!--后台操作----------------------------------------------------------------------------------------->
<script>
//包裹入库获取相关数据:仓位
/*
	$Typ=1 该包裹单号仓位
*/
function getBaoguoDate(Typ) { 
	
	if($('[name="bgydh"]').length>0)
	{
		var bgydh=$('[name="bgydh"]')[0].value;
	}

	//获取单选值
	var eless = document.getElementsByName("getTyp");//必须用Name
	for(var i=0;i<eless.length;i++)
	{
		if(eless[i].checked)
		{
		  var getTyp=eless[i].value;//必须加var全局变量 
		  break;//获取后退出，不再获取后面 
		}
	}

	
	if($('[name="username"]').length>0)
	{
		var username=document.getElementsByName("username")[0].value;
		var userid=document.getElementsByName("userid")[0].value;
		var useric=document.getElementsByName("useric")[0].value;
	}else{
		var username='';
		var userid='';
		var useric='';
	}


	if(!bgydh){return;}
	$.ajax({
        type: "POST",
        cache: false,
        data: 'lx=show_whPlace&Typ='+Typ+'&bgydh='+bgydh+'&getTyp='+getTyp+'&username='+username+'&userid='+userid+'&useric='+useric+'',
        async: false,//true导步处理;false为同步处理
        url: "/public/ajax.php",
        success: function (data) 
		{
			arr=data.split(",");
			if($('[id="cw_msg"]').length>0)
			{
				document.getElementById("cw_msg").innerHTML=arr[0];
			}
			document.getElementsByName("whPlace")[0].value=arr[1];
		}
    });
}

$(function(){   if($('[name="getTyp"]').length>0){ getBaoguoDate(); }   });
</script>








<!--会员操作----------------------------------------------------------------------------------------->
<script>
//物品:通用添加行,删除行
function delProductDetail(node) {
	$(node).parent().parent().remove();
	if ($('[name=hiddenType]').val() == '1') {
		autoHeight();
	}
}
function addProductDetail(node) {

	$tr = $(node).parent().parent().removeClass().clone();
	$table = $(node).parent().parent().parent();
/*      $tr.find('input[id=""]').val('');//某个(ID空时就不复制数据)
	$tr.find('input[id="productNum"]').val('');//某个
	$tr.find('input[id="productPrice"]').val('');//某个
	$tr.find('input[id="productTotalPrice"]').val('');//某个
*/
	$tr.find('input').val('');
	$tr.find('select').val('');
	$tr.find('[name=deleteHref]').show();
	$tr.appendTo($table);
	if ($('[name=hiddenType]').val() == '1') {
		autoHeight();
	}
	$bigNode = $('#tableProduct tr:last').find("#ProductType");
	ChangeProductBigType($bigNode);
}





//物品:前台预报添加行,删除行
function delProductDetail2(node) {
	$(node).parent().parent().remove();
	if ($('[name=hiddenType]').val() == '1') {
		autoHeight();
	}
}

var n=1;
function addProductDetail2(node) {
	n=n+1;
	$tr = $(node).parent().parent().removeClass().clone();
	$table = $(node).parent().parent().parent();
/*      $tr.find('input[id=""]').val('');//某个(ID空时就不复制数据)
	$tr.find('input[id="productNum"]').val('');//某个
	$tr.find('input[id="productPrice"]').val('');//某个
	$tr.find('input[id="productTotalPrice"]').val('');//某个
*/
	$tr.find('input').val('');
	$tr.find('select').val('');
	$tr.find('input[id="wupin_id"]').val(n);
	
	//改变元素名
	$tr.find('select[id="wupin_type"]').attr("name",'wupin_type'+n+'[]');
	$tr.find('input[id="wupin_name"]').attr("name",'wupin_name'+n+'[]');
	$tr.find('input[id="wupin_brand"]').attr("name",'wupin_brand'+n+'[]');
	$tr.find('input[id="wupin_spec"]').attr("name",'wupin_spec'+n+'[]');
	$tr.find('input[id="wupin_weight"]').attr("name",'wupin_weight'+n+'[]');
	$tr.find('input[id="productNum"]').attr("name",'wupin_number'+n+'[]');
	$tr.find('select[id="wupin_unit"]').attr("name",'wupin_unit'+n+'[]');
	$tr.find('input[id="productPrice"]').attr("name",'wupin_price'+n+'[]');
	$tr.find('input[id="productTotalPrice"]').attr("name",'wupin_total'+n+'[]');
  
	$tr.find('[name=deleteHref]').show();
	$tr.find('[id="line"]:gt(0)').remove();//只留一行物品
	
	$tr.appendTo($table);
	if ($('[name=hiddenType]').val() == '1') {
		autoHeight();
	}
	$bigNode = $('#tableProduct tr:last').find("#ProductType");
	ChangeProductBigType($bigNode);
}







//计算申报总价
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






//计算总价
var declareValue_max = 0;
function CalcDeclareValue() {

	var declareValue = 0;
	var zhongliang_yugu = 0;
	var productName = "";
	var brand = "";
	var num = "";
	var totalPrice = 0;
	var product_7 = 0;
	$('#tableProduct tr').not(':first').each(function () {
		num = $(this).find('[id=productNum]').val();
		totalPrice = $(this).find('[id=productTotalPrice]').val();
		product_7 = $(this).find('[id=product_7]').val();
		
		declareValue = parseFloat(accAdd(declareValue ,(totalPrice == null ? 0 : totalPrice)));
		declareValue_max = declareValue;
		zhongliang_yugu = accAdd(zhongliang_yugu ,(product_7 == null ? 0 : product_7));
	});
	$('[id=declarevalue]').val(declareValue);
	$('[id=declareValue_max]').val(declareValue_max);
	 lblinsureamounte.innerHTML=declareValue;
  $('[id=zhongliang_yugu]').val(zhongliang_yugu);
}

</script>