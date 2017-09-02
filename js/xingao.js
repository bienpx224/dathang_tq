/**
================================================兴奥常用综合JS====================================================
**/



/**
返回上一页------------------------------------------------------------------------------------------
	如果没有前一页历史，则直接刷新当前页面
	lx=空 无一上页则返回本页；
	lx=uc 无上一页则关闭；
	lx=c关闭；
	lx=r刷新
	lx=指定地址
	如:goBack('c');
**/
function goBack(lx)
{
	if(!lx||lx==''||lx=='uc'){
	   //history.length  IE 6 和 Opera 9 以 0 开始,因此不支持这2浏览器版本
	   if(window.history.length >=2){//有上一页就返回
			window.history.go(-1);
	   }else if(lx=='uc'){//没有上一页时
		   window.opener=null;window.open('','_self');window.close();
	   }else{//没有上一页时
			window.history.go(0);//返回本页
	   }
	}else if(lx=='c'){//关闭
		window.opener=null;window.open('','_self');window.close();//关闭
	}else if(lx=='r'){//刷新本页
		window.location.href=window.location.href;
	}else if(lx){//指定地址
		window.location=lx;
	}
}


/**
 回到页面顶部----------------------------------------------------------------------
 acceleration 速度
 time 时间间隔 (毫秒)
 
 调用：
 <a href="#" onclick="goTop();return false;">TOP</a>
 <a href="#" onclick="goTop(10);">TOP</a>
 **/
function goTop(acceleration,time) {
 acceleration = acceleration || 0.1;
 time = time || 16;

 var x1 = 0;
 var y1 = 0;
 var x2 = 0;
 var y2 = 0;
 var x3 = 0;
 var y3 = 0;
 
 if (document.documentElement) {
  x1 = document.documentElement.scrollLeft || 0;
  y1 = document.documentElement.scrollTop || 0;
 }
 if (document.body) {
  x2 = document.body.scrollLeft || 0;
  y2 = document.body.scrollTop || 0;
 }
 var x3 = window.scrollX || 0;
 var y3 = window.scrollY || 0;
 
 // 滚动条到页面顶部的水平距离
 var x = Math.max(x1, Math.max(x2, x3));
 // 滚动条到页面顶部的垂直距离
 var y = Math.max(y1, Math.max(y2, y3));
 
 // 滚动距离 = 目前距离 / 速度, 因为距离原来越小, 速度是大于 1 的数, 所以滚动距离会越来越小
 var speed = 1 + acceleration;
 window.scrollTo(Math.floor(x / speed), Math.floor(y / speed));
 
 // 如果距离不为零, 继续调用迭代本函数
 if(x > 0 || y > 0) {
  var invokeFunction = "goTop(" + acceleration + ", " + time + ")";
  window.setTimeout(invokeFunction, time);
 }
}  



//该函数将返回XMLHTTP对象实例----------------------------------------------------------------------
function createAjax() 
{   
 var _xmlhttp;
 try { 
  _xmlhttp=new ActiveXObject("Microsoft.XMLHTTP"); 
 }
 catch (e) {
  try {
   _xmlhttp=new XMLHttpRequest(); 
  }
  catch (e) {
   _xmlhttp=false;  
  }
 }
 return _xmlhttp; 
}

//浏览器 placeholder 兼容代码----------------------------------------------------------------------
$(function(){
if(!placeholderSupport()){   // 判断浏览器是否支持 placeholder
    $('[placeholder]').focus(function() {
        var input = $(this);
        if (input.val() == input.attr('placeholder')) {
            input.val('');
            input.removeClass('placeholder');
        }
    }).blur(function() {
        var input = $(this);
        if (input.val() == '' || input.val() == input.attr('placeholder')) {
            input.addClass('placeholder');
            input.val(input.attr('placeholder'));
        }
    }).blur();
};
})
function placeholderSupport() {
    return 'placeholder' in document.createElement('input');
}




//输入框限制范围数字----------------------------------------------------------------------
/*
	name 元素名称
	limit='-1,10' 范围
	decimal=2 小数点位数
	
	调用：onBlur="limitNumber(this,'-1,10','2');"  有小数时，必须用onBlur
*/
function limitNumber(name,limit,decimal)
{   
	if(!name){return}
	var value=$(name).val();	value=parseFloat(value.replace(/[^\-?\d.]/g,''));	if(!value){value=0;}

	if(limit)
	{
		arr=limit.split(",");
		if(value<parseFloat(arr[0])){value=parseFloat(arr[0]);}
		if(value>parseFloat(arr[1])){value=parseFloat(arr[1]);}
	}

	decimal=parseInt(Number(decimal));
	if(decimal>=0){value=decimalNumber(value,decimal);}

	$(name).val(value);
}



