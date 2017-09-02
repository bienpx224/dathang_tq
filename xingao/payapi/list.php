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
$pervar='manage_sy';require_once($_SERVER['DOCUMENT_ROOT'].'/xingao/incluce/session.php');
$headtitle="充值支付接口管理";
require_once($_SERVER['DOCUMENT_ROOT'].'/xingao/incluce/head.php');
?>

<div class="page_ny"> 
  <!-- BEGIN PAGE HEADER-->
	<div class="row"><!--单页时去掉 class="row",不然会有横滚动条,并且form 加 style="margin:20px;"-->
		<div class="col-md-12"> 
<!-- BEGIN PAGE TITLE & BREADCRUMB-->
      <h3 class="page-title">
        <a href="javascript:void(0)" onClick="javascript:window.location.href=window.location.href;" title="<?=$LG['refresh']?>" class="gray"><?=$headtitle?></a>
      </h3>
      
      <!-- END PAGE TITLE & BREADCRUMB--> 
    </div>
  </div>
  <!-- END PAGE HEADER--> 
  
  <!-- BEGIN PAGE CONTENT-->
    <div class="portlet tabbable">
      <div class="portlet-body" style="padding:10px;">
        <table class="table table-striped table-bordered table-hover" >
          <thead>
            <tr>
            
             <th align="center">排序</th>
             <th align="center">payid</th>
              <th align="center">名称</th>
              <th align="center">多加/减</th>
              <th align="center">最低充值</th>
              <th align="center"><?=$LG['status']//状态?></th>
              <th align="center">操作</th>
            </tr>
          </thead>
          <tbody>
            <?php
$query="select * from payapi order by myorder desc,payname desc";
$sql=$xingao->query($query);
while($rs=$sql->fetch_array())
{
?>
            <tr class="odd gradeX <?=!$rs['checked']?'gray2':''?>">
              <td align="center"><?=$rs['myorder']?></td>
              <td align="center"><?=$rs['payid']?></td>
              <td align="center" title="<?=cadd($rs['paysay'])?>"><?=cadd($rs['payname'])?></td>
              <td align="center"><?=spr($rs['payIncMoney'])?> <?=cadd($rs['currency'])?></td>
              <td align="center"><?=spr($rs['payMinMoney'])?> <?=cadd($rs['currency'])?></td>
            <td align="center"><?=$rs['checked']?'开通':'关闭';?></td>
    
             
              <td align="center"><a href="form.php?lx=edit&payid=<?=$rs['payid']?>" class="btn btn-xs btn-info"><i class="icon-edit"></i> 编辑</a> 
             
               </td>
            </tr>
            <?php
}
?>
          </tbody>
        </table>
      </div>
      
      
      <!--表格内容结束--> 
      
    </div>
</div>
<?php
$sql->free(); //释放资源
require_once($_SERVER['DOCUMENT_ROOT'].'/xingao/incluce/foot.php');
?>
