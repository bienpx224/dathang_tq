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
$headtitle=$LG['yundan.excel_import_1'];//运单导入
require_once($_SERVER['DOCUMENT_ROOT'].'/xamember/incluce/head.php');

if(!$member_per[$Mgroupid]['off_zjxd']){exit ("<script>alert('{$LG['yundan.excel_import_2']}');goBack();</script>");}	


//获取,处理
$lx=par($_POST['lx']);
$file=par($_POST['file'],'',1);
$tokenkey=par($_POST['tokenkey']);

//生成令牌密钥(为安全要放在所有验证的最后面)
$token=new Form_token_Core();
$tokenkey= $token->grante_token('member_yundan_excel_import');
?>

<div class="alert alert-block fade in " style="margin-top:0px;">
  <h3 class="page-title"> <a href="javascript:history.go(-1)" title="<?=$LG['back']?>"><i class="icon-reply"></i></a> <a href="list.php?status=0" class="gray" target="_parent"><?=$LG['backList']?></a> >  <a href="javascript:void(0)" onClick="javascript:window.location.href=window.location.href;" title="<?=$LG['refresh']?>" class="gray">
    <?=$headtitle?>
    </a> </h3>
  <form action="" method="post" class="form-horizontal form-bordered" name="xingao">
    <input name="lx" type="hidden" value="tj">
    <input name="tokenkey" type="hidden" value="<?=$tokenkey?>">
    <div class="portlet">
      <div class="portlet-body form" style="display: block;"> 
        <!--表单内容-->
        
        
        
        <div class="form-group">
          <label class="control-label col-md-2"><?=$LG['file'];//文件?></label>
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
          <label class="control-label col-md-2"><?=$LG['explain']?></label>
          <div class="col-md-10"> <span class="help-block">
		  &raquo;  <a href="/doc/Import_yundan.xls" target="_blank" class="red"><strong><?=$LG['excelFormat'];//Excel格式?></strong></a><?=$LG['excelFormatExplain'];//，请做成跟此表一样!?><br>
		  &raquo;  <font class="red"><?=$LG['yundan.excel_import_3'];//如果第一遍导入不全,重新导入时,必须把已导入的信息删除掉,否则会重复导入?></font><br>
<table width="100%" class="table table-striped table-bordered table-hover" >
  <tbody>
    <tr>
      <td valign="top"><?=$LG['yundan.excel_import_18'].$XAwt?>；<br><?=$LG['yundan.excel_import_19'].$XAsz?>；<br><?=$LG['yundan.excel_import_20'].$XAsc?>；</td>
      <td valign="top"><strong><?=$LG['yundan.excel_import_5'];//仓库编号：?></strong><br><?=TextareaToBr($warehouse)?><br><br><?=$LG['yundan.excel_import_4'];//提示:并不是所有仓库都可用,还视您对该仓库是否有下单权限,如不明白请咨询客服?></td>
      <td valign="top"><strong><?=$LG['yundan.excel_import_7'];//国家区号：?></strong><br><?=yundan_Country('',2)?><br><?=$LG['yundan.excel_import_6'];//提示:并不是所有国家都可用,还视该仓库是否支持,如不明白请咨询客服?></td>
      <td valign="top"><strong><?=$LG['yundan.excel_import_9'];//渠道编号：?></strong><br><?=channel_name('','','','',1)?><br><?=$LG['yundan.excel_import_8'];//提示:并不是所有渠道都可用,还视该仓库和该国家是否支持,如不明白请咨询客服?></td>
    </tr>
  </tbody>
