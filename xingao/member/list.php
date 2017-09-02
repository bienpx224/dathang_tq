<?php
/*
软件著作权：=====================================================
软件名称：兴奥全球代购网站管理系统(简称：兴奥代购系统)
著作权人：广西兴奥网络科技有限责任公司
软件登记号：2016SR041223
网址：www.xingaowl.com
本系统已在中华人民共和国国家版权局注册，著作权受法律及国际公约保护！
版权所有，未购买严禁使用，未经书面许可严禁开发衍生品，违反将追究法律责任！
*/
require_once($_SERVER['DOCUMENT_ROOT'].'/public/config_top.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/public/html.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/public/function.php');
$pervar='member_ed,member_se';require_once($_SERVER['DOCUMENT_ROOT'].'/xingao/incluce/session.php');
$headtitle="会员管理";
require_once($_SERVER['DOCUMENT_ROOT'].'/xingao/incluce/head.php');

//处理:1125

$member_cp=0;if(permissions('member_cp',0,'manage',1)){$member_cp=1;}//是否有会员高级管理权限
$member_co=0;if(permissions('member_co',0,'manage',1)||$member_cp){$member_co=1;}//是否有查看会员联系方式权限
$settlement=0;if(permissions('settlement_se,settlement_ed',0,'manage',1)){$settlement=1;}//是否月结管理权限

//搜索
$where="1=1";
$so=(int)$_GET['so'];
if($so==1)
{
	$key=par($_GET['key']);
	$groupid=par($_GET['groupid']);
	$checked=par($_GET['checked']);
	$certification=par($_GET['certification']);
	$payment_settlement=par($_GET['payment_settlement']);
	
	if($key){$where.=" and (userid='".CheckNumber($key,-0.1)."' or useric='{$key}' or username like '%{$key}%'  or truename like '%{$key}%'   or nickname like '%{$key}%'  or preip='{$key}' or CustomerService='{$key}' )";}
	if(CheckEmpty($groupid)){$where.=" and groupid='{$groupid}'";}
	if(CheckEmpty($payment_settlement))
	{
		if($payment_settlement){$where.=" and (settlement_yundan_bill<>'' or settlement_other_bill<>'')";}else{$where.=" and settlement_all_money<>'0' and settlement_yundan_bill='' and settlement_other_bill='' ";}
	}
	if($checked==2){$where.=" and api='1'";}elseif(CheckEmpty($checked)){$where.=" and checked='{$checked}'";}
	if($certification==2){$where.=" and certification_for='1' and certification='0'";}elseif(CheckEmpty($certification)){$where.=" and certification='{$certification}'";}

	$search.="&so={$so}&key={$key}&groupid={$groupid}&checked={$checked}";
}


$order=' order by userid desc';//默认排序
include($_SERVER['DOCUMENT_ROOT'].'/public/orderby.php');//排序处理页


//echo $search;exit();
$query="select * from member where {$where} {$myMember} {$order}";

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
        
      <ul class="page-breadcrumb breadcrumb">
      <?php if(permissions('member_ed','','manage',1) ){?>
        <button type="button" class="btn btn-info" onClick="window.open('form.php');"><i class="icon-plus"></i> <?=$LG['add']?> </button>
        
        <a class="btn btn-info" href="/xingao/iframe.php?typ=member_excel_import"><i class="icon-plus"></i> 批量导入 </a>
      <?php }?>  
      </ul>
      
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
              <input type="text" name="key" class="form-control input-msmall popovers" data-trigger="hover" data-placement="right"  data-content="会员ID/入库码/昵称/会员名/姓名/客服/最后登录IP (可留空)" value="<?=$key?>">
            </div>
            <div class="form-group">
              <div class="col-md-0">
                <select  class="form-control input-medium select2me" name="groupid" data-placeholder="所属分类">
                  <option></option>
                  <?php
