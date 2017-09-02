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
$pervar='count_dg';require_once($_SERVER['DOCUMENT_ROOT'].'/xingao/incluce/session.php');
$headtitle="代购统计 (数据导出请在 代购导出 功能里操作)";
require_once($_SERVER['DOCUMENT_ROOT'].'/xingao/incluce/head.php');

$showTyp=par($_GET['showTyp']);
if(!CheckEmpty($showTyp)){$showTyp=1;}//默认月计

//搜索
$where="1=1";
$so=(int)$_GET['so'];
if($so)
{
	$stime=par($_GET['stime']);
	$etime=par($_GET['etime']);
	$pr_stime=par($_GET['pr_stime']);
	$pr_etime=par($_GET['pr_etime']);
	
	if($stime){$where.=" and addtime>='".strtotime($stime.' 00:00:00')."'";}
	if($etime){$where.=" and addtime<='".strtotime($etime.' 23:59:59')."'";}
	if($pr_stime){$where.=" and procurementTime>='".strtotime($pr_stime.' 00:00:00')."'";}
	if($pr_etime){$where.=" and procurementTime<='".strtotime($pr_etime.' 23:59:59')."'";}
	$search.="&so={$so}&stime={$stime}&etime={$etime}&pr_stime={$pr_stime}&pr_etime={$pr_etime}&showTyp={$showTyp}";
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
           
            <div class="form-group">
                <div class="col-md-0">
                  <div class="input-group input-xmedium date-picker input-daterange" data-date-format="yyyy-mm-dd">
                    <input type="text" class="form-control input-small" name="pr_stime" value="<?=$pr_stime?>" placeholder="采购时间">
                    <span class="input-group-addon">-</span>
                    <input type="text" class="form-control input-small" name="pr_etime" value="<?=$pr_etime?>" placeholder="采购时间">
                  </div>
                 </div>
           </div>
           
             <div class="form-group">
                <div class="col-md-0">
                     <select  class="form-control input-small select2me" name="showTyp" data-placeholder="显示" >
                     <option></option>
                       <?=showTyp($showTyp,1)?>
                     </select>
               </div>
              </div>
            
            <button type="submit" class="btn btn-info">统计</button>
          </form>
        </div>
        
        <?php
		$timequeryName='下单时间:';
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
			<?php if($showTyp==0){?>	
              <th align="center">会员</th>
              <th align="center">下单时间</th>
              <th align="center">采购时间</th>
			<?php }elseif($showTyp==1){?>
              <th align="center">下单时间</th>
			<?php }?>
              <th align="center">代购价格</th>
              <th align="center">采购成本</th>
              <th align="center">利润</th>
              <th align="center">扣费</th>
              <th align="center">单数</th>
              
           </tr>
          </thead>
          <tbody>
<?php 
//显示详细----------------------------------------------------------
if($showTyp==0)
{
	$callFile='daigou_detailed.php';
}

//月计方式----------------------------------------------------------
elseif($showTyp==1)
{
	$callFile='daigou_monthly.php';
}




//默认显示今年所有月份
$views_1='';
$views_2='';
$month='';
if($where=='1=1')
{
	$total_show=1;
	$views_2_name='利润 ['.$XAmc.']'; 
	$charts_timename='月';
	$month=spr(date('m'));
	while($month>0) 
	{
		$where='1=1';
		$where.=" and addtime>='".strtotime(date('Y').'-'.$month)."'";
		$where.=" and addtime<'".strtotime(date('Y').'-'.($month+1))."'";
		require($_SERVER['DOCUMENT_ROOT'].'/xingao/count/call/'.$callFile);
		
		//图表数据(月份顺序从小至大)
		$views_1='['.$month.','.$total_num.'],'.$views_1;	$total_num=0;
		$views_2='['.$month.','.$money_MC.'],'.$views_2;	$money_MC=0;
		
		$month-=1;
	}
}else{
	require($_SERVER['DOCUMENT_ROOT'].'/xingao/count/call/'.$callFile);
}
?>
          </tbody>
		  
		<?php if($total_show){?>		  
          <thead>
            <tr>
			<?php if($showTyp==0){?>	
              <th align="center">本页总计</th>
              <th align="center"></th>
              <th align="center"></th>
			<?php }elseif($showTyp==1){?>
              <th align="center">本页总计</th>
			<?php }?>

              <th align="center">
			  <?php 
				//所有币种	
				$arr=ToArr($openCurrency,',');
				if($arr)
				{
					foreach($arr as $arrkey=>$value)
					{
						if(spr($total_money_0[$value])){echo spr($total_money_0[$value]).$value.'<br>';}
					}
				}
			  ?>
			  </th>
             
              <th align="center">
			  <?php 
				//所有币种	
				$arr=ToArr($openCurrency,',');
				if($arr)
				{
					foreach($arr as $arrkey=>$value)
					{
						if(spr($total_money_1[$value])){echo spr($total_money_1[$value]).$value.'<br>';}
					}
				}
			  ?>
			  </th>
              <th align="center">
			  <?php 
				//所有币种	
				$arr=ToArr($openCurrency,',');
				if($arr)
				{
					foreach($arr as $arrkey=>$value)
					{
						if($total_money_2[$value]<0){echo '<font class="red">';}else{echo '<font>';}
						if(spr($total_money_2[$value])){echo spr($total_money_2[$value]).$value.'<br>';}
						echo '</font>';
					}
				}
			  ?>
			  </th>
              <th align="center">
			  <?php 
				//所有币种	
				$arr=ToArr($openCurrency,',');
				if($arr)
				{
					foreach($arr as $arrkey=>$value)
					{
						if(spr($total_money_3[$value])){echo spr($total_money_3[$value]).$value.'<br>';}
					}
				}
			  ?>
			  </th>
              <th align="center">
			  <?php if($total_num){echo $total_num;}?>单
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
		&raquo; 以代购表来统计,如果代购有过删除,将影响统计结果<br />
	</div>
	
</div>


<?php
require_once($_SERVER['DOCUMENT_ROOT'].'/xingao/incluce/foot.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/xingao/count/call/charts.php');
?>
