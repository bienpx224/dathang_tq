
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
require_once($_SERVER['DOCUMENT_ROOT'].'/xamember/incluce/session.php');
$headtitle=$LG['main.headtitle'];//会员中心
require_once($_SERVER['DOCUMENT_ROOT'].'/xamember/incluce/head.php');

$mr=FeData('member','*',"userid='{$Muserid}'");
?>
<div class="page_ny">
<?php 
$_SESSION['member']['certification']=$mr['certification'];
if($off_certification&&!$mr['certification']){
?>
    <div class="alert alert-block alert-danger fade in col-md-12">
    <?=$LG['main.18'];//重要提示：您未通过实名认证，部分功能不能使用，?>
    <a href="/xamember/data/form.php?tab=4"  class="btn btn-xs btn-warning">
	<?=$LG['main.19'];//请提交实名认证申请?>
    </a>
    </div>
<?php }?>


<div style="margin-bottom:20px;">
	<?php if($off_baoguo){?>
    <a href="/xamember/baoguo/list.php?status=<?=$CN_zhi='kuwai'?>" class="icon-btn">
	 <i class="icon-dropbox"></i>
	 <div><?=$LG['main.name_3'];//未入库包裹?></div>
	 <?=$bgnum_status_kuwai=CountNum($CN_table='baoguo',$CN_field='',$CN_zhi='kuwai',$CN_where="and status in (0,1) and ware=0",$CN_userid=$Muserid,$CN_color='warning');?>
	</a>
	
	<a href="/xamember/baoguo/list.php?status=ruku" class="icon-btn">
	 <i class="icon-dropbox"></i>
	 <div><?=$LG['main.name_4'];//待下单包裹?></div>
	 <?=$bgnum_status_ruku=CountNum($CN_table='baoguo',$CN_field='',$CN_zhi='',$CN_where="and status in (2,3) and ware=0",$CN_userid=$Muserid,$CN_color='success');?>
	</a>
	
	  <?php if($ON_ware){?>
		  <a href="/xamember/baoguo/list.php?status=ware" class="icon-btn">
			 <i class="icon-dropbox"></i>
			 <div><?=$LG['main.name_5'];//仓储包裹?></div>
			 <?=$bgnum_status_ware=CountNum($CN_table='baoguo',$CN_field='ware',$CN_zhi=1,$CN_where="",$CN_userid=$Muserid,$CN_color='default');?>
		  </a>
	 <?php }?>
     
	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
   <?php }?>
    
	<a href="/xamember/yundan/list.php?status=<?=$CN_zhi=0?>" class="icon-btn">
	 <i class="icon-plane"></i>
	 <div><?=$LG['main.name_6'];//未审核运单?></div>
	 <?=$ydnum_status_0=CountNum($CN_table='yundan',$CN_field='status',$CN_zhi=0,$CN_where="",$CN_userid=$Muserid,$CN_color='default');?>
	</a>
	
	<a href="/xamember/yundan/list.php?status=<?=$CN_zhi=1?>" class="icon-btn">
	 <i class="icon-plane"></i>
	 <div><?=$LG['main.name_7'];//未通过运单?></div>
	<?=$ydnum_status_1=CountNum($CN_table='yundan',$CN_field='status',$CN_zhi=1,$CN_where="",$CN_userid=$Muserid,$CN_color='important');?>
	</a>
	
	<a href="/xamember/yundan/list.php?status=<?=$CN_zhi=3?>" class="icon-btn">
	 <i class="icon-plane"></i>
	 <div><?=$LG['main.name_8'];//待支付运单?></div>
	<?=$ydnum_status_3=CountNum($CN_table='yundan',$CN_field='status',$CN_zhi=3,$CN_where="",$CN_userid=$Muserid,$CN_color='warning');?>
	</a>
    
	<?php if($off_mall){?>
	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
	<a href="/xamember/mall_order/list.php?so=1&pay=<?=$CN_zhi=1?>&status=1" class="icon-btn">
	 <i class="icon-shopping-cart"></i>
	 <div><?=$LG['main.name_9'];//商城订单?></div>
	 <?=CountNum($CN_table='mall_order',$CN_field='pay',$CN_zhi,$CN_where=" and status=1",$CN_userid=$Muserid,$CN_color='warning');?>
	</a>
	<?php }?>
	
	<!---->
