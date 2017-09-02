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
$headtitle="财务统计-概况";
require_once($_SERVER['DOCUMENT_ROOT'].'/xingao/incluce/head.php');

//搜索
$where_type=" 1=1";
$where=" and 1=1";
$so=(int)$_GET['so'];

$table=par($_GET['table']);if(!$table){$table='member';}
$type=par($_GET['type']);
$orderby=par($_GET['orderby']);

if($table=='money_czbak')
{
	switch($type)
	{
		case 'member':
			$where_type.=" and type<=20";
		break;
		case 'baoguo':
			$where_type.=" and type>=30 and type<=50";
		break;
		case 'manage':
			$where_type.=" and type>=51 and type<=60";
		break;
		case 'other':
			$where_type.=" and type=100";
		break;
	}
}elseif($table=='money_kfbak'){
	switch($type)
	{
		case 'yundan':
			$where_type.=" and type in (21,22,51)";
		break;
		case 'baoguo':
			$where_type.=" and ((type>=30 and type<=50) or type in (52,54,55))";
		break;
		case 'manage':
			$where_type.=" and type>=51 and type<=60";
		break;
		case 'qujian':
			$where_type.=" and type in (2,53)";
		break;
		case 'other':
			$where_type.=" and type=100";
		break;
		default:
			$where_type.=" and type={$type}";
	}
}


if($so)
{
	$key=par($_GET['key']);
	$stime=par($_GET['stime']);
	$etime=par($_GET['etime']);
	
	if($key){$where.=" and (userid='".CheckNumber($key,-0.1)."' or username='{$key}')";}
	if($stime){$where.=" and addtime>='".strtotime($stime.' 00:00:00')."'";}
	if($etime){$where.=" and addtime<='".strtotime($etime.' 23:59:59')."'";}
}
$search.="&so={$so}&key={$key}&table={$table}&type={$type}&stime={$stime}&etime={$etime}";

$timequerycall=1;
$field='addtime';
include($_SERVER['DOCUMENT_ROOT'].'/public/timequery.php');//按时间快速查询

