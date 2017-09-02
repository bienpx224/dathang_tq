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
$pervar='member_ed,member_se,member_de';require_once($_SERVER['DOCUMENT_ROOT'].'/xingao/incluce/session.php');
$headtitle=cadd($_GET['key'])." 会员扣费记录";
require_once($_SERVER['DOCUMENT_ROOT'].'/xingao/incluce/head.php');

//删除
$lx=par($_POST['lx']);
if($lx=='del')
{
	//开启“长期保存记录”时禁止删除的状态
	if($off_delbak){exit ("<script>alert('已开启“长期保存记录”功能，不能删除记录');goBack();</script>");}

	$date=par($_POST['date']);
	$key=par($_GET['key']);
	
	if(!CheckEmpty($date)){exit ("<script>alert('请填写要删除多少月之前 (填0则删除全部)！');goBack();</script>");}
	
	$where="tally=0";
	
	if($date)
	{
		$start =strtotime('-'.$date.' Month');
		$where.=" and addtime<".$start;
	}
	if($key){$where.=" and (userid='".CheckNumber($key,-0.1)."' or username='{$key}' or fromid='".CheckNumber($key,-0.1)."' or title like '%{$key}%')";}

	$xingao->query("delete from money_kfbak where {$where} ");//删除用户日志
	$rc=mysqli_affected_rows($xingao);
	if($rc>0){
		echo "<script>alert('删除{$key}记录成功,共删除{$rc}条！');</script>";
	}else{
		echo "<script>alert('{$LG['pptDelEmpty']}{$key}记录！');</script>";
	}
}

//搜索

$where="1=1";
$so=(int)$_GET['so'];
if($so)
{
	$key=par($_GET['key']);
	$type=par($_GET['type']);
	$stime=par($_GET['stime']);
	$etime=par($_GET['etime']);
	$status=par($_GET['status']);
	$fromtable=par($_GET['fromtable']);
	$tally=par($_GET['tally']);
	$showTyp=spr($_GET['showTyp']);
	$fromCurrency=par($_GET['fromCurrency']);
	$toCurrency=par($_GET['toCurrency']);
	
	if($type){
		switch($type)
		{
			case 'yundan':
				$where.=" and type in (21,22,51)";
			break;
			case 'baoguo':
				$where.=" and ((type>=30 and type<=50) or type in (52,54,55))";
			break;
			case 'manage':
				$where.=" and type>=51 and type<=60";
			break;
			case 'qujian':
				$where.=" and type in (2,53)";
			break;
			case 'other':
				$where.=" and type=100";
			break;
			default:
				$where.=" and type='{$type}'";
		}
	}
	
	if($key){$where.=" and (userid='".CheckNumber($key,-0.1)."' or username='{$key}' or fromid='".CheckNumber($key,-0.1)."' or title like '%{$key}%')";}
	if($stime){$where.=" and addtime>='".strtotime($stime.' 00:00:00')."'";}
	if($etime){$where.=" and addtime<='".strtotime($etime.' 23:59:59')."'";}
	if(CheckEmpty($fromtable)){$where.=" and fromtable='{$fromtable}'";}
	if(CheckEmpty($tally)){$where.=" and tally='{$tally}'";}
	if($fromCurrency){$where.=" and fromCurrency='{$fromCurrency}'";}
	if($toCurrency){$where.=" and toCurrency='{$toCurrency}'";}

	$search.="&so={$so}&key={$key}&type={$type}&stime={$stime}&etime={$etime}&fromtable={$fromtable}&fromid={$fromid}&tally={$tally}&showTyp={$showTyp}&fromCurrency={$fromCurrency}&toCurrency={$toCurrency}";
}

$timequerycall=1;
$field="addtime";
include($_SERVER['DOCUMENT_ROOT'].'/public/timequery.php');//按时间快速查询

$order=' order by id desc';//默认排序
include($_SERVER['DOCUMENT_ROOT'].'/public/orderby.php');//排序处理页


$query="select * from money_kfbak where {$where} {$order}";