<?php if($ON_daigou&&$member_per[$Mgroupid]['daigou']){?>
	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
	<a href="/xamember/daigou/list.php?so=1&status=pay" class="icon-btn">
	 <i class="icon-retweet"></i>
	 <div><?=$LG['main.name_10'];//待付款代购?></div>
	<?=$dgnum_status_pay=CountNum($CN_table='daigou',$CN_field='',$CN_zhi='',$CN_where=" and status in (2,4)",$CN_userid=$Muserid,$CN_color='warning');?>
	</a>
    
	<a href="/xamember/daigou/list.php?so=1&status=1" class="icon-btn">
	 <i class="icon-retweet"></i>
	 <div><?=$LG['main.name_11'];//拒绝代购?></div>
	<?=$dgnum_status_1=CountNum($CN_table='daigou',$CN_field='status',$CN_zhi=1,$CN_where="",$CN_userid=$Muserid,$CN_color='important');?>
	</a>
<?php }?>
	<!---->
	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
	<a href="/xamember/qujian/list.php?so=1&status=<?php echo $zhi=0;?>" class="icon-btn">
	 <i class="icon-upload-alt"></i>
	 <div><?=$LG['main.name_12'];//待处理取件?></div>
	<?=$qjnum_status_0=CountNum($CN_table='qujian',$CN_field='status',$CN_zhi,$CN_where='',$CN_userid=$Muserid,$CN_color='default');?>
	</a>
	<a href="/xamember/qujian/list.php?so=1&status=<?php echo $zhi=3;?>" class="icon-btn">
	 <i class="icon-upload-alt"></i>
	 <div><?=$LG['main.name_13'];//拒绝取件?></div>
	<?=$qjnum_status_3=CountNum($CN_table='qujian',$CN_field='status',$CN_zhi,$CN_where='',$CN_userid=$Muserid,$CN_color='important');?>
	</a>
	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
	
	<a href="/xamember/lipei/list.php?so=1&status=<?php echo $zhi=0;?>" class="icon-btn">
	 <i class="icon-money"></i>
	 <div><?=$LG['main.name_14'];//待处理理赔?></div>
	  <?=$lpnum_status_0=CountNum($CN_table='lipei',$CN_field='status',$CN_zhi,$CN_where='',$CN_userid=$Muserid,$CN_color='default');?>
	</a>
	<a href="/xamember/lipei/list.php?so=1&status=<?php echo $zhi=3;?>" class="icon-btn">
	 <i class="icon-money"></i>
	 <div><?=$LG['main.name_15'];//拒绝理赔?></div>
	<?=$lpnum_status_3=CountNum($CN_table='lipei',$CN_field='status',$CN_zhi,$CN_where='',$CN_userid=$Muserid,$CN_color='important');?>
	</a>
	<!---->
	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
	<a href="/xamember/tixian/list.php?so=1&status=<?php echo $zhi=1;?>" class="icon-btn">
	 <i class="icon-credit-card"></i>
	 <div><?=$LG['main.name_16'];//处理中提现?></div>
	  <?=$txnum_status_1=CountNum($CN_table='tixian',$CN_field='status',$CN_zhi,$CN_where='',$CN_userid=$Muserid,$CN_color='default');?>
	</a>
	<a href="/xamember/tixian/list.php?so=1&status=<?php echo $zhi=3;?>" class="icon-btn">
	 <i class="icon-credit-card"></i>
	 <div><?=$LG['main.name_17'];//拒绝提现?></div>
	 <?=$txnum_status_3=CountNum($CN_table='tixian',$CN_field='status',$CN_zhi,$CN_where='',$CN_userid=$Muserid,$CN_color='important');?>
	</a>
	<!---->
	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
	<?php if($off_shaidan){?>
	<a href="/xamember/shaidan/list.php?so=1&checked=<?=$CN_zhi=0?>" class="icon-btn">
	 <i class="icon-star"></i>
	 <div><?=$LG['main.name_18'];//待审核晒单?></div>
	 <?=$sdnum_status_0=CountNum($CN_table='shaidan',$CN_field='checked',$CN_zhi,$CN_where='',$CN_userid=$Muserid,$CN_color='warning');?>
	</a>
	<?php }?>
	<!---->
	<a class="icon-btn">
	 <i class="icon-thumbs-up"></i>
	 <div><?=$LG['main.name_19'];//待审核评论?></div>
	  <?=CountNum($table='comments',$field='checked',0,$where="and reply_userid='0'",$userid=$Muserid,$color='default');?>
	</a>
