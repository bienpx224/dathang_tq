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
$noper='member_re';require_once($_SERVER['DOCUMENT_ROOT'].'/xingao/incluce/session.php');
$headtitle='转账申请记录';
require_once($_SERVER['DOCUMENT_ROOT'].'/xingao/incluce/head.php');
if(!$ON_bankAccount){exit ("<script>alert('转账充值系统已关闭！');goBack();</script>");}


//搜索
$where="1=1";
$status=par($_GET['status']);if(!$status){$status=0;}
if(is_numeric($status)&&CheckEmpty($status)){$where.=" and status='{$status}'";}
$search.="&status={$status}";

$so=(int)$_GET['so'];
if($so)
{
	$key=par($_GET['key']);
	$fromCurrency=par($_GET['fromCurrency']);
	$toCurrency=par($_GET['toCurrency']);
	$stime=par($_GET['stime']);
	$etime=par($_GET['etime']);
	$opStime=par($_GET['opStime']);
	$opEtime=par($_GET['opEtime']);
	
	if($key){$where.=" and (orderNo like '%{$key}%' or tfid='".CheckNumber($key,-0.1)."' or userid='".CheckNumber($key,-0.1)."' or username like '%{$key}%')";}
	if($fromCurrency){$where.=" and fromCurrency='{$fromCurrency}'";}
	if($toCurrency){$where.=" and toCurrency='{$toCurrency}'";}
	if($stime){$where.=" and addtime>='".strtotime($stime.' 00:00:00')."'";}
	if($etime){$where.=" and addtime<='".strtotime($etime.' 23:59:59')."'";}
	if($opStime){$where.=" and optime>='".strtotime($opStime.' 00:00:00')."'";}
	if($opEtime){$where.=" and optime<='".strtotime($opEtime.' 23:59:59')."'";}
	
	$search.="&so={$so}&key={$key}&fromCurrency={$fromCurrency}&toCurrency={$toCurrency}&stime={$stime}&etime={$etime}&opStime={$opStime}&opEtime={$opEtime}";
}

$order=' order by status asc,tfid desc';//默认排序
include($_SERVER['DOCUMENT_ROOT'].'/public/orderby.php');//排序处理页

