//信息AJAX调用
function msg_update() 
{
	var xmlhttp_msg_number=createAjax(); 
	if (xmlhttp_msg_number) 
	{  
		var span_number=document.getElementById('msg_number');  // 获取显示节点
		xmlhttp_msg_number.open('get','/xingao/msg/nav_msg.php?lx=msg_number&n='+Math.random(),true); 
		xmlhttp_msg_number.onreadystatechange=function() 
		{  
			if (xmlhttp_msg_number.readyState==4 && xmlhttp_msg_number.status==200) 
			{ 
			span_number.innerHTML=unescape(xmlhttp_msg_number.responseText); //innerHTML输出到页面；value输出到文本框；
			}
		}
	xmlhttp_msg_number.send(null); 
	}

	var xmlhttp_msg_list=createAjax(); 
	if (xmlhttp_msg_list) 
	{  
		var span_list=document.getElementById('msg_list');  // 获取显示节点
		xmlhttp_msg_list.open('get','/xingao/msg/nav_msg.php?lx=msg_list&n='+Math.random(),true); 
		xmlhttp_msg_list.onreadystatechange=function() 
		{  
			if (xmlhttp_msg_list.readyState==4 && xmlhttp_msg_list.status==200) 
			{ 
			span_list.innerHTML=unescape(xmlhttp_msg_list.responseText); //innerHTML输出到页面；value输出到文本框；
			}
		}
		
	xmlhttp_msg_list.send(null); 
	}
}



