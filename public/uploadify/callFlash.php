<?php
/*
	Flash版调用此文件 (已停用)
	
	$rsfile_my='no';//指定文件，no则空
	$upuserid=指定用户/会员ID
*/

if(!$rsfile_my)
{
	$filefield=str_ireplace('[]','', $inputname);
	$rsfile=cadd($rs[$filefield]);//读取原有文件
	
}else{
	if($rsfile_my=='no')
	{
		$rsfile='';//空
	}else{
		$rsfile=$rsfile_my;//读取指定文件
	}
}

$instanceID=str_replace("[]","",$inputname) ;//实例名
if (stristr($inputname,'[]'))
{
	$multi='true';//可以上传多个文件true,false
}else{
	$multi='false';//可以上传多个文件true,false
	$uploadLimit='1';//允许上传文件个数
}
if(!$upuserid){$upuserid=$_SESSION['manage']['userid'];}
if(!$upuserid){$upuserid=$_SESSION['member']['userid'];}
$timestamp =$upuserid.time();

if($uplx=='img')
{
	$buttonText=$LG['uploadify.buttonText_img'];//按钮显示名称:图片上传
	$fileSizeLimit=$image_size.'KB';//上传图片大小
	$fileTypeExts='*'.str_replace("|",";*",$image_ext);
	
}elseif($uplx=='file')
{
	$buttonText=$LG['uploadify.buttonText_file'];//按钮显示名称:文件上传
	$fileSizeLimit=$file_size.'KB';//上传附件大小
	$fileTypeExts='*'.str_replace("|",";*",$file_ext);
}
?>

<?php if(!$uploadify){?>
<!--只输出一次-->
<script src="/public/uploadify/jquery.uploadify.js" type="text/javascript"></script><!--V3.2-->
<link href="/public/uploadify/uploadify.css" rel="stylesheet" type="text/css"/>
<?php }?>


<div id="queue"></div><!--队列-->
<input id="<?=$instanceID?>" type="file" multiple>

<?php
//显示旧文件-------------------------------------------------------------------------------------------------
//读取多值字段
$zhi=$rsfile;
if($zhi)
{
	echo '<div id="'.$instanceID.'-queue" class="uploadify-queue">';
	
	$morefr=explode(',',$zhi);
	$mfcount=count($morefr);
	for($mfi=0;$mfi<$mfcount;$mfi++)
	{
		$morefrf=explode('::::::',$morefr[$mfi]);//必须
	?>
      <div id="id_<?=$instanceID?>_<?=$mfi+1?>" class="uploadify-queue-item">
        <p class="data">
        <?php if (stristr($inputname,'[]')){?>
        <!--多个-->
             <!--return confirm('确认要删除？需要提交保存否则已删除文件显示错误！ '); 提示不能操作onClick部分-->
             <a class="cancel" href="/public/uploadify/delfile.php?lx=del&file=<?=$morefrf[0]?>"  onClick="$('#<?=$instanceID?>').uploadify('cancel', 'id_<?=$instanceID?>_<?=$mfi+1?>');delupsl_<?=$instanceID?>();" target="delfile"><img src="/public/uploadify/uploadify-cancel.png" title="<?=$LG['del']//删除?>"></a>
             <input type="hidden" name="<?=$inputname?>" value="<?=$morefrf[0]?>" readonly>		
       <?php }else{?>
       <!--单个-->  
            <a class="cancel" href="/public/uploadify/delfile.php?lx=del&file=<?=$morefrf[0]?>"  onClick="$('#<?=$instanceID?>').uploadify('cancel', 'id_<?=$instanceID?>_<?=$mfi+1?>');delupsl_<?=$instanceID?>();" target="delfile"><img src="/public/uploadify/uploadify-cancel.png" title="<?=$LG['del']//删除?>"></a>
            <input type="hidden" name="old_<?=$inputname?>" value="<?=$morefrf[0]?>" readonly>		
	  <?php }?> 
         
         	<?php if($uplx=='img'){?>
				<a href="<?=$morefrf[0]?>" target="_blank"><img src="<?=$morefrf[0]?>" width="100"></a>
			<?php }elseif($uplx=='file'){?>
				<a href="<?=$morefrf[0]?>" target="_blank"><img src="/images/file.png" width="100"></a>
			<?php }?>
          
        </p>
        <p class="fileName" style="width:65px"><?=$LG['uploadify.oldFile']//原文件?><?=$mfi+1?></p>
      </div>
	<?php
	}
echo '</div>';
}
?>

<script type="text/javascript">

function delupsl_<?=$instanceID?>() //更新删除数量
{
	var sl=$('#<?=$instanceID?>').uploadify('settings','uploadLimit')+1;
	$('#<?=$instanceID?>').uploadify('settings','uploadLimit',sl)
}

