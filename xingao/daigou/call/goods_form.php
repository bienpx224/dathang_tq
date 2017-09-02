<?php if(!$typ||$typ=='add'||$typ=='edit'||$err){?>

<?php 
if($typ=='edit')
{
	if(!$goid){exit ("<script>alert('goid{$LG['pptError']}');goBack();</script>");}
	
	$rs=FeData('daigou_goods','*',"goid='{$goid}' {$Mmy}");
	if($dg_checked&&spr($rs['status'])>=2||!$dg_checked&&spr($rs['status'])>=3){exit ("<script>alert('{$LG['daigou.104']}');goBack();</script>");}
}

$token=new Form_token_Core();	
$tokenkey=$token->grante_token("goods{$goid}");
?>

<a id="location"></a>
<script>
$(function(){
	goTop();
	$("html,body").animate({scrollTop:$("#location").offset().top},1000);
});
</script>

<br>
<br>
 
<div class="portlet" style="margin-bottom:0px;">
<div class="portlet-title">
<div class="caption"><i class="icon-plus"></i><?=$LG['daigou.122_1'];//商品信息?></div>
</div> 
</div>
         
         
<div class="page_ny">
  <form action="?<?=$urlpar?>" method="post" class="form-horizontal form-bordered" name="xingao">
    <input name="typ" type="hidden" value="<?=$typ!='edit'?'add':$typ?>">
    <input name="smt" type="hidden" value="1">
    <input name="goid" type="hidden" value="<?=$goid?>">
    <input name="tokenkey" type="hidden" value="<?=$tokenkey?>">
    <input name="tmp" type="hidden" value="<?=$tmp?>">
    <input name="userid" autocomplete="off"  type="hidden" value="<?=$rs['userid']?$rs['userid']:$userid?>">
    <input name="username" autocomplete="off" type="hidden" value="<?=$rs['username']?$rs['username']:$username?>">
    <input id="groupid" type="hidden" value="<?=$groupid?>">
   
    <div class="form">
      <div class="form-body">
    
    <?php require_once($_SERVER['DOCUMENT_ROOT'].'/xingao/daigou/call/goods_form_call.php'); ?>
      </div>
    </div>
        
    <div align="center" style="margin-top:20px; margin-bottom:20px;display:none">
      <button type="submit" class="btn btn-info input-small" id="goods_smt" style="display:none"> <i class="icon-check"></i> <?=$typ=='edit'?$LG['daigou.105']:$LG['daigou.106']?> </button>
      <button type="reset" class="btn btn-default" style="margin-left:30px;"> <?=$LG['reset']?> </button>
    </div>
    
  </form>
</div>



<?php }?>

