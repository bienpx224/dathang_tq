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
$pervar='tixian';//权限验证
require_once($_SERVER['DOCUMENT_ROOT'].'/xingao/incluce/session.php');
$headtitle="提现管理";
require_once($_SERVER['DOCUMENT_ROOT'].'/xingao/incluce/head.php');


//删除
$lx=par($_POST['lx']);
if($lx=='del')
{
	$date=par($_POST['date']);
	$key=par($_GET['key']);
	
	if(!CheckEmpty($date)){exit ("<script>alert('请填写要删除多少月之前 (填0则删除全部)！');goBack();</script>");}
	
	$where="1=1";
	
	if($date)
	{
		$start =strtotime('-'.$date.' Month');
		$where.=" and addtime<".$start;
	}
	if($key){$where.=" and (bank like '%{$key}%' or name like '%{$key}%' or userid='".CheckNumber($key,-0.1)."' or username like '%{$key}%')";}
	$where.=" and status<>1";//1处理中
	
	//开启“长期保存记录”时禁止删除的状态
	if($off_delbak)
	{
		$delbak_status="and status<>'2'";
		$delbak_ts='\\n已开启“长期保存记录”功能，如果是正常记录则不能删除';
	}
	
	$xingao->query("delete from tixian where {$where} {$delbak_status} ");//删除用户日志
	$rc=mysqli_affected_rows($xingao);
	if($rc>0){
		echo "<script>alert('删除{$key}记录成功,共删除{$rc}条！');</script>";
	}else{
		echo "<script>alert('{$LG['pptDelEmpty']}{$key}记录！{$delbak_ts}');</script>";
	}
}

//搜索
$where="1=1";
$so=(int)$_GET['so'];
if($so==1)
{
	$key=par($_GET['key']);
	$stime=par($_GET['stime']);
	$etime=par($_GET['etime']);
	$status=par($_GET['status']);
	if($key){$where.=" and (bank like '%{$key}%' or name like '%{$key}%' or userid='".CheckNumber($key,-0.1)."' or username like '%{$key}%')";}
	if($stime){$where.=" and addtime>='".strtotime($stime.' 00:00:00')."'";}
	if($etime){$where.=" and addtime<='".strtotime($etime.' 23:59:59')."'";}
	if(CheckEmpty($status)){$where.=" and status='{$status}'";}

	$search.="&so={$so}&key={$key}&stime={$stime}&etime={$etime}&status={$status}";
}

$order=' order by status asc,txid desc';//默认排序
include($_SERVER['DOCUMENT_ROOT'].'/public/orderby.php');//排序处理页


//echo $search;exit();
$query="select * from tixian where {$where} {$order}";

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
		<small>(<a href="?so=1<?=$search?>&key=" class="gray">查看所有会员</a>)</small>
		</h3>
      
      <!-- END PAGE TITLE & BREADCRUMB--> 
    </div>
  </div>
  <!-- END PAGE HEADER--> 
  
  <!-- BEGIN PAGE CONTENT-->
  <div class="tabbable tabbable-custom boxless">
    <div class="tab-content" style="padding:10px;"> 
      <!--搜索-->
      <div class="navbar navbar-default" role="navigation">
        
        <div class="collapse navbar-collapse navbar-ex1-collapse">
          <form class="navbar-form navbar-left"  method="get" action="?">
            <input name="so" type="hidden" value="1">
            <input name="my" type="hidden" value="<?=$my?>">
            <div class="form-group">
              <input type="text" name="key" class="form-control input-msmall popovers" data-trigger="hover" data-placement="right"  data-content="会员ID/会员名/银行/开户名 (可留空)" value="<?=$key?>">
            </div>
            <div class="form-group">
              <div class="col-md-0">
                <div class="input-group input-xmedium date-picker input-daterange" data-date-format="yyyy-mm-dd">
                  <input type="text" class="form-control input-small" name="stime" value="<?=$stime?>" title="申请时间">
                  <span class="input-group-addon">-</span>
                  <input type="text" class="form-control input-small" name="etime" value="<?=$etime?>"  title="申请时间">
                </div>
              </div>
            </div>
            <div class="form-group">
              <div class="col-md-0">
                <select  class="form-control input-small select2me" name="status" data-placeholder="状态">
                  <option></option>
                  <?=tixian_Status($status,1)?>
                </select>
              </div>
            </div>
            <button type="submit" class="btn btn-info"><i class="icon-search"></i> <?=$LG['search']//搜索?></button>
          </form>
        </div>
      </div>
      <form action="?<?=$search?>" method="post" name="XingAoForm">
        <input name="lx" type="hidden">
        <table class="table table-striped table-bordered table-hover" >
          <thead>
            <tr>
              <th align="center"><a href="?<?=$search?>&orderby=username&orderlx=" class="<?=orac('username')?>">会员</a></th>
              <th align="center"><a href="?<?=$search?>&orderby=money&orderlx=" class="<?=orac('money')?>">金额</a></th>
              <th align="center"><a href="?<?=$search?>&orderby=bank&orderlx=" class="<?=orac('bank')?>">银行</a></th>
              <th align="center"><a href="?<?=$search?>&orderby=account&orderlx=" class="<?=orac('account')?>">账号</a></th>
              <th align="center"><a href="?<?=$search?>&orderby=status&orderlx=" class="<?=orac('status')?>"><?=$LG['status']//状态?></a></th>
              <th align="center"><a href="?<?=$search?>&orderby=withtime&orderlx=" class="<?=orac('withtime')?>">处理时间</a></th>
              <th align="center"><a href="?<?=$search?>&orderby=addtime&orderlx=" class="<?=orac('addtime')?>">申请时间</a></th>
              <th align="center">操作</th>
            </tr>
          </thead>
          <tbody>