$(function() {
	$('#<?=$instanceID?>').uploadify({
		'formData'     : {
			//传参数部分
			'upuserid' : '<?=$upuserid?>',
			'uplx' : '<?=$uplx?>',
			'off_water' : '<?=$off_water?>',
			'off_narrow' : '<?=$off_narrow?>',
			'img_w' : '<?=$img_w?>',
			'img_h' : '<?=$img_h?>',
			'timestamp' : '<?php echo $timestamp;?>',
			'token'     : '<?php echo md5('unique_salt' . $timestamp);?>'
		},
		//参数设置部分
		//'checkExisting':'style/uploadify/check-exists.php',//检查重复性
		'swf'      : '/public/uploadify/uploadify.swf',
		'uploader' : '/public/uploadify/uploadify.php',

		'auto':true,//自动上传true,false
		'multi':<?=$multi?>,//可以上传多个文件true,false
        'uploadLimit' :<?=$uploadLimit?>, //允许上传文件个数,0不限制
		'buttonText':'<?=$buttonText?>', //按钮显示名称
		'fileObjName':'the_files',//文件上传对象的名称，如果命名为’the_files’，PHP程序可以用$_FILES['the_files']来处理上传的文件对象。
        'fileSizeLimit': '<?=$fileSizeLimit?>',//上传附件大小,0不限制,5120B/512KB/2MB/1GB
       // 'fileTypeDesc': '支持的格式：<?=$fileTypeExts?>',
        'fileTypeExts': '<?=$fileTypeExts?>',//上传附件后缀限制,*.*不限制

		'progressData':'percentage',//设置上传进度显示方式，percentage显示上传百分比，speed显示上传速度
        'removeCompleted': false ,//上传完成移除
        //'removeTimeout': 0,//移除的延迟时间
        'overrideEvents': ['onUploadSuccess'],//可以被用户自定义事件覆盖
        'preventCaching':true,//后缀加随机数,防止缓存
        'requeueErrors':true,//上传出现错误后,重新加入队列
        'successTimeout':60,//等待服务器响应时间,超过30秒认为上传完成

        'itemTemplate':'<div id="${fileID}" class="uploadify-queue-item">\
                    <p class="data"></p>\
                    <p class="fileName">${fileName} <br>(${fileSize})</p>\
                </div>',

        //没有兼容的FLASH时触发
        'onFallback':function(){
            alert("<?=$LG['uploadify.ppt_1']//请先安装FLASH控件才能上传图片！?>");
        },

        'onUploadProgress' : function(file, bytesUploaded, bytesTotal, totalBytesUploaded, totalBytesTotal) {
             console.log(file);
             //有时候上传进度什么想自己个性化控制，可以利用这个方法
             //使用方法见官方说明
        },
       //  选择上传文件后调用
        'onSelect' : function(file) {},
        'onSelectError':function(file, errorCode, errorMsg){
            switch(errorCode) {
                case -100:
                    alert("<?=$LG['uploadify.ppt_2']//只能上传?> <?=$uploadLimit?> <?=$LG['uploadify.ppt_3']//个文件,如要上传请先删除其他文件！?>");
                    break;
                case -110:
                    alert("["+file.name+"] <?=$LG['uploadify.ppt_4']//超出了?><?=$fileSizeLimit?>");
                    break;
                case -120:
                    alert("["+file.name+"] <?=$LG['uploadify.ppt_5']//大小异常?>");
                    break;
                case -130:
                    alert("["+file.name+"] <?=$LG['uploadify.ppt_6']//类型不正确?>");
                    break;
            }
        },
        //上传文件成功后触发（每一个文件都触发一次）
		
        'onUploadSuccess': function (file, data, response) {
			<?php if($uplx=='img'){?>
				var img='<a class="cancel" href="/public/uploadify/delfile.php?lx=del&file='+data+'" onClick="$(\'#<?=$instanceID?>\').uploadify(\'cancel\', \''+file.id+'\');delupsl_<?=$instanceID?>();"  target="delfile">\
				<img src="/public/uploadify/uploadify-cancel.png" title="<?=$LG['del']//删除?>">\
				</a>\
				<a href="'+data+'" target="_blank"><img src="'+data+'" width="100"/></a>\
				<input type="hidden" name="<?=$inputname?>" value="'+data+'" readonly />';
			<?php }elseif($uplx=='file'){?>
				var img='<a class="cancel" href="/public/uploadify/delfile.php?lx=del&file='+data+'" onClick="$(\'#<?=$instanceID?>\').uploadify(\'cancel\', \''+file.id+'\');delupsl_<?=$instanceID?>();"  target="delfile">\
				<img src="/public/uploadify/uploadify-cancel.png" title="<?=$LG['del']//删除?>">\
				</a>\
				<a href="'+data+'" target="_blank"><img src="/images/file.png" width="100"/></a>\
				<input type="hidden" name="<?=$inputname?>" value="'+data+'" readonly />';
			<?php }?>
            $('#' + file.id).find('.data').html(img);
            console.log(file);
            console.log(data);
            console.log(response);
        },
    });
 });
</script>

<?php if(!$uploadify){$uploadify=1;?>
<!--只输出一次-->
<IFRAME  src="" name="delfile"  width="0" height="0" border=0  marginWidth=0 frameSpacing=0 marginHeight=0 frameBorder=0 noResize scrolling=no vspale="0"></IFRAME>
<?php }?>