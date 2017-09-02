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
//运费自定义计算公式

if(!$off_mall){	exit ("<script>alert('{$LG['front.136']}');goBack('uc');</script>");}

//处理:1125

//获取,处理
$lx=par($_GET['lx']);
$mlid=(int)$_GET['mlid'];

if(!$mlid)
{
	exit ("<script>alert('id{$LG['pptError']}');goBack('uc');</script>");
}

$rs=FeData('mall','*',"mlid='{$mlid}'");

$classid=$rs['classid'];//每页必须有$classid
$cr=ClassData($classid);

$headtitle=$rs['seotitle'.$LT]?cadd($rs['seotitle'.$LT]):cadd($rs['title'.$LT]);
$headtitle.='-'.cadd($cr['name'.$LT]);

require_once($_SERVER['DOCUMENT_ROOT'].'/template/incluce/header.php');//放查询的后面

if(!$rs['checked'])
{
	XAts('mall_checked');
	exit();
}

if($rs['url'.$LT])
{
	echo '<script language=javascript>';
	echo 'location.href="'.$rs['url'.$LT].'";';
	echo '</script>';
	XAtsto($rs['url'.$LT]);
	exit();
}

require_once($_SERVER['DOCUMENT_ROOT'].'/template/incluce/service.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/template/incluce/nav.php');

$rs['unit']=classify($rs['unit'],2);
?>
<!--商城样式开始-->
<!--商城内容页原来是用jquery-1.8.3.js-->
<link href="/css/mall.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="/js/cloud-zoom.1.0.2.js"></script>
<!--商城样式结束-->

<!--内容开始-->
<style>
.class_banner{background:url(<?=ClassImg($classid)?>) no-repeat center top;}
</style>

<div class="class_banner"></div>
<div class="mbt10" id="goodss">
  <div class="add_fr">
    <?=Addnav($classid)?>
  </div>
  <div class="right_tit"><i class="icon-th-large"></i>
    <?=cadd($cr['name'.$LT])?>
    
  </div>
  <br>
  <!--上部商品-开始-->
  <div class="goodss_js">
  <!--左边图片-->
    <div class="goodss_js_left">
      <div class="wraps"> <a onMouseOut="scrollVert.stop();" onMouseDown="scrollVert.play(this);" href="javascript:;" id="rollUp" class="rollMenu"><img src="/images/galleryup.png" alt="up"></a> <a onMouseOut="scrollVert.stop();" onMouseDown="scrollVert.play(this);" href="javascript:;" id="rollDown" class="rollMenu"><img src="/images/gallerydown.png" alt="down"></a>
        <div id="rollBox">
          <ul id="rollList">
            <div id="thumbpic">
              <div class="clearfix">
                <div class="gallery">
                  <div id="demo" class="clearfix">
                    <ul class="clearfix">
                      <li style="float:left;"> <a onClick="CngClass(this);" href="<?=ImgAdd($rs['titleimg'.$LT])?>" class="cloud-zoom-gallery" rel="useZoom: 'zoom1', smallImage: '<?=ImgAdd($rs['titleimg'.$LT])?>'"><img class="zoom-tiny-image" src="<?=ImgAdd($rs['titleimg'.$LT])?>"/></a> </li>
                     
						<?php
                        $arr=$rs['img'.$LT];
						if($arr)
						{
							if(!is_array($arr)){$arr=explode(",",$arr);}//转数组
							foreach($arr as $arrkey=>$value)
							{
							?>
							  <li style="float:left;"> <a onClick="CngClass(this);" href="<?=$value?>" class="cloud-zoom-gallery" rel="useZoom: 'zoom1', smallImage: '<?=$value?>'"><img class="zoom-tiny-image" src="<?=$value?>"/></a> </li>
							<?php
							}
						}
                        ?>
                    </ul>
                  </div>
                </div>
              </div>
            </div>
          </ul>
        </div>
      </div>
    </div>
<script>
var Lst;
function CngClass(obj)
{
 if (typeof(obj)=='string') obj=document.getElementById(obj);
 if (Lst) Lst.className='';
 obj.className='selected';
 Lst=obj;
}

