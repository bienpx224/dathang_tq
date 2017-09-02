<?php 
//获取处理
$step=par($_GET['step']);if(!$step){$step=1;}
$type=par($_POST['type']);

if($_GET['back']==1){unset($_SESSION['upload']['ydid']);}


//通用验证------------------------------------
if($type){
	$tokenkey=par($_POST['tokenkey']);
	$token=new Form_token_Core();
	$token->is_token('upload',$tokenkey); //验证令牌密钥
}

//第一步:验证------------------------------------------------------------
if($type=='smt1'){
	$ydh=par($_POST['ydh']);
	$op_1=par($_POST['op_1']);
	$s_name=par($_POST['s_name']);
	$s_mobile=par($_POST['s_mobile']);
	$code=strtolower(par($_POST['code']));
	unset($_SESSION['upload']['ydid']);
	
	//验证
	if(!$code){exit ( "<script>alert('{$LG['codeEmpty']}');goBack();</script>");}
	if(!$s_name||!$s_mobile){exit ( "<script>alert('{$LG['front.46']}');goBack();</script>");}
	
	$vname=xaReturnKeyVarname('ot');$code_se=$_SESSION[$vname];
	if($code!=$_SESSION[$vname]){unset($_SESSION[$vname]);exit ( "<script>alert('{$LG['codeOverdue']}');goBack();</script>");}
	unset($_SESSION[$vname]);
	
	$where="status<30 and s_name='{$s_name}' and s_mobile='{$s_mobile}'";
	if($op_1){$where.=" and (s_shenfenhaoma='' or s_shenfenimg_z='' or s_shenfenimg_b='')";}
	if($ydh)
	{
		$where.=" and ydh='{$ydh}'";
		$rs=FeData('yundan','userid,ydid,warehouse,channel,s_shenfenimg_z,s_shenfenimg_b',$where);
		$ydid=$rs['ydid'];
		if(!channelPar($rs['warehouse'],$rs['channel'],'shenfenzheng')){exit ("<script>alert('{$LG['front.31']}');goBack();</script>");}
	}else{
		$query="select ydid,warehouse,channel from yundan where {$where}";
		$sql=$xingao->query($query);
		while($rs=$sql->fetch_array())
		{
			if(channelPar($rs['warehouse'],$rs['channel'],'shenfenzheng')){$ydid.=$rs['ydid'].',';}
		}
		$ydid=DelStr($ydid);
	}
	
	if(!$ydid){exit ("<script>alert('{$LG['front.32']}');goBack();</script>");}
	$_SESSION['upload']['ydid']=$ydid;
	$step=2;
	$token->drop_token('upload'); //处理完后删除密钥
}





//第二步:上传------------------------------------------------------------
elseif($type=='smt2'){
	$s_shenfenhaoma=par($_POST['s_shenfenhaoma']);
	$s_shenfenimg_z=$_POST['s_shenfenimg_z'];
	$s_shenfenimg_b=$_POST['s_shenfenimg_b'];
	
	if(!$_SESSION['upload']['ydid']){exit ( "<script>alert('{$LG['front.33']}');location='?back=1';</script>");}	
	if(!$s_shenfenhaoma||!$s_shenfenimg_z||!$s_shenfenimg_b){exit ( "<script>alert('{$LG['front.34']}');location='?step=2';</script>");}	
	
	//上传
	$rc=0;
	$query="select * from yundan where ydid in ({$_SESSION['upload']['ydid']})";
	$sql=$xingao->query($query);
	while($rs=$sql->fetch_array())
	{
		//删除旧证件
		DelFile(cadd($rs['s_shenfenimg_z']));
		DelFile(cadd($rs['s_shenfenimg_b']));

		//复制证件
		$shenfenimg_z_now='/upxingao/card/'.DateYmd(time(),2).'/'.newFilename($s_shenfenimg_z);
		CopyFile($s_shenfenimg_z,$shenfenimg_z_now);
		
		$shenfenimg_b_now='/upxingao/card/'.DateYmd(time(),2).'/'.newFilename($s_shenfenimg_b);
		CopyFile($s_shenfenimg_b,$shenfenimg_b_now);

		$xingao->query("update yundan set s_shenfenhaoma='{$s_shenfenhaoma}',s_shenfenimg_z='{$shenfenimg_z_now}',s_shenfenimg_b='{$shenfenimg_b_now}' where ydid='{$rs['ydid']}'");
		SQLError('修改');
		$rc+=mysqli_affected_rows($xingao);
		
		//更新或添加地址簿
		$rs['s_shenfenhaoma']=$s_shenfenhaoma;
		$rs['s_shenfenimg_z']=$shenfenimg_z_now;
		$rs['s_shenfenimg_b']=$shenfenimg_b_now;
		$_POST=$rs;$upload_cert=1;
		$content_address=$LG['front.35'];
		$Mmy=" and userid='{$_POST['userid']}'";$Muserid=spr($_POST['userid']);$Musername=add($_POST['username']);
		$sf='s';require($_SERVER['DOCUMENT_ROOT'].'/xingao/yundan/call/address_save.php');
		$Muserid='';$Musername='';
	

	}

	DelFile($s_shenfenimg_z);//删除文件
	DelFile($s_shenfenimg_b);//删除文件
	
	if($rc>0)
	{
		unset($_SESSION['upload']['ydid']);
		$token->drop_token('upload'); //处理完后删除密钥
		exit("<script>alert('".LGtag($LG['front.36'],'<tag1>=='.$rc)."');location='?back=1';</script>");
	}else{
		exit ("<script>alert('{$LG['front.37']}');goBack();</script>");
	}
}



