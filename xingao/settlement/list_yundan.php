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
$headtitle=cadd($_GET['username']).' 月结运单账单';
require_once($_SERVER['DOCUMENT_ROOT'].'/xingao/incluce/head.php');

if(!CheckEmpty($_GET['so'])){$_GET['so']=1;$_GET['tally']=1;}
$op2=par($_GET['op2']);

require($_SERVER['DOCUMENT_ROOT'].'/xingao/settlement/call/list_yundan_where.php');//条件处理,返回{$select} {$where} {$group} {$order}
require($_SERVER['DOCUMENT_ROOT'].'/public/orderby.php');//排序处理页

$query="select {$select} from yundan where {$where} {$Xwh} {$group} {$order}";
//分页处理 : $line=20;$page_line=15;//$line=-1则不分页(不设置则默认)
require($_SERVER['DOCUMENT_ROOT'].'/public/page.php');
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
			
			<li class="active" style="margin-right:30px;"><a href="list_yundan.php?<?=$search?>">运单账单</a></li>
			<li class=""><a href="list_other.php?so=1&op1=1&op2=1<?=$search?>">其他账单</a></li>
			
		</ul>
		<div class="tab-content" style="padding:10px;"> 
       <!--搜索-->
      <div class="navbar navbar-default" role="navigation">
        
        <div class="collapse navbar-collapse navbar-ex1-collapse">
          <form class="navbar-form navbar-left"  method="get" action="?">
            <input name="so" type="hidden" value="1">
            
            <div class="form-group">
              <input type="text" class="form-control input-small tooltips" name="lotno" data-container="body" data-placement="top" data-original-title="批次号(多个批号用英文逗号,分开)" value="<?=$lotno?>" placeholder="批次号">
            </div>
            
             <div class="form-group">
              <input type="text" class="form-control input-small tooltips" data-container="body" data-placement="top" data-original-title="会员ID/会员名" name="username" autocomplete="off" value="<?=$username?>" placeholder="会员ID/会员名">
            </div>
             <div class="form-group">
              <input type="text" class="form-control input-small tooltips" data-container="body" data-placement="top" data-original-title="发件人/收件人" name="sf_name" value="<?=$sf_name?>" placeholder="发件人/收件人">
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
<?php 
    //$classtag='so';//标记:同个页面,同个$classtype时,要有不同标记
    //$classtype=1;//分类类型
    //$classid=8;//已保存的ID
    require($_SERVER['DOCUMENT_ROOT'].'/public/classify.php');
?>
            </div>
          
            <div class="form-group">
                <div class="col-md-0">
                  <div class="input-group input-xmedium date-picker input-daterange" data-date-format="yyyy-mm-dd">
                    <input type="text" class="form-control input-small" name="stime" value="<?=$stime?>" placeholder="出库时间-起">
                    <span class="input-group-addon">-</span>
                    <input type="text" class="form-control input-small" name="etime" value="<?=$etime?>" placeholder="出库时间-止">
                  </div>
                 </div>
              </div>
              
               <div class="form-group">
                   <div class="col-md-0">
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
              <th align="center"><a href="?<?=$search?>&orderby=username&orderlx=" class="<?=orac('username')?>">会员</a></th>
              <th align="center"><a href="?<?=$search?>&orderby=ydh&orderlx=" class="<?=orac('ydh')?>">运单号</a></th>
              <th align="center"><a href="?<?=$search?>&orderby=f_name&orderlx=" class="<?=orac('f_name')?>">发件人</a></th>
              <th align="center"><a href="?<?=$search?>&orderby=s_name&orderlx=" class="<?=orac('s_name')?>">收件人</a></th>
               <th align="center"><a href="?<?=$search?>&orderby=weight&orderlx=" class="<?=orac('weight')?>">重量</a> </th>
              <th align="center"><a href="?<?=$search?>&orderby=money&orderlx=" class="<?=orac('money')?>">总费用</a></th>
              <th align="center">优惠</th>
              <th align="center">退费</th>
              <th align="center">收费</th>
              <th align="center"><a href="?<?=$search?>&orderby=tally&orderlx=" class="<?=orac('tally')?>">销账状态</a></th>
              <th align="center"><a href="?<?=$search?>&orderby=chukutime&orderlx=" class="<?=orac('chukutime')?>">出库时间</a></th>
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
		
		//总计
		if($op2)
		{
			require($_SERVER['DOCUMENT_ROOT'].'/xingao/settlement/call/list_yundan_total.php');
		}
	}
