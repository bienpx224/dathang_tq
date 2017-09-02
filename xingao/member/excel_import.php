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
$pervar='member_ed';require_once($_SERVER['DOCUMENT_ROOT'].'/xingao/incluce/session.php');
$headtitle="会员批量导入";
$alonepage=2;//1单页形式;2框架形式
require_once($_SERVER['DOCUMENT_ROOT'].'/xingao/incluce/head.php');
@set_time_limit(0);//如果有大量数据导入,需要设置长些

//获取,处理
$lx=par($_POST['lx']);
$file=par($_POST['file'],'',1);
$tokenkey=par($_POST['tokenkey']);
$upType=par($_POST['upType']);


//生成令牌密钥(为安全要放在所有验证的最后面)
$token=new Form_token_Core();
$tokenkey= $token->grante_token("member_excel_import");
?>

<div class="alert alert-block fade in " style="margin-top:0px;">
	<h3 class="page-title"><?php if($alonepage!=1){?>
<a href="javascript:history.go(-1)" title="<?=$LG['back']?>"><i class="icon-reply"></i></a> <a href="list.php?status=kuwai" class="gray" target="_parent"><?=$LG['backList']?></a> > 
    <?php }?>
    <a href="javascript:void(0)" onClick="javascript:window.location.href=window.location.href;" title="<?=$LG['refresh']?>" class="gray">
		<?=$headtitle?>
		</a> </h3>
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
					<div class="col-md-10"> <span class="help-block">&raquo;  <a href="/doc/Import_member.xls" target="_blank" class="red"><strong><?=$LG['excelFormat']//Excel格式?></strong></a><?=$LG['excelFormatExplain']//，请做成跟此表一样!?><br>
						&raquo;  会员组：<br>
<?php
$query="select groupid,groupname{$LT} from member_group  order by  myorder desc,groupname{$LT} desc,groupid desc";
$sql=$xingao->query($query);
while($rs=$sql->fetch_array())
{
	echo cadd($rs['groupname'.$LT]).'		=		'.$rs['groupid'].'<br>';
}
?>
                        
                        <br>
						&raquo;  支持币种：<?=$me_openCurrency?>
						<br>
						</span> </div>
				</div>
			</div>
		</div>
        <div align="center" >
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
		
		
		//验证
		if($ok && (!spr($strs[0])||!par($strs[1])||!par($strs[2])  ) ) 
		{
			$ok=0;
			$error_result+=1;
			echo '<strong>'.$strs[1].'</strong>：必填内容未填写完整。此行没导入！<br>';
		}
		
		if($ok) 
		{
			//验证会员组ID
			$num=NumData('member_group',"groupid='".spr($strs[0])."'");
			if(!$num){
				$ok=0;
				$error_result+=1;
				echo '<strong>'.$strs[1].'</strong>：会员组ID错误。此行没导入！<br>';
			}
		}
		
		if($ok) 
		{
			//验证会员名
			$num=NumData('member',"username='".par($strs[1])."'");
			if($num){
				$ok=0;
				$error_result+=1;
				echo '<strong>'.$strs[1].'</strong>：会员登录名重复。此行没导入！<br>';
			}
		}
		
		if($ok) 
		{
			//验证币种
			if(!have($me_openCurrency,$strs[2])){
				$ok=0;
				$error_result+=1;
				echo '<strong>'.$strs[1].'</strong>：不支持该币种。此行没导入！<br>';
			}
		}
		
		
		//生成临时密码
		$password=par($strs[3]);
		$tmpPassword='';
		if($ok && !$password) 
		{
			//生成临时密码
			$tmpPassword=make_NoAndPa(8);
			$rnd='tmpPassword'.make_password(10);
			$password=md5($rnd.md5($tmpPassword));
		}elseif($ok && $password){
			//加密密码
			$rnd=make_password(20);
			$password=md5($rnd.md5($password));
		}
		
		
		//处理入库码
		$useric=par($strs[4]);
		if($ok && !$useric) 
		{
			//生成入库码
			$useric=createWhcod('member');
		}elseif($ok &&$useric){
			//验证入库码
			$num=NumData('member',"useric='{$useric}'");
			if($num){
				$ok=0;
				$error_result+=1;
				echo '<strong>'.$strs[1].'</strong>：入库码重复。此行没导入！<br>';
			}
		}
		

		//保存
		if ($ok)
		{
			$status=0;
			$xingao->query("insert into member (
				groupid, `username`,currency,
				`password`, `rnd`,tmp,useric,
				`money`,`integral`,checked,
				 `truename`, `enname`,
				 gender,`mobile_code`, `mobile`,
				 email,qq,wx,weibo,zip,
				 certification,shenfenhaoma,store,content,
				 addtime
			) values(
				
				'".spr($strs[0])."','".par($strs[1])."','".par($strs[2])."',
				'".add($password)."','".add($rnd)."','".add($tmpPassword)."','".add($useric)."',
				'".spr($strs[5])."','".spr($strs[6])."','".spr($strs[7])."',
				'".add($strs[8])."','".add($strs[9])."',
				'".add($strs[10])."','".add($strs[11])."','".add($strs[12])."',
				'".add($strs[13])."','".add($strs[14])."','".add($strs[15])."','".add($strs[16])."','".add($strs[17])."',
				'".spr($strs[18])."','".add($strs[19])."','".add($strs[20])."','".html($strs[21])."',
				'".time()."'

			)");
			
			SQLError('保存会员');
			$succ_result+=1;
		}
		
		
	}//for ($row=2;$row<=$highestRow;$row++)
	//导入部分-结束----------------------------------------------------------------------------------
	
	
	
	
	
	
	echo '<br><hr size="1" width="100%" />';
	echo $LG['importSuccess'].":<strong>{$succ_result}</strong><br>";
	echo $LG['importFailure'].":<strong>{$error_result}</strong><br>";

	DelFile($file);//删除文件
	//$token->drop_token("member_excel_import"); //同一个页面不能删除-处理完后删除密钥
	
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