if($table=='member')
{
	$order=' order by userid desc';//默认排序
	include($_SERVER['DOCUMENT_ROOT'].'/public/orderby.php');//排序处理页
	$query="select * from {$table} where {$where_type} {$where} {$order}";
}else{
	$query="select *,sum(toMoney) from {$table} where {$where_type} {$where}  group by userid order by sum(toMoney) desc";
}


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
            <input name="table" type="hidden" value="<?=$table?>">
            <input name="type" type="hidden" value="<?=$type?>">
            <input name="orderby" type="hidden" value="<?=$orderby?>">
      		<div class="form-group">
              <input type="text" name="key" class="form-control input-msmall popovers" data-trigger="hover" data-placement="right"  data-content="会员ID/会员名 (可留空)" value="<?=$key?>">
            </div>
           <div class="form-group">
                <div class="col-md-0">
                  <div class="input-group input-xmedium date-picker input-daterange" data-date-format="yyyy-mm-dd">
                    <input type="text" class="form-control input-small" name="stime" value="<?=$stime?>" placeholder="添加/注册时间">
                    <span class="input-group-addon">-</span>
                    <input type="text" class="form-control input-small" name="etime" value="<?=$etime?>" placeholder="添加/注册时间">
                  </div>
                 </div>
              </div>
            
            <button type="submit" class="btn btn-info">统计</button>
          </form>
        </div>
        
        <?php 
		$timequeryshow=1;$timequerycall=0;
		$searchtime="&so={$so}&key={$key}&table={$table}&type={$type}&orderby={$orderby}";
		include($_SERVER['DOCUMENT_ROOT'].'/public/timequery.php');//按时间快速查询
		?>

        <span class="help-block"><br>(先点列名再搜索时间)</span>
      </div>
      <form action="save.php" method="post" name="XingAoForm">
        <input name="lx" type="hidden">
        <table class="table table-striped table-bordered table-hover" >
          <thead>
            <tr>
              <th align="center">
			  <a href="?<?=$search?>&table=member&orderby=username&orderlx=" class="<?=orac('username')?>">会员</a>
			  /
			  <a href="?<?=$search?>&table=member&orderby=userid&orderlx=" class="<?=orac('userid')?>">ID</a></th>
			  
              <th align="center">
			  <a href="?<?=$search?>&table=money_czbak&type=member&orderby=c1&orderlx=" class="<?=orac('c1')?>">充值获得</a>
			  </th>
              <th align="center"><a href="?<?=$search?>&table=money_czbak&type=manage&orderby=c2&orderlx=" class="popovers <?=orac('c2')?>" data-trigger="hover" data-placement="top" data-content="后台充值,退费,增送,理赔等" >后台获得</a></th>
              <th align="center"><a href="?<?=$search?>&table=money_czbak&type=baoguo&orderby=c3&orderlx=" class="<?=orac('c3')?>">包裹退费</a></th>
              <th align="center"><a href="?<?=$search?>&table=money_czbak&type=other&orderby=c4&orderlx=" class="<?=orac('c4')?>">其他获得</a></th>
			  
              <th align="center"></th>
             
             
			<!---------------------------------------------------------------------------------------------------->
          
              <th align="center"><a href="?<?=$search?>&table=money_kfbak&type=1&orderby=k1&orderlx=" class="<?=orac('k1')?>">商城消费</a></th>
              <th align="center"><a href="?<?=$search?>&table=money_kfbak&type=3&orderby=k2&orderlx=" class="<?=orac('k2')?>">代购消费</a></th>
              <th align="center"><a href="?<?=$search?>&table=money_kfbak&type=qujian&orderby=k6&orderlx=" class="<?=orac('k6')?>">取件消费</a></th>
              <th align="center"><a href="?<?=$search?>&table=money_kfbak&type=baoguo&orderby=k3&orderlx=" class="<?=orac('k3')?>">包裹消费</a></th>
              <th align="center"><a href="?<?=$search?>&table=money_kfbak&type=yundan&orderby=k4&orderlx=" class="<?=orac('k4')?>">运单消费</a></th>
              <th align="center"><a href="?<?=$search?>&table=money_kfbak&type=other&orderby=k5&orderlx=" class="<?=orac('k5')?>">其他消费</a></th>
             <th align="center">余额</th>
        
			  
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
				</a>			  
			</td>
			
			<!--充值-->
			<td align="center">
				<a href="../member/money_czbak.php?so=1&key=<?=$rs['userid']?>&type=member" target="_blank">
				<?php 
                //所有币种	
                $arr=ToArr($openCurrency,',');
                if($arr)
                {
                    foreach($arr as $arrkey=>$value)
                    {
                        $fe=FeData('money_czbak',"count(*) as total,sum(`toMoney`) as toMoney","toCurrency='{$value}' and userid='{$rs[userid]}' and type>=1 and type<=20 {$where}");
                        if(spr($fe['toMoney'])){$money_cz[$value]=spr($fe['toMoney']); echo $money_cz[$value].$value.'<br>';}		
                    }
                }
                ?>
				</a>			  
			</td>
			<!--后台-->
			<td align="center">
				<a href="../member/money_czbak.php?so=1&key=<?=$rs['userid']?>&type=manage" target="_blank">
				<?php 
                //所有币种	
                $arr=ToArr($openCurrency,',');
                if($arr)
                {
                    foreach($arr as $arrkey=>$value)
                    {
                        $fe=FeData('money_czbak',"count(*) as total,sum(`toMoney`) as toMoney","toCurrency='{$value}' and userid='{$rs[userid]}' and type>=51 and type<=60 {$where}");
                        if(spr($fe['toMoney'])){$money_hcz[$value]=spr($fe['toMoney']); echo $money_hcz[$value].$value.'<br>';}		
                    }
                }
                ?>
				</a>
			</td>
			<td align="center">
            <a href="../member/money_czbak.php?so=1&key=<?=$rs['userid']?>&type=baoguo" target="_blank">
				<?php 
                //所有币种	
                $arr=ToArr($openCurrency,',');
                if($arr)
                {
                    foreach($arr as $arrkey=>$value)
                    {
                        $fe=FeData('money_czbak',"count(*) as total,sum(`toMoney`) as toMoney","toCurrency='{$value}' and userid='{$rs[userid]}' and type>=30 and type<=50 {$where}");
                        if(spr($fe['toMoney'])){$money_bgtf[$value]=spr($fe['toMoney']); echo $money_bgtf[$value].$value.'<br>';}		
                    }
                }
                ?>
			</a>
            </td>
			
			<!--其他-->
			<td align="center">
				<a href="../member/money_czbak.php?so=1&key=<?=$rs['userid']?>&type=other" target="_blank">
				<?php 
                //所有币种	
                $arr=ToArr($openCurrency,',');
                if($arr)
                {
                    foreach($arr as $arrkey=>$value)
                    {
                        $fe=FeData('money_czbak',"count(*) as total,sum(`toMoney`) as toMoney","toCurrency='{$value}' and userid='{$rs[userid]}' and type=100 {$where}");
                        if(spr($fe['toMoney'])){$money_ottf[$value]=spr($fe['toMoney']); echo $money_ottf[$value].$value.'<br>';}		
                    }
                }
                ?>
				</a>
			</td>
			<!--消费-->
			<td align="center">
					  
			</td>
			
			<!--商城消费-->
			<td align="center">
               <a href="../member/money_kfbak.php?so=1&key=<?=$rs['userid']?>&type=normal" target="_blank">
				<?php 
                //所有币种	
                $arr=ToArr($openCurrency,',');
                if($arr)
                {
                    foreach($arr as $arrkey=>$value)
                    {
                        $fe=FeData('money_kfbak',"count(*) as total,sum(`fromMoney`) as fromMoney","fromCurrency='{$value}' and userid='{$rs[userid]}' and type=1 {$where}");
                        if(spr($fe['toMoney'])){$money_sckf[$value]=spr($fe['toMoney']); echo $money_sckf[$value].$value.'<br>';}		
                    }
                }
                ?>
				</a>
			</td>
			
			<!--代购-->
			<td align="center"><a href="../member/money_kfbak.php?so=1&key=<?=$rs['userid']?>&type=normal" target="_blank">
				<?php 
                //所有币种	
                $arr=ToArr($openCurrency,',');
                if($arr)
                {
                    foreach($arr as $arrkey=>$value)
                    {
                        $fe=FeData('money_kfbak',"count(*) as total,sum(`fromMoney`) as fromMoney","fromCurrency='{$value}' and userid='{$rs[userid]}' and type=3 {$where}");
                        if(spr($fe['toMoney'])){$money_dgkf[$value]=spr($fe['toMoney']); echo $money_dgkf[$value].$value.'<br>';}		
                    }
                }
                ?>
			</a></td>
			<td align="center"><a href="../member/money_kfbak.php?so=1&key=<?=$rs['userid']?>&type=qujian" target="_blank">
				<?php 
                //所有币种	
                $arr=ToArr($openCurrency,',');
                if($arr)
                {
                    foreach($arr as $arrkey=>$value)
                    {
                        $fe=FeData('money_kfbak',"count(*) as total,sum(`fromMoney`) as fromMoney","fromCurrency='{$value}' and userid='{$rs[userid]}' and type in (2,53) {$where}");
                        if(spr($fe['toMoney'])){$money_ydkf[$value]=spr($fe['toMoney']); echo $money_ydkf[$value].$value.'<br>';}		
                    }
                }
                ?>
             </a></td>
			
			<!---------------------------------------------------------------------------------------------------->
			
            <!--积分获取-->
  			<td align="center">
				<a href="../member/money_kfbak.php?so=1&key=<?=$rs['userid']?>&type=baoguo" target="_blank">
				<?php 
                //所有币种	
                $arr=ToArr($openCurrency,',');
                if($arr)
                {
                    foreach($arr as $arrkey=>$value)
                    {
                        $fe=FeData('money_kfbak',"count(*) as total,sum(`fromMoney`) as fromMoney","fromCurrency='{$value}' and userid='{$rs[userid]}' and ((type>=30 and type<=50) or type in (52,54,55)) {$where}");
                        if(spr($fe['toMoney'])){$money_bgkf[$value]=spr($fe['toMoney']); echo $money_bgkf[$value].$value.'<br>';}		
                    }
                }
                ?>
				</a>		  
			</td>
			
            <!--积分消费-->
  			<td align="center">
  				<a href="../member/money_kfbak.php?so=1&key=<?=$rs['userid']?>&type=yundan" target="_blank">
				<?php 
                //所有币种	
                $arr=ToArr($openCurrency,',');
                if($arr)
                {
                    foreach($arr as $arrkey=>$value)
                    {
                        $fe=FeData('money_kfbak',"count(*) as total,sum(`fromMoney`) as fromMoney","fromCurrency='{$value}' and userid='{$rs[userid]}' and type in (21,22,51) {$where}");
                        if(spr($fe['toMoney'])){$money_ydkf[$value]=spr($fe['toMoney']); echo $money_ydkf[$value].$value.'<br>';}		
                    }
                }
                ?>
  				</a>		  
  				</td>
  			<!--计算-->
			<td align="center"><a href="../member/money_kfbak.php?so=1&key=<?=$rs['userid']?>" target="_blank">
				<?php 
                //所有币种	
                $arr=ToArr($openCurrency,',');
                if($arr)
                {
                    foreach($arr as $arrkey=>$value)
                    {
                        $fe=FeData('money_kfbak',"count(*) as total,sum(`fromMoney`) as fromMoney","fromCurrency='{$value}' and userid='{$rs[userid]}' and type in (2,53,60,100) {$where}");
                        if(spr($fe['toMoney'])){$money_otkf[$value]=spr($fe['toMoney']);echo $money_otkf[$value].$value.'<br>';}		
                    }
                }
                ?>
            
				</a></td>
			
			<!--余额-->
			<td align="center">
				<?php 
                //所有币种	
                $arr=ToArr($openCurrency,',');
                if($arr)
                {
                    foreach($arr as $arrkey=>$value)
                    {
                        $fe=FeData('member',"count(*) as total,sum(`money`) as money,sum(`money_lock`) as money_lock","currency='{$value}' and userid='{$rs[userid]}'");
						
						if(spr($fe['money'])||spr($fe['money_lock']))
						{
							$money[$value]=spr($fe['money']);	
							$money_lock[$value]=spr($fe['money_lock']);
							echo $money[$value].$value;
							if(spr($money_lock[$value])){echo '(冻结 '.spr($money_lock[$value]).$value.')';}
							echo '<br>';
						}
						
                    }
                }
                ?>
			  </td>
          
			
		</tr>
