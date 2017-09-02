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
$headtitle="财务核销统计";
require_once($_SERVER['DOCUMENT_ROOT'].'/xingao/incluce/head.php');

//搜索
$where="1=1";
$so=(int)$_GET['so'];
if($so)
{
	$key=par($_GET['key']);
	$stime=par($_GET['stime']);
	$etime=par($_GET['etime']);
	$status=par($_GET['status']);
	
	if($key){$where.=" and (userid='".CheckNumber($key,-0.1)."' or username='{$key}' or truename like '%{$key}%' )";}
	if($stime){$where.=" and addtime>='".strtotime($stime.' 00:00:00')."'";}
	if($etime){$where.=" and addtime<='".strtotime($etime.' 23:59:59')."'";}

	$search.="&so={$so}&key={$key}&stime={$stime}&etime={$etime}";
}

$timequerycall=1;
$field="addtime";
include($_SERVER['DOCUMENT_ROOT'].'/public/timequery.php');//按时间快速查询

$order=' order by userid desc';//默认排序
include($_SERVER['DOCUMENT_ROOT'].'/public/orderby.php');//排序处理页

//echo $search;exit();
$query="select * from member where {$where} {$order}";//

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
              <input type="text" name="key" class="form-control input-msmall popovers" data-trigger="hover" data-placement="right"  data-content="会员ID/会员名/姓名 (可留空)" value="<?=$key?>">
            </div>
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
              <th align="center"><a href="?<?=$search?>&orderby=username&orderlx=" class="<?=orac('username')?>">会员</a>/<a href="?<?=$search?>&orderby=userid&orderlx=" class="<?=orac('userid')?>">ID</a></th>
              <th align="center">充值</th>
              <th align="center">退费</th>
              <th align="center">提现</th>
              <th align="center">消费</th>
              <th align="center">实际消费 (<?=$XAmc?>)</th>
              <th align="center">余额</th>
              <th align="center">&nbsp;</th>
			<!---------------------------------------------------------------------------------------------------->
              <?php if ($off_integral){ ?>
			  
              <th align="center">积分获取</th>
              <th align="center">积分消费</th>
              <th align="center">计算</th>
              <th align="center">目前</th>
              <?php }?>
			  
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
                        $fe=FeData('money_czbak',"count(*) as total,sum(`toMoney`) as toMoney","toCurrency='{$value}' and userid='{$rs[userid]}' and (type<30 or type>50)");
                        if(spr($fe['toMoney'])){$money_cz[$value]=spr($fe['toMoney']); echo $money_cz[$value].$value.'<br>';}		
                    }
                }
                ?>
				</a>			  
			</td>
			<!--退费-->
			<td align="center">
				<a href="../member/money_czbak.php?so=1&key=<?=$rs['userid']?>" target="_blank">
				<?php 
                //所有币种	
                $arr=ToArr($openCurrency,',');
                if($arr)
                {
                    foreach($arr as $arrkey=>$value)
                    {
                        $fe=FeData('money_czbak',"count(*) as total,sum(`toMoney`) as toMoney","toCurrency='{$value}' and userid='{$rs[userid]}' and type>=30 and type<=51");
                        if(spr($fe['toMoney']))
						{
							$exchange=exchange($value,$XAMcurrency);
							$money_tf_mc=$fe['toMoney']*$exchange;
							$money_tf[$value]=spr($fe['toMoney']); 
							echo $money_tf[$value].$value.'<br>';
						}		
                    }
                }
                ?>
				</a>
			</td>
			
			<!--提现-->
			<td align="center">
				<a href="../tixian/list.php?so=1&status=2&key=<?=$rs['userid']?>" target="_blank">
				<?php 
					$fe=FeData('tixian',"count(*) as total,sum(`money`) as money","userid='{$rs[userid]}' and status=2");
					$money_tx=spr($fe['money']);if($money_tx){echo $money_tx.$XAmc;}
				?>
				</a>			  
			</td>
			<!--消费-->
			<td align="center">
				<a href="../member/money_kfbak.php?so=1&key=<?=$rs['userid']?>" target="_blank">
				<?php 
                //所有币种	
                $arr=ToArr($openCurrency,',');
                if($arr)
                {
                    foreach($arr as $arrkey=>$value)
                    {
                        $fe=FeData('money_kfbak',"count(*) as total,sum(`fromMoney`) as fromMoney","fromCurrency='{$value}' and userid='{$rs[userid]}' ");
                        if(spr($fe['fromMoney'])){$money_kf[$value]=spr($fe['fromMoney']); echo $money_kf[$value].$value.'<br>';}		
                    }
                }
                ?>
				</a>			  
			</td>
			
			<!--计算-->
			<td align="center">
                <?php 
				$money_calc=$money_kf[$XAMcurrency]-$money_tf_mc;
				if($money_calc){echo $money_calc.$XAmc;}
				?>
			</td>
			
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
			<td align="center">&nbsp;</td>
			
			<!---------------------------------------------------------------------------------------------------->
			<?php if ($off_integral){ ?>
            <!--积分获取-->
  			<td align="center">
				<a href="../member/integral_czbak.php?so=1&key=<?=$rs['userid']?>" target="_blank">
				<?php 
					$fe=FeData('integral_czbak',"count(*) as total,sum(`integral`) as integral","userid='{$rs[userid]}'");
					$integral_cz=spr($fe['integral']);if($integral_cz){echo $integral_cz.'分';}
				?>
				</a>
			</td>
			
            <!--积分消费-->
  			<td align="center">
				<a href="../member/integral_czbak.php?so=1&key=<?=$rs['userid']?>" target="_blank">
				<?php 
					$fe=FeData('integral_kfbak',"count(*) as total,sum(`integral`) as integral","userid='{$rs[userid]}'");
					$integral_kf=spr($fe['integral']);if($integral_kf){echo $integral_kf.'分';}
				?>
				</a>			  
			</td>
			
			<!--计算-->
			<td align="center">
                <?php 
				$integral_calc=$integral_cz-$integral_kf;
				if($integral_calc){echo $integral_calc.'分';}
				?>
			</td>
			
			<!--目前积分-->
			<td align="center">
			    <font class="red">
				<?php $integral=spr($rs['integral']);if($integral){echo $integral.'分';}?>
                </font>
			</td>
            <?php }?>
			
		</tr>
