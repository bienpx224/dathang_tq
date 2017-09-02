<!--此文件必须放在后面,放到:foot.php 里面-->

<script language="javascript">
//列表页:保存分页ID-----------------------------------------------------------------------
function id_save()
{
	var eless = document.getElementsByName('<?=DelStr(DelStr($id_name,'X',1),'M',1)?>[]');//必须用Name
	var id="";
	for(var i=0;i<eless.length;i++)
	  {
		 if(eless[i].checked)
		  {
			   if(id!=""){ 
				   id=id+','+eless[i].value;
			   }else{ 
				   id=eless[i].value;
			   }
		  }
	}
	if (typeof(id) == "undefined"){var id=0;}//判断
		
	var id_save_xmlhttp=createAjax(); 
	if (id_save_xmlhttp) 
	{  
		id_save_xmlhttp.open('POST','/public/idSave.php?n='+Math.random(),true); 
		id_save_xmlhttp.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
		id_save_xmlhttp.send('lx=bc&id_name=<?=$id_name?>&page=<?=par($_GET['page'])?>&id='+id+'');
		id_save_xmlhttp.onreadystatechange=function() 
		{  
			if (id_save_xmlhttp.readyState==4 && id_save_xmlhttp.status==200) 
			{ 
				if($('#IDNumber').length>0){
					document.getElementById('IDNumber').innerHTML=unescape(id_save_xmlhttp.responseText);
				}
			}
		}
	}

}
	
if($('#IDNumber').length>0){	$(function(){ id_save(); });  }
</script>





<script language="javascript">
//列表页:展开简化内容-----------------------------------------------------------------------

function TestBlack(tri)
{
	if($('#trshow1').length<=0){return;}//不存在

	var  AllTrBlack='';if($('[id="AllTrBlack"]').length>0){ AllTrBlack=$('#AllTrBlack').val();}
	if(AllTrBlack=='allClose'){return;}
	
	//全部隐藏/展开
	for (var i=1;i<10000;i++)
	{
		if($('#trshow'+i).length>0){ 
			$('#trshow'+i).css("display","none");
		}else{
			break;
		}
	}


	//处理
	var trshow =$('#trshow'+tri);
	if(trshow.attr('display')==""){
		trshow.css("display","none");
	}else{
		//显示
		trshow.css("display","");//注意不能用block ,会变成块显示,导致错位
		trshow.css("visibility","visible");
		trshow.css("position","static");
		
		//打开网址
		var url=trshow.attr('url');
		var target=trshow.attr('target');
		if(url&&target&& $("#"+target).height()<=0 ){window.open(url,target);}
	}
}
$(function(){ TestBlack(1); });



//全部展开/收缩
function AllTrOpen()
{
	if($('#trshow1').length<=0){return;}//不存在

	var  AllTrBlack=$('#AllTrBlack').val();
	
	//设置按钮
	if(AllTrBlack=='allClose')
	{
		$('[id=AllTrBlackName]').html('<?=$LG['allOpen']//全部展开 名称?>');
		$('[id=AllTrBlack]').val('allOpen');
		$('[id=AllTrBlackIco]').attr("class","icon-resize-full");
	}else{
		$('[id=AllTrBlackName]').html('<?=$LG['allClose']//全部收缩 名称?>');
		$('[id=AllTrBlack]').val('allClose');
		$('[id=AllTrBlackIco]').attr("class","icon-resize-small");
	}
	
	//全部隐藏/展开
	for (var i=1;i<10000;i++)
	{
		if($('#trshow'+i).length>0){ 
		
			if(AllTrBlack=='allClose'){
				$('#trshow'+i).css("display","none");//全部隐藏
			}else{
				$('#trshow'+i).css("display","");//全部展开 //注意不能用block ,会变成块显示,导致错位

				//打开网址
				var url=$('#trshow'+i).attr('url');
				var target=$('#trshow'+i).attr('target');
				if(url&&target&& $("#"+target).height()<=0 ){window.open(url,target);}
			}
			
		}else{
			break;
		}
	}
}
</script>



<script>
//页面载入完之后才能提交
$(function(){
	//载入完后转为可使用
	if($('#openSmt1').length>0){ $('#openSmt1').removeAttr("disabled"); }
	if($('#openSmt2').length>0){ $('#openSmt2').removeAttr("disabled"); }
	if($('#openSmt3').length>0){ $('#openSmt3').removeAttr("disabled"); }
	if($('#openSmt4').length>0){ $('#openSmt4').removeAttr("disabled"); }
	if($('#openSmt5').length>0){ $('#openSmt5').removeAttr("disabled"); }
});
</script>