</div>         







<!------------------------------------------选项卡----------------------------------------------->	
	<div class="clear"></div>
	<div class="tabbable tabbable-custom boxless">
	<ul class="nav nav-tabs">
	  <li class="active"><a href="#tab_1" data-toggle="tab"><?=$LG['main.53'];//我的仓库?></a></li>
	  
	  <li><a href="#tab_2" data-toggle="tab"><?=$LG['main.54'];//我的价格?></a></li>
	  
	  <li><a href="#tab_3" data-toggle="tab"><?=$LG['main.55'];//最新入库包裹?></a></li>
	  <li><a href="#tab_4" data-toggle="tab"><?=$LG['main.56'];//最新运单状态?></a></li>
	  <li><a href="#tab_5" data-toggle="tab"><?=$LG['main.57'];//最新消费记录?></a></li>
	  
	</ul>
	<div class="tab-content">
		<div class="tab-pane active" id="tab_1">
			<div class="article_ny" bgcolor="red">
			<?php require_once($_SERVER['DOCUMENT_ROOT'].'/xamember/other/call/warehouse.php');?>
			</div>
		</div>
		
		<div class="tab-pane" id="tab_2">
			 <?php require_once($_SERVER['DOCUMENT_ROOT'].'/xamember/other/call/price.php')?>  
		</div>
		
		
		<div class="tab-pane" id="tab_3">
			<table class="table table-striped table-bordered table-hover" >
			  <thead>
				<tr>
					<th align="center"><?=$LG['main.11'];//单号?></th>
					<th align="center"><?=$LG['warehouse'];//仓库?></th>
					<th align="center"><?=$LG['main.12'];//重量?></th>
					<th align="center"><?=$LG['main.13'];//来源?></th>
					<th align="center"><?=$LG['main.14'];//预报时间?></th>
					<th align="center"><?=$LG['main.15'];//入库时间?></th>
				</tr>
			  </thead>
			  <tbody>
				<?php
				$query="select bgid,bgydh,warehouse,weight,addSource,rukutime,addtime from baoguo where status in (2,3) and ware=0 {$Mmy} order by rukutime desc limit 10";
				$sql=$xingao->query($query);
				while($rs=$sql->fetch_array())
				{
				?>
				<tr class="odd gradeX">
					<td align="center" valign="middle"><a href="/xamember/baoguo/show.php?bgid=<?=$rs['bgid']?>" target="_blank"><?=cadd($rs['bgydh'])?></a></td>
					<td align="center" valign="middle"><?=warehouse($rs['warehouse'])?></td>
					<td align="center" valign="middle"><?=$rs['weight']>0?spr($rs['weight']).$XAwt:''?></td>
					<td align="center" valign="middle"><?=baoguo_addSource($rs['addSource'])?></td>
					<td align="center" valign="middle"><font title="<?=$LG['main.63'];//添加/预报时间?>"><?=DateYmd($rs['addtime']);?></font></td>
					<td align="center" valign="middle"><?=DateYmd($rs['rukutime']);?></td>
				</tr>
				<?php
				}
				?>
			  </tbody>
			</table>
			<div align="right">
			<a href="/xamember/baoguo/list.php?status=ruku" class="btn btn-default"><?=$LG['main.name_4'];//待下单包裹?></a>
			</div>
		</div>
		
		<div class="tab-pane" id="tab_4">
			<table class="table table-striped table-bordered table-hover" >
			  <thead>
				<tr>
					<th align="center"><?=$LG['main.11'];//单号?></th>
					<th align="center"><?=$LG['status'];//状态?></th>
					<th align="center"><?=$LG['main.66'];//更新时间?></th>
				</tr>
			  </thead>
			  <tbody>
				<?php
				$query="select ydid,ydh,status,statustime from yundan where 1=1 {$Mmy} order by ydh desc limit 10";
				$sql=$xingao->query($query);
				while($rs=$sql->fetch_array())
				{
				?>
				<tr class="odd gradeX">
					<td align="center" valign="middle"><a href="/xamember/yundan/show.php?ydid=<?=$rs['ydid']?>" target="_blank"><?=cadd($rs['ydh'])?></a></td>
					<td align="center" valign="middle">
					<a href="/yundan/status.php?ydh=<?=$rs['ydh']?>" target="_blank">
						<?php 
						if(spr($rs['status'])<=1){echo status_name(spr($rs['status']));}
						else{echo status_name(FeData('yundan_bak','status',"ydid={$rs[ydid]} order by id desc"));}
						?>
					</a>
					</td>
					<td align="center" valign="middle"><?=DateYmd($rs['statustime'],1);?></tr>
				<?php
				}
				?>
			  </tbody>
			</table>
			<div align="right">
			<a href="/xamember/yundan/list.php?status=chuku" class="btn btn-default"><?=$LG['main.67'];//已出库运单?></a>
			</div>
		</div>
		
		<div class="tab-pane" id="tab_5">
			<table class="table table-striped table-bordered table-hover" >
			  <thead>
				<tr>
              <th align="center"><?=$LG['money.money_bak_7'];//类型?></th>
  
              <th align="center"><?=$LG['money.money_bak_8'];//用途?> </th>
              <th align="center"><?=$LG['explain'];//说明?> </th>
              <th align="center"><?=$LG['money.money_bak_9'];//本币?></th>
              <th align="center"><?=$LG['money.money_bak_10'];//原币?></th>
              <th align="center"><?=$LG['money.money_bak_11'];//账户?></th>
              <th align="center"><?=$LG['money.money_bak_12'];//操作员ID?></th>
              <th align="center"><?=$LG['time'];//时间?></th>
            
            </tr>
			  </thead>
			  <tbody>