<?php
while($rs=$sql->fetch_array())
{
?>
            <tr class="odd gradeX <?=spr($rs['status'])==2?'gray2':''?>">
              <td align="center" valign="middle">
			  <?=cadd($rs['username'])?><br>
              <font class="gray2"><?=$rs['userid']?></font>
              </td>
              
              <td align="center" valign="middle"><?=spr($rs['money'])?> <?=$rs['currency']?></td>
              <td align="center" valign="middle"><?=cadd($rs['bank'])?><br>
                <font class="gray2">
                <?=cadd($rs['address'])?>
                </font></td>
              <td align="center" valign="middle"><?=cadd($rs['name'])?><br>
                <font class="gray2">
                <?=cadd($rs['account'])?>
                </font></td>
              <td align="center" valign="middle"><?=tixian_Status(spr($rs['status']))?></td>
              <td align="center" valign="middle"><?=DateYmd($rs['withtime'])?></td>
              <td align="center" valign="middle"><?=DateYmd($rs['addtime'])?></td>
              <td align="center" valign="middle"><?php if(spr($rs['status'])==1){?>
                <a href="form.php?lx=edit&txid=<?=$rs['txid']?>" class="btn btn-xs btn-info" target="_blank"><i class="icon-edit"></i> <?=$LG['showedit']?></a>
                <?php
					}
				?></td>
            </tr>
            <tr>
              <td colspan="8" align="left">
			  <?php
         $zhi=cadd($rs['content']);
         if($zhi){
		 ?>
                <div class="gray modal_border"> <strong>备注：</strong>
                  <?php 
			echo leng($zhi,100,"...");
			Modals($zhi,$title='备注',$time=$rs['addtime'],$nameid='content'.$rs['txid'],$count=100);
			?>
                </div>
                <?php }?>
                
                <!---->
                
                <?php
         $zhi=cadd($rs['reply']);
         if($zhi){
		 ?>
                <div class="gray modal_border"> <strong>回复：</strong>
                  <?php 
			echo leng($zhi,100,"...");
			Modals($zhi,$title='回复',$time=$rs['replytime'],$nameid='reply'.$rs['txid'],$count=100);
			?>
                </div>
                <?php }?>
                
                <!----></td>
            </tr>
            <?php
}
?>
          </tbody>
        </table>				
			
            
<!--底部操作按钮固定--> 

<style>body{margin-bottom:50px !important;}</style><!--后台不用隐藏,增高底部高度-->
<div align="right" class="fixed_btn" id="Autohidden">


<?php if(!$off_delbak||$off_delbak&&$status==1){?>
		<input type="text" class="form-control input-xsmall" name="date" title="删除X月之前 (填0则删除全部)">月
		<!--btn-danger--><button type="submit" class="btn btn-grey" onClick="
		document.XingAoForm.lx.value='del';
		return confirm('<?=!$off_delbak?'注意：此记录用于数据统计，删除后将影响统计结果！\r':''?>此操作不可恢复！确认要删除所选吗？ ');
		"><i class="icon-signin"></i> 按搜索删除 </button>
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
$sql->free(); //释放资源
require_once($_SERVER['DOCUMENT_ROOT'].'/xingao/incluce/foot.php');
?>