?>
            <tr class="odd gradeX">
              
              <td <?php if(!$op2){?>rowspan="2"<?php }?> align="center">
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
			  <a href="../yundan/show.php?ydid=<?=$rs['ydid']?>" target="_blank"><?=cadd($rs['ydh'])?></a>
              </td>
            
              <td align="center"><?=cadd($rs['f_name'])?></td>
              <td align="center"><?=cadd($rs['s_name'])?></td>
              <td align="center"><?=spr($rs['weight']).$XAwt?></td>
            
              <td align="center">
              <!--总费用(包括税费)-->
			  <?=$all_fee=spr($rs['money']+$rs['tax_money'])?><?=$XAmc?>
              </td>
            
              <td align="center">
			  <!--优惠-->
              <?php 
			  $sp=SettlementPreferential($fromtable='yundan',$rs['ydid']);
			  $reduce=spr($sp['money_co']+$sp['money_jf']);
			  if($reduce>0)
			  {
				$sp_show1='';if($sp['money_co']>0){$sp_show1='优惠券/折扣券'.spr($sp['money_co']).$XAmc.'；';}
				$sp_show2='';if($sp['money_jf']>0){$sp_show2='积分抵消'.spr($sp['money_jf']).$XAmc.'；';}
				$sp_show.="{$sp_show1}{$sp_show2}";
			  ?>
                  <font class="tooltips" data-container="body" data-placement="top" data-original-title="<?=$sp_show?>"><?=$reduce.$XAmc?></font>
              <?php }?>
              </td>

			  <!--退费-->
              <td align="center">
             <?php 
				$sr=SettlementRefund($fromtable='yundan',$rs['ydid']);
				if($sr>0)
				{
					echo '<font class="popovers" data-trigger="hover" data-placement="top"  data-content="由于某些原因已把费用充到账户 (相当给会员多充值了'.spr($sr).$XAmc.',因此在收费时要重新扣除)">';/*原意是从账户重新扣除,但如果会员已使用该款那会员就要重新给*/
					echo '-'.spr($sr).$XAmc;
					echo '</font>';
				}
			  ?>
              </td>
            
 			  <!--收费-->
             <td align="center">
              <?php 
				$sc=SettlementCharge($fromtable='yundan',$rs['ydid']);
				echo spr($sc-$sr).$XAmc;
				
				if($sr>0)
				{
					echo '<font class="popovers" data-trigger="hover" data-placement="top"  data-content="由于有退费到账户,因此要重新扣除">';
					echo ' <br><font class="gray2">(另扣'.spr($sr).$XAmc.')</font>';
					echo ' </font>';
				}
			  ?>
              </td>
            
              <td align="center"><?=Tally($rs['tally'])?></td>
              <td align="center"><?=DateYmd($rs['chukutime'])?></td>
            
              <td align="center">
			  <!--操作-->
<?php if($userid_nwe){?>

	<?php 
    if(permissions('settlement_ed','','manage',1) ){
		$mr=FeData('member','settlement_yundan_bill,settlement_yundan_money',"userid='{$rs['userid']}'");
		$settlement_bill=$mr['settlement_yundan_bill'];
		?>
    
		<?php if($settlement_bill){?>
            <!--显示销账按钮-开始-->
            <a href="pay.php?userid=<?=$rs['userid']?>&fromtable=yundan"  target="XingAobox" class="btn btn-xs btn-danger showdiv"><i class="icon-money"></i> 销账</a>
            <!--显示销账按钮-结束-->
            
            <!--显示账单按钮-开始-->
            <?php if(is_json($settlement_bill)){$bi=(array)json_decode($settlement_bill,true);//不能加cadd?>
                <a href="?username=<?=cadd($rs['username'])?>&classid=<?=cadd($bi['classid'])?>&lotno=<?=cadd($bi['lotno'])?>&sf_name=<?=cadd($bi['sf_name'])?>&tally=1&stime=<?=cadd($bi['stime'])?>&etime=<?=cadd($bi['etime'])?>"  class="btn btn-xs btn-default" target="_blank"><i class="icon-file-text"></i> 账单</a>
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
    document.XingAoForm.ex_tem.value='tem_yundan_all';
    "><i class="icon-share"></i> 按搜索导出</button>
    <?php }?>
    
    <a class="btn btn-xs btn-default" href="list_other.php?<?=$search?>&username=<?=$rs['userid']?>" target="_blank">其他账单</a>

<?php }?>
              </td>
                 
            </tr>

			<?php if(!$op2){?>
            <tr>
            <td colspan="20" align="left" class="gray2 modal_border">
            <!--收费明细-->
            <?php 
			if(spr($rs['tax_money'])){echo '税收:'.spr($rs['tax_money']).$XAmc;}//独立的税收
            $show_small=1;
            require($_SERVER['DOCUMENT_ROOT'].'/xingao/yundan/call/money_content.php');
            ?>
            </td>
            </tr>
            <?php }?>
						
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
<input name="fromtable" type="hidden" value="yundan">
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
        "><i class="icon-signin"></i> 按搜索生成待销账单给会员</button>
    <?php }?>	
    
    <!--************导出************-->
    <?php if(permissions('settlement_se','','manage',1) ){?>
        <select class="form-control select2me input-small" data-placeholder="导出模板" name="ex_tem">
        <option></option>
        <?php settlement_yundan_excel_export('',1)?>
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
        <font class="red2">
        &raquo; 注意：一旦销账将不可再变更费用(包括税费)，因此请确定该运单所有费用(包括税费)已确认清楚再生成账单<br>
        </font>
        &raquo; 此页面不包含其他账单，还需要在“其他账单”里操作<br>
        &raquo; 该页面有仓库权限限制，只显示您拥有该仓库权限的运单账单<br>
        
        <font class=" popovers" data-trigger="hover" data-placement="top"  data-content="相当要收费90元，我们先找零10元给会员，会员再给我们100元">
       &raquo; 退费：比如会员消费记账是100元，后来给予优惠10元，就先退回10元到会员账户，到结算时会员给我们100元，实收就是90元
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
