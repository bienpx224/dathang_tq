<!-----------------------------------------------自动弹出------------------------------------------>	
<?php 
$popup_auto=0;
$prompt_time=$member_prompt_time;


	



//弹出公告------------------------------------------------------
if(DateDiff(time(),$_SESSION['member']['popuptime'],'i')>=$prompt_time||!$_SESSION['member']['popuptime'])
{
    $popup_allclassid=$news_classid.SmallClassID($news_classid);
    $popup_r=FeData('article',"id,title{$LT},content{$LT},edittime,addtime"," checked=1 and classid in ({$popup_allclassid}) and isgood=3 order by istop desc,isgood desc,ishead desc,edittime desc,addtime desc");
    if($popup_r['id'])
    {
        $_SESSION['member']['popuptime']=time();
		$xingao->query("update member set popuptime='".time()."' where userid='{$Muserid}' ");
		SQLError('更新弹出时间');
		
		$popup_auto=1;
		$popup_title=striptags($popup_r['title'.$LT]);
		$popup_content=caddhtml($popup_r['content'.$LT]);
		$popup_time=DateYmd($popup_r['edittime'],0,$popup_r['addtime']);
    }
}






//关闭弹出：更新------------------------------------------------------
$popup_table=par($_GET['popup_table']);
$popup_close=spr($_GET['popup_close']);
$popup_id=spr($_GET['popup_id']);
if($popup_table=='msg'&&$popup_close&&$popup_id)
{
	$xingao->query("update msg set popup='0' where id='{$popup_id}' {$Mmy}");
	SQLError('关闭弹出');
}

//弹出站内信：查询------------------------------------------------------
if(!$popup_auto)
{
	$popup_r=FeData('msg','id,title,content,popup,popuptime,edittime,addtime'," popup=1 and userid='{$Muserid}' order by id desc");
	
	//$popup_r['popuptime']=0; //测试
	if(DateDiff(time(),$popup_r['popuptime'],'i')>=$prompt_time||!$popup_r['popuptime'])
	{
		if($popup_r['id'])
		{
			$xingao->query("update msg set popuptime='".time()."' where id='{$popup_r['id']}'");
			SQLError('更新弹出时间');
			
			$popup_auto=1;
			$popup_title=striptags($popup_r['title']);
			$popup_content=caddhtml($popup_r['content']);
			$popup_time=DateYmd($popup_r['edittime'],0,$popup_r['addtime']);
			
			$button_up='<a href="?popup_table=msg&popup_close=1&popup_id='.$popup_r['id'].'" class="btn btn-success"  style="color:#ffffff">'.$LG['msg.popup_2'].'</a>';
		}
	}
}





//弹出提示修改临密码------------------------------------------------------
if(stristr($Mrnd,'tmpPassword')&&!$popup_auto)
{
	$popup_auto=1;
	$popup_title=$LG['msg.popup_3'];//'请修改密码';
	$popup_content=$LG['msg.popup_4'];//'为了您的账号安全,请及时修改临时密码';
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
                    
                   <?php if($popup_time){?><div align="right" class="gray2"><?=$popup_time?></div><?php }?>
                </div>
                <div class="modal-footer">
               	   <?=$button_up?>
                   <button type="button" class="btn btn-danger" data-dismiss="modal"> <?=$LG['msg.popup_1']?> </button>
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