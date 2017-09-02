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
ob_end_clean();ob_implicit_flush(1);//实时输出:要放最前面,因为内容从此输出(之前的内容不输出)
require_once($_SERVER['DOCUMENT_ROOT'].'/public/config_top.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/public/html.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/public/function.php');

$pervar='yundan_pr';require_once($_SERVER['DOCUMENT_ROOT'].'/xingao/incluce/session.php');

//显示表单-----------------------------------------------------------------------------------------------------
$headtitle="Excel打印";
require_once($_SERVER['DOCUMENT_ROOT'].'/xingao/incluce/head.php');

//生成令牌密钥(为安全要放在所有验证的最后面)
$token=new Form_token_Core();
$tokenkey=$token->grante_token('printExcel');
?>

<div class="page_ny"> 
  <!-- BEGIN PAGE HEADER-->
  <div class="row"><!--单页时去掉 class="row",不然会有横滚动条,并且form 加 style="margin:20px;"-->
    <div class="col-md-12">
      <h3 class="page-title"> <a href="javascript:history.go(-1)" title="<?=$LG['back']?>"><i class="icon-reply"></i></a> <a href="list.php?status=all" class="gray" target="_parent"><?=$LG['backList']?></a> > <a href="javascript:void(0)" onClick="javascript:window.location.href=window.location.href;" title="<?=$LG['refresh']?>" class="gray"><?=$headtitle?></a> </h3>
    </div>
  </div>
  <!-- END PAGE HEADER--> 
  
  <!-- BEGIN PAGE CONTENT-->
  
  <form action="/xingao/yundan/print/excel.php" method="post" class="form-horizontal form-bordered" name="xingao" target="_blank">
    <input type="hidden" name="lx" value="tj">
    <input name="tokenkey" type="hidden" value="<?=$tokenkey?>">
    <div class="tabbable tabbable-custom boxless">
      <ul class="nav nav-tabs">
        <li><a href="print.php">运单打印</a></li>
        <li class="active"><a href="printExcel.php">Excel打印</a></li>
      </ul>
      <div class="tab-content">
        <div class="tab-pane active" id="tab_1">
          <div class="form">
            <div class="form-body">
              <div>
                <div class="portlet-body form" style="display: block;"> 
                  <!--表单内容-->
                  
                  <div class="form-group">
                    <label class="control-label col-md-2">Excel类型和打印模板</label>
                    <div class="col-md-10 has-error">
                      <div class="radio-list">
                      <label class="radio-inline">
                        <input type="radio" name="print_tem" value="excel_1" checked>
                        普通类型1 <a href="/doc/printExcel_1.xls" target="_blank" class="red"><strong><?=$LG['excelFormat']//Excel格式?></strong></a><?=$LG['excelFormatExplain']//，请做成跟此表一样!?>
                      </label>
                      </div>
                    </div>
                  </div>
                  
                  <div class="form-group">
                    <label class="control-label col-md-2"><?=$LG['file']//文件?></label>
                    <div class="col-md-10"> 
            <?php 
			//文件上传配置
			$uplx='file';//img,file
			$uploadLimit='10';//允许上传文件个数(如果是单个，此设置无法，默认1)
			$inputname='file';//保存字段名，多个时加[]
			$Pathname='import';//指定存放目录分类
			include($_SERVER['DOCUMENT_ROOT'].'/public/uploadify/call.php');
			?>
                    </div>
                  </div>
                  
                  
                </div>
              </div>
            </div>
          </div>
        </div>
        <div align="center"><br>
          <button type="submit" class="btn btn-primary input-small" id="openSmt1" disabled > <i class="icon-ok"></i> 打 印 </button>
        </div>
      </div>
    </div>
  </form>
</div>
<?php
require_once($_SERVER['DOCUMENT_ROOT'].'/xingao/incluce/foot.php');
?> 