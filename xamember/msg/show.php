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
$headtitle=$LG['msg.show_1'];//站内信
$alonepage=1;
require_once($_SERVER['DOCUMENT_ROOT'].'/xamember/incluce/head.php');

//获取,处理
$lx=par($_GET['lx']);
$id=par($_GET['id']);
if(!$lx){$lx='reply';}

$token=new Form_token_Core();
$tokenkey= $token->grante_token('msg'.$id); //生成令牌密钥


//验证,查询
if(!$id)
{
	exit ("<script>alert('ID{$LG['pptError']}');goBack('uc');</script>");
}



$rs=FeData('msg','*',"id='{$id}' {$Mmy}");
$userimg=FeData('member','img',"userid='{$rs['userid']}'");

if(!$rs['id'])
{
	exit ("<script>alert('{$LG['msg.show_2']}');goBack('uc');</script>");
}

//更新主信息状态：已读
$xingao->query("update msg set new='0' where id='{$id}' {$Mmy}");

?>

<div class="alert alert-block fade in alert_cs col-md-8" style="margin-top:0px;"> 
  <!-- BEGIN PAGE HEADER-->
	<div><!--单页时去掉 class="row",不然会有横滚动条,并且form 加 style="margin:20px;"-->
		<div class="col-md-12"> 
<h3 class="page-title" align="left"><i class="icon-comments-alt"></i> <a href="javascript:void(0)" onClick="javascript:window.location.href=window.location.href;" title="<?=$LG['refresh']?>" class="gray" style="font-size:18px"><?=cadd($rs['title'])?></a>
         <?=MsgStatus(spr($rs['status']))?></h3>
         
         
    </div>
  </div>
  <!-- END PAGE HEADER--> 
  
  <!-- BEGIN PAGE CONTENT-->
  
  <div class="tabbable tabbable-custom boxless">
    <div class="tab-content">
      <div class="portlet-body">
        <ul class="chats">
          <!--主信息-->
          <li class="in"> <img class="avatar img-responsive" src="<?=$userimg?>" width="50" height="50"/>
            <div class="message"> <span class="arrow"></span> <a href="../xamember/form.php?lx=edit&userid=<?=$rs['userid']?>" class="name" target="_blank">
              <?=$rs['username']?>
              </a> <span class="datetime">
              <?=DateYmd($rs['addtime'],1)?>
              </span> <span class="body">
              <?=TextareaToBr($rs['content'])?>
              <?php if($rs['file']){?>
              <br>
              <button type="button"  class="btn btn-success" onClick="window.open('<?=$rs['file']?>');"><i class="icon-file"></i> <?=$LG['msg.show_3'];//查看附件?></button>
              <?php }?>
              </span> </div>
          </li>
          
          <!--回复信息-->
          <?php
							  $query2="select * from msg_reply where msgid='{$rs[id]}' order by id asc";
							  $sql2=$xingao->query($query2);
							  while($rs2=$sql2->fetch_array())
							  {
								if($rs2['reply_userid'])
								{
							?>
          <li class="out"><!--后台--> 
            <img src="/images/manage_tx.jpg" width="50" height="50" class="avatar img-responsive" />
            <div class="message"> <span class="arrow"></span> <a class="name"><?=$LG['msg.show_5']?>
              <?=$rs2['reply_userid']?>
              </a> <span class="datetime">
              <?=DateYmd($rs2['addtime'],1)?>
              </span> <span class="body">
              <?=cadd($rs2['content'])?>
              <?php if($rs2['file']){?>
              <br>
              <button type="button"  class="btn btn-success" onClick="window.open('<?=$rs2['file']?>');"><i class="icon-file"></i> <?=$LG['msg.show_3'];//查看附件?></button>
              <?php }?>
              </span> </div>
          </li>
          <?php }else{ ?>
          <li class="in"><!--会员--> 
            <img class="avatar img-responsive" src="<?=$userimg?>" width="50" height="50"/>
            <div class="message"> <span class="arrow"></span> <a href="../xamember/form.php?lx=edit&userid=<?=$rs['userid']?>" class="name" target="_blank">
              <?=$rs['username']?>
              </a> <span class="datetime">
              <?=DateYmd($rs2['addtime'],1)?>
              </span> <span class="body">
              <?=cadd($rs2['content'])?>
              <?php if($rs2['file']){?>
              <br>
              <button type="button"  class="btn btn-success" onClick="window.open('<?=$rs2['file']?>');"><i class="icon-file"></i> <?=$LG['msg.show_3'];//查看附件?></button>
              <?php }?>
              </span> </div>
          </li>
          <?php
								}
							  }//while($rs2=$sql2->fetch_array())
						   ?>
        </ul>
        <?php if(spr($rs['status'])!=1){?>
        <form action="save.php" method="post" class="form-horizontal form-bordered" name="xingao">
          <input name="lx" type="hidden" value="<?=add($lx)?>">
          <input name="id" type="hidden" value="<?=$rs['id']?>">
          <input name="tokenkey" type="hidden" value="<?=$tokenkey?>">
          <div class="chat-form">
            <div class="input-cont" style="margin-right:0px;" align="left">
            
          
            <div class="radio-list">  
          
             <?=MsgStatus($zhi='',2)?>  
                <label class="radio-inline"><font class="red">*</font></label>          
           </div>
            <br>

              <textarea  class="form-control" rows="4" name="content" placeholder="<?=$LG['msg.show_4'];//回复内容...?>" ></textarea>
            </div>
            <div align="right" style="margin-top:10px;">
                        
       <?php if( $off_code_liuyan){?>
      <?=$LG['code'];//验证码?>
          <input name="code" id="code" type="text" class="form-control placeholder-no-fix input-small" placeholder="<?=$LG['codeShort'];//验证码?>" style="margin-right:10px;" autocomplete="off"  maxlength="10" required onkeyup="checkcode('gbook');"  title="<?=$LG['codePpt1'];//不分大小写?>"/>
          <span align="left"><span id="msg_code"></span> <img src="/images/code.gif" onclick="codeimg.src='/public/code/?v=gbook&rm='+Math.random()" id="codeimg" title="<?=$LG['codePpt2'];//看不清，点击换一张(不分大小写)?>"  width="100" height="35"/></span>
      <?php }?>
      


              <button type="submit" class="btn btn-primary input-small" id="openSmt1" disabled  style="margin-left:30px;" required> <i class="icon-ok"></i> <?=$LG['submit']?> </button>
              <button type="reset" class="btn btn-default" style="margin-left:30px;"> <?=$LG['reset']?> </button>
            </div>
            <?php 
//文件上传配置
$uplx='file';//img,file
$uploadLimit='10';//允许上传文件个数(如果是单个，此设置无法，默认1)
$inputname='file';//保存字段名，多个时加[]

$off_water=0;//水印(不手工设置则按后台设置)
$off_narrow=1;//是否裁剪
//$img_w=$certi_w;$img_h=$certi_h;//裁剪尺寸：证件
//$img_w=$other_w;$img_h=$other_h;//裁剪尺寸：通用
$img_w=500;$img_h=500;//裁剪尺寸：指定
$rsfile_my='no';//指定文件，no则空
include($_SERVER['DOCUMENT_ROOT'].'/public/uploadify/call.php');
?>
          </div>
        </form>
        <?php }?>
      </div>
    </div>
  </div>
</div>

<?php
require_once($_SERVER['DOCUMENT_ROOT'].'/js/checkJS.php');//通用验证
require_once($_SERVER['DOCUMENT_ROOT'].'/xamember/incluce/foot.php');
?>
