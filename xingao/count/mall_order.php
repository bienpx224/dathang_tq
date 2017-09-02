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
$pervar='count_ot';require_once($_SERVER['DOCUMENT_ROOT'].'/xingao/incluce/session.php');
$headtitle="订单统计";
require_once($_SERVER['DOCUMENT_ROOT'].'/xingao/incluce/head.php');

//搜索
$where="1=1";
$so=(int)$_GET['so'];
if($so)
{
	$stime=par($_GET['stime']);
	$etime=par($_GET['etime']);
	if($stime){$where.=" and addtime>='".strtotime($stime.' 00:00:00')."'";}
	if($etime){$where.=" and addtime<='".strtotime($etime.' 23:59:59')."'";}
	$search.="&so={$so}&stime={$stime}&etime={$etime}";
}

$timequerycall=1;
$field="addtime";
include($_SERVER['DOCUMENT_ROOT'].'/public/timequery.php');//按时间快速查询
?>

<div class="page_ny"> 
  <!-- BEGIN PAGE HEADER-->
	<div class="row"><!--单页时去掉 class="row",不然会有横滚动条,并且form 加 style="margin:20px;"-->
		<div class="col-md-12"> 
<!-- BEGIN PAGE TITLE & BREADCRUMB-->
      <h3 class="page-title"> <a href="javascript:void(0)" onClick="javascript:window.location.href=window.location.href;" title="<?=$LG['refresh']?>" class="gray">
        <?=$headtitle?>
        </a> 
		</h3>
      
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
                    <input type="text" class="form-control input-small" name="stime" value="<?=$stime?>" placeholder="下单时间">
                    <span class="input-group-addon">-</span>
                    <input type="text" class="form-control input-small" name="etime" value="<?=$etime?>" placeholder="下单时间">
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
              <th align="center">时间</th>
              <th align="center">待生成包裹</th>
              <th align="center">已生成包裹</th>
              <th align="center">总共</th>
           </tr>
          </thead>
          <tbody>
<?php 
//默认显示今年所有月份
$views_1='';
$views_2='';
$month='';
if($where=='1=1')
{
	$total_show=1;
	$views_2_name='金额 ['.$XAmc.']'; 
	$charts_timename='月';
	$month=spr(date('m'));
	while($month>0) 
	{
		$where='1=1';
		$where.=" and addtime>='".strtotime(date('Y').'-'.$month)."'";
		$where.=" and addtime<'".strtotime(date('Y').'-'.($month+1))."'";
		require($_SERVER['DOCUMENT_ROOT'].'/xingao/count/call/mall_order.php');
		
		//一列总计
		$total_num_0+=$num_0;  $total_payment_0+=$payment_0;
		$total_num_1+=$num_1;  $total_payment_1+=$payment_1;
		
		//一行总计
		$total_num_all+=$total_num;  $total_payment_all+=$total_payment;
		
		//图表数据(月份顺序从小至大)
		$views_1='['.$month.','.$total_num.'],'.$views_1;			$total_num=0;
		$views_2='['.$month.','.$total_payment.'],'.$views_2;		$total_payment=0;
		
		$month-=1;
	}
}else{
	require($_SERVER['DOCUMENT_ROOT'].'/xingao/count/call/mall_order.php');
}
?>
          </tbody>
		  
		<?php if($total_show){?>		  
          <thead>
            <tr>
              <th align="center">总计</th>
              <th align="center">
			  <?php 
				if($total_num_0){
					echo $total_num_0.'单';
					echo '<span class="xa_sep"> / </span>';
					echo $total_payment_0.$XAmc;
				}
			  ?>
			 </th>
              <th align="center">
			  <?php 
				if($total_num_1){
					echo $total_num_1.'单';
					echo '<span class="xa_sep"> / </span>';
					echo $total_payment_1.$XAmc;
				}
			  ?>
			  </th>
              

              <th align="center">
			  <?php 
				if($total_num_all){
					echo $total_num_all.'单';
					echo '<span class="xa_sep"> / </span>';
					echo $total_payment_all.$XAmc;
				}
			  ?>
			 </th>
            </tr>
          </thead>
		<?php }?>   
        </table>
     
      </form>
    </div>
    <!--表格内容结束--> 
  </div>
  	
	<?php if($total_show){?>		
	<!--图表显示-->
	<div class="portlet">
		<div class="portlet-title">
			<div class="caption"><i class="icon-reorder"></i>今年</div>
			<div class="tools"> <a href="javascript:;" class="collapse"></a></div>
		</div>
		<div class="portlet-body">
			<div id="chart_2" class="chart"></div>
		</div>
	</div>
	<?php }?> 	
	
	<div class="xats">
		<strong>提示:</strong><br />
		&raquo; 以订单表来统计,如果订单有过删除,将影响统计结果<br />
	</div>
	
</div>


<?php
require_once($_SERVER['DOCUMENT_ROOT'].'/xingao/incluce/foot.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/xingao/count/call/charts.php');
?>