//小数点位数----------------------------------------------------------------------
/*
	不会四舍五入，不会固定有小数位数
	decimal=2 小数点位数
	
	调用：decimalNumber('11.0000',2)//输出11

其他:	
2.向上取整,有小数就整数部分加1
Math.ceil(5/2)
3,四舍五入.
Math.round(5/2)
4,向下取整
Math.floor(5/2)

*/
function decimalNumber(value,decimal)
{
    var value,decimal;
　　var resultStr,nTen;
	value = ""+value+"";
　　strLen = value.length; 
　　dotPos = value.indexOf(".",0);
　　　　if ((strLen - dotPos - 1) >= decimal ){
　　　　　　nAfter = dotPos + decimal  + 1;
　　　　　　nTen =1;
　　　　　　for(j=0;j<decimal ;j++){
　　　　　　　　nTen = nTen*10;
　　　　　　}
			//Math.round 四舍五入
　　　　　　resultStr = Math.floor(parseFloat(value)*nTen)/nTen;
　　　　　　return resultStr;
　　　　}
　　　　else{
　　　　　　resultStr = value;
　　　　　　for (i=0;i<(decimal  - strLen + dotPos + 1);i++){
　　　　　　　　resultStr = resultStr;
　　　　　　}
　　　　　　return resultStr;
　　　　}
} 


//判断滚动条拉到最底，并改变某DIV样式，向上滚动还原样式
function Autohidden(distance)
{
	if(!distance){distance=10;}//默认20PX
	$(window).scroll(function() {
		
		if ($(document).scrollTop() >= $(document).height()-$(window).height()-distance)//距离底还有distance PX
		{
			//到底时
			document.getElementById("Autohidden").style.display ="none";
		} else {
			//离底时
			document.getElementById("Autohidden").style.display ="block"; 
		}
		
	});
}



//获取会员名或ID
function getUsernameId(typ)
{
	var val='';
	if(typ=='userid'){val=$('[name="userid"]')[0].value;	}
	else if(typ=='username'){val=$('[name="username"]')[0].value;}
	else if(typ=='useric'){	val=$('[name="useric"]')[0].value;}
	
	if(val=='')
	{
		if($('[name="useric"]').length>0){	$('[name="useric"]')[0].value=''; } 
		if($('[name="userid"]').length>0){	$('[name="userid"]')[0].value=''; } 
		if($('[name="username"]').length>0){	$('[name="username"]')[0].value=''; } 
		return;
	}
	
	$.ajax({
        type: "POST",
        cache: false,
        data: 'lx=getUsernameId&typ='+typ+'&val='+val+'',
        async: false,//true导步处理;false为同步处理
        url: "/public/ajax.php",
        success: function (data) 
		{
			data=data.split(",");
			if($('[name="useric"]').length>0){	$('[name="useric"]')[0].value=data[0]; } 
			//if($('[name="userid"]').length>0){	$('[name="userid"]')[0].value=data[1]; } 
			if($('[name="username"]').length>0){	$('[name="username"]')[0].value=data[2]; } 
		}
    });
}



//全选/取消
function chkAll(obj) 
{
	var id = $(obj).attr("id");
	$(":checkbox[id=" + id.substring(0, 1) + "]").each
	(
		function () 
		{
			this.checked = obj.checked;
			//Bootstrap要加才有效果:原理是在外部的span添加一个checked样式
			if(this.checked) 
			{
				$(this).parents('span').addClass('checked');
				$(this).parents('tr').addClass('active');
				//parents('span')表示在外部的span;addClass('checked')表示添加class='checked';
			} else {
				$(this).parents('span').removeClass('checked');
				$(this).parents('tr').removeClass('active');
			}
		}
	);
}

//单勾选行时变色
function chkColor(val)
{
	if(val.checked) 
	{
		$(val).parents('tr').addClass('active');
	} else {
		$(val).parents('tr').removeClass('active');
	}
}


//框架自动高度
/*
	iframeID 	框架ID
	AddHeight	再多增加高度(px)
*/
function iframeHeight(iframeID,AddHeight)
{
	$("#"+iframeID).load(function(){
		
		if(!AddHeight){AddHeight=0;}
		var mainheight = $(this).contents().find("body").outerHeight(true)+AddHeight;
		$(this).height(mainheight);
	}); 
}


function xacopy()
{
   var clipboard1 = new Clipboard('.xacopy'); 
   
  if( clipboard1.on('success')){
	  //具体参数，请在Bootstrap模板中直接生成代码
	  toastr.options = {
		"closeButton": true,
		"debug": false,
		"positionClass": "toast-bottom-right",
		"onclick": null,
		"showDuration": "1000",
		"hideDuration": "1000",
		"timeOut": "1000",
		"extendedTimeOut": "1000",
		"showEasing": "swing",
		"hideEasing": "linear",
		"showMethod": "show",
		"hideMethod": "hide"
	  }
	  toastr.info('复制成功!');
	  
  }else if(clipboard1.on('error')){
   
		  //具体参数，请在Bootstrap模板中直接生成代码
		  toastr.options = {
			"closeButton": true,
			"debug": false,
			"positionClass": "toast-bottom-right",
			"onclick": null,
			"showDuration": "1000",
			"hideDuration": "1000",
			"timeOut": "1000",
			"extendedTimeOut": "1000",
			"showEasing": "swing",
			"hideEasing": "linear",
			"showMethod": "show",
			"hideMethod": "hide"
		  }
		  toastr.error('复制失败!');
	}
}
