<?php 
/*
	$upload_cert=0;//不管是否已有证件都更新(默认)
	$upload_cert=1;//有完整证件时不再更新
*/

//会员下运单时保存联系人到地址簿
$addclass=0; if($sf=='s'){$addclass=2;}elseif($sf=='f'){$addclass=1;}

if($_POST[$sf.'_name']&&$_POST[$sf.'_mobile'])
{
	//处理身份证文件（如果是新上传要复制一份并保存地址）--------------------
	$add_shenfenimg_z=add($_POST[$sf.'_shenfenimg_z_add']);
	$add_shenfenimg_b=add($_POST[$sf.'_shenfenimg_b_add']);
	if(stristr($_POST[$sf.'_shenfenimg_z'],'/upxingao/')){$add_shenfenimg_z=add(AutoCopyFile($_POST[$sf.'_shenfenimg_z']));}
	if(stristr($_POST[$sf.'_shenfenimg_b'],'/upxingao/')){$add_shenfenimg_b=add(AutoCopyFile($_POST[$sf.'_shenfenimg_b']));}
	
	$address=FeData('member_address','*',"truename='".add($_POST[$sf.'_name'])."' and mobile='".add($_POST[$sf.'_mobile'])."' and addclass in (0,{$addclass}) {$Mmy} order by addid desc");
	
	//已有完整证件则不更新
	$address_err=0;
	if($upload_cert&&$address['shenfenhaoma']&&strtoupper($address['shenfenhaoma'])!='LATE'&&$address['shenfenimg_z']&&$address['shenfenimg_b']){$address_err=1;}
	
	if(!$address_err)
	{
		//更新地址--------------------------------------------------------------------------------
		if($address['addid'])
		{
			if(!$content_address){$content_address=$LG['address.Xcall_out_save_1'];}
			if($address['content']&&!stristr($address['content'],$content_address))
			{
				$content_address=add($content_address).'；'.$address['content'];
			}
		
			$save="
			mobile_code='".add($_POST[$sf.'_mobile_code'])."',
			mobile='".add($_POST[$sf.'_mobile'])."',
			tel='".add($_POST[$sf.'_tel'])."',
			zip='".add($_POST[$sf.'_zip'])."',
			add_shengfen='".add($_POST[$sf.'_add_shengfen'])."',
			add_chengshi='".add($_POST[$sf.'_add_chengshi'])."',
			add_quzhen='".add($_POST[$sf.'_add_quzhen'])."',
			add_dizhi='".add($_POST[$sf.'_add_dizhi'])."',
			edittime='".time()."',
			content='{$content_address}'
			";
		
			if(trim($_POST[$sf.'_shenfenhaoma'])){$save.=",shenfenhaoma='".add($_POST[$sf.'_shenfenhaoma'])."'";}
			if($add_shenfenimg_z){$save.=",shenfenimg_z='{$add_shenfenimg_z}'";}
			if($add_shenfenimg_b){$save.=",shenfenimg_b='{$add_shenfenimg_b}'";}

			$xingao->query("update member_address set {$save} where addid='{$address['addid']}'");
			SQLError('更新地址簿'.$sf);
			if(mysqli_affected_rows($xingao)>0)
			{
				if($add_shenfenimg_z!=$address['shenfenimg_z']){DelFile($address['shenfenimg_z']);}
				if($add_shenfenimg_b!=$address['shenfenimg_b']){DelFile($address['shenfenimg_b']);}
			}
			$retAaddid=$address['addid'];
			
		//添加地址-------------------------------------------------------------------------------------------
		}else{
	
			//处理身份证文件（除了以上外，如果是用旧文件也要复制一份）
			if($add_shenfenimg_z==add($_POST[$sf.'_shenfenimg_z_add'])){$add_shenfenimg_z=add(AutoCopyFile($_POST[$sf.'_shenfenimg_z_add']));}
			if($add_shenfenimg_b==add($_POST[$sf.'_shenfenimg_b_add'])){$add_shenfenimg_b=add(AutoCopyFile($_POST[$sf.'_shenfenimg_b_add']));}
			
			if(!$content_address){$content_address=$LG['address.Xcall_out_save_2'];}
			
			$xingao->query("insert into member_address (checked,addclass,content,addtime,edittime,userid,username,
			truename,mobile_code,mobile,tel,zip,add_shengfen,add_chengshi,add_quzhen,add_dizhi,shenfenhaoma,shenfenimg_z,shenfenimg_b) 
			
			values('1','{$addclass}','".$content_address."','".time()."','".time()."','{$Muserid}','{$Musername}',
			'".add($_POST[$sf.'_name'])."','".add($_POST[$sf.'_mobile_code'])."','".add($_POST[$sf.'_mobile'])."','".add($_POST[$sf.'_tel'])."','".add($_POST[$sf.'_zip'])."','".add($_POST[$sf.'_add_shengfen'])."','".add($_POST[$sf.'_add_chengshi'])."','".add($_POST[$sf.'_add_quzhen'])."','".add($_POST[$sf.'_add_dizhi'])."','".add($_POST[$sf.'_shenfenhaoma'])."','{$add_shenfenimg_z}','{$add_shenfenimg_b}')
			
			");
			$retAaddid=mysqli_insert_id($xingao);
			SQLError('添加地址簿'.$sf);
		}
	}
}
?>