$query="select * from transfer where {$where}   {$order}";

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
  
    <ul class="nav nav-tabs">
		<?php 
		$tfnum_status_all=CountNum($CN_table='transfer',$CN_field='',$CN_zhi='',$CN_where="",$CN_userid=$Muserid,$CN_color='warning');
		$tfnum_status_0=CountNum($CN_table='transfer',$CN_field='status',$CN_zhi=0,$CN_where="",$CN_userid=$Muserid,$CN_color='warning');
		$tfnum_status_1=CountNum($CN_table='transfer',$CN_field='status',$CN_zhi=1,$CN_where="",$CN_userid=$Muserid,$CN_color='success');
		$tfnum_status_5=CountNum($CN_table='transfer',$CN_field='status',$CN_zhi=5,$CN_where="",$CN_userid=$Muserid,$CN_color='default');
		?> 
       
        <li class="<?php $val='all';if(!is_numeric($status)&&$status==$val){echo 'active';$tfnum_status_all='<span class="badge badge-default">'.$num.'</span>';}?>"><a href="?status=<?=$val?>">全部<?=$tfnum_status_all?></a></li>
        
        <li class="<?php $val=0;if(is_numeric($status)&&$status==$val){echo 'active';$tfnum_status_0='<span class="badge badge-default">'.$num.'</span>';}?>"><a href="?status=<?=$val?>"><?=transfer_Status($val)?><?=$tfnum_status_0?></a></li>
        <li class="<?php $val=1;if($status==$val){echo 'active';$tfnum_status_1='<span class="badge badge-default">'.$num.'</span>';}?>"><a href="?status=<?=$val?>"><?=transfer_Status($val)?><?=$tfnum_status_1?></a></li>
        <li class="<?php $val=5;if($status==$val){echo 'active';$tfnum_status_5='<span class="badge badge-default">'.$num.'</span>';}?>"><a href="?status=<?=$val?>"><?=transfer_Status($val)?><?=$tfnum_status_5?></a></li>
    </ul>
    
    <div class="tab-content" style="padding:10px;"> 
      <!--搜索-->
      <div class="navbar navbar-default" role="navigation">
        <div class="collapse navbar-collapse navbar-ex1-collapse">
          <form class="navbar-form navbar-left"  method="get" action="?">
            <input name="so" type="hidden" value="1">
            <input name="status" type="hidden" value="all">
            <div class="form-group">
              <input type="text" name="key" class="form-control input-msmall popovers" data-trigger="hover" data-placement="right"  data-content="转账回单编号/信息ID/会员名/会员ID" value="<?=$key?>">
            </div>
            
            <div class="form-group">
                <select  class="form-control input-small select2me" name="fromCurrency" data-placeholder="原币种">
                <?=openCurrency($fromCurrency,2)?>
                </select>
            </div>
            
            <div class="form-group">
                <select  class="form-control input-small select2me" name="toCurrency" data-placeholder="本币种">
                <?=openCurrency($toCurrency,2)?>
                </select>
            </div>
            
            <div class="form-group">
              <div class="col-md-0">
                <div class="input-group input-xmedium date-picker input-daterange" data-date-format="yyyy-mm-dd">
                  <input type="text" class="form-control input-small" name="stime" value="<?=$stime?>" placeholder="申请时间-起">
                  <span class="input-group-addon">-</span>
                  <input type="text" class="form-control input-small" name="etime" value="<?=$etime?>"  placeholder="申请时间-止">
                </div>
              </div>
            </div>
            
             <div class="form-group">
              <div class="col-md-0">
                <div class="input-group input-xmedium date-picker input-daterange" data-date-format="yyyy-mm-dd">
                  <input type="text" class="form-control input-small" name="opStime" value="<?=$opStime?>" placeholder="首次处理-起">
                  <span class="input-group-addon">-</span>
                  <input type="text" class="form-control input-small" name="opEtime" value="<?=$opEtime?>"  placeholder="首次处理-止">
                </div>
              </div>
            </div>
                       
            <button type="submit" class="btn btn-info"><i class="icon-search"></i> <?=$LG['search']//搜索?></button>
          </form>
        </div>
      </div>
      
      
      <form action="save.php" method="post" name="XingAoForm">
        <input name="lx" type="hidden">
        <table class="table table-striped table-bordered table-hover" >
          <thead>
            <tr>
              <th align="center" class="table-checkbox"> <input type="checkbox"  id="aAll" onClick="chkAll(this)"  title="全选/取消"/>
              </th>
              
              <th align="center"><a href="?<?=$search?>&orderby=tfid&orderlx=" class="<?=orac('tfid')?>">ID</a></th>
             <th align="center"><a href="?<?=$search?>&orderby=toMoney&orderlx=" class="<?=orac('toMoney')?>">充值</a></th>
              <th align="center"><a href="?<?=$search?>&orderby=fromMoney&orderlx=" class="<?=orac('fromMoney')?>">转账</a></th>
               <th align="center"><a href="?<?=$search?>&orderby=orderNo&orderlx=" class="<?=orac('orderNo')?>">转账回单编号</a></th>
              <th align="center"><a href="?<?=$search?>&orderby=img&orderlx=" class="<?=orac('img')?>">转账回单</a></th>
              <th align="center"><a href="?<?=$search?>&orderby=fromtable&orderlx=" class="<?=orac('fromtable')?>">自动支付</a></th>
              <th align="center"><a href="?<?=$search?>&orderby=content&orderlx=" class="<?=orac('content')?>">留言</a></th>
              <th align="center"><a href="?<?=$search?>&orderby=reply&orderlx=" class="<?=orac('reply')?>">回复</a></th>
              <th align="center"><a href="?<?=$search?>&orderby=status&orderlx=" class="<?=orac('status')?>"><?=$LG['status']//状态?></a></th>
              <th align="center"><a href="?<?=$search?>&orderby=username&orderlx=" class="<?=orac('username')?>">会员</a></th>
              <th align="center"><a href="?<?=$search?>&orderby=addtime&orderlx=" class="<?=orac('addtime')?>">申请时间</a></th>
             <th align="center">操作</th>
            </tr>
          </thead>
          <tbody>
