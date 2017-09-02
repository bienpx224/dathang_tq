<?php
/*
软件著作权：=====================================================
软件名称：兴奥国际物流转运网站管理系统(简称：兴奥转运系统)V7.0
著作权人：广西兴奥网络科技有限责任公司
软件登记号：2016SR041223
网址：www.xingaowl.com
本系统已在中华人民共和国国家版权局注册，著作权受法律及国际公约保护！
版权所有，未购买严禁使用，未经书面许可严禁开发衍生品，违反将追究法律责任！
*/
require_once($_SERVER['DOCUMENT_ROOT'].'/public/config_top.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/public/html.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/public/function.php');
$pervar='pinglun';require_once($_SERVER['DOCUMENT_ROOT'].'/xingao/incluce/session.php');
$headtitle="评论管理";
require_once($_SERVER['DOCUMENT_ROOT'].'/xingao/incluce/head.php');

//搜索
$where="1=1";
$so=(int)$_GET['so'];
if($so==1)
{
	$key=par($_GET['key']);
	$checked=par($_GET['checked']);
	$fromtable=par($_GET['fromtable']);
	$fromid=par($_GET['fromid']);
	$my=(int)$_GET['my'];
	$stime=par($_GET['stime']);
	$etime=par($_GET['etime']);
	
	if($key){$where.=" and (content like '%{$key}%'  or userid='".CheckNumber($key,-0.1)."' or username='{$key}')";}
	if($stime){$where.=" and addtime>='".strtotime($stime.' 00:00:00')."'";}
	if($etime){$where.=" and addtime<='".strtotime($etime.' 23:59:59')."'";}
	if(CheckEmpty($checked)){$where.=" and checked='{$checked}'";}
	if(CheckEmpty($fromtable)){$where.=" and fromtable='{$fromtable}'";}
	if($fromid){$where.=" and fromid='{$fromid}'";}
	if($my)
	{
		$reply_userid=$Xuserid;
		$where.=" and reply_userid='{$reply_userid}'";
	}
	$search.="&so={$so}&key={$key}&checked={$checked}&fromtable={$fromtable}&fromid={$fromid}&my={$my}&stime={$stime}&etime={$etime}";
}

if(!$my)
{
	$where.=" and reply_userid='0'";
}


$order=' order by cmid desc';//默认排序
include($_SERVER['DOCUMENT_ROOT'].'/public/orderby.php');//排序处理页


//echo $search;exit();
$query="select * from comments where {$where}  {$order}";

//分页处理
//$line=20;$page_line=15;//$line=-1则不分页(不设置则默认)
include($_SERVER['DOCUMENT_ROOT'].'/public/page.php');
?>

<div class="page_ny"> 
  <!-- BEGIN PAGE HEADER-->
	<div class="row"><!--单页时去掉 class="row",不然会有横滚动条,并且form 加 style="margin:20px;"-->
		<div class="col-md-12"> 