//生成令牌密钥(为安全要放在所有验证的最后面)
$token=new Form_token_Core();
$tokenkey= $token->grante_token('upload');


//输出
if ($step==1||!$_SESSION['upload']['ydid']){
?>
     <!--第一步:验证------------------------------------------------------------ -->
     <form action="?" method="post" class="form-horizontal form-bordered" name="xingao" style="margin:20px;">
	 <input name="type" type="hidden" value="smt1">
     <input name="tokenkey" type="hidden" value="<?=$tokenkey?>">
				  
    <div class="form-group">
        <label class="control-label col-md-3"><?=$LG['yundan.s_name'];//收件人姓名?></label>
        <div class="col-md-9 has-error">
            <input type="text" class="form-control input-medium" name="s_name"  value="<?=$s_name?>" required>
        </div>
    </div>
				  
    <div class="form-group">
        <label class="control-label col-md-3"><?=$LG['front.s_mobile'];//收件人手机号?></label>
        <div class="col-md-9 has-error">
            <input type="text" class="form-control input-medium" name="s_mobile"  value="<?=$s_mobile?>"  required>
            <span class="help-block"><?=$LG['front.38'];//只填写号码,不用加区号和-符号 如:13200000000?></span>
        </div>
    </div>
    
    <div class="form-group">
        <label class="control-label col-md-3"><?=$LG['awb'];//运单号?></label>
        <div class="col-md-9">
            <input type="text" class="form-control input-medium" name="ydh"  value="<?=$ydh?>">
            <span class="help-block"><?=$LG['front.39'];//填写时只更新该运单，留空时则更新所有未签收的运单?></span>
        </div>
    </div>
    
    <div class="form-group">
        <label class="control-label col-md-3"><?=$LG['front.op_1'];//选项?></label>
        <div class="col-md-9">
            <input type="checkbox" name="op_1" value="1" checked/> <?=$LG['front.47'];//只更新未有证件的运单?>
            <span class="help-block"><?=$LG['front.40'];//不勾选时则更新所有?></span>
        </div>
    </div>
    
    <div class="form-group">
            <label class="control-label col-md-3"><?=$LG['code'];//验 证 码?></label>
            <div class="col-md-9 has-error">
              <input name="code" id="code" type="text" class="form-control placeholder-no-fix input-small pull-left" style="margin-right:10px;" autocomplete="off"  maxlength="10" required onkeyup="checkcode('ot');"  title="<?=$LG['codePpt1'];//不分大小写?>"/>
              <span align="left"><span id="msg_code"></span> <img src="/images/code.gif" onclick="codeimg.src='/public/code/?v=ot&rm='+Math.random()" id="codeimg" title="<?=$LG['codePpt2'];//看不清，点击换一张(不分大小写)?>"  width="100" height="35"/></span> 
            </div>
          </div>

        <button type="submit" class="btn btn-info" style="width:100%; margin-top:15px;"><i class="icon-arrow-right"></i> <?=$LG['nextStep'];//下一步?> </button>
    </form>
    
   
   
   
   
   
   
   
   
   
   
   
   
    
<?php }if ($step==2&&$_SESSION['upload']['ydid']){
	$rs=FeData('yundan','userid,ydid,warehouse,channel,s_shenfenimg_z,s_shenfenimg_b',"ydid in ({$_SESSION['upload']['ydid']})");
	$upuserid=$rs['userid'];//上传到该会员ID
	?>
     <!--第二步:上传------------------------------------------------------------ -->
     <form action="?" method="post" class="form-horizontal form-bordered" name="xingao" style="margin:20px;">
      <input name="type" type="hidden" value="smt2">
      <input name="tokenkey" type="hidden" value="<?=$tokenkey?>">

		
            <div class="form-group red2">
                 <label class="control-label col-md-3"></label>
                <div class="col-md-9">
                  <?php if(!stristr($_SESSION['upload']['ydid'],',')&&$rs['s_shenfenimg_z']&&$rs['s_shenfenimg_b']){?>
                  <?=$LG['front.48'];//该运单已上传过证件，如果再次上传将替换原先的证件！?>
                  <br>
                  <?php }?>
                  <?php echo LGtag($LG['front.41'],'<tag1>=='.arrcount($_SESSION['upload']['ydid']));?>
                </div>
            </div>
        
        
		<?php $rs['s_shenfenimg_z']='';$rs['s_shenfenimg_b']='';//防止显示出来他人查看?>
			 <div class="form-group">
                <label class="control-label col-md-3"><?=$LG['address.shenfenhaoma'];//身份证号码?></label>
                <div class="col-md-9 has-error">
                  <input type="text" class="form-control input-medium" name="s_shenfenhaoma" value="<?=$s_shenfenhaoma?>" required>
                 </div>
              </div>
              
			 <div class="form-group">
                <label class="control-label col-md-3"><?=$LG['address.shenfenimg_z'];//身份证正面?></label>
                <div class="col-md-9">
<?php 
//文件上传配置
$uplx='img';//img,file
$uploadLimit='10';//允许上传文件个数(如果是单个，此设置无法，默认1)
$inputname='s_shenfenimg_z';//保存字段名，多个时加[]
$Pathname='card';//存放目录分类

$off_water=0;//水印(不手工设置则按后台设置)
$off_narrow=1;//是否裁剪
$img_w=$certi_w;$img_h=$certi_h;//裁剪尺寸：证件
//$img_w=$other_w;$img_h=$other_h;//裁剪尺寸：通用
//$img_w=500;$img_h=500;//裁剪尺寸：指定
include($_SERVER['DOCUMENT_ROOT'].'/public/uploadify/call.php');
?>
                 </div>
              </div>
			  
			 <div class="form-group">
                <label class="control-label col-md-3"><?=$LG['address.shenfenimg_b'];//身份证背面?></label>
                <div class="col-md-9">
				
				
<?php 
//文件上传配置
$uplx='img';//img,file
$uploadLimit='10';//允许上传文件个数(如果是单个，此设置无法，默认1)
$inputname='s_shenfenimg_b';//保存字段名，多个时加[]
$Pathname='card';//存放目录分类

$off_water=0;//水印(不手工设置则按后台设置)
$off_narrow=1;//是否裁剪
$img_w=$certi_w;$img_h=$certi_h;//裁剪尺寸：证件
//$img_w=$other_w;$img_h=$other_h;//裁剪尺寸：通用
//$img_w=500;$img_h=500;//裁剪尺寸：指定
include($_SERVER['DOCUMENT_ROOT'].'/public/uploadify/call.php');
?>
                 </div>
              </div>


        <button type="submit" class="btn btn-info" style="width:100%; margin-top:15px;"><i class="icon-ok"></i> <?=$LG['front.43'];//上传?> </button>
         <?php if($step==2){?>
        <button type="button" class="btn btn-default pull-left" onClick="location.href='?back=1';" style="width:100%; margin-top:15px; margin-right:30px;"><i class=" icon-arrow-left"></i> <?=$LG['lastStep'];//上一步?> </button>
        <?php }?>

   </form>
<?php }?>











<div class="gray2" style="margin:20px;">
    <strong><?=$LG['pptInfo'];//提示：?></strong> <br>
    &raquo; <?=$LG['front.44'];//根据中国海关总署修订后的《中华人民共和国海关对进出境快件监管办法》，入境到中国大陆的个人包裹经海关查验需向海关提供收件人身份证影印件等相关信息。?><br>
    &raquo; <?=$LG['front.45'];//我们承诺所有信息只提交给海关清关时进行查验，绝不用做其它途径!?><br>
</div>
<?php require_once($_SERVER['DOCUMENT_ROOT'].'/js/checkJS.php');//通用验证?>