<?php
$query="select * from 
(
	select 'cz' as flag,id,userid,username,fromtable,fromid,fromMoney,fromCurrency,toMoney,toCurrency,exchange,type,title,content,remain,operator,addtime from money_czbak
UNION ALL
	select 'kf' as flag,id,userid,username,fromtable,fromid,fromMoney,fromCurrency,toMoney,toCurrency,exchange,type,title,content,remain,operator,addtime from money_kfbak
) 
	a  where 1=1 {$Mmy} order by id desc limit 10
";
$sql=$xingao->query($query);
while($rs=$sql->fetch_array())
{
	if($rs['flag']=='cz')
	{
		$rs['type']=money_cz($rs['type']);
		$rs['money']=spr($rs['money']);
		$i_cz++;
	}elseif($rs['flag']=='kf'){
		$rs['type']=money_kf($rs['type']);
		$rs['money']='-'.spr($rs['money']);
		$i_kf++;
	}
?>
            
            <tr class="odd gradeX">
              <td align="center"><?=$rs['type']?></td>
              
              <td align="center">
			  <?=$rs['fromtable']?fromtableName($rs['fromtable']).'ID:'.$rs['fromid'].'':''?> 
              </td>
              
              <td align="center">
                <a <?=fromtableUrl($rs['fromtable'],$rs['fromid'])?>  class="popovers" data-trigger="hover" data-placement="top"  data-content="<?=cadd($rs['title'])?><?=$rs['content']?'：'.TextareaToCo($rs['content']):''?>">
                <?=$rs['title']?leng($rs['title'],20,'...'):$LG['explain']?>
                </a>
              </td>
              
              <td align="center"><?=spr($rs['toMoney']).cadd($rs['toCurrency'])?></td>
              <td align="center"><?=spr($rs['fromMoney']).cadd($rs['fromCurrency'])?></td>
              <td align="center"><?=spr($rs['remain']).cadd($rs['toCurrency'])?></td>
              <td align="center"><?=cadd($rs['operator'])?></td>
              <td align="center"><?=DateYmd($rs['addtime'],1)?></td>
              
            </tr>
<?php
}
?>
            
			  </tbody>
			</table>
			<div align="right">
			<a href="/xamember/money/money_bak.php" class="btn btn-default" ><?=$LG['main.72']?></a>
			</div>
		</div>
	</div>
	</div>



