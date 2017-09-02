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
$pervar='settlement_se,settlement_ed';require_once($_SERVER['DOCUMENT_ROOT'].'/xingao/incluce/session.php');
$headtitle=cadd($_GET['username']).' 月结其他账单';
require_once($_SERVER['DOCUMENT_ROOT'].'/xingao/incluce/head.php');


if(!CheckEmpty($_GET['so'])){$_GET['so']=1;$_GET['tally']=1;}
$op2=par($_GET['op2']);

require($_SERVER['DOCUMENT_ROOT'].'/xingao/settlement/call/list_other_where.php');//条件处理,返回{$select} {$where} {$group} {$order}
include($_SERVER['DOCUMENT_ROOT'].'/public/orderby.php');//排序处理页

$query="select {$select} from money_kfbak where {$where} {$group} {$order}";

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
		</h3>
      
      <!-- END PAGE TITLE & BREADCRUMB--> 
    </div>
  </div>
  <!-- END PAGE HEADER--> 
  
  <!-- BEGIN PAGE CONTENT-->
  	<div class="tabbable tabbable-custom boxless">
		<ul class="nav nav-tabs">
			
			<li class="" style="margin-right:30px;"><a href="list_yundan.php?<?=$search?>">运单账单</a></li>
			<li class="active"><a href="list_other.php?<?=$search?>">其他账单</a></li>
			
		</ul>
		<div class="tab-content" style="padding:10px;"> 
       <!--搜索-->
      <div class="navbar navbar-default" role="navigation">
        
        <div class="collapse navbar-collapse navbar-ex1-collapse">
          <form class="navbar-form navbar-left"  method="get" action="?">
            <input name="so" type="hidden" value="1">

             <div class="form-group">
              <input type="text" class="form-control input-medium" name="username" autocomplete="off" title="会员ID/会员名" value="<?=$username?>" placeholder="会员ID/会员名">
            </div>

             <div class="form-group">
                <div class="col-md-0">
                     <select  class="form-control input-small select2me" name="tally" data-placeholder="月结销账" >
                     <option></option>
                      <?=Tally($tally,1)?>
                  	 </select>
               </div>
              </div>
             
           <button type="submit" class="btn btn-info"><i class="icon-search"></i> 搜索更新</button>
           <div style="margin-top:10px;">
                
             <div class="form-group">
                <div class="col-md-0">
                  <div class="input-group input-xmedium date-picker input-daterange" data-date-format="yyyy-mm-dd">
                    <input type="text" class="form-control input-small" name="stime" value="<?=$stime?>" placeholder="记账时间-起">
                    <span class="input-group-addon">-</span>
                    <input type="text" class="form-control input-small" name="etime" value="<?=$etime?>" placeholder="记账时间-止">
                  </div>
                 </div>
              </div>
            
               <div class="form-group">
                   <div class="col-md-0">
                  <!--<input type="checkbox" name="op1" value="1" <?=$op1?'checked':''?>>无运单账单的
                  &nbsp;&nbsp;-->
                  <input type="checkbox" name="op2" value="1" <?=$op2?'checked':''?>>总计 
                  </div>
              </div>
              
           </div>
              
          </form>
        </div>
      </div>
      <form action="?<?=$search?>" method="post" name="XingAoForm">
        <table class="table table-striped table-bordered table-hover" >
          <thead>
            <tr>
              <th align="center"><a href="?<?=$search?>&orderby=username&orderlx=" class="<?=orac('username')?>">会员</a>/<a href="?<?=$search?>&orderby=userid&orderlx=" class="<?=orac('userid')?>">ID</a></th>
              
              <th align="center"><a href="?<?=$search?>&orderby=type&orderlx=" class="<?=orac('type')?>">扣费类型</a></th>
               <th align="center"><a href="?<?=$search?>&orderby=fromtable&orderlx=" class="<?=orac('fromtable')?>">用途</a> </th>
               <th align="center"><a href="?<?=$search?>&orderby=title&orderlx=" class="<?=orac('title')?>">使用说明</a> </th>
              
              <th align="center"><a href="?<?=$search?>&orderby=fromMoney&orderlx=" class="<?=orac('fromMoney')?>">扣费</a></th>
              <th align="center"><a href="?<?=$search?>&orderby=addtime&orderlx=" class="<?=orac('addtime')?>">扣费时间</a></th>
              <th align="center">操作</th>
            
            </tr>
          </thead>
          <tbody>
