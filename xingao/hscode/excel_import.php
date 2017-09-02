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
$pervar='yundan_ot';require_once($_SERVER['DOCUMENT_ROOT'].'/xingao/incluce/session.php');
$headtitle="HS/HG/快递单号/批次号导入";
require_once($_SERVER['DOCUMENT_ROOT'].'/xingao/incluce/head.php');

//获取,处理
$lx=par($_POST['lx']);
$file=par($_POST['file'],'',1);
$del=par($_POST["del"]);
$checked=par($_POST["checked"]);
$tokenkey=par($_POST['tokenkey']);


//生成令牌密钥(为安全要放在所有验证的最后面)
$token=new Form_token_Core();
$tokenkey= $token->grante_token("excel_import");
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
          <label class="control-label col-md-2">可使用</label>
          <div class="col-md-10">
            <div class="make-switch" data-on-label="<i class='icon-ok icon-white'></i>" data-off-label="<i class='icon-remove'></i>">
              <input type="checkbox" class="toggle" name="checked" value="1" checked />
            </div>
          </div>
        </div>
        
        <div class="form-group">
          <label class="control-label col-md-2">删除已用号码</label>
          <div class="col-md-10">
            <div class="make-switch" data-on-label="<i class='icon-ok icon-white'></i>" data-off-label="<i class='icon-remove'></i>">
              <input type="checkbox" class="toggle" name="del" value="1"   />
            </div>
            <span class="help-block">导入后删除数据库中已使用号码 (Excel表格最后一种类型的号码)</span> </div>
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
        <div class="form-group">
          <label class="control-label col-md-2"><?=$LG['explain']//说明?></label>
          <div class="col-md-10"> <span class="help-block">&raquo;  <a href="/doc/Import_hscode.xls" target="_blank" class="red"><strong><?=$LG['excelFormat']//Excel格式?></strong></a><?=$LG['excelFormatExplain']//，请做成跟此表一样!?></span> </div>
        </div>
      </div>
    </div>        
        
                
<!--提交按钮固定--> 
<style>body{margin-bottom:50px !important;}</style><!--后台不用隐藏,增高底部高度-->
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
		$number_str=add(trim($strs[0]));
		$number_end=add(trim($strs[1]));
		$types=add(trim($strs[2]));
		
		$ok=1;
		if ($number_str && $ok)
		{ 
			//严格验证---------
			$fr=mysqli_fetch_array($xingao->query("select checked from hscode where number_str='{$number_str}'  and number_end='{$number_end}' and types='{$types}' "));
			$rc=mysqli_affected_rows($xingao);
			
			if($rc)
			{
				$ok=0;$error_result+=1;
				if($fr['checked']){$show_now='未使用';}else{$show_now='已使用';}
				echo '<strong>'.$number_str .'</strong>：该号码已经存在('.$show_now.')，不再导入！<br>';
			}
			
	
			if($number_end&&$ok)
			{
				$number=findNum($number_str);//提取数字部分
				$number=str_replace($number,$number+1,$number_str);
				if($number==$number_str)
				{
					$ok=0;$error_result+=1;
					echo '<strong>'.$number_str .'</strong>：该号段开头格式错误，不导入！<br>';
				}
				
				$number=findNum($number_end);//提取数字部分
				$number=str_replace($number,$number+1,$number_end);
				if($number==$number_end)
				{
					$ok=0;$error_result+=1;
					echo '<strong>'.$number_str .'</strong>：该号段结尾格式错误，不导入！<br>';
				}
			}

			//添加---------
			if ($ok)
			{	
				$xingao->query("insert into hscode (number_str,number_end,types,checked,addtime) values('{$number_str}','{$number_end}','{$types}','{$checked}','".time()."') ");
				SQLError();
				$succ_result+=1;
			}
		}
	}//for ($row=2;$row<=$highestRow;$row++)//导入部分-结束---------------------------------------------
	
	echo '<br><hr size="1" width="100%" />';
	echo "成功添加<strong>".(int)$succ_result."</strong>条数据！<br>";
	echo "添加失败<strong>".(int)$error_result."</strong>条数据！<br>";

	DelFile($file);//删除文件
	//$token->drop_token("excel_import"); //同一个页面不能删除-处理完后删除密钥
	
	//删除已使用号码
	if($del)
	{
		$xingao->query("delete from hscode where checked<>'1'  and types='{$types}' ");
		$rc=mysqli_affected_rows($xingao);
		
		if($rc>0){
			echo "删除<strong>".$rc."</strong>个已使用号码/号码段！<br>";
		}
	}
	
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
require_once($_SERVER['DOCUMENT_ROOT'].'/xingao/incluce/foot.php');
?>
