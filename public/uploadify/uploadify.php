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
ob_start();
require_once($_SERVER['DOCUMENT_ROOT'].'/public/config_top.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/public/function.php');

//uploadify.php不能获取_SESSION
//uploadify,swfupload采用的都是flash客户端，这样它们产生的useragent与用户使用浏览器的user-agent必然不同。



//##########################配置开始#########################
//获取
/*
	$callFrom_up='' 原uploadify flash 类型
	$callFrom_up='editor' //编辑器 (编辑器批量上传时用的也是flash)
*/
if(!$callFrom_up){
	
	$uplx=trim($_POST['uplx']);
	$userid=trim($_POST['upuserid']);
	$off_water=trim($_POST['off_water']);
	$off_narrow=trim($_POST['off_narrow']);
	$img_w=trim($_POST['img_w']);
	$img_h=trim($_POST['img_h']);
	$formFileFieldName="the_files";//表单中文件标签的name属性值

	$verifyToken = md5('unique_salt' . $_POST['timestamp']);
	if ($_POST['token'] != $verifyToken) {exit('token'.$LG['pptError']);}
	if (empty($_FILES)){exit($LG['uploadify.ppt_7']);	}

}elseif($callFrom_up=='editor'){
	
	$uplx=trim($_GET['dir']);
	if($uplx=='image'||$uplx=='moreimage'){$uplx='img';}

	if(!$upuserid){$upuserid=$_SESSION['manage']['userid'];}
	if(!$upuserid){$upuserid=$_SESSION['member']['userid'];}
	$userid=$upuserid;
	
	$formFileFieldName="imgFile";//表单中文件标签的name属性值
	$img_w=$other_w;//通用：裁剪，缩小宽度
	$img_h=$other_h;//通用：裁剪，缩小高度
	
	if (empty($_FILES)){exit($LG['uploadify.ppt_7']);}
}



//安全验证
if(!$userid && trim($_GET['dir'])!='moreimage')//编辑器批量上传时用的也是flash，不能获取_SESSION
{
	exit($LG['uploadify.ppt_8']);
}



//上传目录目录维护-----------------------------------------------------------
//导入文件目录 特殊目录处理
DelDirAndFile('/upxingao/import/',date('Y-m-d',time()));//删除所有非当天的导入文件再上传

//上传目录处理-----------------------------------------------------------
$isRnd=true;//true是使用随机文件名保存文件，false使用原始文件名或者用户自己指定的文件名保存
if($_POST['Pathname']){	$Pathname=par($_POST['Pathname'],0,1).'/';}else{$Pathname='xa/';}
$Pathname="/upxingao/{$Pathname}".date('Y-m-d',time()).'/';//上传文件保存路径,最后要有 /
DoMkdir($Pathname);//创建目录

$upfilePath =$_SERVER['DOCUMENT_ROOT'].$Pathname;




switch($uplx){
 case 'img'://图片上传
	 if(!is_array($image_ext)&&$image_ext!=''){$image_ext=explode('|',$image_ext);}
	 $Exts=$image_ext;
     $file_size = $image_size; 
     break;
 case 'file'://文件上传
	 if(!is_array($file_ext)&&$file_ext!=''){$file_ext=explode('|',$file_ext);}
	 $Exts=$file_ext;
     $file_size = $file_size; 
     break;  
 case 'flash'://flash媒体上传
	 if(!is_array($flash_ext)&&$flash_ext!=''){$flash_ext=explode('|',$flash_ext);}
	 $Exts=$flash_ext;
     $flash_size = $flash_size; 
     break;  
 case 'media'://媒体上传
	 if(!is_array($media_ext)&&$media_ext!=''){$media_ext=explode('|',$media_ext);}
	 $Exts=$media_ext;
     $media_size = $media_size; 
     break;  
 default:
 	exit('uplx'.$LG['pptError']);
}

//################################配置结束#############################



//上传处理部分--开始------------------
$successEcho=json_encode(array("code"=>200,"name"=>"[name]","save_name"=>"[save_name]","path"=>"[path]","msg"=>"上传文件[name]成功！"));
@$file=$_FILES[$formFileFieldName];
$fileobj = new upload($file);
$fileobj->isRnd=$isRnd;//设置是否使用随机文件名
$fileobj->file_size=$file_size;//设置文件大小
$fileobj->upfilePath = $upfilePath;//设置上传路径
$fileobj->successEcho=$successEcho;//设置上传成功返回的json数据
$fileobj->setExt($Exts);//设置允许的文件后缀
$fileobj->saveFile();//处理文件

$name=$fileobj->filename;
$saveName=$fileobj->saveName;
$path=$fileobj->upfilePath;
$buffer=ob_get_contents();ob_clean();
$buffer=str_replace("[name]",$name,$buffer);
$buffer=str_replace("[save_name]",$saveName,$buffer);
$buffer=str_replace("[path]",$path,$buffer);
//上传处理部分--结束------------------