//分页处理
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
		<small>(<a href="?so=1<?=$search?>&key=" class="gray">查看所有会员</a>)</small>
		</h3>
      
      <!-- END PAGE TITLE & BREADCRUMB--> 
    </div>
  </div>
  <!-- END PAGE HEADER--> 
  
  <!-- BEGIN PAGE CONTENT-->
  <div class="portlet tabbable">
    <div class="portlet-body" style="padding:10px;"> 
      <!--搜索-->
      <div class="navbar navbar-default" role="navigation">
        
        <div class="collapse navbar-collapse navbar-ex1-collapse">
          <form class="navbar-form navbar-left"  method="get" action="?">
            <input name="so" type="hidden" value="1">
            <div class="form-group">
              <input type="text" name="key" class="form-control input-msmall popovers" data-trigger="hover" data-placement="right"  data-content="会员ID/会员名/使用说明/订单ID" value="<?=$key?>">
            </div>
              <div class="form-group">
              <div class="col-md-0">
                <select  class="form-control input-small select2me" name="type" data-placeholder="类型">
                  <option></option>
                  <option value="yundan"  <?=$type=='yundan'?' selected':''?>>运单</option>
                  <option value="baoguo"  <?=$type=='baoguo'?' selected':''?>>包裹</option>
                  <option value="1"  <?=$type=='1'?' selected':''?>>商城</option>
                  <option value="3"  <?=$type=='3'?' selected':''?>>代购</option>
                  <option value="qujian"  <?=$type=='qujian'?' selected':''?>>取件</option>
				  
                  <option value="manage"  <?=$type=='manage'?' selected':''?>>后台操作</option>
                  <option value="other"  <?=$type=='other'?' selected':''?>>其他</option>
                </select>
              </div>
            </div>
                
             <div class="form-group">
                <div class="col-md-0">
                     <select  class="form-control input-small select2me" name="fromtable" data-placeholder="用途" >
                     <option></option>
                	 <?=fromtableName($fromtable,1)?>
                  	 </select>
               </div>
              </div>
                 
             <div class="form-group">
                <div class="col-md-0">
                     <select  class="form-control input-small select2me" name="tally" data-placeholder="月结" >
                     <option></option>
                       <?=Tally($tally,1)?>
                     </select>
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
                    <input type="text" class="form-control input-small" name="stime" value="<?=$stime?>" placeholder="扣款时间-起">
                    <span class="input-group-addon">-</span>
                    <input type="text" class="form-control input-small" name="etime" value="<?=$etime?>" placeholder="扣款时间-止">
                  </div>
                 </div>
              </div>
            
            <button type="submit" class="btn btn-info"><i class="icon-search"></i> <?=$LG['search']//搜索?></button>
          </form>
        </div>
        
        <?php
		$timequeryshow=1;
		$searchtime="&so={$so}&key={$key}";
		include($_SERVER['DOCUMENT_ROOT'].'/public/timequery.php');//按时间快速查询
		?>
      </div>
      <form action="?<?=$search?>" method="post" name="XingAoForm">
        <input name="lx" type="hidden">
        <table class="table table-striped table-bordered table-hover" >
          <thead>
            <tr>
              <th align="center"><a href="?<?=$search?>&orderby=username&orderlx=" class="<?=orac('username')?>">会员</a>/<a href="?<?=$search?>&orderby=userid&orderlx=" class="<?=orac('userid')?>">ID</a></th>
              
              <th align="center"><a href="?<?=$search?>&orderby=type&orderlx=" class="<?=orac('type')?>">扣费类型</a></th>
               <th align="center"><a href="?<?=$search?>&orderby=fromtable&orderlx=" class="<?=orac('fromtable')?>">用途</a> </th>
               <th align="center"><a href="?<?=$search?>&orderby=title&orderlx=" class="<?=orac('title')?>">使用说明</a> </th>
              
              <th align="center"><a href="?<?=$search?>&orderby=toMoney&orderlx=" class="<?=orac('toMoney')?>">本币</a></th>
              <th align="center"><a href="?<?=$search?>&orderby=fromMoney&orderlx=" class="<?=orac('fromMoney')?>">原币</a></th>
              <th align="center"><a href="?<?=$search?>&orderby=remain&orderlx=" class="<?=orac('remain')?>">账户</a></th>
              <th align="center"><a href="?<?=$search?>&orderby=operator&orderlx=" class="<?=orac('operator')?>">操作员ID</a></th>
              <th align="center"><a href="?<?=$search?>&orderby=addtime&orderlx=" class="<?=orac('addtime')?>">扣费时间</a></th>
            
            </tr>
          </thead>
          <tbody>
