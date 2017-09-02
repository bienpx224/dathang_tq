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
$pervar='off_settlement';require_once($_SERVER['DOCUMENT_ROOT'].'/xamember/incluce/session.php');
$headtitle=$LG['settlement.list_yundan_1'];
require_once($_SERVER['DOCUMENT_ROOT'].'/xamember/incluce/head.php');

if(!CheckEmpty($_GET['so'])){$_GET['so']=1;$_GET['tally']=1;}
$op2=par($_GET['op2']);

require($_SERVER['DOCUMENT_ROOT'].'/xingao/settlement/call/list_yundan_where.php');//条件处理,返回{$select} {$where} {$group} {$order}
require($_SERVER['DOCUMENT_ROOT'].'/public/orderby.php');//排序处理页

$query="select {$select} from yundan where {$where} {$Mmy} {$group} {$order}";
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
        
  <ul class="page-breadcrumb breadcrumb">
  <?php require_once($_SERVER['DOCUMENT_ROOT'].'/xamember/settlement/call/menu.php');?>
  </ul>
      
      <!-- END PAGE TITLE & BREADCRUMB--> 
    </div>
  </div>
  <!-- END PAGE HEADER--> 
  
  <!-- BEGIN PAGE CONTENT-->
  	<div class="tabbable tabbable-custom boxless">
		<ul class="nav nav-tabs">
			
			<li class="active" style="margin-right:30px;"><a href="list_yundan.php?<?=$search?>"><?=$LG['settlement.list_other_3'];//运单账单?></a></li>
			<li class=""><a href="list_other.php?so=1&op1=1&op2=1<?=$search?>"><?=$LG['settlement.list_other_4'];//其他账单?></a></li>
			
		</ul>
		<div class="tab-content" style="padding:10px;"> 
       <!--搜索-->
      <div class="navbar navbar-default" role="navigation">
        
        <div class="collapse navbar-collapse navbar-ex1-collapse">
          <form class="navbar-form navbar-left"  method="get" action="?">
            <input name="so" type="hidden" value="1">
            
            
            <div class="form-group">
              <input type="text" class="form-control input-small tooltips" name="lotno" data-container="body" data-placement="top" data-original-title="<?=$LG['settlement.list_yundan_4'];//批次号(多个批号用英文逗号,分开)?>" value="<?=$lotno?>" placeholder="<?=$LG['settlement.list_yundan_3'];//批次号?>">
            </div>
            
             
             <div class="form-group">
              <input type="text" class="form-control input-small tooltips" data-container="body" data-placement="top" data-original-title="<?=$LG['settlement.list_yundan_5'];//发件人/收件人?>" name="sf_name" value="<?=$sf_name?>" placeholder="<?=$LG['settlement.list_yundan_5'];//发件人/收件人?>">
            </div>
            
             <div class="form-group">
                <div class="col-md-0">
                     <select  class="form-control input-small select2me" name="tally" data-placeholder="<?=$LG['settlement.list_other_5'];//月结销账?>" >
                     <option></option>
                     <?=Tally($tally,1)?>
                  	 </select>
               </div>
              </div>
            <button type="submit"  class="btn btn-info"><i class="icon-search"></i> <?=$LG['settlement.list_other_12'];//搜索更新?></button>
           
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
                    <input type="text" class="form-control input-small" name="stime" value="<?=$stime?>" placeholder="<?=$LG['settlement.list_yundan_6'];//出库时间-起?>">
                    <span class="input-group-addon">-</span>
                    <input type="text" class="form-control input-small" name="etime" value="<?=$etime?>" placeholder="<?=$LG['settlement.list_yundan_7'];//出库时间-止?>">
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
              <th align="center"><a href="?<?=$search?>&orderby=ydh&orderlx=" class="<?=orac('ydh')?>"><?=$LG['settlement.list_yundan_9'];//运单号?></a></th>
              <th align="center"><a href="?<?=$search?>&orderby=f_name&orderlx=" class="<?=orac('f_name')?>"><?=$LG['settlement.list_yundan_10'];//发件人?></a></th>
              <th align="center"><a href="?<?=$search?>&orderby=s_name&orderlx=" class="<?=orac('s_name')?>"><?=$LG['settlement.list_yundan_11'];//收件人?></a></th>
               <th align="center"><a href="?<?=$search?>&orderby=weight&orderlx=" class="<?=orac('weight')?>"><?=$LG['settlement.list_yundan_12'];//重量?></a> </th>
              <th align="center"><a href="?<?=$search?>&orderby=money&orderlx=" class="<?=orac('money')?>"><?=$LG['settlement.list_yundan_13'];//总费用?></a></th>
              <th align="center"><?=$LG['settlement.list_yundan_14'];//优惠?></th>
              <th align="center"><?=$LG['settlement.list_yundan_15'];//退费?></th>
              <th align="center"><?=$LG['settlement.list_yundan_16'];//收费?></th>
              <th align="center"><a href="?<?=$search?>&orderby=tally&orderlx=" class="<?=orac('tally')?>"><?=$LG['settlement.list_yundan_17'];//销账状态?></a></th>
              <th align="center"><a href="?<?=$search?>&orderby=chukutime&orderlx=" class="<?=orac('chukutime')?>"><?=$LG['settlement.list_yundan_18'];//出库时间?></a></th>
            
            </tr>
          </thead>
          <tbody>