<?php 
	//所有币种	
	$arr=ToArr($openCurrency,',');
	if($arr)
	{
		foreach($arr as $arrkey=>$value)
		{			if($money_cz[$value]){$total_money_cz[$value]+=$money_cz[$value];}
			if($money_hcz[$value]){$total_money_hcz[$value]+=$money_hcz[$value];}
			if($money_bgtf[$value]){$total_money_bgtf[$value]+=$money_bgtf[$value];}
			if($money_ottf[$value]){$total_money_ottf[$value]+=$money_ottf[$value];}
			if($money_tx[$value]){$total_money_tx[$value]+=$money_tx[$value];}
			if($money_sckf[$value]){$total_money_sckf[$value]+=$money_sckf[$value];}
			if($money_dgkf[$value]){$total_money_dgkf[$value]+=$money_dgkf[$value];}
			if($money_bgkf[$value]){$total_money_bgkf[$value]+=$money_bgkf[$value];}
			if($money_ydkf[$value]){$total_money_ydkf[$value]+=$money_ydkf[$value];}
			if($money_qjkf[$value]){$total_money_qjkf[$value]+=$money_qjkf[$value];}
			if($money_otkf[$value]){$total_money_otkf[$value]+=$money_otkf[$value];}
			if($money[$value]){$total_money[$value]+=$money[$value];}
			if($money_lock[$value]){$total_money_lock[$value]+=$money_lock[$value];}
		}
	}
	
	unset($money_cz);unset($money_hcz);unset($money_bgtf);unset($money_ottf);unset($money_tx);unset($money_sckf);unset($money_dgkf);unset($money_bgkf);unset($money_ydkf);unset($money_qjkf);unset($money_otkf);unset($money);unset($money_lock);//必须:清空之前数据
}
?>
            <tr>
              <th align="center">本页总计</th>
              <th align="center">
				<?php 
                //所有币种	
                $arr=ToArr($openCurrency,',');
                if($arr)
                {
                    foreach($arr as $arrkey=>$value)
                    {
                        if($total_money_cz[$value]){echo $total_money_cz[$value].$value.'<br>';}
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
                        if($total_money_hcz[$value]){echo $total_money_hcz[$value].$value.'<br>';}
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
                        if($total_money_bgtf[$value]){echo $total_money_bgtf[$value].$value.'<br>';}
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
                        if($total_money_ottf[$value]){echo $total_money_ottf[$value].$value.'<br>';}
                    }
                }
                ?>
			  </th>
              <th align="center"></th>
              <th align="center">
				<?php 
                //所有币种	
                $arr=ToArr($openCurrency,',');
                if($arr)
                {
                    foreach($arr as $arrkey=>$value)
                    {
                        if($total_money_sckf[$value]){echo $total_money_sckf[$value].$value.'<br>';}
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
                        if($total_money_dgkf[$value]){echo $total_money_dgkf[$value].$value.'<br>';}
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
                        if($total_money_qjkf[$value]){echo $total_money_qjkf[$value].$value.'<br>';}
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
                        if($total_money_bgkf[$value]){echo $total_money_bgkf[$value].$value.'<br>';}
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
                        if($total_money_ydkf[$value]){echo $total_money_ydkf[$value].$value.'<br>';}
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
                        if($total_money_otkf[$value]){echo $total_money_otkf[$value].$value.'<br>';}
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
           </tr>
          </tbody>
		  
          <thead>
            <tr>
              <th align="center">全部总计</th>
              <th align="center">
				<?php 
                //所有币种	
                $arr=ToArr($openCurrency,',');
                if($arr)
                {
                    foreach($arr as $arrkey=>$value)
                    {
                        $fe=FeData('money_czbak',"count(*) as total,sum(`toMoney`) as toMoney","toCurrency='{$value}'  and type>=1 and type<=20");
						if(spr($fe['toMoney'])){echo spr($fe['toMoney']).$value.'<br>';}	
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
                        $fe=FeData('money_czbak',"count(*) as total,sum(`toMoney`) as toMoney","toCurrency='{$value}'  and type>=51 and type<=60");
						if(spr($fe['toMoney'])){echo spr($fe['toMoney']).$value.'<br>';}	
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
                        $fe=FeData('money_czbak',"count(*) as total,sum(`toMoney`) as toMoney","toCurrency='{$value}'  and type>=30 and type<=50");
						if(spr($fe['toMoney'])){echo spr($fe['toMoney']).$value.'<br>';}	
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
                        $fe=FeData('money_czbak',"count(*) as total,sum(`toMoney`) as toMoney","toCurrency='{$value}'  and type=100");
						if(spr($fe['toMoney'])){echo spr($fe['toMoney']).$value.'<br>';}	
                    }
                }
                ?>
			  </th>
              <th align="center">
				
			  </th>
              <th align="center">
				<?php 
                //所有币种	
                $arr=ToArr($openCurrency,',');
                if($arr)
                {
                    foreach($arr as $arrkey=>$value)
                    {
                        $fe=FeData('money_kfbak',"count(*) as total,sum(`fromMoney`) as fromMoney","fromCurrency='{$value}' and type=1");
                        if(spr($fe['fromMoney'])){echo spr($fe['fromMoney']).$value.'<br>';}
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
                        $fe=FeData('money_kfbak',"count(*) as total,sum(`fromMoney`) as fromMoney","fromCurrency='{$value}' and type=3");
                        if(spr($fe['fromMoney'])){echo spr($fe['fromMoney']).$value.'<br>';}	
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
                        $fe=FeData('money_kfbak',"count(*) as total,sum(`fromMoney`) as fromMoney","fromCurrency='{$value}' and type in (2,53)");
                        if(spr($fe['fromMoney'])){echo spr($fe['fromMoney']).$value.'<br>';}	
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
                        $fe=FeData('money_kfbak',"count(*) as total,sum(`fromMoney`) as fromMoney","fromCurrency='{$value}' and ((type>=30 and type<=50) or type in (52,54,55))");
                        if(spr($fe['fromMoney'])){echo spr($fe['fromMoney']).$value.'<br>';}	
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
                        $fe=FeData('money_kfbak',"count(*) as total,sum(`fromMoney`) as fromMoney","fromCurrency='{$value}' and type in (21,22,51)");
                        if(spr($fe['fromMoney'])){echo spr($fe['fromMoney']).$value.'<br>';}	
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
                        $fe=FeData('money_kfbak',"count(*) as total,sum(`fromMoney`) as fromMoney","fromCurrency='{$value}' and type in (2,53,60,100)");
                        if(spr($fe['fromMoney'])){echo spr($fe['fromMoney']).$value.'<br>';}		
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
                        $fe=FeData('member',"count(*) as total,sum(`money`) as money,sum(`money_lock`) as money_lock","currency='{$value}'");
						if(spr($fe['money'])||spr($fe['money_lock']))
						{
							echo spr($fe['money']).$value;
							if(spr($fe['money_lock'])){echo '(冻结 '.spr($fe['money_lock']).$value.')';}
							echo '<br>';
						}
						
                    }
                }
                ?>

				</th>
           </tr>

          </thead>
        </table>
        
        <div class="row">
          <?=$listpage?>
        </div>
      </form>
	  
	<div class="xats">
		<strong>提示:</strong><br />
		&raquo; 点击对应列名就按统计该列<br />
		&raquo; 以上是以记录表来统计,如果记录表有过删除,将影响统计结果<br />
	</div>

    </div>
    <!--表格内容结束--> 
    
  </div>
</div>
<?php
require_once($_SERVER['DOCUMENT_ROOT'].'/xingao/incluce/foot.php');
?>
