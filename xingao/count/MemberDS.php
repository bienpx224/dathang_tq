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
$pervar='count_hy_hx';require_once($_SERVER['DOCUMENT_ROOT'].'/xingao/incluce/session.php');
$headtitle="财务统计-具体";
require_once($_SERVER['DOCUMENT_ROOT'].'/xingao/incluce/head.php');

//搜索
$where="1=1";
$where_count="1=1";
$so=(int)$_GET['so'];
if($so)
{
	$key=par($_GET['key']);
	$stime=par($_GET['stime']);
	$etime=par($_GET['etime']);
	$type=par($_GET['type']);
	
	//会员表查询条件
	if($key){$where_user=" and (userid='".CheckNumber($key,-0.1)."' or username='{$key}')";}
	
	//数据表查询条件
	if(!$type){exit ("<script>alert('请选择统计类型！');goBack();</script>");}
	
	//处理查询类型数据
	$table='money_kfbak';	$count=',sum(fromMoney) as sumdata,fromCurrency';
	
	if(stristr($type,'kf_type_')){//消费通用
			$where_count.=' and type='.findNum($type);
	}elseif(stristr($type,'cz_type_')){//储蓄通用
			$table='money_czbak';
			$where_count.=' and type='.findNum($type);
	}else{
		switch($type)
		{
			//消费特殊
			case 'kf':
				$where_count.='';
			break;
			case 'kf_yundan':
				$where_count.=' and (type>=21 and type<=29 or type in (51))';
			break;
			case 'kf_qujian':
				$where_count.=' and type in (2,53)';
			break;
			case 'kf_baoguo':
				$where_count.=' and ((type>=30 and type<=50) or type in (52,54,55))';
			break;
			case 'kf_other':
				$where_count.=' and type in (60,100)';
			break;
			
			
			//储蓄特殊
			case 'cz':
				$table='money_czbak';
				$where_count.='';
			break;
			case 'cz_api':
				$table='money_czbak';
				$where_count.=' and type>=1 and type<=20';
			break;
			case 'cz_baoguo':
				$table='money_czbak';
				$where_count.=' and type>=30 and type<=50';
			break;
			case 'cz_manage':
				$table='money_czbak';
				$where_count.=' and type>=51 and type<=60';
			break;
		}
	}
	
	
	
	if($stime){$where_time1=" and addtime>='".strtotime($stime.' 00:00:00')."'";$where_count.=$where_time1;}
	if($etime){$where_time2=" and addtime<='".strtotime($etime.' 23:59:59')."'";$where_count.=$where_time2;}
	if($table=='money_czbak'||$table=='money_kfbak'){$where.=$where_time1.$where_time2;}
}

