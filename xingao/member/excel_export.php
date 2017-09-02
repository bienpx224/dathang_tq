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
$noper='member_ot';require_once($_SERVER['DOCUMENT_ROOT'].'/xingao/incluce/session.php');
$headtitle="导出会员";
$alonepage=1;//单页形式
require_once($_SERVER['DOCUMENT_ROOT'].'/xingao/incluce/head.php');

$path='/upxingao/export/manage/'.$Xuserid.'/';//保存目录,后面要有/
if ($_GET['lx']=='del')
{
	DelAllFile($path);//删除文件
	exit("<script>goBack('c');</script>");
}


$member_cp=0;if(permissions('member_cp',0,'manage',1)){$member_cp=1;}//是否有会员高级管理权限
$member_co=0;if(permissions('member_co',0,'manage',1)||$member_cp){$member_co=1;}//是否有查看会员联系方式权限

//获取,处理
$groupid=intval($_REQUEST["groupid"]) ;
$userid=$_REQUEST["userid"];
$lx=par($_POST['lx']);
$s_r4=trim($_POST["s_r4"]);
$b_r4=trim($_POST["b_r4"]);
$tokenkey=par($_POST['tokenkey']);

if (is_array($userid)){$userid=implode(',',$userid);}
if (is_array($groupid)){$groupid=implode(',',$groupid);}

if ($lx=="tj")
{ 
	$token=new Form_token_Core();
	$token->is_token("excel_export",$tokenkey); //验证令牌密钥
	if (!$groupid&&!$userid&&!$s_r4&&!$b_r4){exit ("<script>alert('至少要选择一项！');goBack();</script>");}	

	//读取条件
	$where=" 1=1 ";
	if ($groupid)
	{
		$where.=" and groupid in ({$groupid}) ";
		
	}
	elseif($s_r4||$b_r4)
	{
		if ($s_r4)
		{
			$where.=" and userid>=".$s_r4." ";
		}
		if ($b_r4)
		{
			$where.=" and userid<=".$b_r4." ";
		}
	}
	elseif($userid)
	{
		$where.=" and userid in ({$userid})";
	}

	//导出-开始______________________________________________________________________
	/*
	注意：
		》这部分要在下面
		》生成时不能打开已生成的文件，无法报错。
		》保存中文时必须转码，用 '中文内容部分')
	*/
	require_once($_SERVER['DOCUMENT_ROOT'].'/public/PHPExcel/tem_normal.php');
	
	$excel_i=0;
	$ts='';
	$su=0;
	$query="select * from member where {$where} {$myMember} order by userid desc";
	$sql=$xingao->query($query);
	while($rs=$sql->fetch_array())
	{ 
		$excel_i+=1;
		//读取内容-开始
		  $data[] = array(
			'list1'=>$rs['userid'],
			'list2'=>cadd($rs['username']),
			'list3'=>cadd($rs['useric']),
			'list4'=>$rs['checked']?'账号开通':'账号关闭',
			'list5'=>cadd($member_per[$rs['groupid']]['groupname']),
			'list6'=>$member_co?cadd($rs['truename']):'无权限',
			'list7'=>$member_co?cadd($rs['enname']):'无权限',
			'list8'=>$member_co?Gender($rs['gender']):'无权限',
			'list9'=>$member_co?cadd($rs['mobile']):'无权限',
			'list10'=>$member_co?cadd($rs['email']):'无权限',
			'list11'=>$member_co?cadd($rs['qq']):'无权限',
			'list12'=>$member_co?cadd($rs['wx']):'无权限',
			'list13'=>$member_co?cadd($rs['zip']):'无权限',
			
			'list14'=>$rs['api']?'api开通':'api关闭',
			'list15'=>$member_cp?cadd($rs['api_key']):'无权限',
			'list16'=>$rs['certification']?'已认证':'未认证',
			'list17'=>$member_cp?cadd($rs['shenfenhaoma']):'无权限',
			'list18'=>cadd($rs['tg_username']).'('.$rs['tg_userid'].')',
			
			'list19'=>$member_cp?spr($rs['money']):'无权限',
			'list20'=>$rs['currency'],
			'list21'=>$member_cp?spr($rs['integral']):'无权限',
			'list22'=>DateYmd($rs['addtime'],1),
			'list23'=>$member_co?cadd($rs['store']):'无权限',
		  );		
		//读取内容-结束
	}
	if (!$excel_i)
	{ 
		exit ("<script>alert('{$LG['NoDataExported']}');goBack();</script>");
	} 
		
	//正式导出
	$enTable = array('list1','list2','list3','list4','list5','list6','list7','list8','list9','list10','list11','list12','list13','list14','list15','list16','list17','list18','list19','list20','list21','list22','list23');//保存列数
	$cnTable=array(
		'会员ID',//list1
		'会员名',//list2
		'入库码',//list3
		'状态',//list4
		'所属组',//list5
		
		'真实姓名',//list6
		'英文名/拼音',//list7
		'性别',//list8
		'手机',//list9
		'E-mail',//list10
		'QQ',//list11
		'微信',//list12
		'邮编',//list13
		
		'api状态',//list14
		'api key',//list15
		'认证状态',//list16
		'身份证号码',//list17
		'受邀推广员',//list18
		
		'账户',//list19
		'币种',//list19
		'积分',//list20
		'注册时间',//list21
		'网店',//list21
	);//列表名
	$excel-> getExcel($data,$cnTable,$enTable,'other',20);
	
	//20单元格宽度，如果设置为‘auto’ 表示自适应宽度，也可是具体的数字
	$ts= "导出成功，共导出<font class='red'>".$excel_i."</font>条信息!";
	$su=1;
	
	//导出-结束______________________________________________________________________
	
	$token->drop_token("excel_export"); //处理完后删除密钥
}


