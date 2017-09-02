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

if(!defined('InXingAo'))
{
	exit('No InXingAo');
}

//信息提示-------------------------------------------------------------------------------------------------
/*
	$tslx='' 通用类型,其他请看内部代码
	$color= info淡蓝色;warning橘色; danger红色;success绿色
	$button 自定义按钮
	$exit 是否结束
	$buttonClose 是否显示关闭按钮
*/

/*
	另一种提示
	$alert_color='success'; if(!$ok){$alert_color='danger';}
	if($ppt)
	{
	  echo '<div class="alert alert-'.$alert_color.'">'.$ppt.'</div>';
	}
	
	简单:<?php 	if($_GET['ret']){echo '<div class="alert alert-success">添加成功</div>';} ?> 
*/


function XAts($tslx='',$color='info',$title='',$content='',$button='return',$exit='0',$buttonClose='1')
{
	require($_SERVER['DOCUMENT_ROOT'].'/public/global.php');
	
	if(!$title){$title=$LG['ppt'];}
	if($button=='return'){$button='<a class="btn btn-info" href="javascript:history.go(-1)">'.$LG['function.51'].'</a>';}
	echo '
			<meta charset="utf-8">
			<link href="/bootstrap/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css"/>
			<br><br><br><br>
		';
	//通用类型
	switch($tslx)
	{
		case '':
			echo '
			<div class="alert alert-block alert-'.$color.' fade in alert_cs col-md-9">
			  <h4 class="alert-heading">'.$title.'</h4>
			  <p>'.$content.'</p>
			  <p><br>
			  '.$button.'
			  ';
			if($buttonClose)
			{
				echo '<a class="btn btn-danger" href="#" onClick="window.opener=null;window.open(\'\',\'_self\');window.close();" >'.$LG['closePage'].'</a></p>';		
			}
			echo '</div>';
			
			if($exit){exit();}
		break;
		
		//以下是指定类型
		
		case 'temp':
			exit("<script>alert('{$LG['function.52']}');goBack('uc');</script>");
		break;
		
		case 'checked':
			echo '
			<div class="alert alert-block alert-danger fade in alert_cs col-md-9">
			  <h4 class="alert-heading">'.$LG['function.53'].'</h4>
			  <p>'.$LG['function.50'].'</p>
			  <p><br>
			 <a class="btn btn-info" href="javascript:history.go(-1)">'.$LG['function.51'].'</a> <a class="btn btn-danger" href="#" onClick="window.opener=null;window.open(\'\',\'_self\');window.close();" >'.$LG['closePage'].'</a></p>
			</div>
			';
		break;
		
		case 'mall_checked':
			echo '
			<div class="alert alert-block alert-danger fade in alert_cs col-md-9">
			  <h4 class="alert-heading">'.$LG['function.53'].'</h4>
			  <p>'.$LG['function.63'].'</p>
			  <p><br>
			  <a class="btn btn-danger" href="#" onClick="window.opener=null;window.open(\'\',\'_self\');window.close();" >关闭页面</a></p>
			</div>
			';
		break;
		
		case 'login_member':
			echo '
			<div class="alert alert-block alert-danger fade in alert_cs col-md-9">
			  <h4 class="alert-heading">'.$LG['function.64'].'</h4>
			  <p>'.$LG['function.65'].'</p>
			  <p> <br>
			 <a href="'.$button.'" class="btn btn-info input-medium"  style="color:#ffffff">'.$LG['function.66'].'</a>
			 
			 &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
			 <a href="'.$button.'" class="btn btn-default input-medium"  target="_blank">'.$LG['function.67'].'</a>
			 <a href="javascript:window.location.reload();" class="btn btn-default">'.$LG['function.68'].'</a>
			</div>
			';
			exit();
		break;
		
		case 'login_manage':
			echo '
			<div class="alert alert-block alert-danger fade in alert_cs col-md-9">
			  <h4 class="alert-heading">'.$LG['function.69'].'</h4>
			  <p>'.$LG['function.70'].'</p>
			</div>
			';
			exit();
		break;
		
		case 'pay_success':
			echo '
			<div class="alert alert-block alert-info fade in alert_cs col-md-9">
			  <h4 class="alert-heading">'.$LG['function.71'].'</h4>
			  <p>'.$LG['function.72'].'</p>
			  <p><br>
			 <a class="btn btn-info" href="/xamember/money/money_czbak.php">'.$LG['function.73'].'</a> <a class="btn btn-danger" href="#" onClick="window.opener=null;window.open(\'\',\'_self\');window.close();" >'.$LG['closePage'].'</a></p>
			</div>
			';
	    break;
		
		case 'pay_failure':
			echo '
			<div class="alert alert-block alert-danger fade in alert_cs col-md-9">
			  <h4 class="alert-heading">'.$LG['function.61'].'</h4>
			  <p>'.$content.'</p>
			  <p><br>
			 <a class="btn btn-danger" href="#" onClick="window.opener=null;window.open(\'\',\'_self\');window.close();" >'.$LG['closePage'].'</a></p>
			</div>
			';
		break;
	}
}