<?php
while($rs=$sql->fetch_array())
{
	//另一个会员时
	$userid_nwe=0;
	if($rs['userid']!=$userid_pre)
	{ 
		$userid_pre=$rs['userid'];$userid_nwe=1;
	}
?>
            <tr class="odd gradeX">
              
              <td align="center">
              <?php 
			  if($userid_nwe)
			  {
			  ?>
			   <strong>
			   <?=cadd($rs['username'])?><br>
               <font class="gray2"><?=$rs['userid']?></font>
               </strong>
              <?php }?>
              </td>
            
              <td align="center">
              <?=money_kf($rs['type'])?>
              </td>
              
              <td align="center">
			  <?=$rs['fromtable']?fromtableName($rs['fromtable']).'ID:'.$rs['fromid'].'':''?> </td>
              
              <td align="center">
              <?php if(!$op2){?>
                  <a <?=fromtableUrl($rs['fromtable'],$rs['fromid'])?>  class="popovers" data-trigger="hover" data-placement="top"  data-content="<?=cadd($rs['title'])?><?=$rs['content']?'：'.cadd($rs['content']):''?>">
                  <?=$rs['title']?leng($rs['title'],20,'...'):'说明'?>
                  </a>
              <?php }?>
              </td>
              <td align="center">
              <?php 
			  if($op2)
			  {
				  $sr=FeData('money_kfbak',"count(*) as total,sum(`fromMoney`) as fromMoney","{$where} and userid='{$rs[userid]}'");
				  echo spr($sr['fromMoney']).$XAmc;
			  }
			  ?>
             
              <?php if(!$op2){?>
				  <?=spr($rs['fromMoney']).$XAmc?>
                  <?=Tally($rs['tally'])?>
              <?php }?>
              
              </td>
              <td align="center"><?=DateYmd($rs['addtime'],1)?></td>
              <td align="center">
<?php if($userid_nwe){?>

	<?php 
    if(permissions('settlement_ed','','manage',1) ){
		$mr=FeData('member','settlement_other_bill,settlement_other_money',"userid='{$rs['userid']}'");
		$settlement_bill=$mr['settlement_other_bill'];
		?>
			<?php if($settlement_bill){?>
                <!--显示销账按钮-开始-->
                <a href="pay.php?userid=<?=$rs['userid']?>&fromtable=other"  target="XingAobox" class="btn btn-xs btn-danger showdiv"><i class="icon-money"></i> 销账</a>
                <!--显示销账按钮-结束-->
                
                <!--显示账单按钮-开始-->
                <?php if(is_json($settlement_bill)){$bi=(array)json_decode($settlement_bill,true);//不能加cadd ?>
                    <a href="?username=<?=$rs['username']?>&tally=1&stime=<?=cadd($bi['stime'])?>&etime=<?=cadd($bi['etime'])?>"  class="btn btn-xs btn-default" target="_blank"><i class="icon-file-text"></i> 账单</a>
                <?php }?>
                <!--显示账单按钮-结束-->
            <?php }?>
            
            <br>
            <button type="submit" class="btn btn-xs btn-warning" onClick="
            document.XingAoForm.target='_blank';
            document.XingAoForm.lx.value='save';
            document.XingAoForm.action='save.php?save_lx_alone=1<?=$search?>';
            document.XingAoForm.userid.value='<?=$rs['userid']?>';
            return confirm('确定生成 <?=cadd($rs['username'])?> (<?=$rs['userid']?>) 的账单吗？');
            "><i class="icon-list-alt"></i> 按搜索<?=$settlement_bill?'重新':''?>生成待销账单</button>
            <br>
        
        <?php }?>
    
    
    <?php if(permissions('settlement_se','','manage',1) ){?>
    <button type="submit" class="btn btn-xs btn-default" onClick="
    document.XingAoForm.target='_blank';
    document.XingAoForm.action='excel_export/?<?=$search?>';
    document.XingAoForm.userid.value='<?=$rs['userid']?>';
    document.XingAoForm.ex_tem.value='tem_other_all';
    "><i class="icon-share"></i>按搜索导出</button>
    <?php }?>
    
    <!--显示其他按钮-开始-->
    <a class="btn btn-xs btn-default" href="/xingao/member/money_czbak.php?<?=$search?>&so=1&key=<?=$rs['userid']?>" target="_blank">充值/退费记录</a>
    <!--显示其他按钮-结束-->

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


<input name="lx" type="hidden">
<input name="userid" autocomplete="off"  type="hidden" value="">
<input name="fromtable" type="hidden" value="other">
    <!--************生成账单************-->	
    <?php if(permissions('settlement_ed','','manage',1) ){?>
        <select class="form-control select2me input-medium" data-placeholder="生成条件" name="save_lx">
        <option></option>
        <option value="1">会员上份账单:已销账</option>
        <option value="2">会员上份账单:未销账</option>
        <option value="3">会员上份账单:不限</option>
        <!--<option value="4">会员上份账单:提示</option>-->
        </select>
    
        <button type="submit" class="btn btn-grey" style="margin-right:20px;"
        onClick="
        document.XingAoForm.target='_blank';
        document.XingAoForm.lx.value='save';
        document.XingAoForm.action='save.php?<?=$search?>';
        document.XingAoForm.userid.value='';
        return confirm('确定生成账单吗？');
        "><i class="icon-signin"></i> 按搜索生成待支付账单给会员</button>
    <?php }?>	
    
    <!--************导出************-->
    <?php if(permissions('settlement_se','','manage',1) ){?>
        <select class="form-control select2me input-small" data-placeholder="统计方式" name="ex_tem">
        <option></option>
        <?php settlement_other_excel_export('',1)?>
        </select>
         
        <button type="submit" class="btn btn-grey" style="margin-right:20px;"
        onClick="
        document.XingAoForm.target='_blank';
        document.XingAoForm.action='excel_export/?<?=$search?>';
        document.XingAoForm.userid.value='';
        "><i class="icon-signin"></i> 按搜索导出</button>
    <?php }?>
 </div>

        <div class="row">
          <?=$listpage?>
        </div>
      </form>
      
    <div class="xats"> 
        <br>
        <strong> 提示：</strong><br />
        &raquo; 此页面不包含运单账单，还需要在“运单账单”里操作<br>
        
        &raquo; 此账单的退费不显示，按扣费金额计算 (退费:由于某些原因已把费用充到账户,相当给会员多充值了款)<br>
        <font class=" popovers" data-trigger="hover" data-placement="top"  data-content="相当要收费90元，我们先找零10元给会员，会员再给我们100元">
        &nbsp;&nbsp; 如:会员消费记账是100元，后来给予优惠10元，就先退回10元到会员账户，到结算时会员给我们100元，实收就是90元
        </font>
        <br>
        
        &raquo; 操作流程：按需求搜索 > 生成账单 > 导出账单 > “付款销账”或会员自行充值销账 (完成)<br>
    </div>

    </div>
    <!--表格内容结束--> 
    
  </div>
</div>
<?php
$showbox=1;//是否用到 操作弹窗 (/public/showbox.php)
require_once($_SERVER['DOCUMENT_ROOT'].'/xingao/incluce/foot.php');
?>