$search.="&so={$so}&key={$key}&type={$type}&stime={$stime}&etime={$etime}";
$query="select *,sum(fromMoney) from {$table} where {$where} {$where_user} group by userid order by sum(fromMoney) desc";
//分页处理
//$line=1;$page_line=15;//不设置则默认
if($so)
{
	include($_SERVER['DOCUMENT_ROOT'].'/public/page.php');
}?>

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
              <div class="col-md-0 has-error">
                <select  class="form-control input-large select2me" data-placeholder="统计类型" name="type" required >
                  <option></option>
                  <!------------------------------------------扣费记录表------------------------------------------->
                  <option <?php $val='kf';if($type==$val){echo 'selected';}?> value="<?=$val?>">【消费:全部】</option>
                  <option value="" disabled> </option>
                  <option <?php $val='kf_yundan';if($type==$val){echo 'selected';}?> value="<?=$val?>">消费:运单-全部</option>
                  <option <?php $val='kf_type_21';if($type==$val){echo 'selected';}?> value="<?=$val?>">消费:运单费用</option>
                  <option <?php $val='kf_type_22';if($type==$val){echo 'selected';}?> value="<?=$val?>">消费:运单税费</option>
                  <option <?php $val='kf_type_23';if($type==$val){echo 'selected';}?> value="<?=$val?>">消费:运单直播代购费</option>
                  
                  <option value="" disabled> </option>
                  <option <?php $val='kf_type_1';if($type==$val){echo 'selected';}?> value="<?=$val?>">消费:商城</option>
                  <option <?php $val='kf_type_3';if($type==$val){echo 'selected';}?> value="<?=$val?>">消费:代购</option>
                  <option <?php $val='kf_qujian';if($type==$val){echo 'selected';}?> value="<?=$val?>">消费:取件</option>
                  <option <?php $val='kf_type_10';if($type==$val){echo 'selected';}?> value="<?=$val?>">消费:转账扣除</option>
                 
                  <!--包裹具体操作扣费类型-开始-->
                  <option value="" disabled> </option>
                  <option <?php $val='kf_baoguo';if($type==$val){echo 'selected';}?> value="<?=$val?>">消费:包裹-全部</option>
                  <?php 
				  for($i=30; $i<=50; $i++){
					  $name=op_money_type($i);
					  if($name){
				  ?>
                  <option <?php $val='kf_type_'.$i;if($type==$val){echo 'selected';}?> value="<?=$val?>">消费:<?=$name?></option>
                  <?php }
				  }?>
                  <!--包裹具体操作扣费类型-结束-->
                  
                  
                  
                 <option value="" disabled> </option>
                 <option <?php $val='kf_other';if($type==$val){echo 'selected';}?> value="<?=$val?>">消费:其他</option>
                  
                  
                  
                  
                  
                  
                  
                  
                  
                  
                  
                  
                  <!----------------------------------------充值记录表--------------------------------------------->
                  <option value="" disabled> </option>
                  <option value="" disabled> </option>
                  <option value="" disabled>=====================================================</option>
                  <option value="" disabled> </option>
                  <option value="" disabled> </option>
                  <option <?php $val='cz';if($type==$val){echo 'selected';}?> value="<?=$val?>">【储蓄:全部】</option>
                  <option value="" disabled> </option>
                  <!--接口充值类型-开始-->
                  <option <?php $val='cz_api';if($type==$val){echo 'selected';}?> value="<?=$val?>">储蓄:会员充值-全部</option>
                 <?php 
				  for($i=1; $i<=20; $i++){
					  $name=money_cz($i);
					  if($name){
				  ?>
                  <option <?php $val='cz_type_'.$i;if($type==$val){echo 'selected';}?> value="<?=$val?>">储蓄:会员充值-<?=$name?></option>
                  <?php }
				  }?>
                  <!--接口充值类型-结束-->
                  
                  <!--包裹具体操作充值类型-开始-->
                  <option value="" disabled> </option>
                  <option <?php $val='cz_baoguo';if($type==$val){echo 'selected';}?> value="<?=$val?>">储蓄:包裹-全部</option>
                  <?php 
				  for($i=30; $i<=50; $i++){
					  $name=op_money_type($i);
					  if($name){
				  ?>
                  <option <?php $val='cz_type_'.$i;if($type==$val){echo 'selected';}?> value="<?=$val?>">储蓄:<?=$name?></option>
                  <?php }
				  }?>
                  <!--包裹具体操作充值类型-结束-->
                  
                  <!--后台充值类型-开始-->
                  <option value="" disabled> </option>
                  <option <?php $val='cz_manage';if($type==$val){echo 'selected';}?> value="<?=$val?>">储蓄:后台-全部</option>
                 <?php 
				  for($i=51; $i<=60; $i++){
					  $name=money_kf($i);
					  if($name){
				  ?>
                  <option <?php $val='cz_type_'.$i;if($type==$val){echo 'selected';}?> value="<?=$val?>">储蓄:<?=$name?></option>
                  <?php }
				  }?>
                  <!--后台充值类型-结束-->
                  
                  <option value="" disabled> </option>
                  <option <?php $val='cz_type_100';if($type==$val){echo 'selected';}?> value="<?=$val?>">储蓄:其他</option>
                </select>
              </div>
            </div>
            
            <div class="form-group">
              <div class="col-md-0">
                <div class="input-group input-xmedium date-picker input-daterange" data-date-format="yyyy-mm-dd">
                  <input type="text" class="form-control input-small" name="stime" value="<?=$stime?>" placeholder="添加时间">
                  <span class="input-group-addon">-</span>
                  <input type="text" class="form-control input-small" name="etime" value="<?=$etime?>" placeholder="添加时间">
                </div>
              </div>
            </div>
            
            <div class="form-group">
              <input type="text" class="form-control" name="key" placeholder="会员ID/会员名 (可留空)"  value="<?=$key?>">
            </div>
            
            <button type="submit" class="btn btn-info">统计</button>
          </form>
        </div>
      </div>
      
