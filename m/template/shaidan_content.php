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

	
//获取,处理

$lx=par($_GET['lx']);
$id=(int)$_GET['id'];
//验证,查询
if(!$_SESSION['manage']['userid']&&$lx!='html'){XAts('temp');}
if(!$id){echo ("<script>alert('id{$LG['pptError']}');goBack('uc');</script>");  goto checked; }
$rs=FeData('shaidan','*',"sdid='{$id}' and types='0'");

$classid=$rs['classid'];//每页必须有$classid
$cr=ClassData($classid);

$headtitle=leng($rs['content'],30,"...");
$headtitle.='-'.cadd($cr['name'.$LT]);

require_once($_SERVER['DOCUMENT_ROOT'].'/m/template/incluce/header.php');//放查询的后面
require_once($_SERVER['DOCUMENT_ROOT'].'/m/template/incluce/top.php');//放查询的后面
if(!$rs['checked']){echo XAts('checked');  goto checked;}
//图片扩大插件
require_once($_SERVER['DOCUMENT_ROOT'].'/public/enlarge/call.html');
?>
<link href="/css/shaidan.css" rel="stylesheet" type="text/css"/>

<!--内容开始-->
<!--图片自动缩小--> 
<script type="text/javascript" src="/js/jQuery.autoIMG.min.js"></script> 
<?php echo '<script type="text/javascript">$(function(){	$("#autoimg").autoIMG();});</script>';//用PHP输出不然DW里提示JS错误?>

<div class="article_ny" id="autoimg">
	<span class="title"><?=$LG['member']//会员?>：<?=substr_cut($rs['username'],$length=3)?><br>
<?=$LG['awb']//运单号?>：<?=substr_cut($rs['ydh'],$length=3)?></span>
	<span class="intr">
	<i class="icon-eye-open"></i> <?=$LG['browse']?>：<script src="/public/onclick.php?table=shaidan&id=<?=$rs['sdid']?>"></script> <?=$LG['main.58']?>
	<i class="icon-calendar" style="margin-left:20px;"></i> <?=$LG['release']//发布?>：<?=DateYmd($rs['edittime'],2,$rs['addtime'])?>
	</span> 
	
	<p><?=cadd($rs['content'])?></p>
	
	<?php 
	$arr=cadd($rs['img']);
	if($arr)
	{
		if(!is_array($arr)){$arr=explode(",",$arr);}//转数组
		foreach($arr as $arrkey=>$value)
		{
			echo '<img src="'.$value.'"/><br><br>';
		}
	}
	?>
	
	<a href="#pl" class="btn btn-info">
	<i class="icon-comments"></i>
	<?=$LG['comments']//评论?>
	(<font class="red" title="<?=$LG['front.151']//评论数?>"><script src="/public/onclick.php?table=shaidan&field=plclick&id=<?=$rs['sdid']?>"></script></font>)
	</a>

	<div class="row">
	<!--评论开始-->
	<a name="pl"></a>
	<iframe src="/comments/show.php?fromtable=shaidan&fromid=<?=$rs['sdid']?>" id="comments" width="100%" height="100%" frameborder="0" scrolling="auto"></iframe>
	<script type="text/javascript" language="javascript">
	//注意：下面的代码是放在和iframe同一个页面调用
	$("#comments").load(function(){
	var mainheight = $(this).contents().find("body").height()+30;
	$(this).height(mainheight);
	}); 
	</script>
	<!--评论结束-->
	</div>
				
</div>
<!--内容结束-->
<?php require_once($_SERVER['DOCUMENT_ROOT'].'/m/template/incluce/footer.php');  checked: ?>