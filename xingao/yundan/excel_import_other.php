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

$pervar='yundan_im';require_once($_SERVER['DOCUMENT_ROOT'].'/xingao/incluce/session.php');
$headtitle="快递/其他导入";
$alonepage=2;//1单页形式;2框架形式
require_once($_SERVER['DOCUMENT_ROOT'].'/xingao/incluce/head.php');
@set_time_limit(0);//如果有大量数据导入,需要设置长些

//获取,处理
$lx=par($_POST['lx']);
$file=par($_POST['file'],'',1);
$tokenkey=par($_POST['tokenkey']);


if ($lx=="tj")
{ 
	if(!$tokenkey){exit ("<script>alert('来源错误！');goBack();</script>");}//同一个页面里提交,不能用"验证令牌密钥"
}

//生成令牌密钥(为安全要放在所有验证的最后面)
$token=new Form_token_Core();
$tokenkey= $token->grante_token("yundan_excel_import");
?>

<div class="alert alert-block fade in " style="margin-top:0px;">
  <h3 class="page-title"><?php if($alonepage!=1){?>
<a href="javascript:history.go(-1)" title="<?=$LG['back']?>"><i class="icon-reply"></i></a> <a href="list.php?status=0" class="gray" target="_parent"><?=$LG['backList']?></a> >  <?php }?>
  
  <a href="javascript:void(0)" onClick="javascript:window.location.href=window.location.href;" title="<?=$LG['refresh']?>" class="gray">
    <?=$headtitle?>
    </a> </h3>
  <form action="" method="post" class="form-horizontal form-bordered" name="xingao">
    <input name="lx" type="hidden" value="tj">
    <input name="tokenkey" type="hidden" value="<?=$tokenkey?>">
    <div class="portlet">
      <div class="portlet-body form" style="display: block;"> 
        <!--表单内容-->
        
        <div class="form-group">
          <label class="control-label col-md-2">导入类型<br><font class="red">必选</font></label>
          <div class="col-md-10">
			  <div class="radio-list">
				 <label>
				 <input type="radio" name="types"  value="dsfydh" <?php if ($types=='dsfydh'){echo 'checked';}?>> 第三方运单号 (<a href="/doc/Import_yundan_dsfydh.xls" target="_blank">实例表</a>)
				 </label>
				 
				 <label>
				 <input type="radio" name="types"  value="lotno" <?php if ($types=='lotno'){echo 'checked';}?>> 托盘号、批次号
				  (
                  <a href="/doc/Import_yundan_lotno.xls" target="_blank">实例表</a>、
                  <a href="/xingao/classify/list.php?so=1&classtype=3" target="_blank">托盘号 分类ID</a>
                  )
				 </label>
				 
				 <label>
				 <input type="radio" name="types"  value="gnkd" <?php if ($types=='gnkd'){echo 'checked';}?>> 派送快递
				  (<a href="/doc/Import_yundan_gnkd.xls" target="_blank">实例表</a>)
				 </label>
				 
				 <label>
				 <input type="radio" name="types"  value="sf" <?php if ($types=='sf'){echo 'checked';}?>> 顺丰快递
				  (<a href="/doc/Import_yundan_SF.xls" target="_blank">实例表</a>)
				 </label>
				 
			  </div>
          </div>
        </div>
        
        <div class="form-group">
          <label class="control-label col-md-2">导入选项</label>
          <div class="col-md-10">

			<input name="lotno" class="form-control input-msmall" type="text" size="10" value="<?=par($_POST['lotno'])?>" />
			只导入到该批次号的运单  <span class="gray2">(顺丰如果有子单号，请填写此项以免导错)</span><br /><br />
			
			<input name="op_status_4" type="checkbox" value="1" />
			只导入已出库的运单<br />
			
			<input name="op_status_20" type="checkbox" value="1" checked/>
			只导入“<?=status_name(20)?>”之前状态的运单<br />
			
			<input name="op_status_30" type="checkbox" value="1" checked>
			只导入未“<?=status_name(30)?>”状态的运单<br />
			
			<input name="op_status_kd" type="checkbox" value="1" />
			导入后更新“<?=status_name(20)?>”状态<br />

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
        <div align="center">
      <button type="submit" class="btn btn-primary input-small" id="openSmt1" disabled > <i class="icon-ok"></i> <?=$LG['submit']?> </button>
      <button type="reset" class="btn btn-default" style="margin-left:30px;"> <?=$LG['reset']?> </button>
    </div>
  </form>
  			  <span class="xats help-block">
			   &raquo; 实例表一样，请做成一样的列数<br>
			   &raquo; 运单多时会慢些，不要反复点击<br>
			  </span> 
<?php
//处理页--------------------------------------------------------------------------------------------
require_once($_SERVER['DOCUMENT_ROOT'].'/xingao/yundan/call/excel_import_other_save.php');
?>

</div>
</div>
<?php
require_once($_SERVER['DOCUMENT_ROOT'].'/xingao/incluce/foot.php');
?>