//上传完处理图片（裁剪，加水印等）--开始------------------
$fileadd=$Pathname.$saveName;
if((int)$off_narrow && ( (int)$img_w || (int)$img_h ))//裁剪缩小
{
	$img = new image();
	$bigimg =$fileadd;// 原始图片
	$saveimg =$fileadd;// 处理后的图片
	$stat = $img->param($_SERVER['DOCUMENT_ROOT'].$bigimg)->thumb($_SERVER['DOCUMENT_ROOT'].$saveimg,(int)$img_w,(int)$img_h,0);// 0等比缩放；1 居中裁剪；2 顶左裁剪
	$fileadd=$saveimg;
}

if((int)$off_water && $water_file)//加水印
{
	@$img = new image();
	$bigimg =$fileadd;// 原始图片
	$saveimg =$fileadd;// 处理后的图片
	$img->param($_SERVER['DOCUMENT_ROOT'].$bigimg)->water($_SERVER['DOCUMENT_ROOT'].$saveimg,$_SERVER['DOCUMENT_ROOT'].$water_file,$water_location,$water_tran);// 9右下角添加水印,80透明度
	$fileadd=$saveimg;
}

//删除木马图片:如果有PHP代码,imgcompress执行会有错误,因此先检查并删除
DelTrojan($fileadd);

//以上加水印时,也会压缩处理,但图片太小时,就不会加水印,因此最后还要再压缩一次
//保留原清晰质量的图片压缩类：专门用于压缩原图,重新压缩会自行清除木马图片
if( $uplx=='img' && !$_SESSION['manage']['userid'] && file_exists(AddPath($fileadd)) )
{
	(new imgcompress(AddPath($fileadd)))->compressImg(AddPath($fileadd));
}

//上传完处理图片（裁剪，加水印等）--结束------------------

if( file_exists(AddPath($fileadd)) ){echo $fileadd;}else{echo '';}










class upload
{  
    public $file; //通过$_FILES["upload"]获得的文件对象
    public $file_size; //上传最大值，单位KB，默认是：1024KB（1M）最大不能超过php.ini中的限制（默认是2M）
    public $upfilePath; //上传文件路径，如果文件夹不存在自动创建
    private $ext; //文件类型限制
    public $saveName,$filename,$isRnd=true;
    public function __construct($files)
    {   @session_start();
        $this->file = $files;
    }

    function saveFile($saveName = null)
    {	
        $upPath = $this->upfilePath;
        $file = $this->file;
        $this->filename=$file["name"];
        if (!$this->isRnd) {
        	if(!$saveName){$saveName=$this->filename;}
        	$this->saveName=$saveName;
            $path = $upPath.$saveName;
        } elseif($this->isRnd){
            $saveName=$this->getRandomName();
            $path = $upPath.$saveName;
            $this->saveName=$saveName;
        }

        if (!file_exists($upPath)) {
            mkdir($upPath);
        }


        if ($this->check_file_type($file)) {
			echo $LG['uploadify.ppt_9'].str_replace('"',"",json_encode($this->ext)).'';
            //echo '{"code":403,"msg":"该文件类型不允许上传！只能上传：'.str_replace('"',"",json_encode($this->ext)).'"}';
            return;
        }
        if ($this->check_file_size($file)) {
			echo $LG['uploadify.ppt_10'].$this->file_size.'KB';
        	//echo '{"code":403,"msg":"文件大小超出限制！[最大'.$this->file_size.'KB]"}';
            return;
        }
        @move_uploaded_file($file["tmp_name"], iconv("UTF-8", "gbk", $path));
        if($file["error"]===0){
        echo $this->successEcho;
        }else{
			echo $LG['uploadify.ppt_11'].$file["error"].'';
      		//echo '{"code":500,"msg":"上传失败！错误代码:'.$file["error"].'"}';
        }
    }

    function setExt(array $ext)
    {
        $this->ext = $ext;
    }
    function getExt(array $ext)
    {
        return $this->ext;
    }
    function getRandomName()
    {
        @$pos = strrpos($this->file["name"], ".");
        @$fileExt = strtolower(substr($this->file["name"], $pos));
        ini_set('date.timezone', 'Asia/Shanghai');
       	global $userid; 
		
		$filename=$userid.'_'.md5(rand(10000, 999999));//加密文件名，防止非法下载身份证图片
        return $filename.$fileExt;
    }

    function check_file_type()
    {
        @$file = $this->file;
        @$ext = $this->ext;
        @$pos = strrpos($file["name"], ".");
        @$fileExt = strtolower(substr($file["name"], $pos));
		
        //新加:禁止上传的扩展名
		if (CheckSaveTranFiletype($fileExt)){return true;}
		
		//原
        if (count($ext) == 0)
            return false;
        for ($i = 0; $i < count($ext); $i++) {
            if (strcmp($ext[$i], $fileExt) == 0) {
                return false;
            }
            ;
        }
        return true;
    }
    function check_file_size()
    {
        $file = $this->file;
        if (@$file["size"] / 1024 <= $this->file_size)
            return false;
        return true;
    }
}//end class
?>