var $id=function(id){return document.getElementById(id);}
var scrollVert={
locked: 0,
vector: 0,
play:function(thiso){
	if (scrollVert.locked == 0) {
		if (thiso.id == 'rollUp') {
			scrollVert.vector = -60;
		}
		if (thiso.id == 'rollDown') {
			scrollVert.vector = 60;
		}
		roll = setInterval(function(){
			$id('rollBox').scrollTop+=scrollVert.vector;
		}, 30);
		scrollVert.locked = 1;
	}
},
stop: function(){
	if (window.roll) {
		clearInterval(window.roll);
		scrollVert.locked = 0;
	}
}
}
</script> 
    
    
    <div class="goodss_js_middle">
      <div class="goodspic"> <a href="<?=ImgAdd($rs['titleimg'.$LT])?>" class = "cloud-zoom" id="zoom1" rel="adjustX: 10, adjustY:-4,smoothMove:10"> <img src="<?=ImgAdd($rs['titleimg'.$LT])?>" onload="AutoResizeImage(410,420,this)"> </a> </div>
      
      <br>

      <?=$LG['front.148']//所有商品均为海外本土商品，非国内进口版，需经海关通关!?>
      <div class="pingjia" style="margin-top:10px;"><img style="vertical-align:middle;"; src="/images/stars5.gif" alt="comment rank 5" />
      <a href="#pl"  onClick="changeTabs(3)"> (<?=$LG['comments']?><script src="/public/onclick.php?table=mall&field=plclick&id=<?=$rs['mlid']?>"></script>)</a>  
      &nbsp; <?=$LG['browse']?><script src="/public/onclick.php?table=mall&id=<?=cadd($rs['mlid'])?>"></script>
      </div>
              
             
              
      <div class="baidu"> <span>
              <div id="bdshare" class="bdshare_t bds_tools get-codes-bdshare"> <span class="bds_more" style="height:25px;"></span> <a class="bds_qzone" style="height:25px;"></a> <a class="bds_tsina" style="height:25px;"></a> <a class="bds_tqq" style="height:25px;"></a> <a class="bds_renren" style="height:25px;"></a> <a class="bds_t163" style="height:25px;"></a> </div>
              <script type="text/javascript" id="bdshare_js" data="type=tools&amp;uid=0" ></script> 
              <script type="text/javascript" id="bdshell_js"></script> 
              <script type="text/javascript">
document.getElementById("bdshell_js").src = "http://bdimg.share.baidu.com/static/js/shell_v2.js?cdnversion=" + Math.ceil(new Date()/3600000)
</script> 
              </span> </div>
              
              
      </div>
      
	<!--订购表单-开始-->
	<div class="goodss_js_right">
		<div id="textinfo">
		<?php require_once($_SERVER['DOCUMENT_ROOT'].'/mall/call/show_cartform.php');?>
		<div class="clear"></div>
		</div>
	</div>
	<!--订购表单-结束--> 
	
    <div style="clear:both;"></div>
  </div>
  <!--上部商品-结束--> 
</div>
<div class="center">
  <div class="article_left fl">
  
  <?php require_once($_SERVER['DOCUMENT_ROOT'].'/mall/call/left.php');?>
    
  </div>
  <div class="article_right fr">
    <div class="article_content" >
      <div class="clear"></div>
      
        <!--下部商品-开始-->
        <!--图片自动缩小--> 
        <script type="text/javascript" src="/js/jQuery.autoIMG.min.js"></script> 
        <?php echo '<script type="text/javascript">$(function(){	$("#autoimg").autoIMG();});</script>';//用PHP输出不然DW里提示JS错误?>
        
        <div class="article_ny" id="autoimg">
              <?php require_once($_SERVER['DOCUMENT_ROOT'].'/mall/call/show_content.php');?>
        </div>     
        <!--下部商品-结束--> 
      
    </div>
  </div>
  <div class="clear"></div>
</div>
<div class="clear"></div>


<!--内容结束-->
<?php require_once($_SERVER['DOCUMENT_ROOT'].'/template/incluce/footer.php');?>