</table>
		  </span> 
		  
		  </div>
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
	if(!$tokenkey){exit ("<script>alert('{$LG['yundan.excel_import_10']}');goBack();</script>");}//同一个页面里提交,不能用"验证令牌密钥"
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
		//运单-开始
		if($ok && (par($strs[2])&&par($strs[3])) ) 
		{
			//验证
			if($ok && (!$strs[2]||!$strs[3]) ) 
			{
				$ok=0;
				$error_result+=1;
				echo '<strong>'.LGtag($LG['yundan.excel_import_11'],'<tag1>=='.$row)."</strong>：{$LG['yundan.excel_import_12']}<br>";
			}
			
			if($ok && (!$strs[17]||!$strs[18]||!$strs[19]||!$strs[20]||!$strs[21]||!$strs[22]||!$strs[23]) ) 
			{
				$ok=0;
				$error_result+=1;
				echo '<strong>'.LGtag($LG['yundan.excel_import_11'],'<tag1>=='.$row)."</strong>：{$LG['yundan.excel_import_13']}<br>";
			}
			
			if($ok) 
			{
				if(!warehouse(par($strs[2])))
				{
					$ok=0;
					$error_result+=1;
				echo '<strong>'.LGtag($LG['yundan.excel_import_11'],'<tag1>=='.$row)."</strong>：{$LG['yundan.excel_import_14']}<br>";
				}
			}
			
			if($ok&&$strs[0]) 
			{
				$num=mysqli_num_rows($xingao->query("select ydid from yundan where ydh='".par($strs[0])."'"));
				if($num)
				{
					$ok=0;
					$error_result+=1;
				echo '<strong>'.LGtag($LG['yundan.excel_import_11'],'<tag1>=='.$row)."</strong>：{$LG['yundan.excel_import_15']}<br>";
				}
			}
			
			if($ok) 
			{
				if(!channel_name($Mgroupid,par($strs[2]),par($strs[3]),par($strs[4])))
				{
					$ok=0;
					$error_result+=1;
				echo '<strong>'.LGtag($LG['yundan.excel_import_11'],'<tag1>=='.$row)."</strong>：{$LG['yundan.excel_import_16']}<br>";
				}
			}
	
	
			if(!par($strs[0])) 
			{
				$strs[0]=OrderNo('yundan',$strs[2]);
			}
	
			//运单-开始
			if ($ok)
			{	
				//更新上一个运单其他资料
				//$calc=1;//后台
				$calc=0;if($member_per[$Mgroupid]['off_zjxd_calc']||$ON_yundan_prepay){$calc=2;}//会员
				yundan_calc_save($ydid,$calc,0);

				//保存本运单
				$addSource=3;
				$status=0;
				$statusauto=0;if($off_statusauto&&$yd_statusauto){$statusauto=1;}
				
				$xingao->query("insert into yundan (`ydh`,   `gwkdydh`, `warehouse`, country,channel, `weightEstimate`, declarevalue,insureamount,`s_name`, `s_add_shengfen`,s_add_chengshi, `s_add_quzhen`,`s_add_dizhi`,`s_mobile_code`,`s_mobile`,`s_tel`,`s_zip`,`s_shenfenhaoma`,`f_name`, `f_add_shengfen`,f_add_chengshi, `f_add_quzhen`,`f_add_dizhi`,`f_mobile_code`,`f_mobile`,`f_tel`,`f_zip`,`kffs`,`cc_chang`,`cc_kuan`,`cc_gao`,`prefer`,`gnkd`,`gnkdydh`,`content`,addSource,status,statusauto,statustime,`addtime`, `userid`, `username`) values('".par($strs[0])."','".add($strs[1])."','".spr($strs[2])."','".spr($strs[3])."','".spr($strs[4])."','".spr($strs[14])."','".spr($strs[15])."','".spr($strs[16])."','".add($strs[17])."','".add($strs[18])."','".add($strs[19])."','".add($strs[20])."','".add($strs[21])."','".add($strs[22])."','".add($strs[23])."','".add($strs[24])."','".add($strs[25])."','".add($strs[26])."','".add($strs[27])."','".add($strs[28])."','".add($strs[29])."','".add($strs[30])."','".add($strs[31])."','".add($strs[32])."','".add($strs[33])."','".add($strs[34])."','".add($strs[35])."','".spr($strs[36])."','".spr($strs[37])."','".spr($strs[38])."','".spr($strs[39])."','".spr($strs[40])."','".par($strs[41])."','".add($strs[42])."','".html($strs[43])."','".$addSource."','".$status."','".$statusauto."','".time()."','".time()."','{$Muserid}','{$Musername}')");
				SQLError('保存运单');
				$succ_result+=1;
				$fromtable='yundan';$fromid=mysqli_insert_id($xingao);$ydid=$fromid;
			}else{
				$fromtable='';$fromid='';
			}
		}
		//运单-结束
		
		
		
		//物品-开始
		if($ok&&$fromtable&&$fromid)	
		{		
			$xingao->query("insert into wupin (fromtable,fromid,wupin_type,wupin_name,wupin_brand,wupin_spec,wupin_weight,wupin_number,wupin_unit,wupin_price,wupin_total) values ('".add($fromtable)."','".spr($fromid)."','".add($strs[5])."','".add($strs[6])."','".add($strs[7])."','".add($strs[8])."','".spr($strs[9])."','".spr($strs[10])."','".add($strs[11])."','".spr($strs[12])."','".spr($strs[13])."' )");
			SQLError('保存物品');
		}
		//物品-结束
		


	}//for ($row=2;$row<=$highestRow;$row++)
	
	//更新最后一个运单其他资料
	//$calc=1;//后台
	$calc=0;if($member_per[$Mgroupid]['off_zjxd_calc']||$ON_yundan_prepay){$calc=2;}//会员
	yundan_calc_save($ydid,$calc,0);


	//导入部分-结束-------------------------------------------------------------------------------------
	
	echo '<br><hr size="1" width="100%" />';
	echo $LG['importSuccess'].":<strong>{$succ_result}</strong><br>";
	echo $LG['importFailure'].":<strong>{$error_result}</strong><br>";

	DelFile($file);//删除文件
	
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