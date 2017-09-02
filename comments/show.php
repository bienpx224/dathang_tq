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
$noper=1;require_once($_SERVER['DOCUMENT_ROOT'].'/xamember/incluce/session.php');

$m='';if(isMobile()){$m='/m';}
require_once($_SERVER['DOCUMENT_ROOT'].$m.'/template/incluce/header.php');

//获取,处理
$fromtable=par($_GET['fromtable']);
$fromid=(int)$_GET['fromid'];

if($fromtable=='mall'){$off_code_sp=$off_code_shangpin_sp;}
elseif($fromtable=='shaidan'){$off_code_sp=$off_code_shaidan_sp;}
else{exit( "<script>alert('fromtable{$LG['pptError']}');goBack();</script>");}//防垃圾广告提交




//验证,查询
if(!$fromtable||!$fromid)
{
	exit($LG['comments.1']);
}
$search.="&fromtable={$fromtable}&fromid={$fromid}";

$query="select * from comments where fromtable='{$fromtable}' and fromid='{$fromid}' and repcmid='0' and checked='1' order by cmid desc";
//分页处理
$line=10;$page_line=15;//不设置则默认
include($_SERVER['DOCUMENT_ROOT'].'/public/page.php');
$i=0;

//图片扩大插件
require_once($_SERVER['DOCUMENT_ROOT'].'/public/enlarge/call.html');
?>
<!-- BEGIN THEME STYLES -->
<link href="/bootstrap/css/style-conquer.css" rel="stylesheet" type="text/css"/>
<link href="/bootstrap/css/style.css" rel="stylesheet" type="text/css"/>
<link href="/bootstrap/css/style-responsive.css" rel="stylesheet" type="text/css"/>
<link href="/bootstrap/css/plugins.css" rel="stylesheet" type="text/css"/>
<link href="/bootstrap/css/custom.css" rel="stylesheet" type="text/css"/>
<!-- END THEME STYLES -->
<style>
body {
	min-width:0px;
}
</style>

