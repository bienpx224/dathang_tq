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
$pervar='count_hy_sl';require_once($_SERVER['DOCUMENT_ROOT'].'/xingao/incluce/session.php');
$headtitle="会员数量统计";
require_once($_SERVER['DOCUMENT_ROOT'].'/xingao/incluce/head.php');

//搜索
$where="1=1";
$so=(int)$_GET['so'];
if($so)
{
	$stime=par($_GET['stime']);
	$etime=par($_GET['etime']);
	$status=par($_GET['status']);
	if($stime){$where.=" and addtime>='".strtotime($stime.' 00:00:00')."'";}
	if($etime){$where.=" and addtime<='".strtotime($etime.' 23:59:59')."'";}

	$search.="&so={$so}&stime={$stime}&etime={$etime}";
}

$timequerycall=1;
$field="addtime";
include($_SERVER['DOCUMENT_ROOT'].'/public/timequery.php');//按时间快速查询


$order="  order by myorder desc, groupname{$LT} desc,groupid desc";//默认排序
include($_SERVER['DOCUMENT_ROOT'].'/public/orderby.php');//排序处理页

//echo $search;exit();
$query="select * from member_group {$order}";// where {$where} 

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
  <div class="portlet tabbable">
    <div class="portlet-body" style="padding:10px;"> 
      <!--统计-->
      <div class="navbar navbar-default" role="navigation">
        
        <div class="collapse navbar-collapse navbar-ex1-collapse">
          <form class="navbar-form navbar-left"  method="get" action="?">
            <input name="so" type="hidden" value="1">
            <div class="form-group">
                <div class="col-md-0">
                  <div class="input-group input-xmedium date-picker input-daterange" data-date-format="yyyy-mm-dd">
                    <input type="text" class="form-control input-small" name="stime" value="<?=$stime?>" placeholder="注册时间">
                    <span class="input-group-addon">-</span>
                    <input type="text" class="form-control input-small" name="etime" value="<?=$etime?>" placeholder="注册时间">
                  </div>
                 </div>
              </div>
            
            <button type="submit" class="btn btn-info">统计</button>
          </form>
        </div>
        
        <?php
		$timequeryshow=1;
		$searchtime="&so={$so}&key={$key}";
		include($_SERVER['DOCUMENT_ROOT'].'/public/timequery.php');//按时间快速查询
		?>
      </div>
      <form action="save.php" method="post" name="XingAoForm">
        <input name="lx" type="hidden">
        <table class="table table-striped table-bordered table-hover" >
          <thead>
            <tr>
              <th align="center">会员组</th>
              <th align="center">开通数量</th>
              <th align="center">关闭数量</th>
              <th align="center">总共数量</th>
            </tr>
          </thead>
          <tbody>
<?php
while($rs=$sql->fetch_array())
{
?>
		<tr class="odd gradeX">
			<td align="center"><?=cadd($rs['groupname'.$LT])?></td>
			<td align="center">
				<a href="../member/list.php?so=1&groupid=<?=$rs['groupid']?>&checked=1" target="_blank" title="查看所有用户">
				<?php $num_1=mysqli_num_rows($xingao->query("select groupid from member where {$where} and groupid='{$rs[groupid]}' and checked=1"));if($num_1){echo $num_1.'个';}?>
				</a>			  
			</td>
			<td align="center">
				<a href="../member/list.php?so=1&groupid=<?=$rs['groupid']?>&checked=0" target="_blank" title="查看所有用户">
				<?php $num_0=mysqli_num_rows($xingao->query("select groupid from member where {$where} and   groupid='{$rs[groupid]}' and checked=0"));if($num_0){echo $num_0.'个';}?>
				</a>			  
			</td>
			<td align="center">
				<a href="../member/list.php?so=1&groupid=<?=$rs['groupid']?>" target="_blank" title="查看所有用户">
				<?php $num_t=mysqli_num_rows($xingao->query("select groupid from member where {$where} and   groupid='{$rs[groupid]}'"));if($num_t){echo $num_t.'个';}?>
				</a>			  
			</td>
		</tr>
<?php 
	$total_1+=$num_1;
	$total_0+=$num_0;
	$total_t+=$num_t;
}?>
          </tbody>
		  
          <thead>
            <tr>
              <th align="center">本页总计</th>
              <th align="center"><?php if($total_1){echo $total_1.'个';}?></th>
              <th align="center"><?php if($total_0){echo $total_0.'个';}?></th>
              <th align="center"><?php if($total_t){echo $total_t.'个';}?></th>
            </tr>
          </thead>
        </table>
        
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