<?php if($so){?>      
      <form action="save.php" method="post" name="XingAoForm">
        <input name="lx" type="hidden">
        <table class="table table-striped table-bordered table-hover" >
          <thead>
            <tr>
              <th align="center">会员/ID</th>
              <th align="center">统计</th>
              <th align="center">记录数</th>
              <th align="center">账户余额</th>
              <th align="center">账户积分</th>
           </tr>
          </thead>
          <tbody>
<?php 
while($rs=$sql->fetch_array())
{
?>
		<tr class="odd gradeX">
			<td align="center">
				<a href="../member/form.php?lx=edit&userid=<?=$rs['userid']?>" target="_blank" title="ID:<?=cadd($rs['userid'])?>&#13;姓名:<?=cadd($rs['truename'])?>&#13;分组:<?=$member_per[$rs['groupid']]['groupname']?>&#13;状态:<?=$rs['checked']?'开通':'关闭';?>&#13;注册:<?=DateYmd($rs['addtime'],1)?>">
                <?=cadd($rs['username'])?>
			    <font class="gray2"><?=$rs['userid']?></font>			
				</a>			  
			</td>
			
			<!--金额-->
			<td align="center" class="red2">
<?php 
$total=0;
$arr=ToArr($openCurrency,',');
if($arr)
{
	foreach($arr as $arrkey=>$value)
	{
		$fe=FeData($table,"count(*) as total {$count}","{$where_count} and userid='{$rs['userid']}' and fromCurrency='{$value}' ");
		if(spr($fe['sumdata'])){$sumdata[$value]=spr($fe['sumdata']); echo $sumdata[$value].$value.'<br>';}
		if(spr($fe['total'])){$total+=spr($fe['total']);}
	}
}
?>
			</td>
            
			<!--记录数-->
			<td align="center" class="red2">
				<?=$total?>条
			</td>
            
			<!--账户余额-->
			<td align="center">
            <?php 
				$integral=0;
				$fe=FeData('member',"count(*) as total,sum(`money`) as money,sum(`money_lock`) as money_lock,sum(`integral`) as integral,currency","userid='{$rs[userid]}'");
				
				if(spr($fe['money'])||spr($fe['money_lock']))
				{
					$money[$fe['currency']]=spr($fe['money']);	
					$money_lock[$fe['currency']]=spr($fe['money_lock']);
					echo $money[$fe['currency']].$fe['currency'];
					if(spr($money_lock[$fe['currency']])){echo '(冻结 '.spr($money_lock[$fe['currency']]).$fe['currency'].')';}
					echo '<br>';
				}
				if(spr($fe['integral'])){$integral+=spr($fe['integral']);}
            ?>

			</td>
            
			<!--账户积分-->
			<td align="center">
            <?=$integral?>分
			</td>
          
			
		</tr>
<?php 
	//所有币种	
	$arr=ToArr($openCurrency,',');
	if($arr)
	{
		foreach($arr as $arrkey=>$value)
		{
			if($sumdata[$value]){$total_sumdata[$value]+=$sumdata[$value];}
			if($money[$value]){$total_money[$value]+=$money[$value];}
			if($money_lock[$value]){$total_money_lock[$value]+=$money_lock[$value];}
		}
	}

	unset($sumdata);unset($money);unset($money_lock);//必须:清空之前数据
	
	$total_number+=$total;
	$total_integral+=$integral;
}?>
            <tr>
              <th align="center">本页总计</th>
              <th align="center" class="red2">
				<?php 
                //所有币种	
                $arr=ToArr($openCurrency,',');
                if($arr)
                {
                    foreach($arr as $arrkey=>$value)
                    {
                        if($total_sumdata[$value]){echo $total_sumdata[$value].$value.'<br>';}
                    }
                }
                ?>
              </th>
              <th align="center" class="red2"><?php if(spr($total_number)){echo spr($total_number).'条';}?></th>
              <th align="center">
				<?php 
                //所有币种	
                $arr=ToArr($openCurrency,',');
                if($arr)
                {
                    foreach($arr as $arrkey=>$value)
                    {
                        if($total_money[$value]||$total_money_lock[$value])
						{
							echo $total_money[$value].$value;
							if(spr($total_money_lock[$value])){echo '(冻结 '.spr($total_money_lock[$value]).$value.')';}
							echo '<br>';
						}
                    }
                }
                ?>
              </th>
              <th align="center"><?php if(spr($total_integral)){echo spr($total_integral);}?>分</th>
           </tr>
          </tbody>
		  
          <thead>
            <tr>
              <th align="center">全部总计</th>
              <!--金额/积分-->
              <th align="center" class="red2">
				<?php 
				$total=0;
                //所有币种	
                $arr=ToArr($openCurrency,',');
                if($arr)
                {
                    foreach($arr as $arrkey=>$value)
                    {
						$fe=FeData($table,"count(*) as total {$count}","{$where_count} and fromCurrency='{$value}' {$where_user}");
                        if(spr($fe['sumdata'])){echo spr($fe['sumdata']).$value.'<br>';}
						if(spr($fe['total'])){$total+=spr($fe['total']);}		
                    }
                }
                ?>
			  </th>
            
			<!--记录数-->
			<th align="center" class="red2"><?=$total?>条</td>
            
			<!--账户余额-->
			<th align="center">
				<?php 
                //所有币种	
				$integral=0;
                $arr=ToArr($openCurrency,',');
                if($arr)
                {
                    foreach($arr as $arrkey=>$value)
                    {
                        $fe=FeData('member',"count(*) as total,sum(`money`) as money,sum(`money_lock`) as money_lock,sum(`integral`) as integral","currency='{$value}' {$where_user}");
						if(spr($fe['money'])||spr($fe['money_lock']))
						{
							echo spr($fe['money']).$value;
							if(spr($fe['money_lock'])){echo '(冻结 '.spr($fe['money_lock']).$value.')';}
							echo '<br>';
						}
						if(spr($fe['integral'])){$integral+=spr($fe['integral']);}
						
                    }
                }
                ?>
			</th>
            
			<!--账户积分-->
			<th align="center">
            <?=$integral?>分
			</th>
          
           </tr>
          </thead>
          
        </table>				
			
            
<!--底部操作按钮固定--> 

<style>body{margin-bottom:50px !important;}</style><!--后台不用隐藏,增高底部高度-->
<div align="right" class="fixed_btn" id="Autohidden">


<input type="hidden" name="table" value="<?=$table?>">
      <input type="hidden" name="count" value="<?=$count?>">
      <input type="hidden" name="where" value="<?=$where?>">
      <input type="hidden" name="where_count" value="<?=$where_count?>">
      <input type="hidden" name="ex_tem" value="MemberDS">
      <!--btn-info--><button type="submit" class="btn btn-grey tooltips" data-container="body" data-placement="top" data-original-title="导出搜索结果"
      onClick="
      document.XingAoForm.action='excelExport/';
      document.XingAoForm.target='_blank';
      document.XingAoForm.lx.value='export';
      "><i class="icon-signin"></i> 导出记录 </button>

</div>


        
        <div class="row">
          <?=$listpage?>
        </div>
      </form>
<?php }//if($so){?>	  
	<div class="xats">
		<strong>提示:</strong><br />
		&raquo; 以上是以记录表来统计,如果记录表有过删除,将影响统计结果<br />
		&raquo; 列表里只显示有数据的会员,总计里则不限<br />
	</div>

    </div>
    <!--表格内容结束--> 
    
  </div>
</div>
<?php
require_once($_SERVER['DOCUMENT_ROOT'].'/xingao/incluce/foot.php');
?>
