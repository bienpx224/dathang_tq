<script type="text/javascript">
//---------------------------------------------------------------------------------------
//-------------------------------------------通用验证------------------------------------
//---------------------------------------------------------------------------------------






//密码验证----------------------------------------------------------------------------------------------
function check_password(ts)
{
	var temp = document.getElementsByName("password")[0].value;
	if(temp.length<6||temp.length>20)
	{
		document.getElementById('msg_password').innerHTML=ts;
		password.focus();
		return false;
	}
	else
	{     
		if(checkPass(temp)==1){
			document.getElementById('msg_password').innerHTML='<font color="#999999" title="<?=$LG['js.1'];//用大写字母+小写字母+数字+特殊符号 提高安全等级?>"><?=$LG['js.3'];//安全等级:?><font color="#FF0000">★</font>☆☆☆</font>';
			return false;
		}else if(checkPass(temp)==2){
			document.getElementById('msg_password').innerHTML='<font color="#999999" title="<?=$LG['js.1'];//用大写字母+小写字母+数字+特殊符号 提高安全等级?>"><?=$LG['js.3'];//安全等级:?><font color="#FF9900">★★</font>☆☆</font>';
			return false;
		}else if(checkPass(temp)==3){
			document.getElementById('msg_password').innerHTML='<font color="#999999" title="<?=$LG['js.1'];//用大写字母+小写字母+数字+特殊符号 提高安全等级?>"><?=$LG['js.3'];//安全等级:?><font color="#66CCCC">★★★</font>☆</font>';
			return false;
		}else if(checkPass(temp)==4){
			document.getElementById('msg_password').innerHTML='<font color="#999999" title="<?=$LG['js.1'];//用大写字母+小写字母+数字+特殊符号 提高安全等级?>"><?=$LG['js.3'];//安全等级:?><font color="#009900">★★★★</font></font>';
			return false;
		}else
		{     
			document.getElementById('msg_password').innerHTML="";
		}
	}
	
}
function checkPass(pass){ 
	var ls = 0; 
	if(pass.match(/([a-z])+/)){ ls++; } 
	if(pass.match(/([0-9])+/)){ ls++; } 
	if(pass.match(/([A-Z])+/)){ls++; } 
	if(pass.match(/[^a-zA-Z0-9]+/)){ls++;}  
	return ls 
} 


function check_password2()
{
   var temp = document.getElementsByName("password")[0].value;
   var temp2 = document.getElementsByName("password2")[0].value;
  //对电子邮件的验证
  if(temp!=temp2)
    {
      document.getElementById('msg_password2').innerHTML="<?=$LG['js.2'];//2次输入的密码不一样?>";
      //password2.focus();//不能强制焦点
      return false;
    }
	else
	{     
	 document.getElementById('msg_password2').innerHTML="";
	}
}



//验证码验证----------------------------------------------------------------------------------------------
function checkcode(v)  
{
	
	var code=document.getElementsByName("code")[0].value;
	var span=document.getElementById('msg_code');  // 获取显示节点
	if(code.length<4)
	{
		span.innerHTML='';
		return false; 
	}
	
	var xmlhttp_code=createAjax(); 
	if (xmlhttp_code) 
	{
		xmlhttp_code.open('POST','/public/code/yz.php?n='+Math.random(),true); 
		xmlhttp_code.setRequestHeader("Content-Type","application/x-www-form-urlencoded"); 
		xmlhttp_code.send('v='+v+'&code='+code+'');
		xmlhttp_code.onreadystatechange=function() 
		{  
			if (xmlhttp_code.readyState==4 && xmlhttp_code.status==200) 
			{ 
			span.innerHTML=unescape(xmlhttp_code.responseText); //innerHTML输出到页面；value输出到文本框；
			}
		}
	}
}

</script>