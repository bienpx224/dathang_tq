<div style="padding:20px; padding-top:30px;">
    <div class="col-md-0">
     <input type="text" name="menu_key" class="form-control input-msmall  tooltips" data-container="body" data-placement="top" data-original-title="<?=$LG['gd.2'];//填写完后点一下框外便自动搜索 (不要有空格)?>" value="<?=$menu_key?>" placeholder="<?=$LG['gd.1'];//快速搜索 (可留空)?>" onBlur="gd_mosuda_list()";>
    </div>
  
  
<!--调用同一个表输出筛选菜单-开始-->           
<span id="options_producer"></span>
<span id="options_types"></span>
<span id="options_brand"></span>
<span id="options_unit"></span>
<span id="options_name"></span>

<script>
//显示下拉
/*
	chkbig=1验证是否有大分类
*/
function options_menu(options_id,table,field,id,chkbig)
{
	$.ajax({
        type: "POST",
        cache: false,
        data: 'lx=options_menu&warehouse=<?=$warehouse?>&channel=<?=$channel?>&table='+table+'&field='+field+'&id='+id+'&chkbig='+chkbig+'',
        async: true,//true导步处理;false为同步处理
        url: "/public/ajax.php",
        success: function (data) 
		{
			//首分类下拉只有一个或没有时,则隐藏,并显示第二个分类下拉
			if(chkbig&&Number(data)==1)
			{
				$(function(){ options_menu('options_producer','gd_mosuda','producer',''); });
			}else if(chkbig&&Number(data)==0){
				$(function(){ options_menu('options_types','gd_mosuda','types',''); });
			}else{
				
				//显示下拉-开始-----------------------
				document.getElementById(options_id).innerHTML=data; 
				
				//清空跳级下拉:
				//options_id操作的是下一个ID,因此当==options_brand时,其实是在options_producer操作
				if(options_id=='options_types'){
					document.getElementById('options_brand').innerHTML='';
					document.getElementById('options_unit').innerHTML='';
					document.getElementById('options_name').innerHTML='';
				}else if(options_id=='options_brand'){
					document.getElementById('options_unit').innerHTML='';
					document.getElementById('options_name').innerHTML='';
				}else if(options_id=='options_unit'){
					document.getElementById('options_name').innerHTML='';
				}
				//显示下拉-结束-----------------------

				//输出商品资料列表-----------------------
				gd_mosuda_list();
			}
		}
    });
}

$(function(){ options_menu('options_producer','gd_mosuda','producer','','1'); });
</script>
<!--调用同一个表输出筛选菜单-结束-->           
</div>







<div class="clear" style="padding:20px;">
    <span id="gd_mosuda_list"></span>
    
    <span class="help-block">
    <br>
     &raquo; <?=$LG['gd.3'];//搜索商品，然后点右边的“+”符号，添加到下面的列表中 （下面为已选列表，上面为未选列表）?><br>
	
     <?php if($callFrom=='member'){?>
         &raquo; <?=$LG['gd.4'];//如果未能找到该商品，说明该商品还没有备案，需要备案后才能清关，您可以选择其他渠道?>
         <?php if($ON_gd_mosuda_apply){?>
         <a href="/xamember/gd_mosuda/form.php" class="btn btn-xs btn-success showdiv" target="XingAobox"><?=$LG['gd.5'];//或者申请备案?></a>
         <?php }?>
         <br>
         
         
         <?php if($ON_gd_mosuda_apply){?>
         &raquo; <?=$LG['gd.6'];//申请备案后,需要通过备案后才能清送,因此可能会影响您的收货时间?><br>
         <?php }?>
         &raquo; <?=$LG['gd.13'];//税收是按商品备案价格收取，而非您所填写的单价 (所填单价是用于物品保价)?><br>
     <?php }?>
     
    </span>
</div>





<table width="100%" class="table table-striped  table-hover" id="tableProduct">
<!--自动计算要加这个 id="tableProduct"-->
<thead>
<tr>
     <?=wupin_header_general(1)?>
     <th align="center" class="title"></th>
