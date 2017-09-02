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
$index=1;
$headtitle=$LG['name.nav_0'];
require_once($_SERVER['DOCUMENT_ROOT'].'/template/incluce/header.php');//放查询的后面

//验证,查询

$lx=par($_GET['lx']);
if(!$_SESSION['manage']['userid']&&$lx!='html'){XAts('temp');}
require_once($_SERVER['DOCUMENT_ROOT'].'/template/incluce/nav.php');
?>
<!--各种验证代码放此处-->
<meta property="qc:admins" content="157011404545576016717746375" />

<!--幻灯片样式 放在temp2_index.css 之前-->
<link href="/css/temp2_swiper.css" rel="stylesheet" type="text/css" />
<link href="/css/temp2_banner.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="/js/temp2_swiper.js"></script>

<!--首页样式-->
<link href="/bootstrap/css/pages/login.css" rel="stylesheet" type="text/css"/>
<link href="/css/temp2_index.css" rel="stylesheet" type="text/css" />
<script src="/bootstrap/plugins/bootstrap/js/bootstrap.min.js" type="text/javascript"></script>


<!--登录框-->
<div class="xa_container">
    <link href="/css/temp2_login.css" rel="stylesheet" type="text/css">
    <script>document.write('<script src="/template/incluce/indexLogin.php?t='+Math.random()+'"><'+'/script>');</script><!--登录JS-->
</div>

<!--幻灯片-开始-->
<div class="xa_banner">
 <div class="swiper-container">
    <div class="swiper-wrapper">
<?php 
$classid='38';//幻灯片
$field=",hdimg{$LT}";//特别字段(以,开头)
$where="and isgood=0 and hdimg{$LT}<>''";//特别条件(以and开头) 不设为推荐表示是电脑版
$limit=6;
$sqllx='article';require($_SERVER['DOCUMENT_ROOT'].'/template/call/sql.php');


//输出按钮数量
/*
$rc=mysqli_affected_rows($xingao);
for ($i=1;$i<=$rc;$i++){echo '<a href="javascript:void(0)">'.$i.'</a>';}
*/

while($rs=$sql->fetch_array())
{
?>
            <div class="swiper-slide">
            	<span style="background: url('<?=ImgAdd($rs['hdimg'.$LT])?>') center top no-repeat"></span>
                
                 <?php if($rs['url'.$LT]){?>
                 <!--支持内部HTML代码-->
                 <div class="xa_banner_text">
                     <a href="<?=cadd($rs['url'.$LT])?>" class="xa_more" target="_blank"><?=$LG['ViewDetailed']//点击查看详细?></a>
                 </div>
                 <?php }?>
                 
           </div>
<?php 
}
?>
        </div>
        <div class="swiper-pagination"></div>
        <div class="swiper-button-next" id="btn_next"></div>
        <div class="swiper-button-prev" id="btn_prev"></div>
    </div>
    
   
    
    <script>
	//参数说明:http://www.cnblogs.com/xinlinux/p/4720198.html
    var swiper = new Swiper('.swiper-container', {
        pagination: '.swiper-pagination',
        nextButton: '.swiper-button-next',
        prevButton: '.swiper-button-prev',
        paginationClickable: true,
		speed:1000,//滑动速度(毫秒)
        centeredSlides: true,
        autoplay:3000,//自动切换的时间间隔(毫秒)
        autoplayDisableOnInteraction: false,
		loop: true
    });
	$(function(){
        $(".swiper-container").hover(function(){
            $("#btn_prev,#btn_next").fadeIn()
            },function(){
            $("#btn_prev,#btn_next").fadeOut()
            })
    });
    </script>          
   </div>

<!--幻灯片-结束-->


<div class="xa_search">
    <div class="xa_container xa_pp">
        <?php require_once($_SERVER['DOCUMENT_ROOT'].'/yundan/call/status_form.php');?>
        <?php require_once($_SERVER['DOCUMENT_ROOT'].'/yundan/call/price_form.php');?>
    </div>