<!-- BEGIN PAGE TITLE & BREADCRUMB-->
      <h3 class="page-title"> <a href="javascript:void(0)" onClick="javascript:window.location.href=window.location.href;" title="<?=$LG['refresh']?>" class="gray">
        <?=$headtitle?>
        </a> </h3>
      
      <!-- END PAGE TITLE & BREADCRUMB--> 
    </div>
  </div>
  <!-- END PAGE HEADER--> 
  
  <!-- BEGIN PAGE CONTENT-->
  <div class="tabbable tabbable-custom boxless">
    <ul class="nav nav-tabs" style="margin-top:10px;">
      <li class="<?=$my?'':'active';?>"><a href="?">全部信息</a></li>
      <li class="<?=$my?'active':'';?>"><a href="?so=1&my=1">我回复的信息</a></li>
    </ul>
    <div class="tab-content" style="padding:10px;"> 
      <!--搜索-->
      <div class="navbar navbar-default" role="navigation">
        
        <div class="collapse navbar-collapse navbar-ex1-collapse">
          <form class="navbar-form navbar-left"  method="get" action="?">
            <input name="so" type="hidden" value="1">
            <input name="my" type="hidden" value="<?=$my?>">
            <div class="form-group">
              <input type="text" name="key" class="form-control input-msmall popovers" data-trigger="hover" data-placement="right"  data-content="会员ID/会员名/内容 (可留空)" value="<?=$key?>">
            </div>
            <div class="form-group">
              <div class="col-md-0">
                <div class="input-group input-xmedium date-picker input-daterange" data-date-format="yyyy-mm-dd">
                  <input type="text" class="form-control input-small" name="stime" value="<?=$stime?>" title="评论时间">
                  <span class="input-group-addon">-</span>
                  <input type="text" class="form-control input-small" name="etime" value="<?=$etime?>"  title="评论时间">
                </div>
              </div>
            </div>
            <div class="form-group">
              <div class="col-md-0">
                <select  class="form-control input-small select2me" name="fromtable" data-placeholder="系统">
                  <option></option>
                  <?=shaidan_Fromtable($fromtable,1)?>
                </select>
              </div>
            </div>
            <div class="form-group">
              <div class="col-md-0">
                <select  class="form-control input-small select2me" name="checked" data-placeholder="审核">
                  <option></option>
                  <option value="0"  <?=$checked=='0'?' selected':''?>>未审核</option>
                  <option value="1"  <?=$checked=='1'?' selected':''?>>已审核</option>
                </select>
              </div>
            </div>
            <button type="submit" class="btn btn-info"><i class="icon-search"></i> <?=$LG['search']//搜索?></button>
          </form>
        </div>
      </div>
      <form action="save.php" method="post" name="XingAoForm">
        <input name="lx" type="hidden">
        <input name="checked" type="hidden">
        <table class="table table-striped table-bordered table-hover" >
          <thead>
            <tr>
              <th align="center" class="table-checkbox"> <input type="checkbox"  id="aAll" onClick="chkAll(this)"  title="全选/取消"/>
              </th>
              <th align="left">受评论信息</th>
              <th align="center">操作</th>
            </tr>
          </thead>
          <tbody>
            <?php
