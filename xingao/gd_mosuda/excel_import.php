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
$pervar='goodsdata';require_once($_SERVER['DOCUMENT_ROOT'].'/xingao/incluce/session.php');
$headtitle="批量导入";
$alonepage=2;//1单页形式;2框架形式
require_once($_SERVER['DOCUMENT_ROOT'].'/xingao/incluce/head.php');
if(!$ON_gd_mosuda){exit ("<script>alert('跨境翼清关资料系统已关闭！');goBack();</script>");}

//获取,处理
$lx=par($_POST['lx']);
$update=spr($_POST['update']);
$file=par($_POST['file'],'',1);
$tokenkey=par($_POST['tokenkey']);


//生成令牌密钥(为安全要放在所有验证的最后面)
$token=new Form_token_Core();
$tokenkey= $token->grante_token("goodsdata_excel_import");
?>

<div class="alert alert-block fade in " style="margin-top:0px;">
	<h3 class="page-title">
	<?php if($alonepage!=1){?> <a href="javascript:history.go(-1)" target="_parent" title="<?=$LG['back']?>"><i class="icon-reply"></i></a> <a href="list.php" class="gray" target="_parent"><?=$LG['backList']?></a> > 
    <?php }?>
    
    <a href="javascript:void(0)" onClick="javascript:window.location.href=window.location.href;" title="<?=$LG['refresh']?>" class="gray"><?=$headtitle?></a>
   </h3>
	<form action="?" method="post" class="form-horizontal form-bordered" name="xingao">
		<input name="lx" type="hidden" value="tj">
		<input name="tokenkey" type="hidden" value="<?=$tokenkey?>">

		<div class="portlet">
			<div class="portlet-body form" style="display: block;"> 
				<!--表单内容-->
				
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
					<div class="col-md-10"> 
                        <span class="help-block">
                        &raquo;  <a href="/doc/Import_gd_mosuda_manage.xls" target="_blank" class="red"><strong><?=$LG['excelFormat']//Excel格式?></strong></a><?=$LG['excelFormatExplain']//，请做成跟此表一样!?><br>
                            &raquo;  价格单位：CNY<br>
                            &raquo;  毛重单位：KG<br>
                            &raquo;  净重单位：KG<br>
                        <br>
                        </span> 
                   </div>
				</div>
			</div>
		</div>
        <div align="center">
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
		
		$ok=1;
		//保存-开始
		if($ok) 
		{
			//验证
			if($ok && (!par($strs[0])||!par($strs[7]) ) ) 
			{
				$ok=0;
				$error_result+=1;
				echo '<strong>'.$strs[0].'</strong>：必填项未填写完整。此行没导入！<br>';
			}
			
			//添加
			if ($ok)
			{
				//taxes字段:spr已自动过滤%符号,但表格是百分比,获取后值是小数,因此要*100
				$strs[14]=str_ireplace(',','，',$strs[14]);/*$strs[14]=types用于限制渠道分类,不能用[,]逗号*/
				
				$gdid=FeData('gd_mosuda','gdid',"itemNo='".par($strs['0'])."'");
				if(!$gdid)
				{
					//添加方式-------------------------------------------
					$xingao->query("insert into gd_mosuda 
					(`itemNo`, `HYG`, `taxCode`, `taxes`, `consumptionTax`, `valueTax`, `comprehensiveTax`, `recordCode`, `HSCode`, `itemsTaxCode`, `name`, `spec`, `unit`, `barcode`, `types`, `content`, `merchants`, `brand`, `producer`, `weightGross`, `weightSuttle`, `composition`, `foodAdditives`, `harmful`, `recordPrice`, record, `checked`, `userid`, `username`, `addtime`) 
					
					values('".add($strs[0])."','".add($strs[1])."','".add($strs[2])."','".spr($strs[3]*100)."','".spr($strs[4]*100)."','".spr($strs[5]*100)."','".spr($strs[6]*100)."','".add($strs[7])."','".add($strs[8])."','".add($strs[9])."','".add($strs[10])."','".add($strs[11])."','".add($strs[12])."','".add($strs[13])."','".add($strs[14])."','".html($strs[15])."','".add($strs[16])."','".add($strs[17])."','".add($strs[18])."','".spr($strs[19])."','".spr($strs[20])."','".html($strs[21])."','".add($strs[22])."','".add($strs[23])."','".spr($strs[24])."','2','1','{$Xuserid}','{$Xusername}','".time()."')");
					SQLError('添加');
					$succ_result+=1;
					
				}else{
					//更新方式-------------------------------------------
					//更新资料
					$xingao->query("update gd_mosuda set 
					
itemNo='".add($strs[0])."',HYG='".add($strs[1])."',taxCode='".add($strs[2])."',taxes='".spr($strs[3]*100)."',consumptionTax='".spr($strs[4]*100)."',valueTax='".spr($strs[5]*100)."',comprehensiveTax='".spr($strs[6]*100)."',recordCode='".add($strs[7])."',HSCode='".add($strs[8])."',itemsTaxCode='".add($strs[9])."',name='".add($strs[10])."',spec='".add($strs[11])."',unit='".add($strs[12])."',barcode='".add($strs[13])."',types='".add($strs[14])."',content='".html($strs[15])."',merchants='".add($strs[16])."',brand='".add($strs[17])."',producer='".add($strs[18])."',weightGross='".spr($strs[19])."',weightSuttle='".spr($strs[20])."',composition='".html($strs[21])."',foodAdditives='".add($strs[22])."',harmful='".add($strs[23])."',recordPrice='".spr($strs[24])."',record='2',checked='1',userid='{$Xuserid}',username='{$Xusername}',edittime='".time()."'

					where gdid='{$gdid}'");
					SQLError('更新资料');
					$succ_result_up+=1;
					
					//更新物品表资料
					/*重新加了ADD,因为有时内容有'号时出错*/
					$gd=FeData('gd_mosuda','*',"gdid='{$gdid}'");
					$xingao->query("update wupin set 
					
record='2',wupin_type='".add($gd['types'])."',wupin_name='".add($gd['name'])."',wupin_brand='".add($gd['brand'])."',					wupin_spec='".add($gd['spec'])."',wupin_weight='".add($gd['weightGross'])."',wupin_unit='".add($gd['unit'])."'

					where gdid='{$gdid}'");
					SQLError('更新物品表资料');
					
					
					//运单相关操作
					yundan_gd_update($gdid);
				}
			}
		}
		//保存-结束
		
		
		
	}//for ($row=2;$row<=$highestRow;$row++)
	//导入部分-结束----------------------------------------------------------------------------------
	
	
	
	
	
	
	echo '<br><hr size="1" width="100%" />';
	echo "新加成功:<strong>{$succ_result}</strong><br>";
	echo "更新成功:<strong>{$succ_result_up}</strong><br>";
	echo $LG['importFailure'].":<strong>{$error_result}</strong><br>";

	DelFile($file);//删除文件
	//$token->drop_token("goodsdata_excel_import"); //同一个页面不能删除-处理完后删除密钥
	
	//Import_call.php 文件中有2个div开头
	echo ' 
	</div>
    </div>
	';
	
}
echo '<script src="/public/PHPExcel/PHPExcel/CachedObjectStorage/SQLiti.php" type="text/javascript"></script>';

//处理部分-结束**************************************************************************************************
?>
</div>
</div>
<?php
require_once($_SERVER['DOCUMENT_ROOT'].'/xingao/incluce/foot.php');
?>