//页面指定位置提示-------------------------------------------------------------------------------------------------
/*
	$typ 颜色类型：success	info	warning	error
	$location 位置：
		toast-top-right
		toast-bottom-right
		toast-bottom-left
		toast-top-left
		toast-top-center
		toast-bottom-center
		toast-top-full-width
		toast-bottom-full-width
*/
function XAtoastr($content,$typ='info',$location='toast-bottom-right')
{
	if(!$content||!$typ){return;}
?>
<script>
$(function(){
  //具体参数，请在Bootstrap模板中直接生成代码
  toastr.options = {
	"closeButton": true,
	"debug": false,
	"positionClass": "<?=$location?>",
	"onclick": null,
	"showDuration": "1000",
	"hideDuration": "1000",
	"timeOut": "5000",
	"extendedTimeOut": "1000",
	"showEasing": "swing",
	"hideEasing": "linear",
	"showMethod": "show",
	"hideMethod": "hide"
  }
  
  toastr.<?=$typ?>('<?=$content?>');
});
</script>

<?php
}




//页面固定位置提示-------------------------------------------------------------------------------------------------
/*
	$typ=	success	info warning	danger  well(灰色)
	$style= 指定样式
*/
function XAalert($content,$typ='info',$style='')
{
	if(!$content||!$typ){return;}
	
	if($typ=='well'){echo '<div class="'.$typ.'"   '.($style?'style="'.$style.'"':'').'>'.$content.'</div>';}
	else{echo '<div class="alert alert-'.$typ.'"   '.($style?'style="'.$style.'"':'').'>'.$content.'</div>';}
	
}


//转向提示-------------------------------------------------------------------------------------------------
function XAtsto($url='')
{
	require($_SERVER['DOCUMENT_ROOT'].'/public/global.php');
	echo '<div align="center"><br><br><br><a href="'.$url.'"><strong>'.$LG['function.62'].'</strong></a></div>';
}

//排序颜色-------------------------------------------------------------------------------------------------
function orac($var)
{
	global $_GET,$desc;
	
	if($desc=='asc'){$order_img='icon-sort-by-attributes';}else{$order_img='icon-sort-by-attributes-alt';}
	if(par($_GET['orderby'])==$var){return 'order_activity '.$order_img;}else{return 'order_default';}
}


//显示SQL错误，对查询无效-------------------------------------------------------------------------------------------------
function SQLError($ts='')
{
	global $xingao,$sqlerrorshow;
	$error=mysqli_error($xingao);
	if($error)
	{
		if($ts){$ts='['.$ts.']';}
		if($sqlerrorshow)
		{
			exit('<strong>兴奥转运系统数据库错误提示-'.$ts.'：</strong>'.$error);
		}else{
			exit('<strong>兴奥转运系统数据库错误提示-'.$ts.'：</strong>未开启显示错误信息');
		}
	}
}

//点击弹出提示-------------------------------------------------------------------------------------------------
/*
	$content=内容;
	$title=标题;
	$time=显示时间;
	$nameid=独立的弹出ID;
	$count=超过多少字符才显示弹出;
	$html=是否支持HTML;
	$link_name=显示按钮的名称;可自行指定； $link_name='' 名称‘全部’；$link_name='empty' 不显示按钮；
	$backClose=1 点背景关闭;0点背景不关闭;
*/
function Modals($content,$title,$time='',$nameid,$count=100,$html=0,$link_name='',$backClose=1)
{
	require($_SERVER['DOCUMENT_ROOT'].'/public/global.php');
	if(!$count||fnCharCount($content)>$count)
	{
		if($html){$content=caddhtml($content);}else{$content=TextareaToBr($content);}
		
		if(!$link_name){echo '<a class="btn btn-xs btn-default" data-toggle="modal" href="#'.$nameid.'">'.$LG['all'].'</a>';}
		elseif($link_name!='empty'){echo '<a data-toggle="modal" href="#'.$nameid.'">'.$link_name.'</a>';}
		
		
		$modal='role="basic" aria-hidden="true"';
		if(!$backClose){$modal='data-backdrop="static" data-keyboard="false"';}
		
		
		echo '
		<div class="modal fade" id="'.$nameid.'" tabindex="-1"  '.$modal.'>
		  <div class="modal-dialog modal-wide">
			 <div class="modal-content">
				<div class="modal-header">
				   <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
				   <h4 class="modal-title">'.$title.'</h4>
				</div>
				<div class="modal-body">
				   '.$content.'
				   <div align="right" class="gray2">'.DateYmd($time).'</div>
				</div>
				<div class="modal-footer">
				   <button type="button" class="btn btn-danger" data-dismiss="modal"> '.$LG['close'].' </button>
				</div>
			 </div>
		  </div>
		</div>
		';
    }
}



//提示音
function music($typ='yes')
{
	global $ON_MusicYes,$ON_MusicNo;
	
	if($typ=='yes'&&$ON_MusicYes)
	{
		echo '<audio src="/images/music/yes.wav" autoplay>
		<!--浏览器不支持 audio 标签时-->
		<embed src="/images/music/yes.wav" autostart="true" loop="false" hidden="true" width=0 height=0></embed>
		</audio>';
	}elseif($typ=='no'&&$ON_MusicNo){
		echo '<audio src="/images/music/no.wav" autoplay>
		<!--浏览器不支持 audio 标签时-->
		<embed src="/images/music/no.wav" autostart="true" loop="false" hidden="true" width=0 height=0></embed>
		</audio>';
	}
}
?>