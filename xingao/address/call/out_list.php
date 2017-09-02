<?php
//获取,处理
$lx=par($_GET['lx']);
$addid=par($_GET['addid']);


//输出导入----------------------------------------------------------------------------------------------------
if($addid)
{
	$rs=FeData('member_address','*',"addid='{$addid}' {$Mmy}");

	echo '<script>';
	echo 'opener.document.xingao.'.$lx.'_name.value="'.cadd($rs['truename']).'";';
	//也可以用:echo 'opener.document.getElementsByName("'.$lx.'_name[]")[0].value="'.cadd($rs['truename']).'";';
	
	echo 'opener.document.xingao.'.$lx.'_mobile_code.value="'.cadd($rs['mobile_code']).'";';
	echo 'opener.document.xingao.'.$lx.'_mobile.value="'.cadd($rs['mobile']).'";';
	echo 'opener.document.xingao.'.$lx.'_tel.value="'.cadd($rs['tel']).'";';
	echo 'opener.document.xingao.'.$lx.'_zip.value="'.cadd($rs['zip']).'";';
	echo 'opener.document.xingao.'.$lx.'_add_shengfen.value="'.cadd($rs['add_shengfen']).'";';
	echo 'opener.document.xingao.'.$lx.'_add_chengshi.value="'.cadd($rs['add_chengshi']).'";';
	echo 'opener.document.xingao.'.$lx.'_add_quzhen.value="'.cadd($rs['add_quzhen']).'";';
	echo 'opener.document.xingao.'.$lx.'_add_dizhi.value="'.cadd($rs['add_dizhi']).'";';
	
	if($lx=='s'&&$off_shenfenzheng)
	{
		echo 'opener.document.xingao.'.$lx.'_shenfenhaoma.value="'.cadd($rs['shenfenhaoma']).'";';
		echo 'opener.document.xingao.'.$lx.'_shenfenimg_z_add.value="'.cadd($rs['shenfenimg_z']).'";';
		echo 'opener.document.xingao.'.$lx.'_shenfenimg_b_add.value="'.cadd($rs['shenfenimg_b']).'";';
		
		echo 'opener.document.getElementById("'.$lx.'_shenfenimg_z_msg").innerHTML="<a href=\"'.cadd($rs['shenfenimg_z']).'\" target=\"_blank\"><img src=\"'.cadd($rs['shenfenimg_z']).'\" width=\"200\" height=\"150\"\/><\/a>";';
		echo 'opener.document.getElementById("'.$lx.'_shenfenimg_b_msg").innerHTML="<a href=\"'.cadd($rs['shenfenimg_b']).'\" target=\"_blank\"><img src=\"'.cadd($rs['shenfenimg_b']).'\" width=\"200\" height=\"150\"\/><\/a>";';
	}
	
	
	echo 'window.close();';
	echo '</script>';
	exit;
}

//输出列表----------------------------------------------------------------------------------------------------
//搜索
$where="checked='1'";
$so=(int)$_GET['so'];
if($so==1)
{
	$key=par($_GET['key']);
	$addclass=par($_GET['addclass']);
	if($key){$where.=" and (truename like '%{$key}%' or mobile like '%{$key}%' or add_dizhi like '%{$key}%')";}
	if(CheckEmpty($addclass)){$where.=" and addclass='{$addclass}'";}

	$search.="&so={$so}&key={$key}&addclass={$addclass}&checked={$checked}";
}

if($lx=='f'){$where.=" and addclass in (0,1)";}
elseif($lx=='s'){$where.=" and addclass in (0,2)";}
else{exit ("<script>alert('lx{$LG['pptError']}');goBack('c');</script>");}

$search.="&lx={$lx}&userid={$userid}&username={$username}";

$order=' order by addid desc';//默认排序
include($_SERVER['DOCUMENT_ROOT'].'/public/orderby.php');//排序处理页

$query="select * from member_address where {$where}  {$Mmy} {$order}";

//分页处理
//$line=20;$page_line=15;//$line=-1则不分页(不设置则默认)
include($_SERVER['DOCUMENT_ROOT'].'/public/page.php');
?>

