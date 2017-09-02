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
$pervar='member_ed,member_in,member_se';require_once($_SERVER['DOCUMENT_ROOT'].'/xingao/incluce/session.php');
$headtitle=cadd($_GET['key'])." 会员加积分记录";
require_once($_SERVER['DOCUMENT_ROOT'].'/xingao/incluce/head.php');

//删除
$lx=par($_POST['lx']);
if($lx=='del')
{
	//开启“长期保存记录”时禁止删除的状态
	if($off_delbak){exit ("<script>alert('已开启“长期保存记录”功能，不能删除记录');goBack();</script>");}

	$date=par($_POST['date']);
	$key=par($_GET['key']);
	
	if(!CheckEmpty($date)){exit ("<script>alert('请填写要删除多少月之前 (填0则删除全部)！');goBack();</script>");}
	
	$where="1=1";
	
	if($date)
	{
		$start =strtotime('-'.$date.' Month');
		$where.=" and addtime<".$start;
	}
	if($key){$where.=" and (userid='".CheckNumber($key,-0.1)."' or username='{$key}' or fromid='".CheckNumber($key,-0.1)."' or title like '%{$key}%')";}
	$xingao->query("delete from integral_czbak where {$where} ");//删除用户日志
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
	$fromtable=par($_GET['fromtable']);

		if($key){$where.=" and (userid='".CheckNumber($key,-0.1)."' or username='{$key}' or fromid='".CheckNumber($key,-0.1)."' or title like '%{$key}%')";}

	if($stime){$where.=" and addtime>='".strtotime($stime.' 00:00:00')."'";}
	if($etime){$where.=" and addtime<='".strtotime($etime.' 23:59:59')."'";}
	if(CheckEmpty($fromtable)){$where.=" and fromtable='{$fromtable}'";}

	$search.="&so={$so}&key={$key}&stime={$stime}&etime={$etime}&fromtable={$fromtable}";
}

$timequerycall=1;
$field="addtime";
include($_SERVER['DOCUMENT_ROOT'].'/public/timequery.php');//按时间快速查询


$order=' order by id desc';//默认排序
include($_SERVER['DOCUMENT_ROOT'].'/public/orderby.php');//排序处理页


//echo $search;exit();
$query="select * from integral_czbak where {$where} {$order}";

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
              <input type="text" name="key" class="form-control input-msmall popovers" data-trigger="hover" data-placement="right"  data-content="会员ID/会员名/使用说明" value="<?=$key?>">
            </div>
                
             <div class="form-group">
                <div class="col-md-0">
                     <select  class="form-control input-small select2me" name="fromtable" data-placeholder="用途" >
                     <option></option>
                	 <?=fromtableName($fromtable,1)?>
                  	 </select>
               </div>
              </div>
            <div class="form-group">
                <div class="col-md-0">
                  <div class="input-group input-xmedium date-picker input-daterange" data-date-format="yyyy-mm-dd">
                    <input type="text" class="form-control input-small" name="stime" value="<?=$stime?>" placeholder="获分时间-起">
                    <span class="input-group-addon">-</span>
                    <input type="text" class="form-control input-small" name="etime" value="<?=$etime?>" placeholder="获分时间-止">
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
              <th align="center"><a href="?<?=$search?>&orderby=username&orderlx=" class="<?=orac('username')?>">会员</a>/<a href="?<?=$search?>&orderby=userid&orderlx=" class="<?=orac('userid')?>">ID</a></th>
              
              <th align="center"><a href="?<?=$search?>&orderby=type&orderlx=" class="<?=orac('type')?>">加积分类型</a></th>
               <th align="center"><a href="?<?=$search?>&orderby=fromtable&orderlx=" class="<?=orac('fromtable')?>">用途</a> </th>
               <th align="center"><a href="?<?=$search?>&orderby=title&orderlx=" class="<?=orac('title')?>">来源说明</a> </th>
              <th align="center"><a href="?<?=$search?>&orderby=integral&orderlx=" class="<?=orac('integral')?>">加积分</a></th>
              <th align="center"><a href="?<?=$search?>&orderby=remain&orderlx=" class="<?=orac('remain')?>">账户</a></th>
              <th align="center"><a href="?<?=$search?>&orderby=operator&orderlx=" class="<?=orac('operator')?>">操作员ID</a></th>
              <th align="center"><a href="?<?=$search?>&orderby=addtime&orderlx=" class="<?=orac('addtime')?>">加积分时间</a></th>
            
            </tr>
          </thead>
          <tbody>