</div>


<div class="xa_container xapt">
<?php 
$classid=23;//转运公告
$crs=ClassData($classid);
?>		
 <div class="xa_lmtit"><?=cadd($crs['name'.$LT])?></div>
<?php 
$field='';//特别字段(以,开头)
$where='';//特别条件(以and开头)
$limit=4;$i=0;
$sqllx='article';require($_SERVER['DOCUMENT_ROOT'].'/template/call/sql.php');
while($rs=$sql->fetch_array())
{$i++;
?>
  <a href="<?=$rs['url'.$LT]?cadd($rs['url'.$LT]):pathLT($rs['path'])?>" target="_blank"  >
  <div class="xa_gg_list">
      <div class="xa_gg_list_left">0<?=$i?><span><?=DateYmd($rs['edittime'],3,$rs['addtime'])?> </span></div>
      <div class="xa_gg_list_right">
          <h2 style="color:<?=cadd($rs['titlecolor'])?>"><?=leng($rs['title'.$LT],40,"...");?></h2>
          <p><?=leng($rs['intro'.$LT],100,"...");?></p>
      </div> 
  </div>
  </a>

<?php
}
?>
  
  <div class="clear"></div>
  <div class="xa_more"><a href="<?=pathLT($crs['path'])?>" target="_blank"><?=$LG['index.3'];//查看更多?></a></div>
</div>




    <div class="youshi">
    	<div class="xa_lmtit xa_mb"><?=$LG['index.1'];//转运流程?></div>
        <div class="xa_container">
        	<div class="xa_liucheng_list xa_bg1">
            <i><img src="/images/temp2/tb1.png"></i>
        	<div class="xa_liucheng_text"><?=$LG['index.4'];//Step 1<span>注册会员</span><p>获取您的个人仓库地址</p>?></div>
        	</div>
        	<div class="xa_liucheng_jg"></div>
            <div class="xa_liucheng_list xa_bg2">
            <i><img src="/images/temp2/tb2.png"></i>
        	<div class="xa_liucheng_text"><?=$LG['index.5'];//Step 2<span>电商购物</span><p>填写个人仓库地址为收货地址</p>?></div>
        	</div>
        	<div class="xa_liucheng_jg"></div>
            <div class="xa_liucheng_list xa_bg3">
            <i><img src="/images/temp2/tb3.png"></i>
        	<div class="xa_liucheng_text"><?=$LG['index.6'];//Step 3<span>寄到仓库</span><p>可对仓库中包裹申请操作服务</p>?></div>
        	</div>
        	<div class="xa_liucheng_jg"></div>
            <div class="xa_liucheng_list xa_bg4">
            <i><img src="/images/temp2/tb4.png"></i>
        	<div class="xa_liucheng_text"><?=$LG['index.7'];//Step 4<span>下单发货</span><p>等待包裹寄到您手中</p>?></div>
        	</div>
        </div>
    </div>

<!-- 有该语种图片时,自动用该语种否则用默认-->
<style>
.xa_tu1 { height: 311px; background: url(<?=HaveFile('/images/temp2/bt1'.$LT.'.jpg')?'/images/temp2/bt1'.$LT.'.jpg':'/images/temp2/bt1.jpg'?>) no-repeat right top; position: relative; }
.xa_tu2 { height: 311px; background: url(<?=HaveFile('/images/temp2/bt2'.$LT.'.jpg')?'/images/temp2/bt2'.$LT.'.jpg':'/images/temp2/bt2.jpg'?>) no-repeat 10px top; position: relative; }
.xa_tu3 { height: 311px; background: url(<?=HaveFile('/images/temp2/bt3'.$LT.'.jpg')?'/images/temp2/bt3'.$LT.'.jpg':'/images/temp2/bt3.jpg'?>) no-repeat right top; position: relative; }
</style>


    <div class="xa_youshi">
    	<div class="xa_lmtit xa_mb"><?=$LG['index.2'];//我们的优势?></div>
        <div class="xa_container">
        	<div class="xa_tu1">
        	<div class="xa_mm xa_tu_text1">
           	    <?=$LG['index.8']?>