<?php
if($showTyp==0){
	while($rs=$sql->fetch_array())
	{
?>
        <tr class="odd gradeX">
          <td align="center"><?=cadd($rs['username'])?><br>
            <font class="gray2">
            <?=$rs['userid']?>
            </font>
          </td>
        
          <td align="center"><?=money_kf($rs['type'])?></td>
          
          <td align="center">
          <?=$rs['fromtable']?fromtableName($rs['fromtable']).'ID:'.$rs['fromid'].'':''?> </td>
          
          <td align="center">
          <a <?=fromtableUrl($rs['fromtable'],$rs['fromid'])?>  class="popovers" data-trigger="hover" data-placement="top"  data-content="<?=cadd($rs['title'])?><?=$rs['content']?'：'.TextareaToCo($rs['content']):''?>">
          <?=$rs['title']?leng($rs['title'],20,'...'):'说明'?>
          </a>
          </td>
          
          <td align="center"><?=spr($rs['toMoney']).cadd($rs['toCurrency'])?><?=Tally($rs['tally'])?></td>
          <td align="center"><?=spr($rs['fromMoney']).cadd($rs['fromCurrency'])?></td>

          <td align="center"><?=spr($rs['remain']).cadd($rs['toCurrency'])?></td>
          <td align="center"><?=cadd($rs['operator'])?></td>
          <td align="center"><?=DateYmd($rs['addtime'],1)?></td>
        </tr>
<?php
	$toMoney[$rs['toCurrency']]+=$rs['toMoney'];
	$fromMoney[$rs['fromCurrency']]+=$rs['fromMoney'];
	}//while($rs=$sql->fetch_array())
	
}elseif($showTyp==1){
	
	//月计
	if($stime&&$etime)
	{
		if(date('Y',strtotime($stime))!=date('Y',strtotime($etime))){exit ("<script>alert('月计类型时只能选择同一年的时间！');goBack();</script>");}
	}
	
	$y=date('Y',time());
	$sm=1;
	$em=date('m',time()); 
	if($stime){$sm=date('m',strtotime($stime)); $y=date('Y',strtotime($stime));}
	if($etime){$em=date('m',strtotime($etime));}
	
	//按月输出
	for ($m=$sm; $m<=$em; $m++)
	{
		$where_m='';
		$where_m.=" and addtime>='".strtotime($y.'-'.$m)."'";
		$where_m.=" and addtime<='".strtotime($y.'-'.$m.'-'.date('t',strtotime($y.'-'.$m)).' 23:59:59')."'";
?> 
     <tr class="odd gradeX">
      <td align="center"></td>
      <td align="center"></td>
      <td align="center"></td>
      <td align="center"></td>
      
      <td align="center">
        <strong>
        <?php 
        $query="select count(*) as total,sum(`toMoney`) as toMoney,toCurrency from money_kfbak where {$where} {$where_m}  group by toCurrency";
        $sql=$xingao->query($query);
        while($rs=$sql->fetch_array())
        {
		  if(spr($rs['toMoney'])){echo spr($rs['toMoney']).' '.cadd($rs['toCurrency']).'<br>';}
        }
        ?>
        </strong>
        </td>
        
        <td align="center">
        <strong>
          <?php 
          $query="select count(*) as total,sum(`fromMoney`) as fromMoney,fromCurrency from money_kfbak where {$where} {$where_m} group by fromCurrency";
          $sql=$xingao->query($query);
          while($rs=$sql->fetch_array())
          {
              if(spr($rs['fromMoney'])){echo spr($rs['fromMoney']).' '.cadd($rs['fromCurrency']).'<br>';}
          }
          ?>
        </strong>
        </td>
      <td align="center"></td>
      <td align="center"></td>
      <td align="center"><?=$y.'-'.$m?></td>
    </tr>
<?php
	}//for ($m=$sm; $m<=$em; $m++)
}//if($showTyp==1){
?>



	<?php if($showTyp==0){?>
           <tr class="odd gradeX">
            <td align="center"><strong>本页总计</strong></td>
            <td align="center"></td>
            <td align="center"></td>
            <td align="center"></td>
              
              <th align="center" valign="middle">
              <strong>
			<?php 
            $arr=ToArr($openCurrency,',');
            if($arr)
            {
                foreach($arr as $arrkey=>$value)
                {
                    if(spr($toMoney[$value])){echo $toMoney[$value].' '.$value.'<br>';}
                }
            }
            ?>
			 </strong>
             </th>
             
              <th align="center" valign="middle">
              <strong>
			<?php 
            $arr=ToArr($openCurrency,',');
            if($arr)
            {
                foreach($arr as $arrkey=>$value)
                {
                    if(spr($fromMoney[$value])){echo $fromMoney[$value].' '.$value.'<br>';}
                }
            }
            ?>
			 </strong>
             </th>

            <td align="center"></td>
            <td align="center"></td>
            <td align="center"></td>
          </tr>
   <?php }?>


           <thead>
            <tr class="odd gradeX">
              <th align="center"><strong>全部总计</strong></th>
              <th align="center"></th>
              <th align="center"></th>
              <th align="center"></th>
              
              <th align="center">
              <strong>
