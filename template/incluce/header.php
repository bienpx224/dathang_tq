<?php 
if($rs['seokey']){$seokey=striptags($rs['seokey']);}
elseif($rs['seokey'.$LT]){$seokey=striptags($rs['seokey'.$LT]);}
elseif($cr['seokey']){$seokey=striptags($cr['seokey']);}
elseif($cr['seokey'.$LT]){$seokey=striptags($cr['seokey'.$LT]);}
else{$seokey=striptags($sitekey);}

if($rs['intro'.$LT]){$seointro=striptags($rs['intro'.$LT]);}
elseif($rs['intro'.$LT]){$seointro=striptags($rs['intro'.$LT]);}
elseif($cr['intro'.$LT]){$seointro=striptags($cr['intro'.$LT]);}
elseif($cr['intro'.$LT]){$seointro=striptags($cr['intro'.$LT]);}
else{$seointro=striptags($sitetext);}
?>
<meta name="keywords" content="<?=$seokey?>" />
<meta name="description" content="<?=$seointro?>" />
<title><?=$headtitle?>-<?=cadd($sitename)?>|<?=cadd($sitetitle)?></title>

<!--载入效果-->
<script src="/js/pace.js"></script>
<link href="/css/pace-theme-barber-shop.css" rel="stylesheet" />

<?php if(!$index){?>

    <!-- BEGIN GLOBAL MANDATORY STYLES -->
    <link href="/bootstrap/plugins/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css"/>
    <link href="/bootstrap/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css"/>
    <link href="/bootstrap/plugins/uniform/css/uniform.default.css" rel="stylesheet" type="text/css"/>
    <!-- END GLOBAL MANDATORY STYLES -->
    
    <!-- BEGIN THEME STYLES -->
    <link href="/bootstrap/css/style-conquer.css" rel="stylesheet" type="text/css"/>
    <link href="/bootstrap/css/style.css" rel="stylesheet" type="text/css"/>
    <link href="/bootstrap/css/style-responsive.css" rel="stylesheet" type="text/css"/>
    <link href="/bootstrap/css/plugins.css" rel="stylesheet" type="text/css"/>
    <link href="/bootstrap/css/custom.css" rel="stylesheet" type="text/css"/>
    <!-- END THEME STYLES -->
    
    <link href="/bootstrap/css/pages/profile.css" rel="stylesheet" type="text/css"/><!-- 左边三级栏目导航 -->
	<link href="/css/template.css" rel="stylesheet" type="text/css"/><!--内页模板-->
<?php }?>
	

<!--模板2-->
<link href="/css/base.css" rel="stylesheet" type="text/css" />
<link href="/css/temp2_public.css" rel="stylesheet" type="text/css" />
<link href="/css/temp2_animate.min.css" rel="stylesheet" type="text/css">

<!--公用-->
<link href="/css/xingao.css" rel="stylesheet" type="text/css"/>
<script src="/bootstrap/plugins/jquery-1.10.2.min.js" type="text/javascript"></script>
<script type="text/javascript" src="/js/jquery.SuperSlide.2.1.2.js"></script>

<?php if($LT!='CN'){?><link href="/css/otherLanguage.css" rel="stylesheet" type="text/css"/><?php }?>
<?php if(!$ism){?><script src="/m/jump.php"></script><?php }?><!--自动转向对应版本-->
<?php if(!$client){?><script src="/client/jump.php"></script><?php }?><!--判断是否来自会员的客户端-->

<script src="/js/previewImage.js"></script>
<!-- 图片自动按比例显示<img src="/images/trans_img_2.png" width="0" height="0" onload="AutoResizeImage(190,150,this)" /> 190,150是宽高 -->

<!--[if IE]>
    <link href="/css/ie.css" rel="stylesheet" type="text/css"/>
<![endif]-->

<!--[if IE 6]>
    <link href="/css/ie6.css" rel="stylesheet" type="text/css"/>
    <style type="text/css"> img, .container{behavior: url(/js/ie-css3.htc);} </style> 
    <script type="text/javascript" src="/js/DD_belatedPNG_0.0.8a.js"></script>
    <script type="text/javascript">
        DD_belatedPNG.fix('div,img,a,input');
    </script>
<![endif]-->

</head>
<body>