<!--            	<p>专业的仓储物流系统 高效快捷</p>
                <p>经验丰富的仓库员工 全年无休为您服务</p>
                <p>多项转运增值服务 贴心周到</p>
-->            </div>
			<div class="xa_tu_pic fr"><img src="/images/temp2/tu1.jpg"></div>
        </div>
        <div class="xa_tu2">
        	<div class="xa_mm xa_tu_text2">
           		<?=$LG['index.9']?>
<!--            	<p>细致考量每一位客户的需求</p>
                <p>帮助客户安全把包裹送达目的</p>
                <p>高效、体贴、全心为您服务 超乎您的期待</p>
-->            </div>
			<div class="xa_tu_pic fl"><img src="/images/temp2/tu2.jpg"></div>
        </div>
        <div class="xa_tu3">
        	<div class="xa_mm xa_tu_text3">
           		<?=$LG['index.10']?>
<!--            	<p>多种增值服务免费提供 多种运输渠道任您选择</p>
                <p>经济快捷各取所需</p>
                <p>充值赠送、积分抵消运费等多项优惠结合</p>
-->            </div>
			<div class="xa_tu_pic fr"><img src="/images/temp2/tu3.jpg"></div>
        </div>
        </div>
    </div>





<?php if($off_mall){?>
<div class="xa_sp">
   <div class="xa_lmtit xa_ff"><?=$LG['front.70'];//推荐商品?></div>
   <div class="scrollBox" style="margin:0 auto">
			<div class="ohbox">
					<ul class="piclist">
                    <li>
<?php 
$classid='5';//在线商城
$field='';//特别字段(以,开头)
$where='';//特别条件(以and开头)
$limit=24;$i=0;
$sqllx='mall';require($_SERVER['DOCUMENT_ROOT'].'/template/call/sql.php');
while($rs=$sql->fetch_array())
{$i++;
?>
    <a href="/mall/show.php?mlid=<?=$rs['mlid']?>" target="_blank">
        <div class="xa_xiaotu"> 
        <div class="xa_xiaotu_img"><img src="<?=ImgAdd($rs['titleimg'.$LT])?>" onload="AutoResizeImage(256,256,this)"></div>
        <div class="xa_zz xa_xiaotu_zhezhao">
        <p><?=leng($rs['title'.$LT],80)?></p>
        <p class="xa_jg"><?=spr($rs['price']).$XAmc?></p>
        </div> 
        </div>
    </a>
<?php
	if($i==2){echo '</li><li>';$i=0;}
	
}
?>
					</li>
                    </ul>
			</div>
			<div class="pageBtn">
				<span class="prev">&nbsp;</span>
				<span class="next">&nbsp;</span>
                <ul class="list">
<?php //输出按钮数量:必须
$rc=mysqli_affected_rows($xingao)/8;
for ($i=1;$i<=$rc;$i++){echo '<li>'.$i.'</li>';}
?>
                </ul>
			</div>
	</div>
	<script type="text/javascript">jQuery(".scrollBox").slide({ titCell:".list li", mainCell:".piclist", effect:"left",vis:4,scroll:2,delayTime:800,trigger:"click",easing:"easeOutCirc"});</script>
    <div class="clear"></div>
        <div class="xa_more"><a href="<?=$m?>/mall/list.php" target="_blank"><?=$LG['index.3'];//查看更多?></a></div>
    </div>
<?php }?>    
    
    
    
    
    
    
<?php if($off_shaidan){?>
<div class="xa_pinglun">
<?php 
$classid='25';//晒单评价
$crs=ClassData($classid);
?>		
 <div class="xa_lmtit"><?=cadd($crs['name'.$LT])?></div>
 <div class="xa_container">
    <div class="m_wnews" style="margin:0px auto 60px">
    <div id="miniNewsRegion">
    
<?php 
$limit=6;
$where="checked='1' and types='0' ";
if($ON_shaidan_language&&$LT!='CN'){$where.=" and language='{$LT}'";}
elseif($ON_shaidan_language&&$LT=='CN'){$where.=" and (language='{$LT}' or language='')";}

$order=' order by edittime desc,sdid desc';//默认排序
$query="select * from shaidan where {$where} and classid='{$classid}' {$order} limit {$limit}";
$sql=$xingao->query($query);
while($rs=$sql->fetch_array())
{
?>
    
            <div class="xa_pingjia_list">
           		<a href="<?=pathLT($rs['path'])?>" target="_blank">
                <div class="xa_pingjia_left fl"><img src="<?=FeData('member','img',"userid='{$rs['userid']}'")?>" width="125" height="125" ></div>
                <div class="xa_pingjia_text">
                    <i class="xa_yh1"><img src="/images/temp2/yh1.png"></i>
                <p><?=leng($rs['content'],275,"...")?> <i><img src="/images/temp2/yh2.png"></i></p>
                    <div class="xa_dh"><?=substr_cut($rs['username'],3)?>  <?=$LG['awb']//运单号?>：<?=substr_cut($rs['ydh'],3)?></div>
                </div>
                
                <?php
				$arr=$rs['img'];
				if($arr)
				{
					if(!is_array($arr)){$arr=explode(",",$arr);}//转数组
					$i=0;
					foreach($arr as $arrkey=>$value)
					{$i++;
						?><div class="xa_pingjia_pic"><img src="<?=$value?>" width="195" height="152"></div><?php
					 if($i==2){break;}
					}
				}
				?>
            	</a>
            </div>
<?php 
}
?>
            
    </div>
    
</div>

<script type="text/javascript">
jQuery(".m_wnews").slide({ mainCell:"#miniNewsRegion", effect:"topLoop", autoPlay:true});
</script>
    

    
 </div>
     <div class="clear"></div>
    <div class="xa_more"><a href="<?=pathLT($crs['path'])?>" target="_blank"><?=$LG['index.3'];//查看更多?></a></div>
    <div class="xa_fj"><img src="/images/temp2/fj.png"></div>
   
</div>    
<?php }?>


 