$query2="select groupid,groupname{$LT} from member_group order by  myorder desc,groupname{$LT} desc,groupid desc";
$sql2=$xingao->query($query2);
while($rs2=$sql2->fetch_array())
{
?>
                  <option value="<?=$rs2['groupid']?>"  <?=$rs2['groupid']==$groupid?' selected':''?>>
                  <?=$rs2['groupname'.$LT]?>
                  </option>
                  <?php
}
?>
                </select>
              </div>
            </div>
            <div class="form-group">
              <div class="col-md-0">
                <select  class="form-control input-small select2me" name="checked" data-placeholder="状态">
                  <option></option>
                  <option value="0"  <?=$checked=='0'?' selected':''?>><?=$LG['close']?> </option>
                  <option value="1"  <?=$checked=='1'?' selected':''?>><?=$LG['checkedOn']//开通?></option>
                  <option value="2"  <?=$checked=='2'?' selected':''?>>支持API</option>
                </select>
              </div>
            </div>
            <div class="form-group">
              <div class="col-md-0">
                <select  class="form-control input-small select2me" name="certification" data-placeholder="认证">
                  <option></option>
                  <option value="2"  <?=$certification=='2'?' selected':''?>>待审核</option>
                  <option value="0"  <?=$certification=='0'?' selected':''?>>未认证</option>
                  <option value="1"  <?=$certification=='1'?' selected':''?>>已认证</option>
                </select>
              </div>
            </div> 
            
            <div class="form-group">
              <div class="col-md-0">
                <select  class="form-control input-small select2me" name="payment_settlement" data-placeholder="月结账单">
                  <option></option>
                  <option value="1"  <?=$payment_settlement=='1'?' selected':''?>>待销账单</option>
                  <option value="0"  <?=$payment_settlement=='0'?' selected':''?>>已销账单</option>
                </select>
              </div>
            </div>
            
            <button type="submit" class="btn btn-info"><i class="icon-search"></i> <?=$LG['search']//搜索?></button>
          </form>
        </div>
      </div>
      <form action="save.php" method="post" name="XingAoForm">
        <input name="lx" type="hidden">
        <input name="checked" type="hidden">
				<table class="table table-striped table-bordered table-hover" style="border:0px solid #ddd;">
          <thead>
            <tr>
              <th align="center" class="table-checkbox"> <input type="checkbox"  id="aAll" onClick="chkAll(this)"  title="全选/取消"/>
              </th>
              <th align="center">
			  <a href="?<?=$search?>&orderby=username&orderlx=" class="<?=orac('username')?>">会员</a> /
			  <a href="?<?=$search?>&orderby=userid&orderlx=" class="<?=orac('userid')?>">ID</a>
			  
			  </th>
             
              <th align="center">
			  <a href="?<?=$search?>&orderby=gender&orderlx=" class="<?=orac('gender')?>">性别</a> |  <a href="?<?=$search?>&orderby=groupid&orderlx=" class="<?=orac('groupid')?>">分类</a> | <a href="?<?=$search?>&orderby=CustomerService&orderlx=" class="<?=orac('CustomerService')?>">客服</a></th>
              
              
              <th align="center"><a href="?<?=$search?>&orderby=lasttime&orderlx=" class="<?=orac('lasttime')?>" title="上次登录时间">登录</a> | <a href="?<?=$search?>&orderby=addtime&orderlx=" class="<?=orac('addtime')?>" title="注册时间">注册</a> </th>
             
              <th align="center"><a href="?<?=$search?>&orderby=money&orderlx=" class="<?=orac('money')?>">账户</a> | <a href="?<?=$search?>&orderby=xiaofei_t&orderlx=" class="<?=orac('xiaofei_t')?>">消费</a></th>
               <th align="center"><a href="?<?=$search?>&orderby=settlement_all_money&orderlx=" class="<?=orac('settlement_all_money')?>">月结账单</a> </th>
               <th align="center">优惠券/折扣券</th>
               
              <?php if ($off_integral==1){ ?>
              <th align="center"><a href="?<?=$search?>&orderby=integral&orderlx=" class="<?=orac('integral')?>">积分</a></th>
              <?php }?>
              <th align="center"><a href="?<?=$search?>&orderby=yundan_tsl&orderlx=" class="<?=orac('yundan_tsl')?>" title="出库后才算">转运</a></th>

              <th align="center">操作</th>
            </tr>
          </thead>
          <tbody>
