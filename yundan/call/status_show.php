<?php 

//查询子站-部分1-开始
$SonWebsite=(int)$_GET['SonWebsite'];//最前面没有载入任何函数,要用自带函数
if($SonWebsite)
{
	require_once($_SERVER['DOCUMENT_ROOT'].'/public/config_top.php');
	require_once($_SERVER['DOCUMENT_ROOT'].'/public/html.php');
	require_once($_SERVER['DOCUMENT_ROOT'].'/public/function.php');
	$ism=1;require_once($_SERVER['DOCUMENT_ROOT'].'/template/incluce/header.php');
	require_once($_SERVER['DOCUMENT_ROOT'].'/xingao/yundan/call/update.php');//自动更新处理
	if(!$ON_SonWebsite){exit(LGtag($LG['front.22'],'<tag1>=='.$siteurl));}
	
	echo '<style>html{  overflow-x:hidden;/*横滚动条隐藏*/}</style>';
}
//查询子站-部分1-结束


//获取
$lx=par($_GET['lx']);
$ydh=TextareaToCo($_GET['ydh']);
$ydh=par($ydh);
$num=arrcount($ydh);
$numlimit=20;
$SonWebsite_ydh=$ydh;

//验证
$ts='';
$i=0;
if($num){$ok=1;}
if(!$num){$ts.= '<i class="icon-warning-sign"></i> 请填写运单号!';$ok=0;}
if($num>$numlimit){$ts.= '<i class="icon-warning-sign"></i>'.LGtag($LG['front.24'],'<tag1>=='.$numlimit);$ok=0;}

echo $ts;

