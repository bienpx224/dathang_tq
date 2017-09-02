<title><?=$headtitle?>-<?=cadd($sitename)?></title>

<!--载入效果-->
<script src="/js/pace.js"></script>
<link href="/css/pace-theme-flash.css" rel="stylesheet" />


<!-- BEGIN GLOBAL MANDATORY STYLES -->
<link href="/bootstrap/plugins/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css"/>
<link href="/bootstrap/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css"/>
<link href="/bootstrap/plugins/uniform/css/uniform.default.css" rel="stylesheet" type="text/css"/>
<!-- END GLOBAL MANDATORY STYLES -->


<!-- BEGIN PAGE LEVEL STYLES -->
<!--<link rel="stylesheet" type="text/css" href="/bootstrap/plugins/bootstrap-fileupload/bootstrap-fileupload.css" />-->
<link rel="stylesheet" type="text/css" href="/bootstrap/plugins/gritter/css/jquery.gritter.css" />
<link rel="stylesheet" type="text/css" href="/bootstrap/plugins/select2/select2_conquer.css" />
<link rel="stylesheet" type="text/css" href="/bootstrap/plugins/clockface/css/clockface.css" />
<!--<link rel="stylesheet" type="text/css" href="/bootstrap/plugins/bootstrap-wysihtml5/bootstrap-wysihtml5.css" />-->
<link rel="stylesheet" type="text/css" href="/bootstrap/plugins/bootstrap-datepicker/css/datepicker.css" />
<link rel="stylesheet" type="text/css" href="/bootstrap/plugins/bootstrap-timepicker/compiled/timepicker.css" />
<link rel="stylesheet" type="text/css" href="/bootstrap/plugins/bootstrap-colorpicker/css/colorpicker.css" />
<link rel="stylesheet" type="text/css" href="/bootstrap/plugins/bootstrap-daterangepicker/daterangepicker-bs3.css" />
<link rel="stylesheet" type="text/css" href="/bootstrap/plugins/bootstrap-datetimepicker/css/datetimepicker.css" />
<link rel="stylesheet" type="text/css" href="/bootstrap/plugins/jquery-multi-select/css/multi-select.css" />
<link rel="stylesheet" type="text/css" href="/bootstrap/plugins/bootstrap-switch/static/stylesheets/bootstrap-switch-conquer.css"/>
<link rel="stylesheet" type="text/css" href="/bootstrap/plugins/jquery-tags-input/jquery.tagsinput.css" />
<link rel="stylesheet" type="text/css" href="/bootstrap/plugins/bootstrap-markdown/css/bootstrap-markdown.min.css">
<!--<link href="/bootstrap/plugins/bootstrap-modal/css/bootstrap-modal.css" rel="stylesheet" type="text/css"/>--><!--弹窗:主样式已含有,不能再加(引起错误:点击时位置改变,显示时无滚动条,无法查看全部内容)-->
<link rel="stylesheet" type="text/css" href="/bootstrap/plugins/bootstrap-toastr/toastr.min.css" />
<link rel="stylesheet" href="/bootstrap/plugins/data-tables/DT_bootstrap.css" />
<!-- END PAGE LEVEL STYLES -->

<!-- BEGIN THEME STYLES -->
<link href="/bootstrap/css/style-conquer.css" rel="stylesheet" type="text/css"/>
<link href="/bootstrap/css/style.css" rel="stylesheet" type="text/css"/>
<link href="/bootstrap/css/style-responsive.css" rel="stylesheet" type="text/css"/>
<link href="/bootstrap/css/plugins.css" rel="stylesheet" type="text/css"/>
<link href="/bootstrap/css/pages/error.css" rel="stylesheet" type="text/css"/>
<link href="/bootstrap/css/custom.css" rel="stylesheet" type="text/css"/>
<!-- END THEME STYLES -->

<link href="/css/template.css" rel="stylesheet" type="text/css"/>
<link href="/css/xingao.css" rel="stylesheet" type="text/css"/>
<link href="/css/member.css" rel="stylesheet" type="text/css"/>
<link href="/bootstrap/css/themes/<?=$theme_member?>" rel="stylesheet" type="text/css" id="style_color"/>

<!--模板2-->
<link href="/css/base.css" rel="stylesheet" type="text/css" />
<link href="/css/temp2_public.css" rel="stylesheet" type="text/css" />
<link href="/css/temp2_animate.min.css" rel="stylesheet" type="text/css">

<!--[if IE]>
    <link href="/css/ie.css" rel="stylesheet" type="text/css"/>
<![endif]-->

<script src="/bootstrap/plugins/jquery-1.10.2.min.js" type="text/javascript"></script> 
<script src="/js/jquery.SuperSlide.2.1.2.js"></script>
<script src="/js/clipboard.min.js" type="text/javascript"></script><!--一键复制-->

<?php if($LT!='CN'){?><link href="/css/otherLanguage.css" rel="stylesheet" type="text/css"/><?php }?>

<?php 
//验证是否是移动版
if($_SESSION['isMobile']){
	$ism=1;$m='/m';
	echo '<link href="/css/xingao_m.css" rel="stylesheet" type="text/css"/>';
}
?>

</head>
<body><!-- class="page-header-fixed" 头部固定的高度(top距离)--> 

<?php 
if(!$alonepage)
{	
	require_once($_SERVER['DOCUMENT_ROOT'].'/xamember/incluce/nav.php');
}elseif($alonepage==1){
?>	
	<!--单页-->
	<style>
	body 
	{
		background-color: #ffffff !important;/*本页弹窗操作也用到,必须白色*/
		margin:10px !important;
	}
    </style>
<?php }elseif($alonepage==2){ ?>
	<!--框架页-->
	<style>html{overflow-x:hidden;}</style>
	<style>body{background-color: #FAFAFA !important;}</style>
<?php }?>

<style>
.xa_header_bg { margin-top:45px;}
.xa_container{margin-left:10px;}
</style>