<!------------------------------------------选项卡----------------------------------------------->	
















<!--------------------------------------------我的资料--------------------------------------------->	
	<div class="col-md-6 col-sm-6" style="padding-left:0px;">
	   <div class="portlet">
		  <div class="portlet-title">
			 <div class="caption">
             <i class="icon-user"></i>
			  <?=$Mtruename?><span class="xa_sep"> | </span>
			  <?=$Musername?><span class="xa_sep"> | </span>
			  <?=cadd($mr['nickname'])?><span class="xa_sep"> | </span>
              <?=$member_per[$Mgroupid]['groupname'];?>
			  </div>
			  <div class="tools">
				<a href="javascript:;" class="collapse"></a>
			 </div>
		  </div>
		  <div class="portlet-body form">
				<table class="table  gray">
				   <tbody>
                   
					  <tr>
						<td rowspan="10" align="center" valign="middle">
                        <a href="/xamember/data/form.php" ><img src="<?=$user_img?>"  width="150" height="150" /></a>
						</td>
					   </tr>
                       
					  <tr>
						 <td>
							<?=$LG['Muserid']//会员ID?>: <?=$memberid_tpre?><?=$Muserid?>
                            <font class="xa_sep"> | </font> 
							<?=$LG['Museric']//入库码?>: <?=$Museric?>
                            
                            
                            <?php 
							if($mr['CustomerService']){
								$r=CustomerService($mr['CustomerService']);
							?>
                            	<font class="xa_sep"> | </font> 
								<a href="/xamember/data/show.php" class=" popovers" data-trigger="hover" data-placement="top"  data-content="<?=CustomerService($mr['CustomerService'],2)?>">
								<?=$LG['CustomerService.0']?>:
                                <?=$r[0]?$r[0]:''?>
								<?=$r[1]?'('.$r[1].')':''?>
                                </a>
                                
	<?php if($r[6]){?>
    <a href="<?=urldecode($r[6])?>" class="btn btn-success" target="_blank" style="color:#ffffff"><i class="icon-comment"></i> <?=$LG['consulting']?></a>
    <?php }?>            <font color="red">&nbsp;&nbsp;&nbsp;点击二维码扫一扫加好友</font> 

                              <!--二维码-->
                              <li class="dropdown" style="float: right;padding-right:inherit"> <a href="javascript:void(0)" class="dropdown-toggle" data-toggle="dropdown" data-hover="dropdown"> <img src="/images/weixin/qrico.png" width="35" height="35"> </a>
                                  <ul class="dropdown-menu" style="width:210px; text-align: center; left:-150px;">
                                      <a target="_blank"><img src="<?=$r[5]?$r[5]:''?>" title="扫一扫加好友" width="250" height="350"></a>
                                  </ul>
                                  </a></li>

                              </a>
                                <span style="width: 30px;float: right;"></span>
                            <?php }?>

                             <!--新加的地方显示二维码图片-->



                         </td>
					   </tr>
					  <tr>
						 <td>
                         <?=$LG['money']//余额?>
                         <font class="red" style="font-size:24px;">
						 <?php $r_money=spr_sepa($mr['money']);echo $r_money[0];?><font style="font-size: 14px;"><?=$r_money[1]>0?'.'.$r_money[1]:''?></font>
                         </font>
                         <?=$mr['currency']?>
                         
                         <font class="red tooltips" data-container="body" data-placement="top" data-original-title="<?=$LG['main.20'];//可能用于提现或其他操作?>">
						 <?php if(spr($mr['money_lock'])>0){echo ' ('.$LG['money_lock'].spr($mr['money_lock']).$mr['currency'].')';}?>
                         </font>
              
             
            
              </font>
              