<?php
while($rs=$sql->fetch_array())
{
	//获取会员币种
	//$m_currency=FeData('member','currency',"userid='{$rs['userid']}'");
?>
            <tr class="odd gradeX <?=spr($rs['status'])==5?'gray2':''?>">
              <td align="center" valign="middle">
               <?php if(spr($rs['status'])==0){?>
               <input type="checkbox" id="a" onClick="chkColor(this);" name="tfid[]" value="<?=$rs['tfid']?>" />
               <?php }?>
               </td>
              <td align="center" valign="middle"><?=$rs['tfid']?></td>
              <td align="center" valign="middle"><?=$rs['toMoney']>0?spr($rs['toMoney']).cadd($rs['toCurrency']):''?></td>
              <td align="center" valign="middle">
              <?=$rs['fromMoney']>0?spr($rs['fromMoney']).cadd($rs['fromCurrency']):''?><br>
                <font class="gray2 tooltips" data-container="body" data-placement="top" data-original-title="当时汇率"><?=spr($rs['exchange'],5,0)?></font>
              </td>
              <td align="center" valign="middle"><?=cadd($rs['orderNo'])?></td>
              <td align="center" valign="middle"><?=ShowImg($rs['img'])?></td>
              <td align="center" valign="middle">
			   <?php if($rs['autoPay']&&$rs['fromtable']&&$rs['fromid']){?>
                   <a class=" popovers" data-trigger="hover" data-placement="top"  data-content="<?=$LG['transfer.autoPay'].fromtableName($rs['fromtable']).':'.cadd($rs['fromid'])?>">
                   <?=transfer_autoPayStatus($rs['autoPayStatus'])?>
                   </a>
               <?php }?>
              </td>
              
              <td align="center" valign="middle">
			   <?php if(trim($rs['content'])){?>
                   <a class=" popovers" data-trigger="hover" data-placement="top"  data-content="<?=cadd($rs['content'])?>">
                   <i class="icon-comment"></i>
                   </a>
               <?php }?>
              </td>
              
              <td align="center" valign="middle">
			   <?php if(trim($rs['reply'])){?>
                   <a class=" popovers" data-trigger="hover" data-placement="top"  data-content="<?=cadd($rs['reply'])?> <br> (<?=DateYmd($rs['replytime'],1)?>)">
                   <i class="icon-comments"></i>
                   </a>
               <?php }?>
              </td>
              
              <td align="center" valign="middle"><?=transfer_Status(spr($rs['status']))?></td>
              <td align="center" valign="middle">
			  <?=cadd($rs['username'])?><br><font class="gray2"><?=$rs['userid']?></font>
              </td>
              
              <td align="center" valign="middle">
               
              <?php if($rs['edittime']){?>
              <font class=" tooltips" data-container="body" data-placement="top" data-original-title="最后再次处理"><?=DateYmd($rs['edittime'])?></font><br>
              <?php }?>

             <?php if($rs['optime']){?>
              <font class="gray tooltips" data-container="body" data-placement="top" data-original-title="首次处理"><?=DateYmd($rs['optime'])?></font><br>
              <?php }?>
              
              <font class="gray2 tooltips" data-container="body" data-placement="top" data-original-title="申请时间"><?=DateYmd($rs['addtime'])?></font>
              
              </td>

              <td align="center" valign="middle">
              
              <?php if(spr($rs['status'])==0||(spr($rs['status'])==1&&$bankAccountLock>0&&abs(DateDiff($rs['optime'],time(),'d'))<=$bankAccountLock) ){?>
              <a href="form.php?lx=edit&tfid=<?=$rs['tfid']?>" class="btn btn-xs btn-info"><i class="icon-edit"></i> 处理 </a> 
			  <?php }?>
              
              
              <?php if(spr($rs['status'])==0||(!$off_delbak&&(spr($rs['status'])==1||spr($rs['status'])==5)) ){?>
              <a href="save.php?lx=del&tfid=<?=$rs['tfid']?>" class="btn btn-xs btn-danger"  onClick="return confirm('<?=$LG['pptDelConfirm']//确认要删除所选吗?此操作不可恢复!?>');"><i class="icon-remove"></i> <?=$LG['del']?></a> 
              <?php }?>

              </td>
            </tr>
<?php
}
?>
                     
                     
          </tbody>
        </table>				
			
            
<!--底部操作按钮固定--> 

<style>body{margin-bottom:50px !important;}</style><!--后台不用隐藏,增高底部高度-->
<div align="right" class="fixed_btn" id="Autohidden">


<?php if(!$off_delbak||$off_delbak&&$status==0){?>
<!--btn-danger--><button type="submit" class="btn btn-grey" onClick="
document.XingAoForm.lx.value='del';
return confirm('<?=$LG['pptDelConfirm']//确认要删除所选吗?此操作不可恢复!?>');
"><i class="icon-signin"></i> <?=$LG['delSelect']//删除所选?></button>
 <?php }?> 
       </div>
        <div class="row">
          <?=$listpage?>
        </div>
      </form>
    </div>
    <!--表格内容结束--> 

<div class="xats"> 
    <?php if($bankAccountLock>0){?>
    <strong> 提示：</strong><br />
    &raquo; 处理后<?=$bankAccountLock?>天内可以修改，超过则不可再修改！<br />		
    <?php }?>  
</div>
    
    
    
        
  </div>
</div>

  
                     
     
<?php
$sql->free(); //释放资源
$enlarge=1;//是否用到 图片扩大插件 (/public/enlarge/call.html)
require_once($_SERVER['DOCUMENT_ROOT'].'/xingao/incluce/foot.php');
?>
