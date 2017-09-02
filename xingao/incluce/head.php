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
?>
<title><?=$headtitle?>-<?=cadd($sitename)?><?php if($off_jishu){echo '-'.$jishu.$banben; }?></title>

<!--载入效果-->
<script src="/js/pace.js"></script>
<link href="/css/pace-theme-flash.css" rel="stylesheet" />


<!-- 全局强制样式-开始 -->
<link href="/bootstrap/plugins/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css"/><!--字体-->
<link href="/bootstrap/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css"/><!--主样式-->
<link href="/bootstrap/plugins/uniform/css/uniform.default.css" rel="stylesheet" type="text/css"/><!--表单-->
<!-- 全局强制样式-结束 -->

<!-- 页面样式-开始 -->
<!--<link rel="stylesheet" type="text/css" href="/bootstrap/plugins/bootstrap-fileupload/bootstrap-fileupload.css" />--><!--文件上传（文件小）-->
<link rel="stylesheet" type="text/css" href="/bootstrap/plugins/gritter/css/jquery.gritter.css" />
<link rel="stylesheet" type="text/css" href="/bootstrap/plugins/select2/select2_conquer.css" /><!--单选下拉-->
<link rel="stylesheet" type="text/css" href="/bootstrap/plugins/clockface/css/clockface.css" /><!--钟面-->
<!--<link rel="stylesheet" type="text/css" href="/bootstrap/plugins/bootstrap-wysihtml5/bootstrap-wysihtml5.css" />-->
<link rel="stylesheet" type="text/css" href="/bootstrap/plugins/bootstrap-datepicker/css/datepicker.css" /><!--日期选择器 -->
<link rel="stylesheet" type="text/css" href="/bootstrap/plugins/bootstrap-timepicker/compiled/timepicker.css" /><!--时间选择器 -->
<link rel="stylesheet" type="text/css" href="/bootstrap/plugins/bootstrap-colorpicker/css/colorpicker.css" /><!--颜色选择器 -->
<link rel="stylesheet" type="text/css" href="/bootstrap/plugins/bootstrap-daterangepicker/daterangepicker-bs3.css" /><!--日期范围选择器 -->
<link rel="stylesheet" type="text/css" href="/bootstrap/plugins/bootstrap-datetimepicker/css/datetimepicker.css" /><!--日期时间选择器 -->
<link rel="stylesheet" type="text/css" href="/bootstrap/plugins/jquery-multi-select/css/multi-select.css" /><!--多选下拉-->
<link rel="stylesheet" type="text/css" href="/bootstrap/plugins/bootstrap-switch/static/stylesheets/bootstrap-switch-conquer.css"/><!--单选开关-->
<link rel="stylesheet" type="text/css" href="/bootstrap/plugins/jquery-tags-input/jquery.tagsinput.css" /><!--标签输入-->
<link rel="stylesheet" type="text/css" href="/bootstrap/plugins/bootstrap-markdown/css/bootstrap-markdown.min.css"><!--减价（文件小）-->

<!--<link href="/bootstrap/plugins/bootstrap-modal/css/bootstrap-modal.css" rel="stylesheet" type="text/css"/>--><!--弹窗:已有,不能再加(引起错误:点击时位置改变,显示时无滚动条,无法查看全部内容)-->

<link rel="stylesheet" href="/bootstrap/plugins/data-tables/DT_bootstrap.css" /><!--数据表格（文件小）-->
<link rel="stylesheet" type="text/css" href="/bootstrap/plugins/bootstrap-toastr/toastr.min.css" /><!--页面中任意位置提示框-->
<!-- 页面样式-结束 -->


<!-- 主题模板样式-结束 -->
<link href="/bootstrap/css/style-conquer.css" rel="stylesheet" type="text/css"/>
<link href="/bootstrap/css/style.css" rel="stylesheet" type="text/css"/>
<link href="/bootstrap/css/style-responsive.css" rel="stylesheet" type="text/css"/><!--响应 -->
<link href="/bootstrap/css/plugins.css" rel="stylesheet" type="text/css"/><!--插件-->
<link href="/bootstrap/css/pages/error.css" rel="stylesheet" type="text/css"/><!--错误-->
<link href="/bootstrap/css/custom.css" rel="stylesheet" type="text/css"/><!--自定义-->
<!-- 主题模板样式-结束 -->

<link href="/css/xingao.css" rel="stylesheet" type="text/css"/>
<link href="/css/manage.css" rel="stylesheet" type="text/css"/>
<link href="/bootstrap/css/themes/<?=$theme_manage?>" rel="stylesheet" type="text/css" id="style_color"/>

<!--[if IE]>
    <link href="/css/ie.css" rel="stylesheet" type="text/css"/>
<![endif]-->

<?php if($LT!='CN'){?><link href="/css/otherLanguage.css" rel="stylesheet" type="text/css"/><?php }?>


<script src="/bootstrap/plugins/jquery-1.10.2.min.js" type="text/javascript"></script> 
<script src="/js/clipboard.min.js" type="text/javascript"></script><!--一键复制-->
</head>




<?php if(!$alonepage){?>
    <body class="page-header-fixed"><!--头部固定的高度(top距离)-->
    <?php require_once($_SERVER['DOCUMENT_ROOT'].'/xingao/incluce/nav.php');?>
<?php }elseif($alonepage==1){?>	
	<!--单页:登录页不能有class="page-header-fixed"-->
	<body>
	<style>
	body 
	{
		background-color: #ffffff !important;/*本页弹窗操作也用到,必须白色*/
		margin:10px !important;
	}
    </style>
<?php }elseif($alonepage==2){ ?>
	<!--框架页-->
	<style>html {overflow-x:hidden;}</style>
	<style>body {background-color: #FAFAFA !important;}</style>
<?php }?>