<?php
while($rs=$sql->fetch_array())
{
?>
            <tr class="odd gradeX <?=!$rs['checked']?'gray2':''?>">
              <td rowspan="2" align="left"><input type="checkbox" id="a" onClick="chkColor(this);" name="userid[]" value="<?=$rs['userid']?>" /></td>
              <td align="center">
			    <font class="tooltips" data-container="body" data-placement="top" data-original-title="登录账号">
                <strong><?=cadd($rs['username'])?></strong>
                  <font class="red2"><?=!$rs['checked']?'(未开通)':'';?></font>
                </font>
                
			    <br>
			    <font class=" tooltips" data-container="body" data-placement="top" data-original-title="会员ID">
                <?=cadd($rs['userid'])?>   
                </font>
				<span class="xa_sep"> | </span>
				<font class=" tooltips" data-container="body" data-placement="top" data-original-title="会员入库码"><?=cadd($rs['useric'])?></font>
				<br>	
                			
                <font class="gray2 tooltips" data-container="body" data-placement="top" data-original-title="会员姓名">
                <?=cadd($rs['truename'])?>	
                <?=$rs['enname']?'('.cadd($rs['enname']).')':''?>	
                </font>
                <span class="xa_sep"> | </span>
                <font class="gray2 tooltips" data-container="body" data-placement="top" data-original-title="昵称">
                <?=cadd($rs['nickname'])?>
                </font>
				 
				</td>
              <td align="center">
			  <?=Gender($rs['gender'])?>
              <br>
			  <?=$member_per[$rs['groupid']]['groupname']?>
			  <br>
 <a class="popovers" data-trigger="hover" data-placement="top"  data-content="<?=CustomerService($rs['CustomerService'],2)?> " href="javascript:void(0)"><?=cadd($rs['CustomerService'])?></a>			   
               </td>

              
              <td align="center">
                 <a class="popovers" data-trigger="hover" data-placement="top"  data-content="
                 上次登录时间：<?=DateYmd($rs['pretime'],1)?><br>
                 上次登录地区：<?=FeData('member_log','loginadd',"userid='{$rs['userid']}'");?><br>
                 上次登录IP：<?=cadd($rs['preip'])?>
                 "
                  href="log_list.php?so=1&key=<?=$rs['userid']?>" target="_blank">登录<?=cadd($rs['loginnum'])?>次</a>
                 <br>
                  <font class="tooltips gray2" data-container="body" data-placement="top" data-original-title="注册时间"> <?=DateYmd($rs['addtime'],1)?></font>

				</td>
              
              <td align="center">
            <?php if($member_co){?>
			    <font class="red"><?=spr($rs['money'])?></font> <?=$rs['currency']?>
                <?=spr($rs['money_lock'])>0?'<br>冻结'.spr($rs['money_lock']).$rs['currency'].'':'';?>
                <br>
			<?php }?>
                 
				<font class="gray2">
					<font class="tooltips" data-container="body" data-placement="top" data-original-title="本月消费"><?=spr($rs['xiaofei_m'])?> <?=$XAmc?></font>
					<span class="xa_sep"> | </span>
					<font class="tooltips" data-container="body" data-placement="top" data-original-title="全部消费"><?=spr($rs['xiaofei_t'])?> <?=$XAmc?></font>
                </font>
                <br>

                <!--只能传ID，因为名称不固定可以修改-->
                <a href="money_bak.php?so=1&key=<?=$rs['userid']?>" target="_blank">流水</a>
				<span class="xa_sep"> | </span>
                <a href="money_czbak.php?so=1&key=<?=$rs['userid']?>" target="_blank">增加</a>
				<span class="xa_sep"> | </span>
				<a href="money_kfbak.php?so=1&key=<?=$rs['userid']?>" target="_blank">消费</a>
				<span class="xa_sep"> | </span>
				<a href="../tixian/list.php?so=1&key=<?=$rs['userid']?>" target="_blank">提现</a>
                
                </td>
                
            <td align="center">
            	<?php if($settlement){?>
			    总额<font class="red tooltips" data-container="body" data-placement="top" data-original-title="待销账总额">
                <?=spr($rs['settlement_all_money'])?>
                </font>
                <?=$rs['currency']?>
                <br>
                 
                <font class="gray2">
                <?php if($rs['settlement_yundan_money']!=0){?>
                    运单<font class="gray tooltips" data-container="body" data-placement="top" data-original-title="已生成的待销运单账单">
                    <?=spr($rs['settlement_yundan_money'])?>
                    </font>
                    <?=$rs['currency']?>
                    <span class="xa_sep"> | </span>
                <?php }?>
                
                  <?php if($rs['settlement_other_money']!=0){?>
                    其他<font class="gray tooltips" data-container="body" data-placement="top" data-original-title="已生成的待销其他账单">
                    <?=spr($rs['settlement_other_money'])?>
                    </font>
                    <?=$rs['currency']?>
                <?php }?>
                </font>

              <?php }else{echo '无权限';}?>
                
                 <br>
                <!--只能传ID，因为名称不固定可以修改-->
				<a href="money_kfbak.php?so=1&key=<?=$rs['userid']?>&tally=1" target="_blank">记录</a>
                
                </td>
                
                                
                <td align="center">
                <a href="/xingao/coupons/list.php?so=1&status=0&key=<?=$rs['userid']?>" target="_blank">
              <?php
			  $cp=FeData('coupons',' count(*) as total,sum(`number`) as number',"status=0 and userid='{$rs['userid']}'");
			  echo $cp['number'];
			  ?>张
             </a>
              </td>
              
              <?php if ($off_integral==1){ ?>
              <td align="center">
			  <?php if($member_co){?>
			  	<?=$rs['integral']?>分<br>
              <?php }?> 
               
                 <!--只能传ID，因为名称不固定可以修改-->
                 <a href="integral_bak.php?so=1&key=<?=$rs['userid']?>" target="_blank">流水</a>
                 <span class="xa_sep"> | </span>
                 <a href="integral_czbak.php?so=1&key=<?=$rs['userid']?>" target="_blank">获得</a>
                 <span class="xa_sep"> | </span>
                 <a href="integral_kfbak.php?so=1&key=<?=$rs['userid']?>" target="_blank">使用</a>
             
                </td>
              <?php }?>
              
              <td align="center">
			
			  	<font class="tooltips" data-container="body" data-placement="top" data-original-title="本月转运"><?=$rs['yundan_msl']?>单<span class="xa_sep"> | </span><?=spr($rs['yundan_mzl'])?><?=$XAwt?></font><br>
                <font class="tooltips" data-container="body" data-placement="top" data-original-title="全部转运"><?=$rs['yundan_tsl']?>单<span class="xa_sep"> | </span><?=spr($rs['yundan_tzl'])?><?=$XAwt?></font>
				
				</td>
                
                
              
              
              
              
              
              
              
              
              
              
              
              <td align="center">
				<?php memberMenu($rs['username'],$rs['userid']);//会员账户操作菜单 ?>
                <br>
                
               <?php if($member_co){?>
					<?php if($rs['certification_for']&&!$rs['certification']){?>
                    <a href="form.php?lx=edit&userid=<?=$rs['userid']?>#certification" class="btn btn-xs btn-warning" target="_blank"><i class="icon-check"></i> 待审认证</a>
                    <?php }?>
                <?php }?>
                
                <?php if(permissions('member_ed',0,'manage',1)){?>
                <a href="form.php?lx=edit&userid=<?=$rs['userid']?>" class="btn btn-xs btn-info" target="_blank"><i class="icon-edit"></i> 查看/编辑</a> 
                <a href="save.php?lx=del&userid=<?=$rs['userid']?>" class="btn btn-xs btn-danger"  onClick="return confirm('<?=$LG['pptDelConfirm']//确认要删除所选吗?此操作不可恢复!?>');"><i class="icon-remove"></i> <?=$LG['del']?></a>
                <?php }?>
                
                </td>
            </tr>
            
            <tr>
              <td colspan="11" class="gray" style="background-color:#F9F9F9;">
              
              <?php if($member_co){?>
                <div class="gray modal_border"> <strong>联系：</strong>             
                  <?=$rs['mobile']?'手机:'.$rs['mobile_code'].' '.cadd($rs['mobile']):''?>
				  <?=$rs['email']?'<span class="xa_sep"> | </span>邮箱:'.cadd($rs['email']):''?>
                  <?=$rs['qq']?'<span class="xa_sep"> | </span>QQ:'.cadd($rs['qq']):''?>
                  <?=$rs['wx']?'<span class="xa_sep"> | </span>微信:'.cadd($rs['wx']):''?>
                  <?=$rs['weibo']?'<span class="xa_sep"> | </span>微博:'.cadd($rs['weibo']):''?>
                  <?=$rs['zip']?'<span class="xa_sep"> | </span>邮编:'.cadd($rs['zip']):''?>
                </div>
              <?php }?>

              



<?php if($off_tuiguang){?>
    <div class="gray modal_border"> <strong>推广：</strong>             
        今天有效<?=mysqli_num_rows($xingao->query("select userid from tuiguang_bak where userid='{$rs[userid]}' and status='0' and addtime>='".strtotime(date('Y-m-d')." 00:00:00")."'"));?>位
        
        
        全部有效<?=mysqli_num_rows($xingao->query("select userid from tuiguang_bak where userid='{$rs[userid]}' and status='1'"));?>位
        
        <?php 
        $wx=mysqli_num_rows($xingao->query("select userid from tuiguang_bak where userid='{$rs[userid]}' and status='0'"));
        if($wx){?>
        
        <font class="tooltips" data-container="body" data-placement="top" data-original-title="系统怀疑为作弊注册的无效邀请，不送积分">无效<?=$wx?>位</font>
        <?php }?>
        
        <a href="tuiguang_bak.php?so=1&key=<?=$rs['userid']?>" target="_blank">推广记录</a>
    </div>
<?php }?>


              
              </td>
            </tr>
            
		<!--分隔-开始-->
		<tr>
			<td colspan="10" style="border-left: 0px none #ffffff;	border-right: 0px none #ffffff; background-color:#ffffff; height:30px;"></td>
		</tr>
		<tr>
        </tr>
		<!--分隔-结束-->
            <?php
}
?>
          </tbody>
        </table>				
			
            