<?php 
	//所有币种	
	$arr=ToArr($openCurrency,',');
	if($arr)
	{
		foreach($arr as $arrkey=>$value)
		{
			if($money_cz[$value]){$total_money_cz[$value]+=$money_cz[$value];}
			if($money_tf[$value]){$total_money_tf[$value]+=$money_tf[$value];}
			if($money_kf[$value]){$total_money_kf[$value]+=$money_kf[$value];}
			if($money[$value]){$total_money[$value]+=$money[$value];}
			if($money_lock[$value]){$total_money_lock[$value]+=$money_lock[$value];}
		}
	}

	unset($money_cz);unset($money_tf);unset($money_kf);unset($money);unset($money_lock);//必须:清空之前数据
	
	$total_money_tx+=$money_tx;
	$total_money_calc+=$money_calc;
	$total_integral_cz+=$integral_cz;
	$total_integral_kf+=$integral_kf;
	$total_integral_calc+=$integral_calc;
	$total_integral+=$integral;
}?>
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
                        if($total_money_tf[$value]){echo $total_money_tf[$value].$value.'<br>';}
                    }
                }
                ?>
			 </th>
              <th align="center"><?php if($total_money_tx){echo $total_money_tx.$XAmc;}?></th>
              <th align="center">
				<?php 
                //所有币种	
                $arr=ToArr($openCurrency,',');
                if($arr)
                {
                    foreach($arr as $arrkey=>$value)
                    {
                        if($total_money_kf[$value]){echo $total_money_kf[$value].$value.'<br>';}
                    }
                }
                ?>
			  </th>
              <th align="center"><?php if($total_money_calc){echo $total_money_calc.$XAmc;}?></th>
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
              <th align="center">&nbsp;</th>
 
              <th align="center"><?php if($total_money_tf){echo $total_money_tf.'分';}?></th>
              <th align="center"><?php if($total_integral_kf){echo $total_integral_kf.'分';}?></th>
              <th align="center"><?php if($total_integral_calc){echo $total_integral_calc.'分';}?></th>
              <th align="center"><?php if($total_integral){echo $total_integral.'分';}?></th>
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
                        $fe=FeData('money_czbak',"count(*) as total,sum(`toMoney`) as toMoney","toCurrency='{$value}' and  type<30 or type>50");
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
                        $fe=FeData('money_czbak',"count(*) as total,sum(`toMoney`) as toMoney","toCurrency='{$value}' and  type>=30 and type<=50");
                        if(spr($fe['toMoney'])){echo spr($fe['toMoney']).$value.'<br>';}		
                    }
                }
                ?>
			  </th>
              <th align="center">
				<?php 
					$fe=FeData('tixian',"count(*) as total,sum(`money`) as money","status=2");
					$money_tx=spr($fe['money']);if($money_tx){echo $money_tx.$XAmc;}
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
                        $fe=FeData('money_kfbak',"count(*) as total,sum(`fromMoney`) as fromMoney","fromCurrency='{$value}' ");
                        if(spr($fe['fromMoney'])){echo spr($fe['fromMoney']).$value.'<br>';}		
                    }
                }
                ?>
			  </th>
              <th align="center">
                <?php 
				$money_calc=$money_kf[$XAMcurrency]-$money_tf_mc;
				if($money_calc){echo $money_calc.$XAmc;}
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
              <th align="center">&nbsp;</th> 
              <th align="center">
				<?php 
					$fe=FeData('integral_czbak',"count(*) as total,sum(`integral`) as integral","");
					$integral_cz=spr($fe['money']);if($integral_cz){echo $integral_cz.'分';}
				?>
			  </th>
              <th align="center">
				<?php 
					$fe=FeData('integral_kfbak',"count(*) as total,sum(`integral`) as integral","");
					$integral_kf=spr($fe['money']);if($integral_kf){echo $integral_kf.'分';}
				?>
			  </th>
              <th align="center"><?=$integral_calc=$integral_cz-$integral_kf?>分</th>
              <th align="center">
			  <?php 
					$fe=FeData('member',"count(*) as total,sum(`integral`) as integral","");
					$integral_all=spr($fe['integral']);if($integral_all){echo $integral_all.'分';}
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
		&raquo; 实际消费计算公式：消费-退费<br />
		&raquo; 如果会员账户不是 <?=$XAmc?> ，则实际消费计算是按当前的汇率兑换 <?=$XAmc?> 后大概的计算，不是100%准确<br />
		&raquo; 以上是以记录表来统计,如果记录表有过删除,将影响统计结果<br />
	</div>

    </div>
    <!--表格内容结束--> 
    
  </div>
</div>
<?php
require_once($_SERVER['DOCUMENT_ROOT'].'/xingao/incluce/foot.php');
?>