<div class="page_ny">

	<div class="tabbable tabbable-custom boxless">
		<div class="tab-content" style="padding:10px;">
            <button type="button" class="btn btn-danger" onClick="goBack('c');" style="float:right; margin-bottom:10px;"><i class="icon-remove"></i> <?=$LG['close']?> </button>
    
             
            <?php if($Muserid){?>
            <button type="button" class="btn btn-info tooltips" data-container="body" data-placement="bottom" data-original-title="<?=$LG['address.Xcall_out_list_1'];//编辑完新地址后，返回刷新本页?>"  onClick="window.open('/xamember/address/list.php')" style="float:right; margin-bottom:10px; margin-left:10px;"><i class="icon-file-text"></i> <?=$LG['address.Xcall_out_list_2'];//地址管理?> </button>
            <?php }?>
   
            <form class="navbar-form navbar-left"  method="get" action="?">
            <input name="so" type="hidden" value="1">
            <input name="lx" type="hidden" value="<?=$lx?>">
            <input name="userid" autocomplete="off"  type="hidden" value="<?=$userid?>">
            <input name="username" autocomplete="off" type="hidden" value="<?=$username?>">
            <div class="form-group">
              <input type="text" name="key" class="form-control input-msmall popovers" data-trigger="hover" data-placement="right"  data-content="<?=$LG['address.pptList1'];//姓名/电话/具体地址 (可留空)?>" value="<?=$key?>">
            </div>
            <div class="form-group">
              <div class="col-md-0">
                <select  class="form-control input-small select2me" name="addclass" data-placeholder="<?=$LG['address.addclass'];//分类?>">
                  <option></option>
                  <?=AddClass($addclass,1)?>
                </select>
              </div>
            </div>
            
            <button type="submit" class="btn btn-info"><i class="icon-search"></i> <?=$LG['search']?></button>
          </form>
          
		<table class="table table-striped table-bordered table-hover" >
				<thead>
					<tr>
						<th align="left"><a href="?<?=$search?>&orderby=truename&orderlx=" class="<?=orac('truename')?>"><?=$LG['address.truename'];//姓名?></a>/<a href="?<?=$search?>&orderby=addclass&orderlx=" class="<?=orac('addclass')?>"><?=$LG['address.addclass'];//分类?></a></th>
						<th align="center"><a href="?<?=$search?>&orderby=mobile&orderlx=" class="<?=orac('mobile')?>"><?=$LG['address.mobile'];//手机?></a>/<a href="?<?=$search?>&orderby=tel&orderlx=" class="<?=orac('tel')?>"><?=$LG['address.tel'];//固话?></a></th>
						<th align="center"><a href="?<?=$search?>&orderby=zip&orderlx=" class="<?=orac('zip')?>"><?=$LG['address.zip'];//邮编?></a>/<a href="?<?=$search?>&orderby=add_dizhi&orderlx=" class="<?=orac('add_dizhi')?>"><?=$LG['address.Xcall_out_list_6'];//地址?></a></th>
						<th align="center"><a href="?<?=$search?>&orderby=shenfenhaoma&orderlx=" class="<?=orac('shenfenhaoma')?>"><?=$LG['address.Xcall_out_list_3'];//证件?></a></th>
						<th align="center"><?=$LG['op'];//操作?></th>
					</tr>
				</thead>
				<tbody>
<?php
while($rs=$sql->fetch_array())
{
	
		$shenfenimg_z="";
		if($rs['shenfenimg_z'])
		{
			$shenfenimg_z="<a href='".$rs['shenfenimg_z']."' title='{$LG['address.pptList2']}' target=_blank><i class='icon-picture'></i></a>";
		}
		$shenfenimg_b="";
		if($rs['shenfenimg_b'])
		{
			$shenfenimg_b="<a href='".$rs['shenfenimg_b']."' title='{$LG['address.pptList3']}' target=_blank><i class='icon-picture'></i></a>";
		}
?>
					<tr class="odd gradeX">
						<td align="center" valign="middle"><?=cadd($rs['truename'])?><br>
							<font class="gray2"> <?=AddClass($rs['addclass'])?> </font></td>
						<td align="center" valign="middle"><?=cadd($rs['mobile'])?><br>
							<font class="gray2"> <?=$rs['tel']?> </font></td>
						<td align="center" valign="middle"><?=cadd($rs['zip'])?><br>
							<font class="gray2"> <?=$rs['add_shengfen'].' '.$rs['add_chengshi'].' '.$rs['add_quzhen'].' '.$rs['add_dizhi']?> </font></td>
						<td align="center" valign="middle"><?=cadd($rs['shenfenhaoma'])?> <br>
							<?=$shenfenimg_z?> <?=$shenfenimg_b?></td>
						<td align="center" valign="middle"><a href="?lx=<?=$lx?>&addid=<?=$rs['addid']?>&userid=<?=$userid?>&username=<?=$username?>" class="btn btn-xs btn-primary"><i class="icon-edit"></i> <?=$LG['address.Xcall_out_list_4'];//选择?></a></td>
					</tr>
<?php
}
?>
				</tbody>
			</table>
<?php 
if(!mysqli_num_rows($sql)){echo $LG['address.Xcall_out_list_5'];}
?>			
			
			<div class="row"> <?=$listpage?> </div>
		</div>
	</div>
</div>
