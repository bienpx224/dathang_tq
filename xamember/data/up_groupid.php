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
$headtitle=$LG['data.up_groupid_1'];//会员升级
require_once($_SERVER['DOCUMENT_ROOT'].'/xamember/incluce/head.php');

//通用查询
$up_type_have=0;
$rs=FeData('member_group','groupid,up_groupid,up_groupid_integral,up_groupid_max_cz_once,up_groupid_max_cz_more',"groupid={$Mgroupid}");
$mr=FeData('member','integral,max_cz_once,max_cz_more',"1=1 {$Mmy}");

//保存处理
$lx=par($_POST['lx']);
if($lx=='up')
{
	$up_groupid=spr($_POST['up_groupid']);
	$up_type=spr($_POST['up_type']);
	$tokenkey=par($_POST['tokenkey']);
	
	//初步验证
	$token=new Form_token_Core();
	$token->is_token('up_groupid',$tokenkey); //验证令牌密钥
	if(!$up_groupid){exit ("<script>alert('{$LG['data.up_groupid_2']}');goBack();</script>");}
	if(!$up_type){exit ("<script>alert('{$LG['data.up_groupid_11']}');goBack();</script>");}

	//处理验证
	if($up_type==1&&$rs['up_groupid_integral']<0){exit ("<script>alert('{$LG['data.up_groupid_12']}');goBack();</script>");}
	elseif($up_type==2&&$rs['up_groupid_max_cz_once']<0){exit ("<script>alert('{$LG['data.up_groupid_12']}');goBack();</script>");}
	elseif($up_type==3&&$rs['up_groupid_max_cz_more']<0){exit ("<script>alert('{$LG['data.up_groupid_12']}');goBack();</script>");}

	if($up_type==1&&$mr['integral']<$rs['up_groupid_integral']){exit ("<script>alert('{$LG['data.up_groupid_3']}');goBack();</script>");}
	elseif($up_type==2&&$mr['max_cz_once']<$rs['up_groupid_max_cz_once']){exit ("<script>alert('{$LG['data.up_groupid_4']}');goBack();</script>");}
	elseif($up_type==3&&$mr['max_cz_more']<$rs['up_groupid_max_cz_more']){exit ("<script>alert('{$LG['data.up_groupid_4']}');goBack();</script>");}
	
	//处理保存
	$save="groupid='{$up_groupid}'";
	if($up_type==1){
		$title=LGtag($LG['data.up_groupid_13'],'<tag1>=='.$up_groupid);
		integralKF($Muserid,'','',$rs['up_groupid_integral'],'',$title,'',$type=100);
	}elseif($up_type==2){
		$save.=",max_cz_once=max_cz_once-".$rs['up_groupid_max_cz_once'];
		$save.=",max_cz_more=max_cz_more-".$rs['up_groupid_max_cz_once'];
	}elseif($up_type==3){
		$save.=",max_cz_more=max_cz_more-".$rs['up_groupid_max_cz_more'];
		$save.=",max_cz_once=max_cz_once-".$rs['up_groupid_max_cz_more'];
	}else{
		exit ("<script>alert('{$LG['data.up_groupid_15']}');goBack();</script>");
	}
	
	$xingao->query("update member set {$save} where 1=1 {$Mmy}");SQLError('修改');
	$rc=mysqli_affected_rows($xingao);
	$_SESSION['member']['groupid']=$up_groupid;
	
	$token->drop_token('up_groupid'); //处理完后删除密钥
	exit ("<script>alert('{$LG['data.up_groupid_6']}');location='/xamember/data/up_groupid.php';</script>");
}

//生成令牌密钥(为安全要放在所有验证的最后面)
$token=new Form_token_Core();
$tokenkey= $token->grante_token('up_groupid');
?>

<div class="page_ny"> 
  <!-- BEGIN PAGE HEADER-->
	<div class="row"><!--单页时去掉 class="row",不然会有横滚动条,并且form 加 style="margin:20px;"-->
		<div class="col-md-12"> 
