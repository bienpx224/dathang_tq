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
require_once($_SERVER['DOCUMENT_ROOT'].'/xamember/incluce/session.php');
$headtitle=$LG['address.headtitleImport'];
require_once($_SERVER['DOCUMENT_ROOT'].'/xamember/incluce/head.php');


//获取,处理
$lx=par($_POST['lx']);
$file=par($_POST['file'],'',1);
$tokenkey=par($_POST['tokenkey']);


//生成令牌密钥(为安全要放在所有验证的最后面)
$token=new Form_token_Core();
$tokenkey= $token->grante_token("address_excel_import");
?>

<div class="alert alert-block fade in " style="margin-top:0px;">
  <h3 class="page-title"> <a href="javascript:history.go(-1)" title="<?=$LG['back']?>"><i class="icon-reply"></i></a> <a href="list.php" class="gray" target="_parent"><?=$LG['backList']?></a> >  <a href="javascript:void(0)" onClick="javascript:window.location.href=window.location.href;" title="<?=$LG['refresh']?>" class="gray">
    <?=$headtitle?>
    </a> </h3>
  <form action="" method="post" class="form-horizontal form-bordered" name="xingao">
    <input name="lx" type="hidden" value="tj">
    <input name="tokenkey" type="hidden" value="<?=$tokenkey?>">
    <div class="portlet">
      <div class="portlet-body form" style="display: block;"> 
        <!--表单内容-->
        
        
        
        <div class="form-group">
          <label class="control-label col-md-2"><?=$LG['file']?><!--文件--></label>
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
        <div class="form-group">
          <label class="control-label col-md-2"><?=$LG['explain']//说明?></label>
          <div class="col-md-10"> <span class="help-block">&raquo;  <a href="/doc/Import_address.xls" target="_blank" class="red"><strong><?=$LG['excelFormat']//Excel格式?></strong></a><?=$LG['excelFormatExplain']//，请做成跟此表一样!?></span> </div>
        </div>
      </div>
    </div>        
        <!--提交按钮固定--> 
		<script>/*$(function(){ Autohidden(); });*/</script> <!--拉到底时自动隐藏-->
<style>body{margin-bottom:40px !important;}</style><!--不用隐藏,增高底部高度-->
 
        <div align="center" class="fixed_btn" id="Autohidden">
        
        
<button type="submit" class="btn btn-primary input-small" id="openSmt1" disabled > <i class="icon-ok"></i> <?=$LG['submit']?> </button>
      <button type="reset" class="btn btn-default" style="margin-left:30px;"> <?=$LG['reset']?> </button>
    </div>
  </form>
  
 
  <?php
//处理部分-开始**************************************************************************************************
//必须有$file 文件
if ($lx=="tj")
{ 
	if(!$tokenkey){exit ("<script>alert('来源错误！');goBack();</script>");}//同一个页面里提交,不能用"验证令牌密钥"
	require_once($_SERVER['DOCUMENT_ROOT'].'/public/PHPExcel/Import_call.php');
	
	//导入部分-开始-------------------------------------------------------------------------------------
	for ($row=2;$row<=$highestRow;$row++) //$row =2; 从第2行读取(第一行是标题) 
	{
		$strs=array();
		for ($col = 0;$col < $highestColumnIndex;$col++)
		{
			$strs[$col] =$objWorksheet->getCellByColumnAndRow($col, $row)->getValue();
		}    
		
		//获取,初步验证---------
		//echo $strs[0];
		//$number=par($strs[0]);
		
		
		$ok=1;
		//严格验证---------
		if($ok && (!$strs[1]||!$strs[3]||!$strs[10]) ) 
		{
			$ok=0;
			$error_result+=1;
			echo '<strong>'.$strs[1].'</strong>：'.$LG['address.pptImport1'].'<br>';//姓名/手机/详细地址 未填写！
		}
		
		if($ok) 
		{
			$num=mysqli_num_rows($xingao->query("select addid from member_address where truename='".add($strs[1])."' and mobile='".add($strs[3])."' and add_dizhi='".add($strs[10])."' {$Mmy} "));
			if($num)
			{
				$ok=0;
				$error_result+=1;
				echo '<strong>'.$strs[1].'</strong>：'.$LG['address.pptImport2'].'<br>';//系统已有该地址，不再导入！
			}
		}

		//添加---------
		if ($ok)
		{	
			$xingao->query("INSERT INTO member_address 
			(`userid`, `username`, `addclass`, `truename`, `mobile_code`, `mobile`, `tel`, `zip`, `add_shengfen`, `add_chengshi`, `add_quzhen`, `add_dizhi`, `shenfenhaoma`, `content`,`checked`,`addtime`) VALUES 
			('$Muserid', '$Musername', '".add($strs[0])."', '".add($strs[1])."', '".add($strs[2])."', '".add($strs[3])."', '".add($strs[4])."', '".add($strs[5])."', '".add($strs[6])."', '".add($strs[7])."', '".add($strs[8])."', '".add($strs[9])."', '".add($strs[10])."', '".add($strs[11])."', '1', '".time()."') ");
			
			
			SQLError();
			$succ_result+=1;
		}
	}//for ($row=2;$row<=$highestRow;$row++)//导入部分-结束-------------------------------------------------------------------------------------
	
	echo '<br><hr size="1" width="100%" />';
	echo $LG['importSuccess'].":<strong>{$succ_result}</strong><br>";
	echo $LG['importFailure'].":<strong>{$error_result}</strong><br>";

	DelFile($file);//删除文件
	//$token->drop_token("address_excel_import"); //同一个页面不能删除-处理完后删除密钥
	
	//Import_call.php 文件中有2个div开头
	echo ' 
	</div>
    </div>
	';
	
}
//处理部分-结束**************************************************************************************************
?>
</div>
</div>
<?php
require_once($_SERVER['DOCUMENT_ROOT'].'/xamember/incluce/foot.php');
?>