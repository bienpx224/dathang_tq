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
$pervar='manage_db';require_once($_SERVER['DOCUMENT_ROOT'].'/xingao/incluce/session.php');
$headtitle="phpMyAdmin 数据库管理工具";
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
       <div align="center">
<?php if($ON_demo){?>      
         <button type="button" class="btn btn-info"><i class="icon-plus"></i> 演示站保密 </button>
<?php }else{
			if(HaveFile('/phpMyAdmin/index.php')){$phpMyAdmin_url='/phpMyAdmin/';}
			elseif(stristr($_SERVER['SERVER_ADDR'],'.70')){$phpMyAdmin_url='http://phpmyadmin.xingaowl.com/';}
			elseif(stristr($_SERVER['SERVER_ADDR'],'.122')){$phpMyAdmin_url='http://phpmyadmin-hk.xingaowl.com/';}
?>
			<button type="button" class="btn btn-info" onClick="window.open('<?=$phpMyAdmin_url?>')"><i class="icon-plus"></i> 打开数据库管理 </button>
            
<?php }?>

</div>
<br>
<?php if($ON_demo){?>
        <table class="table table-striped table-bordered table-hover" >
          <tbody>
            <tr class="odd gradeX">
              <td align="right">数据库登录用户名:</td>
              <td align="left">演示站保密</td>
              <td align="right">数据库登录密码:</td>
              <td align="left">演示站保密</td>
              <td align="right">数据库端口:</td>
              <td align="left">演示站保密</td>
            </tr>
          </tbody>
        </table>
<?php }else{?>
        <table class="table table-striped table-bordered table-hover" >
          <tbody>
            <tr class="odd gradeX">
              <td align="right">数据库登录用户名:</td>
              <td align="left"><?='请在《保守机密.txt》中查看'//$xa_config['db']['username']?></td>
              <td align="right">数据库登录密码:</td>
              <td align="left"><?='请在《保守机密.txt》中查看'//$xa_config['db']['password']?></td>
              <td align="right">数据库端口:</td>
              <td align="left"><?=$xa_config['db']['port']?></td>
            </tr>
          </tbody>
        </table>
<?php }?>		
       <br>

   <div align="center">
   <h3><strong>优化/修复表方法:</strong></h3><br><br>
     <img src="/images/phpMyAdmin1.jpg"> 
     <br><br><hr>
   <h3><strong>备份方法:</strong></h3><br><br>
     <img src="/images/phpMyAdmin2.jpg"> 
     <br><br><hr>
   <h3><strong>恢复还原方法:</strong></h3><br><br>
     <img src="/images/phpMyAdmin3.jpg"> 
     <br><br>
     
     </div>
      </div>
      
      
      <!--表格内容结束--> 
      
    </div>
</div>
<?php
require_once($_SERVER['DOCUMENT_ROOT'].'/xingao/incluce/foot.php');
?>
