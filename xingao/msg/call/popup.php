<!-----------------------------------------------自动弹出------------------------------------------>	
<?php 
$popup_auto=0;
$prompt_time=$manage_prompt_time;

//弹出站内公告------------------------------------------------------
if(DateDiff(time(),$_SESSION['manage']['popuptime'],'i')>=$prompt_time||!$_SESSION['manage']['popuptime'])
{
    $popup_r=FeData('notice','noid,title,content,edittime,addtime,popuptime',"popup='1' and checked='1' and to_groupid in (0,{$Xgroupid}) and status in (0,1) order by edittime desc,addtime desc");

    if(DateDiff(time(),$_SESSION['manage']['popuptime'],'i')>=$popup_r['popuptime']&&$popup_r['noid'])
    {
        $_SESSION['manage']['popuptime']=time();
		$xingao->query("update manage set popuptime='".time()."' where userid='{$Xuserid}'");
		SQLError('更新弹出时间');
		
		$popup_auto=1;
		$popup_title=striptags($popup_r['title']);
		$popup_content=caddhtml($popup_r['content']);
		$popup_time=DateYmd($popup_r['edittime'],0,$popup_r['addtime']);
    }
}






//关闭弹出：更新------------------------------------------------------
$popup_table=par($_GET['popup_table']);
$popup_close=spr($_GET['popup_close']);
$popup_id=par($_GET['popup_id']);//有all参数，不能用spr

if($popup_close&&$popup_id)
{
	if($popup_table=='transfer'&&$ON_bankAccount&&permissions('member_re','','manage',1))
	{
		$xingao->query("update transfer set popup='0' where popup='1'");
		SQLError('关闭弹出');
	}
}





//弹出转账充值申请：查询------------------------------------------------------
if(!$popup_auto&&$ON_bankAccount&&permissions('member_re','','manage',1))
{
	$query_popup="select tfid,addtime from transfer where popup=1 order by addtime asc";//获取最新时间,所以用asc
	$sql_popup=$xingao->query($query_popup);
	while($popup=$sql_popup->fetch_array())
	{
		$popup_tfid=$popup['tfid'];
		$popup_addtime=$popup['addtime'];
	}
	$popup_num=mysqli_num_rows($sql_popup);
	
	if($popup_num)
	{
		$popup_auto=1;
		$popup_title='新转账充值申请 提示';
		$popup_content='目前有 <font class="red"><strong>'.$popup_num.'</strong></font> 个转账充值申请待处理!';
		$popup_time=DateYmd(time());
		
		$popup_url="/xingao/transfer/list.php?popup_table=transfer&popup_close=1&popup_id=all";
		if($popup_num==1){$popup_url="/xingao/transfer/form.php?lx=edit&tfid={$popup_tfid}&popup_table=transfer&popup_close=1&popup_id=all";}
		
		$button_up='
		<a href="'.$popup_url.'" class="btn btn-info">马上处理</a>
		<a href="?popup_table=transfer&popup_close=1&popup_id=all" class="btn btn-success">知道了，不再提醒</a>
		';
	}
	
}
?>



<?php if($popup_auto){?>
        <a class="btn btn-xs btn-default" data-toggle="modal" href="#at" id='popup' style="display:none"></a>
        <div class="modal fade" id="at" tabindex="-1" role="basic" aria-hidden="true">
          <div class="modal-dialog modal-wide">
             <div class="modal-content">
                <div class="modal-header">
                   <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                   <h4 class="modal-title"><?=$popup_title?></h4>
                </div>
                <div class="modal-body">
                    <?=$popup_content?>
                    
                   <div align="right" class="gray2">
                   <?=$popup_time?>
                   </div>
                </div>
                <div class="modal-footer">
               	   <?=$button_up?>
                   <button type="button" class="btn btn-danger" data-dismiss="modal"> 关 闭 </button>
                </div>
             </div>
          </div>
        </div>
        <script>
          $(function(){       
            lnk = document.getElementById("popup");
            lnk.click();
          });
        </script>
<?php }?>