<a class="btn btn-warning" href="/xamember/money/money_cz.php" style="color:#ffffff"><i class="icon-credit-card"></i> <?=$LG['main.39']//在线充值?></a>

<?php if($ON_bankAccount){?>
<a class="btn btn-warning" href="/xamember/transfer/form.php"  style="color:#ffffff"><i class="icon-money"></i> <?=$LG['main.40']//转账充值?></a>
<?php }?>
						 </td>
					   </tr>
 
 
<!--月结-开始-->
<?php if($member_per[$Mgroupid]['off_settlement']){?>
                       <tr>
						 <td>
                         
                         <a href="/xamember/settlement/list_yundan.php" class="tooltips" data-container="body" data-placement="top" data-original-title="<?=$LG['main.26'];//您是月结会员（负数是欠费，正数是退费）?>">
                         <?=$LG['main.21'];//待销账总额?>
                         <font class="show_price">
                         <?php $r_money=spr_sepa($mr['settlement_all_money']);echo $r_money[0];?><font style="font-size: 14px;"><?=$r_money[1]>0?'.'.$r_money[1]:''?></font>
                         </font>
                         <?=$XAmc?>
                         </a>
                         
<?php if($mr['settlement_yundan_bill']){?>
<a class="btn btn-info tooltips" data-container="body" data-placement="top" data-original-title="<?=$LG['main.22'];//待销账的运单账单?><?=spr($mr['settlement_yundan_money']).$XAmc?>" href="/xamember/settlement/list_yundan.php" style="color:#ffffff"><i class="icon-money"></i> <?=$LG['main.27'];//运单销帐?></a>
<?php }?>
				 
<?php if($mr['settlement_other_bill']){?>
<a class="btn btn-info tooltips" data-container="body" data-placement="top" data-original-title="<?=$LG['main.23'];//待销账的其他账单?><?=spr($mr['settlement_other_money']).$XAmc?>" href="/xamember/settlement/list_other.php" style="color:#ffffff"><i class="icon-money"></i> <?=$LG['main.29'];//其他销帐?></a>
<?php }?>

						 </td>
					   </tr>
<?php }?>  
<!--月结-结束-->
                                     
                       <tr>
						 <td>
                         <?=$LG['integral']//积分?> <font class="red2"><?=spr($mr['integral'])?></font> 
                         <font class="xa_sep"> | </font> 
                         
                         <a href="/xamember/coupons/list.php?status=0" style=" color:#666; text-decoration: none;">
                         <?=$LG['main.28'];//优惠券/折扣券?>
						 <font class="red2">
						 <?php
							$cp=FeData('coupons',' count(*) as total,sum(`number`) as number',"status=0 {$Mmy}");
							echo $cp['number'];
						 ?>
                         </font>
                         <?=$LG['main.32'];//张?>
                         </a>
						 
<a class="btn btn-success" href="/xamember/coupons/form.php" style="color:#ffffff"><i class="icon-credit-card"></i> <?=$LG['main.31'];//兑换码?></a>
						 </td>
					   </tr>
                       
					  <tr>
						
						 <td>
        <a href="/xamember/log/list.php" style=" text-decoration: none;">                
		<font class="gray2 tooltips" data-container="body" data-placement="top" data-original-title="<?=$LG['main.33'];//上次登录IP?>">
		<?=cadd($mr['lastip'])?> <font class="xa_sep"> | </font> 
        </font>

		<font class="gray2 tooltips" data-container="body" data-placement="top" data-original-title="<?=$LG['main.34'];//上次登录时间?>">
		<?=DateYmd($mr['lasttime'],1)?> <font class="xa_sep"> | </font> 
		</font>
    
        <font class="gray2 tooltips" data-container="body" data-placement="top" data-original-title="<?=$LG['main.35'];//登录次数?>">
         <?=cadd($mr['loginnum'])?><?=$LG['main.58'];//次?>
        </font>
		</a>
							 
						 </td>
				     </tr>


 					  <tr>
					    <td>
