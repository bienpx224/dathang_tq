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
$pervar='member_ed,member_se';require_once($_SERVER['DOCUMENT_ROOT'].'/xingao/incluce/session.php');
$headtitle=cadd($_GET['key'])." 会员推广记录";
require_once($_SERVER['DOCUMENT_ROOT'].'/xingao/incluce/head.php');


//删除
$lx=par($_POST['lx']);
if($lx=='del')
{
	$date=par($_POST['date']);
	$key=par($_GET['key']);
	
	if(!CheckEmpty($date)){exit ("<script>alert('请填写要删除多少月之前 (填0则删除全部)！');goBack();</script>");}
	
	$where="1=1";
	
	if($date)
	{
		$start =strtotime('-'.$date.' Month');
		$where.=" and addtime<".$start;
	}
	if($key){$where.=" and (userid='".CheckNumber($key,-0.1)."' or username='{$key}' or tg_userid='".CheckNumber($key,-0.1)."' or tg_username='{$key}')";}

	$xingao->query("delete from tuiguang_bak where {$where} ");//删除用户日志
	$rc=mysqli_affected_rows($xingao);
	if($rc>0){
		echo "<script>alert('删除{$key}记录成功,共删除{$rc}条！');</script>";
	}else{
		echo "<script>alert('{$LG['pptDelEmpty']}{$key}记录！');</script>";
	}
}

//搜索
$where="1=1";
$so=(int)$_GET['so'];
if($so)
{
	$key=par($_GET['key']);
	$stime=par($_GET['stime']);
	$etime=par($_GET['etime']);
	$status=par($_GET['status']);
	if($key){$where.=" and (userid='".CheckNumber($key,-0.1)."' or username='{$key}' or tg_userid='".CheckNumber($key,-0.1)."' or tg_username='{$key}')";}
	if($stime){$where.=" and addtime>='".strtotime($stime.' 00:00:00')."'";}
	if($etime){$where.=" and addtime<='".strtotime($etime.' 23:59:59')."'";}

	$search.="&so={$so}&key={$key}&stime={$stime}&etime={$etime}";
}

$timequerycall=1;
$field="addtime";
include($_SERVER['DOCUMENT_ROOT'].'/public/timequery.php');//按时间快速查询


$order=' order by id desc';//默认排序
include($_SERVER['DOCUMENT_ROOT'].'/public/orderby.php');//排序处理页


//echo $search;exit();
$query="select * from tuiguang_bak where {$where} {$order}";

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
        </a> 
		<small>(<a href="?so=1<?=$search?>&key=" class="gray">查看所有会员</a>)</small>
		</h3>
      
      <!-- END PAGE TITLE & BREADCRUMB--> 
    </div>
  </div>
  <!-- END PAGE HEADER--> 
  
  <!-- BEGIN PAGE CONTENT-->
  <div class="portlet tabbable">
    <div class="portlet-body" style="padding:10px;"> 
      <!--搜索-->
      <div class="navbar navbar-default" role="navigation">
        
        <div class="collapse navbar-collapse navbar-ex1-collapse">
          <form class="navbar-form navbar-left"  method="get" action="?">
            <input name="so" type="hidden" value="1">
            <div class="form-group">
              <input type="text" name="key" class="form-control input-msmall popovers" data-trigger="hover" data-placement="right"  data-content="推广ID/推广会员名 / 注册会员ID/注册会员名" value="<?=$key?>">
            </div>
            <div class="form-group">
                <div class="col-md-0">
                  <div class="input-group input-xmedium date-picker input-daterange" data-date-format="yyyy-mm-dd">
                    <input type="text" class="form-control input-small" name="stime" value="<?=$stime?>">
                    <span class="input-group-addon">-</span>
                    <input type="text" class="form-control input-small" name="etime" value="<?=$etime?>">
                  </div>
                 </div>
              </div>
            
            <button type="submit" class="btn btn-info"><i class="icon-search"></i> <?=$LG['search']//搜索?></button>
          </form>
        </div>
        
        <?php
		$timequeryshow=1;
		$searchtime="&so={$so}&key={$key}";
		include($_SERVER['DOCUMENT_ROOT'].'/public/timequery.php');//按时间快速查询
		?>
      </div>
      <form action="?<?=$search?>" method="post" name="XingAoForm">
        <input name="lx" type="hidden">
        <table class="table table-striped table-bordered table-hover" >
          <thead>
            <tr>
              <th align="center"><a href="?<?=$search?>&orderby=username&orderlx=" class="<?=orac('username')?>">推广会员</a></th>
              
              <th align="center"><a href="?<?=$search?>&orderby=tg_username&orderlx=" class="<?=orac('tg_username')?>">注册会员</a></th>
			  <th align="center"><a href="?<?=$search?>&orderby=integral&orderlx=" class="<?=orac('integral')?>">送积分</a></th>
               <th align="center"><a href="?<?=$search?>&orderby=coupons&orderlx=" class="<?=orac('coupons')?>"><?=$LG['coupons.headtitle'];//优惠券/折扣券?></a></th>
             <th align="center"><a href="?<?=$search?>&orderby=status&orderlx=" class="<?=orac('status')?>"><?=$LG['status']//状态?></a></th>
			  <th align="center"><a href="?<?=$search?>&orderby=invalid_content&orderlx=" class="<?=orac('invalid_content')?>">无效原因</a></th>
              
              <th align="center"><a href="?<?=$search?>&orderby=addtime&orderlx=" class="<?=orac('addtime')?>">注册时间</a></th>
            
            </tr>
          </thead>
          <tbody>
<?php
while($rs=$sql->fetch_array())
{
?>
            <tr class="odd gradeX  <?=!spr($rs['status'])?'gray2':''?>">
              
              <td align="center">
			  <?=cadd($rs['username'])?><br>
             <font class="gray2"><?=$rs['userid']?></font>
             </td>
            
              <td align="center">
             			  
			  <?=cadd($rs['tg_username'])?><br>
                <font class="gray2">
                <?=$rs['tg_userid']?>
                </font>
                

              </td>
			   <td align="center"><?=spr($rs['integral'])?></td>
			   <td align="center"><?=spr($rs['coupons'])?></td>
              <td align="center"><?=spr($rs['status'])?'有效':'无效'?></td>
			   <td align="center"><?=cadd($rs['invalid_content'])?></td>
             
              <td align="center"><?=DateYmd($rs['addtime'],1)?></td>
              
            </tr>
<?php
}
?>
          </tbody>
        </table>				
			
            
<!--底部操作按钮固定--> 

<style>body{margin-bottom:50px !important;}</style><!--后台不用隐藏,增高底部高度-->
<div align="right" class="fixed_btn" id="Autohidden">


<input type="text" class="form-control input-xsmall" name="date" title="删除X月之前 (填0则删除全部)">月
		
		<!--btn-danger--><button type="submit" class="btn btn-grey" onClick="
		document.XingAoForm.lx.value='del';
		return confirm('注意：此记录用于数据统计，删除后将影响统计结果！\r此操作不可恢复！确认要删除所选吗？ ');
		"><i class="icon-signin"></i> 删除记录 </button>
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
require_once($_SERVER['DOCUMENT_ROOT'].'/xingao/incluce/foot.php');
?>