while($rs=$sql->fetch_array())
{
?>
            <tr class="odd gradeX <?=!$rs['checked']?'gray2':''?>">
              <td align="center" valign="middle"><input type="checkbox" id="a" onClick="chkColor(this);" name="cmid[]" value="<?=$rs['cmid']?>" /></td>
              <td align="left" valign="middle"><?php 
			if($rs['fromtable']&&$rs['fromid'])
			{
            	
				if($rs['fromtable']=='mall'&&$off_mall)
				{
					$fr=mysqli_fetch_array($xingao->query("select title{$LT} from mall where mlid='{$rs[fromid]}'"));
					$url='/mall/show.php?mlid='.$rs['fromid'];
					$title='<a href="'.$url.'" target="_blank" >'.$fr['title'.$LT].'</a>';
					$label='<span class="label label-sm label-info">'.shaidan_Fromtable($rs['fromtable']).'</span>';
				}
				elseif($rs['fromtable']=='shaidan'&&$off_shaidan)
				{
					$fr=mysqli_fetch_array($xingao->query("select content,path from shaidan where sdid='{$rs[fromid]}'"));
					$url=cadd($fr['path']);
					$title='<a href="'.$url.'" target="_blank" >'.leng($fr['content'],100,"...").'</a>';
					$label='<span class="label label-sm label-success">'.shaidan_Fromtable($rs['fromtable']).'</span>';
				}
				else{
					$fr='';
					$title='';
					$label='';
				}
            ?>
                <?=$label?>
                <?=$title?>
                <a href="?so=1&fromid=<?=$rs['fromid']?>" target="_blank"  class="label label-sm label-default"> 全部评论</a>
                <?php }?></td>
              <td align="center" valign="middle"><a href="form.php?lx=edit&cmid=<?=$rs['cmid']?>" class="btn btn-xs btn-info" target="_blank"><i class="icon-edit"></i> <?=$LG['showedit']?></a> <a href="reply.php?lx=reply&cmid=<?=$rs['cmid']?>" class="btn btn-xs btn-info" target="_blank"><i class="icon-edit"></i> 回复</a> <a href="save.php?lx=del&cmid=<?=$rs['cmid']?>" class="btn btn-xs btn-danger"  onClick="return confirm('<?=$LG['pptDelConfirm']//确认要删除所选吗?此操作不可恢复!?>');"><i class="icon-remove"></i> <?=$LG['del']?></a></td>
            </tr>
            <tr>
              <td colspan="3" align="left"><!--内容-->
                
                <?php
                $zhi=LabelFace(cadd($rs['content']));
                if($zhi||$rs['img']){
                ?>
                <div class="gray modal_border">
                  <?php 
					if(!$rs['checked'])
					{
					echo '<span class="label label-sm label-warning">未审</span>';
					}	
					
					echo '<strong>'.$rs['username'].'</strong>';	
					echo ' ('.DateYmd($rs['addtime'],1).') ：';	  
					echo leng($zhi,200,"...");
					
					
					Modals($zhi,$title='详细评论',$time=$rs['addtime'],$nameid='content'.$rs['cmid'],$count=200);
                ?>
                  <!--评论图片-->
				  <?php if($rs['img']){EnlargeImg($rs['img'],$rs['cmid']); }?>
                  
                  
                </div>
                <?php }?>
                
                <!--回复-->
                
				<?php
                if($rs['rep'])
                {
					$query_rep="select * from comments where repcmid='{$rs[cmid]}' and reply_userid<>'' order by cmid desc";
					$sql_rep=$xingao->query($query_rep);
					while($rep=$sql_rep->fetch_array())
					{
						$zhi=cadd($rep['content']);
						if($zhi||$rep['img'])
						{
							?>
							<div class="gray modal_border"> <strong>
							<?=$rep['reply_username']?>
							管理回复</strong>
							<?php 
							echo ' ('.DateYmd($rep['addtime'],1).') ：';
							echo leng($zhi,200,"...");
							
							Modals($zhi,$title=$rep['reply_username'].' 管理回复',$time=$rep['addtime'],$nameid='reply'.$rep['cmid'],$count=200);
							?>
							<!--回复图片-->
							 <?php if($rep['img']){EnlargeImg($rep['img'],$rep['cmid']); }?>
							 
							
                            </div>
                            
                            <?php
						}
					}//while($rep=$sql_rep->fetch_array())
                }//if($rs['rep'])
                ?>
                
                <!----></td>
            </tr>
            <?php
}
?>
          </tbody>
        </table>				
			
            
<!--底部操作按钮固定--> 

<style>body{margin-bottom:50px !important;}</style><!--后台不用隐藏,增高底部高度-->
<div align="right" class="fixed_btn" id="Autohidden">


<button type="submit" class="btn btn-grey"
    onClick="
  document.XingAoForm.lx.value='attr';
  document.XingAoForm.checked.value='1'; 
  "><i class="icon-signin"></i> 设为已审</button>
          <button type="submit" class="btn btn-grey"
    onClick="
  document.XingAoForm.lx.value='attr';
  document.XingAoForm.checked.value='0'; 
  "><i class="icon-signin"></i> 设为未审</button>
          <!--btn-danger--><button type="submit" class="btn btn-grey" onClick="
  document.XingAoForm.lx.value='del';
  return confirm('<?=$LG['pptDelConfirm']//确认要删除所选吗?此操作不可恢复!?>');
  "><i class="icon-signin"></i> <?=$LG['delSelect']//删除所选?></button>
        </div>
        <div class="row">
          <?=$listpage?>
        </div>
      </form>
    </div>
    <!--表格内容结束--> 
    
  </div>
</div>
<?php
$sql->free(); //释放资源
$enlarge=1;//是否用到 图片扩大插件 (/public/enlarge/call.html)
require_once($_SERVER['DOCUMENT_ROOT'].'/xingao/incluce/foot.php');
?>