<?php if($off_certification){?>
    <a href="/xamember/data/form.php?tab=4" target="_blank">
    <i class="<?=spr($mr['certification'])?'icon-ok':'icon-remove red2'?>"></i><?=$LG['main.binding1']//实名?>
    </a>
    <span class="xa_sep"> | </span>
<?php }?>

<a href="/xamember/data/form.php?tab=3" target="_blank">
<i class="<?=cadd($mr['mobile'])?'icon-ok':'icon-remove red2'?>"></i><?=$LG['main.binding2']//手机?>
</a>
<span class="xa_sep"> | </span>


<a href="/xamember/data/form.php?tab=3" target="_blank">
<i class="<?=cadd($mr['email'])?'icon-ok':'icon-remove red2'?>"></i><?=$LG['main.binding3']//邮箱?>
</a>
<span class="xa_sep"> | </span>


<?php if($ON_WX){?>
    <a href="/xamember/data/form.php?tab=3" target="_blank">
    <i class="<?=cadd($mr['wx_openid'])?'icon-ok':'icon-remove red2'?>"></i><?=$LG['main.binding4']//微信公众号?>
    </a>
    <span class="xa_sep"> | </span>
<?php }?>

<?php if($off_connect_weixin){
	$num=NumData('member_connect',"apptype='weixin' {$Mmy}");
	?>
    <a href="/xamember/connect/list.php" target="_blank">
	<i class="<?=$num?'icon-ok':'icon-remove red2'?>"></i><?=$LG['main.binding5']//微信快捷登录?>
    </a>
    <span class="xa_sep"> | </span>
<?php }?>

<?php if($off_connect_qq){
	$num=NumData('member_connect',"apptype='qq' {$Mmy}");
	?>
    <a href="/xamember/connect/list.php" target="_blank">
	<i class="<?=$num?'icon-ok':'icon-remove red2'?>"></i><?=$LG['main.binding6']//QQ快捷登录?>
    </a>
    <span class="xa_sep"> | </span>
<?php }?>

<?php if($off_connect_alipay){
	$num=NumData('member_connect',"apptype='alipay' {$Mmy}");
	?>
    <a href="/xamember/connect/list.php" target="_blank">
	<i class="<?=$num?'icon-ok':'icon-remove red2'?>"></i><?=$LG['main.binding7']//支付宝快捷登录?>
    </a>
<?php }?>
						</td>
				     </tr>


					  
				   </tbody>
				</table>
			
		  </div>
	   </div>
	</div>

<!-------------------------------------------新闻公告---------------------------------------------->	
	<div class="col-md-6 col-sm-12"  style="padding-right:0px;">
	   <div class="portlet">
		  <div class="portlet-title">
			 <div class="caption"><i class="icon-bullhorn"></i><?=$LG['main.36'];//新闻公告?></div>
			 <div class="tools">
				<a href="" class="collapse"></a>
			 </div>
		  </div>
		  <div class="portlet-body">
			 <!--BEGIN TABS-->
			 <div class="tab-content"  style="height: 160px;">
                <div class="tab-pane active">
                <div class="scroller" data-always-visible="1" data-rail-visible="0">
                  <ul class="feeds">
					<?php 
                    $c['classid']=23;
                    $i=0;
                    $field='';//特别字段(以,开头)
                    $where='and isgood>=2';//特别条件(以and开头)
                    $limit=15;
                
                    $allclassid=$c['classid'].SmallClassID($c['classid']);
                    $order=' order by istop desc,isgood desc,ishead desc,edittime desc,addtime desc';//默认排序
                    $query="select id,url{$LT},path,title{$LT},edittime,addtime,titlecolor,intro{$LT},img{$LT}{$field} from article where checked=1 and classid in ({$allclassid}) {$where} {$order} limit {$limit}";
                    $sql=$xingao->query($query);
                    while($rs=$sql->fetch_array())
                    {
                    ?>
                    <li>
                    <a href="<?=$rs['url'.$LT]?cadd($rs['url'.$LT]):pathLT($rs['path'])?>" target="_blank" title="<?=leng(striptags($rs['intro'.$LT]),150,"...")?>">
                    <div class="col1">
                      <div class="cont">
                         <div class="cont-col1">
                            <div class="label label-sm label-default">                        
                               <i class="icon-bullhorn"></i>
                            </div>
                         </div>
                         <div class="cont-col2">
                            <div class="desc">
                               <?=leng($rs['title'.$LT],48,"...");?>
                            </div>
                         </div>
                      </div>
                    </div>
                    <div class="col2">
                      <div class="date">
                         <?=DateYmd($rs['edittime'],3,$rs['addtime'])?> 
                      </div>
                    </div>
                    </a>
                    </li>
                    
                    <?php
                    }
                    ?>
                    </ul>
					<script type="text/javascript">
                    jQuery(".scroller").slide({mainCell:".feeds",autoPage:true,effect:"topLoop",autoPlay:true,vis:5,interTime:2000});
                    </script> 
                    
                </div>
                </div>
    		  </div>
			 <!--END TABS-->
		  </div>
	   </div>
	</div>

	<div class="clear"></div>