if($su){
?>
    <meta charset="utf-8">
    <link href="/bootstrap/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css"/>
    <br><br><br><br>
    
    <div class="alert alert-block alert-info fade in alert_cs col-md-9">
      <h4 class="alert-heading">导出成功: 共导出约有<?=$excel_i?>条</h4>
        <p><br></p>
        <p><a class="btn btn-danger" href="?lx=del">删除服务器上文件并关闭页面 (防止他人下载,下载完后请删除)</a></p>
        <p>注意:导出时会删除之前的文件，因此如果之前文件没下载完，请下载完本次导出文件后再重新导出之前的信息!</p>
    </div>
<?php
	echo '<script language=javascript>';
	echo 'location.href="'.$path.$xaname.'";';
	echo '</script>';
	XAtsto($path.$xaname);
}else{
	//生成令牌密钥(为安全要放在所有验证的最后面)
	$token=new Form_token_Core();
	$tokenkey= $token->grante_token("excel_export");
	?>
	
	<div class="alert alert-block fade in alert_cs col-md-6" style="margin-top:0px;">
	  <h3 class="page-title"> <a href="javascript:void(0)" onClick="javascript:window.location.href=window.location.href;" title="<?=$LG['refresh']?>" class="gray">
		<?=$headtitle?>
		</a> </h3>
	  <form action="" method="post" class="form-horizontal form-bordered" name="xingao">
		<input name="lx" type="hidden" value="tj">
		<input name="tokenkey" type="hidden" value="<?=$tokenkey?>">
		<div class="portlet">
		  <div class="portlet-title">
			<div class="caption"><i class="icon-reorder"></i>
			  <?=$ts?>
			</div>
			<div class="tools"> <a href="javascript:;" class="collapse"></a> </div>
		  </div>
		  <div class="portlet-body form" style="display: block;"> 
			<!--表单内容-->
			
			<div class="form-group">
			  <label class="control-label col-md-3">按组导出</label>
			  <div class="col-md-9">
				<select multiple="multiple" class="multi-select" id="my_multi_select2" name="groupid[]">
	<?php
	$query2="select groupid,groupname{$LT} from member_group  order by myorder desc,groupname{$LT} desc,groupid desc";
	$sql2=$xingao->query($query2);
	while($rs2=$sql2->fetch_array())
	{
	?>
				  <option value="<?=$rs2['groupid']?>" <?php if($groupid_arr){if (in_array($rs2['groupid'],$groupid_arr)){echo "selected";}}?>>
				  <?=$rs2['groupname'.$LT]?>
				  </option>
	<?php
	}
	?>
				</select>
			  </div>
			</div>
			
			<div class="form-group">
			  <label class="control-label col-md-3">按会员ID范围</label>
			  <div class="col-md-9">
			  从<input name="s_r4" type="text" class="form-control input-small" size="15" value="<?=$s_r4?>" />
	到
	<input name="b_r4" type="text" class="form-control  input-small" size="15"  value="<?=$b_r4?>"/>
	
			  
				<span class="help-block">只能填写纯数字，可以单独只填写一项，如果选择了按组导出，此项设置无效</span>
			   </div>
			</div>
			
			<div class="form-group">
			  <label class="control-label col-md-3">按会员ID导出</label>
			  <div class="col-md-9">
				<input type="text" class="form-control" name="userid" autocomplete="off"  value="<?=$userid?>">
				<span class="help-block">多个会员“,”隔开，如果选择了按组导出或按会员ID范围，此项设置无效</span>
			   </div>
			</div>
		  </div>
		</div>
			
			
			        
        
        
                
<!--提交按钮固定--> 
<style>body{margin-bottom:50px !important;}</style><!--后台不用隐藏,增高底部高度-->
<div align="center" class="fixed_btn" id="Autohidden">





		  <button type="submit" class="btn btn-primary input-small" id="openSmt1" disabled > <i class="icon-ok"></i> <?=$LG['export']?> </button>
		  <button type="reset" class="btn btn-default" style="margin-left:30px;"> <?=$LG['reset']?> </button>
		  <button type="button" class="btn btn-danger" onClick="goBack('c');"  style="margin-left:30px;"><i class="icon-remove"></i> <?=$LG['close']?> </button>
		</div>
	  </form>
	</div>
	</div>
<?php	
}
require_once($_SERVER['DOCUMENT_ROOT'].'/xingao/incluce/foot.php');
?>	