<?php
while($rs=$sql->fetch_array())
{
		//总计
		if($op2)
		{
			require($_SERVER['DOCUMENT_ROOT'].'/xingao/settlement/call/list_yundan_total.php');
		}
?>
            <tr class="odd gradeX">
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
				$sp_show1='';if($sp['money_co']>0){$sp_show1=$LG['settlement.list_yundan_25'].spr($sp['money_co']).$XAmc.'；';}
				$sp_show2='';if($sp['money_jf']>0){$sp_show2=$LG['settlement.list_yundan_26'].spr($sp['money_jf']).$XAmc.'；';}
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
					echo '<font class="popovers" data-trigger="hover" data-placement="top"  data-content="'.LGtag($LG['settlement.list_yundan_21'],'<tag1>=='.spr($sr).$XAmc.'').'">';/*原意是从账户重新扣除,但如果您已使用该款那您就要重新给*/
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
					echo '<font class="popovers" data-trigger="hover" data-placement="top"  data-content="'.$LG['settlement.list_yundan_22'].'">';
					echo ' <br><font class="gray2">('.LGtag($LG['settlement.list_yundan_23'],'<tag1>=='.spr($sr).$XAmc.'').')</font>';
					echo ' </font>';
				}
			  ?>
              </td>
            
              <td align="center"><?=Tally($rs['tally'])?></td>
              <td align="center"><?=DateYmd($rs['chukutime'])?></td>
            
            </tr>

			<?php if(!$op2){?>
            <tr>
            <td colspan="20" align="left" class="gray2 modal_border">
            <!--收费明细-->
            <?php 
			if(spr($rs['tax_money'])){echo $LG['settlement.list_yundan_24'].spr($rs['tax_money']).$XAmc;}//独立的税收
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
<script>/*$(function(){ Autohidden(); });*/</script> <!--拉到底时自动隐藏-->
<style>body{margin-bottom:40px !important;}</style><!--不用隐藏,增高底部高度-->
 
<div align="right" class="fixed_btn" id="Autohidden">



<a class="btn btn btn-info" href="excel_export.php?ex_tem=tem_yundan_all&<?=$search?>" target="_blank"><i class="icon-share"></i> <?=$LG['settlement.list_other_18'];//按搜索导出?></a>
</div>       

        <div class="row">
          <?=$listpage?>
        </div>
      </form>

  

   <div class="xats"> 
        <br>
        <strong> <?=$LG['pptInfo']?></strong><br />
        <font class=" popovers" data-trigger="hover" data-placement="top"  data-content="<?=$LG['settlement.list_other_21']?>">
        &nbsp;&nbsp; <?=$LG['settlement.list_other_22']?>
        </font>
        <br>
    </div>
    
    </div>
    <!--表格内容结束--> 
    
  </div>
</div>

<?php
$showbox=1;//是否用到 操作弹窗 (/public/showbox.php)
require_once($_SERVER['DOCUMENT_ROOT'].'/xamember/incluce/foot.php');
?>
