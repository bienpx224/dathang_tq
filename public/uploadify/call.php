<?php
/*
调用：
<?php 
//文件上传配置
$uplx='img';//img,file
$uploadLimit='10';//允许上传文件个数(如果是单个，此设置无法，默认1)
$inputname='s_shenfenimg_z';//保存字段名，多个时加[]
$Pathname='card';//指定存放目录分类,支持:$Pathname='card/manage'

$off_water=0;//水印(不手工设置则按后台设置)
$off_narrow=1;//是否裁剪
$img_w=$certi_w;$img_h=$certi_h;//裁剪尺寸：证件
//$img_w=$other_w;$img_h=$other_h;//裁剪尺寸：通用
//$img_w=500;$img_h=500;//裁剪尺寸：指定
include($_SERVER['DOCUMENT_ROOT'].'/public/uploadify/call.php');
?>


*/


/*
旧版升级方法:
	用以下语言包
	把本页的$LG全改为$LGuploadify
	把uploadify目录全部上传替换
*/

/*
$LGuploadify=array(
//文件上传--------------------------------------------------------------
	'uploadify.buttonText_img'=>'图片上传',
	'uploadify.buttonText_file'=>'文件上传',
	'uploadify.oldFile'=>'原文件',
	'uploadify.ppt_0'=>'您的浏览器不支持HTML5！',
	'uploadify.ppt_1'=>'请先安装FLASH控件才能上传图片！',
	'uploadify.ppt_2'=>'只能上传',	
	'uploadify.ppt_3'=>'个文件,如要上传请先删除其他文件！',
	'uploadify.ppt_4'=>'超出了',
	'uploadify.ppt_5'=>'大小异常',
	'uploadify.ppt_6'=>'类型不正确',
	'uploadify.ppt_7'=>'没选择上传文件',
	'uploadify.ppt_8'=>'禁止上传文件：未登录也未指定会员ID',
	'uploadify.ppt_9'=>'只能上传：',
	'uploadify.ppt_10'=>'文件太大，不能超过：',
	'uploadify.ppt_11'=>'上传失败，错误代码：',

);
*/




/*
	HTML5版调用此文件
	
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
}elseif($uplx=='file'){
	$buttonText=$LG['uploadify.buttonText_file'];//按钮显示名称:文件上传
	$fileSizeLimit=$file_size.'KB';//上传附件大小
	$fileTypeExts='*'.str_replace("|",";*",$file_ext);
}
?>

<?php if(!$uploadify){?>
<!--只输出一次-->
<script src="/public/uploadify/jquery.uploadifive.js" type="text/javascript"></script><!--V3.2-->
<link href="/public/uploadify/uploadifive.css" rel="stylesheet" type="text/css"/>
<?php }?>


<input id="<?=$instanceID?>" type="file" multiple>
<div id="queue_<?=$instanceID?>"></div><!--队列-->

<?php
//显示旧文件-------------------------------------------------------------------------------------------------
//读取多值字段
$zhi=$rsfile;
if($zhi)
{
	echo '<div id="'.$instanceID.'-queue" class="uploadifive-queue">';
	
	$morefr=explode(',',$zhi);
	$mfcount=count($morefr);
	for($mfi=0;$mfi<$mfcount;$mfi++)
	{
		$morefrf=explode('::::::',$morefr[$mfi]);//必须
		$fileid ='PHP_'.$instanceID.'-file-'.($mfi+1);
		$uploadLimit--;
	?>
      <div id="<?=$fileid?>" class="uploadifive-queue-item">
        <p class="data">
        <?php if (stristr($inputname,'[]')){?>
        <!--多个-->
             <!--return confirm('确认要删除？需要提交保存否则已删除文件显示错误！ '); 提示不能操作onClick部分-->
             <a class="cancel" href="/public/uploadify/delfile.php?lx=del&file=<?=$morefrf[0]?>"  onClick="delupsl_<?=$instanceID?>('<?=$fileid?>');" target="delfile"><img src="/public/uploadify/uploadify-cancel.png" title="<?=$LG['del']//删除?>"></a>
             <input type="hidden" name="<?=$inputname?>" value="<?=$morefrf[0]?>" readonly>		
       <?php }else{?>
       <!--单个-->  
            <a class="cancel" href="/public/uploadify/delfile.php?lx=del&file=<?=$morefrf[0]?>"  onClick="delupsl_<?=$instanceID?>('<?=$fileid?>');" target="delfile"><img src="/public/uploadify/uploadify-cancel.png" title="<?=$LG['del']//删除?>"></a>
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
/*
	参数说明：
	http://www.cnblogs.com/skyfynn/p/5895583.html
	http://www.cnblogs.com/skyfynn/p/5895738.html
	搜索jquery.uploadifive_HTML5.js：//全部参数
*/

