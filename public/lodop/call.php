<!--
    调用:
    < ?php 
    //需要放在模板的后面,模板中需要有$print_i(没有表示模板不支持插件打印)
    $button_print=1;//显示按钮信息
    $auto_print=1;//JS自动打印
    require_once($_SERVER['DOCUMENT_ROOT'].'/public/lodop/call.php');
	?>
-->

<!-- 全局强制样式-开始 -->
<link href="/bootstrap/plugins/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css"/><!--字体-->
<link href="/bootstrap/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css"/><!--主样式-->
<link href="/bootstrap/plugins/uniform/css/uniform.default.css" rel="stylesheet" type="text/css"/><!--表单-->
<!-- 全局强制样式-结束 -->

<link rel="stylesheet" type="text/css" href="/css/xingao.css">
<link rel="stylesheet" type="text/css" href="/xingao/yundan/print/style/print.css">
<link rel="stylesheet" type="text/css" href="/xingao/baoguo/print/style/print.css">

<!--如果没有载入过JQ,就载入-->
<script>!window.jQuery && document.write("<script src=\"/bootstrap/plugins/jquery-1.10.2.min.js\">"+"</scr"+"ipt>");</script>


<!--调用控件-->
<script language="javascript" src="/public/lodop/LodopFuncs.js"></script>
<object  id="LODOP_OB" classid="clsid:2105C259-1E0C-4534-8141-A753534CB4CA" width=0 height=0> 
<embed id="LODOP_EM" type="application/x-print-lodop" width=0 height=0></embed>
</object>

<!--设置本页参数-->
<script language="javascript" type="text/javascript">
	//页面加载完之后再调用打印
	if (LODOP.webskt && LODOP.webskt.readyState==1)
	{   
		var LODOP=getLodop();//载入控件(必须)
	}
	
	//默认:打印预览
	function prn1_preview() {    
		CreateOneFormPage();    
		LODOP.PREVIEW();    
	};
	
	
	//默认:单打
	function prn1_print() {        
		CreateOneFormPage();
		LODOP.PRINT(); 
	};
	
	
	//单打内容
	function CreateOneFormPage(){
		LODOP=getLodop();  
		
		LODOP.PRINT_INIT("兴 奥 转 运 打 印");
		LODOP.ADD_PRINT_HTM("0%","0%","100%","100%",document.getElementById("xingao_print_content").innerHTML);
	};
	
</script>






<script language="javascript" type="text/javascript">
	//兴奥:打印预览
	function xa_preview() {    
		CreatePrintPage1();    
		LODOP.PREVIEW();    
	};
	
	//兴奥:连打/自动打印
	function xa_print() 
	{        
		<?php 
		if($print_i)
		{
			for ($tag=1; $tag<=$print_i; $tag++)
			{
				?>
					printOK=LODOP.PRINT(CreatePrintPage<?=$tag?>());
				<?php 
			}
		}?>
		//alert(printOK);
	};
	
	
	//兴奥:模板设计
	function xa_template() 
	{        
		CreatePrintPage1();
		LODOP.PRINT_DESIGN();
	};
	
	
	//兴奥:新模板设计
	function xa_template_new() 
	{        
		LODOP.PRINT_DESIGN();
	};
		
	//选择打印机
	function SelectAsDefaultPrinter() 
	{
		if (LODOP.CVERSION) 
		{
			LODOP.On_Return=function(TaskID,Value){if(Value>=0){xa_print();}else {alert("选择失败！");}};
			LODOP.SELECT_PRINTER();
			return;
		};
		if (LODOP.SELECT_PRINTER()>=0){xa_print();}else {alert("选择失败！");}
	};
	

</script>

<?php if($button_print){?>
<button type="button" class="btn btn-info" onClick="SelectAsDefaultPrinter();" ><i class="icon-print"></i> 选择打印机并打印</button>
<button type="button" class="btn btn-primary" onClick="xa_print();" ><i class="icon-print"></i> 用默认或已选的打印机 打印 </button>
<br><br>
<a href="javascript:xa_preview();" class="gray_prompt2 gray">打印预览</a>
<a href="javascript:xa_template();" class="gray_prompt2 gray">模板设计</a>
<a href="javascript:xa_template_new();" class="gray_prompt2 gray">新模板</a>
<?php }?>

<script>
<?php if($auto_print||$auto_print_jq||$auto_print_js){//$auto_print_jq||$auto_print_js 是为兼容旧版,新版只用$auto_print?>
	
	$(function(){	
	
		//以下代码是表示载入完后再执行打印
		if (needCLodop()) {
			window.On_CLodop_Opened=function()
			{
				xa_print();
				window.On_CLodop_Opened=null;
			};	
		} else {
			window.onload = function(){
				xa_print();
			}; 	
		}
		
	});
<?php }?>
</script>