<h3 class="page-title"> <a href="javascript:void(0)" onClick="javascript:window.location.href=window.location.href;" title="<?=$LG['refresh']?>" class="gray">
        <?=$headtitle?>
        </a> <small>
        <?=$member_per[$rs['groupid']]['groupname']?>
        </small> </h3>
    </div>
  </div>
  <!-- END PAGE HEADER--> 
  
  <!-- BEGIN PAGE CONTENT-->
  
  <form action="?" method="post" class="form-horizontal form-bordered" name="xingao">
    <input name="lx" type="hidden" value="up">
    <input name="tokenkey" type="hidden" value="<?=$tokenkey?>">
    <div class="tabbable tabbable-custom boxless">
      <div>
              <div class="portlet">
                <div class="portlet-title">
                  <div class="caption"><i class="icon-reorder"></i><strong><!--选择升级--></strong></div>
                  <div class="tools"> <a href="javascript:;" class="collapse"></a> </div>
                </div>
                <div class="portlet-body form" style="display: block;"> 
                  <!--表单内容-->
                  
                  <div class="form-group">
                    <label class="control-label col-md-2"><?=$LG['data.up_groupid'];//可升级的会员组?></label>
                    <div class="col-md-10 has-error">
                      <select  class="form-control input-medium select2me" data-placeholder="Select..." name="up_groupid">
<?php 
if($rs['up_groupid'])
{
	$query_up="select groupid,groupname{$LT} from member_group where checked=1 and groupid<>'{$Mgroupid}' and groupid in ({$rs[up_groupid]}) order by  myorder desc,groupname{$LT} desc,groupid desc";
	$sql_up=$xingao->query($query_up);
	while($up=$sql_up->fetch_array())
	{
	?>
		<option value="<?=$up['groupid']?>"><?=$up['groupname'.$LT]?></option>
	<?php
	}
}else{
	echo '<option>'.$LG['data.up_groupid_16'].'</option>';
}
?>
                      </select>
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="control-label col-md-2"><?=$LG['data.up_type'];//升级方式?></label>
                    <div class="col-md-10">
                      <div class="radio-list">
					  <?php if($rs['up_groupid_integral']>=0){$up_type_have=1;?>
						 <label>
						 <input type="radio" name="up_type" value="1" <?=$mr['integral']<$rs['up_groupid_integral']?'disabled':'checked'?>> 
						 <?=LGtag($LG['data.up_groupid_17'],'<tag1>=='.spr($rs['up_groupid_integral']) )?>
						  <font class="red2"><?=$mr['integral']<$rs['up_groupid_integral']?'('.$LG['data.up_groupid_19'].')':''?></font>
						 </label>
					 <?php }?>
					 
					 <?php if($rs['up_groupid_max_cz_once']>=0){$up_type_have=1;?>
						 <label>
						 <input type="radio" name="up_type" value="2" <?=$mr['max_cz_once']<$rs['up_groupid_max_cz_once']?'disabled':'checked'?>> 
                         <?=LGtag($LG['data.up_groupid_20'],'<tag1>=='.spr($rs['up_groupid_max_cz_once'].$XAmc) )?>
						  <font class="red2"><?=$mr['max_cz_once']<$rs['up_groupid_max_cz_once']?'('.$LG['data.up_groupid_22'].'：<a href="/xamember/money/money_cz.php" target="_blank"><strong>'.$LG['data.up_groupid_23'].'</strong></a> )':''?></font>
						 </label>
					<?php }?>
					 
					 <?php if($rs['up_groupid_max_cz_more']>=0){$up_type_have=1;?>
						 <label>
						 <input type="radio" name="up_type" value="3" <?=$mr['max_cz_more']<$rs['up_groupid_max_cz_more']?'disabled':'checked'?>> 
                         
                         <?=LGtag($LG['data.up_groupid_24'],'<tag1>=='.spr($rs['up_groupid_max_cz_more'].$XAmc) )?>
						 <font class="red2"><?=$mr['max_cz_more']<$rs['up_groupid_max_cz_more']?'('.$LG['data.up_groupid_22'].'：<a href="/xamember/money/money_cz.php" target="_blank"><strong>'.$LG['data.up_groupid_23'].'</strong></a> )':''?></font>
						 </label>
					<?php }?>
						 
					<?php if(!$up_type_have){echo $LG['data.up_groupid_7'];}else{?>
						<br><span class="xats">&raquo; <?=$LG['data.up_groupid_26']?></span>
					<?php }?>
						</div>
					  
                    </div>
                  </div>
				  
                </div>
              </div>


        <div align="center">
          <button type="submit" class="btn btn-primary input-small" id="openSmt1" disabled  style="margin-left:30px;"> <i class="icon-ok"></i> <?=$LG['data.up_groupid_10'];//升 级?> </button>
        </div>
      </div>
    </div>
  </form>
</div>

<br>
<br>
<br>
<br>


<style>
.thead_color{background-color:#7EC4E0; color:#FFFFFF;}
</style>
<?php
require_once($_SERVER['DOCUMENT_ROOT'].'/xamember/incluce/zengsong.php');
?>

<?php
require_once($_SERVER['DOCUMENT_ROOT'].'/xamember/incluce/foot.php');
?>
