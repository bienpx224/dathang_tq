<script>
//货源类型处理--------------------------------------------------------------
function get_source()
{
	if(!document.getElementsByName("source")){return;}//判断元素是否存在
	var source=document.getElementsByName("source")[0].value;
	if(Number(source)==1){
		//线上网站==========================

		//品牌和折扣
		document.getElementById("brandShow").style.display ='none';
		document.getElementsByName("brand")[0].removeAttribute("required");
		
		//地址框
		document.getElementById("address_name").innerHTML='<?=$LG['js.4']//电商网址?>';
		document.getElementsByName("address")[0].setAttribute("required",true);
		document.getElementsByName("address")[0].setAttribute("class", "form-control input_txt_red");
	}else if(Number(source)==2){
		//线下专柜==========================
		
		//品牌和折扣
		document.getElementById("brandShow").style.display ='block';
		document.getElementsByName("brand")[0].setAttribute("required",true);
		
		//地址框
		document.getElementById("address_name").innerHTML='<?=$LG['js.5']//专柜地址?>';
		document.getElementsByName("address")[0].removeAttribute("required");
		document.getElementsByName("address")[0].setAttribute("class", "form-control");
	}
}
$(function(){ get_source();});
</script>



<script>
//代购表单参数--------------------------------------------------------------
function daigouPar()
{
	var groupid=document.getElementsByName('groupid')[0].value;
	var source=document.getElementsByName('source')[0].value;
	var brand=0;	if(Number(source)==2){brand=document.getElementsByName('brand')[0].value;}
	var priceCurrency=document.getElementsByName('priceCurrency')[0].value;
	var groupid=document.getElementsByName('groupid')[0].value;
	var freightFee=document.getElementsByName('freightFee')[0].value;

	var tmp='';		if($('[name="tmp"]').length>0){	tmp=document.getElementsByName('tmp')[0].value;	}
	
	var xmlhttp=createAjax(); 
	if (xmlhttp) 
	{ 
		xmlhttp.open('POST','/public/ajax.php?n='+Math.random(),true); 
		xmlhttp.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
		xmlhttp.send('lx=daigouPar&groupid='+groupid+'&brand='+brand+'&priceCurrency='+priceCurrency+'&tmp='+tmp+'&groupid='+groupid+'&freightFee='+freightFee+'&source='+source+'');

		xmlhttp.onreadystatechange=function() 
		{  
			if (xmlhttp.readyState==4 && xmlhttp.status==200) 
			{ 
				arr=unescape(xmlhttp.responseText);
				arr =arr.split(",");
				/*
					字符串转数组
					arr[0] 折扣
					arr[1] 运费
				*/
				 
				if(arr[0]==-1){msg='<?=$LG['js.6']//不支持该品牌?>';} 
				else if(arr[0]==10){msg='0';}
				document.getElementById('brandDiscount_msg').innerHTML=arr[0]; 
				document.getElementsByName('brandDiscount')[0].value=arr[0]; 
				
				if(!freightFee&&Number(arr[1])>0){document.getElementsByName('freightFee')[0].value=arr[1]; }
				if($('[id="priceCurrency_msg"]').length>0){document.getElementById('priceCurrency_msg').innerHTML=priceCurrency; }
				if($('[id="priceCurrency_msg3"]').length>0){document.getElementById('priceCurrency_msg3').innerHTML=priceCurrency; }
			}
		}
	}
}
$(function(){ daigouPar();});
</script>


                   
<script>
//显示自填框--------------------------------------------------------------
function inputOther(val)
{
	var value=document.getElementsByName(val)[0].value;
	if(value=="Other"){
		document.getElementsByName(val+'Other')[0].style.display ='block';
	}else{
		document.getElementsByName(val+'Other')[0].style.display ='none';
	}
}


//计算总价--------------------------------------------------------------
function totalPrice()
{
	var number=document.getElementsByName('number')[0].value;
	var price=document.getElementsByName('price')[0].value;
	var priceCurrency=parent.document.getElementsByName('priceCurrency')[0].value;
	var total=decimalNumber(Number(number)*Number(price),2);
	document.getElementById("totalPrice_msg").innerHTML=total+priceCurrency;
}
</script>




<script>
//会员操作菜单--------------------------------------------------------------
function show_op_member(dgid) {
	$.ajax({
        type: "POST",
        cache: false,
        data: 'dgid='+dgid,
        async: false,//true导步处理;false为同步处理
        url: "/xamember/daigou/call/op_menu_ajax.php",
        success: function (data) 
		{
			document.getElementById('show_menu'+dgid).innerHTML= data;
		}
    });
}

//后台操作菜单--------------------------------------------------------------
function show_op_xingao(dgid) {
	$.ajax({
        type: "POST",
        cache: false,
        data: 'dgid='+dgid,
        async: false,//true导步处理;false为同步处理
        url: "/xingao/daigou/call/op_menu_ajax.php",
        success: function (data) 
		{
			document.getElementById('show_menu'+dgid).innerHTML= data;
		}
    });
}
</script>