<?php 
$query="select count(*) as total,sum(`toMoney`) as toMoney,toCurrency from money_kfbak where {$where} group by toCurrency";
$sql=$xingao->query($query);
while($rs=$sql->fetch_array())
{
	if(spr($rs['toMoney'])){echo spr($rs['toMoney']).' '.cadd($rs['toCurrency']).'<br>';}
}
?>
			 </strong>
             </th>
             
              <th align="center">
              <strong>
<?php 
$query="select count(*) as total,sum(`fromMoney`) as fromMoney,fromCurrency from money_kfbak where {$where}  group by fromCurrency";
$sql=$xingao->query($query);
while($rs=$sql->fetch_array())
{
	if(spr($rs['fromMoney'])){echo spr($rs['fromMoney']).' '.cadd($rs['fromCurrency']).'<br>';}
}
?>
              </strong>
              </th>
              
              <th align="center"></th>
              <th align="center"></th>
              <th align="center"></th>
            </tr>
            </thead>


          </tbody>
        </table>				
			
            
<!--底部操作按钮固定--> 

<style>body{margin-bottom:50px !important;}</style><!--后台不用隐藏,增高底部高度-->
<div align="right" class="fixed_btn" id="Autohidden">


<?php if(permissions('member_ot','','manage',1)){?>
      <input type="hidden" name="where" value="<?=$where?>">
      <input type="hidden" name="table" value="money_kfbak">

      <!--btn-info--><button type="submit" class="btn btn-grey tooltips" data-container="body" data-placement="top" data-original-title="全部分页都导出"
      onClick="
      document.XingAoForm.action='excel_export_bak.php';
      document.XingAoForm.lx.value='export';
      document.XingAoForm.target='_blank';
      "><i class="icon-signin"></i> 导出记录 </button>
    <?php }?>
    
    
    
    <?php if(!$off_delbak){?>
            <input type="text" class="form-control input-xsmall tooltips" data-container="body" data-placement="top" data-original-title="删除X月之前 (填0则删除全部)"  name="date">月
            
            <!--btn-danger--><button type="submit" class="btn btn-grey" onClick="
            document.XingAoForm.action='?<?=$search?>';
            document.XingAoForm.lx.value='del';
            return confirm('注意：此记录用于数据统计，删除后将影响统计结果！\r此操作不可恢复！确认要删除吗？ ');
            "><i class="icon-signin"></i> 删除记录 </button>
    <?php }?>

</div>

        <div class="row">
          <?=$listpage?>
        </div>
      </form>
    </div>
    <!--表格内容结束--> 
    
  </div>
</div>
<?php
require_once($_SERVER['DOCUMENT_ROOT'].'/xingao/incluce/foot.php');
?>