//查询
if($ok)
{
	$sqlydh=str_replace(',',"','",$ydh);
	$query="select ydid,ydh,status,statustime,api_status,api_time,api_comnu,gnkd,gnkdydh,lotno from yundan where ydh in ('".$sqlydh."') order by ydh desc";
	$sql=$xingao->query($query);
	while($rs=$sql->fetch_array())
	{
		$i+=1;
		$rs['ydh']=cadd($rs['ydh']);
		$SonWebsite_ydh=str_ireplace($rs['ydh'],'',$SonWebsite_ydh);//删除已查询到的单号
	?>
	<table class="table table-striped table-bordered table-hover" style=" <?=$SonWebsite?'width:890px;':''?>">
		<thead>
			<tr>
				<th colspan="2" align="left"><i class="icon-reorder"></i> <?=cadd($rs['ydh'])?> <?=$rs['lotno']?cadd("({$LG['function_types.34']}:{$rs['lotno']})"):''?></th>
				</tr>
		</thead>
		<tbody>
		
		
        
        
        
        
		
		<!------------------------------------主表:未审核和拒绝--------------------------------->
		<?php if(spr($rs['status'])<=1){?>
			<tr class="odd gradeX">
				<td align="center" valign="middle" width="170"><strong><?=DateYmd($rs['statustime'],1)?></strong></td>
				<td align="left" valign="middle"><?=status_name(spr($rs['status']))?></td>
			</tr>
		<?php }?>
		
        
        
        
        
        
        
        
        
		
		<!----------------------------------记录表:发快递之前----------------------------------->
		<?php if(spr($rs['status'])>1){?>
			<?php
			$query_bak="select * from yundan_bak where ydid={$rs[ydid]} and status<='20' order by statustime asc";
			$sql_bak=$xingao->query($query_bak);
			while($bak=$sql_bak->fetch_array())
			{
			?>	
			<tr class="odd gradeX">
				<td align="center" valign="middle" width="170"><strong><?=DateYmd($bak['statustime'],1)?></strong></td>
				<td align="left" valign="middle">
				<?=status_name($bak['status'])?>
                <?php 
				if($bak['status']==20&&$rs['gnkd']&&$rs['gnkdydh']){
					echo '<font class="red2">'.cadd($expresses[$rs['gnkd']]).'：'.cadd($rs['gnkdydh']).'</font>';
				}?>
                </td>
			</tr>
			<?php }?>
		<?php }?>
		
		
		
        
        
        
        
        
        
		<!---------------------------------主表:快递查询------------------------------------>
        <tr class="odd gradeX" >
            <td colspan="2" align="center" valign="middle" style="padding:0px;">
              <span id="status_ajax_msg<?=$rs['ydid']?>"><br><img src="/images/loading_2.gif"/><br></span>
            </td>
        </tr>
        <script>$(function(){ status_ajax(<?=$rs['ydid']?>); });</script>

        
        
        
        
        
		
		<!-------------------------------记录表:发快递之后-------------------------------------->
		<?php if(spr($rs['status'])>1){?>
			<?php
			$query_bak="select * from yundan_bak where ydid={$rs[ydid]} and status>'20' order by statustime asc";
			$sql_bak=$xingao->query($query_bak);
			while($bak=$sql_bak->fetch_array())
			{
			?>	
			<tr class="odd gradeX">
				<td align="center" valign="middle" width="170"><strong><?=DateYmd($bak['statustime'],1)?></strong></td>
				<td align="left" valign="middle">
				<?=status_name($bak['status'])?>
                <?php 
				if($bak['status']==20&&$rs['gnkd']&&$rs['gnkdydh']){
					echo '<font class="red2">'.cadd($expresses[$rs['gnkd']]).':'.cadd($rs['gnkdydh']).'</font>';
				}?>
                </td>
			</tr>
			<?php }?>
		<?php }?>
		
		

		</tbody>
	</table>
    <br>
	<?php }//while($rs=$sql->fetch_array())?>
  
  
  
  
  
  
  
  
  
  
  
  
  
  
	<!---------------------------------以上查询不到时,则查询子站------------------------------------>
	<?php  
	//查询子站 部分2-开始
	if($ON_SonWebsite_main&&$SonWebsite_ydh)
	{
		$swydh_arr=ToArr($SonWebsite_ydh,"','");
		$arr=ToArr($SonWebsiteList,1);
		if($arr)
		{
			foreach($arr as $arrkey=>$value)
			{
				//获取子站资料
				$sw=ToArr($value,2);
				$sw_order=$sw[0];//子站单号区别
				$sw_url=$sw[1];//子站网址
				if($_SERVER['HTTP_HOST']==$sw_url){continue;}//网址是主站时,跳过
				
				//获取运单资料
				$swydh='';
				if($swydh_arr)
				{
					foreach($swydh_arr as $swydh_arr_key=>$swydh_value)
					{
						if($swydh_value&&have($swydh_value,$sw_order,0)){$swydh.=$swydh_value.'%0D%0A';}//%0D%0A 是文本域GET发送时的换行符号
					}
				}
				
				if($sw_order&&$sw_url&&$swydh)
				{
					$sw_i++;
					?>
                    <!-- 跨域自动高度无效,只能用滚动条-->
					<iframe src="http://<?=$sw_url?>/yundan/call/status_show.php?SonWebsite=1&ydh=<?=$swydh?>"  width="100%" height="400" frameborder="0" scrolling="auto"></iframe>
					<?php		
				}
				
				
			}
		}
	}
	//查询子站 部分2-结束
	?>  
    
    
    
    
    
    
    
    
    
    
    
    
    
    <?php if(!$SonWebsite){?>
    <!--目前属于查询子站时则不显示-->
    <div class="gray2">
    &raquo; <?=LGtag($LG['front.29'],'<tag1>=='.$i.'||<tag2>== '.($num?'('.$num.')':''))?><br>
    &raquo; <?=$LG['front.27'];//部分运单需要外调第三方运输公司的物流状态，因此查询可能较慢，请耐心等待！?><br>
    </div>	
	<?php }?>	
    
    	
<?php 
}
?>		

<script>
//调用API数据
function status_ajax(ydid)
{
	var xmlhttp=createAjax(); 
	if (xmlhttp) 
	{ 
		xmlhttp.open('POST','/yundan/call/status_ajax.php?n='+Math.random(),true); 
		xmlhttp.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
		xmlhttp.send('lx=status_ajax&ydid='+ydid);

		xmlhttp.onreadystatechange=function() 
		{  
			if (xmlhttp.readyState==4 && xmlhttp.status==200) 
			{ 
				document.getElementById('status_ajax_msg'+ydid).outerHTML='<table class="table table-striped table-bordered table-hover" style="margin-bottom:0px;"><tbody>'+unescape(xmlhttp.responseText)+'</tbody></table>'; 
				/*必须外部加表格,否则错位*/
			}
		}
	}
}
</script>
