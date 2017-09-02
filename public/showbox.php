<!-- 
点击支付/包裹操作弹出 (bootstrap 的AJAX方式问题太多,比如某次打开错误后如果没有刷新,就会一直打开错误;另外不能放到iframe调用,因为无法获取href值,只能获取参数来重新生成链接给iframe) 

调用方法1:
    <a href="/4.php" class="showdiv" target="XingAobox">转给其他会员</a>
    require_once($_SERVER['DOCUMENT_ROOT'].'/public/showbox.php');

调用方法2:
    <div class="showdiv">
    <a href="/4.php" target="XingAobox">转给其他会员</a>
    </div>
    require_once($_SERVER['DOCUMENT_ROOT'].'/public/showbox.php');
    
AJAX输出,就要用方法2,如:
    <div class="showdiv">
    <span id="yd_show_msg"></span>
    </div>

-->
<?php 
	//外部参数
	//$SB_OFFRefresh=0;//1禁止返回刷新
	//$SB_JSEvent='';//点击关闭时触发JS事件
?>

<style>
<!--10.15更新:替换-->
.modal-dialog {width: 700px;}
.modal-body{ padding-bottom:0px;}
.modal-footer{ margin-top:0px;}
.modal-wide{min-width:700px;}
</style>

<script type="text/javascript">
$(function(){
    $(".showdiv").click(function(){
        var box =350;
        var th= $(window).scrollTop()+$(window).height()/1.6-box;<!--上下边距设置-->
        var h =document.body.clientHeight;
        var rw =$(window).width()/2-box;<!--左右边距设置-->
		
		if(th<0){th=0;}
		if(rw<0){rw=0;}
		
        $(".showbox").animate({top:th,opacity:'show',width:700,height:600,left:rw},500);
        $("#box_bg").css({
            display:"block",height:$(document).height()
        });
        return true;
    });
    $(".showbox .close").click(function(){
        $(this).parents(".showbox").animate({top:0,opacity: 'hide',width:0,height:0,right:0},500);
        $("#box_bg").css("display","none");
           windowl.location.href=window.location.href;
    });
});
</script>
<div class="showbox" style="display:none;">
	<div class="modal-dialog modal-wide">
		<div class="modal-content">
			<div class="modal-header">
				 <button type="button" class="close" id="XAclose" title="<?=$LG['close']?>" onclick="<?=$SB_JSEvent?>"></button>
			</div>
			
            <div class="modal-body">
                <iframe id="XingAobox" name="XingAobox" width="100%" height="0" frameborder="0" scrolling="auto"></iframe>
                <script>
                $(function(){ iframeHeight('XingAobox'); });
                </script>
            </div>
            
			<div class="modal-footer">
				<button type="button" class="btn btn-default"  onclick="<?=$SB_JSEvent?><?=!$SB_OFFRefresh?'javascript:window.location.href=window.location.href;':''?> $('#XAclose').click();"> <?=!$SB_OFFRefresh?$LG['Tool.closeRefresh']:$LG['close']?> </button>
			</div>
            
		</div>
	</div>
</div>
<div id="box_bg"></div>