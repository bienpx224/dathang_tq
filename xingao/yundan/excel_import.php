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

$pervar='yundan_ad';require_once($_SERVER['DOCUMENT_ROOT'].'/xingao/incluce/session.php');
$headtitle="运单导入";
$alonepage=2;//1单页形式;2框架形式
require_once($_SERVER['DOCUMENT_ROOT'].'/xingao/incluce/head.php');
@set_time_limit(0);//如果有大量数据导入,需要设置长些

//获取,处理
$lx=par($_POST['lx']);
$file=par($_POST['file'],'',1);
$tokenkey=par($_POST['tokenkey']);

//生成令牌密钥(为安全要放在所有验证的最后面)
$token=new Form_token_Core();
$tokenkey=$token->grante_token('manage_yundan_excel_import');
?>

<div class="alert alert-block fade in " style="margin-top:0px;">
  <h3 class="page-title"> 
  
  <?php if($alonepage!=1){?>
<a href="javascript:history.go(-1)" title="<?=$LG['back']?>"><i class="icon-reply"></i></a> <a href="list.php?status=0" class="gray" target="_parent"><?=$LG['backList']?></a> > <?php }?> 
  
  <a href="javascript:void(0)" onClick="javascript:window.location.href=window.location.href;" title="<?=$LG['refresh']?>" class="gray">
    <?=$headtitle?>
    </a></h3>
  <form action="" method="post" class="form-horizontal form-bordered" name="xingao">
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
		  &raquo;  <a href="/doc/Import_yundan_manage.xls" target="_blank" class="red"><strong><?=$LG['excelFormat']//Excel格式?></strong></a><?=$LG['excelFormatExplain']//，请做成跟此表一样!?><br>
          
		  &raquo;  <font class="red">如果第一遍导入不全,重新导入时,必须把已导入的信息删除掉,否则会重复导入</font><br>
<table width="100%" class="table table-striped table-bordered table-hover" >
  <tbody>
    <tr>
      <td valign="top">重量单位:<?=$XAwt?>；<br>
尺寸单位:<?=$XAsz?>；<br>物品、物品价值、物品保价币种:<?=$XAsc?>；</td>
      <td valign="top"><strong>仓库编号：</strong><br><?=TextareaToBr($warehouse)?>
      <br><br>提示:并不是所有仓库都可用,还视您对该仓库是否有管理权限
      </td>
      <td valign="top"><strong>国家区号：</strong><br><?=yundan_Country('',2)?>
       <br>提示:并不是所有国家都可用,还视该仓库是否支持
      </td>
      <td valign="top"><strong>渠道编号：</strong><br><?=channel_name('','','','',1)?>
      <br>提示:并不是所有渠道都可用,还视该仓库和该国家是否支持
      </td>
    </tr>
  </tbody>
</table>
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
		if(par($strs[45])||spr($strs[46])){$username=par($strs[45]);$userid=spr($strs[46]);}

		//运单-开始
		if($ok && (par($strs[2])&&par($strs[3])) ) 
		{
			//验证
			if($ok && (!$strs[2]||!$strs[3]) ) 
			{
				$ok=0;
				$error_result+=1;
				echo '<strong>第'.$row.'行</strong>：基本资料未填写完整。此行没导入！<br>';
			}
			
			if($ok && (!$strs[17]||!$strs[18]||!$strs[19]||!$strs[20]||!$strs[21]||!$strs[22]||!$strs[23]) ) 
			{
				$ok=0;
				$error_result+=1;
				echo '<strong>第'.$row.'行</strong>：收件人资料未填写完整。此行没导入！<br>';
			}
	
			if($ok&&$strs[0]) 
			{
				$num=mysqli_num_rows($xingao->query("select ydid from yundan where ydh='".par($strs[0])."'"));
				if($num)
				{
					$ok=0;
					$error_result+=1;
					echo '<strong>第'.$row.'行</strong>：系统已有该运单号。此行没导入！<br>';
				}
			}

			if($ok) 
			{
				if(!warehouse(par($strs[2])))
				{
					$ok=0;
					$error_result+=1;
					echo '<strong>第'.$row.'行</strong>：仓库编号错误。此行没导入！<br>';
				}
			}
			
			if($ok) 
			{
				$memberok=MemberOK('','',$userid,$username,1,'');
				if(!$memberok)
				{
					$ok=0;
					$error_result+=1;
					echo '<strong>第'.$row.'行</strong>：会员名或会员ID错误。此行没导入！<br>';
				}
			}
			
			if($ok) 
			{
				$warehouse=warehouse_per('ts',par($strs[2]),1);
				if($warehouse)
				{
					$ok=0;
					$error_result+=1;
					echo '<strong>第'.$row.'行</strong>：您无权添加到该仓库。此行没导入！<br>';
				}
			}
	
			if($ok) 
			{
				if(!channel_name(FeData('member','groupid',"userid='{$userid}'"),par($strs[2]),par($strs[3]),par($strs[4])))
				{
					$ok=0;
					$error_result+=1;
					echo '<strong>第'.$row.'行</strong>：仓库、国家、渠道其中有错误。此行没导入！<br>';
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
				yundan_calc_save($ydid,1,0);

				//保存本运单
				$addSource=5;
				$status=spr($strs[47]);
				$statusauto=0;if($off_statusauto&&$yd_statusauto){$statusauto=1;}
				
				$xingao->query("insert into yundan (`ydh`,`gwkdydh`, `warehouse`, country,channel, `weightEstimate`, declarevalue,insureamount, `s_name`, `s_add_shengfen`,s_add_chengshi, `s_add_quzhen`,`s_add_dizhi`,`s_mobile_code`,`s_mobile`,`s_tel`,`s_zip`,`s_shenfenhaoma`,`f_name`, `f_add_shengfen`,f_add_chengshi, `f_add_quzhen`,`f_add_dizhi`,`f_mobile_code`,`f_mobile`,`f_tel`,`f_zip`,`kffs`,`cc_chang`,`cc_kuan`,`cc_gao`, `prefer`,`gnkd`,`gnkdydh`,`content`,weight,lotno,addSource,status,statusauto,statustime,`addtime`, `userid`, `username`) values('".par($strs[0])."','".add($strs[1])."','".spr($strs[2])."','".spr($strs[3])."','".spr($strs[4])."','".spr($strs[14])."','".spr($strs[15])."','".spr($strs[16])."','".add($strs[17])."','".add($strs[18])."','".add($strs[19])."','".add($strs[20])."','".add($strs[21])."','".add($strs[22])."','".add($strs[23])."','".add($strs[24])."','".add($strs[25])."','".add($strs[26])."','".add($strs[27])."','".add($strs[28])."','".add($strs[29])."','".add($strs[30])."','".add($strs[31])."','".add($strs[32])."','".add($strs[33])."','".add($strs[34])."','".add($strs[35])."','".spr($strs[36])."','".spr($strs[37])."','".spr($strs[38])."','".spr($strs[39])."','".spr($strs[40])."','".par($strs[41])."','".add($strs[42])."','".html($strs[43])."','".spr($strs[44])."','".add($strs[48])."','".$addSource."','".$status."','".$statusauto."','".time()."','".time()."','{$userid}','{$username}' )");
				
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
	yundan_calc_save($ydid,1,0);

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
require_once($_SERVER['DOCUMENT_ROOT'].'/xingao/incluce/foot.php');
?>