<?php
while($rs=$sql->fetch_array())
{
?>
            <tr class="odd gradeX">
              
              <td align="center"><?=cadd($rs['username'])?><br>
                <font class="gray2">
                <?=$rs['userid']?>
               
                </font>
                
                </td>
            
              <td align="center"><?=integral_cz($rs['type'])?>
              </td>
              
              <td align="center">
			  <?=$rs['fromtable']?fromtableName($rs['fromtable']).'ID:'.$rs['fromid'].'':''?> </td>
              
              <td align="center">
              <a <?=fromtableUrl($rs['fromtable'],$rs['fromid'])?>  class="popovers" data-trigger="hover" data-placement="top"  data-content="<?=cadd($rs['title'])?><?=$rs['content']?'：'.TextareaToCo($rs['content']):''?>">
			  <?=$rs['title']?leng($rs['title'],20,'...'):'说明'?>
              </a>
              </td>
              <td align="center"><?=$rs['integral'].'分'?></td>
              <td align="center"><?=spr($rs['remain']).'分'?></td>
              <td align="center"><?=cadd($rs['operator'])?></td>
              <td align="center"><?=DateYmd($rs['addtime'],1)?></td>
              
            </tr>
<?php
$integral+=$rs['integral'];
}
?>
            <tr class="odd gradeX">
              <td align="center"><strong>本页总计</strong></td>
              <td align="center"></td>
              <td align="center"></td>
              <td align="center"></td>
              <td align="center"><strong><?=spr($integral).'分'?></strong></td>
              <td align="center"></td>
              <td align="center"></td>
              <td align="center"></td>
            </tr>
            <thead>
            <tr class="odd gradeX">
              <th align="center"><strong>全部总计</strong></th>
              <th align="center"></th>
              <th align="center"></th>
              <th align="center"></th>
              <th align="center"><strong>
				<?php 
					$fe=FeData('integral_czbak',"count(*) as total,sum(`integral`) as integral","{$where}");
					if(spr($fe['integral'])){echo spr($fe['integral']).'分';}
				?>
              </strong></th>
              <th align="center"></th>
              <th align="center"></th>
              <th align="center"></th>
            </tr>
            </thead>
          </tbody>
        </table>				
			
            
<!--底部操作按钮固定--> 

<style>body{margin-bottom:50px !important;}</style><!--后台不用隐藏,增高底部高度-->
<div align="right" class="fixed_btn" id="Autohidden">


<?php if(permissions('member_ot','','manage',1)){?>
      <input type="hidden" name="where" value="<?=$where?>">
      <input type="hidden" name="table" value="integral_czbak">

      <!--btn-info--><button type="submit" class="btn btn-grey tooltips" data-container="body" data-placement="top" data-original-title="全部分页都导出"
      onClick="
      document.XingAoForm.action='excel_export_bak.php';
      document.XingAoForm.lx.value='export';
      document.XingAoForm.target='_blank';
      "><i class="icon-signin"></i> 导出记录 </button>
    <?php }?>
    
<?php if(!$off_delbak){?>
		<input type="text" class="form-control input-xsmall tooltips" data-container="body" data-placement="top" data-original-title="删除X月之前 (填0则删除全部)"  name="date">月
		
		<!--btn-danger--><button type="submit" class="btn btn-grey" onClick="
		document.XingAoForm.lx.value='del';
		return confirm('注意：此记录用于数据统计，删除后将影响统计结果！\r此操作不可恢复！确认要删除吗？ ');
		"><i class="icon-signin"></i> 删除记录 </button>
<?php }?>
    
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