<div class="alert alert-block fade in alert_cs" style="padding-bottom:0px;margin-bottom:0px;"> 
  <!-- BEGIN PAGE CONTENT-->
  <div class="tabbable tabbable-custom boxless">
    <div class="tab-content">
      <div class="portlet-body">
        <ul class="chats">
          <!--输出---------------------------------------------------------------------------------------> 
          <!--回复信息--> 
          <!--===========会员或后台发表的评论===========-->
          <?php
			while($rs=$sql->fetch_array())
			{
			  if($rs['reply_userid'])
			  { 
			 	  //后台发的评论
				  $class= 'in';
				  $divclass= 'cm_manage';
				  $name= $LG['comments.10'].$rs['reply_userid'];
				  $icon='/images/manage_tx.jpg';
			  }else{
				  //会员发的评论
				  $class= 'in';
				  $divclass= '';
				  $name= $rs['username'];
				  $icon=FeData('member','img',"userid='{$rs['userid']}'");
			  }
			  
		  ?>
          <li class="<?=$class?>"> <img src="<?=$icon?>" width="50" height="50" class="avatar img-responsive" />
            <div class="message <?=$divclass?>"> <span class="arrow"></span> <a class="name">
              <?=$name?>
              </a> <span class="datetime">
              <?=DateYmd($rs['addtime'],1)?>
              </span> <span class="datetime"> 
              <a href='javascript:void(0)' onclick="document.xingao.saytext.focus();document.getElementById('saytext').innerHTML='<?=cadd($rs['content'])?>引用以上：';"><?=$LG['comments.6'];//引用?></a> </span> <span class="datetime"> <a href='javascript:void(0)' onclick="document.xingao.saytext.focus();document.xingao.repcmid.value='<?=$rs['cmid']?>';"><?=$LG['comments.11']?></a> </span> <span class="body"> 
              <font id="getbody<?=$rs['cmid']?>">
<?php 
//输出内容
$arr=cadd($rs['content']);
if($arr)
{
	if(!is_array($arr)){$arr=explode('引用以上：',$arr);}//转数组
	foreach($arr as $arrkey=>$value)
	{
		if($value){echo '<div class=cm_reply>'.LabelFace(TextareaToBr($value)).'<div>';}
	}
}
?>      
			  </font><!--这行是引用,为显示美观,不要有换行或空格-->
             <?php if($rs['img']){EnlargeImg($rs['img'],$rs['cmid']); }?>
              </span> </div>
          </li>
          <!--========会员或后台回复的评论===========-->
          <?php 
				if($rs['rep'])
				{
					$query_rep="select * from comments where repcmid='{$rs[cmid]}' and checked='1' order by cmid desc";
					$sql_rep=$xingao->query($query_rep);
					while($rep=$sql_rep->fetch_array())
					{
						  if($rep['reply_userid'])
						  { 
							  //后台回复的评论
							  $class= 'out';
							  $divclass= 'cm_manage';
							  $name=$LG['comments.10'].$rep['reply_userid'];
							  $icon='/images/manage_tx.jpg';
						  }else{
							  //会员回复的评论
							  $class= 'out';
							  $divclass= '';
							  $name= $rep['username'];
							  $icon=FeData('member','img',"userid='{$rep['userid']}'");
						  }
						  ?>
          <li class="<?=$class?>"> <img src="<?=$icon?>" width="50" height="50" class="avatar img-responsive" />
            <div class="message <?=$divclass?>"> <span class="arrow"></span> <a class="name">
              <?=$name?>
              </a> <span class="datetime">
              <?=DateYmd($rep['addtime'],1)?>
              </span> <span class="datetime"> <a href='javascript:void(0)' onclick="document.xingao.saytext.focus();document.getElementById('saytext').innerHTML='<?=cadd($rep['content'])?>引用以上：';"><?=$LG['comments.6'];//引用?></a> </span> <span class="body"> 
              <font id="getbody<?=$rep['cmid']?>">
<?php 
//输出内容
$arr=cadd($rep['content']);
if($arr)
{
	if(!is_array($arr)){$arr=explode("引用以上：",$arr);}//转数组
	foreach($arr as $arrkey=>$value)
	{
		if($value){echo '<div class=cm_reply>'.LabelFace(TextareaToBr($value)).'<div>';}
	}
}
?>      
			</font>
			  <?php if($rep['img']){EnlargeImg($rep['img'],$rep['cmid']); }?>
              </span> </div>
          </li>
          <?php
					}
				}
		  
			}//while($rs=$sql->fetch_array())
		 ?>
        </ul>
        <br>
        <div class="row">
          <?=$listpage?>
        </div>
        
        <!--表单---------------------------------------------------------------------------------->
        <?php if(!$Muserid){?>
        <a href="/xamember/" target="_blank"  class="btn btn-info"  title="<?=$LG['comments.7'];//如果您已经登录,请刷新本页?>"><?=$LG['comments.12']?> </a>
        <?php }else{
		//生成令牌密钥(为安全要放在所有验证的最后面)
		$token=new Form_token_Core();
		$tokenkey= $token->grante_token('comments'.$fromid);
		?>
        <form action="save.php" method="post" class="form-horizontal form-bordered" name="xingao">
          <input name="lx" type="hidden" value="add">
          <input name="fromtable" type="hidden" value="<?=$fromtable?>">
          <input name="fromid" type="hidden" value="<?=$fromid?>">
          <input name="repcmid" type="hidden" value="0">
          <input name="tokenkey" type="hidden" value="<?=$tokenkey?>">
          <div class="chat-form"> 
            <script src='/js/addface.js'></script>
            <div align="left">
              <?php
			  //列出目录下所有表情
            $file=ShowFile($path='/images/face/');
            if($file)
            {
                foreach($file as $arrkey=>$value)
                {
                    if($value!='.' && $value!='..')
                    {
                        echo " <a href='javascript:void(0)' onclick=\"eaddplface('[~".$value."~]');\"><img src='".$path.$value."' border=0></a>";
                    }
                
                }
            }
            ?>
            </div>
            <div class="input-cont" style="margin-right:0px; margin-top:10px;">
              <textarea  class="form-control" rows="4" name="content" id="saytext" placeholder="<?=$LG['comments.9'];//评论内容...?>" ></textarea>
            </div>
            <?php if($off_code_sp){?>
            <div class="form-group">
              <label class="control-label col-md-0"><?=$LG['code'];//验 证 码?></label>
              <div class="col-md-0">
                <input name="code" id="code" type="text" class="form-control placeholder-no-fix input-small pull-left" placeholder="<?=$LG['codeShort'];//验证码?>" style="margin-right:10px;" autocomplete="off"  maxlength="10" required onkeyup="checkcode('pl');"  title="<?=$LG['codePpt1'];//不分大小写?>"/>
                <span align="left"><span id="msg_code"></span> <img src="/images/code.gif" onclick="codeimg.src='/public/code/?v=pl&rm='+Math.random()" id="codeimg" title="<?=$LG['codePpt2'];//看不清，点击换一张(不分大小写)?>"  width="100" height="35"/></span> </div>
            </div>
            <?php }?>
            <div align="right" style="margin-top:10px;">
              <button type="submit" class="btn btn-primary input-small" style="margin-left:30px;" required> <i class="icon-ok"></i> <?=$LG['submit']?> </button>
              <button type="reset" class="btn btn-default" style="margin-left:30px;"> <?=$LG['reset']?> </button>
            </div>
<?php 
//文件上传配置
$uplx='img';//img,file
$uploadLimit='10';//允许上传文件个数(如果是单个，此设置无法，默认1)
$inputname='img[]';//保存字段名，多个时加[]

//$off_water=0;//水印(不手工设置则按后台设置)
//$off_narrow=1;//是否裁剪
//$img_w=$certi_w;$img_h=$certi_h;//裁剪尺寸：证件
$img_w=$other_w;$img_h=$other_h;//裁剪尺寸：通用
//$img_w=500;$img_h=500;//裁剪尺寸：指定
include($_SERVER['DOCUMENT_ROOT'].'/public/uploadify/call.php');
?>


          </div>
        </form>
        <?php }?>
      </div>
    </div>
  </div>
</div>


<?php require_once($_SERVER['DOCUMENT_ROOT'].'/js/checkJS.php');//通用验证?>