<?php 
require_once($_SERVER['DOCUMENT_ROOT'].'/js/checkJS.php');//通用验证
require_once($_SERVER['DOCUMENT_ROOT'].'/template/incluce/footer.php');
?>


<script type="text/javascript">
//首页效果
$(document).ready(function(e)
{
	$(".xa_gg_list").hover(
		function(){
			$(this).addClass("xa_cur");
			},function(){
			$(this).removeClass("xa_cur");
			}
	);
	$(".xa_mm").hover(
		function(){
			$(this).css("color","#c43e27");
			},
			function(){
			$(this).css("color","#4B4B4C");
			}
	);
	$(".xa_zz").css("display","none");
	$(".xa_xiaotu").hover(
		function(){
			$(this).find(".xa_zz").addClass("animated flipInX");
			$(this).find(".xa_zz").css("display","block");
			},
		function(){
			$(this).find(".xa_zz").removeClass("animated flipInX");
			$(this).find(".xa_zz").css("display","none");
			}
	);
});


//顶部导航背景透明
$(function(){  
    var scrollTop=0;
    setInterval(function(){
        scrollTop=$(document).scrollTop();
        if(scrollTop<100){  
            document.getElementById('xa_nav_scroll').style.backgroundImage="url(/images/nav_bg_tran.png)";//<div id="xa_nav_scroll">滚动条位置小于100时,显示透明背景
        }else{  
            document.getElementById('xa_nav_scroll').style.backgroundImage="url(/images/nav_bg.png)";//否则显示不透明背景
        }
    },0)
	});
</script>