</tr>
</thead>
<?php
$j=0;
if($fromtable&&$fromid)
{
	//读取该运单所用资料库
	if($fromtable=='yundan')
	{
		$yd=FeData('yundan','warehouse,channel',"ydid='{$fromid}'");
		$customs=channelPar(spr($yd['warehouse']),spr($yd['channel']),'customs');
	}

	$query_wp="select * from wupin where fromtable='{$fromtable}' and fromid in ({$fromid}) and gdid<>'0' order by wupin_brand asc,wupin_name asc";
	$sql_wp=$xingao->query($query_wp);
	while($wp=$sql_wp->fetch_array())
	{
		$j+=1;
		if($tag){$_SESSION[$tag].=','.$wp['gdid'];}
		
		//读取资料库
		if($customs&&$wp['gdid']){$gd=FeData($customs,'gdid,barcode',"gdid='{$wp['gdid']}'");$wp['barcode']=$gd['barcode'];}
		?>
        <tr class="odd gradeX <?=$wp['record']==1?'red2':''?>" id="line" style="height:35px;">
		<?=wupin_form_gd($wp,'edit',0,1)?>
        </tr>
		<?php		
	}
	if($tag){$_SESSION[$tag]=DelStr($_SESSION[$tag],',',1);}
}
?>
</table>








<script>
//输出商品资料列表
function gd_mosuda_list(page) {
	var menu_key=document.getElementsByName('menu_key')[0].value;
	

	var menu_producer=''; 
	if($('[name="menu_producer"]').length>0){menu_producer=document.getElementsByName('menu_producer')[0].value;}
	
	var menu_types=''; 
	if($('[name="menu_types"]').length>0){menu_types=document.getElementsByName('menu_types')[0].value;}
	
	var menu_brand=''; 
	if($('[name="menu_brand"]').length>0){menu_brand=document.getElementsByName('menu_brand')[0].value;}
	
	var menu_name=''; 
	if($('[name="menu_name"]').length>0){menu_name=document.getElementsByName('menu_name')[0].value;}
	
	var menu_unit=''; 
	if($('[name="menu_unit"]').length>0){menu_unit=document.getElementsByName('menu_unit')[0].value;}


    $.ajax({
        type: "POST",
        cache: false,
        data: 'lx=gd_mosuda_list&warehouse=<?=$warehouse?>&channel=<?=$channel?>&menu_key='+menu_key+'&menu_producer='+menu_producer+'&menu_types='+menu_types+'&menu_brand='+menu_brand+'&menu_name='+menu_name+'&menu_unit='+menu_unit+'&tag=<?=$tag?>&page='+page,
        async: true,//true导步处理;false为同步处理
        url: "/public/ajax.php",
        success: function (data) 
		{
			document.getElementById('gd_mosuda_list').innerHTML= data;
		}
    });
}
</script>





<a href="" id="applyEdit" class="showdiv" target="XingAobox"></a>    
<script>
//修改备案
function applyEdit(val)
{
	document.getElementById("applyEdit").href=val;
	document.getElementById("applyEdit").click()
}

//ajax更新点击
function addOnClick(id)
{
	var xmlhttp=createAjax();
	if (xmlhttp) 
	{ 
		xmlhttp.open('POST','/public/ajax.php?n='+Math.random(),true); 
		xmlhttp.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
		xmlhttp.send('lx=addOnClick&table=gd_mosuda&id_field=gdid&id='+id+'');
	}
}


//记录已加或已删
/*
	typ=add
	typ=del
*/
function UPtag(typ,id)
{
	var xmlhttp=createAjax();
	if (xmlhttp) 
	{ 
		xmlhttp.open('POST','/public/ajax.php?n='+Math.random(),true); 
		xmlhttp.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
		xmlhttp.send('lx=UPtag&typ='+typ+'&id='+id+'&tag=<?=$tag?>');

		xmlhttp.onreadystatechange=function() 
		{  
			if (xmlhttp.readyState==4 && xmlhttp.status==200) 
			{ 
				if(typ=='del'){gd_mosuda_list();}//删除时重新载入列表(添加时已自动删除,不用重新载入)
			}
		}
	}
}




//移动节点
function moveDetail(node) {
	$tr = $(node).parent().parent().clone();//clone()克隆原行
	$(node).parent().parent().remove();//移除原行
	
	$tr.find('[name=addHref]').hide();
	$tr.find('[name=editHref]').hide();
	$tr.find('[name=deleteHref]').show();
	$tr.find('[id=noCheck]').val('0');
	$tr.find('[id=productNum]').attr("required",true);		$tr.find('[id=productNum]').attr("class",'input_txt_red');
	$tr.find('[id=productPrice]').attr("required",true);	$tr.find('[id=productPrice]').attr("class",'input_txt_red');
	$table = $('#tableProduct');
	$tr.appendTo($table);
}
</script>