$(function() {
	$('#<?=$instanceID?>').uploadifive({
		'formData'     : {
			//传参数部分
			'upuserid' : '<?=$upuserid?>',
			'uplx' : '<?=$uplx?>',
			'Pathname' : '<?=$Pathname?>',
			'off_water' : '<?=$off_water?>',
			'off_narrow' : '<?=$off_narrow?>',
			'img_w' : '<?=$img_w?>',
			'img_h' : '<?=$img_h?>',
			'timestamp' : '<?php echo $timestamp;?>',
			'token'     : '<?php echo md5('unique_salt' . $timestamp);?>'
		},
		//参数设置部分
		'queueID'          : 'queue_<?=$instanceID?>',
		'uploadScript' : '/public/uploadify/uploadify.php',

		'auto':true,//自动上传true,false
		'multi':<?=$multi?>,//可以上传多个文件true,false
        'uploadLimit' :<?=$uploadLimit<0?0:$uploadLimit?>, //允许上传文件个数,-1不限制
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
		
		'onCancel' : function(file) {
		   $("#frontSide").val("");
			/* 取消上传后重新设置uploadLimit */
			$data	= $('#<?=$instanceID?>').data('uploadifive'),
			settings = $data.settings;
			settings.uploadLimit++;
		},
		
        'itemTemplate':'<div class="uploadifive-queue-item">\
                    <p class="data"></p>\
                </div>',

        //没有兼容HTML5时触发
        'onFallback':function(){
            alert("<?=$LG['uploadify.ppt_0']//您的浏览器不支持HTML5！?>");
        },

        'onUploadProgress' : function(file, bytesUploaded, bytesTotal, totalBytesUploaded, totalBytesTotal) {
             console.log(file);
             //有时候上传进度什么想自己个性化控制，可以利用这个方法
             //使用方法见官方说明
        },
        'onError':function(file, errorCode, errorMsg){
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
        'onUploadComplete': function (file, data) {
			
			$data= $('#<?=$instanceID?>').data('uploadifive'),
			fileid ='uploadifive-<?=$instanceID?>-file-'+$data.fileID;
			//fileid生成位置搜索：file.queueItem.attr('id', settings.id + '-file-' +
			
			//alert(fileid);//file.name file.lastModified
	
			<?php if($uplx=='img'){?>
				var img='<a class="cancel" href="/public/uploadify/delfile.php?lx=del&file='+data+'" onclick="delupsl_<?=$instanceID?>(\''+fileid+'\');" target="delfile"><img src="/public/uploadify/uploadify-cancel.png" title="<?=$LG['del']//删除?>"></a>\
				<a href="'+data+'" target="_blank"><img src="'+data+'" width="100"/></a>\
				<input type="hidden" name="<?=$inputname?>" value="'+data+'" readonly />';
			<?php }elseif($uplx=='file'){?>
				var img='<a class="cancel" href="/public/uploadify/delfile.php?lx=del&file='+data+'" onClick="delupsl_<?=$instanceID?>(\''+fileid+'\');"  target="delfile">\
				<img src="/public/uploadify/uploadify-cancel.png" title="<?=$LG['del']//删除?>">\
				</a>\
				<a href="'+data+'" target="_blank"><img src="/images/file.png" width="100"/></a>\
				<input type="hidden" name="<?=$inputname?>" value="'+data+'" readonly />';
			<?php }?>
			
			//alert(fileid);//file.name file.lastModified
            $('#'+fileid).find('.data').html(img);
			$data.fileID++;
           // console.log(file);//输出file数组信息，查看方法：http://www.mamicode.com/info-detail-611316.html
           // console.log(data);
        },
    });
 });
 
 
 
 
 
 
function delupsl_<?=$instanceID?>(val) //更新删除数量
{
	$('#'+val).remove();//删除

	//更新数量
	$data= $('#<?=$instanceID?>').data('uploadifive'),
	settings = $data.settings;
	settings.uploadLimit++;
}


</script>






<?php if(!$uploadify){$uploadify=1;?>
<!--只输出一次-->
<IFRAME  src="" name="delfile"  width="0" height="0" border=0  marginWidth=0 frameSpacing=0 marginHeight=0 frameBorder=0 noResize scrolling=no vspale="0"></IFRAME>
<?php }?>