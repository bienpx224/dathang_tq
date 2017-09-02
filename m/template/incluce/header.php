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

<!-- BEGIN GLOBAL MANDATORY STYLES -->
<link href="/bootstrap/plugins/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css"/>
<link href="/bootstrap/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css"/>
<link href="/bootstrap/plugins/uniform/css/uniform.default.css" rel="stylesheet" type="text/css"/>
<!-- END GLOBAL MANDATORY STYLES -->

<link href="/m/css/template.css" rel="stylesheet" type="text/css">
<link href="/css/xingao.css" rel="stylesheet" type="text/css"/>
<script src="/bootstrap/plugins/jquery-1.10.2.min.js" type="text/javascript"></script> 

<?php if($LT!='CN'){?><link href="/m/css/otherLanguage.css" rel="stylesheet" type="text/css"/><?php }?>
<?php if(!$ism){?><script src="/m/jump.php"></script><?php }?><!--自动转向对应版本-->
<?php if(!$client){?><script src="/client/jump.php"></script><?php }?><!--判断是否来自会员的客户端-->

<script src="/js/previewImage.js"></script>
<!--
 图片自动按比例显示
 调用：<img src="/images/trans_img_2.png" width="0" height="0" onload="AutoResizeImage(190,150,this)" /> 190,150是宽高
-->

<!--各类特效JS:必须有,放在footer.php无效-->
<script type="text/javascript" src="/js/jquery.SuperSlide.2.1.2.js"></script>

</head>
<body>