<!--底部操作按钮固定--> 

<style>body{margin-bottom:50px !important;}</style><!--后台不用隐藏,增高底部高度-->
<div align="right" class="fixed_btn" id="Autohidden">


<div class="col-md-0">
          <select  class="form-control input-medium select2me" data-placeholder="Select..." name="groupid_new">
            <option></option>
            <?php
$query="select groupid,groupname{$LT} from member_group where checked=1 order by  myorder desc,groupname{$LT} desc,groupid desc";
$sql=$xingao->query($query);
while($rs=$sql->fetch_array())
{
?>
            <option value="<?=$rs['groupid']?>">
            <?=$rs['groupname'.$LT]?>
            </option>
            <?php
}
?>
          </select>
  <span class="help-block"> </span> </div>
		  
  <!--btn-primary--><button type="submit" class="btn btn-grey" 
  onClick="
  document.XingAoForm.lx.value='mobile';
  return confirm('确认要把所选会员转移到新分类中吗?');
  "><i class="icon-signin"></i> 转移会员</button>

         <button type="submit" class="btn btn-grey" 
         onClick="
  document.XingAoForm.lx.value='checked';
  document.XingAoForm.checked.value='1';
  return confirm('确认要开通所选吗?');
  "><i class="icon-signin"></i> 开通</button>

         <button type="submit" class="btn btn-grey" 
         onClick="
  document.XingAoForm.lx.value='checked';
  document.XingAoForm.checked.value='0';
  return confirm('确认要关闭所选吗?');
  "><i class="icon-signin"></i> <?=$LG['close']?> </button>

  <button type="submit" class="btn btn-grey" 
  onClick="
  document.XingAoForm.action='excel_export.php';
  document.XingAoForm.target='_blank';
  "><i class="icon-signin"></i> 导出</button>


  <!--btn-success--><button type="submit" class="btn btn-grey" 
  onClick="
  document.XingAoForm.action='send.php';
  document.XingAoForm.target='_blank';
  "><i class="icon-envelope-alt"></i> 发信息</button>


         <!--btn-danger--><button type="submit" class="btn btn-grey" onClick="
  document.XingAoForm.lx.value='del';
  return confirm('<?=$LG['pptDelConfirm']//确认要删除所选吗?此操作不可恢复!?>');
  "><i class="icon-signin"></i> <?=$LG['delSelect']//删除所选?></button>
  
  
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