<!------------------------------------------邀请----------------------------------------------->	
      <?php if($off_tuiguang){?>
	<div class="col-md-6 col-sm-6" style="padding-left:0px;">
		<div class="portlet">
		<div class="portlet-title">
		  <div class="caption"><i class="icon-reorder"></i><?=$LG['main.38'];//邀请?></div>
		  <div class="tools"> <a href="javascript:;" class="collapse"></a> </div>
		</div>
		<div class="portlet-body" style="display: block; height:100px;">
        
		<?php require_once($_SERVER['DOCUMENT_ROOT'].'/xamember/tuiguang/call/ppt.php');?>
        <a href="tuiguang/tuiguang_bak.php" class="label label-sm label-default"><?=$LG['main.46'];//邀请记录?></a>
        
		  </div>
		</div>
	</div>
      <?php }?>
      
      
      
      
      
<!------------------------------------------我的客户端----------------------------------------------->	
      <?php if($ON_MemberClient){?>
	<div class="col-md-6 col-sm-12"  style="padding-right:0px;">
		<div class="portlet">
		<div class="portlet-title">
		  <div class="caption"><i class="icon-reorder"></i><?=$LG['main.47'];//我的客户端?></div>
		  <div class="tools"> <a href="javascript:;" class="collapse"></a> </div>
		</div>
		<div class="portlet-body" style="display: block;height:100px;">

<table width="100%"  style=" margin-bottom:10px;">
    <tr>
      <td width="110">
		<button type="button" class="btn btn-info"  onClick="document.getElementById('code_ce').select();document.execCommand('Copy');alert('<?=$LG['main.51']?>');"><i class="icon-copy"></i> <?=$LG['main.41'];//复制链接?> </button>
      </td>
      <td>
<input id="code_ce" readonly class="tgfz popovers" data-trigger="hover" data-placement="top"  data-content="<?=$LG['main.52'];//如果您要测试该功能需要先退出会员再打开该链接，访问后需要关闭浏览器才能登录会员?>" 
value="<?=httpSite().$_SERVER['HTTP_HOST']?>/client"  
onClick="select();" style="color:#666666;">
      </td>
    </tr>
</table>
			  
                
                
				 <font class="gray_prompt">
                 <?=$LG['main.77'];//专门给您客户使用的功能，只有运单查询?>
					 <?php if($off_upload_cert){?>
                         <?=$LG['main.78'];//和上传证件功能?>
                     <?php }?>
				 <?=$LG['main.79'];//，访问该客户端后就无法浏览其他页面。防止这些客户自行在网站下单，以保护您的客户资源?>
                 </font>
		  </div>
		</div>
	</div>
      <?php }?>
	  
      
    
  </div>    
      
      
      

<?php
require_once($_SERVER['DOCUMENT_ROOT'].'/xamember/incluce/foot.php');
?>
